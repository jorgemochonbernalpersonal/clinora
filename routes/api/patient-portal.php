<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Patient Portal API Routes
|--------------------------------------------------------------------------
|
| Routes for patient-facing features
|
*/

Route::prefix('api/v1/patient')->middleware(['api'])->group(function () {
    
    // Public routes
    Route::prefix('booking')->group(function () {
        // TODO: Public booking routes (view availability, book appointment)
    });

    // Authenticated patient routes
    Route::middleware(['auth:sanctum', 'role:patient'])->group(function () {
        
        // Appointments
        Route::prefix('appointments')->group(function () {
            // TODO: Patient appointment routes
        });

        // Payments
        Route::prefix('payments')->group(function () {
            // TODO: Patient payment routes
        });

        // History
        Route::prefix('history')->group(function () {
            // TODO: Patient history routes
        });

        // Communication
        Route::prefix('messages')->group(function () {
            // TODO: Patient messaging routes
        });
    });
});
