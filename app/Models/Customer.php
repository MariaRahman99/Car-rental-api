<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    public function reservations()
    {
        return $this->hasMany(CarReservation::class);
    }
    public function reviews()
    {
        return $this->hasMany(CarReview::class);
    }
}
