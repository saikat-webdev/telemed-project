<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorCategory;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::query()
                    ->when($request->category_id, function ($query, $categoryId) {
                        return $query->where('specialization', $categoryId);
                    })
                    ->when($request->search, function ($query, $search) {
                        return $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->get();
        
        // dd($doctors);
        // Fetch categories for the dropdown filter
        $categories = DoctorCategory::all();

        return view('patient.doctors.index', compact('doctors', 'categories'));
    }    
}
