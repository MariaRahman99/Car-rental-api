<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Traits\ApiResponse;

class CarController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $cars = Car::all();
        return $this->success(CarResource::collection($cars), 'Cars retrieved successfully');
    }

    public function show($id)
    {
        $car = Car::find($id);
        if (!$car) {
            return $this->error('Car not found', 404);
        }
        $this->authorize('view', $car);
        return $this->success(new CarResource($car), 'Car retrieved successfully');
    }

    public function store(CarRequest $request)
    {
        $this->authorize('create', Car::class); 
        $car = Car::create($request->validated());
        return $this->success(new CarResource($car), 'Car created successfully', 201);
    }
}