<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['booking.user', 'company', 'service']);

        // Filter by company if specified
        if ($request->filled('company')) {
            $query->where('company_id', $request->company);
        }

        // Filter by review type (company, platform, or both)
        if ($request->filled('review_type')) {
            if ($request->review_type === 'company') {
                $query->whereNotNull('company_rating');
            } elseif ($request->review_type === 'platform') {
                $query->whereNotNull('bitra_rating');
            }
        }

        // Filter by company status
        if ($request->filled('company_status')) {
            $query->where('company_status', $request->company_status);
        }

        // Filter by platform status
        if ($request->filled('platform_status')) {
            $query->where('bitra_status', $request->platform_status);
        }

        // Filter by company rating
        if ($request->filled('company_rating')) {
            $query->where('company_rating', $request->company_rating);
        }

        // Filter by platform rating
        if ($request->filled('platform_rating')) {
            $query->where('bitra_rating', $request->platform_rating);
        }

        $reviews = $query->latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'total' => Review::count(),
            'company_reviews' => Review::whereNotNull('company_rating')->count(),
            'platform_reviews' => Review::whereNotNull('bitra_rating')->count(),
            'pending_company' => Review::where('company_status', 'pending')->count(),
            'pending_platform' => Review::where('bitra_status', 'pending')->count(),
            'approved_company' => Review::where('company_status', 'approved')->count(),
            'approved_platform' => Review::where('bitra_status', 'approved')->count(),
            'rejected_company' => Review::where('company_status', 'rejected')->count(),
            'rejected_platform' => Review::where('bitra_status', 'rejected')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function show(Review $review): View
    {
        $review->load(['booking.user', 'company', 'service']);
        
        return view('admin.reviews.show', compact('review'));
    }

    public function approveCompany(Review $review): RedirectResponse
    {
        $review->update(['company_status' => 'approved']);

        return redirect()->back()->with('success', 'Företagsrecensionen har godkänts.');
    }

    public function rejectCompany(Review $review): RedirectResponse
    {
        $review->update(['company_status' => 'rejected']);

        return redirect()->back()->with('success', 'Företagsrecensionen har avvisats.');
    }

    public function approveBitra(Review $review): RedirectResponse
    {
        $review->update(['bitra_status' => 'approved']);

        return redirect()->back()->with('success', 'Bitra-recensionen har godkänts.');
    }

    public function rejectBitra(Review $review): RedirectResponse
    {
        $review->update(['bitra_status' => 'rejected']);

        return redirect()->back()->with('success', 'Bitra-recensionen har avvisats.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Recensionen har tagits bort.');
    }
}