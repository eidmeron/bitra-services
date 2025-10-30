<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Company;
use App\Models\CommissionSetting;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepositService
{
    /**
     * Create a deposit for a completed booking
     */
    public function createDepositForBooking(Booking $booking): Deposit
    {
        return DB::transaction(function () use ($booking) {
            $company = $booking->company;
            $commissionSetting = $company->commissionSetting;
            
            if (!$commissionSetting) {
                throw new \Exception("No commission setting found for company {$company->id}");
            }
            
            // Calculate commission
            $commissionRate = $commissionSetting->commission_rate;
            $commissionAmount = $booking->final_price * ($commissionRate / 100);
            
            // Calculate deposit amount (commission - loyalty points used)
            $loyaltyPointsValue = $booking->loyalty_points_value ?? 0;
            $depositAmount = $commissionAmount - $loyaltyPointsValue;
            
            // Update booking with commission details
            $booking->update([
                'commission_amount' => $commissionAmount,
                'commission_rate' => $commissionRate,
            ]);
            
            // Create deposit record
            $deposit = Deposit::create([
                'company_id' => $company->id,
                'booking_id' => $booking->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => now()->toDateString(),
                'due_date' => now()->addDays(30)->toDateString(),
                'booking_amount' => $booking->final_price,
                'commission_amount' => $commissionAmount,
                'commission_rate' => $commissionRate,
                'loyalty_points_used' => $booking->loyalty_points_used ?? 0,
                'loyalty_points_value' => $loyaltyPointsValue,
                'deposit_amount' => max(0, $depositAmount), // Never negative
                'status' => 'pending',
            ]);
            
            // Create invoice if auto-send is enabled
            $invoiceSettings = InvoiceSetting::getSettings();
            if ($invoiceSettings->auto_send_invoices) {
                $this->createInvoiceForDeposit($deposit);
            }
            
            return $deposit;
        });
    }
    
    /**
     * Create invoice for a deposit
     */
    public function createInvoiceForDeposit(Deposit $deposit): Invoice
    {
        $invoiceSettings = InvoiceSetting::getSettings();
        
        $invoice = Invoice::create([
            'company_id' => $deposit->company_id,
            'invoice_number' => $deposit->invoice_number,
            'invoice_date' => $deposit->invoice_date,
            'due_date' => $deposit->due_date,
            'period_start' => $deposit->booking->created_at->toDateString(),
            'period_end' => $deposit->booking->created_at->toDateString(),
            'subtotal' => $deposit->deposit_amount,
            'vat_rate' => 25.00, // Swedish VAT
            'vat_amount' => $deposit->deposit_amount * 0.25,
            'total_amount' => $deposit->deposit_amount * 1.25,
            'total_bookings' => 1,
            'total_revenue' => $deposit->booking_amount,
            'total_commission' => $deposit->commission_amount,
            'total_loyalty_points_used' => $deposit->loyalty_points_used,
            'total_loyalty_points_value' => $deposit->loyalty_points_value,
            'net_deposit' => $deposit->deposit_amount,
            'status' => 'draft',
            'bankgiro_number' => $invoiceSettings->bankgiro_number,
            'payment_reference_number' => $this->generatePaymentReference($deposit),
        ]);
        
        // Create invoice item
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'booking_id' => $deposit->booking_id,
            'description' => "Kommission för bokning #{$deposit->booking->booking_number}",
            'details' => "Tjänst: {$deposit->booking->service->name}",
            'quantity' => 1,
            'unit_price' => $deposit->deposit_amount,
            'total_price' => $deposit->deposit_amount,
            'commission_rate' => $deposit->commission_rate,
            'commission_amount' => $deposit->commission_amount,
            'loyalty_points_used' => $deposit->loyalty_points_used,
            'loyalty_points_value' => $deposit->loyalty_points_value,
        ]);
        
        return $invoice;
    }
    
    /**
     * Generate weekly commission reports
     */
    public function generateWeeklyCommissionReports(): array
    {
        $reports = [];
        $lastWeek = Carbon::now()->subWeek();
        $weekNumber = $lastWeek->week;
        $year = $lastWeek->year;
        
        $companies = Company::where('status', 'active')->get();
        
        foreach ($companies as $company) {
            $completedBookings = $company->bookings()
                ->where('status', 'completed')
                ->whereBetween('completed_at', [
                    $lastWeek->startOfWeek()->toDateString(),
                    $lastWeek->endOfWeek()->toDateString()
                ])
                ->get();
            
            if ($completedBookings->isEmpty()) {
                continue; // Skip companies with no completed bookings
            }
            
            $totalRevenue = $completedBookings->sum('final_price');
            $totalCommission = $completedBookings->sum('commission_amount');
            $totalLoyaltyPointsUsed = $completedBookings->sum('loyalty_points_used');
            $totalLoyaltyPointsValue = $completedBookings->sum('loyalty_points_value');
            $netDeposit = $totalCommission - $totalLoyaltyPointsValue;
            
            $report = \App\Models\WeeklyCommissionReport::create([
                'company_id' => $company->id,
                'week_number' => $weekNumber,
                'year' => $year,
                'period_start' => $lastWeek->startOfWeek()->toDateString(),
                'period_end' => $lastWeek->endOfWeek()->toDateString(),
                'total_bookings' => $completedBookings->count(),
                'total_revenue' => $totalRevenue,
                'total_commission' => $totalCommission,
                'total_loyalty_points_used' => $totalLoyaltyPointsUsed,
                'total_loyalty_points_value' => $totalLoyaltyPointsValue,
                'net_deposit' => max(0, $netDeposit),
                'status' => 'pending',
            ]);
            
            $reports[] = $report;
        }
        
        return $reports;
    }
    
    /**
     * Generate invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $prefix = 'DEP';
        
        $lastDeposit = Deposit::where('invoice_number', 'like', "{$prefix}-{$year}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastDeposit) {
            $lastNumber = (int) substr($lastDeposit->invoice_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf('%s-%d-%06d', $prefix, $year, $newNumber);
    }
    
    /**
     * Generate payment reference number
     */
    private function generatePaymentReference(Deposit $deposit): string
    {
        return sprintf('BITRA-%s', $deposit->invoice_number);
    }
}
