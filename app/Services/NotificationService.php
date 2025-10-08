<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function notifyBookingCreated(Booking $booking): void
    {
        $admins = User::where('type', 'admin')->get();

        foreach ($admins as $admin) {
            Log::info("Notifying admin {$admin->email} about new booking {$booking->booking_number}");
            // TODO: Send email notification
        }
    }

    public function notifyBookingAssigned(Booking $booking): void
    {
        if ($booking->company && $booking->company->user) {
            Log::info("Notifying company {$booking->company->user->email} about assigned booking {$booking->booking_number}");
            // TODO: Send email notification
        }

        if ($booking->user) {
            Log::info("Notifying user {$booking->user->email} about booking assignment");
            // TODO: Send email notification
        }
    }

    public function notifyBookingCompleted(Booking $booking): void
    {
        if ($booking->user) {
            Log::info("Notifying user {$booking->user->email} that booking {$booking->booking_number} is completed");
            // TODO: Send email notification with review request
        }
    }

    public function notifyBookingCancelled(Booking $booking): void
    {
        if ($booking->company && $booking->company->user) {
            Log::info("Notifying company {$booking->company->user->email} about cancelled booking {$booking->booking_number}");
            // TODO: Send email notification
        }
    }

    public function notifyNewReview(Booking $booking): void
    {
        $admins = User::where('type', 'admin')->get();

        foreach ($admins as $admin) {
            Log::info("Notifying admin {$admin->email} about new review for booking {$booking->booking_number}");
            // TODO: Send email notification
        }
    }
}

