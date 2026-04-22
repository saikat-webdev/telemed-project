@extends('doctor.layout.structure')

@section('title', 'Dashboard | HealthHub')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-extrabold text-gray-800">Welcome back, {{ Auth::user()->name }}</h2>
                {{-- Status Indicator Dot --}}
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
                {{-- High-End Clock Badge --}}
                <span class="flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-1 rounded-full border border-blue-100 shadow-sm">
                    <i class="far fa-clock animate-pulse text-blue-500"></i>
                    <span id="liveClock" class="font-mono font-black tabular-nums tracking-wider text-sm">
                        {{ now()->format('h:i:s A') }}
                    </span>
                </span>
            </p>
        </div>

        {{-- Doctor Profile Quick View --}}
        <div class="flex items-center gap-3 bg-gray-50 p-2 pr-4 rounded-xl border border-gray-100">
            <div class="w-10 h-10 bg-[#1e293b] text-white rounded-lg flex items-center justify-center font-bold shadow-md">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="text-left">
                <p class="text-xs font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-green-600 font-bold uppercase tracking-tighter mt-1">Active Now</p>
            </div>
        </div>
    </div>

    {{-- Stats Cards (Quick Glance) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Today's Total</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $todaysAppointments->count() }}</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class="fas fa-user-friends"></i>
                </div>
            </div>
            <p class="text-[10px] text-gray-400 mt-4 font-medium italic">* Scheduled consultations</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Completed</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $todaysAppointments->where('status', 3)->count() }}</h3>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4">
                @php 
                    $percent = $todaysAppointments->count() > 0 ? ($todaysAppointments->where('status', 3)->count() / $todaysAppointments->count()) * 100 : 0;
                @endphp
                <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Pending/Next</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $todaysAppointments->whereIn('status', [0, 1, 2])->count() }}</h3>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
            <p class="text-[10px] text-orange-600 mt-4 font-bold flex items-center gap-1">
                <span class="flex h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                Action Required
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Unread Messages</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['unreadMessages'] ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-violet-50 rounded-lg text-violet-600">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
            <a href="{{ route('doctor.messages.index') }}" class="text-[10px] text-violet-600 mt-4 font-bold uppercase tracking-widest inline-block">
                Open Inbox
            </a>
        </div>
    </div>

    {{-- Today's Appointments Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white">
            <div class="flex items-center gap-2">
                <h3 class="font-bold text-gray-800 text-lg">Today's Schedule</h3>
                <span class="flex h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
            </div>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                {{ $todaysAppointments->count() }} Slots
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Time Slot</th>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Patient Details</th>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($todaysAppointments as $appointment)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-gray-800 tabular-nums">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">15 Min Session</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm shadow-sm">
                                    {{ substr($appointment->patient->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800 group-hover:text-blue-600 transition-colors">
                                        {{ $appointment->patient->name }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $appointment->patient->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-[10px] {{ $appointment->status_color }} px-3 py-1 rounded-full font-black uppercase tracking-widest">
                                {{ $appointment->status_label }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if($appointment->status == 1 || $appointment->status == 2)
                                    <a href="{{ route(auth()->user()->hasRole('doctor') ? 'doctor.consultation' : 'patient.consultation', $appointment->id) }}" 
                                    class="bg-[#1e293b] hover:bg-black text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-gray-200 inline-block text-center">
                                        Join Call
                                    </a>
                                    @if(auth()->user()->hasRole('doctor'))
                                        <a href="{{ route('doctor.prescription.create', $appointment->id) }}" 
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                            <i class="fas fa-prescription-bottle-alt"></i> Create Prescription
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="far fa-calendar-times text-gray-300 text-2xl"></i>
                                </div>
                                <h4 class="text-gray-800 font-bold">No appointments today</h4>
                                <p class="text-gray-400 text-xs mt-1">Enjoy your free time or check back later.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
