<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class BookingSuccessController extends Controller
{
    /**
     * Show the booking success page
     */
    public function show(string $bookingNumber): View
    {
        $booking = Booking::where('booking_number', $bookingNumber)
            ->with(['service', 'city', 'company', 'form'])
            ->firstOrFail();

        // Check if user with this email already exists
        $emailExists = User::where('email', $booking->customer_email)->exists();

        return view('public.success', compact('booking', 'emailExists'));
    }

    /**
     * Create account from booking information
     */
    public function createAccount(Request $request, string $bookingNumber): RedirectResponse
    {
        $booking = Booking::where('booking_number', $bookingNumber)->firstOrFail();

        // Validate password
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Lösenord är obligatoriskt.',
            'password.min' => 'Lösenordet måste vara minst 8 tecken.',
            'password.confirmed' => 'Lösenorden matchar inte.',
        ]);

        // Check if user already exists
        $existingUser = User::where('email', $booking->customer_email)->first();
        if ($existingUser) {
            return back()->withErrors(['email' => 'Ett konto med denna e-postadress finns redan.']);
        }

        // Create new user
        $user = User::create([
            'type' => 'user',
            'name' => $booking->customer_name,
            'email' => $booking->customer_email,
            'phone' => $booking->customer_phone,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        // Link booking to the new user
        $booking->update(['user_id' => $user->id]);

        // Log the user in
        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', '🎉 Ditt konto har skapats! Välkommen till ' . site_name() . '!');
    }
}
