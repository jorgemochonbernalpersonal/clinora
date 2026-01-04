<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectLivewirePreview
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Proteger rutas de preview de archivos de Livewire
        if ($request->is('livewire/preview-file/*')) {
            if (!auth()->check()) {
                return response('Unauthorized', 401);
            }
        }
        
        return $next($request);
    }
}

