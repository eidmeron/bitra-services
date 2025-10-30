<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceSetting;
use App\Models\WeeklyCommissionReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Create weekly invoice for a company
     */
    public function createWeeklyInvoice(WeeklyCommissionReport $report): Invoice
    {
        return DB::transaction(function () use ($report) {
            $invoiceSettings = InvoiceSetting::getSettings();
            
            $invoice = Invoice::create([
                'company_id' => $report->company_id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => now()->toDateString(),
                'due_date' => now()->addDays($invoiceSettings->payment_due_days)->toDateString(),
                'period_start' => $report->period_start,
                'period_end' => $report->period_end,
                'week_number' => $report->week_number,
                'year' => $report->year,
                'subtotal' => $report->net_deposit,
                'vat_rate' => 25.00, // Swedish VAT
                'vat_amount' => $report->net_deposit * 0.25,
                'total_amount' => $report->net_deposit * 1.25,
                'total_bookings' => $report->total_bookings,
                'total_revenue' => $report->total_revenue,
                'total_commission' => $report->total_commission,
                'total_loyalty_points_used' => $report->total_loyalty_points_used,
                'total_loyalty_points_value' => $report->total_loyalty_points_value,
                'net_deposit' => $report->net_deposit,
                'status' => 'draft',
                'bankgiro_number' => $invoiceSettings->bankgiro_number,
                'payment_reference_number' => $this->generatePaymentReference($report),
            ]);
            
            // Create invoice items
            if ($invoiceSettings->include_booking_details) {
                $this->createDetailedInvoiceItems($invoice, $report);
            } else {
                $this->createSummaryInvoiceItem($invoice, $report);
            }
            
            // Update report with invoice number
            $report->update(['invoice_number' => $invoice->invoice_number]);
            
            return $invoice;
        });
    }
    
    /**
     * Create detailed invoice items for each booking
     */
    private function createDetailedInvoiceItems(Invoice $invoice, WeeklyCommissionReport $report): void
    {
        $company = $report->company;
        $bookings = $company->bookings()
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$report->period_start, $report->period_end])
            ->get();
        
        foreach ($bookings as $booking) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'booking_id' => $booking->id,
                'description' => "Kommission för bokning #{$booking->booking_number}",
                'details' => "Tjänst: {$booking->service->name} - {$booking->service->description}",
                'quantity' => 1,
                'unit_price' => $booking->commission_amount,
                'total_price' => $booking->commission_amount,
                'commission_rate' => $booking->commission_rate,
                'commission_amount' => $booking->commission_amount,
                'loyalty_points_used' => $booking->loyalty_points_used,
                'loyalty_points_value' => $booking->loyalty_points_value,
            ]);
        }
    }
    
    /**
     * Create summary invoice item
     */
    private function createSummaryInvoiceItem(Invoice $invoice, WeeklyCommissionReport $report): void
    {
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'booking_id' => null,
            'description' => "Veckovis kommission - {$report->getPeriodLabel()}",
            'details' => "{$report->total_bookings} bokningar, total omsättning: " . number_format($report->total_revenue, 2) . " SEK",
            'quantity' => 1,
            'unit_price' => $report->net_deposit,
            'total_price' => $report->net_deposit,
            'commission_rate' => 0, // Will be calculated from total
            'commission_amount' => $report->total_commission,
            'loyalty_points_used' => $report->total_loyalty_points_used,
            'loyalty_points_value' => $report->total_loyalty_points_value,
        ]);
    }
    
    /**
     * Send invoice to company
     */
    public function sendInvoice(Invoice $invoice): bool
    {
        try {
            // Here you would implement email sending logic
            // For now, we'll just update the status
            
            $invoice->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            
            // Update related report if it exists
            if ($invoice->week_number && $invoice->year) {
                WeeklyCommissionReport::where('company_id', $invoice->company_id)
                    ->where('week_number', $invoice->week_number)
                    ->where('year', $invoice->year)
                    ->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send invoice {$invoice->invoice_number}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark invoice as paid
     */
    public function markInvoiceAsPaid(Invoice $invoice, string $paymentReference = null): bool
    {
        try {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_reference' => $paymentReference,
            ]);
            
            // Update related report if it exists
            if ($invoice->week_number && $invoice->year) {
                WeeklyCommissionReport::where('company_id', $invoice->company_id)
                    ->where('week_number', $invoice->week_number)
                    ->where('year', $invoice->year)
                    ->update(['status' => 'paid']);
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to mark invoice {$invoice->invoice_number} as paid: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate invoice number
     */
    private function generateInvoiceNumber(): string
    {
        return Invoice::generateInvoiceNumber();
    }
    
    /**
     * Generate payment reference number
     */
    private function generatePaymentReference(WeeklyCommissionReport $report): string
    {
        return sprintf('BITRA-%s-%d', $report->week_number, $report->year);
    }
    
    /**
     * Get overdue invoices
     */
    public function getOverdueInvoices(): \Illuminate\Database\Eloquent\Collection
    {
        return Invoice::where('status', 'sent')
            ->where('due_date', '<', now()->toDateString())
            ->with(['company', 'items'])
            ->get();
    }
    
    /**
     * Generate invoice PDF
     */
    public function generateInvoicePdf(Invoice $invoice): string
    {
        // This would generate a PDF and return the file path
        // For now, we'll return a placeholder
        return "invoice-{$invoice->invoice_number}.pdf";
    }
    
    /**
     * Get invoice statistics
     */
    public function getInvoiceStatistics(): array
    {
        $totalInvoices = Invoice::count();
        $totalAmount = Invoice::sum('total_amount');
        $paidAmount = Invoice::where('status', 'paid')->sum('total_amount');
        $overdueAmount = Invoice::where('status', 'sent')
            ->where('due_date', '<', now()->toDateString())
            ->sum('total_amount');
        
        return [
            'total_invoices' => $totalInvoices,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'overdue_amount' => $overdueAmount,
            'pending_amount' => $totalAmount - $paidAmount - $overdueAmount,
        ];
    }
}
