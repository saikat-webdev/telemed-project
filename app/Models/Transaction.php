<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'appointment_id',
        'stripe_transaction_id',
        'amount',
        'currency',
        'status',
    ];
}
