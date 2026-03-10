<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'model' => $this->model,
            'year' => $this->year,
            'vin' => $this->vin,
            'license_plate' => $this->license_plate,
            'color' => $this->color,
            'mileage' => $this->mileage,
            'status' => $this->status,
            'rental_rate' => $this->rental_rate,
            'insurance_id' => $this->insurance_id,
            'branch_id' => $this->branch_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => new VehicleCategoryResource($this->whenLoaded('category')),
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'insurance' => new InsuranceResource($this->whenLoaded('insurance')),
        ];
    }
}