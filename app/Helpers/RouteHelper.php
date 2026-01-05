<?php

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
        $professionPrefix = $user?->professional?->getProfessionRoute() ?? 'psychologist';
        
        return route($professionPrefix . '.' . $name, $parameters, $absolute);
    }
}

if (!function_exists('current_profession')) {
    /**
     * Get the current authenticated professional's profession type
     *
     * @return string
     */
    function current_profession(): string
    {
        return auth()->user()?->professional?->profession_type ?? 'psychologist';
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
        return auth()->user()?->professional?->getProfessionRoute() ?? 'psychologist';
    }
}
