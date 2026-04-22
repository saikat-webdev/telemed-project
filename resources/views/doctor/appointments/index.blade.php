@extends('doctor.layout.structure')

@push('styles')
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
}
.modal.active {
    display: flex;
}
.modal-content {
    background: white;
    padding: 24px;
    border-radius: 16px;
    width: 90%;
    max-width: 400px;
}
.reason-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s;
}
.reason-option:hover {
    background-color: #f9fafb;
}
.reason-option.selected {
    border-color: #7c3aed;
    background-color: #f5f3ff;
}
</style>
@endpush

@section('title', 'Appointments | TeleHealth')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Appointments</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">View and manage all your appointments</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-violet-100 text-violet-700 rounded-xl text-sm font-bold">
                {{ $appointments->count() }} Total
            </span>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('doctor.appointments.index') }}" 
               class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ !request()->has('status') ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                All
            </a>
            <a href="{{ route('doctor.appointments.index', ['status' => 0]) }}" 
               class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request()->get('status') == '0' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Pending
            </a>
            <a href="{{ route('doctor.appointments.index', ['status' => 1]) }}" 
               class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request()->get('status') == '1' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Confirmed
            </a>
            <a href="{{ route('doctor.appointments.index', ['status' => 2]) }}" 
               class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request()->get('status') == '2' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Fees Paid
            </a>
            <a href="{{ route('doctor.appointments.index', ['status' => 3]) }}" 
               class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request()->get('status') == '3' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Completed
            </a>
            <a href="{{ route('doctor.appointments.index', ['status' => 4]) }}" 
               class="px-4 py-2 rounded-xl text-sm font-medium transition-all {{ request()->get('status') == '4' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Cancelled
            </a>
        </div>
    </div>

    {{-- Appointments Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Patient</th>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</th>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Time</th>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="p-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                    {{ substr($appointment->patient->name ?? 'P', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800">{{ $appointment->patient->name ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $appointment->patient->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                </span>
                                <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l') }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-sm font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-[10px] {{ $appointment->status_color }} px-3 py-1 rounded-full font-black uppercase tracking-widest">
                                {{ $appointment->status_label }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2 flex-wrap">
                                @if($appointment->status == 0)
                                    <form action="{{ route('doctor.appointments.status', $appointment) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="1">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                            Confirm
                                        </button>
                                    </form>
                                    <form action="{{ route('doctor.appointments.status', $appointment) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="4">
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                                @if($appointment->status == 1 || $appointment->status == 2)
                                    <a href="{{ route('doctor.consultation', $appointment->id) }}" 
                                    class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                        Join Call
                                    </a>
                                    <a href="{{ route('doctor.messages.show', $appointment->patient) }}"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-xs font-medium transition-all">
                                        Message
                                    </a>
                                    <a href="{{ route('doctor.prescription.create', $appointment->id) }}" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                        Prescription
                                    </a>
                                @endif
                                @if($appointment->status == 2)
                                    <form action="{{ route('doctor.appointments.status', $appointment) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="3">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                            Complete
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('doctor.appointments.show', $appointment->id) }}" 
                                class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-xs font-medium transition-all">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="far fa-calendar-times text-gray-300 text-2xl"></i>
                                </div>
                                <h4 class="text-gray-800 font-bold">No appointments found</h4>
                                <p class="text-gray-400 text-xs mt-1">When patients book appointments, they will appear here.</p>
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
