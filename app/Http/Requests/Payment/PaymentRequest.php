<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'reservation_id' => ['required', 'exists:car_reservations,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => [
                'required',
                Rule::in(['Cash', 'Credit Card', 'Debit Card', 'Online'])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'reservation_id.required' => 'Reservation is required.',
            'reservation_id.exists' => 'Selected reservation does not exist.',
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Amount must be numeric.',
            'amount.min' => 'Amount must be greater than 0.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Payment method must be one of: Cash, Credit Card, Debit Card, Online.',
        ];
    }
}