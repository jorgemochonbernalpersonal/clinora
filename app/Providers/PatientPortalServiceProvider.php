<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Patient Portal Service Provider
 * 
 * Registers services for the Patient Portal
 */
class PatientPortalServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Only register if module is enabled
        if (!config('modules.patient_portal.enabled', false)) {
            return;
        }

        // Register Patient Portal repositories and services
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Only boot if module is enabled
        if (!config('modules.patient_portal.enabled', false)) {
            return;
        }

        // Load routes
        $this->loadRoutesFrom(base_path('routes/api/patient-portal.php'));

        // Load migrations
        $this->loadMigrationsFrom(database_path('migrations/patient_portal'));
    }
}
