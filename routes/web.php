<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogViewerController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO - Sitemap (debe estar antes de otras rutas para evitar conflictos)
Route::get('/sitemap.xml', function() {
    // Cache inteligente: 1 hora (el sitemap cambia poco frecuentemente)
    // pero los motores de búsqueda pueden cachear más tiempo
    return response()
        ->view('sitemap', [], 200)
        ->header('Content-Type', 'application/xml; charset=utf-8')
        ->header('Cache-Control', 'public, max-age=3600, s-maxage=86400')
        ->header('X-Content-Type-Options', 'nosniff')
        ->header('X-Robots-Tag', 'noindex'); // No indexar el sitemap mismo
})->name('sitemap');

// SEO - Sitemap XSL Stylesheet (para mejor visualización en navegadores)
Route::get('/sitemap.xsl', function() {
    return response()
        ->view('sitemap.xsl', [], 200)
        ->header('Content-Type', 'application/xml; charset=utf-8')
        ->header('Cache-Control', 'public, max-age=3600');
})->name('sitemap.xsl');

// SEO - Robots.txt dinámico (opcional, si quieres generarlo dinámicamente)
// Por ahora usamos el archivo estático en public/robots.txt
// Route::get('/robots.txt', function() {
//     return response()
//         ->view('robots', [], 200)
//         ->header('Content-Type', 'text/plain; charset=utf-8')
//         ->header('Cache-Control', 'public, max-age=86400');
// })->name('robots');

// Legal pages
Route::get('/legal/cookies', fn() => view('legal.cookies'))->name('legal.cookies');
Route::get('/legal/privacy', fn() => view('legal.privacy'))->name('legal.privacy');
Route::get('/legal/terms', fn() => view('legal.terms'))->name('legal.terms');
Route::get('/legal/gdpr', fn() => view('legal.gdpr'))->name('legal.gdpr');

// Auth routes
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest');
Route::get('/2fa/verify', fn() => view('auth.verify-2fa'))->name('2fa.verify')->middleware('guest');
Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice')->middleware('auth');
Route::get('/email/verify/{id}/{hash}', [\App\Core\Authentication\Controllers\EmailVerificationController::class, 'verifyWeb'])
    ->middleware(['signed'])
    ->name('verification.verify');
Route::post('/logout', function() {
    session()->forget(['api_token', 'user']);
    auth()->logout();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// Dashboard routes (protected)
Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/', \App\Livewire\DashboardHome::class)->name('dashboard');
    
    // Patients
    Route::get('/patients', \App\Livewire\Patients\PatientList::class)->name('patients.index');
    Route::get('/patients/create', \App\Livewire\Dashboard\Patients\PatientForm::class)->name('patients.create');
    Route::get('/patients/{id}/edit', \App\Livewire\Dashboard\Patients\PatientForm::class)->name('patients.edit');

    // Appointments
    Route::get('/appointments', fn() => view('dashboard.appointments.index'))->name('appointments.index');
    Route::get('/appointments/create', \App\Livewire\Dashboard\Appointments\AppointmentForm::class)->name('appointments.create');
    Route::get('/appointments/{id}/edit', \App\Livewire\Dashboard\Appointments\AppointmentForm::class)->name('appointments.edit');

    // Clinical Notes
    Route::get('/clinical-notes', fn() => view('dashboard.clinical-notes.index'))->name('clinical-notes.index');
    Route::get('/clinical-notes/create', \App\Livewire\Dashboard\ClinicalNotes\ClinicalNoteForm::class)->name('clinical-notes.create');
    Route::get('/clinical-notes/{id}/edit', \App\Livewire\Dashboard\ClinicalNotes\ClinicalNoteForm::class)->name('clinical-notes.edit');
    Route::get('/profile', fn() => view('dashboard.profile'))->name('profile.settings');
    Route::get('/security', fn() => view('dashboard.security'))->name('security.settings');
    Route::get('/logs', [LogViewerController::class, 'index'])
        ->name('logs.index')
        ->middleware('can.view.logs');
});
