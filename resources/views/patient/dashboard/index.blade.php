@extends('patient.layout.dashboard')

@section('title', 'Dashboard | TeleHealth')

@section('content')
{{-- Wrap everything in x-data to manage the modal state and selected doctor data --}}
<div x-data="{ 
    openModal: false, 
    selectedDoctorName: '', 
    selectedDoctorId: '' 
}">

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Welcome back, {{ Auth::user()->name ?? 'Patient' }}</h2>
            <p class="text-gray-500 text-sm">{{ now()->format('l, F j, Y') }}</p>
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

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 5000)"
                    class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center shadow-sm"
                    x-transition:leave="transition ease-in duration-300">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Available Doctors</h3>
                    <button class="px-4 py-1 text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-50">View All</button>
                </div>
                
                <div class="p-6 space-y-4">
                    @foreach($doctors as $doctor)
                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-4">
                            <img src="https://i.pravatar.cc/150?u={{ $doctor->name }}" alt="Doctor" class="w-16 h-16 rounded-lg object-cover bg-gray-100">
                            <div>
                                <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded-full uppercase font-bold">Online</span>
                                <h4 class="font-bold text-gray-800 mt-1">{{ $doctor->name }}</h4>
                                <p class="text-gray-500 text-sm">{{ $doctor->category->name }}</p>
                                <div class="text-yellow-500 text-xs mt-1">⭐⭐⭐⭐⭐ <span class="text-gray-400">({{ rand(10, 100) }} reviews)</span></div>
                            </div>
                        </div>

                        <button 
                            @click="openModal = true; selectedDoctorName = '{{ $doctor->name }}'; selectedDoctorId = '{{ $doctor->id }}'"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Book Appointment
                        </button>
                    </div>
                    @endforeach
                    
                    @if($doctors->isEmpty())
                        <div class="p-4 text-center text-gray-400 text-sm italic">
                            No more doctors available at the moment.
                        </div>
                    @endif
                </div>
            </div>

            <div 
                x-show="openModal" 
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
                            <p class="text-gray-500 text-sm">Booking for <span class="text-blue-600 font-semibold" x-text="selectedDoctorName"></span></p>
                        </div>

                        <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-5">
                            @csrf
                            {{-- This hidden input now gets the correct ID via Alpine --}}
                            <input type="hidden" name="doctor_id" :value="selectedDoctorId">
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Date</label>
                                <input type="date" name="appointment_date" min="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 border" required>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Available Slots</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach(['09:00', '10:30', '01:00', '02:30', '04:00', '05:30'] as $time)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="appointment_time" value="{{ $time }}" class="peer hidden" required>
                                            <div class="text-center py-2 px-1 border border-gray-200 rounded-lg text-sm peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-blue-50 transition-all">
                                                {{ $time }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Visit</label>
                                <textarea rows="3" name="comment" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 border" placeholder="Briefly describe your concern..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all active:scale-95">
                                Confirm Appointment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
    @endhasrole
</div>
@endsection