<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

final class BookingCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Tjänst slutförd - Faktura kommer snart')
            ->greeting('Hej ' . $this->booking->customer_name . '!')
            ->line('Din tjänst har slutförts framgångsrikt!')
            ->line('**Tjänst:** ' . $this->booking->service->name)
            ->line('**Stad:** ' . $this->booking->city->name)
            ->line('**Totalt pris:** ' . number_format($this->booking->total_with_tax ?? $this->booking->final_price, 0, ',', ' ') . ' kr (inkl. moms)')
            ->line('')
            ->line('Vår partner (' . $this->booking->company->company_name . ') kommer att skicka din faktura inom kort.')
            ->line('Om du har några frågor gällande betalningen eller behöver ytterligare information, är du alltid välkommen att kontakta oss.')
            ->line('')
            ->line('Vi skulle uppskatta om du kunde lämna en recension om din upplevelse med både företaget och vår plattform.')
            ->action('Lämna recension', route('public.booking.review.show', $this->booking))
            ->line('')
            ->line('Tack för att du valde våra tjänster!')
            ->salutation('Mvh, ' . site_name());

        // Attach PDF invoice
        $pdf = Pdf::loadView('pdf.customer-invoice', ['booking' => $this->booking]);
        $message->attachData($pdf->output(), "faktura-{$this->booking->booking_number}.pdf", [
            'mime' => 'application/pdf',
        ]);

        return $message;
    }
}

