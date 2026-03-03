<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'performed_by');
    }
}
