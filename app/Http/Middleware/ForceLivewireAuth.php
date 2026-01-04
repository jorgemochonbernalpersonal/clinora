<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceLivewireAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log para debug
        \Log::info('Livewire preview request', [
            'url' => $request->fullUrl(),
            'authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'cookies' => $request->cookies->all(),
        ]);
        
        return $next($request);
    }
}
