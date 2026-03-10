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
        return [
            'car_id' => ['required', 'exists:cars,id'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['nullable', Rule::in(['Pending', 'Confirmed', 'Cancelled', 'Completed'])],
        ];
    }

    public function messages(): array
    {
        return [
            'car_id.required' => 'Car is required.',
            'car_id.exists' => 'Selected car does not exist.',
            'reservation_date.required' => 'Reservation date is required.',
            'reservation_date.date' => 'Reservation date must be a valid date.',
            'reservation_date.after_or_equal' => 'Reservation date cannot be in the past.',
            'status.in' => 'Status must be one of: Pending, Confirmed, Cancelled, Completed.',
        ];
    }

    public function reservationData(): array
    {
        return [
            'car_id' => $this->car_id,
            'reservation_date' => $this->reservation_date,
            'status' => $this->status ?? 'Pending',
        ];
    }
}