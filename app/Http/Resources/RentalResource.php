<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'car_id' => $this->car_id,
            'employee_id' => $this->employee_id,
            'discount_id' => $this->discount_id,
            'rental_start_date' => $this->rental_start_date,
            'rental_end_date' => $this->rental_end_date,
            'actual_return_date' => $this->actual_return_date,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'insurance_option' => $this->insurance_option,
            'fuel_level_start' => $this->fuel_level_start,
            'fuel_level_end' => $this->fuel_level_end,
            'created_at' => $this->created_at,

            'car' => new CarResource($this->whenLoaded('car')),
            'customer' => new UserResource($this->whenLoaded('customer')),
            'employee' => new AdminUserResource($this->whenLoaded('employee')),
            'discount' => $this->whenLoaded('discount'),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}