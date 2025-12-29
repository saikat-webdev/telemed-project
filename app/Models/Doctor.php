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
    ];

    
}
