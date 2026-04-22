<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $specializations = ['Cardiology', 'Neurology', 'Pediatrics', 'Dermatology', 'General Surgery'];
        
        // Get or create the doctor user
        $doctorUser = User::where('email', 'doctor@doctor.com')->first();
        
        if (!$doctorUser) {
            $doctorUser = User::create([
                'name' => 'Dr. Sarah Johnson',
                'username' => 'doctor',
                'email' => 'doctor@doctor.com',
                'password' => bcrypt('12345678'),
            ]);
            $doctorUser->assignRole('doctor');
        }
        
        // Get or create doctor profile linked to the user
        $doctor = Doctor::firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'name' => 'Dr. Sarah Johnson',
                'specialization' => 'Cardiology',
                'email' => $doctorUser->email,
                'phone' => $faker->phoneNumber(),
                'fees' => 500.00,
            ]
        );
        
        // Create sample appointments (only if none exist for this doctor)
        if (Appointment::where('doctor_id', $doctor->id)->count() == 0) {
            $patientUser = User::where('email', 'patient@patient.com')->first();
            
            if ($patientUser) {
                // Today's appointments
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patientUser->id,
                    'appointment_date' => now()->toDateString(),
                    'appointment_time' => '09:00:00',
                    'comment' => 'Regular checkup',
                    'status' => 1,
                ]);
                
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patientUser->id,
                    'appointment_date' => now()->toDateString(),
                    'appointment_time' => '10:30:00',
                    'comment' => 'Follow-up consultation',
                    'status' => 1,
                ]);
                
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patientUser->id,
                    'appointment_date' => now()->toDateString(),
                    'appointment_time' => '14:00:00',
                    'comment' => 'New patient intake',
                    'status' => 2,
                ]);
                
                // Tomorrow's appointments
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'patient_id' => $patientUser->id,
                    'appointment_date' => now()->addDay()->toDateString(),
                    'appointment_time' => '11:00:00',
                    'comment' => 'Lab results review',
                    'status' => 0,
                ]);
            }
        }
        
        // Create additional sample doctors (not linked to users)
        foreach (range(1, 9) as $index) {
            DB::table('doctors')->insert([
                'name' => 'Dr. ' . $faker->name,
                'specialization' => $faker->randomElement($specializations),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'fees' => $faker->randomElement([300, 400, 500, 600, 700]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}