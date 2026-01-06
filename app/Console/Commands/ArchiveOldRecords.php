<?php

namespace App\Console\Commands;

use App\Core\Contacts\Models\Contact;
use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Core\Appointments\Models\Appointment;
use App\Notifications\RecordArchivalNotification;
use App\Notifications\RecordDeletionNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Archive Old Records Command
 * 
 * Implements data retention policy according to Spanish healthcare law:
 * - Archive records older than 5 years
 * - Notify before deletion (7 years)
 * - Delete after notification period (30 days)
 */
class ArchiveOldRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'records:archive 
                            {--dry-run : Run without making changes}
                            {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive old clinical records according to data retention policy (5 years minimum)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $force = $this->option('force');
        
        $this->info('🗄️  Starting data retention policy enforcement...');
        $this->newLine();
        
        if ($isDryRun) {
            $this->warn('⚠️  DRY RUN MODE - No changes will be made');
            $this->newLine();
        }
        
        // Step 1: Archive records older than 5 years
        $this->archiveOldRecords($isDryRun);
        
        // Step 2: Notify about records pending deletion (7 years old)
        $this->notifyPendingDeletion($isDryRun);
        
        // Step 3: Delete records after notification period
        $this->deleteNotifiedRecords($isDryRun, $force);
        
        $this->newLine();
        $this->info('✅ Data retention policy enforcement completed');
        
        return Command::SUCCESS;
    }
    
    /**
     * Archive clinical notes older than 5 years
     */
    protected function archiveOldRecords(bool $isDryRun): void
    {
        $cutoffDate = now()->subYears(5);
        
        $this->info("📦 Step 1: Archiving records older than {$cutoffDate->format('Y-m-d')}");
        
        $query = ClinicalNote::where('session_date', '<', $cutoffDate)
            ->whereNull('archived_at');
        
        $count = $query->count();
        
        if ($count === 0) {
            $this->line('   No records to archive');
            return;
        }
        
        $this->line("   Found {$count} records to archive");
        
        if (!$isDryRun) {
            $updated = $query->update([
                'archived_at' => now(),
            ]);
            
            Log::info('Clinical notes archived', [
                'count' => $updated,
                'cutoff_date' => $cutoffDate->toDateString(),
            ]);
            
            $this->info("   ✓ Archived {$updated} clinical notes");
        } else {
            $this->line("   [DRY RUN] Would archive {$count} clinical notes");
        }
    }
    
    /**
     * Notify professionals about records pending deletion (7 years old)
     */
    protected function notifyPendingDeletion(bool $isDryRun): void
    {
        $deletionDate = now()->subYears(7);
        
        $this->info("📧 Step 2: Notifying about records older than {$deletionDate->format('Y-m-d')}");
        
        $notes = ClinicalNote::where('session_date', '<', $deletionDate)
            ->whereNotNull('archived_at')
            ->whereNull('deletion_notified_at')
            ->with('professional.user')
            ->get();
        
        if ($notes->isEmpty()) {
            $this->line('   No records pending notification');
            return;
        }
        
        $this->line("   Found {$notes->count()} records to notify about");
        
        // Group by professional
        $byProfessional = $notes->groupBy('professional_id');
        
        foreach ($byProfessional as $professionalId => $professionalNotes) {
            $professional = $professionalNotes->first()->professional;
            
            if (!$professional || !$professional->user) {
                continue;
            }
            
            if (!$isDryRun) {
                // Send notification
                $professional->user->notify(new RecordDeletionNotification($professionalNotes));
                
                // Mark as notified
                ClinicalNote::whereIn('id', $professionalNotes->pluck('id'))
                    ->update(['deletion_notified_at' => now()]);
                
                $this->line("   ✓ Notified {$professional->user->name} about {$professionalNotes->count()} records");
            } else {
                $this->line("   [DRY RUN] Would notify {$professional->user->name} about {$professionalNotes->count()} records");
            }
        }
        
        if (!$isDryRun) {
            Log::info('Deletion notifications sent', [
                'professionals_notified' => $byProfessional->count(),
                'records_count' => $notes->count(),
            ]);
        }
    }
    
    /**
     * Delete records 30 days after notification
     */
    protected function deleteNotifiedRecords(bool $isDryRun, bool $force): void
    {
        $notificationCutoff = now()->subDays(30);
        
        $this->info("🗑️  Step 3: Deleting records notified before {$notificationCutoff->format('Y-m-d')}");
        
        $query = ClinicalNote::where('deletion_notified_at', '<', $notificationCutoff)
            ->whereNotNull('deletion_notified_at');
        
        $count = $query->count();
        
        if ($count === 0) {
            $this->line('   No records to delete');
            return;
        }
        
        $this->line("   Found {$count} records ready for deletion");
        
        if (!$isDryRun) {
            if (!$force && !$this->confirm("   Are you sure you want to permanently delete {$count} clinical notes?")) {
                $this->warn('   Deletion cancelled');
                return;
            }
            
            // Soft delete (keeps in database but marked as deleted)
            $deleted = $query->delete();
            
            Log::warning('Clinical notes deleted per retention policy', [
                'count' => $deleted,
                'notification_cutoff' => $notificationCutoff->toDateString(),
            ]);
            
            $this->info("   ✓ Deleted {$deleted} clinical notes");
        } else {
            $this->line("   [DRY RUN] Would delete {$count} clinical notes");
        }
    }
}
