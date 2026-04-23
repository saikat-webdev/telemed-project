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

class PatientWorkflowTest extends TestCase
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

    public function test_patient_can_manage_medical_history_and_reschedule_an_appointment(): void
    {
        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $doctorUser = User::factory()->create();
        $doctorUser->assignRole('doctor');

        $category = DoctorCategory::create([
            'name' => 'Cardiology',
            'description' => 'Heart',
            'status' => 1,
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. House',
            'email' => 'house@example.com',
            'phone' => '9999999999',
            'specialization' => $category->id,
            'fees' => 500,
            'user_id' => $doctorUser->id,
        ]);

        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '10:00:00',
            'comment' => 'Follow-up',
            'status' => Appointment::STATUS_PENDING,
        ]);

        $this->actingAs($patient)
            ->post(route('patient.medical-history.store'), [
                'title' => 'Asthma history',
                'condition' => 'Asthma',
                'allergies' => 'Dust',
                'current_medications' => 'Inhaler',
                'notes' => 'Carry inhaler during season change',
                'recorded_at' => now()->toDateString(),
            ])
            ->assertRedirect(route('patient.medical-history.index'));

        $this->assertDatabaseHas('medical_histories', [
            'patient_id' => $patient->id,
            'title' => 'Asthma history',
        ]);

        $this->actingAs($patient)
            ->patch(route('patient.appointments.reschedule', $appointment), [
                'appointment_date' => now()->addDays(3)->toDateString(),
                'appointment_time' => '11:30',
            ])
            ->assertRedirect(route('patient.appointments.index'));

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'appointment_time' => '11:30',
        ]);
    }
}
