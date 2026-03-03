<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone_number' => 'required|unique:employees,phone_number',
            'branch_id' => 'required|exists:branches,id',
            'role' => 'required|in:admin,manager,receptionist,mechanic',
            'salary' => 'nullable|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email is already taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.unique' => 'Phone number is already taken.',
            'branch_id.required' => 'Branch ID is required.',
            'branch_id.exists' => 'Branch ID must exist in branches table.',
            'role.required' => 'Role is required.',
            'role.in' => 'Role must be one of: admin, manager, receptionist, mechanic.',
            'salary.numeric' => 'Salary must be a numeric value.'
        ];
    }
}
