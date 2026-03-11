<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Maintenance;
use App\Traits\ApiResponse;
use App\Http\Requests\MaintenanceRequest;
use App\Http\Resources\MaintenanceResource;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    use ApiResponse;

    public function index($carId)
    {
        $car = Car::find($carId);

        if (!$car) {
            return $this->error('Car not found', 404);
        }

        $maintenances = $car->maintenances()
            ->with('employee')
            ->latest()
            ->get();

        return $this->success(
            MaintenanceResource::collection($maintenances),
            'Maintenances retrieved successfully'
        );
    }

    public function store(MaintenanceRequest $request, $carId)
    {
        $car = Car::find($carId);

        if (!$car) {
            return $this->error('Car not found', 404);
        }

        return DB::transaction(function () use ($request, $car) {
            $maintenance = $car->maintenances()->create([
                'maintenance_date' => $request->maintenance_date,
                'next_due_date' => $request->next_due_date,
                'maintenance_type' => $request->maintenance_type,
                'description' => $request->description,
                'cost' => $request->cost ?? 0,
                'performed_by' => $request->performed_by,
            ]);

            $maintenance->load('employee');

            return $this->success(
                new MaintenanceResource($maintenance),
                'Maintenance created successfully',
                201
            );
        });
    }

    public function show($id)
    {
        $maintenance = Maintenance::with(['car', 'employee'])->find($id);

        if (!$maintenance) {
            return $this->error('Maintenance not found', 404);
        }

        return $this->success(
            new MaintenanceResource($maintenance),
            'Maintenance retrieved successfully'
        );
    }

    public function update(MaintenanceRequest $request, $id)
    {
        $maintenance = Maintenance::with('employee')->find($id);

        if (!$maintenance) {
            return $this->error('Maintenance not found', 404);
        }

        return DB::transaction(function () use ($request, $maintenance) {
            $maintenance->update([
                'maintenance_date' => $request->maintenance_date,
                'next_due_date' => $request->next_due_date,
                'maintenance_type' => $request->maintenance_type,
                'description' => $request->description,
                'cost' => $request->cost ?? 0,
                'performed_by' => $request->performed_by,
            ]);

            $maintenance->load('employee');

            return $this->success(
                new MaintenanceResource($maintenance),
                'Maintenance updated successfully'
            );
        });
    }

    public function destroy($id)
    {
        $maintenance = Maintenance::find($id);

        if (!$maintenance) {
            return $this->error('Maintenance not found', 404);
        }

        $maintenance->delete();

        return $this->success(null, 'Maintenance deleted successfully');
    }
}