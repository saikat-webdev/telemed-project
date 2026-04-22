<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $doctor = Doctor::where('user_id', $userId)->first();
        
        if (!$doctor) {
            abort(403, 'You are not authorized to view doctor dashboard.');
        }
        
        $todaysAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', now()->toDateString())
            ->with('patient')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $unreadMessages = Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        $stats = [
            'unreadMessages' => $unreadMessages,
            'totalActiveAppointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereIn('status', [0, 1, 2])
                ->count(),
        ];

        return view('doctor.dashboard.index', compact('todaysAppointments', 'stats'));
    }
}
