<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorscategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'General Physician', 'icon' => 'fa-user-md'],
            ['name' => 'Cardiology', 'icon' => 'fa-heartbeat'],
            ['name' => 'Dermatology', 'icon' => 'fa-hand-holding-medical'],
            ['name' => 'Pediatrics', 'icon' => 'fa-child'],
            ['name' => 'Neurology', 'icon' => 'fa-brain'],
            ['name' => 'Orthopedics', 'icon' => 'fa-bone'],
            ['name' => 'Psychiatry', 'icon' => 'fa-smile'],
            ['name' => 'Gynecology', 'icon' => 'fa-female'],
            ['name' => 'Dentistry', 'icon' => 'fa-tooth'],
            ['name' => 'Ophthalmology', 'icon' => 'fa-eye'],
        ];

        foreach ($categories as $category) {
            DB::table('doctor_categories')->insert([
                'name' => $category['name'],
                'description' => $category['name'].' specialists',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
