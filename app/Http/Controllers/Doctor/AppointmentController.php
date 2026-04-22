<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected function currentDoctor(): Doctor
    {
        return Doctor::where('user_id', auth()->id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $doctor = $this->currentDoctor();
        
        $query = Appointment::where('doctor_id', $doctor->id)
            ->with(['patient', 'transaction'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc');
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $appointments = $query->get();
        
        return view('doctor.appointments.index', compact('appointments'));
    }
    
    /**
     * Show appointment details for doctor
     */
    public function show($id)
    {
        $doctor = $this->currentDoctor();
        
        $appointment = Appointment::with(['patient', 'review', 'transaction', 'doctor'])
            ->where('doctor_id', $doctor->id)
            ->findOrFail($id);
        
        return view('doctor.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $doctor = $this->currentDoctor();

        abort_unless($appointment->doctor_id === $doctor->id, 403);

        $validated = $request->validate([
            'status' => 'required|integer|in:1,3,4',
        ]);

        $targetStatus = (int) $validated['status'];
        $currentStatus = (int) $appointment->status;

        $allowedTransitions = match ($currentStatus) {
            0 => [1, 4],
            1 => [4],
            2 => [3],
            default => [],
        };

        if (! in_array($targetStatus, $allowedTransitions, true)) {
            return redirect()
                ->back()
                ->withErrors(['status' => 'That status change is not allowed for this appointment.']);
        }

        $appointment->update(['status' => $targetStatus]);

        return redirect()
            ->back()
            ->with('success', 'Appointment status updated successfully.');
    }
}
