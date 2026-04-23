@extends(auth()->user()->hasRole('patient') ? 'patient.layout.dashboard' : 'doctor.layout.structure')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    @php
        $prescription = $appointment->prescription;
    @endphp

    @if(session('success'))
        <div class="p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex justify-between items-start gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Prescription</h2>
            <p class="text-gray-500 text-sm mt-1">Appointment #{{ $appointment->id }} with Dr. {{ $appointment->doctor->name ?? 'Doctor' }}</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->hasRole('doctor'))
                <a href="{{ route('doctor.prescription.create', $appointment) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold">Edit</a>
            @endif
            <a href="{{ route('prescription.download', $appointment) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">Download PDF</a>
        </div>
    </div>

    <div class="bg-white shadow-2xl rounded-sm p-10 border-t-8 border-blue-600">
        <div class="flex justify-between items-start border-b pb-6">
            <div>
                <div class="text-3xl font-black tracking-tight">
                    <span class="text-violet-500">Health</span><span class="text-slate-900">Hub</span>
                </div>
                <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Digital Healthcare Portal</p>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-bold text-gray-800">Dr. {{ $appointment->doctor->name ?? auth()->user()->name }}</h3>
                <p class="text-sm text-blue-600">{{ $appointment->doctor->category->name ?? 'General Physician' }}</p>
                <p class="text-xs text-gray-500">Issued {{ optional($prescription?->issued_at)->format('d M Y h:i A') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-8 bg-gray-50/50 px-4 mt-6 rounded-xl border border-gray-100">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Patient Name</p>
                <p class="font-bold text-gray-800">{{ $prescription->patient_name }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Age / Gender</p>
                <p class="font-bold text-gray-800">{{ $prescription->age_gender ?: 'Not specified' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Weight</p>
                <p class="font-bold text-gray-800">{{ $prescription->weight ?: 'Not specified' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Height</p>
                <p class="font-bold text-gray-800">{{ $prescription->height ?: 'Not specified' }}</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6">
            <div>
                <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-2">Chief Complaints</h4>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-xl p-4">{{ $prescription->chief_complaints ?: 'No chief complaints recorded.' }}</p>
            </div>
            <div>
                <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-2">Diagnosis & Notes</h4>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-xl p-4">{{ $prescription->diagnosis_notes ?: 'No diagnosis notes recorded.' }}</p>
            </div>
            <div>
                <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-2">Medications</h4>
                <div class="overflow-hidden rounded-xl border border-gray-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-[10px] uppercase font-bold text-gray-400">
                            <tr>
                                <th class="p-3">Medicine</th>
                                <th class="p-3">Dosage</th>
                                <th class="p-3">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($prescription->medicines ?? [] as $medicine)
                                <tr>
                                    <td class="p-3 text-gray-800 font-semibold">{{ $medicine['name'] ?? 'Medicine' }}</td>
                                    <td class="p-3 text-gray-600">{{ $medicine['dosage'] ?? 'As directed' }}</td>
                                    <td class="p-3 text-gray-600">{{ $medicine['duration'] ?? 'Until follow-up' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-2">Additional Notes</h4>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-xl p-4">{{ $prescription->additional_notes ?: 'No additional notes recorded.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
