<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorCategory;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected function currentDoctor(): Doctor
    {
        return Doctor::where('user_id', auth()->id())->firstOrFail();
    }

    public function edit()
    {
        $doctor = $this->currentDoctor();
        $categories = DoctorCategory::where('status', 1)->orderBy('name')->get();

        return view('doctor.profile.edit', compact('doctor', 'categories'));
    }

    public function update(Request $request)
    {
        $doctor = $this->currentDoctor();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,'.$doctor->id,
            'phone' => 'nullable|string|max:50',
            'specialization' => 'required|exists:doctor_categories,id',
            'fees' => 'required|numeric|min:0',
        ]);

        $doctor->update($validated);
        $doctor->user?->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return redirect()
            ->route('doctor.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
