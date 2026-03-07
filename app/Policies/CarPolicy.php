<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Car;

class CarPolicy
{
    /**
     * Determine whether the user can view any cars.
     */
    public function viewAny(User $user): bool
    {
        return true; 
    }

    /**
     * Determine whether the user can view a specific car.
     */
    public function view(User $user, Car $car): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create a car.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can update a car.
     */
    public function update(User $user, Car $car): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete a car.
     */
    public function delete(User $user, Car $car): bool
    {
        return in_array($user->role, ['admin', 'manager']);
    }
}