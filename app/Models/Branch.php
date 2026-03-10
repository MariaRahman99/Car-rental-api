<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'manager_id',
    ];

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