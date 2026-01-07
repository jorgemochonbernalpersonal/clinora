<?php

namespace App\Shared\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageSeoHelper
{
    /**
     * Generate SEO-friendly alt text from filename
     * 
     * @param string $filename
     * @return string
     */
    public static function generateAltText(string $filename): string
    {
        // Remove extension
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        // Replace separators with spaces
        $name = str_replace(['-', '_'], ' ', $name);
        
        // Capitalize first letter of each word
        $name = Str::title($name);
        
        return $name;
    }
    
    /**
     * Validate that image has alt attribute
     * 
     * @param string $html
     * @return array ['valid' => bool, 'missing_alt' => array]
     */
    public static function validateImageAltTags(string $html): array
    {
        $missingAlt = [];
        
        // Find all img tags
        preg_match_all('/<img[^>]+>/i', $html, $matches);
        
        foreach ($matches[0] as $imgTag) {
            // Check if alt attribute exists
            if (!preg_match('/alt\s*=\s*["\'][^"\']*["\']/i', $imgTag)) {
                // Extract src for reporting
                preg_match('/src\s*=\s*["\']([^"\']+)["\']/i', $imgTag, $srcMatch);
                $src = $srcMatch[1] ?? 'unknown';
                $missingAlt[] = $src;
            }
        }
        
        return [
            'valid' => empty($missingAlt),
            'missing_alt' => $missingAlt,
            'total_images' => count($matches[0]),
            'images_with_alt' => count($matches[0]) - count($missingAlt),
        ];
    }
    
    /**
     * Generate structured data for image
     * 
     * @param string $url
     * @param string $caption
     * @param string|null $title
     * @return array
     */
    public static function generateImageStructuredData(
        string $url,
        string $caption,
        ?string $title = null
    ): array {
        return [
            '@type' => 'ImageObject',
            'url' => $url,
            'caption' => $caption,
            'name' => $title ?? $caption,
        ];
    }
    
    /**
     * Check if image exists and get dimensions
     * 
     * @param string $path
     * @return array|null
     */
    public static function getImageInfo(string $path): ?array
    {
        $fullPath = public_path($path);
        
        if (!File::exists($fullPath)) {
            return null;
        }
        
        $imageInfo = @getimagesize($fullPath);
        
        if (!$imageInfo) {
            return null;
        }
        
        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'mime' => $imageInfo['mime'],
            'size' => File::size($fullPath),
        ];
    }
    
    /**
     * Generate responsive image srcset
     * 
     * @param string $basePath
     * @param array $sizes [width => suffix]
     * @return string
     */
    public static function generateSrcset(string $basePath, array $sizes = []): string
    {
        if (empty($sizes)) {
            $sizes = [
                320 => '-sm',
                640 => '-md',
                1024 => '-lg',
                1920 => '-xl',
            ];
        }
        
        $srcset = [];
        $pathInfo = pathinfo($basePath);
        
        foreach ($sizes as $width => $suffix) {
            $filename = $pathInfo['filename'] . $suffix . '.' . $pathInfo['extension'];
            $path = $pathInfo['dirname'] . '/' . $filename;
            
            if (File::exists(public_path($path))) {
                $srcset[] = asset($path) . " {$width}w";
            }
        }
        
        return implode(', ', $srcset);
    }
    
    /**
     * Optimize image alt text for SEO
     * 
     * @param string $alt
     * @param string|null $keyword
     * @return string
     */
    public static function optimizeAltText(string $alt, ?string $keyword = null): string
    {
        // Remove excessive whitespace
        $alt = preg_replace('/\s+/', ' ', trim($alt));
        
        // Limit length (recommended: 125 characters)
        if (strlen($alt) > 125) {
            $alt = substr($alt, 0, 122) . '...';
        }
        
        // Add keyword if provided and not already present
        if ($keyword && stripos($alt, $keyword) === false) {
            $alt = $keyword . ' - ' . $alt;
            
            // Re-check length
            if (strlen($alt) > 125) {
                $alt = substr($alt, 0, 122) . '...';
            }
        }
        
        return $alt;
    }
    
    /**
     * Generate lazy loading attributes
     * 
     * @return string
     */
    public static function getLazyLoadingAttributes(): string
    {
        return 'loading="lazy" decoding="async"';
    }
    
    /**
     * Check if image format is optimized (WebP, AVIF)
     * 
     * @param string $path
     * @return array
     */
    public static function checkImageOptimization(string $path): array
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        $optimizedFormats = ['webp', 'avif'];
        $isOptimized = in_array($extension, $optimizedFormats);
        
        $recommendations = [];
        
        if (!$isOptimized) {
            $recommendations[] = "Consider converting to WebP format for better compression";
        }
        
        $info = self::getImageInfo($path);
        
        if ($info && $info['size'] > 500000) { // > 500KB
            $recommendations[] = "Image size is large (" . round($info['size'] / 1024, 2) . "KB). Consider compression.";
        }
        
        return [
            'is_optimized' => $isOptimized,
            'format' => $extension,
            'recommendations' => $recommendations,
        ];
    }
}
