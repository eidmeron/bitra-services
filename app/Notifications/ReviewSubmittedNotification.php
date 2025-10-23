<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ReviewSubmittedNotification extends Notification implements ShouldQueue
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
            ->subject('Tack för din recension!')
            ->greeting('Hej ' . ($notifiable->name ?? 'där') . '!')
            ->line('Tack så mycket för att du tog dig tid att lämna en recension för ' . $this->review->company->company_name . '.')
            ->line('Din feedback är mycket värdefull för oss och hjälper oss att förbättra våra tjänster.')
            ->line('Recensionen kommer att granskas och publiceras inom kort.')
            ->action('Visa din recension', url('/user/bookings/' . $this->review->booking_id))
            ->line('Tack igen för att du valde våra tjänster!')
            ->salutation('Med vänliga hälsningar,<br>' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'review_submitted',
            'title' => 'Recension mottagen',
            'message' => 'Tack för din recension för ' . $this->review->company->company_name,
            'review_id' => $this->review->id,
            'company_id' => $this->review->company_id,
            'booking_id' => $this->review->booking_id,
        ];
    }
}