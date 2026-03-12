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

    public function index(Request $request)
    {
        $query = CarReservation::with(['car.category', 'car.branch', 'customer', 'payments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->get();

        return $this->success(
            CarReservationResource::collection($reservations),
            'Reservations retrieved successfully'
        );
    }

    public function show($id)
    {
        $reservation = CarReservation::with(['car.category', 'car.branch', 'customer', 'payments'])->find($id);

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

        $existingReservation = CarReservation::where('car_id', $request->car_id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('rental_start_date', [$request->rental_start_date, $request->rental_end_date])
                    ->orWhereBetween('rental_end_date', [$request->rental_start_date, $request->rental_end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('rental_start_date', '<=', $request->rental_start_date)
                            ->where('rental_end_date', '>=', $request->rental_end_date);
                    });
            })
            ->exists();

        if ($existingReservation) {
            return $this->error('This car is already reserved for the selected dates', 422);
        }

        $reservation = CarReservation::create([
            ...$request->reservationData(),
            'customer_id' => auth()->id(),
        ]);

        $reservation->load(['car.category', 'car.branch', 'customer', 'payments']);

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

        if ($reservation->customer_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        if ($reservation->status !== 'Pending') {
            return $this->error('Only pending reservations can be updated', 422);
        }

        $car = Car::find($request->car_id);

        if (!$car) {
            return $this->error('Car not found', 404);
        }

        if ($car->status !== 'Available' && $reservation->car_id != $car->id) {
            return $this->error('This car is not available for reservation', 422);
        }

        $existingReservation = CarReservation::where('car_id', $request->car_id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where('id', '!=', $reservation->id)
            ->exists();

        if ($existingReservation) {
            return $this->error('This car is already reserved', 422);
        }

        $reservation->update([
            ...$request->reservationData(),
            'customer_id' => $reservation->customer_id,
            'is_paid' => $reservation->is_paid,
        ]);

        $reservation->load(['car.category', 'car.branch', 'customer', 'payments']);

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

        if ($reservation->customer_id !== auth()->id()) {
            return $this->error('Unauthorized', 403);
        }

        if ($reservation->status !== 'Pending') {
            return $this->error('Only pending reservations can be deleted', 422);
        }

        $reservation->delete();

        return $this->success(null, 'Reservation deleted successfully');
    }

    public function myReservations(Request $request)
    {
        $reservations = CarReservation::with(['car.category', 'car.branch', 'customer', 'payments'])
            ->where('customer_id', auth()->id())
            ->get();

        return $this->success(
            CarReservationResource::collection($reservations),
            'Your reservations retrieved successfully'
        );
    }
}
