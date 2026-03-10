<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'customer_id');
    }

    public function reservations()
    {
        return $this->hasMany(CarReservation::class, 'customer_id');
    }

    public function reviews()
    {
        return $this->hasMany(CarReview::class, 'customer_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}