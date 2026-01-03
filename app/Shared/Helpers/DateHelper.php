<?php

namespace App\Shared\Helpers;

use Carbon\Carbon;

/**
 * Date Helper
 * 
 * Common date/time utility functions
 */
class DateHelper
{
    /**
     * Format date for display
     */
    public static function formatDate(?Carbon $date): string
    {
        if (!$date) {
            return '-';
        }

        return $date->format('d/m/Y');
    }

    /**
     * Format datetime for display
     */
    public static function formatDateTime(?Carbon $date): string
    {
        if (!$date) {
            return '-';
        }

        return $date->format('d/m/Y H:i');
    }

    /**
     * Get human-readable time difference
     */
    public static function diffForHumans(?Carbon $date): string
    {
        if (!$date) {
            return '-';
        }

        return $date->diffForHumans();
    }

    /**
     * Check if date is in the past
     */
    public static function isPast(?Carbon $date): bool
    {
        if (!$date) {
            return false;
        }

        return $date->isPast();
    }

    /**
     * Check if date is in the future
     */
    public static function isFuture(?Carbon $date): bool
    {
        if (!$date) {
            return false;
        }

        return $date->isFuture();
    }

    /**
     * Get start of day
     */
    public static function startOfDay(?Carbon $date = null): Carbon
    {
        return ($date ?? now())->copy()->startOfDay();
    }

    /**
     * Get end of day
     */
    public static function endOfDay(?Carbon $date = null): Carbon
    {
        return ($date ?? now())->copy()->endOfDay();
    }
}
