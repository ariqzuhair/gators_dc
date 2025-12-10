<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\RegistrationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Session routes (public for viewing)
Route::get('/sessions', [SessionController::class, 'index']);
Route::get('/sessions/{id}', [SessionController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Player routes
    Route::apiResource('players', PlayerController::class);

    // Session management routes (protected)
    Route::get('/sessions/upcoming', [SessionController::class, 'upcoming']);
    Route::post('/sessions', [SessionController::class, 'store']);
    Route::put('/sessions/{id}', [SessionController::class, 'update']);
    Route::delete('/sessions/{id}', [SessionController::class, 'destroy']);

    // Registration routes
    Route::post('/registrations/{id}/cancel', [RegistrationController::class, 'cancel']);
    Route::apiResource('registrations', RegistrationController::class);
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Gators Dodgeball Club API',
        'version' => '1.0.0',
        'status' => 'active'
    ]);
});
