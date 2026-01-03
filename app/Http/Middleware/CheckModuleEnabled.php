<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check Module Enabled Middleware
 * 
 * Verifies if a specific module is enabled in the configuration
 */
class CheckModuleEnabled
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!config("modules.{$module}.enabled", false)) {
            abort(403, "El módulo {$module} no está disponible");
        }

        return $next($request);
    }
}
