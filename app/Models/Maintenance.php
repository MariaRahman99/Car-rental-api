<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'car_id',
        'maintenance_date',
        'next_due_date',
        'maintenance_type',
        'description',
        'cost',
        'performed_by',
    ];
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'performed_by');
    }
}
