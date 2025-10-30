<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\WeeklyCommissionReport;
use App\Services\DepositService;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepositController extends Controller
{
    public function __construct(
        private DepositService $depositService,
        private InvoiceService $invoiceService
    ) {}

    /**
     * Display a listing of deposits
     */
    public function index(Request $request): View
    {
        $query = Deposit::with(['company', 'booking']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('invoice_date', '<=', $request->date_to);
        }

        $deposits = $query->latest('created_at')->paginate(20);

        $statistics = [
            'total_deposits' => Deposit::count(),
            'pending_deposits' => Deposit::where('status', 'pending')->count(),
            'sent_deposits' => Deposit::where('status', 'sent')->count(),
            'paid_deposits' => Deposit::where('status', 'paid')->count(),
            'overdue_deposits' => Deposit::where('status', 'sent')
                ->where('due_date', '<', now()->toDateString())
                ->count(),
            'total_amount' => Deposit::sum('deposit_amount'),
            'pending_amount' => Deposit::where('status', 'pending')->sum('deposit_amount'),
            'overdue_amount' => Deposit::where('status', 'sent')
                ->where('due_date', '<', now()->toDateString())
                ->sum('deposit_amount'),
        ];

        return view('admin.deposits.index', compact('deposits', 'statistics'));
    }

    /**
     * Display the specified deposit
     */
    public function show(Deposit $deposit): View
    {
        $deposit->load(['company', 'booking.service', 'booking.city', 'sentBy']);
        
        return view('admin.deposits.show', compact('deposit'));
    }

    /**
     * Send deposit invoice to company
     */
    public function send(Deposit $deposit): RedirectResponse
    {
        try {
            $invoice = $this->depositService->createInvoiceForDeposit($deposit);
            $this->invoiceService->sendInvoice($invoice);
            
            $deposit->update([
                'status' => 'sent',
                'sent_at' => now(),
                'sent_by' => auth()->id(),
            ]);

            return redirect()->route('admin.deposits.show', $deposit)
                ->with('success', 'Deposit faktura skickad till företaget.');
        } catch (\Exception $e) {
            return redirect()->route('admin.deposits.show', $deposit)
                ->with('error', 'Kunde inte skicka faktura: ' . $e->getMessage());
        }
    }

    /**
     * Mark deposit as paid
     */
    public function markAsPaid(Request $request, Deposit $deposit): RedirectResponse
    {
        $request->validate([
            'payment_reference' => 'nullable|string|max:255',
        ]);

        try {
            $deposit->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return redirect()->route('admin.deposits.show', $deposit)
                ->with('success', 'Deposit markerad som betald.');
        } catch (\Exception $e) {
            return redirect()->route('admin.deposits.show', $deposit)
                ->with('error', 'Kunde inte markera som betald: ' . $e->getMessage());
        }
    }

    /**
     * Generate weekly commission reports
     */
    public function generateWeeklyReports(): RedirectResponse
    {
        try {
            $reports = $this->depositService->generateWeeklyCommissionReports();
            
            $message = count($reports) > 0 
                ? "Genererade " . count($reports) . " veckorapporter."
                : "Inga företag hade slutförda bokningar denna vecka.";

            return redirect()->route('admin.deposits.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.deposits.index')
                ->with('error', 'Kunde inte generera rapporter: ' . $e->getMessage());
        }
    }

    /**
     * Display weekly commission reports
     */
    public function weeklyReports(Request $request): View
    {
        try {
            $query = WeeklyCommissionReport::with('company');

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by company
            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }

            // Filter by year
            if ($request->filled('year')) {
                $query->where('year', $request->year);
            }

            $reports = $query->latest('created_at')->paginate(20);

            $statistics = [
                'total_reports' => WeeklyCommissionReport::count(),
                'pending_reports' => WeeklyCommissionReport::where('status', 'pending')->count(),
                'sent_reports' => WeeklyCommissionReport::where('status', 'sent')->count(),
                'paid_reports' => WeeklyCommissionReport::where('status', 'paid')->count(),
                'total_commission' => WeeklyCommissionReport::sum('total_commission'),
                'total_deposits' => WeeklyCommissionReport::sum('net_commission_due'),
            ];

            return view('admin.deposits.weekly-reports', compact('reports', 'statistics'));
        } catch (\Exception $e) {
            // If there's an error (e.g., table doesn't exist), return empty data
            return view('admin.deposits.weekly-reports', [
                'reports' => collect([]),
                'statistics' => [
                    'total_reports' => 0,
                    'pending_reports' => 0,
                    'sent_reports' => 0,
                    'paid_reports' => 0,
                    'total_commission' => 0,
                    'total_deposits' => 0,
                ]
            ]);
        }
    }

    /**
     * Send weekly report invoice
     */
    public function sendWeeklyReport(WeeklyCommissionReport $report): RedirectResponse
    {
        try {
            $invoice = $this->invoiceService->createWeeklyInvoice($report);
            $this->invoiceService->sendInvoice($invoice);

            return redirect()->route('admin.deposits.weekly-reports')
                ->with('success', "Veckorapport skickad för {$report->company->company_name}.");
        } catch (\Exception $e) {
            return redirect()->route('admin.deposits.weekly-reports')
                ->with('error', 'Kunde inte skicka veckorapport: ' . $e->getMessage());
        }
    }
}