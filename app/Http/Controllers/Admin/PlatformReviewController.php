<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformReview;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class PlatformReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = PlatformReview::with('user');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('comment', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('overall_rating', '>=', $request->rating);
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'highest_rating':
                $query->orderBy('overall_rating', 'desc');
                break;
            case 'lowest_rating':
                $query->orderBy('overall_rating', 'asc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $reviews = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => PlatformReview::count(),
            'pending' => PlatformReview::where('status', 'pending')->count(),
            'approved' => PlatformReview::where('status', 'approved')->count(),
            'rejected' => PlatformReview::where('status', 'rejected')->count(),
            'featured' => PlatformReview::where('is_featured', true)->count(),
            'high_rated' => PlatformReview::where('overall_rating', '>=', 4)->count(),
            'average_rating' => round((float) (PlatformReview::avg('overall_rating') ?? 0), 1),
            'today' => PlatformReview::whereDate('created_at', today())->count(),
            'this_week' => PlatformReview::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => PlatformReview::whereMonth('created_at', now()->month)->count(),
        ];

        // Get recent company reviews
        $companyReviews = \App\Models\Review::with(['booking.user', 'company'])
            ->latest()
            ->limit(10)
            ->get();

        // Company review stats
        $companyStats = [
            'total' => \App\Models\Review::count(),
            'pending' => \App\Models\Review::where('status', 'pending')->count(),
            'approved' => \App\Models\Review::where('status', 'approved')->count(),
            'rejected' => \App\Models\Review::where('status', 'rejected')->count(),
            'today' => \App\Models\Review::whereDate('created_at', today())->count(),
            'this_week' => \App\Models\Review::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('admin.platform-reviews.index', compact('reviews', 'stats', 'companyReviews', 'companyStats'));
    }

    public function show(PlatformReview $platformReview): View
    {
        $platformReview->load('user');
        return view('admin.platform-reviews.show', compact('platformReview'));
    }

    public function approve(PlatformReview $platformReview): RedirectResponse
    {
        $platformReview->approve();
        
        return redirect()->back()->with('success', 'Recensionen har godkänts och publicerats.');
    }

    public function reject(PlatformReview $platformReview): RedirectResponse
    {
        $platformReview->reject();
        
        return redirect()->back()->with('success', 'Recensionen har avvisats.');
    }

    public function toggleFeatured(PlatformReview $platformReview): RedirectResponse
    {
        $platformReview->toggleFeatured();
        
        $message = $platformReview->is_featured 
            ? 'Recensionen har markerats som utvald.' 
            : 'Recensionen är inte längre utvald.';
        
        return redirect()->back()->with('success', $message);
    }

    public function destroy(PlatformReview $platformReview): RedirectResponse
    {
        $platformReview->delete();
        
        return redirect()->route('admin.platform-reviews.index')
            ->with('success', 'Recensionen har raderats.');
    }
}

