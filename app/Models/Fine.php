<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
    