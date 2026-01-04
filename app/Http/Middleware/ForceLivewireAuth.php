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
            \Log::info('Livewire preview FULL DEBUG', [
                'full_url' => $request->fullUrl(),
                'query_params' => $request->query->all(),
                'has_expires' => $request->has('expires'),
                'has_signature' => $request->has('signature'),
                'expires_value' => $request->query('expires'),
                'signature_value' => $request->query('signature'),
            ]);
        }
        
        return $next($request);
    }
}
