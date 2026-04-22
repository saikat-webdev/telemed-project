@extends('admin.layout.dashboard')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Appointments</span>
    </a>

    {{-- Appointment Info Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Appointment #{{ $appointment->id }}</h2>
                @php
                $statusClass = match($appointment->status) {
                    0 => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    1 => 'bg-blue-100 text-blue-700 border-blue-200',
                    2 => 'bg-purple-100 text-purple-700 border-purple-200',
                    3 => 'bg-green-100 text-green-700 border-green-200',
                    4 => 'bg-red-100 text-red-700 border-red-200',
                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                };
                @endphp
                <span class="inline-block mt-2 px-3 py-1 text-sm font-medium rounded-full border {{ $statusClass }}">
                    {{ match($appointment->status) { 0 => 'Pending', 1 => 'Confirmed', 2 => 'Fees Paid', 3 => 'Completed', 4 => 'Cancelled', default => 'Unknown' } }}
                </span>
            </div>
            <div>
                <span class="text-sm text-gray-400">Created: {{ $appointment->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            {{-- Patient Info --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Patient Information</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr($appointment->patient->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-800">{{ $appointment->patient->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->patient->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Doctor Info --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Doctor Information</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr($appointment->doctor->name ?? 'D', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-800">{{ $appointment->doctor->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->doctor->category->name ?? 'General' }}</p>
                    </div>
                </div>
            </div>

            {{-- Appointment Details --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Appointment Details</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Date</span>
                        <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Time</span>
                        <span class="font-medium text-gray-800">{{ $appointment->appointment_time }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Fees</span>
                        <span class="font-medium text-gray-800">${{ $appointment->doctor->fees ?? 100 }}</span>
                    </div>
                </div>
            </div>

            {{-- Comment --}}
            @if($appointment->comment)
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Patient Comment</h3>
                <p class="text-gray-700">{{ $appointment->comment }}</p>
            </div>
            @endif
        </div>

        {{-- Update Status Form --}}
        <div class="mt-6 pt-6 border-t border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status</h3>
            <form action="{{ route('admin.appointments.updateStatus', $appointment->id) }}" method="POST" class="flex items-center gap-4">
                @csrf
                @method('PUT')
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="0" {{ $appointment->status == 0 ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $appointment->status == 1 ? 'selected' : '' }}>Confirmed</option>
                    <option value="2" {{ $appointment->status == 2 ? 'selected' : '' }}>Fees Paid</option>
                    <option value="3" {{ $appointment->status == 3 ? 'selected' : '' }}>Completed</option>
                    <option value="4" {{ $appointment->status == 4 ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition-colors">
                    Update Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection