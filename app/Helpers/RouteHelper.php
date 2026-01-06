<?php

use App\Shared\Enums\ProfessionType;

if (!function_exists('profession_route')) {
    /**
     * Generate a route URL for the authenticated professional's profession type
     *
     * @param string $name Route name without profession prefix (e.g. 'patients.index')
     * @param mixed $parameters
     * @param bool $absolute
     * @return string
     */
    function profession_route(string $name, $parameters = [], bool $absolute = true): string
    {
        $user = auth()->user();
        $professionPrefix = $user?->professional?->getProfessionRoute() ?? ProfessionType::PSYCHOLOGIST->routePrefix();
        
        return route($professionPrefix . '.' . $name, $parameters, $absolute);
    }
}

if (!function_exists('current_profession')) {
    /**
     * Get the current authenticated professional's profession type
     *
     * @return string|ProfessionType
     */
    function current_profession(): string|ProfessionType
    {
        $user = auth()->user();
        
        return $user?->professional?->profession_type ?? ProfessionType::PSYCHOLOGIST;
    }
}

if (!function_exists('profession_prefix')) {
    /**
     * Get the current authenticated professional's route prefix
     *
     * @return string
     */
    function profession_prefix(): string
    {
        return auth()->user()?->professional?->getProfessionRoute() ?? ProfessionType::PSYCHOLOGIST->routePrefix();
    }
}

if (!function_exists('profession_route_name')) {
    /**
     * Generate a route name with profession prefix
     * 
     * @param string $name Route name without profession prefix (e.g. 'dashboard', 'patients.index')
     * @return string Full route name with profession prefix
     */
    function profession_route_name(string $name): string
    {
        $prefix = profession_prefix();
        return $prefix . '.' . $name;
    }
}
