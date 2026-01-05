<?php

use App\Http\Controllers\LogViewerController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Psychologist Routes
|--------------------------------------------------------------------------
|
| Routes specific to psychologist users. All routes here are automatically
| protected with: auth, verified, profession:psychologist middleware.
|
*/

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
                'Sala de espera virtual',
            ]
        ],
        'facturacion_avanzada' => [
            'title' => 'Facturación Avanzada',
            'description' => 'Gestión completa de facturación con informes financieros.',
            'benefits' => [
                'Facturas automáticas por sesión',
                'Integración con pasarelas de pago',
                'Informes mensuales y anuales',
                'Exportación a contabilidad',
                'Recordatorios de pago automáticos',
            ]
        ],
        'portal_paciente' => [
            'title' => 'Portal del Paciente',
            'description' => 'Un espacio personalizado donde tus pacientes pueden gestionar sus citas y documentos.',
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
    Route::get('/', \App\Livewire\Psychologist\Appointments\Calendar::class)->name('index');
    Route::get('/create', \App\Livewire\Psychologist\Appointments\AppointmentForm::class)->name('create');
    Route::get('/{id}/edit', \App\Livewire\Psychologist\Appointments\AppointmentForm::class)->name('edit');
});

// Clinical Notes
Route::prefix('clinical-notes')->name('clinical-notes.')->group(function () {
    Route::get('/', \App\Livewire\Psychologist\ClinicalNotes\Timeline::class)->name('index');
    Route::get('/create', \App\Livewire\Psychologist\ClinicalNotes\ClinicalNoteForm::class)->name('create');
    Route::get('/{id}/edit', \App\Livewire\Psychologist\ClinicalNotes\ClinicalNoteForm::class)->name('edit');
});

// Subscription Management
Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
Route::patch('/subscription/preferences', [SubscriptionController::class, 'updatePreferences'])->name('subscription.update-preferences');

// Onboarding
Route::post('/onboarding/welcome-seen', [OnboardingController::class, 'welcomeSeen'])->name('onboarding.welcome-seen');

// Under construction pages
Route::get('/under-construction/{feature}', function ($feature) {
    $features = [
        'evaluations' => [
            'title' => 'Evaluaciones Psicológicas',
            'description' => 'Administra escalas estandarizadas con puntuación automática.',
            'features' => [
                'BDI-II: Inventario de Depresión de Beck',
                'GAD-7: Escala de Ansiedad Generalizada',
                'PHQ-9: Cuestionario de Salud del Paciente',
                'Puntuación automática e interpretación',
                'Gráficos de evolución temporal'
            ]
        ],
        'video-consultations' => [
            'title' => 'Videoconsultas Integradas',
            'description' => 'Realiza sesiones online directamente desde Clinora.',
            'features' => [
                'Sala de espera virtual',
                'Videollamada HD cifrada end-to-end',
                'Grabación opcional con consentimiento',
                'Chat durante la sesión'
            ]
        ],
        'reminders' => [
            'title' => 'Recordatorios Automáticos',
            'description' => 'Notifica a tus pacientes automáticamente.',
            'features' => [
                'Emails 24h antes de la cita',
                'SMS opcionales (integración Twilio)',
                'Configuración por psicólogo',
                'Plantillas personalizables'
            ]
        ],
        'patient-portal' => [
            'title' => 'Portal del Paciente',
            'description' => 'Espacio privado para que tus pacientes accedan a su información.',
            'features' => [
                'Autenticación segura',
                'Ver próximas citas',
                'Cancelar/reprogramar con restricciones',
                'Acceso a documentos e informes',
                'Mensajería con el psicólogo'
            ]
        ],
        'booking-system' => [
            'title' => 'Reservas Online',
            'description' => 'Permite que tus pacientes reserven citas directamente.',
            'features' => [
                'Configuración de disponibilidad',
                'Página pública personalizable',
                'Confirmación automática o manual',
                'Integración con Google Calendar',
                'Widget embebible para tu web'
            ]
        ],
        'billing' => [
            'title' => 'Facturación VeriFactu',
            'description' => 'Sistema de facturación completo cumpliendo con VeriFactu.',
            'features' => [
                'Generación de facturas PDF',
                'Numeración automática legal',
                'Cumplimiento VeriFactu (AEAT)',
                'Integración Stripe para pagos',
                'Control de pagos pendientes'
            ]
        ],
    ];

    $data = $features[$feature] ?? [
        'title' => '¡Próximamente!',
        'description' => 'Estamos trabajando en esta funcionalidad.',
        'features' => []
    ];

    return view('components.under-construction', $data);
})->name('under-construction');

// Settings
Route::get('/profile', fn() => view('psychologist.profile'))->name('profile.settings');
Route::get('/security', fn() => view('psychologist.security'))->name('security.settings');

// Logs (admin only) - aunque está aquí, el middleware can.view.logs lo protege
Route::get('/logs', [LogViewerController::class, 'index'])
    ->name('logs.index')
    ->middleware('can.view.logs');
