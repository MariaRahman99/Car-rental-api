<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\CarReservation;
use App\Models\Payment;
use App\Models\Rental;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use ApiResponse;

    public function store(PaymentRequest $request)
    {
        $reservation = CarReservation::with('car')->find($request->reservation_id);

        if (!$reservation) {
            return $this->error('Reservation not found', 404);
        }

        if ($reservation->customer_id !== auth()->id()) {
            return $this->error('You are not allowed to pay for this reservation', 403);
        }

        if (!$reservation->car) {
            return $this->error('Car not found for this reservation', 404);
        }

        if ($reservation->car->status !== 'Available') {
            return $this->error('This car is not available for rent', 422);
        }

        return DB::transaction(function () use ($request, $reservation) {
            $car = $reservation->car;

            $rentalDays = now()->parse($request->rental_start)
                ->diffInDays(now()->parse($request->rental_end));

            $rentalDays = max($rentalDays, 1);

            $calculatedTotal = $car->rental_rate * $rentalDays;

            if ((float) $request->amount < (float) $calculatedTotal) {
                return $this->error('Insufficient payment amount', 422);
            }

            $rental = Rental::create([
                'customer_id' => auth()->id(),
                'car_id' => $car->id,
                'employee_id' => null,
                'discount_id' => null,
                'rental_start' => $request->rental_start,
                'rental_end' => $request->rental_end,
                'actual_return_date' => null,
                'total_amount' => $calculatedTotal,
                'status' => 'Active',
                'insurance_option' => $request->insurance_option ?? false,
                'fuel_level_start' => $request->fuel_level_start ?? 100,
                'fuel_level_end' => null,
            ]);

            $payment = Payment::create([
                'rental_id' => $rental->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => now(),
                'status' => 'Completed',
            ]);

            $car->update([
                'status' => 'Rented',
            ]);

            $reservation->update([
                'status' => 'Confirmed',
            ]);

            $rental->load(['car', 'customer', 'payments']);

            return $this->success(
                'Payment completed and rental created successfully',
                [
                    'rental' => $rental,
                    'payment' => $payment,
                ],
                201
            );
        });
    }
}