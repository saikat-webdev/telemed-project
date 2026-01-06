<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $doctorId = Doctor::where('user_id', $userId)->value('id');
        // dd($doctorId);
        $todaysAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', now()->toDateString())
            ->with('patient')
            ->get();
        // dd($todaysAppointments);
        return view('doctor.dashboard.index', compact('todaysAppointments'));
    }
}
