<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Services\BookingWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private BookingWorkflowService $bookingWorkflow
    ) {
    }

    public function index(Request $request): View
    {
        $user = auth()->user();

        // Include both registered user bookings and guest bookings by email
        $bookings = Booking::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereNull('user_id')
                          ->where('customer_email', $user->email);
                    });
            })
            ->with(['service', 'city', 'company'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('user.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $user = auth()->user();

        // Ensure booking belongs to this user (either by user_id or email)
        if ($booking->user_id !== $user->id && 
            !($booking->user_id === null && $booking->customer_email === $user->email)) {
            abort(403);
        }

        $booking->load(['service', 'city', 'company', 'form', 'review']);

        return view('user.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        $user = auth()->user();

        if ($booking->user_id !== $user->id || in_array($booking->status, ['completed', 'cancelled'])) {
            abort(403);
        }

        $this->bookingWorkflow->cancelBooking($booking);

        return redirect()->route('user.bookings.show', $booking)
            ->with('success', 'Bokning avbruten.');
    }

    public function review(Request $request, Booking $booking): RedirectResponse
    {
        $user = auth()->user();

        if ($booking->user_id !== $user->id || !$booking->canBeReviewed()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $review = Review::create([
            'booking_id' => $booking->id,
            'company_id' => $booking->company_id,
            'user_id' => $user->id,
            'service_id' => $booking->service_id,
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'] ?? null,
            'status' => 'pending',
        ]);

        // Send notification to user
        $user->notify(new \App\Notifications\ReviewSubmittedNotification($review));

        // Send notification to company
        $booking->company->user->notify(new \App\Notifications\CompanyReviewReceivedNotification($review));

        return redirect()->route('user.bookings.show', $booking)
            ->with('success', 'Tack för din recension!');
    }
}
