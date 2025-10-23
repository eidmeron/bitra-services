<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\CompanyMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCompanyMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CompanyMessage $companyMessage
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nytt Meddelande Till Ditt Företag')
            ->greeting('Hej!')
            ->line('Du har fått ett nytt meddelande.')
            ->line('**Från:** ' . $this->companyMessage->guest_name)
            ->line('**Email:** ' . $this->companyMessage->guest_email)
            ->line('**Ämne:** ' . $this->companyMessage->subject)
            ->line('**Meddelande:**')
            ->line($this->companyMessage->message)
            ->action('Visa och Svara', route('company.messages.show', $this->companyMessage))
            ->line('Svara så snart som möjligt för att ge bästa kundservice!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nytt Företagsmeddelande',
            'message' => 'Från ' . $this->companyMessage->guest_name . ': ' . $this->companyMessage->subject,
            'icon' => '💬',
            'url' => route('company.messages.show', $this->companyMessage),
            'company_message_id' => $this->companyMessage->id,
        ];
    }
}

