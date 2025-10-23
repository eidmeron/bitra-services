<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAssignedNotification extends Notification implements ShouldQueue
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
            ->subject('Ny Bokning Tilldelad - #' . $this->booking->reference_number)
            ->greeting('Hej!')
            ->line('En ny bokning har tilldelats ditt företag.')
            ->line('**Referensnummer:** ' . $this->booking->reference_number)
            ->line('**Tjänst:** ' . $this->booking->service_name)
            ->line('**Bokningsdatum:** ' . $this->booking->booking_date)
            ->line('**Kund:** ' . $this->booking->full_name)
            ->action('Visa Bokning', route('company.dashboard'))
            ->line('Vänligen bekräfta bokningen så snart som möjligt.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Ny Bokning Tilldelad',
            'message' => 'Bokning #' . $this->booking->reference_number . ' har tilldelats ditt företag',
            'icon' => '📋',
            'url' => route('company.dashboard', [], false),
            'booking_id' => $this->booking->id,
        ];
    }
}

