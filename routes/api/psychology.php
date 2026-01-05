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

Route::prefix('api/v1/psychology')->name('api.psychology.')->middleware(['api', 'auth:sanctum', 'module:psychology'])->group(function () {
    
    // Clinical Notes
    Route::prefix('clinical-notes')->name('clinical-notes.')->group(function () {
        Route::get('/', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'index'])->name('index');
        Route::post('/', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'update'])->name('update');
        Route::post('/{id}/sign', [\App\Modules\Psychology\ClinicalNotes\Controllers\ClinicalNoteController::class, 'sign'])->name('sign');
    });

    // Assessments
    Route::prefix('assessments')->group(function () {
        // TODO: Assessment routes (BDI-II, PHQ-9, GAD-7, etc.)
    });

    // Consent Forms
    Route::prefix('consent-forms')->name('consent-forms.')->group(function () {
        Route::get('/', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'index'])->name('index');
        Route::post('/', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'store'])->name('store');
        Route::get('/available-types', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'availableTypes'])->name('available-types');
        Route::get('/check-valid/{contactId}', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'checkValidConsent'])->name('check-valid');
        Route::get('/{id}', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'update'])->name('update');
        Route::post('/{id}/sign', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'sign'])->name('sign');
        Route::post('/{id}/revoke', [\App\Core\ConsentForms\Controllers\ConsentFormController::class, 'revoke'])->name('revoke');
    });

    // Teleconsultation (psychology-specific)
    Route::prefix('teleconsultation')->group(function () {
        // TODO: Teleconsultation routes
    });
});
