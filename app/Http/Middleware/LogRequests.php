<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Log Requests Middleware
 * 
 * Logs all incoming requests with useful context
 */
class LogRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // Log request
        $this->logRequest($request);

        $response = $next($request);

        // Log response
        $this->logResponse($request, $response, $startTime);

        return $response;
    }

    /**
     * Log incoming request
     */
    protected function logRequest(Request $request): void
    {
        // Skip logging for health checks and Telescope
        if ($this->shouldSkipLogging($request)) {
            return;
        }

        $context = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
        ];

        // Log request data (excluding sensitive fields)
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $context['input'] = $this->sanitizeInput($request->except(['password', 'password_confirmation', '_token', 'api_token']));
        }

        Log::info('HTTP Request', $context);
    }

    /**
     * Log response
     */
    protected function logResponse(Request $request, Response $response, float $startTime): void
    {
        // Skip logging for health checks and Telescope
        if ($this->shouldSkipLogging($request)) {
            return;
        }

        $duration = round((microtime(true) - $startTime) * 1000, 2); // milliseconds

        $context = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'user_id' => auth()->id(),
        ];

        // Log slow requests
        if ($duration > 1000) { // More than 1 second
            Log::warning('Slow request detected', $context);
        } elseif ($response->getStatusCode() >= 400) {
            Log::warning('HTTP Error Response', $context);
        } else {
            Log::debug('HTTP Response', $context);
        }
    }

    /**
     * Check if request should skip logging
     */
    protected function shouldSkipLogging(Request $request): bool
    {
        $skipPaths = [
            '/up',
            '/telescope',
            '/_livewire',
            '/livewire',
        ];

        foreach ($skipPaths as $path) {
            if (str_starts_with($request->path(), trim($path, '/'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sanitize input to remove sensitive data
     */
    protected function sanitizeInput(array $input): array
    {
        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'token',
            'api_token',
            'secret',
            'credit_card',
            'cvv',
        ];

        foreach ($sensitiveKeys as $key) {
            if (isset($input[$key])) {
                $input[$key] = '***REDACTED***';
            }
        }

        return $input;
    }
}

