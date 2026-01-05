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
        Route::get('/', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'index']);
        Route::post('/', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'store']);
        Route::get('/{id}', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'show']);
        Route::put('/{id}', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'update']);
        Route::post('/{id}/sign', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'sign']);
    });

    // Assessments
    Route::prefix('assessments')->group(function () {
        // TODO: Assessment routes (BDI-II, PHQ-9, GAD-7, etc.)
    });

    // Consent Forms
    Route::prefix('consent-forms')->group(function () {
        Route::get('/', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'index']);
        Route::post('/', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'store']);
        Route::get('/available-types', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'availableTypes']);
        Route::get('/check-valid/{contactId}', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'checkValidConsent']);
        Route::get('/{id}', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'show']);
        Route::put('/{id}', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'update']);
        Route::post('/{id}/sign', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'sign']);
        Route::post('/{id}/revoke', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'revoke']);
    });

    // Teleconsultation (psychology-specific)
    Route::prefix('teleconsultation')->group(function () {
        // TODO: Teleconsultation routes
    });
});
