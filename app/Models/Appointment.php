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
        'transaction_id'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'id');
    }

    public function review()
    {
        return $this->hasOne(AppointmentReview::class, 'appointment_id', 'id');
    }
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
    
    // Helper method for status label
    public function getStatusLabelAttribute()
    {
        return match((int)$this->status) {
            0 => 'Pending',
            1 => 'Confirmed',
            2 => 'Fees Paid',
            3 => 'Completed',
            4 => 'Cancelled',
            default => 'Unknown',
        };
    }

    // Helper method for status color
    public function getStatusColorAttribute()
    {
        return match((int)$this->status) {
            0 => 'bg-yellow-100 text-yellow-700',
            1 => 'bg-blue-100 text-blue-700',
            2 => 'bg-purple-100 text-purple-700',
            3 => 'bg-green-100 text-green-700',
            4 => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    

    
}
