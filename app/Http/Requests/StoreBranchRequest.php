<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'manager_id' => 'nullable|exists:employees,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Branch name is required.',
            'address.required' => 'Branch address is required.',
            'phone_number.required' => 'Branch phone number is required.',
            'manager_id.exists' => 'Selected manager does not exist.'
        ];
    }
}