<?php

$providers = [
    App\Providers\AppServiceProvider::class,
    
    // Clinora Modular Service Providers
    App\Providers\CoreServiceProvider::class,
    App\Providers\PsychologyModuleServiceProvider::class,
    App\Providers\PatientPortalServiceProvider::class,
];

// Only register Telescope if it's installed
if (class_exists(\Laravel\Telescope\TelescopeApplicationServiceProvider::class)) {
    $providers[] = App\Providers\TelescopeServiceProvider::class;
}

return $providers;
