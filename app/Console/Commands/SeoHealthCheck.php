<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Shared\Helpers\SitemapHelper;
use App\Shared\Helpers\ImageSeoHelper;

class SeoHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:check {--verbose : Show detailed information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check SEO health of the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Running SEO Health Check...');
        $this->newLine();

        $issues = [];
        $warnings = [];
        $passed = [];

        // Check 1: robots.txt exists
        $this->checkRobotsTxt($passed, $issues);

        // Check 2: Sitemap is accessible
        $this->checkSitemap($passed, $issues);

        // Check 3: Google Analytics is configured
        $this->checkGoogleAnalytics($passed, $warnings);

        // Check 4: Meta tags in layouts
        $this->checkMetaTags($passed, $issues);

        // Check 5: Blog posts exist
        $this->checkBlogPosts($passed, $warnings);

        // Check 6: Images have alt tags
        if ($this->option('verbose')) {
            $this->checkImageAltTags($passed, $warnings);
        }

        // Display results
        $this->displayResults($passed, $warnings, $issues);

        return empty($issues) ? 0 : 1;
    }

    protected function checkRobotsTxt(&$passed, &$issues)
    {
        $robotsPath = public_path('robots.txt');
        
        if (File::exists($robotsPath)) {
            $content = File::get($robotsPath);
            
            if (str_contains($content, 'Sitemap:')) {
                $passed[] = '✅ robots.txt exists and contains sitemap reference';
            } else {
                $issues[] = '❌ robots.txt exists but missing sitemap reference';
            }
        } else {
            $issues[] = '❌ robots.txt not found';
        }
    }

    protected function checkSitemap(&$passed, &$issues)
    {
        try {
            $entries = SitemapHelper::getSitemapEntries();
            
            if (count($entries) > 0) {
                $passed[] = "✅ Sitemap configured with " . count($entries) . " entries";
                
                if ($this->option('verbose')) {
                    foreach ($entries as $entry) {
                        $this->line("   - " . ($entry['url'] ?? 'Unknown URL'));
                    }
                }
            } else {
                $issues[] = '❌ Sitemap has no entries';
            }
        } catch (\Exception $e) {
            $issues[] = '❌ Error generating sitemap: ' . $e->getMessage();
        }
    }

    protected function checkGoogleAnalytics(&$passed, &$warnings)
    {
        $layouts = [
            'app.blade.php',
            'guest.blade.php',
            'dashboard.blade.php',
        ];

        $foundGA = false;
        $foundGTM = false;

        foreach ($layouts as $layout) {
            $path = resource_path("views/layouts/{$layout}");
            
            if (File::exists($path)) {
                $content = File::get($path);
                
                if (str_contains($content, 'G-TJ20C7QSTH')) {
                    $foundGA = true;
                }
                
                if (str_contains($content, 'GTM-KQV6Q5MS')) {
                    $foundGTM = true;
                }
            }
        }

        if ($foundGA) {
            $passed[] = '✅ Google Analytics (G-TJ20C7QSTH) configured';
        } else {
            $warnings[] = '⚠️  Google Analytics not found in layouts';
        }

        if ($foundGTM) {
            $passed[] = '✅ Google Tag Manager (GTM-KQV6Q5MS) configured';
        }
    }

    protected function checkMetaTags(&$passed, &$issues)
    {
        $layouts = [
            'app.blade.php',
            'guest.blade.php',
        ];

        $requiredTags = [
            'meta name="description"',
            'meta property="og:',
            'meta name="twitter:',
            'link rel="canonical"',
        ];

        foreach ($layouts as $layout) {
            $path = resource_path("views/layouts/{$layout}");
            
            if (File::exists($path)) {
                $content = File::get($path);
                $missing = [];

                foreach ($requiredTags as $tag) {
                    if (!str_contains($content, $tag)) {
                        $missing[] = $tag;
                    }
                }

                if (empty($missing)) {
                    $passed[] = "✅ {$layout}: All meta tags present";
                } else {
                    $issues[] = "❌ {$layout}: Missing tags - " . implode(', ', $missing);
                }
            }
        }
    }

    protected function checkBlogPosts(&$passed, &$warnings)
    {
        try {
            $postCount = \App\Models\Post::published()->count();
            
            if ($postCount > 0) {
                $passed[] = "✅ Blog has {$postCount} published posts";
            } else {
                $warnings[] = '⚠️  No published blog posts found';
            }
        } catch (\Exception $e) {
            $warnings[] = '⚠️  Could not check blog posts: ' . $e->getMessage();
        }
    }

    protected function checkImageAltTags(&$passed, &$warnings)
    {
        $viewsPath = resource_path('views');
        $bladeFiles = File::allFiles($viewsPath);
        
        $totalImages = 0;
        $missingAlt = 0;

        foreach ($bladeFiles as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                $validation = ImageSeoHelper::validateImageAltTags($content);
                
                $totalImages += $validation['total_images'];
                $missingAlt += count($validation['missing_alt']);
            }
        }

        if ($totalImages > 0) {
            $percentage = round((($totalImages - $missingAlt) / $totalImages) * 100, 1);
            
            if ($percentage === 100.0) {
                $passed[] = "✅ All {$totalImages} images have alt attributes";
            } else {
                $warnings[] = "⚠️  {$missingAlt} of {$totalImages} images missing alt attributes ({$percentage}% coverage)";
            }
        }
    }

    protected function displayResults($passed, $warnings, $issues)
    {
        $this->newLine();
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('           SEO HEALTH CHECK RESULTS');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        // Passed checks
        if (!empty($passed)) {
            $this->info('✅ PASSED (' . count($passed) . ')');
            foreach ($passed as $item) {
                $this->line('  ' . $item);
            }
            $this->newLine();
        }

        // Warnings
        if (!empty($warnings)) {
            $this->warn('⚠️  WARNINGS (' . count($warnings) . ')');
            foreach ($warnings as $item) {
                $this->line('  ' . $item);
            }
            $this->newLine();
        }

        // Issues
        if (!empty($issues)) {
            $this->error('❌ ISSUES (' . count($issues) . ')');
            foreach ($issues as $item) {
                $this->line('  ' . $item);
            }
            $this->newLine();
        }

        // Summary
        $total = count($passed) + count($warnings) + count($issues);
        $score = $total > 0 ? round((count($passed) / $total) * 100) : 0;

        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info("SEO HEALTH SCORE: {$score}%");
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        if ($score >= 90) {
            $this->info('🎉 Excellent! Your SEO is in great shape.');
        } elseif ($score >= 70) {
            $this->warn('👍 Good, but there\'s room for improvement.');
        } else {
            $this->error('⚠️  Action needed to improve SEO health.');
        }

        $this->newLine();
        $this->info('💡 Tip: Run with --verbose for detailed information');
    }
}
