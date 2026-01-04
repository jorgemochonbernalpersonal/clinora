<?php

namespace App\Console\Commands;

use App\Shared\Helpers\SitemapHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ValidateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate sitemap URLs and structure';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Validating sitemap...');
        $this->newLine();

        $entries = SitemapHelper::getSitemapEntries();
        $errors = [];
        $warnings = [];

        foreach ($entries as $entry) {
            $this->line("Checking: {$entry['url']}");

            // Validate URL format
            if (!SitemapHelper::isValidUrl($entry['url'])) {
                $errors[] = "Invalid URL format: {$entry['url']}";
                $this->error("  ❌ Invalid URL format");
                continue;
            }

            // Check if URL is accessible (optional, can be slow)
            try {
                $response = Http::timeout(5)->head($entry['url']);
                if ($response->successful()) {
                    $this->info("  ✅ Accessible (Status: {$response->status()})");
                } else {
                    $warnings[] = "URL returned status {$response->status()}: {$entry['url']}";
                    $this->warn("  ⚠️  Status: {$response->status()}");
                }
            } catch (\Exception $e) {
                $warnings[] = "Could not verify accessibility: {$entry['url']} - {$e->getMessage()}";
                $this->warn("  ⚠️  Could not verify (this is OK if site is not accessible from CLI)");
            }

            // Validate lastmod format
            try {
                $date = \Carbon\Carbon::parse($entry['lastmod']);
                $this->line("  📅 Last modified: {$date->format('Y-m-d H:i:s')}");
            } catch (\Exception $e) {
                $errors[] = "Invalid lastmod format for {$entry['url']}: {$entry['lastmod']}";
                $this->error("  ❌ Invalid lastmod format");
            }

            // Validate priority
            $priority = (float) $entry['priority'];
            if ($priority < 0 || $priority > 1) {
                $errors[] = "Invalid priority for {$entry['url']}: {$entry['priority']} (must be 0.0-1.0)";
                $this->error("  ❌ Invalid priority");
            }

            $this->newLine();
        }

        // Summary
        $this->newLine();
        $this->info("📊 Summary:");
        $this->line("  Total URLs: " . count($entries));
        $this->line("  Errors: " . count($errors));
        $this->line("  Warnings: " . count($warnings));

        if (!empty($errors)) {
            $this->newLine();
            $this->error("❌ Errors found:");
            foreach ($errors as $error) {
                $this->error("  - {$error}");
            }
        }

        if (!empty($warnings)) {
            $this->newLine();
            $this->warn("⚠️  Warnings:");
            foreach ($warnings as $warning) {
                $this->warn("  - {$warning}");
            }
        }

        if (empty($errors) && empty($warnings)) {
            $this->newLine();
            $this->info("✅ Sitemap validation passed!");
            return Command::SUCCESS;
        }

        return empty($errors) ? Command::SUCCESS : Command::FAILURE;
    }
}

