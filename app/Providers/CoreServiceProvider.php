<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Core Service Provider
 *
 * Registers all core module services, repositories, and bindings
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Register Repositories
        $this->app->singleton(
            \App\Core\Contacts\Repositories\ContactRepository::class
        );

        $this->app->singleton(
            \App\Core\Appointments\Repositories\AppointmentRepository::class
        );

        $this->app->singleton(
            \App\Core\ConsentForms\Repositories\ConsentFormRepository::class
        );

        // Register Authentication Repositories
        $this->app->singleton(
            \App\Core\Authentication\Repositories\UserRepository::class
        );

        $this->app->singleton(
            \App\Core\Authentication\Repositories\ProfessionalRepository::class
        );

        // Register Services
        $this->app->singleton(
            \App\Core\Contacts\Services\ContactService::class,
            function ($app) {
                return new \App\Core\Contacts\Services\ContactService(
                    $app->make(\App\Core\Contacts\Repositories\ContactRepository::class)
                );
            }
        );

        $this->app->singleton(
            \App\Core\Appointments\Services\AppointmentService::class,
            function ($app) {
                return new \App\Core\Appointments\Services\AppointmentService(
                    $app->make(\App\Core\Appointments\Repositories\AppointmentRepository::class)
                );
            }
        );

        $this->app->singleton(
            \App\Core\ConsentForms\Services\ConsentFormService::class
        );

        // Register Authentication Service
        $this->app->singleton(
            \App\Core\Authentication\Services\AuthService::class,
            function ($app) {
                return new \App\Core\Authentication\Services\AuthService(
                    $app->make(\App\Core\Authentication\Repositories\UserRepository::class),
                    $app->make(\App\Core\Authentication\Repositories\ProfessionalRepository::class)
                );
            }
        );
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Load routes
        $this->loadRoutesFrom(base_path('routes/api/core.php'));

        // Load migrations (if they exist in migrations/core directory)
        if (is_dir(database_path('migrations/core'))) {
            $this->loadMigrationsFrom(database_path('migrations/core'));
        }

        // Load views if needed
        // $this->loadViewsFrom(__DIR__.'/../../resources/views/core', 'core');
    }
}
