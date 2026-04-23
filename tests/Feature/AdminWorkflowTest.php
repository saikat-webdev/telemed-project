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

class AdminWorkflowTest extends TestCase
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

    public function test_admin_can_open_dashboard_and_update_appointment_status(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $doctorUser = User::factory()->create();
        $doctorUser->assignRole('doctor');

        $patient = User::factory()->create();
        $patient->assignRole('patient');

        $category = DoctorCategory::create([
            'name' => 'Dermatology',
            'description' => 'Skin',
            'status' => 1,
        ]);

        $doctor = Doctor::create([
            'name' => 'Dr. Grey',
            'email' => 'grey@example.com',
            'phone' => '6666666666',
            'specialization' => $category->id,
            'fees' => 400,
            'user_id' => $doctorUser->id,
        ]);

        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '09:00:00',
            'comment' => 'Skin rash',
            'status' => Appointment::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($admin)
            ->put(route('admin.appointments.updateStatus', $appointment->id), [
                'status' => Appointment::STATUS_CONFIRMED,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => (string) Appointment::STATUS_CONFIRMED,
        ]);
    }
}
