<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanViewSystemLogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado para ver logs del sistema.');
        }

        // Solo administradores pueden ver logs del sistema
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Solo los administradores pueden acceder a los logs del sistema.');
        }

        return $next($request);
    }
}
