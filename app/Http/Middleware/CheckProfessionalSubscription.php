<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Check Professional Subscription Middleware
 * 
 * Ensures the authenticated professional has an active subscription
 */
class CheckProfessionalSubscription
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

        // Check if user is a professional
        if (!$user->hasRole('professional')) {
            abort(403, 'Solo profesionales tienen acceso');
        }

        // TODO: Implement actual subscription check
        // For now, we'll allow all professionals
        // if (!$user->professional?->hasActiveSubscription()) {
        //     abort(403, 'Suscripción requerida');
        // }

        return $next($request);
    }
}
