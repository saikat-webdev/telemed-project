<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentReview;
use Illuminate\Http\Request;

class AppointmentreviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id' => 'required|exists:doctors,id',
            'review' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Verify the appointment belongs to the logged-in patient
        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('patient_id', auth()->id())
            ->first();

        if (! $appointment) {
            return back()->withErrors(['appointment_id' => 'You can only review your own appointments.']);
        }

        // Check if a review already exists for this appointment
        $existingReview = AppointmentReview::where('appointment_id', $request->appointment_id)->first();
        if ($existingReview) {
            return back()->withErrors(['review' => 'You have already reviewed this appointment.']);
        }

        $appointmentReview = new AppointmentReview;
        $appointmentReview->appointment_id = $request->appointment_id;
        $appointmentReview->patient_id = auth()->id();
        $appointmentReview->doctor_id = $request->doctor_id;
        $appointmentReview->review = $request->review;
        $appointmentReview->rating = $request->rating;
        $appointmentReview->save();

        return redirect()->back()->with('success', 'Your review has been submitted successfully.');
    }
}
