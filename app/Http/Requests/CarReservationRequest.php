<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'car_id' => ['required', 'exists:cars,id'],
            'customer_id' => ['required', 'exists:users,id'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['required', Rule::in(['Pending', 'Confirmed', 'Cancelled', 'Completed'])],
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.required' => 'Car is required.',
            'car_id.exists' => 'Selected car does not exist.',

            'customer_id.required' => 'Customer is required.',
            'customer_id.exists' => 'Selected customer does not exist.',

            'reservation_date.required' => 'Reservation date is required.',
            'reservation_date.date' => 'Reservation date must be a valid date.',
            'reservation_date.after_or_equal' => 'Reservation date cannot be in the past.',

            'status.required' => 'Reservation status is required.',
            'status.in' => 'Status must be one of: Pending, Confirmed, Cancelled, Completed.',
        ];
    }
}