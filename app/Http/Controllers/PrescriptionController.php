<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class PrescriptionController extends Controller
{
    public function create(Appointment$appointment)
    {
        // dd($appointment);
        // Logic to show the prescription creation form for the given appointment
        return view('common.prescription.create', compact('appointment'));
    }
}
