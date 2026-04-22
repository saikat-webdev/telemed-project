@extends('patient.layout.dashboard')

@section('title', 'Patient Dashboard | HealthHub')

@section('content')
<div x-data="{ openModal: false, selectedDoctorName: '', selectedDoctorId: '' }" class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-extrabold text-gray-800">Welcome back, {{ Auth::user()->name ?? 'Patient' }}</h2>
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
            </div>
            <p class="text-gray-500 text-sm font-medium flex flex-wrap items-center gap-3 mt-1">
                <span class="flex items-center gap-1.5">
                    <i class="far fa-calendar-alt text-orange-500"></i>
                    {{ now()->format('l, F j, Y') }}
                </span>
                <span class="text-gray-300 hidden sm:block">|</span>
                <span class="flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-1 rounded-full border border-blue-100 shadow-sm">
                    <i class="far fa-clock animate-pulse text-blue-500"></i>
                    <span id="liveClock" class="font-mono font-black tabular-nums tracking-wider text-sm">
                        {{ now()->format('h:i:s A') }}
                    </span>
                </span>
            </p>
        </div>

        <div class="flex items-center gap-3 bg-gray-50 p-2 pr-4 rounded-xl border border-gray-100">
            <div class="w-10 h-10 bg-[#1e293b] text-white rounded-lg flex items-center justify-center font-bold shadow-md">
                {{ substr(Auth::user()->name ?? 'P', 0, 1) }}
            </div>
            <div class="text-left">
                <p class="text-xs font-bold text-gray-800 leading-none">{{ Auth::user()->name ?? 'Patient' }}</p>
                <p class="text-[10px] text-green-600 font-bold uppercase tracking-tighter mt-1">Care Plan Active</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
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
            class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl flex justify-between items-center shadow-sm"
            x-transition:leave="transition ease-in duration-300">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">All Appointments</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['totalAppointments'] }}</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Upcoming</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['upcomingAppointments'] }}</h3>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Completed</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['completedAppointments'] }}</h3>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Unread Messages</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['unreadThreads'] }}</h3>
                </div>
                <div class="p-2 bg-violet-50 rounded-lg text-violet-600">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Upcoming Appointments</h3>
                    <p class="text-xs text-gray-400 mt-1">Everything scheduled or ready for consultation.</p>
                </div>
                <a href="{{ route('patient.appointments.index') }}" class="text-sm text-violet-600 font-semibold hover:text-violet-700">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/60">
                        <tr>
                            <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Doctor</th>
                            <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($upcomingAppointments as $appointment)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm shadow-sm">
                                            {{ substr($appointment->doctor->name ?? 'D', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-800">{{ $appointment->doctor->name ?? 'Doctor' }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $appointment->doctor->category->name ?? 'General' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</p>
                                    <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                </td>
                                <td class="p-4">
                                    <span class="text-[10px] {{ $appointment->status_color }} px-3 py-1 rounded-full font-black uppercase tracking-widest">
                                        {{ $appointment->status_label }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    @if((int) $appointment->status === 2)
                                        <a href="{{ route('patient.consultation', $appointment->id) }}" class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all inline-block">
                                            Join Call
                                        </a>
                                    @elseif((int) $appointment->status === 1)
                                        <form action="{{ route('patient.appointments.pay') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                                Pay {{ $appointment->doctor->fees ? 'Rs. ' . number_format($appointment->doctor->fees, 0) : '' }}
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('patient.appointments.index') }}" class="text-sm text-gray-500 hover:text-violet-600 font-medium">
                                            Review
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-16 text-center text-gray-400 text-sm">No upcoming appointments yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Available Doctors</h3>
                    <p class="text-xs text-gray-400 mt-1">Book directly from your dashboard.</p>
                </div>
                <a href="{{ route('patient.doctors.index') }}" class="text-sm text-violet-600 font-semibold hover:text-violet-700">Browse</a>
            </div>
            <div class="p-6 space-y-4">
                @forelse($doctors as $doctor)
                    <div class="flex items-center justify-between gap-4 p-4 border border-gray-100 rounded-2xl hover:shadow-sm transition-shadow">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center font-black">
                                {{ substr($doctor->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-800 truncate">{{ $doctor->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $doctor->category->name ?? 'General Specialist' }}</p>
                                <p class="text-xs text-gray-600 mt-1">Fee: {{ $doctor->fees ? 'Rs. ' . number_format($doctor->fees, 0) : 'Not set' }}</p>
                            </div>
                        </div>
                        <button
                            @click="openModal = true; selectedDoctorName = '{{ $doctor->name }}'; selectedDoctorId = '{{ $doctor->id }}'"
                            class="bg-gray-900 hover:bg-violet-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0">
                            Book
                        </button>
                    </div>
                @empty
                    <div class="text-center text-gray-400 text-sm py-8">No doctors are available right now.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-indigo-950 via-violet-900 to-fuchsia-800 rounded-3xl p-8 text-white shadow-xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="max-w-2xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-xs font-bold uppercase tracking-[0.25em]">New Module</span>
                <h3 class="text-2xl md:text-3xl font-black mt-4">AI Symptom Checker</h3>
                <p class="text-indigo-100 mt-3 text-sm md:text-base">
                    Describe what you are feeling and get a quick AI-guided suggestion about possible health issues and which specialist may be most relevant.
                </p>
                <p class="text-indigo-200/90 mt-3 text-xs uppercase tracking-[0.2em]">
                    Guidance only. Not a medical diagnosis.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('patient.symptom-checker.index') }}" class="inline-flex items-center gap-2 bg-white text-indigo-900 px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-indigo-50 transition-all">
                    Try Symptom Checker
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
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
                    <input type="hidden" name="doctor_id" :value="selectedDoctorId">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Date</label>
                        <input type="date" name="appointment_date" min="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 border" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Available Slots</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['09:00', '10:30', '13:00', '14:30', '16:00', '17:30'] as $time)
                                <label class="cursor-pointer">
                                    <input type="radio" name="appointment_time" value="{{ $time }}" class="peer hidden" required>
                                    <div class="text-center py-2 px-1 border border-gray-200 rounded-lg text-sm peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-blue-50 transition-all">
                                        {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('h:i A') }}
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
</div>
@endsection
