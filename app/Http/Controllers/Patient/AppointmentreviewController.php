<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppointmentReview;

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

        $appointmentReview = new AppointmentReview();
        $appointmentReview->appointment_id = $request->appointment_id;
        $appointmentReview->patient_id = auth()->id();
        $appointmentReview->doctor_id = $request->doctor_id;
        $appointmentReview->review = $request->review;
        $appointmentReview->rating = $request->rating;
        $appointmentReview->save();

        return redirect()->back()->with('success', 'Your review has been submitted successfully.');
    }
}
