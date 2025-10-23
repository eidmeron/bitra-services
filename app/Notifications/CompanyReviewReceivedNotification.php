<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class CompanyReviewReceivedNotification extends Notification implements ShouldQueue
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
        $customerName = $this->review->booking->user->name ?? 'En kund';
        
        return (new MailMessage)
            ->subject('Ny recension mottagen!')
            ->greeting('Hej ' . $notifiable->company_name . '!')
            ->line('Du har fått en ny recension från ' . $customerName . '.')
            ->line('Betyg: ' . $this->review->rating . '/5 stjärnor')
            ->when($this->review->review_text, function ($message) {
                return $message->line('Kommentar: "' . $this->review->review_text . '"');
            })
            ->line('Recensionen kommer att granskas innan den publiceras.')
            ->action('Visa recensionen', url('/company/dashboard'))
            ->line('Tack för att du använder vår plattform!')
            ->salutation('Med vänliga hälsningar,<br>' . config('app.name'));
    }

    public function toDatabase($notifiable): array
    {
        $customerName = $this->review->booking->user->name ?? 'En kund';
        
        return [
            'type' => 'company_review_received',
            'title' => 'Ny recension mottagen',
            'message' => 'Du har fått en ny recension från ' . $customerName . ' (' . $this->review->rating . '/5 stjärnor)',
            'review_id' => $this->review->id,
            'company_id' => $this->review->company_id,
            'booking_id' => $this->review->booking_id,
            'rating' => $this->review->rating,
        ];
    }
}