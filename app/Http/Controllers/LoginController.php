<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            // dd($request->all());
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'username' => 'required|string|min:5',
            ]);
            // dd($request->all());
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole('patient');

            return redirect('/login')->with('success', 'Registration successful!');
        }

        return view('auth.register');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->only('email', 'password');

            if (auth()->attempt($credentials)) {
                // dd("User found");
                // dd(auth()->user()->roles);
                if (auth()->user()->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif (auth()->user()->hasRole('patient')) {
                    return redirect()->route('patient.dashboard.index');
                } elseif (auth()->user()->hasRole('doctor')) {
                    return redirect()->route('doctor.dashboard');
                } else {
                    return redirect()->route('user.index');
                }
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        }

        return view('auth.login');
    }
}
