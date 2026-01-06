<?php

use App\Http\Controllers\Api\GDPRController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
*/

// GDPR/ARCO Rights Endpoints
Route::prefix('gdpr')->middleware(['auth:sanctum'])->name('gdpr.')->group(function () {
    // Right of Access (Art. 15 RGPD)
    Route::get('/access', [GDPRController::class, 'access'])->name('access');
    
    // Right to Data Portability (Art. 20 RGPD)
    Route::get('/export', [GDPRController::class, 'export'])->name('export');
    
    // Right to Erasure (Art. 17 RGPD)
    Route::delete('/delete', [GDPRController::class, 'delete'])->name('delete');
    
    // Right to Rectification (Art. 16 RGPD)
    Route::put('/rectify', [GDPRController::class, 'rectify'])->name('rectify');
    
    // Right to Object (Art. 21 RGPD)
    Route::post('/oppose', [GDPRController::class, 'oppose'])->name('oppose');
});

// Download GDPR export (public with signed URL)
Route::get('/gdpr/download/{filename}', [GDPRController::class, 'download'])
    ->middleware(['auth:sanctum'])
    ->name('gdpr.download');
