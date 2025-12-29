<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard | TeleHealth</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100" x-data="{ openModal: false, selectedDoctor: '' }">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#1e293b] text-white flex flex-col fixed h-full">
            <div class="p-6 flex items-center gap-2 border-b border-gray-700">
                <div class="w-8 h-8 bg-white text-[#1e293b] rounded flex items-center justify-center font-bold">T</div>
                <h1 class="text-xl font-bold">TeleHealth<span class="text-orange-500">)</span></h1>
            </div>

            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li><a href="#" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700 bg-gray-700"><i class="icon">🏠</i> Dashboard</a></li>
                    <li><a href="#" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700"><i class="icon">🔍</i> Find Doctors</a></li>
                    <li><a href="#" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700"><i class="icon">📅</i> My Appointments</a></li>
                    <li><a href="#" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700"><i class="icon">📄</i> Medical Records</a></li>
                    <li><a href="#" class="flex items-center gap-3 p-3 rounded hover:bg-gray-700"><i class="icon">⚙️</i> Settings</a></li>
                </ul>
            </nav>

            <div class="p-6 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white text-black py-2 rounded font-medium hover:bg-gray-200">Log Out</button>
                </form>
            </div>
        </aside>

        <main class="ml-64 flex-1 p-8">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Welcome back, {{ Auth::user()->name ?? 'Patient' }}</h2>
                    <p class="text-gray-500 text-sm">Wednesday, December 24, 2025</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600 font-medium">User Profile</span>
                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center font-bold text-gray-600">
                        {{ substr(Auth::user()->name ?? 'P', 0, 1) }}
                    </div>
                </div>
            </div>
            @hasrole('patient')
            @if(isset($doctors) && $doctors->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Available Doctors</h3>
                    <button class="px-4 py-1 text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-50">View All</button>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($doctors as $doctor)
                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <img src="https://img.freepik.com/premium-vector/default-avatar-profile-icon-social-media-user-image-gray-avatar-icon-blank-profile-silhouette-vector-illustration_561158-3407.jpg" alt="Doctor" class="w-16 h-16 rounded-lg object-cover bg-gray-100">
                            <div>
                                <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full uppercase font-bold">Online</span>
                                <h4 class="font-bold text-gray-800 mt-1">{{ $doctor->name }}</h4>
                                <p class="text-gray-500 text-sm">{{ $doctor->specialization }}</p>
                                <div class="text-yellow-500 text-xs mt-1">⭐⭐⭐⭐⭐ <span class="text-gray-400">({{ rand(10, 100) }} reviews)</span></div>
                            </div>
                        </div>
                        <!-- <button class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700">Book Appointment</button> -->
                        <button 
                            @click="openModal = true; selectedDoctor = '{{ $doctor->name }}'"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Book Appointment
                        </button>

                        <div 
                            {{-- 1. Use .outside instead of .away for better reliability --}}
                            @click.outside="openModal = false"
                            {{-- 2. Stop click propagation from inside the modal to prevent accidental triggers --}}
                            @click.stop
                            x-show="openModal"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="fixed inset-0 z-50 overflow-y-auto" 
                            x-cloak>
                            
                            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="openModal = false"></div>

                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div 
                                    class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative transform transition-all"
                                    @click.away="openModal = false"
                                    x-show="openModal"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4"
                                    x-transition:enter-end="opacity-100 translate-y-0">
                                    
                                    <button @click="openModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>

                                    <div class="mb-6">
                                        <h3 class="text-2xl font-bold text-gray-800">Book Appointment</h3>
                                        <p class="text-gray-500 text-sm">Booking for <span class="text-blue-600 font-semibold" x-text="selectedDoctor"></span></p>
                                    </div>

                                    <form action="{{ route('appointments.store') }}" method="POST" class="space-y-5">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Select Date</label>
                                            <div class="relative">
                                                <input type="date" name="appointment_date" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 border">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Available Slots</label>
                                            <div class="grid grid-cols-3 gap-2">
                                                @foreach(['09:00 AM', '10:30 AM', '01:00 PM', '02:30 PM', '04:00 PM', '05:30 PM'] as $time)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="time_slot" value="{{ $time }}" class="peer hidden">
                                                        <div class="text-center py-2 px-1 border border-gray-200 rounded-lg text-sm peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-blue-50 transition-all">
                                                            {{ $time }}
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Visit</label>
                                            <textarea rows="3" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 border" placeholder="Briefly describe your concern..."></textarea>
                                        </div>

                                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95">
                                            Confirm Appointment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="p-4 text-center text-gray-400 text-sm italic">
                        No more doctors available at the moment.
                    </div>
                </div>
            </div>
            @endif
            @endhasrole
        </main>
    </div>

</body>
</html>