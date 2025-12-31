<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentReview extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'review',
        'rating',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'id');
    }
}
