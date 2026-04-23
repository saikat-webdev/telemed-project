<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(protected Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment Reminder')
            ->greeting('Hello '.$notifiable->name.',')
            ->line('This is a reminder for your upcoming appointment.')
            ->line('Doctor: Dr. '.($this->appointment->doctor->name ?? 'Doctor'))
            ->line('Date: '.$this->appointment->appointment_date->format('d M Y'))
            ->line('Time: '.$this->appointment->appointment_time->format('h:i A'))
            ->action('View Appointments', route('patient.appointments.index'))
            ->line('Please join on time or reschedule if needed.');
    }
}
