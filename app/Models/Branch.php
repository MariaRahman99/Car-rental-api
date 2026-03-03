<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
}
