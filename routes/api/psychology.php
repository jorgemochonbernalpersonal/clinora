<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Psychology Module API Routes
|--------------------------------------------------------------------------
|
| Psychology-specific functionality routes
|
*/

Route::prefix('api/v1/psychology')->middleware(['api', 'auth:sanctum', 'module:psychology'])->group(function () {
    
    // Clinical Notes
    Route::prefix('clinical-notes')->group(function () {
        // TODO: Clinical notes routes
    });

    // Assessments
    Route::prefix('assessments')->group(function () {
        // TODO: Assessment routes (BDI-II, PHQ-9, GAD-7, etc.)
    });

    // Consent Forms
    Route::prefix('consent-forms')->group(function () {
        // TODO: Consent form routes
    });

    // Teleconsultation (psychology-specific)
    Route::prefix('teleconsultation')->group(function () {
        // TODO: Teleconsultation routes
    });
});
