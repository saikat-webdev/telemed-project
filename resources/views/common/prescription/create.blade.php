@extends('doctor.layout.structure')

@section('content')
<div class="max-w-4xl mx-auto my-8">
    <div class="bg-white shadow-2xl rounded-sm p-12 border-t-8 border-blue-600 min-h-[1056px] flex flex-col">
        
        {{-- 1. Header: Logo & Doctor Info --}}
        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-8">
            <div>
                <h1 class="text-3xl font-black text-blue-600 tracking-tighter">TeleHealth</h1>
                <p class="text-xs text-gray-400 uppercase tracking-widest">Digital Healthcare Portal</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800">Dr. {{ Auth::user()->name }}</h2>
                <p class="text-blue-600 font-medium">{{ Auth::user()->doctorProfile->specialization ?? 'General Physician' }}</p>
                <p class="text-xs text-gray-500">Reg No: #{{ Auth::user()->id + 1000 }}</p>
            </div>
        </div>

        {{-- 2. Patient Demographics --}}
        <div class="grid grid-cols-4 gap-4 py-8 bg-gray-50/50 px-4 mt-6 rounded-xl border border-gray-100">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Patient Name</label>
                <input type="text" class="w-full bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0" name="patient_name" value="{{ $appointment->patient->name }}">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Age / Gender</label>
                <input type="text" class="w-full bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0" name="age_gender" placeholder="24/Male">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Weight</label>
                <input type="text" class="w-full bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0" placeholder="70kg">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Height</label>
                <input type="text" class="w-full bg-transparent border-none p-0 font-bold text-gray-800 focus:ring-0" placeholder="5'9''">
            </div>
        </div>

        {{-- 3. Clinical Section --}}
        <div class="mt-10 space-y-8 flex-1">
            <div>
                <h3 class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest mb-3">
                    <i class="fas fa-notes-medical text-blue-500"></i> Chief Complaints
                </h3>
                <textarea class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm focus:ring-2 focus:ring-blue-100" rows="3" placeholder="Patient is facing..."></textarea>
            </div>

            <div>
                <h3 class="flex items-center gap-2 text-sm font-black text-gray-800 uppercase tracking-widest mb-3">
                    <i class="fas fa-stethoscope text-blue-500"></i> Diagnosis & Notes
                </h3>
                <textarea class="w-full border-none bg-gray-50 rounded-xl p-4 text-sm focus:ring-2 focus:ring-blue-100" rows="3" placeholder="Clinical findings..."></textarea>
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
                    {{-- Rows will be injected here --}}
                    <tr>
                        <td class="p-3"><input type="text" name="medicines[]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="e.g. Paracetamol"></td>
                        <td class="p-3"><input type="text" name="dosage[]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="1-0-1 After Meal"></td>
                        <td class="p-3"><input type="text" name="duration[]" class="w-full border-none p-0 text-sm focus:ring-0 bg-transparent" placeholder="5 Days"></td>
                        <td class="p-3 text-center"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- 5. Footer & Signature --}}
        <div class="mt-12 pt-8 border-t border-gray-100 flex justify-between items-end">
            <div class="text-[10px] text-gray-400 italic">
                Generated via TeleHealth System on {{ now()->format('d M, Y h:i A') }}
            </div>
            <div class="text-center">
                <div class="mb-2">
                    {{-- Demo Digital Signature --}}
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
        <button class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold text-sm">Save Draft</button>
        <button class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-200">Issue Prescription</button>
    </div>
</div>
@endsection