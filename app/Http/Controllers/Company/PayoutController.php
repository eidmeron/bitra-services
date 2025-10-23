<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\WeeklyPayoutReport;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PayoutController extends Controller
{
    public function __construct(
        private readonly PayoutService $payoutService
    ) {}

    public function index(Request $request): View
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            abort(404, 'Inget företag hittades.');
        }

        $query = Payout::where('company_id', $company->id)
            ->with(['booking.service', 'booking.city']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $payouts = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get balance information
        $balance = $this->payoutService->getCompanyBalance($company->id);

        return view('company.payouts.index', compact('payouts', 'balance'));
    }

    public function show(Payout $payout): View
    {
        $company = auth()->user()->company;
        
        if (!$company || $payout->company_id !== $company->id) {
            abort(404, 'Utbetalning hittades inte.');
        }

        $payout->load(['booking.service', 'booking.city', 'booking.user']);
        return view('company.payouts.show', compact('payout'));
    }

    public function weeklyReports(Request $request): View
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            abort(404, 'Inget företag hittades.');
        }

        $query = WeeklyPayoutReport::where('company_id', $company->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('company.payouts.weekly-reports', compact('reports'));
    }

    public function showWeeklyReport(WeeklyPayoutReport $report): View
    {
        $company = auth()->user()->company;
        
        if (!$company || $report->company_id !== $company->id) {
            abort(404, 'Veckorapport hittades inte.');
        }

        $report->load('company');
        
        // Get detailed breakdown for this week
        $payouts = Payout::where('company_id', $company->id)
            ->whereBetween('period_start', [$report->period_start, $report->period_end])
            ->with(['booking.service', 'booking.city'])
            ->get();

        return view('company.payouts.weekly-report-detail', compact('report', 'payouts'));
    }

    public function balance(): View
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            abort(404, 'Inget företag hittades.');
        }

        $balance = $this->payoutService->getCompanyBalance($company->id);
        $history = $this->payoutService->getCompanyPayoutHistory($company->id);

        return view('company.payouts.balance', compact('balance', 'history'));
    }

    public function taxInfo(): View
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            abort(404, 'Inget företag hittades.');
        }

        $balance = $this->payoutService->getCompanyBalance($company->id);
        
        // Calculate Swedish tax implications
        $taxInfo = $this->payoutService->calculateSwedishTaxes(
            $balance['current_balance'],
            'enskild_firma' // Default, could be made configurable
        );

        return view('company.payouts.tax-info', compact('balance', 'taxInfo'));
    }
}