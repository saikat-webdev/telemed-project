<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DashboardController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = auth()->user()->getRoleNames()->first();
        // dd($role);
        if($role == 'patient'){
            $doctors = Doctor::all()->take(3);
            return view('patient.dashboard.index')->with('doctors', $doctors);
        }
        return view('patient.dashboard.index');
    }
}
