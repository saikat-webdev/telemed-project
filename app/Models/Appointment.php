<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    public const STATUS_PENDING = 0;

    public const STATUS_CONFIRMED = 1;

    public const STATUS_FEES_PAID = 2;

    public const STATUS_COMPLETED = 3;

    public const STATUS_CANCELLED = 4;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'appointment_time',
        'comment',
        'status',
        'transaction_id',
    ];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => self::normalizeStatus($value),
            set: fn ($value) => (string) self::normalizeStatus($value),
        );
    }

    public static function normalizeStatus(mixed $status): int
    {
        if (is_int($status) || (is_string($status) && is_numeric(trim($status)))) {
            return match ((int) $status) {
                self::STATUS_PENDING,
                self::STATUS_CONFIRMED,
                self::STATUS_FEES_PAID,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED => (int) $status,
                default => self::STATUS_PENDING,
            };
        }

        return match (strtolower(trim((string) $status))) {
            'scheduled', 'pending' => self::STATUS_PENDING,
            'confirmed' => self::STATUS_CONFIRMED,
            'fees paid', 'fees_paid', 'fees-paid', 'paid' => self::STATUS_FEES_PAID,
            'completed', 'complete', 'done' => self::STATUS_COMPLETED,
            'cancelled', 'canceled' => self::STATUS_CANCELLED,
            default => self::STATUS_PENDING,
        };
    }

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
        return match ((int) $this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_FEES_PAID => 'Fees Paid',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }

    // Helper method for status color
    public function getStatusColorAttribute()
    {
        return match ((int) $this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-700',
            self::STATUS_CONFIRMED => 'bg-blue-100 text-blue-700',
            self::STATUS_FEES_PAID => 'bg-purple-100 text-purple-700',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-700',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
