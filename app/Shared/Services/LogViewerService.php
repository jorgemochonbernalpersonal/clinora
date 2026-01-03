<?php

namespace App\Shared\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class LogViewerService
{
    private string $logPath;

    public function __construct()
    {
        $this->logPath = storage_path('logs');
    }

    /**
     * Get all available log files with their dates
     */
    public function getAvailableLogDates(): Collection
    {
        $files = File::files($this->logPath);
        
        return collect($files)
            ->filter(function ($file) {
                return preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log/', $file->getFilename());
            })
            ->map(function ($file) {
                preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log/', $file->getFilename(), $matches);
                return [
                    'date' => $matches[1],
                    'carbon' => Carbon::parse($matches[1]),
                    'path' => $file->getPathname(),
                    'size' => $file->getSize(),
                ];
            })
            ->sortByDesc('date')
            ->values();
    }

    /**
     * Read and parse log file for a specific date
     */
    public function getLogsByDate(string $date): array
    {
        $filename = "laravel-{$date}.log";
        $filepath = $this->logPath . DIRECTORY_SEPARATOR . $filename;

        if (!File::exists($filepath)) {
            return [
                'logs' => collect(),
                'stats' => $this->getEmptyStats(),
                'date' => $date,
                'exists' => false,
            ];
        }

        // Cache parsed logs for 5 minutes to improve performance
        return Cache::remember("logs.{$date}", 300, function() use ($filepath, $date) {
            $content = File::get($filepath);
            $logs = $this->parseLogContent($content);

            return [
                'logs' => $logs,
                'stats' => $this->calculateStats($logs),
                'date' => $date,
                'exists' => true,
            ];
        });
    }

    /**
     * Get today's logs
     */
    public function getTodayLogs(): array
    {
        return $this->getLogsByDate(Carbon::today()->format('Y-m-d'));
    }

    /**
     * Parse log file content into structured entries
     */
    private function parseLogContent(string $content): Collection
    {
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?(\w+)\.(\w+):(.+?)(?=\[\d{4}-\d{2}-\d{2}|$)/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        return collect($matches)->map(function ($match) {
            $message = trim($match[4]);
            $stackTrace = null;

            // Extract stack trace if exists
            if (strpos($message, 'Stack trace:') !== false) {
                $parts = explode('Stack trace:', $message);
                $message = trim($parts[0]);
                $stackTrace = isset($parts[1]) ? trim($parts[1]) : null;
            }

            return [
                'timestamp' => $match[1],
                'carbon' => Carbon::parse($match[1]),
                'environment' => strtolower($match[2]),
                'level' => strtoupper($match[3]),
                'message' => $message,
                'stack_trace' => $stackTrace,
                'id' => md5($match[0]), // Unique ID for expand/collapse
            ];
        })->reverse()->values();
    }

    /**
     * Filter logs by level and search term
     */
    public function filterLogs(Collection $logs, ?string $level = null, ?string $search = null): Collection
    {
        return $logs->filter(function ($log) use ($level, $search) {
            // Filter by level
            if ($level && $level !== 'all' && strtoupper($level) !== $log['level']) {
                return false;
            }

            // Filter by search term
            if ($search && stripos($log['message'], $search) === false) {
                return false;
            }

            return true;
        })->values();
    }

    /**
     * Calculate statistics for logs
     */
    private function calculateStats(Collection $logs): array
    {
        $stats = [
            'total' => $logs->count(),
            'emergency' => 0,
            'alert' => 0,
            'critical' => 0,
            'error' => 0,
            'warning' => 0,
            'notice' => 0,
            'info' => 0,
            'debug' => 0,
        ];

        foreach ($logs as $log) {
            $level = strtolower($log['level']);
            if (isset($stats[$level])) {
                $stats[$level]++;
            }
        }

        return $stats;
    }

    /**
     * Get empty stats structure
     */
    private function getEmptyStats(): array
    {
        return [
            'total' => 0,
            'emergency' => 0,
            'alert' => 0,
            'critical' => 0,
            'error' => 0,
            'warning' => 0,
            'notice' => 0,
            'info' => 0,
            'debug' => 0,
        ];
    }

    /**
     * Get formatted date label
     */
    public function getDateLabel(string $date): string
    {
        $carbon = Carbon::parse($date);
        $today = Carbon::today();
        
        if ($carbon->isToday()) {
            return 'Hoy - ' . $carbon->isoFormat('D [de] MMMM [de] YYYY');
        }
        
        if ($carbon->isYesterday()) {
            return 'Ayer - ' . $carbon->isoFormat('D [de] MMMM [de] YYYY');
        }
        
        return $carbon->isoFormat('D [de] MMMM [de] YYYY');
    }

    /**
     * Download log file
     */
    public function downloadLog(string $date): ?string
    {
        $filename = "laravel-{$date}.log";
        $filepath = $this->logPath . DIRECTORY_SEPARATOR . $filename;

        if (File::exists($filepath)) {
            return $filepath;
        }

        return null;
    }
}
