<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DepositService;
use App\Services\InvoiceService;
use Illuminate\Console\Command;

class GenerateWeeklyCommissionReports extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reports:generate-weekly-commission 
                            {--send : Automatically send invoices to companies}
                            {--week= : Specific week number (default: last week)}
                            {--year= : Specific year (default: current year)}';

    /**
     * The console command description.
     */
    protected $description = 'Generate weekly commission reports and optionally send invoices to companies';

    /**
     * Execute the console command.
     */
    public function handle(DepositService $depositService, InvoiceService $invoiceService): int
    {
        $shouldSend = $this->option('send');
        $weekNumber = $this->option('week');
        $year = $this->option('year') ?? now()->year;
        
        $this->info('📊 Generating weekly commission reports...');
        
        try {
            // Generate reports
            $reports = $depositService->generateWeeklyCommissionReports();
            
            if (empty($reports)) {
                $this->info('✅ No companies had completed bookings this week.');
                return Command::SUCCESS;
            }
            
            $this->info("📈 Generated {$reports->count()} commission reports");
            
            // Show summary
            $totalCommission = $reports->sum('total_commission');
            $totalDeposits = $reports->sum('net_deposit');
            $totalBookings = $reports->sum('total_bookings');
            
            $this->table(
                ['Company', 'Bookings', 'Commission', 'Deposit Amount', 'Status'],
                $reports->map(function ($report) {
                    return [
                        $report->company->company_name,
                        $report->total_bookings,
                        number_format($report->total_commission, 2) . ' SEK',
                        number_format($report->net_deposit, 2) . ' SEK',
                        $report->status,
                    ];
                })
            );
            
            $this->info("💰 Total Commission: " . number_format($totalCommission, 2) . " SEK");
            $this->info("💳 Total Deposits: " . number_format($totalDeposits, 2) . " SEK");
            $this->info("📅 Total Bookings: {$totalBookings}");
            
            // Send invoices if requested
            if ($shouldSend) {
                $this->info('📧 Sending invoices to companies...');
                
                $sentCount = 0;
                foreach ($reports as $report) {
                    try {
                        $invoice = $invoiceService->createWeeklyInvoice($report);
                        $invoiceService->sendInvoice($invoice);
                        $sentCount++;
                        $this->line("✅ Sent invoice to {$report->company->company_name}");
                    } catch (\Exception $e) {
                        $this->error("❌ Failed to send invoice to {$report->company->company_name}: " . $e->getMessage());
                    }
                }
                
                $this->info("📬 Sent {$sentCount} invoices successfully");
            } else {
                $this->info('💡 Use --send flag to automatically send invoices to companies');
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to generate reports: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}