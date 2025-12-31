<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;

class DoctorCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'status'
    ];
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
