<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Tenants\Controllers\TenantController;
use App\Modules\Teams\Controllers\TeamController;
use App\Modules\Bookings\Controllers\BookingController;
use App\Modules\Auth\Controllers\AuthController;
use App\Modules\Users\Controllers\UserController;

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/tenant/{tenantId}', [UserController::class, 'getByTenant']);
    });

    // Tenant routes
    Route::prefix('tenants')->group(function () {
        Route::get('/', [TenantController::class, 'index']);
        Route::post('/', [TenantController::class, 'store']);
        Route::get('/{id}', [TenantController::class, 'show']);
        Route::put('/{id}', [TenantController::class, 'update']);
        Route::delete('/{id}', [TenantController::class, 'destroy']);
        
        Route::get('/{id}/teams', [TenantController::class, 'getTeams']);
        Route::get('/{id}/users', [TenantController::class, 'getUsers']);
        Route::get('/{id}/bookings', [TenantController::class, 'getBookings']);
        Route::put('/{id}/settings', [TenantController::class, 'updateSettings']);
    });

    // Team routes
    Route::prefix('teams')->group(function () {
        Route::get('/', [TeamController::class, 'index']);
        Route::post('/', [TeamController::class, 'store']);
        Route::get('/{id}', [TeamController::class, 'show']);
        Route::put('/{id}', [TeamController::class, 'update']);
        Route::delete('/{id}', [TeamController::class, 'destroy']);
        
        Route::get('/{id}/availability', [TeamController::class, 'getAvailability']);
        Route::get('/{id}/bookings', [TeamController::class, 'getBookings']);
        Route::post('/{id}/check-availability', [TeamController::class, 'checkAvailability']);
        Route::get('/{id}/available-slots', [TeamController::class, 'getAvailableTimeSlots']);
    });

    // Booking routes
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/{id}', [BookingController::class, 'show']);
        Route::put('/{id}', [BookingController::class, 'update']);
        Route::delete('/{id}', [BookingController::class, 'destroy']);
        
        Route::get('/tenant/{tenantId}', [BookingController::class, 'getByTenant']);
        Route::get('/team/{teamId}', [BookingController::class, 'getByTeam']);
        Route::get('/user/{userId}', [BookingController::class, 'getByUser']);
        Route::put('/{id}/status', [BookingController::class, 'updateStatus']);
        Route::get('/tenant/{tenantId}/upcoming', [BookingController::class, 'getUpcomingBookings']);
    });
}); 