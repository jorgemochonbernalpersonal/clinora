<?php

namespace App\Shared\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class SitemapHelper
{
    /**
     * Get last modification date for a view file
     * Falls back to current date if file doesn't exist
     */
    public static function getLastModForView(string $viewPath): string
    {
        $fullPath = resource_path("views/{$viewPath}.blade.php");
        
        if (File::exists($fullPath)) {
            $timestamp = File::lastModified($fullPath);
            return Carbon::createFromTimestamp($timestamp)->toAtomString();
        }
        
        // Fallback to current date
        return now()->toAtomString();
    }

    /**
     * Get last modification date for homepage
     * Checks both landing.blade.php and HomeController
     */
    public static function getHomepageLastMod(): string
    {
        $viewPath = resource_path('views/landing.blade.php');
        $controllerPath = app_path('Http/Controllers/HomeController.php');
        
        $timestamps = [];
        
        if (File::exists($viewPath)) {
            $timestamps[] = File::lastModified($viewPath);
        }
        
        if (File::exists($controllerPath)) {
            $timestamps[] = File::lastModified($controllerPath);
        }
        
        if (!empty($timestamps)) {
            $latest = max($timestamps);
            return Carbon::createFromTimestamp($latest)->toAtomString();
        }
        
        return now()->toAtomString();
    }

    /**
     * Validate URL is accessible
     */
    public static function isValidUrl(string $url): bool
    {
        // Basic validation - in production you might want to do actual HTTP check
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Get sitemap entries configuration
     * Centralized configuration for easier maintenance
     */
    public static function getSitemapEntries(): array
    {
        return [
            [
                'url' => url('/'),
                'lastmod' => self::getHomepageLastMod(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
                'description' => 'Homepage / Landing Page',
            ],
            [
                'url' => url('/legal/privacy'),
                'lastmod' => self::getLastModForView('legal/privacy'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
                'description' => 'Privacy Policy',
            ],
            [
                'url' => url('/legal/terms'),
                'lastmod' => self::getLastModForView('legal/terms'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
                'description' => 'Terms of Service',
            ],
            [
                'url' => url('/legal/gdpr'),
                'lastmod' => self::getLastModForView('legal/gdpr'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
                'description' => 'GDPR / Data Protection',
            ],
            [
                'url' => url('/legal/cookies'),
                'lastmod' => self::getLastModForView('legal/cookies'),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'description' => 'Cookies Policy',
            ],
        ];
    }
}

