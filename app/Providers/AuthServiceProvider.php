<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Employee;
use App\Policies\Admin\EmployeePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Employee::class => EmployeePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
