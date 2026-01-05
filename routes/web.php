<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogViewerController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO - Sitemap
Route::get('/sitemap.xml', function () {
    try {
        $entries = \App\Shared\Helpers\SitemapHelper::getSitemapEntries();

        // Ensure we always have at least the homepage
        if (empty($entries)) {
            $entries = [
                [
                    'url' => \App\Shared\Helpers\SitemapHelper::getSecureUrl('/'),
                    'lastmod' => \App\Shared\Helpers\SitemapHelper::getHomepageLastMod(),
                    'changefreq' => 'weekly',
                    'priority' => '1.0',
                ],
            ];
        }

        return response()
            ->view('sitemap', ['entries' => $entries], 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600, s-maxage=86400')
            ->header('X-Content-Type-Options', 'nosniff');
    } catch (\Exception $e) {
        // Log the error for debugging
        Log::error('Sitemap generation failed: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString(),
        ]);

        // Return a minimal valid sitemap with just the homepage
        $entries = [
            [
                'url' => url('/'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
        ];

        return response()
            ->view('sitemap', ['entries' => $entries], 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=300, s-maxage=300');
    }
})->name('sitemap');

Route::get('/sitemap.xsl', function () {
    return response()
        ->view('sitemap_style', [], 200)
        ->header('Content-Type', 'application/xml; charset=utf-8')
        ->header('Cache-Control', 'public, max-age=3600');
})->name('sitemap.xsl');

// Legal pages
Route::get('/legal/cookies', fn() => view('legal.cookies'))->name('legal.cookies');
Route::get('/legal/privacy', fn() => view('legal.privacy'))->name('legal.privacy');
Route::get('/legal/terms', fn() => view('legal.terms'))->name('legal.terms');
Route::get('/legal/gdpr', fn() => view('legal.gdpr'))->name('legal.gdpr');

// Public pages
Route::get('/contacto', fn() => view('contacto'))->name('contact');
Route::get('/faqs', fn() => view('faqs'))->name('faqs');
Route::get('/sobre-nosotros', fn() => view('about'))->name('about');

// Blog
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Auth routes
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest');
Route::get('/2fa/verify', fn() => view('auth.verify-2fa'))->name('2fa.verify')->middleware('guest');
Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice')->middleware('auth');
Route::get('/email/verify/{id}/{hash}', [\App\Core\Authentication\Controllers\EmailVerificationController::class, 'verifyWeb'])
    ->middleware(['signed'])
    ->name('verification.verify');
Route::post('/logout', function () {
    session()->forget(['api_token', 'user']);
    auth()->logout();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// ============================================================================
// Redirect old /dashboard/* routes to /psychologist/* (backwards compatibility)
// ============================================================================
Route::middleware(['auth', 'verified'])
    ->prefix('dashboard')
    ->group(function () {
        // Main dashboard redirect
        Route::get('/', function () {
            $professionRoute = profession_prefix();
            return redirect()->route($professionRoute . '.dashboard');
        })->name('dashboard');

        // Catch-all redirect for any /dashboard/* route
        Route::get('/{any}', function ($any) {
            $professionRoute = profession_prefix();

            // Convert path to route name (e.g. 'patients/create' -> 'patients.create')
            $routeName = str_replace('/', '.', $any);

            // Check if route exists and redirect
            if (Route::has($professionRoute . '.' . $routeName)) {
                return redirect()->route($professionRoute . '.' . $routeName);
            }

            // Default to dashboard
            return redirect()->route($professionRoute . '.dashboard');
        })->where('any', '.*');
    });
