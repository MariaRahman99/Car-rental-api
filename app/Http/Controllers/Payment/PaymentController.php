<?php

namespace App\Http\Controllers\Payment;

use App\Http\Requests\Payment\PaymentRequest;
use App\Models\CarReservation;
use App\Models\Payment;
use App\Models\Rental;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
            return $this->error('You cannot pay for this reservation', 403);
        }

        if (!$reservation->car) {
            return $this->error('Car not found for this reservation', 404);
        }

        if ($reservation->car->status !== 'Available') {
            return $this->error('This car is not available for rent', 422);
        }

        return DB::transaction(function () use ($request, $reservation) {
            $car = $reservation->car;

            $days = \Carbon\Carbon::parse($request->rental_start)
                ->diffInDays(\Carbon\Carbon::parse($request->rental_end));

            $days = max($days, 1);

            $totalAmount = $car->rental_rate * $days;

            if ((float) $request->amount < (float) $totalAmount) {
                return $this->error('Paid amount is less than required rental amount', 422);
            }

            $rental = Rental::create([
                'customer_id' => auth()->id(),
                'car_id' => $car->id,
                'employee_id' => 1,
                'discount_id' => null,
                'rental_start_date' => $request->rental_start,
                'rental_end_date' => $request->rental_end,
                'actual_return_date' => null,
                'total_amount' => $totalAmount,
                'status' => 'Ongoing',
                'insurance_option' => ($request->insurance_option ?? false) ? 'Yes' : 'No',
                'fuel_level_start' => $request->fuel_level_start ?? 100,
                'fuel_level_end' => null,
            ]);

            $payment = Payment::create([
                'rental_id' => $rental->id,
                'payment_date' => now()->toDateString(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'Completed',
            ]);

            $car->update([
                'status' => 'Rented',
            ]);

            $reservation->update([
                'status' => 'Confirmed',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment completed and rental created successfully',
                'data' => [
                    'rental' => $rental,
                    'payment' => $payment,
                ],
            ], 201);
        });
    }
}
