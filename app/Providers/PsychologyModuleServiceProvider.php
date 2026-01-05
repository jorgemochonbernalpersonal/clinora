<?php

namespace App\Providers;

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
        if (!config('modules.psychology.enabled', false)) {
            return;
        }

        // Register Psychology module repositories and services
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Only boot if module is enabled
        if (!config('modules.psychology.enabled', false)) {
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

        // Load migrations
        $this->loadMigrationsFrom(database_path('migrations/modules/psychology'));
    }
}
