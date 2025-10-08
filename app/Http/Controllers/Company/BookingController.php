<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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
        $company = auth()->user()->company;

        $bookings = Booking::where('company_id', $company->id)
            ->with(['service', 'city', 'user'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('company.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $company = auth()->user()->company;

        // Ensure booking belongs to this company
        if ($booking->company_id !== $company->id) {
            abort(403);
        }

        $booking->load(['service', 'city', 'user', 'form', 'review']);

        return view('company.bookings.show', compact('booking'));
    }

    public function accept(Booking $booking): RedirectResponse
    {
        $company = auth()->user()->company;

        if ($booking->company_id !== $company->id || $booking->status !== 'assigned') {
            abort(403);
        }

        $this->bookingWorkflow->acceptBooking($booking);

        return redirect()->route('company.bookings.show', $booking)
            ->with('success', 'Bokning accepterad.');
    }

    public function reject(Request $request, Booking $booking): RedirectResponse
    {
        $company = auth()->user()->company;

        if ($booking->company_id !== $company->id || $booking->status !== 'assigned') {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->bookingWorkflow->rejectBooking($booking, $request->reason);

        return redirect()->route('company.bookings.index')
            ->with('success', 'Bokning avvisad.');
    }

    public function complete(Booking $booking): RedirectResponse
    {
        $company = auth()->user()->company;

        if ($booking->company_id !== $company->id || $booking->status !== 'in_progress') {
            abort(403);
        }

        $this->bookingWorkflow->completeBooking($booking);

        return redirect()->route('company.bookings.show', $booking)
            ->with('success', 'Bokning slutförd.');
    }
}
