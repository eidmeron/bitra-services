<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\ExtraFee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExtraFeeAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ExtraFee $extraFee
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->extraFee->booking;
        $company = $this->extraFee->company;
        
        return (new MailMessage)
            ->subject('💰 Ny extra avgift tillagd - Bokning #' . $booking->booking_number)
            ->greeting('Hej!')
            ->line('En ny extra avgift har lagts till i bokning #' . $booking->booking_number . '.')
            ->line('**Företag:** ' . $company->name)
            ->line('**Titel:** ' . $this->extraFee->title)
            ->line('**Belopp:** ' . number_format($this->extraFee->amount, 2, ',', ' ') . ' kr')
            ->line('**Beskrivning:** ' . ($this->extraFee->description ?? 'Ingen beskrivning'))
            ->action('Visa bokning', route('admin.bookings.show', $booking))
            ->line('Tack för att du använder vår plattform!');
    }

    public function toArray(object $notifiable): array
    {
        $booking = $this->extraFee->booking;
        $company = $this->extraFee->company;
        
        return [
            'title' => '💰 Ny extra avgift tillagd',
            'message' => 'Extra avgift "' . $this->extraFee->title . '" (' . number_format($this->extraFee->amount, 2, ',', ' ') . ' kr) tillagd i bokning #' . $booking->booking_number . ' av ' . $company->name,
            'type' => 'extra_fee_added',
            'booking_id' => $booking->id,
            'extra_fee_id' => $this->extraFee->id,
            'company_id' => $company->id,
        ];
    }
}
