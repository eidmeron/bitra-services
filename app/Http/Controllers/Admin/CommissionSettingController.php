<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CommissionSettingController extends Controller
{
    /**
     * Display a listing of commission settings
     */
    public function index(): View
    {
        $commissions = CommissionSetting::with('company')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.commissions.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new commission setting
     */
    public function create(): View
    {
        // Get companies without commission settings
        $companies = Company::whereDoesntHave('commissionSetting')
            ->where('status', 'approved')
            ->orderBy('company_name')
            ->get();

        return view('admin.commissions.create', compact('companies'));
    }

    /**
     * Store a newly created commission setting
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id|unique:commission_settings,company_id',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_rate' => 'required_if:commission_type,percentage|nullable|numeric|min:0|max:100',
            'fixed_amount' => 'required_if:commission_type,fixed|nullable|numeric|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->has('is_active');

        CommissionSetting::create($validated);

        return redirect()
            ->route('admin.commissions.index')
            ->with('success', 'Provisionsinställning skapad framgångsrikt!');
    }

    /**
     * Show the form for editing a commission setting
     */
    public function edit(CommissionSetting $commission): View
    {
        $commission->load('company');
        
        return view('admin.commissions.edit', compact('commission'));
    }

    /**
     * Update the specified commission setting
     */
    public function update(Request $request, CommissionSetting $commission): RedirectResponse
    {
        $validated = $request->validate([
            'commission_type' => 'required|in:percentage,fixed',
            'commission_rate' => 'required_if:commission_type,percentage|nullable|numeric|min:0|max:100',
            'fixed_amount' => 'required_if:commission_type,fixed|nullable|numeric|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $commission->update($validated);

        return redirect()
            ->route('admin.commissions.index')
            ->with('success', 'Provisionsinställning uppdaterad framgångsrikt!');
    }

    /**
     * Remove the specified commission setting
     */
    public function destroy(CommissionSetting $commission): RedirectResponse
    {
        $commission->delete();

        return redirect()
            ->route('admin.commissions.index')
            ->with('success', 'Provisionsinställning raderad!');
    }

    /**
     * Bulk create commission settings for all companies
     */
    public function bulkCreate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $companies = Company::whereDoesntHave('commissionSetting')
            ->where('status', 'approved')
            ->get();

        foreach ($companies as $company) {
            CommissionSetting::create([
                'company_id' => $company->id,
                'commission_rate' => $validated['commission_rate'],
                'commission_type' => 'percentage',
                'is_active' => true,
            ]);
        }

        return redirect()
            ->route('admin.commissions.index')
            ->with('success', "Provisionsinställningar skapade för {$companies->count()} företag!");
    }
}
