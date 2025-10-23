<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GuestReviewController extends Controller
{
    /**
     * Submit a review for a completed booking (guest)
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

        // Check if review already exists
        if ($booking->review) {
            return back()->with('error', 'Du har redan lämnat en recension för denna bokning.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Betyg är obligatoriskt.',
            'rating.integer' => 'Betyg måste vara ett nummer.',
            'rating.min' => 'Betyg måste vara minst 1.',
            'rating.max' => 'Betyg kan inte vara mer än 5.',
            'review_text.max' => 'Recensionen får inte vara längre än 1000 tecken.',
        ]);

        $review = Review::create([
            'booking_id' => $booking->id,
            'company_id' => $booking->company_id,
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'status' => 'pending', // Requires admin approval
        ]);

        // Send notification to company
        $booking->company->user->notify(new \App\Notifications\CompanyReviewReceivedNotification($review));

        return back()->with('success', '✅ Tack för din recension! Den kommer att granskas och publiceras inom kort.');
    }

    /**
     * Show the review form for a booking (using public token)
     */
    public function show(string $publicToken): View
    {
        $booking = Booking::where('public_token', $publicToken)
            ->with(['service', 'city', 'company'])
            ->firstOrFail();

        // Verify booking is completed
        if ($booking->status !== 'completed') {
            abort(403, 'Du kan endast recensera slutförda bokningar.');
        }

        // Verify company is assigned
        if (!$booking->company_id) {
            abort(403, 'Ingen företag tilldelad för denna bokning.');
        }

        // Check if review already exists
        if ($booking->review) {
            abort(403, 'Du har redan lämnat en recension för denna bokning.');
        }

        return view('public.review', compact('booking'));
    }

    /**
     * Submit a review for a completed booking (using public token)
     */
    public function submitByToken(Request $request, string $publicToken): RedirectResponse
    {
        $booking = Booking::where('public_token', $publicToken)->firstOrFail();

        // Verify booking is completed
        if ($booking->status !== 'completed') {
            return back()->with('error', 'Du kan endast recensera slutförda bokningar.');
        }

        // Verify company is assigned
        if (!$booking->company_id) {
            return back()->with('error', 'Ingen företag tilldelad för denna bokning.');
        }

        // Check if review already exists
        if ($booking->review) {
            return back()->with('error', 'Du har redan lämnat en recension för denna bokning.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review_text' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Betyg är obligatoriskt.',
            'rating.integer' => 'Betyg måste vara ett nummer.',
            'rating.min' => 'Betyg måste vara minst 1.',
            'rating.max' => 'Betyg kan inte vara mer än 5.',
            'review_text.max' => 'Recensionen får inte vara längre än 1000 tecken.',
        ]);

        $review = Review::create([
            'booking_id' => $booking->id,
            'company_id' => $booking->company_id,
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'status' => 'pending', // Requires admin approval
        ]);

        // Send notification to company
        $booking->company->user->notify(new \App\Notifications\CompanyReviewReceivedNotification($review));

        return redirect()->route('public.booking.review.show', $publicToken)
            ->with('success', '✅ Tack för din recension! Den kommer att granskas och publiceras inom kort.');
    }
}
