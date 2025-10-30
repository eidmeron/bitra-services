<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class BitraReviewReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Review $review
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ny Bitra-recension mottagen')
            ->greeting('Hej!')
            ->line('En ny recension för Bitra-plattformen har mottagits.')
            ->line("Bokning: {$this->review->booking->booking_number}")
            ->line("Betyg: {$this->review->bitra_rating}/5")
            ->line("Recension: {$this->review->bitra_review_text}")
            ->action('Granska recension', route('admin.reviews.show', $this->review))
            ->line('Tack för att du använder vår plattform!');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'bitra_review_received',
            'review_id' => $this->review->id,
            'booking_number' => $this->review->booking->booking_number,
            'rating' => $this->review->bitra_rating,
            'message' => 'Ny Bitra-recension mottagen',
        ];
    }
}