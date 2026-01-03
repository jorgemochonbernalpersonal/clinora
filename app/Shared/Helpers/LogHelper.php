<?php

namespace App\Shared\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Log Helper Functions
 * 
 * Global helper functions for logging throughout the application
 */

/**
 * Log an error with automatic context
 */
function logError(string $message, ?\Throwable $exception = null, array $context = []): void
{
    $context = enrichLogContext($context, $exception);
    
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
 * Log a warning
 */
function logWarning(string $message, array $context = []): void
{
    Log::warning($message, enrichLogContext($context));
}

/**
 * Log info
 */
function logInfo(string $message, array $context = []): void
{
    Log::info($message, enrichLogContext($context));
}

/**
 * Log debug (only in debug mode)
 */
function logDebug(string $message, array $context = []): void
{
    if (config('app.debug')) {
        Log::debug($message, enrichLogContext($context));
    }
}

/**
 * Log critical error
 */
function logCritical(string $message, ?\Throwable $exception = null, array $context = []): void
{
    $context = enrichLogContext($context, $exception);
    
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
function logUserAction(string $action, array $context = []): void
{
    Log::info("User Action: {$action}", enrichLogContext($context, null, [
        'action' => $action,
    ]));
}

/**
 * Log database query (for slow queries)
 */
function logSlowQuery(string $query, float $time, array $bindings = []): void
{
    if ($time > 1.0) { // Log queries slower than 1 second
        Log::warning('Slow database query detected', enrichLogContext([
            'query' => $query,
            'time' => $time . 's',
            'bindings' => $bindings,
        ]));
    }
}

/**
 * Enrich log context with common information
 */
function enrichLogContext(array $context = [], ?\Throwable $exception = null, array $additional = []): array
{
    try {
        $userId = Auth::check() ? Auth::id() : null;
        $userEmail = Auth::check() ? Auth::user()?->email : null;
    } catch (\Exception $e) {
        $userId = null;
        $userEmail = null;
    }

    $enriched = array_merge([
        'user_id' => $userId,
        'user_email' => $userEmail,
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

