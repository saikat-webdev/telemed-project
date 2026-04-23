@extends('doctor.layout.structure')

@section('content')
<div class="max-w-4xl mx-auto my-8">
    @php
        $prescription = $appointment->prescription;
    @endphp

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('doctor.prescription.store', $appointment) }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white shadow-2xl rounded-sm p-12 border-t-8 border-blue-600 min-h-[1056px] flex flex-col">
        
            {{-- 1. Header: Logo & Doctor Info --}}
            <div class="flex justify-between items-start border-b-2 border-gray-100 pb-8">
                <div>
                    <div class="text-3xl font-black tracking-tight">
                        <span class="text-violet-500">Health</span><span class="text-slate-900">Hub</span>
                    </div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Digital Healthcare Portal</p>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-bold text-gray-800">Dr. {{ Auth::user()->name }}</h2>
                    <p class="text-blue-600 font-medium">{{ Auth::user()->doctorProfile?->category?->name ?? 'General Physician' }}</p>
                    <p class="text-xs text-gray-500">Reg No: #{{ Auth::user()->id + 1000 }}</p>
                </div>
            </div>

            {{-- 2. Patient Demographics --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-8 bg-gray-50/50 px-4 mt-6 rounded-xl border border-gray-100">
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Patient Name</label>
                    <input type="text" class="w-full bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0" name="patient_name" value="{{ old('patient_name', $prescription?->patient_name ?? $appointment->patient->name) }}">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Age / Gender</label>
                    <input type="text" class="w-full bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0" name="age_gender" value="{{ old('age_gender', $prescription?->age_gender) }}" placeholder="24/Male">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Weight</label>
                    <input type="text" class="w-full bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0" name="weight" value="{{ old('weight', $prescription?->weight) }}" placeholder="70kg">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase">Height</label>
                    <input type="text" class="w-full bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0" name="height" value="{{ old('height', $prescription?->height) }}" placeholder="5'9''">
                </div>
            </div>

            {{-- 3. Clinical Section --}}
            <div class="mt-10 space-y-8 flex-1">
                <div>
                    <h3 class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest mb-3">
                        <i class="fas fa-notes-medical text-blue-500"></i> Chief Complaints
                    </h3>
                    <textarea name="chief_complaints" class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm focus:ring-2 focus:ring-blue-100" rows="3" placeholder="Patient is facing...">{{ old('chief_complaints', $prescription?->chief_complaints ?? $appointment->comment) }}</textarea>
                </div>

                <div>
                    <h3 class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest mb-3">
                        <i class="fas fa-stethoscope text-blue-500"></i> Diagnosis & Notes
                    </h3>
                    <textarea name="diagnosis_notes" class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm focus:ring-2 focus:ring-blue-100" rows="3" placeholder="Clinical findings...">{{ old('diagnosis_notes', $prescription?->diagnosis_notes) }}</textarea>
                </div>

                <div>
                    <h3 class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest mb-3">
                        <i class="fas fa-clipboard text-blue-500"></i> Additional Notes
                    </h3>
                    <textarea name="additional_notes" class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm focus:ring-2 focus:ring-blue-100" rows="3" placeholder="Follow-up instructions, tests, warnings...">{{ old('additional_notes', $prescription?->additional_notes) }}</textarea>
                </div>

                {{-- 4. Medicine List --}}
                <div class="flex justify-between items-center mb-3">
                    <h3 class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest">
                        <i class="fas fa-pills text-blue-500"></i> Rx / Medications
                    </h3>
                    <button type="button" id="add-medicine-row" class="text-blue-600 font-bold text-xs hover:text-blue-800 transition-colors">
                        <i class="fas fa-plus-circle"></i> Add Medicine
                    </button>
                </div>

                @php
                    $medicines = old('medicines', $prescription?->medicines ?? [['name' => '', 'dosage' => '', 'duration' => '']]);
                @endphp
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-[10px] uppercase font-bold text-gray-400">
                        <tr>
                            <th class="p-3">Medicine Name</th>
                            <th class="p-3">Dosage</th>
                            <th class="p-3">Duration</th>
                            <th class="p-3 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="medicine-list-body" class="divide-y divide-gray-100">
                        @foreach($medicines as $index => $medicine)
                            <tr>
                                <td class="p-3"><input type="text" name="medicines[{{ $index }}][name]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" value="{{ $medicine['name'] ?? '' }}" placeholder="e.g. Paracetamol"></td>
                                <td class="p-3"><input type="text" name="medicines[{{ $index }}][dosage]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" value="{{ $medicine['dosage'] ?? '' }}" placeholder="1-0-1 After Meal"></td>
                                <td class="p-3"><input type="text" name="medicines[{{ $index }}][duration]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" value="{{ $medicine['duration'] ?? '' }}" placeholder="5 Days"></td>
                                <td class="p-3 text-center">
                                    <button type="button" class="remove-medicine-row text-red-500 text-xs font-bold">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 5. Footer & Signature --}}
            <div class="mt-12 pt-8 border-t border-gray-100 flex justify-between items-end">
                <div class="text-[10px] text-gray-400 italic">
                    Generated via HealthHub System on {{ now()->format('d M, Y h:i A') }}
                </div>
                <div class="text-center">
                    <div class="mb-2">
                        <p class="font-serif text-3xl text-blue-900/30 select-none italic tracking-tighter">
                            {{ Auth::user()->name }}
                        </p>
                    </div>
                    <div class="w-48 border-t-2 border-gray-800 pt-2">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-800">Digital Signature</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="mt-6 flex justify-end gap-4">
            @if($prescription)
                <a href="{{ route('prescription.show', $appointment) }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold text-sm">Preview Prescription</a>
            @endif
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-200">
                {{ $prescription ? 'Update Prescription' : 'Issue Prescription' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const tbody = document.getElementById('medicine-list-body');
        const addButton = document.getElementById('add-medicine-row');

        addButton?.addEventListener('click', () => {
            const index = tbody.querySelectorAll('tr').length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="p-3"><input type="text" name="medicines[${index}][name]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="e.g. Paracetamol"></td>
                <td class="p-3"><input type="text" name="medicines[${index}][dosage]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="1-0-1 After Meal"></td>
                <td class="p-3"><input type="text" name="medicines[${index}][duration]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="5 Days"></td>
                <td class="p-3 text-center"><button type="button" class="remove-medicine-row text-red-500 text-xs font-bold">Remove</button></td>
            `;
            tbody.appendChild(row);
        });

        tbody?.addEventListener('click', (event) => {
            if (!event.target.classList.contains('remove-medicine-row')) {
                return;
            }

            if (tbody.querySelectorAll('tr').length === 1) {
                tbody.querySelector('input[name$="[name]"]').value = '';
                tbody.querySelector('input[name$="[dosage]"]').value = '';
                tbody.querySelector('input[name$="[duration]"]').value = '';
                return;
            }

            event.target.closest('tr').remove();
        });
    })();
</script>
@endpush
