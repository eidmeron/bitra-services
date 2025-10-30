<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DualReviewController extends Controller
{
    /**
     * Show dual review form for completed booking
     */
    public function show(Booking $booking): View
    {
        // Verify booking is completed
        if ($booking->status !== 'completed') {
            abort(403, 'Du kan endast recensera slutförda bokningar.');
        }

        // Verify company is assigned
        if (!$booking->company_id) {
            abort(403, 'Ingen företag tilldelad för denna bokning.');
        }

        // Check if review already exists
        $review = $booking->review;
        if (!$review) {
            // Create a new review record for dual reviews
            $review = Review::create([
                'booking_id' => $booking->id,
                'company_id' => $booking->company_id,
                'user_id' => $booking->user_id,
                'service_id' => $booking->service_id,
                'review_type' => 'company', // Default to company review
                'company_status' => 'pending',
                'bitra_status' => 'pending',
            ]);
        }

        return view('public.dual-review', compact('booking', 'review'));
    }

    /**
     * Submit dual reviews (Bitra + Company)
     */
    public function submit(Request $request, Booking $booking): RedirectResponse
    {
        // Verify booking is completed
        if ($booking->status !== 'completed') {
            return back()->with('error', 'Du kan endast recensera slutförda bokningar.');
        }

        // Verify company is assigned
        if (!$booking->company_id) {
            return back()->with('error', 'Ingen företag tilldelad för denna bokning.');
        }

        $validated = $request->validate([
            // Company review
            'company_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'company_review_text' => ['nullable', 'string', 'max:1000'],
            
            // Bitra review
            'bitra_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'bitra_review_text' => ['nullable', 'string', 'max:1000'],
        ], [
            'company_rating.required' => 'Företagsbetyg är obligatoriskt.',
            'company_rating.integer' => 'Företagsbetyg måste vara ett nummer.',
            'company_rating.min' => 'Företagsbetyg måste vara minst 1.',
            'company_rating.max' => 'Företagsbetyg kan inte vara mer än 5.',
            'company_review_text.max' => 'Företagsrecensionen får inte vara längre än 1000 tecken.',
            
            'bitra_rating.required' => 'Bitra-betyg är obligatoriskt.',
            'bitra_rating.integer' => 'Bitra-betyg måste vara ett nummer.',
            'bitra_rating.min' => 'Bitra-betyg måste vara minst 1.',
            'bitra_rating.max' => 'Bitra-betyg kan inte vara mer än 5.',
            'bitra_review_text.max' => 'Bitra-recensionen får inte vara längre än 1000 tecken.',
        ]);

        // Get or create review
        $review = $booking->review;
        if (!$review) {
            $review = Review::create([
                'booking_id' => $booking->id,
                'company_id' => $booking->company_id,
                'user_id' => $booking->user_id,
                'service_id' => $booking->service_id,
                'review_type' => 'company',
                'company_status' => 'pending',
                'bitra_status' => 'pending',
            ]);
        }

        // Update with dual reviews
        $review->update([
            'company_rating' => $validated['company_rating'],
            'company_review_text' => $validated['company_review_text'],
            'bitra_rating' => $validated['bitra_rating'],
            'bitra_review_text' => $validated['bitra_review_text'],
            'company_status' => 'pending',
            'bitra_status' => 'pending',
        ]);

        // Send notifications
        if ($booking->company && $booking->company->user) {
            $booking->company->user->notify(new \App\Notifications\CompanyReviewReceivedNotification($review));
        }

        // Send notification to admin for Bitra review
        $adminUsers = \App\Models\User::where('is_admin', true)->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new \App\Notifications\BitraReviewReceivedNotification($review));
        }

        return redirect()->route('public.booking.review.success', $booking)
            ->with('success', '✅ Tack för dina recensioner! De kommer att granskas och publiceras inom kort.');
    }

    /**
     * Show review success page
     */
    public function success(Booking $booking): View
    {
        return view('public.review-success', compact('booking'));
    }
}