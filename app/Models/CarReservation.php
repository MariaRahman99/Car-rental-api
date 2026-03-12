<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarReservation extends Model
{
    protected $fillable = [
        'car_id',
        'customer_id',
        'reservation_date',
        'rental_start_date',
        'rental_end_date',
        'insurance_option',
        'status',
        'is_paid',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'is_paid' => 'boolean',
        'insurance_option' => 'boolean',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'reservation_id');
    }
}