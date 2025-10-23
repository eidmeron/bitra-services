<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ny Bokning Mottagen - #' . $this->booking->reference_number)
            ->greeting('Hej!')
            ->line('En ny bokning har skapats.')
            ->line('**Referensnummer:** ' . $this->booking->reference_number)
            ->line('**Tjänst:** ' . $this->booking->service_name)
            ->line('**Bokningsdatum:** ' . $this->booking->booking_date)
            ->line('**Område:** ' . $this->booking->service_area)
            ->action('Visa Bokning', route('admin.bookings.show', $this->booking))
            ->line('Tack för att du använder vår tjänst!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Ny Bokning',
            'message' => 'Ny bokning #' . $this->booking->reference_number . ' från ' . $this->booking->full_name,
            'icon' => '📅',
            'url' => route('admin.bookings.show', $this->booking, false),
            'booking_id' => $this->booking->id,
        ];
    }
}

