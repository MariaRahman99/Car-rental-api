<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarReservation extends Model
{
    protected $fillable = [
        'car_id',
        'customer_id',
        'reservation_date',
        'status'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
