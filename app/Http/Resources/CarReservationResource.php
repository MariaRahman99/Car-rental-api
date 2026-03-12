<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'car_id' => $this->car_id,
            'customer_id' => $this->customer_id,
            'reservation_date' => $this->reservation_date,
            'rental_start_date' => $this->rental_start_date,
            'rental_end_date' => $this->rental_end_date,
            'insurance_option' => $this->insurance_option,
            'status' => $this->status,
            'is_paid' => $this->is_paid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'car' => new CarResource($this->whenLoaded('car')),

            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'id' => $this->customer->id,
                    'first_name' => $this->customer->first_name,
                    'last_name' => $this->customer->last_name,
                    'email' => $this->customer->email,
                    'phone_number' => $this->customer->phone_number,
                    'role' => $this->customer->role,
                ];
            }),

            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}