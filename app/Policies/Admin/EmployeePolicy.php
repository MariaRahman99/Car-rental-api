<?php

namespace App\Policies\Admin;

use App\Models\User;

class EmployeePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function create(User $user)
    {
        return $user->role === 'admin';
    }
}
