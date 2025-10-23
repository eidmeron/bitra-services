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

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review): View
    {
        $review->load(['booking.user', 'company', 'service']);
        
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Recensionen har godkänts.');
    }

    public function reject(Review $review): RedirectResponse
    {
        $review->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Recensionen har avvisats.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Recensionen har tagits bort.');
    }
}