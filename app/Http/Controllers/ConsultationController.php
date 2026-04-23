<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;

class ConsultationController extends Controller
{
    public function index(Appointment $appointment)
    {
        $userId = auth()->id();
        $doctorId = Doctor::where('user_id', $userId)->value('id');
        if ($doctorId !== $appointment->doctor_id && $userId !== $appointment->patient_id) {
            abort(403, 'You are not authorized to enter this room.');
        }

        $roomName = 'HealthHub_Live_'.md5($appointment->id.config('app.key'));

        return view('common.consultation.room', compact('appointment', 'roomName'));
    }
}
