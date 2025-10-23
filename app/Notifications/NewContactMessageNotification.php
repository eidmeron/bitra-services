<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ContactMessage $contactMessage
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nytt Kontaktmeddelande Mottaget')
            ->greeting('Hej!')
            ->line('Ett nytt kontaktmeddelande har mottagits.')
            ->line('**Från:** ' . $this->contactMessage->name)
            ->line('**Email:** ' . $this->contactMessage->email)
            ->line('**Ämne:** ' . $this->contactMessage->subject)
            ->line('**Meddelande:**')
            ->line($this->contactMessage->message)
            ->action('Visa Meddelande', route('admin.contact-messages.show', $this->contactMessage))
            ->line('Svara så snart som möjligt!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nytt Kontaktmeddelande',
            'message' => 'Från ' . $this->contactMessage->name . ': ' . $this->contactMessage->subject,
            'icon' => '📧',
            'url' => route('admin.contact-messages.show', $this->contactMessage),
            'contact_message_id' => $this->contactMessage->id,
        ];
    }
}

