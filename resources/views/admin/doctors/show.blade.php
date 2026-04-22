@extends('admin.layout.dashboard')

@section('title', 'Doctor Details')
@section('page-title', 'Doctor Details')

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('admin.doctors.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Doctors</span>
    </a>

    {{-- Doctor Info Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start gap-6">
            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                {{ substr($doctor->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800">{{ $doctor->name }}</h2>
                <p class="text-gray-500 mt-1">{{ $doctor->category->name ?? 'General Medicine' }}</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Email</p>
                        <p class="text-sm font-medium text-gray-800">{{ $doctor->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Phone</p>
                        <p class="text-sm font-medium text-gray-800">{{ $doctor->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Consultation Fees</p>
                        <p class="text-sm font-medium text-gray-800">${{ $doctor->fees }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Joined</p>
                        <p class="text-sm font-medium text-gray-800">{{ $doctor->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Appointments Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Appointments History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($doctorAppointments as $appointment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $appointment->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $appointment->patient->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-400">No appointments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($doctorAppointments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $doctorAppointments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection