<?php

namespace App\Shared\Services;

use App\Modules\Psychology\PsychologyModule;
use Illuminate\Support\ServiceProvider;

/**
 * Module Service Provider
 * 
 * Registers all profession-specific modules in the ModuleRegistry
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Register ModuleRegistry as singleton
        $this->app->singleton(ModuleRegistry::class, function ($app) {
            return new ModuleRegistry();
        });
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        $registry = $this->app->make(ModuleRegistry::class);

        // Register all modules
        $registry->register(new PsychologyModule());
        
        // TODO: Register other modules as they are created
        // $registry->register(new NutritionModule());
        // $registry->register(new PhysiotherapyModule());
    }
}

