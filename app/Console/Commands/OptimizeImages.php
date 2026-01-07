<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize 
                            {path? : Path to optimize (default: public/images)}
                            {--webp : Convert to WebP format}
                            {--quality=80 : Quality for compression (1-100)}
                            {--max-width=1920 : Maximum width}
                            {--max-height=1080 : Maximum height}';

    protected $description = 'Optimize images for web (compress, resize, convert to WebP)';

    public function handle()
    {
        $path = $this->argument('path') ?? public_path('images');
        $quality = (int) $this->option('quality');
        $maxWidth = (int) $this->option('max-width');
        $maxHeight = (int) $this->option('max-height');
        $convertWebP = $this->option('webp');

        if (!File::exists($path)) {
            $this->error("Path does not exist: {$path}");
            return 1;
        }

        $this->info("🖼️  Optimizing images in: {$path}");
        $this->newLine();

        $images = File::allFiles($path);
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        $processed = 0;
        $totalSaved = 0;

        foreach ($images as $file) {
            $extension = strtolower($file->getExtension());
            
            if (!in_array($extension, $extensions)) {
                continue;
            }

            try {
                $originalSize = $file->getSize();
                $img = Image::make($file->getPathname());

                // Resize if needed
                if ($img->width() > $maxWidth || $img->height() > $maxHeight) {
                    $img->resize($maxWidth, $maxHeight, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Save optimized version
                $img->save($file->getPathname(), $quality);
                
                $newSize = filesize($file->getPathname());
                $saved = $originalSize - $newSize;
                $totalSaved += $saved;

                $this->line("✅ {$file->getFilename()} - Saved: " . $this->formatBytes($saved));

                // Convert to WebP if requested
                if ($convertWebP) {
                    $webpPath = str_replace('.' . $extension, '.webp', $file->getPathname());
                    $img->encode('webp', $quality)->save($webpPath);
                    $this->line("   📦 Created WebP: " . basename($webpPath));
                }

                $processed++;

            } catch (\Exception $e) {
                $this->error("❌ Error processing {$file->getFilename()}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✅ Optimization Complete!");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("Images processed: {$processed}");
        $this->line("Total space saved: " . $this->formatBytes($totalSaved));
        
        if ($convertWebP) {
            $this->line("WebP versions created: {$processed}");
        }

        return 0;
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
