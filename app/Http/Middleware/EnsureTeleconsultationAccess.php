<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensure Teleconsultation Access Middleware
 * 
 * Verifies the user has access to teleconsultation features
 */
class EnsureTeleconsultationAccess
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'No autenticado');
        }

        // Check if teleconsultation module is enabled
        if (!config('modules.teleconsultation.enabled', false)) {
            abort(403, 'Teleconsulta no disponible');
        }

        // TODO: Check if user's subscription includes teleconsultation
        // if (!$user->hasAccessToTeleconsultation()) {
        //     abort(403, 'Tu plan no incluye teleconsulta');
        // }

        return $next($request);
    }
}
