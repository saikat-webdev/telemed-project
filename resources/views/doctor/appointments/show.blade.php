@extends('doctor.layout.structure')

@section('title', 'Appointment Details | HealthHub')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800">Appointment Details</h2>
                <p class="text-gray-500 text-sm mt-1">View appointment information and move it through the consultation workflow.</p>
            </div>
            <a href="{{ route('doctor.appointments.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition-all">
                Back to Appointments
            </a>
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
        <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-gray-800 text-lg">Appointment #{{ $appointment->id }}</h3>
                <span class="text-[10px] {{ $appointment->status_color }} px-4 py-2 rounded-full font-black uppercase tracking-widest">
                    {{ $appointment->status_label }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <h4 class="font-bold text-gray-700 mb-4">Patient Information</h4>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center font-bold text-lg">
                            {{ substr($appointment->patient->name ?? 'P', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">{{ $appointment->patient->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $appointment->patient->email ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <h4 class="font-bold text-gray-700 mb-4">Appointment Information</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500 text-sm">Date</span>
                            <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 text-sm">Time</span>
                            <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 text-sm">Status</span>
                            <span class="font-medium text-gray-800">{{ $appointment->status_label }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 text-sm">Consultation Fee</span>
                            <span class="font-medium text-gray-800">Rs. {{ number_format($appointment->doctor->fees ?? 0, 0) }}</span>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 bg-gray-50 rounded-xl p-4">
                    <h4 class="font-bold text-gray-700 mb-2">Notes/Comments</h4>
                    <p class="text-gray-600">{{ $appointment->comment ?? 'No notes added.' }}</p>
                </div>

                @if($appointment->transaction)
                    <div class="md:col-span-2 bg-violet-50 rounded-xl p-4 border border-violet-100">
                        <h4 class="font-bold text-violet-700 mb-2">Payment</h4>
                        <p class="text-sm text-violet-700">Transaction ID: {{ $appointment->transaction->stripe_transaction_id }}</p>
                        <p class="text-sm text-violet-700">Amount: Rs. {{ number_format($appointment->transaction->amount, 2) }}</p>
                    </div>
                @endif

                @if($appointment->review)
                    <div class="md:col-span-2 bg-yellow-50 rounded-xl p-4 border border-yellow-100">
                        <h4 class="font-bold text-yellow-700 mb-2">Patient Review</h4>
                        <p class="text-sm text-gray-700 mb-1">Rating: {{ $appointment->review->rating }}/5</p>
                        <p class="text-sm text-gray-700">{{ $appointment->review->review }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="p-6 border-t border-gray-100 bg-gray-50">
            <div class="flex flex-wrap gap-3">
                @if($appointment->status == 0)
                    <form action="{{ route('doctor.appointments.status', $appointment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="1">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition-all">
                            Confirm Appointment
                        </button>
                    </form>
                    <form action="{{ route('doctor.appointments.status', $appointment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="4">
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-6 py-3 rounded-xl font-bold transition-all">
                            Cancel Appointment
                        </button>
                    </form>
                @endif

                @if($appointment->status == 1 || $appointment->status == 2)
                    <a href="{{ route('doctor.consultation', $appointment->id) }}"
                       class="bg-violet-600 hover:bg-violet-700 text-white px-6 py-3 rounded-xl font-bold transition-all">
                        Start Consultation
                    </a>
                    <a href="{{ route('doctor.messages.show', $appointment->patient) }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold transition-all">
                        Message Patient
                    </a>
                    <a href="{{ route('doctor.prescription.create', $appointment->id) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold transition-all">
                        Create Prescription
                    </a>
                @endif

                @if($appointment->status == 2)
                    <form action="{{ route('doctor.appointments.status', $appointment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="3">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold transition-all">
                            Mark Completed
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
