<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class DoctorWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('patient');
        Role::findOrCreate('doctor');
        Role::findOrCreate('admin');
    }

    public function test_doctor_can_update_profile_and_issue_prescription(): void
    {
        $doctorUser = User::factory()->create();
        $doctorUser->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $category = DoctorCategory::create([
            'name' => 'General Medicine',
            'description' => 'General',
            'status' => 1,
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. Strange',
            'email' => 'strange@example.com',
            'phone' => '8888888888',
            'specialization' => $category->id,
            'fees' => 700,
            'user_id' => $doctorUser->id,
        ]);

        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '13:00:00',
            'comment' => 'Migraine',
            'status' => Appointment::STATUS_FEES_PAID,
        ]);

        $this->actingAs($doctorUser)
            ->put(route('doctor.profile.update'), [
                'name' => 'Dr. Stephen Strange',
                'email' => 'stephen@example.com',
                'phone' => '7777777777',
                'specialization' => $category->id,
                'fees' => 900,
            ])
            ->assertRedirect(route('doctor.profile.edit'));

        $this->assertDatabaseHas('doctors', [
            'id' => $doctor->id,
            'name' => 'Dr. Stephen Strange',
            'email' => 'stephen@example.com',
            'fees' => 900,
        ]);

        $this->actingAs($doctorUser)
            ->post(route('doctor.prescription.store', $appointment), [
                'patient_name' => $patient->name,
                'age_gender' => '30/Male',
                'weight' => '72kg',
                'height' => '5ft 9in',
                'chief_complaints' => 'Severe headache',
                'diagnosis_notes' => 'Migraine suspected',
                'additional_notes' => 'Hydrate well',
                'medicines' => [
                    ['name' => 'Paracetamol', 'dosage' => '1-0-1', 'duration' => '5 days'],
                ],
            ])
            ->assertRedirect(route('prescription.show', $appointment));

        $this->assertDatabaseHas('prescriptions', [
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);
    }

    public function test_doctor_can_open_prescription_create_page_before_any_prescription_exists(): void
    {
        $doctorUser = User::factory()->create();
        $doctorUser->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $category = DoctorCategory::create([
            'name' => 'Neurology',
            'description' => 'Brain',
            'status' => 1,
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. Banner',
            'email' => 'banner@example.com',
            'phone' => '5555555555',
            'specialization' => $category->id,
            'fees' => 650,
            'user_id' => $doctorUser->id,
        ]);

        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '15:00:00',
            'comment' => 'Dizziness',
            'status' => Appointment::STATUS_FEES_PAID,
        ]);

        $this->actingAs($doctorUser)
            ->get(route('doctor.prescription.create', $appointment))
            ->assertOk()
            ->assertSee('Issue Prescription');
    }
}
