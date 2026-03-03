<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCategory extends Model
{
    public function cars()
    {
        return $this->hasMany(Car::class, 'category_id');
    }
}
