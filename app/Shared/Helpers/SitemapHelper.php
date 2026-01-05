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
     * Get secure URL (force HTTPS in production)
     */
    public static function getSecureUrl(string $path = '/'): string
    {
        $url = url($path);
        
        // Force HTTPS in production
        if (app()->environment('production') && !str_starts_with($url, 'https://')) {
            $url = str_replace('http://', 'https://', $url);
        }
        
        return $url;
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
     * Validate image exists
     */
    public static function imageExists(string $imagePath): bool
    {
        $fullPath = public_path($imagePath);
        return File::exists($fullPath);
    }

    /**
     * Filter valid sitemap entries
     */
    public static function filterValidEntries(array $entries): array
    {
        return array_map(function ($entry) {
            // Validate images if present
            if (isset($entry['images']) && is_array($entry['images'])) {
                $entry['images'] = array_values(array_filter($entry['images'], function ($image) {
                    if (empty($image['loc'])) {
                        return false;
                    }
                    // Extract path from full URL
                    $path = parse_url($image['loc'], PHP_URL_PATH);
                    return $path && self::imageExists($path);
                }));
                
                // Remove images key if empty
                if (empty($entry['images'])) {
                    unset($entry['images']);
                }
            }
            
            return $entry;
        }, array_filter($entries, function ($entry) {
            // Must have URL
            return !empty($entry['url']) && self::isValidUrl($entry['url']);
        }));
    }

    /**
     * Get sitemap entries configuration
     * Centralized configuration for easier maintenance
     */
    public static function getSitemapEntries(): array
    {
        $entries = [
            [
                'url' => self::getSecureUrl('/'),
                'lastmod' => self::getHomepageLastMod(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
                'description' => 'Homepage / Landing Page',
                'images' => [
                    [
                        'loc' => self::getSecureUrl('/images/logo.png'),
                        'caption' => 'Logo de Clinora, software profesional de gestión para clínicas de salud',
                        'title' => 'Clinora - Software de Gestión Clínica',
                    ],
                    // Si tienes un screenshot del dashboard, añádelo aquí:
                    // [
                    //     'loc' => self::getSecureUrl('/images/dashboard-preview.png'),
                    //     'caption' => 'Vista del dashboard de gestión clínica con agenda, pacientes y notas SOAP',
                    //     'title' => 'Dashboard de Clinora',
                    // ],
                ],
            ],
            [
                'url' => self::getSecureUrl('/contacto'),
                'lastmod' => self::getLastModForView('contacto'),
                'changefreq' => 'monthly',
                'priority' => '0.8',
                'description' => 'Contact Page',
            ],
            [
                'url' => self::getSecureUrl('/faqs'),
                'lastmod' => self::getLastModForView('faqs'),
                'changefreq' => 'monthly',
                'priority' => '0.8',
                'description' => 'Frequently Asked Questions',
            ],
            [
                'url' => self::getSecureUrl('/sobre-nosotros'),
                'lastmod' => self::getLastModForView('about'),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'description' => 'About Us',
            ],
            [
                'url' => self::getSecureUrl('/blog'),
                'lastmod' => self::getBlogLastMod(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
                'description' => 'Blog - Resources and Guides',
            ],
            // Añadir posts individuales del blog
            ...self::getBlogPosts(),
            [
                'url' => self::getSecureUrl('/legal/privacy'),
                'lastmod' => self::getLastModForView('legal/privacy'),
                'changefreq' => 'monthly',
                'priority' => '0.3',
                'description' => 'Privacy Policy',
            ],
            [
                'url' => self::getSecureUrl('/legal/terms'),
                'lastmod' => self::getLastModForView('legal/terms'),
                'changefreq' => 'monthly',
                'priority' => '0.3',
                'description' => 'Terms of Service',
            ],
            [
                'url' => self::getSecureUrl('/legal/gdpr'),
                'lastmod' => self::getLastModForView('legal/gdpr'),
                'changefreq' => 'monthly',
                'priority' => '0.3',
                'description' => 'GDPR / Data Protection',
            ],
            [
                'url' => self::getSecureUrl('/legal/cookies'),
                'lastmod' => self::getLastModForView('legal/cookies'),
                'changefreq' => 'monthly',
                'priority' => '0.3',
                'description' => 'Cookies Policy',
            ],
        ];
        
        // Filter and validate all entries
        return self::filterValidEntries($entries);
    }

    /**
     * Get last modification date for blog
     */
    public static function getBlogLastMod(): string
    {
        try {
            $latestPost = \App\Models\Post::published()->first();
            if ($latestPost && $latestPost->updated_at) {
                return $latestPost->updated_at->toAtomString();
            }
        } catch (\Exception $e) {
            // Si la tabla no existe aún, retornar fecha actual
        }
        
        return now()->toAtomString();
    }

    /**
     * Get blog posts for sitemap
     */
    public static function getBlogPosts(): array
    {
        try {
            $posts = \App\Models\Post::published()->get();
            
            return $posts->map(function ($post) {
                // Ensure updated_at exists, fallback to published_at or now
                $lastmod = $post->updated_at 
                    ? $post->updated_at->toAtomString()
                    : ($post->published_at 
                        ? $post->published_at->toAtomString()
                        : now()->toAtomString());
                
                // Get secure URL for blog post
                $postUrl = route('blog.show', $post);
                if (app()->environment('production') && !str_starts_with($postUrl, 'https://')) {
                    $postUrl = str_replace('http://', 'https://', $postUrl);
                }
                
                return [
                    'url' => $postUrl,
                    'lastmod' => $lastmod,
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                    'description' => "Blog Post: {$post->title}",
                ];
            })->filter(function ($entry) {
                // Validate URL before including
                return !empty($entry['url']) && self::isValidUrl($entry['url']);
            })->values()->toArray();
        } catch (\Exception $e) {
            // Si la tabla no existe aún, retornar array vacío
            return [];
        }
    }
}

