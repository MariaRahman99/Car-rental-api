<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\User;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('employee')
            ->when($request->search, function ($query, $search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return $this->success(
            'Users retrieved successfully',
            AdminUserResource::collection($users)
        );
    }

    public function store(StoreEmployeeRequest $request)
    {
        $user = User::create($request->userData());

        Employee::create(
            $request->employeeData($user->id)
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(
            'Employee created successfully',
            [
                'employee' => new AdminUserResource($user->load('employee')),
            ],
            201,
            [
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        );
    }

    public function show($id)
    {
        $user = User::with('employee')->findOrFail($id);

        return $this->success(
            'Employee retrieved successfully',
            new AdminUserResource($user)
        );
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $user = User::with('employee')->findOrFail($id);

        $userData = $request->userData();
        $employeeData = $request->employeeData($user->id);

        if (isset($userData['password']) && !empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        if ($user->employee) {
            $user->employee->update($employeeData);
        } else {
            Employee::create($employeeData);
        }

        return $this->success(
            'Employee updated successfully',
            new AdminUserResource($user->fresh('employee'))
        );
    }

    public function destroy($id)
    {
        $user = User::with('employee')->findOrFail($id);

        if ($user->employee) {
            $user->employee->delete();
        }

        $user->delete();

        return $this->success(
            'Employee deleted successfully',
            null
        );
    }
}