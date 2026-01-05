<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogViewerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO - Sitemap
Route::get('/sitemap.xml', function () {
    return response()
        ->view('sitemap', [], 200)
        ->header('Content-Type', 'application/xml; charset=utf-8')
        ->header('Cache-Control', 'public, max-age=3600, s-maxage=86400')
        ->header('X-Content-Type-Options', 'nosniff')
        ->header('X-Robots-Tag', 'noindex');
})->name('sitemap');

Route::get('/sitemap.xsl', function () {
    return response()
        ->view('sitemap_style', [], 200)
        ->header('Content-Type', 'application/xml; charset=utf-8')
        ->header('Cache-Control', 'public, max-age=3600');
})->name('sitemap.xsl');

// Legal pages
Route::get('/legal/cookies', fn() => view('legal.cookies'))->name('legal.cookies');
Route::get('/legal/privacy', fn() => view('legal.privacy'))->name('legal.privacy');
Route::get('/legal/terms', fn() => view('legal.terms'))->name('legal.terms');
Route::get('/legal/gdpr', fn() => view('legal.gdpr'))->name('legal.gdpr');

// Public pages
Route::get('/contacto', fn() => view('contacto'))->name('contact');
Route::get('/faqs', fn() => view('faqs'))->name('faqs');
Route::get('/sobre-nosotros', fn() => view('about'))->name('about');

// Blog
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Auth routes
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest');
Route::get('/2fa/verify', fn() => view('auth.verify-2fa'))->name('2fa.verify')->middleware('guest');
Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice')->middleware('auth');
Route::get('/email/verify/{id}/{hash}', [\App\Core\Authentication\Controllers\EmailVerificationController::class, 'verifyWeb'])
    ->middleware(['signed'])
    ->name('verification.verify');
Route::post('/logout', function () {
    session()->forget(['api_token', 'user']);
    auth()->logout();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// ============================================================================
// Psychologist routes (protected)
// ============================================================================
Route::middleware(['auth', 'verified', 'profession:psychologist'])
    ->prefix('psychologist')
    ->name('psychologist.')
    ->group(function () {
        // Dashboard
        Route::get('/', \App\Livewire\Psychologist\DashboardHome::class)->name('dashboard');

        // Feature blocked preview
        Route::get('/features/blocked/{feature}', function ($feature) {
            $featureDetails = [
                'teleconsulta' => [
                    'title' => 'Teleconsulta',
                    'description' => 'Realiza videollamadas seguras con tus pacientes desde cualquier lugar.',
                    'benefits' => [
                        'Videollamadas HD con cifrado end-to-end',
                        'Grabación de sesiones (con consentimiento)',
                        'Compartir pantalla para mostrar recursos',
                        'Notas automáticas de la sesión',
                        'Recordatorios automáticos a pacientes',
                    ]
                ],
                'evaluaciones' => [
                    'title' => 'Evaluaciones Psicológicas',
                    'description' => 'Administra y puntúa evaluaciones estandarizadas en minutos.',
                    'benefits' => [
                        'BDI-II: Inventario de Depresión de Beck',
                        'PHQ-9: Cuestionario de Salud del Paciente',
                        'GAD-7: Escala de Ansiedad Generalizada',
                        'Puntuación automática e interpretación',
                        'Gráficos de progreso a lo largo del tiempo',
                        'Exportación de resultados en PDF',
                    ]
                ],
                'portal_paciente' => [
                    'title' => 'Portal del Paciente',
                    'description' => 'Permite a tus pacientes reservar citas y acceder a sus documentos 24/7.',
                    'benefits' => [
                        'Reserva de citas online',
                        'Acceso a historial de sesiones',
                        'Descarga de documentos e informes',
                        'Completar formularios previos a la cita',
                        'Notificaciones automáticas',
                        'Interfaz personalizada con tu branding',
                    ]
                ],
            ];

            $details = $featureDetails[$feature] ?? [
                'title' => ucfirst($feature),
                'description' => 'Esta función premium no está disponible en tu plan actual.',
                'benefits' => []
            ];

            return view('features.blocked', array_merge(['feature' => $feature], $details));
        })->name('features.blocked');

        // Patients
        Route::prefix('patients')->name('patients.')->group(function () {
            Route::get('/', \App\Livewire\Patients\PatientList::class)->name('index');
            Route::get('/create', \App\Livewire\Psychologist\Patients\PatientForm::class)->name('create');
            Route::get('/{id}/edit', \App\Livewire\Psychologist\Patients\PatientForm::class)->name('edit');
        });

        // Appointments
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', fn() => view('psychologist.appointments.index'))->name('index');
            Route::get('/create', \App\Livewire\Psychologist\Appointments\AppointmentForm::class)->name('create');
            Route::get('/{id}/edit', \App\Livewire\Psychologist\Appointments\AppointmentForm::class)->name('edit');
        });

        // Clinical Notes
        Route::prefix('clinical-notes')->name('clinical-notes.')->group(function () {
            Route::get('/', fn() => view('psychologist.clinical-notes.index'))->name('index');
            Route::get('/create', \App\Livewire\Psychologist\ClinicalNotes\ClinicalNoteForm::class)->name('create');
            Route::get('/{id}/edit', \App\Livewire\Psychologist\ClinicalNotes\ClinicalNoteForm::class)->name('edit');
        });

        // Subscription Management
        Route::get('/subscription', [App\Http\Controllers\SubscriptionController::class, 'index'])->name('subscription.index');
        Route::patch('/subscription/preferences', [App\Http\Controllers\SubscriptionController::class, 'updatePreferences'])->name('subscription.update-preferences');

        // Onboarding
        Route::post('/onboarding/welcome-seen', [App\Http\Controllers\OnboardingController::class, 'welcomeSeen'])->name('onboarding.welcome-seen');

        // Settings
        Route::get('/profile', fn() => view('psychologist.profile'))->name('profile.settings');
        Route::get('/security', fn() => view('psychologist.security'))->name('security.settings');
        
        // Logs (admin only)
        Route::get('/logs', [LogViewerController::class, 'index'])
            ->name('logs.index')
            ->middleware('can.view.logs');
    });

// ============================================================================
// Redirect old /dashboard/* routes to /psychologist/* (backwards compatibility)
// ============================================================================
Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {
        // Main dashboard redirect
        Route::get('/', function () {
            $professionRoute = profession_prefix();
            return redirect()->route($professionRoute . '.dashboard');
        })->name('dashboard');

        // Catch-all redirect for any /dashboard/* route
        Route::get('/{any}', function ($any) {
            $professionRoute = profession_prefix();
            
            // Convert path to route name (e.g. 'patients/create' -> 'patients.create')
            $routeName = str_replace('/', '.', $any);
            
            try {
                $url = route($professionRoute . '.' . $routeName);
                return redirect($url);
            } catch (\Exception $e) {
                // If route doesn't exist, redirect to dashboard
                return redirect()->route($professionRoute . '.dashboard');
            }
        })->where('any', '.*');
    });
