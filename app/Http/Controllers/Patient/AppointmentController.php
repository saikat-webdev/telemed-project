<?php

namespace App\Http\Controllers\Patient;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends \App\Http\Controllers\Controller
{
    /**
     * Store a newly created appointment in storage.
     */
    public function index()
    {
        $appointments = Appointment::where('patient_id', auth()->id())
            ->with(['doctor.category', 'transaction', 'review'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patient.appointments.index', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required',
        ]);
        
        //check if the doctor is available at the given date and time
        $existingAppointment = Appointment::where('doctor_id', $request->input('doctor_id'))
            ->where('appointment_date', $request->input('appointment_date'))
            ->where('appointment_time', $request->input('appointment_time'))
            ->whereIn('status', [0, 1, 2]) //consider only pending, confirmed, and fees paid appointments
            ->first();
        if ($existingAppointment) {
            return redirect()->back()->withErrors(['appointment_time' => 'The doctor is not available at the selected date and time. Please choose a different slot.'])->withInput();
        }
        $appointment = new Appointment();
        $appointment->doctor_id = $request->input('doctor_id');
        $appointment->patient_id = auth()->id();
        $appointment->appointment_date = $request->input('appointment_date');
        $appointment->appointment_time = $request->input('appointment_time');
        $appointment->comment = $request->input('comment');
        $appointment->status = 0;
        $appointment->save();

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment booked successfully.');
    }

    public function cancel(Appointment $appointment)
    {
        abort_unless($appointment->patient_id === auth()->id(), 403);

        if (! in_array((int) $appointment->status, [0, 1], true)) {
            return redirect()
                ->route('patient.appointments.index')
                ->withErrors(['appointment' => 'Only pending or confirmed appointments can be cancelled.']);
        }

        $appointment->update(['status' => 4]);

        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }
}
