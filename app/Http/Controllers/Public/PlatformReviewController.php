<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PlatformReview;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class PlatformReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = PlatformReview::approved();

        // Sorting
        $sort = $request->get('sort', 'best');
        switch ($sort) {
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'best':
            default:
                $query->orderBy('overall_rating', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('overall_rating', '>=', (int) $request->rating);
        }

        $reviews = $query->with('user')->paginate(12);

        // Calculate stats
        $stats = [
            'total' => PlatformReview::approved()->count(),
            'average_rating' => round((float) (PlatformReview::approved()->avg('overall_rating') ?? 0), 1),
            'five_star' => PlatformReview::approved()->where('overall_rating', 5)->count(),
            'four_star' => PlatformReview::approved()->where('overall_rating', 4)->count(),
            'service_quality_avg' => round((float) (PlatformReview::approved()->avg('service_quality_rating') ?? 0), 1),
            'price_avg' => round((float) (PlatformReview::approved()->avg('price_rating') ?? 0), 1),
            'speed_avg' => round((float) (PlatformReview::approved()->avg('speed_rating') ?? 0), 1),
        ];

        return view('public.reviews.index', compact('reviews', 'stats', 'sort'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'overall_rating' => 'required|integer|min:1|max:5',
            'service_quality_rating' => 'nullable|integer|min:1|max:5',
            'price_rating' => 'nullable|integer|min:1|max:5',
            'speed_rating' => 'nullable|integer|min:1|max:5',
            'staff_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ], [
            'name.required' => 'Namn är obligatoriskt.',
            'overall_rating.required' => 'Totalbetyg är obligatoriskt.',
            'overall_rating.min' => 'Betyg måste vara minst 1.',
            'overall_rating.max' => 'Betyg kan vara högst 5.',
            'comment.required' => 'Kommentar är obligatorisk.',
            'comment.min' => 'Kommentaren måste vara minst 10 tecken.',
        ]);

        // Add user_id if authenticated, otherwise set name to 'Anonym'
        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
            $validated['name'] = auth()->user()->name;
            $validated['email'] = auth()->user()->email ?? $validated['email'];
        } else {
            // For guests, always use 'Anonym' as the name
            $validated['name'] = 'Anonym';
        }

        // Status is pending by default for moderation
        $validated['status'] = 'pending';

        PlatformReview::create($validated);

        return redirect()->route('reviews.index')
            ->with('success', '✅ Tack för din recension! Den kommer att granskas och publiceras inom kort.');
    }
}
