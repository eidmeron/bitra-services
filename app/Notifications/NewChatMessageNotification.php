<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Booking;
use App\Models\BookingChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public BookingChat $chat,
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = $notifiable->isCompany() 
            ? route('company.bookings.chat', $this->booking)
            : route('user.bookings.show', $this->booking);

        return (new MailMessage)
            ->subject('💬 Nytt meddelande för bokning #' . $this->booking->booking_number)
            ->greeting('Hej!')
            ->line('Du har fått ett nytt meddelande angående din bokning.')
            ->line('**Bokning:** ' . $this->booking->service->name)
            ->line('**Meddelande:** ' . substr($this->chat->message, 0, 100) . '...')
            ->action('Se meddelande', $url)
            ->line('Tack för att du använder ' . config('app.name') . '!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_chat_message',
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number,
            'sender_type' => $this->chat->sender_type,
            'sender_name' => $this->chat->sender_name,
            'message_preview' => substr($this->chat->message, 0, 100),
            'url' => $notifiable->isCompany() 
                ? route('company.bookings.chat', $this->booking, false)
                : route('user.bookings.show', $this->booking, false),
        ];
    }
}
