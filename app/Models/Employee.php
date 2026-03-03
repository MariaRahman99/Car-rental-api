<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'performed_by');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
