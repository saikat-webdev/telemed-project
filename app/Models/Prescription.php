<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'patient_name',
        'age_gender',
        'weight',
        'height',
        'chief_complaints',
        'diagnosis_notes',
        'additional_notes',
        'medicines',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'medicines' => 'array',
            'issued_at' => 'datetime',
        ];
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
