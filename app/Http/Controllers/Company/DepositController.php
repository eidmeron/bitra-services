<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\WeeklyCommissionReport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepositController extends Controller
{
    /**
     * Display a listing of deposits for the company
     */
    public function index(Request $request): View
    {
        $company = auth()->user()->company;
        
        $query = $company->deposits()->with(['booking.service', 'booking.city']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
            'total_deposits' => $company->deposits()->count(),
            'pending_deposits' => $company->deposits()->where('status', 'pending')->count(),
            'sent_deposits' => $company->deposits()->where('status', 'sent')->count(),
            'paid_deposits' => $company->deposits()->where('status', 'paid')->count(),
            'overdue_deposits' => $company->deposits()->where('status', 'sent')
                ->where('due_date', '<', now()->toDateString())
                ->count(),
            'total_amount' => $company->deposits()->sum('deposit_amount'),
            'pending_amount' => $company->deposits()->where('status', 'pending')->sum('deposit_amount'),
            'overdue_amount' => $company->deposits()->where('status', 'sent')
                ->where('due_date', '<', now()->toDateString())
                ->sum('deposit_amount'),
        ];

        return view('company.deposits.index', compact('deposits', 'statistics'));
    }

    /**
     * Display the specified deposit
     */
    public function show(Deposit $deposit): View
    {
        // Ensure the deposit belongs to the company
        if ($deposit->company_id !== auth()->user()->company->id) {
            abort(403, 'Du har inte behörighet att visa denna deposit.');
        }

        $deposit->load(['booking.service', 'booking.city', 'sentBy']);
        
        return view('company.deposits.show', compact('deposit'));
    }

    /**
     * Display weekly commission reports
     */
    public function weeklyReports(Request $request): View
    {
        $company = auth()->user()->company;
        
        // Check if company has weekly commission reports relationship
        if (!$company || !method_exists($company, 'weeklyCommissionReports')) {
            return view('company.deposits.weekly-reports', [
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
        
        $query = $company->weeklyCommissionReports();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $reports = $query->latest('created_at')->paginate(20);

        $statistics = [
            'total_reports' => $company->weeklyCommissionReports()->count(),
            'pending_reports' => $company->weeklyCommissionReports()->where('status', 'pending')->count(),
            'sent_reports' => $company->weeklyCommissionReports()->where('status', 'sent')->count(),
            'paid_reports' => $company->weeklyCommissionReports()->where('status', 'paid')->count(),
            'total_commission' => $company->weeklyCommissionReports()->sum('total_commission'),
            'total_deposits' => $company->weeklyCommissionReports()->sum('net_commission_due'),
        ];

        return view('company.deposits.weekly-reports', compact('reports', 'statistics'));
    }

    /**
     * Display the specified weekly commission report
     */
    public function showWeeklyReport(WeeklyCommissionReport $report): View
    {
        // Ensure the report belongs to the company
        if ($report->company_id !== auth()->user()->company->id) {
            abort(403, 'Du har inte behörighet att visa denna rapport.');
        }

        $report->load('company');
        
        return view('company.deposits.show-weekly-report', compact('report'));
    }
}