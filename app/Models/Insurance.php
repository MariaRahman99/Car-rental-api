<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        "company_name",

    ];
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
