<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiAuthController;
use App\Http\Controllers\API\EmployeeReportController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Public API route
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected routes (authenticated with Sanctum)
Route::middleware([
    EnsureFrontendRequestsAreStateful::class,
    'auth:sanctum',
])->group(function () {
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // 👇 Employee logs route
    Route::get('/employee/logs', [EmployeeReportController::class, 'index']);
});
