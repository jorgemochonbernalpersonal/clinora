<?php

namespace App\Shared\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Loggable Trait
 * 
 * Provides consistent logging methods for controllers and services
 */
trait Loggable
{
    /**
     * Log an error with context
     */
    protected function logError(string $message, ?\Throwable $exception = null, array $context = []): void
    {
        $context = $this->enrichContext($context, $exception);
        
        if ($exception) {
            Log::error($message, array_merge($context, [
                'exception' => [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                ],
            ]));
        } else {
            Log::error($message, $context);
        }
    }

    /**
     * Log a warning with context
     */
    protected function logWarning(string $message, array $context = []): void
    {
        Log::warning($message, $this->enrichContext($context));
    }

    /**
     * Log info with context
     */
    protected function logInfo(string $message, array $context = []): void
    {
        Log::info($message, $this->enrichContext($context));
    }

    /**
     * Log debug information
     */
    protected function logDebug(string $message, array $context = []): void
    {
        if (config('app.debug')) {
            Log::debug($message, $this->enrichContext($context));
        }
    }

    /**
     * Log a critical error
     */
    protected function logCritical(string $message, ?\Throwable $exception = null, array $context = []): void
    {
        $context = $this->enrichContext($context, $exception);
        
        if ($exception) {
            Log::critical($message, array_merge($context, [
                'exception' => [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                ],
            ]));
        } else {
            Log::critical($message, $context);
        }
    }

    /**
     * Log user action
     */
    protected function logUserAction(string $action, array $context = []): void
    {
        Log::info("User Action: {$action}", $this->enrichContext($context, null, [
            'action' => $action,
        ]));
    }

    /**
     * Log API request
     */
    protected function logApiRequest(Request $request, array $context = []): void
    {
        $this->logInfo('API Request', array_merge($context, [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]));
    }

    /**
     * Enrich context with common information
     */
    protected function enrichContext(array $context = [], ?\Throwable $exception = null, array $additional = []): array
    {
        $enriched = array_merge([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'timestamp' => now()->toIso8601String(),
            'environment' => config('app.env'),
        ], $additional, $context);

        // Add request information if available
        if (request()) {
            $enriched['request'] = [
                'method' => request()->method(),
                'url' => request()->fullUrl(),
                'ip' => request()->ip(),
            ];
        }

        return $enriched;
    }
}

