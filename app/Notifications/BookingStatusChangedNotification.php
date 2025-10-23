<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusText = match($this->newStatus) {
            'confirmed' => 'bekräftad',
            'in_progress' => 'pågår',
            'completed' => 'slutförd',
            'cancelled' => 'avbruten',
            default => $this->newStatus
        };

        return (new MailMessage)
            ->subject('Bokningsstatus Uppdaterad - #' . $this->booking->reference_number)
            ->greeting('Hej ' . $this->booking->full_name . '!')
            ->line('Status för din bokning har uppdaterats.')
            ->line('**Referensnummer:** ' . $this->booking->reference_number)
            ->line('**Tjänst:** ' . $this->booking->service_name)
            ->line('**Ny Status:** ' . ucfirst($statusText))
            ->action('Visa Bokning', url('/booking-check?reference=' . $this->booking->reference_number . '&email=' . $this->booking->email))
            ->line('Tack för att du använder vår tjänst!');
    }

    public function toArray(object $notifiable): array
    {
        $statusText = match($this->newStatus) {
            'confirmed' => 'bekräftad',
            'in_progress' => 'pågår',
            'completed' => 'slutförd',
            'cancelled' => 'avbruten',
            default => $this->newStatus
        };

        return [
            'title' => 'Bokningsstatus Uppdaterad',
            'message' => 'Bokning #' . $this->booking->reference_number . ' är nu ' . $statusText,
            'icon' => '🔄',
            'url' => '/booking-check?reference=' . $this->booking->reference_number . '&email=' . $this->booking->email,
            'booking_id' => $this->booking->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}

