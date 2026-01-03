<?php

namespace App\Shared\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * File Helper
 * 
 * Common file handling utility functions
 */
class FileHelper
{
    /**
     * Generate unique filename
     */
    public static function generateFileName(UploadedFile $file, string $prefix = ''): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->timestamp;
        $random = Str::random(8);
        
        if ($prefix) {
            return "{$prefix}_{$timestamp}_{$random}.{$extension}";
        }

        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Get file size in human-readable format
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Check if file exists in storage
     */
    public static function exists(string $path, string $disk = 'local'): bool
    {
        return Storage::disk($disk)->exists($path);
    }

    /**
     * Delete file from storage
     */
    public static function delete(string $path, string $disk = 'local'): bool
    {
        if (self::exists($path, $disk)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * Get file URL
     */
    public static function url(string $path, string $disk = 'public'): ?string
    {
        if (self::exists($path, $disk)) {
            return Storage::disk($disk)->url($path);
        }
        
        return null;
    }
}
