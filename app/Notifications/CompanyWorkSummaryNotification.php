<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;

final class CompanyWorkSummaryNotification extends Notification implements ShouldQueue
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
        $commissionSetting = $this->booking->company->commissionSetting;
        $commissionAmount = 0;
        if ($commissionSetting && $commissionSetting->is_active) {
            $bookingAmount = (float) $this->booking->final_price;
            $commissionAmount = $commissionSetting->calculateCommission($bookingAmount);
        }

        $netEarnings = ($this->booking->subtotal ?? ($this->booking->final_price - ($this->booking->tax_amount ?? 0))) - $commissionAmount;

        $message = (new MailMessage)
            ->subject('Arbetsrapport - Tjänst slutförd')
            ->greeting('Hej ' . ($this->booking->company->company_name ?? 'Företag') . '!')
            ->line('Tjänsten har slutförts framgångsrikt!')
            ->line('**Tjänst:** ' . $this->booking->service->name)
            ->line('**Kund:** ' . $this->booking->customer_name)
            ->line('**Stad:** ' . $this->booking->city->name)
            ->line('**Din nettointäkt:** ' . number_format($netEarnings, 0, ',', ' ') . ' kr')
            ->line('')
            ->line('**Utbetalning:** Utbetalning från admin kommer varje vecka. ROT-avdrag tar företaget från Skatteverket enligt svenska regler.')
            ->line('')
            ->line('Tack för ditt arbete!')
            ->salutation('Mvh, ' . site_name());

        // Attach PDF summary
        $pdf = Pdf::loadView('pdf.company-summary', ['booking' => $this->booking]);
        $message->attachData($pdf->output(), "arbetsrapport-{$this->booking->booking_number}.pdf", [
            'mime' => 'application/pdf',
        ]);

        return $message;
    }
}

