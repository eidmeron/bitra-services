<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class BookingCheckController extends Controller
{
    /**
     * Show the booking check form
     */
    public function showForm(): View
    {
        return view('public.check-booking');
    }

    /**
     * Check booking by reference number and email
     */
    public function check(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'booking_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ], [
            'booking_number.required' => 'Bokningsnummer är obligatoriskt.',
            'email.required' => 'E-postadress är obligatoriskt.',
            'email.email' => 'E-postadressen måste vara giltig.',
        ]);

        $booking = Booking::where('booking_number', strtoupper($validated['booking_number']))
            ->where('customer_email', $validated['email'])
            ->with(['service', 'city', 'company', 'form'])
            ->first();

        if (!$booking) {
            return back()
                ->withErrors(['error' => 'Ingen bokning hittades med det bokningsnumret och e-postadressen. Kontrollera att uppgifterna är korrekta.'])
                ->withInput();
        }

        return view('public.booking-details', compact('booking'));
    }
}

