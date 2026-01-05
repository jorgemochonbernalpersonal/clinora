<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Psychologist routes
            Route::middleware(['web', 'auth', 'verified', 'profession:psychologist'])
                ->prefix('psychologist')
                ->name('psychologist.')
                ->group(base_path('routes/psychologist.php'));

            // Admin routes  
            Route::middleware(['web', 'auth', 'verified', 'role:admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'can.view.logs' => \App\Http\Middleware\CanViewSystemLogs::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'feature' => \App\Http\Middleware\CheckFeatureAccess::class,
            'profession' => \App\Http\Middleware\EnsureProfessionAccess::class,
        ]);
        
        // Debug middleware for Livewire preview
        $middleware->appendToGroup('web', \App\Http\Middleware\ForceLivewireAuth::class);
        
        // Register request logging middleware (optional - can be enabled per route group)
        // $middleware->appendToGroup('web', \App\Http\Middleware\LogRequests::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Log all exceptions
        $exceptions->report(function (\Throwable $e) {
            try {
                $userId = auth()->check() ? auth()->id() : null;
            } catch (\Exception $authException) {
                $userId = null;
            }

            \Illuminate\Support\Facades\Log::error('Unhandled exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId,
                'request_url' => request()?->fullUrl(),
                'request_method' => request()?->method(),
            ]);
        });

        // Render exceptions for API requests
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'Error del servidor',
                    'error' => config('app.debug') ? [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ] : null,
                ], $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500);
            }
        });
    })->create();
