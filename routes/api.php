<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ReservationApprovalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CarReservationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\DiscountController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/admin/users', [EmployeeController::class, 'index']);
    Route::post('/admin/register', [EmployeeController::class, 'store'])
        ->middleware('can:create,App\Models\Employee');
    Route::get('/admin/users/{id}', [EmployeeController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cars', [\App\Http\Controllers\CarController::class, 'store']);
    Route::put('/cars/{car}', [\App\Http\Controllers\CarController::class, 'update']);
    Route::delete('/cars/{car}', [\App\Http\Controllers\CarController::class, 'destroy']);

    Route::get('/cars', [\App\Http\Controllers\CarController::class, 'index']);
    Route::get('/cars/{car}', [\App\Http\Controllers\CarController::class, 'show']);
});

Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-reservations', [CarReservationController::class, 'myReservations']);
    Route::post('/reservations', [CarReservationController::class, 'store']);
    Route::get('/reservations/{id}', [CarReservationController::class, 'show']);
    Route::put('/reservations/{id}', [CarReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [CarReservationController::class, 'destroy']);

    Route::post('/payments', [PaymentController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'manager'])->group(function () {
    Route::get('/reservations', [CarReservationController::class, 'index']);
    Route::post('/reservations/{id}/approve', [ReservationApprovalController::class, 'approve']);
    Route::post('/reservations/{id}/decline', [ReservationApprovalController::class, 'decline']);
});

Route::middleware(['auth:sanctum', 'manager'])->group(function () {
    Route::post('/discounts', [DiscountController::class, 'store']);
    Route::put('/discounts/{id}', [DiscountController::class, 'update']);
    Route::delete('/discounts/{id}', [DiscountController::class, 'destroy']);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('cars/{car}/maintenances', [MaintenanceController::class, 'index']);
    Route::post('cars/{car}/maintenances', [MaintenanceController::class, 'store']);
    Route::get('maintenances/{id}', [MaintenanceController::class, 'show']);
    Route::put('maintenances/{id}', [MaintenanceController::class, 'update']);
    Route::delete('maintenances/{id}', [MaintenanceController::class, 'destroy']);
});
Route::middleware(['auth:sanctum', 'manager'])->group(function () {
    Route::get('/admin/branches', [BranchController::class, 'index']);
    Route::post('/admin/branches', [BranchController::class, 'store']);
    Route::get('/admin/branches/{branch}', [BranchController::class, 'show']);
    Route::put('/admin/branches/{branch}', [BranchController::class, 'update']);
    Route::delete('/admin/branches/{branch}', [BranchController::class, 'destroy']);
});