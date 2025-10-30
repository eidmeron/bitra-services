<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService
    ) {}

    /**
     * Display a listing of invoices
     */
    public function index(Request $request): View
    {
        $query = Invoice::with(['company', 'items']);

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

        $invoices = $query->latest('created_at')->paginate(20);

        $statistics = $this->invoiceService->getInvoiceStatistics();

        return view('admin.invoices.index', compact('invoices', 'statistics'));
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['company', 'items.booking']);
        
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Send invoice to company
     */
    public function send(Invoice $invoice): RedirectResponse
    {
        try {
            $success = $this->invoiceService->sendInvoice($invoice);
            
            if ($success) {
                return redirect()->route('admin.invoices.show', $invoice)
                    ->with('success', 'Faktura skickad till företaget.');
            } else {
                return redirect()->route('admin.invoices.show', $invoice)
                    ->with('error', 'Kunde inte skicka faktura. Kontrollera e-postinställningar.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('error', 'Kunde inte skicka faktura: ' . $e->getMessage());
        }
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Request $request, Invoice $invoice): RedirectResponse
    {
        $request->validate([
            'payment_reference' => 'nullable|string|max:255',
        ]);

        try {
            $success = $this->invoiceService->markInvoiceAsPaid(
                $invoice, 
                $request->input('payment_reference')
            );
            
            if ($success) {
                return redirect()->route('admin.invoices.show', $invoice)
                    ->with('success', 'Faktura markerad som betald.');
            } else {
                return redirect()->route('admin.invoices.show', $invoice)
                    ->with('error', 'Kunde inte markera som betald.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('error', 'Kunde inte markera som betald: ' . $e->getMessage());
        }
    }

    /**
     * Generate invoice PDF
     */
    public function pdf(Invoice $invoice)
    {
        try {
            $pdfPath = $this->invoiceService->generateInvoicePdf($invoice);
            
            return response()->download($pdfPath, "faktura-{$invoice->invoice_number}.pdf");
        } catch (\Exception $e) {
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('error', 'Kunde inte generera PDF: ' . $e->getMessage());
        }
    }

    /**
     * Show invoice settings
     */
    public function settings(): View
    {
        $settings = \App\Models\InvoiceSetting::first();
        
        return view('admin.invoices.settings', compact('settings'));
    }

    /**
     * Update invoice settings
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_org_number' => 'required|string|max:20',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:255',
            'bankgiro_number' => 'required|string|max:20',
            'invoice_prefix' => 'required|string|max:10',
            'payment_due_days' => 'required|integer|min:1|max:365',
            'invoice_footer_text' => 'nullable|string|max:1000',
            'payment_instructions' => 'nullable|string|max:1000',
            'auto_send_invoices' => 'boolean',
            'include_booking_details' => 'boolean',
        ]);

        $settings = \App\Models\InvoiceSetting::first();
        
        if (!$settings) {
            $settings = new \App\Models\InvoiceSetting();
        }

        $settings->update($request->all());

        return redirect()->route('admin.invoices.settings')
            ->with('success', 'Fakturainställningar uppdaterade framgångsrikt.');
    }
}