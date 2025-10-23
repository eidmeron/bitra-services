<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtraFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ExtraFeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = ExtraFee::with(['booking', 'company', 'createdBy', 'reviewedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $extraFees = $query->latest()->paginate(20);
        $companies = \App\Models\Company::where('status', 'active')->get();

        return view('admin.extra-fees.index', compact('extraFees', 'companies'));
    }

    public function show(ExtraFee $extraFee): View
    {
        $extraFee->load(['booking', 'company', 'createdBy', 'reviewedBy']);
        
        return view('admin.extra-fees.show', compact('extraFee'));
    }

    public function approve(ExtraFee $extraFee): RedirectResponse
    {
        $extraFee->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        // Update booking total with extra fee
        $booking = $extraFee->booking;
        $booking->increment('final_price', $extraFee->amount);

        return redirect()->route('admin.extra-fees.index')
            ->with('success', 'Extra avgift godkänd');
    }

    public function reject(Request $request, ExtraFee $extraFee): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $extraFee->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.extra-fees.index')
            ->with('success', 'Extra avgift avvisad');
    }
}