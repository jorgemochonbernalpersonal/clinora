<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:clean-unverified 
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--hours=24 : Hours after which unverified users are deleted}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up users who have not verified their email within the specified time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $dryRun = $this->option('dry-run');

        $this->info("🔍 Searching for unverified users older than {$hours} hours...");
        
        // Find unverified users
        $unverifiedUsers = User::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subHours($hours))
            ->get();

        $count = $unverifiedUsers->count();

        if ($count === 0) {
            $this->info('✅ No unverified users found to clean up.');
            return Command::SUCCESS;
        }

        $this->warn("Found {$count} unverified user(s):");
        
        // Display users that will be deleted
        $this->table(
            ['ID', 'Email', 'Name', 'Created At', 'Age (hours)'],
            $unverifiedUsers->map(function ($user) {
                return [
                    $user->id,
                    $user->email,
                    $user->full_name,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->created_at->diffInHours(now()),
                ];
            })
        );

        if ($dryRun) {
            $this->warn('🔸 DRY RUN MODE - No users were deleted');
            return Command::SUCCESS;
        }

        if (!$this->confirm('Are you sure you want to delete these users?', false)) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        $deleted = 0;
        $failed = 0;

        foreach ($unverifiedUsers as $user) {
            try {
                Log::info('[CLEAN_UNVERIFIED] Deleting unverified user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'hours_old' => $user->created_at->diffInHours(now()),
                ]);

                // Soft delete
                $user->delete();
                $deleted++;

                $this->line("✓ Deleted: {$user->email}");

            } catch (\Exception $e) {
                $failed++;
                
                Log::error('[CLEAN_UNVERIFIED] Failed to delete user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);

                $this->error("✗ Failed to delete: {$user->email} - {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("✅ Cleanup complete!");
        $this->info("   Deleted: {$deleted}");
        
        if ($failed > 0) {
            $this->warn("   Failed: {$failed}");
        }

        return Command::SUCCESS;
    }
}
