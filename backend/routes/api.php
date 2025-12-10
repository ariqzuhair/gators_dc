<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MembershipController;

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

    // User management routes (admin only)
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Membership management routes (admin only)
    Route::get('/memberships', [MembershipController::class, 'index']);
    Route::put('/memberships/{userId}/semester', [MembershipController::class, 'updateSemester']);
    Route::get('/memberships/semesters', [MembershipController::class, 'getSemesters']);
    Route::post('/memberships/start-semester', [MembershipController::class, 'startSemester']);
    Route::post('/memberships/end-semester', [MembershipController::class, 'endSemester']);
    Route::post('/memberships/bulk-renew', [MembershipController::class, 'bulkRenew']);
    
    // Payment receipt routes
    Route::post('/memberships/payment-receipt', [MembershipController::class, 'uploadPaymentReceipt']);
    Route::get('/memberships/payment-receipts', [MembershipController::class, 'getPaymentReceipts']);
    Route::get('/memberships/pending-receipts', [MembershipController::class, 'getPendingReceipts']);
    Route::post('/memberships/{userId}/verify-payment', [MembershipController::class, 'verifyPaymentReceipt']);

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
    
    // Guest payment routes
    Route::get('/registrations/guest-payments/pending', [RegistrationController::class, 'getPendingGuestPayments']);
    Route::post('/registrations/{id}/verify-guest-payment', [RegistrationController::class, 'verifyGuestPayment']);
    Route::get('/players/{id}/guest-payment-stats', [RegistrationController::class, 'getGuestPaymentStats']);
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Gators Dodgeball Club API',
        'version' => '1.0.0',
        'status' => 'active'
    ]);
});
