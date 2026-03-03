<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
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
