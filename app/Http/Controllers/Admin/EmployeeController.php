<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Resources\AdminUserResource;
use App\Models\User;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);

        return $this->success(
            'Users retrieved successfully',
            AdminUserResource::collection($users)
        );
    }

    public function store(StoreEmployeeRequest $request)
    {
        
        DB::beginTransaction();

        try {

            $user = User::create($request->userData());

            $token = $user->createToken('auth_token')->plainTextToken;

            $employee = Employee::create(
                $request->employeeData($user->id)
            );

            DB::commit();

            return $this->success(
                'Employee created successfully',
                [
                    'employee' => new AdminUserResource($user),
                ],
                201,
                [
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->error(
                'Employee creation failed',
                500,
                $e->getMessage()
            );
        }
    }
    public function show($id)
    {
        $user = User::with('employee')->find($id); 

        if (!$user) {
            return $this->error('User not found', 404);
        }

        $this->authorize('view', $user); 
        return $this->success(
            'User retrieved successfully',
            new AdminUserResource($user)
        );
    }
}