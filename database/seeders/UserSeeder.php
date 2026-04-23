<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  Create an admin user (only if not exists)
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => bcrypt('12345678'),
            ]
        );
        $admin->assignRole('admin');

        // Create a patient user (only if not exists)
        $patient = User::firstOrCreate(
            ['email' => 'patient@patient.com'],
            [
                'name' => 'Patient User',
                'username' => 'patient',
                'password' => bcrypt('12345678'),
            ]
        );
        $patient->assignRole('patient');

        // Create a doctor user (only if not exists)
        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor@doctor.com'],
            [
                'name' => 'Dr. Sarah Johnson',
                'username' => 'doctor',
                'password' => bcrypt('12345678'),
            ]
        );
        $doctorUser->assignRole('doctor');
    }
}
