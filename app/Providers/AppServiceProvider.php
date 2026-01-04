<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers for email notifications and onboarding tracking
        \App\Core\Contacts\Models\Contact::observe(\App\Observers\ContactObserver::class);
        \App\Core\Appointments\Models\Appointment::observe(\App\Observers\AppointmentObserver::class);
        \App\Core\ClinicalNotes\Models\ClinicalNote::observe(\App\Observers\ClinicalNoteObserver::class);
    }
}
