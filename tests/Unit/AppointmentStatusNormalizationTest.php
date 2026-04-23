<?php

namespace Tests\Unit;

use App\Models\Appointment;
use PHPUnit\Framework\TestCase;

class AppointmentStatusNormalizationTest extends TestCase
{
    public function test_it_normalizes_legacy_status_strings(): void
    {
        $appointment = new Appointment;

        $appointment->status = 'scheduled';
        $this->assertSame(Appointment::STATUS_PENDING, $appointment->status);
        $this->assertSame('Pending', $appointment->status_label);

        $appointment->status = 'confirmed';
        $this->assertSame(Appointment::STATUS_CONFIRMED, $appointment->status);
        $this->assertSame('Confirmed', $appointment->status_label);

        $appointment->status = 'paid';
        $this->assertSame(Appointment::STATUS_FEES_PAID, $appointment->status);
        $this->assertSame('Fees Paid', $appointment->status_label);

        $appointment->status = 'completed';
        $this->assertSame(Appointment::STATUS_COMPLETED, $appointment->status);
        $this->assertSame('Completed', $appointment->status_label);

        $appointment->status = 'cancelled';
        $this->assertSame(Appointment::STATUS_CANCELLED, $appointment->status);
        $this->assertSame('Cancelled', $appointment->status_label);
    }

    public function test_it_normalizes_invalid_values_to_pending(): void
    {
        $appointment = new Appointment;

        $appointment->status = 'mystery-state';

        $this->assertSame(Appointment::STATUS_PENDING, $appointment->status);
        $this->assertSame('Pending', $appointment->status_label);
    }
}
