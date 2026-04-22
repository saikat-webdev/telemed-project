@extends('admin.layout.dashboard')

@section('title', 'Manage Appointments')
@section('page-title', 'Manage Appointments')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Appointments Management</h2>
            <p class="text-gray-500 mt-1">View and manage all appointments in the system</p>
        </div>
    </div>

    {{-- Appointments Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
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
                    @forelse($appointments as $appointment)
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
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs">
                                    {{ substr($appointment->doctor->name ?? 'D', 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $appointment->doctor->name ?? 'Unknown' }}</span>
                            </div>
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
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                {{ match($appointment->status) { 0 => 'Pending', 1 => 'Confirmed', 2 => 'Fees Paid', 3 => 'Completed', 4 => 'Cancelled', default => 'Unknown' } }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="px-3 py-1.5 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition-colors">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">No appointments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection