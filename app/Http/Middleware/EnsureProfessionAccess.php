<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfessionAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $profession): Response
    {
        $user = auth()->user();
        
        if (!$user || !$user->professional) {
            abort(403, 'Solo profesionales pueden acceder a esta sección');
        }

        if ($user->professional->profession_type !== $profession) {
            // Redirigir al dashboard correcto según su profesión
            $correctRoute = $user->professional->getProfessionRoute() . '.dashboard';
            return redirect()->route($correctRoute);
        }

        return $next($request);
    }
}
