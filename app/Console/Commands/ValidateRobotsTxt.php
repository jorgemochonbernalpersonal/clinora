<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ValidateRobotsTxt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:validate-robots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate robots.txt file structure and SEO configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Validating robots.txt...');
        $this->newLine();

        $robotsPath = public_path('robots.txt');
        
        if (!File::exists($robotsPath)) {
            $this->error('❌ robots.txt file not found at: ' . $robotsPath);
            return Command::FAILURE;
        }

        $content = File::get($robotsPath);
        $errors = [];
        $warnings = [];
        $info = [];

        // Check for required sections
        $checks = [
            'User-agent' => 'Has User-agent directive',
            'Sitemap' => 'Has Sitemap directive',
            'Disallow' => 'Has Disallow directives',
        ];

        foreach ($checks as $keyword => $description) {
            if (stripos($content, $keyword) !== false) {
                $this->info("✅ {$description}");
            } else {
                $warnings[] = "Missing: {$description}";
                $this->warn("⚠️  {$description} - Not found");
            }
        }

        $this->newLine();

        // Check for common issues
        $this->line('Checking for common issues...');

        // Check for sitemap URLs
        preg_match_all('/Sitemap:\s*(.+)/i', $content, $sitemaps);
        if (!empty($sitemaps[1])) {
            $this->info('✅ Found ' . count($sitemaps[1]) . ' sitemap(s):');
            foreach ($sitemaps[1] as $sitemap) {
                $sitemap = trim($sitemap);
                $this->line("   - {$sitemap}");
                
                // Validate URL format
                if (!filter_var($sitemap, FILTER_VALIDATE_URL)) {
                    $errors[] = "Invalid sitemap URL: {$sitemap}";
                    $this->error("   ❌ Invalid URL format");
                } else {
                    $this->info("   ✅ Valid URL");
                }
            }
        } else {
            $warnings[] = "No sitemap URLs found";
            $this->warn("⚠️  No sitemap URLs found");
        }

        $this->newLine();

        // Check for blocked sensitive paths
        $sensitivePaths = ['.env', '.git', 'composer.json', 'package.json'];
        $blockedSensitive = 0;
        foreach ($sensitivePaths as $path) {
            if (stripos($content, "Disallow: /{$path}") !== false || 
                stripos($content, "Disallow: /{$path}/") !== false) {
                $blockedSensitive++;
            }
        }
        
        if ($blockedSensitive === count($sensitivePaths)) {
            $this->info("✅ All sensitive paths are blocked");
        } else {
            $warnings[] = "Some sensitive paths may not be blocked";
            $this->warn("⚠️  Only {$blockedSensitive}/" . count($sensitivePaths) . " sensitive paths blocked");
        }

        // Check for Googlebot configuration
        if (stripos($content, 'User-agent: Googlebot') !== false) {
            $this->info('✅ Googlebot configuration found');
        } else {
            $warnings[] = "No specific Googlebot configuration";
            $this->warn("⚠️  No specific Googlebot configuration");
        }

        // Check for Bingbot configuration
        if (stripos($content, 'User-agent: Bingbot') !== false) {
            $this->info('✅ Bingbot configuration found');
        } else {
            $info[] = "No specific Bingbot configuration (optional)";
            $this->line("ℹ️  No specific Bingbot configuration (optional)");
        }

        // Statistics
        $this->newLine();
        $this->info('📊 Statistics:');
        $disallowCount = substr_count($content, 'Disallow:');
        $allowCount = substr_count($content, 'Allow:');
        $userAgentCount = substr_count($content, 'User-agent:');
        
        $this->line("  Disallow rules: {$disallowCount}");
        $this->line("  Allow rules: {$allowCount}");
        $this->line("  User-agent sections: {$userAgentCount}");
        $this->line("  File size: " . number_format(strlen($content)) . " bytes");

        // Summary
        $this->newLine();
        if (!empty($errors)) {
            $this->error('❌ Errors found:');
            foreach ($errors as $error) {
                $this->error("  - {$error}");
            }
            $this->newLine();
        }

        if (!empty($warnings)) {
            $this->warn('⚠️  Warnings:');
            foreach ($warnings as $warning) {
                $this->warn("  - {$warning}");
            }
            $this->newLine();
        }

        if (empty($errors) && empty($warnings)) {
            $this->info('✅ robots.txt validation passed!');
            return Command::SUCCESS;
        }

        return empty($errors) ? Command::SUCCESS : Command::FAILURE;
    }
}

