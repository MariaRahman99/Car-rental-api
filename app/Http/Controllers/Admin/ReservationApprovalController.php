<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarReservationResource;
use App\Http\Resources\RentalResource;
use App\Models\CarReservation;
use App\Models\Employee;
use App\Models\Rental;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationApprovalController extends Controller
{
    use ApiResponse;

    public function approve($id)
    {
        $reservation = CarReservation::with(['car', 'customer', 'payments'])->find($id);

        if (!$reservation) {
            return $this->error('Reservation not found', 404);
        }

        if ($reservation->status !== 'Pending') {
            return $this->error('Only pending reservations can be approved', 422);
        }

        if (!$reservation->is_paid) {
            return $this->error('Reservation must be paid before approval', 422);
        }

        if (!$reservation->car) {
            return $this->error('Car not found', 404);
        }

        if ($reservation->car->status !== 'Available') {
            return $this->error('Car is not available', 422);
        }

        $employee = Employee::query()->first();

        if (!$employee) {
            return $this->error('No employee found to assign this rental', 422);
        }

        [$reservation, $rental] = DB::transaction(function () use ($reservation, $employee) {
            $days = max(
                Carbon::parse($reservation->rental_start_date)
                    ->diffInDays(Carbon::parse($reservation->rental_end_date)),
                1
            );

            $totalAmount = $reservation->car->rental_rate * $days;

            $rental = Rental::create([
                'customer_id' => $reservation->customer_id,
                'car_id' => $reservation->car_id,
                'employee_id' => $employee->id,
                'discount_id' => null,
                'rental_start_date' => $reservation->rental_start_date,
                'rental_end_date' => $reservation->rental_end_date,
                'actual_return_date' => null,
                'total_amount' => $totalAmount,
                'status' => 'Ongoing',
                'insurance_option' => $reservation->insurance_option ? 'Yes' : 'No',
                'fuel_level_start' => 100,
                'fuel_level_end' => null,
            ]);

            $payment = $reservation->payments()->latest()->first();
            if ($payment) {
                $payment->update([
                    'rental_id' => $rental->id,
                ]);
            }

            $reservation->update([
                'status' => 'Approved',
            ]);

            $reservation->car->update([
                'status' => 'Rented',
            ]);

            return [$reservation, $rental];
        });

        $reservation->load(['car', 'customer', 'payments']);
        $rental->load(['car', 'customer', 'employee', 'payments']);

        return $this->success(
            'Reservation approved and rental created successfully',
            [
                'reservation' => new CarReservationResource($reservation),
                'rental' => new RentalResource($rental),
            ]
        );
    }

    public function decline($id)
    {
        $reservation = CarReservation::with(['car', 'customer', 'payments'])->find($id);

        if (!$reservation) {
            return $this->error('Reservation not found', 404);
        }

        if ($reservation->status !== 'Pending') {
            return $this->error('Only pending reservations can be declined', 422);
        }

        $reservation->update([
            'status' => 'Declined',
        ]);

        return $this->success(
            new CarReservationResource($reservation->load(['car', 'customer', 'payments'])),
            'Reservation declined successfully'
        );
    }
}