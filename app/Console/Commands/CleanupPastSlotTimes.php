<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\SlotTime;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupPastSlotTimes extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'slots:cleanup-past 
                            {--days=0 : Number of days to keep (0 = delete all past days)}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up slot times for past days to keep the schedule clean';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $daysToKeep = (int) $this->option('days');
        $isDryRun = $this->option('dry-run');
        
        // Calculate the cutoff date
        $cutoffDate = Carbon::today();
        if ($daysToKeep > 0) {
            $cutoffDate = $cutoffDate->subDays($daysToKeep);
        }
        
        $this->info("🧹 Cleaning up slot times before: {$cutoffDate->format('Y-m-d')}");
        
        if ($isDryRun) {
            $this->warn("🔍 DRY RUN MODE - No actual deletions will be performed");
        }
        
        // Find past slot times
        $pastSlots = SlotTime::where('date', '<', $cutoffDate->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        if ($pastSlots->isEmpty()) {
            $this->info("✅ No past slot times found to clean up");
            return Command::SUCCESS;
        }
        
        // Group by date for better reporting
        $slotsByDate = $pastSlots->groupBy('date');
        
        $this->info("📊 Found {$pastSlots->count()} slot times across " . $slotsByDate->count() . " past days:");
        
        foreach ($slotsByDate as $date => $slots) {
            $this->line("  📅 {$date}: {$slots->count()} slots");
        }
        
        if ($isDryRun) {
            $this->info("🔍 DRY RUN: Would delete {$pastSlots->count()} slot times");
            return Command::SUCCESS;
        }
        
        // Confirm deletion
        if (!$this->confirm("Are you sure you want to delete {$pastSlots->count()} past slot times?")) {
            $this->info("❌ Operation cancelled");
            return Command::SUCCESS;
        }
        
        // Delete past slot times
        $deletedCount = SlotTime::where('date', '<', $cutoffDate->format('Y-m-d'))->delete();
        
        $this->info("✅ Successfully deleted {$deletedCount} past slot times");
        
        // Show summary
        $remainingSlots = SlotTime::count();
        $this->info("📊 Remaining slot times in database: {$remainingSlots}");
        
        return Command::SUCCESS;
    }
}