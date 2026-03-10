<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarReservationRequest;
use App\Http\Resources\CarReservationResource;
use App\Models\Car;
use App\Models\CarReservation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CarReservationController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $reservations = CarReservation::with(['car.category', 'car.branch', 'customer'])->get();

        return $this->success(
            CarReservationResource::collection($reservations),
            'Reservations retrieved successfully'
        );
    }

    public function show($id)
    {
        $reservation = CarReservation::with(['car.category', 'car.branch', 'customer'])->find($id);

        if (!$reservation) {
            return $this->error('Reservation not found', 404);
        }

        return $this->success(
            new CarReservationResource($reservation),
            'Reservation retrieved successfully'
        );
    }

    public function store(CarReservationRequest $request)
    {
        $car = Car::find($request->car_id);

        if (!$car) {
            return $this->error('Car not found', 404);
        }

        if ($car->status !== 'Available') {
            return $this->error('This car is not available for reservation', 422);
        }

        $reservation = CarReservation::create([
            ...$request->reservationData(),
            'customer_id' => auth()->id(),
        ]);

        $reservation->load(['car.category', 'car.branch', 'customer']);

        return $this->success(
            new CarReservationResource($reservation),
            'Reservation created successfully',
            201
        );
    }

    public function update(CarReservationRequest $request, $id)
    {
        $reservation = CarReservation::find($id);

        if (!$reservation) {
            return $this->error('Reservation not found', 404);
        }

        $car = Car::find($request->car_id);

        if (!$car) {
            return $this->error('Car not found', 404);
        }

        if ($car->status !== 'Available' && $reservation->car_id != $car->id) {
            return $this->error('This car is not available for reservation', 422);
        }

        $reservation->update([
            ...$request->reservationData(),
            'customer_id' => $reservation->customer_id,
        ]);

        $reservation->load(['car.category', 'car.branch', 'customer']);

        return $this->success(
            new CarReservationResource($reservation),
            'Reservation updated successfully'
        );
    }

    public function destroy($id)
    {
        $reservation = CarReservation::find($id);

        if (!$reservation) {
            return $this->error('Reservation not found', 404);
        }

        $reservation->delete();

        return $this->success(null, 'Reservation deleted successfully');
    }

    public function myReservations(Request $request)
    {
        $reservations = CarReservation::with(['car.category', 'car.branch', 'customer'])
            ->where('customer_id', auth()->id())
            ->get();

        return $this->success(
            CarReservationResource::collection($reservations),
            'Your reservations retrieved successfully'
        );
    }
}