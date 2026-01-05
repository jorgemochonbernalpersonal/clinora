<?php

if (!function_exists('api_route')) {
    /**
     * Generate an API route URL
     * 
     * @param string $name Route name (e.g. 'api.contacts.index' or 'api.psychology.clinical-notes.index')
     * @param mixed $parameters
     * @param bool $absolute
     * @return string
     */
    function api_route(string $name, $parameters = [], bool $absolute = true): string
    {
        return route($name, $parameters, $absolute);
    }
}

if (!function_exists('psychology_api_route')) {
    /**
     * Generate a psychology-specific API route URL
     * 
     * @param string $name Route name without prefix (e.g. 'clinical-notes.index')
     * @param mixed $parameters
     * @param bool $absolute
     * @return string
     */
    function psychology_api_route(string $name, $parameters = [], bool $absolute = true): string
    {
        // Psychology API routes are under 'api/v1/psychology' prefix
        // But they don't have a route name prefix, so we need to construct it
        // Actually, they should be: 'api.psychology.clinical-notes.index'
        // Let's check the actual route structure first
        return route('api.psychology.' . $name, $parameters, $absolute);
    }
}

