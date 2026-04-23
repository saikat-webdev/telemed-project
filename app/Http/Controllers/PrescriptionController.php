<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Services\PrescriptionPdfRenderer;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    protected function authorizeAppointment(Appointment $appointment): void
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        $canView = $user->hasRole('admin')
            || ($doctor && $doctor->id === $appointment->doctor_id)
            || $appointment->patient_id === $user->id;

        abort_unless($canView, 403);
    }

    public function create(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        abort_unless(auth()->user()->hasRole('doctor'), 403);

        $appointment->load(['patient', 'doctor', 'prescription']);

        return view('common.prescription.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        abort_unless(auth()->user()->hasRole('doctor'), 403);

        $validated = $request->validate([
            'patient_name' => 'required|string|max:255',
            'age_gender' => 'nullable|string|max:100',
            'weight' => 'nullable|string|max:50',
            'height' => 'nullable|string|max:50',
            'chief_complaints' => 'nullable|string',
            'diagnosis_notes' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.name' => 'required|string|max:255',
            'medicines.*.dosage' => 'nullable|string|max:255',
            'medicines.*.duration' => 'nullable|string|max:255',
        ]);

        $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();
        $medicines = collect($validated['medicines'])
            ->filter(fn (array $medicine) => filled($medicine['name'] ?? null))
            ->values()
            ->all();

        $prescription = Prescription::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'doctor_id' => $doctor->id,
                'patient_id' => $appointment->patient_id,
                'patient_name' => $validated['patient_name'],
                'age_gender' => $validated['age_gender'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'height' => $validated['height'] ?? null,
                'chief_complaints' => $validated['chief_complaints'] ?? null,
                'diagnosis_notes' => $validated['diagnosis_notes'] ?? null,
                'additional_notes' => $validated['additional_notes'] ?? null,
                'medicines' => $medicines,
                'issued_at' => now(),
            ]
        );

        return redirect()
            ->route('prescription.show', $appointment)
            ->with('success', $prescription->wasRecentlyCreated ? 'Prescription issued successfully.' : 'Prescription updated successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $appointment->load(['patient', 'doctor', 'prescription']);

        abort_unless($appointment->prescription, 404);

        return view('common.prescription.show', compact('appointment'));
    }

    public function download(Appointment $appointment, PrescriptionPdfRenderer $renderer)
    {
        $this->authorizeAppointment($appointment);

        $appointment->load(['patient', 'doctor', 'prescription']);

        abort_unless($appointment->prescription, 404);

        $pdf = $renderer->render($appointment->prescription, $appointment);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="prescription-'.$appointment->id.'.pdf"',
        ]);
    }
}
