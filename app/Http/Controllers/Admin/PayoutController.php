<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Payout;
use App\Models\WeeklyPayoutReport;
use App\Services\PayoutService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class PayoutController extends Controller
{
    public function __construct(
        private readonly PayoutService $payoutService
    ) {}

    public function index(Request $request): View
    {
        $query = Payout::with(['company', 'booking.service', 'booking.city']);

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
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $payouts = $query->orderBy('created_at', 'desc')->paginate(50);
        $companies = Company::where('status', 'active')->orderBy('company_name')->get();

        // Summary statistics
        $totalPending = Payout::where('status', 'pending')->sum('payout_amount');
        $totalApproved = Payout::where('status', 'approved')->sum('payout_amount');
        $totalPaid = Payout::where('status', 'paid')->sum('payout_amount');
        $totalCancelled = Payout::where('status', 'cancelled')->sum('payout_amount');

        return view('admin.payouts.index', compact(
            'payouts',
            'companies',
            'totalPending',
            'totalApproved',
            'totalPaid',
            'totalCancelled'
        ));
    }

    public function show(Payout $payout): View
    {
        $payout->load(['company', 'booking.service', 'booking.city', 'approver']);
        return view('admin.payouts.show', compact('payout'));
    }

    public function approve(Payout $payout): RedirectResponse
    {
        if ($payout->approve(auth()->id())) {
            return redirect()->back()->with('success', 'Utbetalning godkänd.');
        }

        return redirect()->back()->with('error', 'Kunde inte godkänna utbetalningen.');
    }

    public function markAsPaid(Payout $payout): RedirectResponse
    {
        if ($payout->markAsPaid()) {
            return redirect()->back()->with('success', 'Utbetalning markerad som betald.');
        }

        return redirect()->back()->with('error', 'Kunde inte markera utbetalningen som betald.');
    }

    public function bulkApprove(Request $request): RedirectResponse
    {
        $payoutIds = $request->input('payout_ids', []);
        
        if (empty($payoutIds)) {
            return redirect()->back()->with('error', 'Inga utbetalningar valda.');
        }

        $approved = 0;
        foreach ($payoutIds as $payoutId) {
            $payout = Payout::find($payoutId);
            if ($payout && $payout->status === 'pending' && $payout->approve(auth()->id())) {
                $approved++;
            }
        }

        return redirect()->back()->with('success', "{$approved} utbetalningar godkända.");
    }

    public function bulkMarkAsPaid(Request $request): RedirectResponse
    {
        $payoutIds = $request->input('payout_ids', []);
        
        if (empty($payoutIds)) {
            return redirect()->back()->with('error', 'Inga utbetalningar valda.');
        }

        $paid = 0;
        foreach ($payoutIds as $payoutId) {
            $payout = Payout::find($payoutId);
            if ($payout && $payout->status === 'approved' && $payout->markAsPaid()) {
                $paid++;
            }
        }

        return redirect()->back()->with('success', "{$paid} utbetalningar markerade som betalda.");
    }

    public function weeklyReports(Request $request): View
    {
        $query = WeeklyPayoutReport::with('company');

        // Filter by company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(50);
        $companies = Company::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.payouts.weekly-reports', compact('reports', 'companies'));
    }

    public function generateWeeklyReports(): RedirectResponse
    {
        try {
            $weekStart = Carbon::now()->subWeek()->startOfWeek();
            $weekEnd = Carbon::now()->subWeek()->endOfWeek();
            
            $reports = $this->payoutService->generateWeeklyReports($weekStart, $weekEnd);
            
            return redirect()->back()->with('success', 
                "Genererade {$reports->count()} veckorapporter för vecka " . $weekStart->week . ", {$weekStart->year}."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Kunde inte generera veckorapporter: ' . $e->getMessage());
        }
    }

    public function sendWeeklyReports(): RedirectResponse
    {
        try {
            $reports = WeeklyPayoutReport::where('status', 'pending')->get();
            $sentCount = $this->payoutService->sendWeeklyReports($reports->toArray());
            
            return redirect()->back()->with('success', 
                "Skickade {$sentCount} veckorapporter till företag."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Kunde inte skicka veckorapporter: ' . $e->getMessage());
        }
    }

    public function companyBalance(Company $company): View
    {
        $balance = $this->payoutService->getCompanyBalance($company->id);
        return view('admin.payouts.company-balance', compact('balance'));
    }

    public function approveCompanyPayouts(Company $company): RedirectResponse
    {
        try {
            $approved = $this->payoutService->approvePayouts($company->id, auth()->id());
            
            return redirect()->back()->with('success', 
                "Godkände {$approved->count()} utbetalningar för {$company->company_name}."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Kunde inte godkänna utbetalningar: ' . $e->getMessage());
        }
    }

    public function markCompanyPayoutsAsPaid(Company $company): RedirectResponse
    {
        try {
            $paid = $this->payoutService->markPayoutsAsPaid($company->id);
            
            return redirect()->back()->with('success', 
                "Markerade {$paid->count()} utbetalningar som betalda för {$company->company_name}."
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Kunde inte markera utbetalningar som betalda: ' . $e->getMessage());
        }
    }
}