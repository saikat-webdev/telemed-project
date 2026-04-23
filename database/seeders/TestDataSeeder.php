<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================================
        // CREATE ROLES
        // ============================================
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $doctorRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'doctor']);
        $patientRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'patient']);

        // ============================================
        // CREATE CATEGORIES
        // ============================================
        $categories = ['Cardiology', 'Neurology', 'Pediatrics', 'Dermatology', 'Orthopedics', 'General Medicine', 'Psychiatry', 'Ophthalmology'];
        foreach ($categories as $cat) {
            DoctorCategory::firstOrCreate(['name' => $cat]);
        }

        // ============================================
        // CREATE ADMIN USER
        // ============================================
        $admin = User::firstOrCreate(
            ['email' => 'admin@telemed.com'],
            ['name' => 'System Admin', 'username' => 'admin', 'password' => bcrypt('12345678')]
        );
        $admin->assignRole('admin');

        // ============================================
        // CREATE DOCTOR USERS & PROFILES
        // ============================================
        $doctorData = [
            ['name' => 'Dr. Sarah Johnson', 'email' => 'sarah@telemed.com', 'category' => 'Cardiology', 'fees' => 500],
            ['name' => 'Dr. Michael Chen', 'email' => 'michael@telemed.com', 'category' => 'Neurology', 'fees' => 600],
            ['name' => 'Dr. Emily Davis', 'email' => 'emily@telemed.com', 'category' => 'Pediatrics', 'fees' => 400],
            ['name' => 'Dr. James Wilson', 'email' => 'james@telemed.com', 'category' => 'Dermatology', 'fees' => 350],
            ['name' => 'Dr. Lisa Anderson', 'email' => 'lisa@telemed.com', 'category' => 'Orthopedics', 'fees' => 550],
        ];

        $doctors = [];
        foreach ($doctorData as $doc) {
            // Create user
            $user = User::firstOrCreate(
                ['email' => $doc['email']],
                ['name' => $doc['name'], 'username' => strtok($doc['email'], '@'), 'password' => bcrypt('12345678')]
            );
            $user->assignRole('doctor');

            // Get category
            $category = DoctorCategory::where('name', $doc['category'])->first();

            // Create doctor profile linked to user
            $doctor = Doctor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $doc['name'],
                    'specialization' => $category->id,
                    'email' => $doc['email'],
                    'phone' => '555-'.rand(1000, 9999),
                    'fees' => $doc['fees'],
                ]
            );

            $doctors[] = $doctor;
        }

        // ============================================
        // CREATE PATIENT USERS
        // ============================================
        $patientData = [
            ['name' => 'John Smith', 'email' => 'john@patient.com'],
            ['name' => 'Mary Johnson', 'email' => 'mary@patient.com'],
            ['name' => 'Robert Brown', 'email' => 'robert@patient.com'],
            ['name' => 'Patricia Davis', 'email' => 'patricia@patient.com'],
            ['name' => 'William Miller', 'email' => 'william@patient.com'],
        ];

        $patients = [];
        foreach ($patientData as $pat) {
            $user = User::firstOrCreate(
                ['email' => $pat['email']],
                ['name' => $pat['name'], 'username' => strtok($pat['email'], '@'), 'password' => bcrypt('12345678')]
            );
            $user->assignRole('patient');
            $patients[] = $user;
        }

        // ============================================
        // CREATE APPOINTMENTS
        // ============================================
        $comments = ['Regular checkup', 'Follow-up visit', 'New symptoms', 'Prescription renewal', 'Lab results', 'Consultation'];

        // Appointments for today
        foreach ($doctors as $index => $doctor) {
            $patient = $patients[array_rand($patients)];

            Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'appointment_date' => now()->toDateString(),
                'appointment_time' => sprintf('%02d:00:00', 9 + $index * 2),
                'comment' => $comments[array_rand($comments)],
                'status' => rand(0, 2),
            ]);
        }

        // More appointments for today
        for ($i = 0; $i < 5; $i++) {
            $doctor = $doctors[array_rand($doctors)];
            $patient = $patients[array_rand($patients)];

            Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'appointment_date' => now()->toDateString(),
                'appointment_time' => sprintf('%02d:30:00', 10 + $i * 2),
                'comment' => $comments[array_rand($comments)],
                'status' => rand(0, 3),
            ]);
        }

        // Tomorrow's appointments
        for ($i = 0; $i < 3; $i++) {
            $doctor = $doctors[array_rand($doctors)];
            $patient = $patients[array_rand($patients)];

            Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'appointment_date' => now()->addDay()->toDateString(),
                'appointment_time' => sprintf('%02d:00:00', 9 + $i * 3),
                'comment' => $comments[array_rand($comments)],
                'status' => 0,
            ]);
        }

        $this->command->info('Test data seeded successfully!');
        $this->command->info('Users: '.User::count());
        $this->command->info('Doctors: '.Doctor::count());
        $this->command->info('Appointments: '.Appointment::count());
    }
}
