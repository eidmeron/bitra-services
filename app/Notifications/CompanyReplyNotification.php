<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\CompanyMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public CompanyMessage $companyMessage
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Svar på Ditt Meddelande - ' . $this->companyMessage->company->company_name)
            ->greeting('Hej ' . $this->companyMessage->guest_name . '!')
            ->line('Du har fått ett svar på ditt meddelande.')
            ->line('**Ditt meddelande:**')
            ->line(substr($this->companyMessage->message, 0, 150) . '...')
            ->line('**Svar från ' . $this->companyMessage->company->company_name . ':**')
            ->line($this->companyMessage->reply)
            ->line('Om du har fler frågor, vänligen kontakta företaget direkt.')
            ->salutation('Med vänliga hälsningar, ' . $this->companyMessage->company->company_name);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
