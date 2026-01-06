<?php

namespace App\Http\Middleware;

use App\Shared\Enums\ProfessionType;
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

        // Convert string to ProfessionType Enum for type-safe comparison
        try {
            $requiredProfession = ProfessionType::from($profession);
        } catch (\ValueError $e) {
            abort(404, 'Tipo de profesión no válido');
        }

        if ($user->professional->profession_type !== $requiredProfession) {
            // Redirigir al dashboard correcto según su profesión
            $correctRoute = $user->professional->getProfessionRoute() . '.dashboard';
            return redirect()->route($correctRoute);
        }

        return $next($request);
    }
}
