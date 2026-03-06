<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $employee = $this->employee;

        return [
            'id'           => $this->id,
            'first_name'   => $employee?->first_name,
            'last_name'    => $employee?->last_name,
            'email'        => $employee?->email ?? $this->email,
            'phone_number' => $employee?->phone_number ?? $this->phone_number,
            'position'     => $employee?->position,
            'branch_id'    => $employee?->branch_id,
            'hire_date'    => $employee?->hire_date,
            'salary'       => $employee?->salary,
            'created_at'   => $employee?->created_at,
            'updated_at'   => $employee?->updated_at,
        ];
    }
}
