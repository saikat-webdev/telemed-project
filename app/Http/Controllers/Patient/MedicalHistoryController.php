<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalHistory;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public function index()
    {
        $histories = auth()->user()
            ->medicalHistories()
            ->latest('recorded_at')
            ->latest()
            ->get();

        return view('patient.medical-history.index', compact('histories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'condition' => 'nullable|string|max:255',
            'allergies' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'notes' => 'nullable|string',
            'recorded_at' => 'nullable|date',
        ]);

        auth()->user()->medicalHistories()->create($validated);

        return redirect()
            ->route('patient.medical-history.index')
            ->with('success', 'Medical history added.');
    }

    public function update(Request $request, MedicalHistory $medicalHistory)
    {
        abort_unless($medicalHistory->patient_id === auth()->id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'condition' => 'nullable|string|max:255',
            'allergies' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'notes' => 'nullable|string',
            'recorded_at' => 'nullable|date',
        ]);

        $medicalHistory->update($validated);

        return redirect()
            ->route('patient.medical-history.index')
            ->with('success', 'Medical history updated.');
    }

    public function destroy(MedicalHistory $medicalHistory)
    {
        abort_unless($medicalHistory->patient_id === auth()->id(), 403);

        $medicalHistory->delete();

        return redirect()
            ->route('patient.medical-history.index')
            ->with('success', 'Medical history deleted.');
    }
}
