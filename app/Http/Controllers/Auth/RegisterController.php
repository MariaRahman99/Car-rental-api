<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class RegisterController extends Controller
{
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [

        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone_number' => 'required|unique:employees,phone_number',
        'branch_id' => 'required|exists:branches,id',
        'role' => 'required|in:admin,manager,receptionist,mechanic',
        'salary' => 'nullable|numeric'

    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();

    try {

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'position' => ucfirst($request->role),
            'branch_id' => $request->branch_id,
            'hire_date' => now(),
            'salary' => $request->salary
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Employee registered successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'data' => [
                'user' => $user,
                'employee' => $employee
            ]
        ], 201);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Registration failed',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
