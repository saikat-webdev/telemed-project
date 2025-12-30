<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Container\Attributes\Auth;

class AppointmentController extends Controller
{
    /**
     * Store a newly created appointment in storage.
     */
    public function index()
    {
        $appointments = Appointment::where('patient_id', auth()->id())->get();
        return view('appointments.index')->with('appointments', $appointments);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required',
        ]);


        $appointment = new Appointment();
        $appointment->doctor_id = $request->input('doctor_id');
        $appointment->patient_id = auth()->id();
        $appointment->appointment_date = $request->input('appointment_date');
        $appointment->appointment_time = $request->input('appointment_time');
        $appointment->comment = $request->input('comment');
        $appointment->status = 0;
        $appointment->save();

        return redirect()->route('dashboard.index')->with('success', 'Appointment booked successfully.');
    }
    
}
