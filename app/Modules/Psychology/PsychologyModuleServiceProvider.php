<?php

namespace App\Modules\Psychology;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Core\ConsentForms\Services\ConsentFormTemplateRegistry;
use App\Modules\Psychology\ConsentForms\Templates\InitialTreatmentTemplate;
use App\Modules\Psychology\ConsentForms\Templates\TeleconsultationTemplate;
use Illuminate\Support\ServiceProvider;

/**
 * Psychology Module Service Provider
 * 
 * Registers services for the Psychology module
 */
class PsychologyModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Only register if module is enabled
        if (!config('modules.psychology.enabled', true)) {
            return;
        }

        // Register repositories
        $this->app->singleton(
            \App\Modules\Psychology\ClinicalNotes\Repositories\ClinicalNoteRepository::class
        );

        // Register services
        $this->app->singleton(
            \App\Modules\Psychology\ClinicalNotes\Services\ClinicalNoteService::class,
            function ($app) {
                return new \App\Modules\Psychology\ClinicalNotes\Services\ClinicalNoteService(
                    $app->make(\App\Modules\Psychology\ClinicalNotes\Repositories\ClinicalNoteRepository::class)
                );
            }
        );
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Only boot if module is enabled
        if (!config('modules.psychology.enabled', true)) {
            return;
        }

        // Register consent form templates for Psychology module
        ConsentFormTemplateRegistry::register('psychology', [
            ConsentForm::TYPE_INITIAL_TREATMENT => InitialTreatmentTemplate::class,
            ConsentForm::TYPE_TELECONSULTATION => TeleconsultationTemplate::class,
            // Add more templates as needed
        ]);

        // Load routes
        $this->loadRoutesFrom(base_path('routes/api/psychology.php'));

        // Load migrations if directory exists
        $migrationsPath = database_path('migrations/modules/psychology');
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        // Load views if they exist
        $viewsPath = __DIR__ . '/Resources/Views';
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'psychology');
        }
    }
}

