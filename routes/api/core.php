<?php

use App\Core\Authentication\Controllers\AuthController;
use App\Core\Authentication\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core API Routes
|--------------------------------------------------------------------------
|
| Core functionality routes (Authentication, Users, Contacts, Appointments, etc.)
|
*/

Route::prefix('api/v1')->middleware(['api'])->group(function () {
    
    // Authentication routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
        Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
        
        // Password reset
        Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])
            ->name('api.auth.forgot-password');
        Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
            ->name('api.auth.reset-password');
        
        // Email verification (verify endpoint requires auth)
        Route::get('/verify/{id}/{hash}', [\App\Core\Authentication\Controllers\EmailVerificationController::class, 'verify'])
            ->middleware(['auth:sanctum', 'signed'])
            ->name('api.auth.verify');
    });

    // Protected routes
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // Auth user info
        Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('api.auth.me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
            Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('api.auth.logout-all');
            
            // Email verification
            Route::post('/email/resend', [\App\Core\Authentication\Controllers\EmailVerificationController::class, 'sendVerificationEmail'])
                ->name('api.auth.verification.send');
            Route::get('/email/status', [\App\Core\Authentication\Controllers\EmailVerificationController::class, 'checkStatus'])
                ->name('api.auth.verification.status');
        });
        
        // Contacts (Patients)
        Route::prefix('contacts')->group(function () {
            Route::get('/', [\App\Core\Contacts\Controllers\ContactController::class, 'index'])->name('api.contacts.index');
            Route::post('/', [\App\Core\Contacts\Controllers\ContactController::class, 'store'])->name('api.contacts.store');
            Route::get('/{id}', [\App\Core\Contacts\Controllers\ContactController::class, 'show'])->name('api.contacts.show');
            Route::put('/{id}', [\App\Core\Contacts\Controllers\ContactController::class, 'update'])->name('api.contacts.update');
            Route::delete('/{id}', [\App\Core\Contacts\Controllers\ContactController::class, 'destroy'])->name('api.contacts.destroy');
        });

        // Appointments
        Route::prefix('appointments')->group(function () {
            Route::get('/', [\App\Core\Appointments\Controllers\AppointmentController::class, 'index'])->name('api.appointments.index');
            Route::post('/', [\App\Core\Appointments\Controllers\AppointmentController::class, 'store'])->name('api.appointments.store');
            Route::get('/{id}', [\App\Core\Appointments\Controllers\AppointmentController::class, 'show'])->name('api.appointments.show');
            Route::put('/{id}', [\App\Core\Appointments\Controllers\AppointmentController::class, 'update'])->name('api.appointments.update');
            Route::delete('/{id}', [\App\Core\Appointments\Controllers\AppointmentController::class, 'destroy'])->name('api.appointments.destroy');
        });

        // Clinical Notes
        Route::prefix('clinical-notes')->group(function () {
            Route::get('/', [\App\Core\ClinicalNotes\Controllers\ClinicalNoteController::class, 'index'])->name('api.clinical-notes.index');
            Route::post('/', [\App\Core\ClinicalNotes\Controllers\ClinicalNoteController::class, 'store'])->name('api.clinical-notes.store');
            Route::get('/{id}', [\App\Core\ClinicalNotes\Controllers\ClinicalNoteController::class, 'show'])->name('api.clinical-notes.show');
            Route::put('/{id}', [\App\Core\ClinicalNotes\Controllers\ClinicalNoteController::class, 'update'])->name('api.clinical-notes.update');
            Route::post('/{id}/sign', [\App\Core\ClinicalNotes\Controllers\ClinicalNoteController::class, 'sign'])->name('api.clinical-notes.sign');
        });
        
        // Dashboard
        Route::prefix('dashboard')->group(function () {
            // TODO: Dashboard routes
        });

        // Professionals
        Route::prefix('professionals')->group(function () {
            // TODO: Professional routes
        });

        // Contacts/Patients
        Route::prefix('contacts')->group(function () {
            // TODO: Contact routes
        });

        // Appointments
        Route::prefix('appointments')->group(function () {
            // TODO: Appointment routes
        });

        // Billing
        Route::prefix('billing')->group(function () {
            // TODO: Billing routes (invoices, payments)
        });

        // Notifications
        Route::prefix('notifications')->group(function () {
            // TODO: Notification routes
        });
    });
});
