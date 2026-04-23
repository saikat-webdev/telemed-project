<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';

    protected $description = 'Send email and SMS-ready reminders for upcoming appointments';

    public function handle(): int
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED, Appointment::STATUS_FEES_PAID])
            ->whereBetween('appointment_date', [now()->toDateString(), now()->addDay()->toDateString()])
            ->whereNull('reminder_sent_at')
            ->get();

        foreach ($appointments as $appointment) {
            if ($appointment->patient) {
                $appointment->patient->notify(new AppointmentReminderNotification($appointment));
            }

            Log::info('Appointment SMS reminder prepared', [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor' => $appointment->doctor->name ?? null,
                'date' => $appointment->appointment_date?->format('Y-m-d'),
                'time' => $appointment->appointment_time?->format('H:i:s'),
            ]);

            $appointment->update(['reminder_sent_at' => now()]);
        }

        $this->info("Processed {$appointments->count()} appointment reminders.");

        return self::SUCCESS;
    }
}
