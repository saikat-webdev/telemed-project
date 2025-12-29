<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
        ]);

        $appointment = new \App\Models\Appointment();
        $appointment->doctor_id = $request->input('doctor_id');
        $appointment->patient_id = auth()->id();
        $appointment->appointment_date = $request->input('appointment_date');
        $appointment->status = 'scheduled';
        $appointment->save();

        return redirect()->route('dashboard.index')->with('success', 'Appointment booked successfully.');
    }
}
