<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\PayoutService;
use Carbon\Carbon;
use Illuminate\Console\Command;

final class GenerateWeeklyPayoutReports extends Command
{
    protected $signature = 'payouts:generate-weekly-reports 
                            {--week= : Specific week number (1-53)}
                            {--year= : Specific year (default: current year)}
                            {--send : Send reports via email after generation}';

    protected $description = 'Generate weekly payout reports for all companies';

    public function __construct(
        private readonly PayoutService $payoutService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $week = $this->option('week');
        $year = $this->option('year') ?: Carbon::now()->year;
        $sendReports = $this->option('send');

        // Calculate week start and end dates
        if ($week) {
            $weekStart = Carbon::createFromFormat('Y-W', "{$year}-{$week}")->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
        } else {
            // Default to previous week
            $weekStart = Carbon::now()->subWeek()->startOfWeek();
            $weekEnd = Carbon::now()->subWeek()->endOfWeek();
        }

        $this->info("Generating weekly payout reports for week {$weekStart->week}, {$year}");
        $this->info("Period: {$weekStart->format('Y-m-d')} to {$weekEnd->format('Y-m-d')}");

        try {
            // Generate reports
            $reports = $this->payoutService->generateWeeklyReports($weekStart, $weekEnd);
            
            $this->info("Generated " . count($reports) . " weekly reports");

            if (count($reports) > 0) {
                $this->table(
                    ['Company', 'Bookings', 'Revenue', 'Commission', 'ROT Deduction', 'Net Payout', 'Invoice'],
                    array_map(function ($report) {
                        return [
                            $report->company->company_name,
                            $report->total_bookings,
                            number_format($report->total_revenue, 0, ',', ' ') . ' SEK',
                            number_format($report->total_commission, 0, ',', ' ') . ' SEK',
                            number_format($report->total_rot_deduction, 0, ',', ' ') . ' SEK',
                            number_format($report->net_payout, 0, ',', ' ') . ' SEK',
                            $report->invoice_number ?? '-',
                        ];
                    }, $reports)
                );
            }

            // Send reports if requested
            if ($sendReports && count($reports) > 0) {
                $this->info("Sending reports via email...");
                $sentCount = $this->payoutService->sendWeeklyReports($reports);
                $this->info("Sent {$sentCount} reports via email");
            }

            $this->info("Weekly payout reports generation completed successfully!");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Failed to generate weekly payout reports: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}