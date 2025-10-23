<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Company;
use App\Models\Payout;
use App\Models\WeeklyPayoutReport;
use App\Models\CommissionSetting;
use App\Notifications\WeeklyPayoutReportNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class PayoutService
{
    /**
     * Calculate Swedish tax implications for company payouts
     */
    public function calculateSwedishTaxes(float $grossIncome, string $companyType = 'enskild_firma'): array
    {
        $taxes = [
            'gross_income' => $grossIncome,
            'social_contributions' => 0,
            'income_tax' => 0,
            'vat_liability' => 0,
            'net_after_taxes' => $grossIncome,
        ];

        // Social contributions (arbetsgivaravgift) - 31.42% for companies
        if ($companyType === 'aktiebolag') {
            $taxes['social_contributions'] = $grossIncome * 0.3142;
        } elseif ($companyType === 'enskild_firma') {
            // For sole proprietorships, social contributions are different
            $taxes['social_contributions'] = $grossIncome * 0.2831; // Approximate rate
        }

        // Income tax calculation (simplified)
        if ($companyType === 'enskild_firma') {
            // Municipal tax + state tax (simplified calculation)
            $taxes['income_tax'] = $grossIncome * 0.32; // Approximate effective rate
        }

        // VAT liability (25% on services, but companies need to handle this separately)
        $taxes['vat_liability'] = $grossIncome * 0.25;

        $taxes['net_after_taxes'] = $grossIncome - $taxes['social_contributions'] - $taxes['income_tax'];

        return $taxes;
    }

    /**
     * Generate weekly payout reports for all companies
     */
    public function generateWeeklyReports(Carbon $weekStart = null, Carbon $weekEnd = null): array
    {
        if (!$weekStart) {
            $weekStart = Carbon::now()->startOfWeek();
        }
        if (!$weekEnd) {
            $weekEnd = Carbon::now()->endOfWeek();
        }

        try {
            DB::beginTransaction();

            $reports = WeeklyPayoutReport::generateAllForWeek($weekStart, $weekEnd);
            
            // Create individual payouts for each booking
            foreach ($reports as $report) {
                $this->createIndividualPayouts($report);
            }

            DB::commit();
            
            Log::info('Weekly payout reports generated', [
                'week_start' => $weekStart->toDateString(),
                'week_end' => $weekEnd->toDateString(),
                'reports_count' => count($reports)
            ]);

            return $reports;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to generate weekly payout reports', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Create individual payouts for bookings in a weekly report
     */
    private function createIndividualPayouts(WeeklyPayoutReport $report): void
    {
        $bookings = Booking::where('company_id', $report->company_id)
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$report->period_start, $report->period_end])
            ->whereDoesntHave('payout')
            ->get();

        foreach ($bookings as $booking) {
            Payout::createFromBooking($booking);
        }
    }

    /**
     * Send weekly reports to companies
     */
    public function sendWeeklyReports(array $reports): int
    {
        $sentCount = 0;

        foreach ($reports as $report) {
            try {
                $company = $report->company;
                if ($company && $company->email) {
                    $company->notify(new WeeklyPayoutReportNotification($report));
                    $report->markAsSent();
                    $sentCount++;
                }
            } catch (\Exception $e) {
                Log::error('Failed to send weekly report', [
                    'company_id' => $report->company_id,
                    'report_id' => $report->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $sentCount;
    }

    /**
     * Get company balance based on completed bookings
     */
    public function getCompanyBalance(int $companyId): array
    {
        $company = Company::findOrFail($companyId);
        
        // Get all completed bookings
        $completedBookings = Booking::where('company_id', $companyId)
            ->where('status', 'completed')
            ->get();

        $totalRevenue = $completedBookings->sum('final_price');
        $totalRotDeduction = $completedBookings->sum('rot_deduction');

        // Calculate total commission
        $commissionSetting = CommissionSetting::getForCompany($companyId);
        $totalCommission = $completedBookings->sum(function ($booking) use ($commissionSetting) {
            $amount = (float) $booking->final_price;
            return $commissionSetting ? $commissionSetting->calculateCommission($amount) : 0.0;
        });

        // Get paid out amount
        $paidOut = Payout::where('company_id', $companyId)
            ->where('status', 'paid')
            ->sum('payout_amount');

        // Get pending payouts
        $pendingPayouts = Payout::where('company_id', $companyId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('payout_amount');

        $currentBalance = $totalRevenue - $totalCommission - $totalRotDeduction - $paidOut;

        // Get recent payouts for display
        $payouts = Payout::where('company_id', $companyId)
            ->with(['booking.service', 'booking.city'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get weekly reports
        $weeklyReports = WeeklyPayoutReport::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return [
            'company' => $company,
            'total_revenue' => $totalRevenue,
            'total_commission' => $totalCommission,
            'total_rot_deduction' => $totalRotDeduction,
            'paid_out' => $paidOut,
            'pending_payouts' => $pendingPayouts,
            'current_balance' => $currentBalance,
            'completed_bookings_count' => $completedBookings->count(),
            'commission_rate' => $commissionSetting ? $commissionSetting->commission_rate : 0,
            'payouts' => $payouts,
            'weekly_reports' => $weeklyReports,
        ];
    }

    /**
     * Approve pending payouts for a company
     */
    public function approvePayouts(int $companyId, int $approvedBy): array
    {
        $payouts = Payout::where('company_id', $companyId)
            ->where('status', 'pending')
            ->get();

        $approved = [];
        foreach ($payouts as $payout) {
            if ($payout->approve($approvedBy)) {
                $approved[] = $payout;
            }
        }

        return $approved;
    }

    /**
     * Mark approved payouts as paid
     */
    public function markPayoutsAsPaid(int $companyId): array
    {
        $payouts = Payout::where('company_id', $companyId)
            ->where('status', 'approved')
            ->get();

        $paid = [];
        foreach ($payouts as $payout) {
            if ($payout->markAsPaid()) {
                $paid[] = $payout;
            }
        }

        return $paid;
    }

    /**
     * Get payout history for a company
     */
    public function getCompanyPayoutHistory(int $companyId, int $limit = 50): array
    {
        $payouts = Payout::where('company_id', $companyId)
            ->with(['booking.service', 'booking.city'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $weeklyReports = WeeklyPayoutReport::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return [
            'payouts' => $payouts,
            'weekly_reports' => $weeklyReports,
        ];
    }
}
