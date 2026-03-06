<?php

namespace App\Policies\Admin;

use App\Models\User;

class EmployeePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }
    public function create(User $user)
    {
        return $user->role === 'admin';
    }
    public function view(User $user, User $employee)
    {
        return $user->role === 'admin' || $user->id === $employee->id;
    }
}
