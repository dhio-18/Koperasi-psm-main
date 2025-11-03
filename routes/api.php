<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'auth:sanctum'])->group(function () {

    // Customer API routes
    Route::middleware(['role:customer'])->group(function () {
        // Route::get('/products', [Api\ProductController::class, 'index']);
        // Route::post('/orders', [Api\OrderController::class, 'store']);
        // Route::post('/payments/upload-proof', [Api\PaymentController::class, 'uploadProof']);

        // Route::post('/profile/update', [App\Http\Controllers\Api\ProfileController::class, 'update'])->name('profile.update');
    });

    // Admin API routes
    Route::middleware(['role:admin,super_admin'])->group(function () {
        // Route::get('/orders/pending', [Api\AdminOrderController::class, 'pending']);
        // Route::patch('/payments/{payment}/approve', [Api\AdminPaymentController::class, 'approve']);
        // Route::patch('/payments/{payment}/reject', [Api\AdminPaymentController::class, 'reject']);
    });

    // Super Admin API routes
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/users', function () {
            return response()->json(User::where('role', '!=', 'super_admin')->get());
        });
    });
});