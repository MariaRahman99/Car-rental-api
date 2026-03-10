<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1886|max:' . date('Y'),

            'vin' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cars', 'vin')->ignore($id),
            ],

            'license_plate' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cars', 'license_plate')->ignore($id),
            ],

            'color' => 'nullable|string|max:255',
            'mileage' => 'required|integer|min:0',
            'status' => 'required|in:Available,Rented,Maintenance,Reserved',
            'rental_rate' => 'required|numeric|min:0',

            'category.name' => 'required|string|max:255',
            'category.description' => 'nullable|string',

            'branch.name' => 'required|string|max:255',
            'branch.address' => 'required|string|max:500',
            'branch.phone_number' => 'required|string|max:20',
            'branch.manager_id' => 'nullable|exists:employees,id',

            'insurance.company_name' => 'required|string|max:255',
        ];
    }

    public function carData(): array
    {
        return $this->safe()->except(['category', 'branch', 'insurance']);
    }

    public function categoryData(): array
    {
        return $this->validated()['category'] ?? [];
    }

    public function branchData(): array
    {
        return $this->validated()['branch'] ?? [];
    }

    public function insuranceData(): array
    {
        return $this->validated()['insurance'] ?? [];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Car name is required.',
            'model.required' => 'Car model is required.',
            'year.required' => 'Manufacturing year is required.',
            'year.integer' => 'Manufacturing year must be an integer.',
            'year.min' => 'Manufacturing year must be at least 1886.',
            'year.max' => 'Manufacturing year cannot be in the future.',

            'vin.required' => 'VIN is required.',
            'vin.unique' => 'VIN must be unique.',

            'license_plate.required' => 'License plate is required.',
            'license_plate.unique' => 'License plate must be unique.',

            'mileage.required' => 'Mileage is required.',
            'mileage.integer' => 'Mileage must be an integer.',
            'mileage.min' => 'Mileage cannot be negative.',

            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: Available, Rented, Maintenance, Reserved.',

            'rental_rate.required' => 'Rental rate is required.',
            'rental_rate.numeric' => 'Rental rate must be a number.',
            'rental_rate.min' => 'Rental rate cannot be negative.',

            'category.name.required' => 'Category name is required.',

            'branch.name.required' => 'Branch name is required.',
            'branch.address.required' => 'Branch address is required.',
            'branch.phone_number.required' => 'Branch phone number is required.',
            'branch.manager_id.exists' => 'Selected branch manager does not exist.',

            'insurance.company_name.required' => 'Insurance company name is required.',
        ];
    }
}