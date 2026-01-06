<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Appointment;
use App\Models\Doctor;

class AppointmentController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $doctorId = Doctor::where('user_id', $userId)->value('id');
        // dd($doctorId);
        $appointments = Appointment::where('doctor_id', $doctorId)->get();
        return view('doctor.appointments.index', compact('appointments'));
    }
}
