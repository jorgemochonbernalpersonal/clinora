<?php

namespace App\Providers;

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

        // Load routes
        $this->loadRoutesFrom(base_path('routes/api/psychology.php'));

        // Load migrations
        $this->loadMigrationsFrom(database_path('migrations/modules/psychology'));
    }
}
