<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
