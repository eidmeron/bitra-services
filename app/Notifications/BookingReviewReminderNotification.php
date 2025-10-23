<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReviewReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $reminderType = 'first' // 'first' or 'second'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->reminderType === 'first' 
            ? '⭐ Hur gick det med din bokning?' 
            : '⭐ Påminnelse: Betygsätt din tjänst';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hej!')
            ->line('Vi hoppas att din bokning gick bra!')
            ->line('**Tjänst:** ' . $this->booking->service->name)
            ->line('**Företag:** ' . ($this->booking->company->company_name ?? 'N/A'))
            ->line('Hjälp andra kunder genom att dela din upplevelse.')
            ->action('Lämna Recensi on', route('user.bookings.show', $this->booking))
            ->line('Tack för att du använder ' . config('app.name') . '!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_review_reminder',
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number,
            'service_name' => $this->booking->service->name,
            'company_name' => $this->booking->company->company_name ?? null,
            'reminder_type' => $this->reminderType,
            'url' => route('user.bookings.show', $this->booking, false),
        ];
    }
}
