<?php

namespace App\Providers;

use App\Models\Employee;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Policies\Admin\EmployeePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => EmployeePolicy::class,
        Employee::class => EmployeePolicy::class
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
