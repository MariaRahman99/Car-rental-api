<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'position',
        'branch_id',
        'hire_date',
        'salary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
}