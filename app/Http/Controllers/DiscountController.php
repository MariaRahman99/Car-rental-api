<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $discounts = Discount::latest()->get();

        return $this->success(
            'Discounts retrieved successfully',
            DiscountResource::collection($discounts)
        );
    }

    public function store(DiscountRequest $request)
    {
        $discount = Discount::create($request->discountData());

        return $this->success(
            'Discount created successfully',
            new DiscountResource($discount),
            201
        );
    }

    public function show($id)
    {
        $discount = Discount::find($id);

        if (!$discount) {
            return $this->error('Discount not found', 404);
        }

        return $this->success(
            'Discount retrieved successfully',
            new DiscountResource($discount)
        );
    }

    public function update(UpdateDiscountRequest $request, $id)
    {
        $discount = Discount::find($id);

        if (!$discount) {
            return $this->error('Discount not found', 404);
        }

        $discount->update($request->discountData());

        return $this->success(
            'Discount updated successfully',
            new DiscountResource($discount)
        );
    }

    public function destroy($id)
    {
        $discount = Discount::find($id);

        if (!$discount) {
            return $this->error('Discount not found', 404);
        }

        $discount->delete();

        return $this->success(
            'Discount deleted successfully',
            null
        );
    }
}