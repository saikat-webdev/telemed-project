<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'appointment_time',
        'comment',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match($this->status) {
                    0 => 'Pending',
                    1 => 'Confirmed',
                    2 => 'Cancelled',
                    default => 'Unknown',
                };
            }
        );
    }

    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                0 => 'bg-yellow-100 text-yellow-700',
                1 => 'bg-green-100 text-green-700',
                2 => 'bg-red-100 text-red-700',
                default => 'bg-gray-100 text-gray-700',
            }
        );
    }

    

    
}
