<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarReview extends Model
{
    protected $fillable = [
        'car_id',
        'customer_id',
        'rating',
        'comment'
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
