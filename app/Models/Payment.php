<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id',
        'rental_id',
        'amount',
        'payment_method',
        'payment_date',
        'status',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function reservation()
    {
        return $this->belongsTo(CarReservation::class, 'reservation_id');
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}