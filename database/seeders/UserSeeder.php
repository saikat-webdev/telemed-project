<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  Create an admin user
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => bcrypt('12345678'),                                                   
        ]);
        $admin->assignRole('admin');        
        // Create a patient user
        $patient = \App\Models\User::create([
            'name' => 'Patient User',
            'username' => 'patient',
            'email' => 'patient@patient.com',
            'password' => bcrypt('12345678'),
        ]);
        $patient->assignRole('patient');            
        //create a doctor user
        $doctorUser = \App\Models\User::create([
            'name' => 'Doctor User',
            'username' => 'doctor',
            'email' => 'doctor@doctor.com',
            'password' => bcrypt('12345678'),
        ]);
        $doctorUser->assignRole('doctor');
    }
}
