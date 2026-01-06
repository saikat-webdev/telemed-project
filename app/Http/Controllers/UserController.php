<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('User index page');
        // return view('user.index');
        if(auth()->check()){
            dd(auth()->user()->roles);
            if(auth()->user()->hasRole('admin')){
                dd('Admin dashboard');
                // return redirect()->route('admin.dashboard');
            }elseif(auth()->user()->hasRole('patient')){
                // dd('Patient dashboard');
                return redirect()->route('patient.dashboard.index');
            }elseif(auth()->user()->hasRole('doctor')){
                // dd('Doctor dashboard');
                return redirect()->route('doctor.dashboard');
            } else {
                dd('Role not defined');    
                // return redirect()->route('user.index');
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
