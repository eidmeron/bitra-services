<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Booking;
use App\Notifications\BookingReviewReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBookingReviewReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $reminderType = 'first'
    ) {}

    public function handle(): void
    {
        // Check if booking is still completed and not reviewed
        if ($this->booking->status !== 'completed') {
            return;
        }

        // Check if company has been reviewed
        $hasReview = $this->booking->review()->exists();
        if ($hasReview) {
            return;
        }

        // Send notification to user
        if ($this->booking->user) {
            $this->booking->user->notify(
                new BookingReviewReminderNotification($this->booking, $this->reminderType)
            );
        }
    }
}
