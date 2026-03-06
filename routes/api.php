<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/admin/users', [EmployeeController::class, 'index']);
        Route::post('/admin/employees', [EmployeeController::class, 'store'])
            ->middleware('can:create,App\Models\Employee');
        Route::get('admin/users/{id}', [EmployeeController::class, 'show']);    
    });
});
