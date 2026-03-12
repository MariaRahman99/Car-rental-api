<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\CarReservation;
use App\Models\Payment;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use ApiResponse;

    public function store(PaymentRequest $request)
    {
        $reservation = CarReservation::with('car', 'payments')->find($request->reservation_id);

        if (!$reservation) {
            return $this->error('Reservation not found', 404);
        }

        if ($reservation->customer_id !== auth()->id()) {
            return $this->error('You cannot pay for this reservation', 403);
        }

        if ($reservation->status !== 'Pending') {
            return $this->error('Only pending reservations can be paid', 422);
        }

        if ($reservation->is_paid) {
            return $this->error('This reservation is already paid', 422);
        }

        if (!$reservation->car) {
            return $this->error('Car not found for this reservation', 404);
        }

        if ($reservation->car->status !== 'Available') {
            return $this->error('This car is not available', 422);
        }

        $days = max(
            Carbon::parse($reservation->rental_start_date)
                ->diffInDays(Carbon::parse($reservation->rental_end_date)),
            1
        );

        $requiredAmount = $reservation->car->rental_rate * $days;

        if ((float) $request->amount < (float) $requiredAmount) {
            return $this->error('Paid amount is less than required rental amount', 422);
        }

        $payment = DB::transaction(function () use ($request, $reservation) {
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'rental_id' => null,
                'payment_date' => now()->toDateString(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'Completed',
            ]);

            $reservation->update([
                'is_paid' => true,
            ]);

            return $payment;
        });

        return $this->success(
            'Payment completed successfully. Waiting for manager approval.',
            new PaymentResource($payment),
            201
        );
    }
}