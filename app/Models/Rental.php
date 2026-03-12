<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'customer_id',
        'car_id',
        'employee_id',
        'discount_id',
        'rental_start_date',
        'rental_end_date',
        'actual_return_date',
        'total_amount',
        'status',
        'insurance_option',
        'fuel_level_start',
        'fuel_level_end',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
}