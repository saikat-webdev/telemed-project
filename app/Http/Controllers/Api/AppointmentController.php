<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::with(['doctor.category', 'transaction', 'prescription'])
            ->where('patient_id', $request->user()->id)
            ->latest('appointment_date')
            ->paginate(15);

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required',
            'comment' => 'nullable|string',
        ]);

        $taken = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereIn('status', [0, 1, 2])
            ->exists();

        if ($taken) {
            return response()->json([
                'message' => 'The doctor is not available at the selected date and time.',
            ], 422);
        }

        $appointment = Appointment::create([
            ...$validated,
            'patient_id' => $request->user()->id,
            'status' => Appointment::STATUS_PENDING,
        ]);

        return response()->json($appointment->load('doctor.category'), 201);
    }
}
