@extends('admin.layout.dashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Patients --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['totalUsers'] }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i> Active
                </span>
                <span class="text-gray-400 ml-2">patients registered</span>
            </div>
        </div>

        {{-- Total Doctors --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Doctors</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['totalDoctors'] }}</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-blue-500 flex items-center">
                    <i class="fas fa-user-md mr-1"></i> {{ $stats['totalCategories'] }}
                </span>
                <span class="text-gray-400 ml-2">specializations</span>
            </div>
        </div>

        {{-- Today's Appointments --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Today's Appointments</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['todayAppointments'] }}</p>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-yellow-500 flex items-center">
                    <i class="fas fa-clock mr-1"></i> {{ $stats['pendingAppointments'] }}
                </span>
                <span class="text-gray-400 ml-2">pending</span>
            </div>
        </div>

        {{-- Completed Appointments --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Completed</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['completedAppointments'] }}</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-gray-400">
                    out of {{ $stats['totalAppointments'] }} total appointments
                </span>
            </div>
        </div>
    </div>

    {{-- Charts and Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Appointments Status Distribution --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Appointment Status</h3>
            <div class="space-y-4">
                @php
                $statusLabels = [
                    0 => ['label' => 'Pending', 'color' => 'bg-yellow-500'],
                    1 => ['label' => 'Confirmed', 'color' => 'bg-blue-500'],
                    2 => ['label' => 'Fees Paid', 'color' => 'bg-purple-500'],
                    3 => ['label' => 'Completed', 'color' => 'bg-green-500'],
                    4 => ['label' => 'Cancelled', 'color' => 'bg-red-500'],
                ];
                $total = $statusDistribution->sum() ?: 1;
                @endphp
                @foreach($statusDistribution as $status => $count)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">{{ $statusLabels[$status]['label'] ?? 'Unknown' }}</span>
                        <span class="font-medium">{{ $count }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="{{ $statusLabels[$status]['color'] }} h-2 rounded-full transition-all duration-500" style="width: {{ ($count / $total) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Doctors --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Doctors</h3>
                <a href="{{ route('admin.doctors.index') }}" class="text-sm text-orange-500 hover:text-orange-600">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentDoctors as $doctor)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ substr($doctor->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $doctor->name }}</p>
                        <p class="text-xs text-gray-500">{{ $doctor->category->name ?? 'General' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No doctors yet</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Patients --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Patients</h3>
                <a href="{{ route('admin.patients.index') }}" class="text-sm text-orange-500 hover:text-orange-600">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentPatients as $patient)
                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ substr($patient->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $patient->name }}</p>
                        <p class="text-xs text-gray-500">{{ $patient->email }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No patients yet</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Appointments Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Appointments</h3>
                <a href="{{ route('admin.appointments.index') }}" class="text-sm text-orange-500 hover:text-orange-600">View All</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentAppointments as $appointment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $appointment->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                    {{ substr($appointment->patient->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $appointment->patient->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-800">{{ $appointment->doctor->name ?? 'Unknown' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $appointment->appointment_time }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusClass = match($appointment->status) {
                                0 => 'bg-yellow-100 text-yellow-700',
                                1 => 'bg-blue-100 text-blue-700',
                                2 => 'bg-purple-100 text-purple-700',
                                3 => 'bg-green-100 text-green-700',
                                4 => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                            $statusLabel = match($appointment->status) {
                                0 => 'Pending',
                                1 => 'Confirmed',
                                2 => 'Fees Paid',
                                3 => 'Completed',
                                4 => 'Cancelled',
                                default => 'Unknown',
                            };
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-400">No appointments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection