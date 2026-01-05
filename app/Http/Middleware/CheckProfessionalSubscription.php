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

        // Check if professional has an active subscription
        if (!$user->professional?->hasActiveSubscription()) {
            return redirect()->route(profession_prefix() . '.dashboard')
                ->with('error', 'Tu suscripción no está activa. Por favor, contacta con soporte.');
        }

        return $next($request);
    }
}
