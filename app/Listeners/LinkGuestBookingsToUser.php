<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Booking;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LinkGuestBookingsToUser
{
    /**
     * Handle the event - Link guest bookings when user logs in or registers
     */
    public function handle(Login|Registered $event): void
    {
        $user = $event->user;

        // Only process for regular users, not companies or admins
        if ($user->type !== 'user') {
            return;
        }

        try {
            // Find all guest bookings with this email
            $guestBookings = Booking::whereNull('user_id')
                ->where('customer_email', $user->email)
                ->get();

            // Link bookings to user account if any exist
            if ($guestBookings->isNotEmpty()) {
                DB::transaction(function () use ($guestBookings, $user) {
                    foreach ($guestBookings as $booking) {
                        $booking->update(['user_id' => $user->id]);
                        
                        Log::info("Guest booking {$booking->booking_number} linked to user {$user->id}");
                    }
                });

                Log::info("Linked {$guestBookings->count()} guest bookings to user {$user->id} ({$user->email})");
            }
            
            // Award welcome bonus for new registrations
            if ($event instanceof Registered && setting('loyalty_points_enabled', true)) {
                $this->awardWelcomeBonus($user);
            }
            
        } catch (\Exception $e) {
            Log::error("Failed to link guest bookings to user {$user->id}: " . $e->getMessage());
        }
    }

    /**
     * Award welcome bonus points to new user
     */
    private function awardWelcomeBonus($user): void
    {
        try {
            $bonusPoints = (int) setting('new_user_loyalty_bonus', 100);
            
            if ($bonusPoints > 0) {
                \App\Models\LoyaltyPoint::addPoints(
                    $user->id,
                    $bonusPoints,
                    'Välkomstbonus - Tack för att du registrerade dig!',
                    null,
                    now()->addDays((int) setting('loyalty_points_expiry_days', 365))
                );
                
                Log::info("Awarded {$bonusPoints} welcome bonus points to user {$user->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to award welcome bonus to user {$user->id}: " . $e->getMessage());
        }
    }
}
