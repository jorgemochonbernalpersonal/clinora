<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceLivewireAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('livewire/preview-file/*')) {
            \Log::info('Livewire preview RAW DEBUG', [
                'SERVER_REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'EMPTY',
                'SERVER_QUERY_STRING' => $_SERVER['QUERY_STRING'] ?? 'EMPTY',
                'PHP_GET' => $_GET,
                'request_fullUrl' => $request->fullUrl(),
                'request_query' => $request->query->all(),
            ]);
        }
        
        return $next($request);
    }
}
