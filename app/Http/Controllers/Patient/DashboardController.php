<?php

namespace App\Http\Controllers\Patient;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Message;

class DashboardController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patientId = auth()->id();

        $doctors = Doctor::with('category')
            ->latest()
            ->take(4)
            ->get();

        $appointments = Appointment::where('patient_id', $patientId)
            ->with(['doctor.category', 'transaction', 'review'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $latestMessages = Message::where('sender_id', $patientId)
            ->orWhere('receiver_id', $patientId)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'totalAppointments' => $appointments->count(),
            'upcomingAppointments' => $appointments->whereIn('status', [0, 1, 2])->count(),
            'completedAppointments' => $appointments->where('status', 3)->count(),
            'cancelledAppointments' => $appointments->where('status', 4)->count(),
            'unreadThreads' => $latestMessages->where('receiver_id', $patientId)->where('is_read', false)->count(),
        ];

        $upcomingAppointments = $appointments
            ->whereIn('status', [0, 1, 2])
            ->take(5);

        return view('patient.dashboard.index', compact('doctors', 'stats', 'upcomingAppointments'));
    }
}
