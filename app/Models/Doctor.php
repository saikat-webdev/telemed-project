<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'specialization',
        'email',
        'phone',
        'fees',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function category()
    {
        return $this->belongsTo(DoctorCategory::class, 'specialization', 'id');
    }
}
