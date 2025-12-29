<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $specializations = ['Cardiology', 'Neurology', 'Pediatrics', 'Dermatology', 'General Surgery'];

        // Create 10 sample doctors
        foreach (range(1, 10) as $index) {
            DB::table('doctors')->insert([
                'name' => 'Dr. ' . $faker->name,
                'specialization' => $faker->randomElement($specializations),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}