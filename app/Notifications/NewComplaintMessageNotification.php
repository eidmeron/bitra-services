<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Complaint;
use App\Models\ComplaintMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class NewComplaintMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Complaint $complaint,
        public ComplaintMessage $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isAdmin = $notifiable->isAdmin();
        $isCompany = $notifiable->isCompany();
        $isUser = !$isAdmin && !$isCompany;

        if ($isAdmin) {
            return (new MailMessage)
                ->subject('💬 Nytt meddelande i reklamation #' . $this->complaint->id)
                ->greeting('Hej Admin!')
                ->line('Ett nytt meddelande har skickats i en reklamation.')
                ->line('**Reklamation ID:** #' . $this->complaint->id)
                ->line('**Ämne:** ' . $this->complaint->subject)
                ->line('**Från:** ' . $this->message->sender_name)
                ->line('**Meddelande:** ' . substr($this->message->message, 0, 200) . '...')
                ->action('Visa meddelande', route('admin.complaints.show', $this->complaint))
                ->line('Vänligen granska det nya meddelandet i reklamationen.');
        }

        if ($isCompany) {
            return (new MailMessage)
                ->subject('💬 Nytt meddelande i reklamation #' . $this->complaint->id)
                ->greeting('Hej ' . $this->complaint->company->company_name . '!')
                ->line('Ett nytt meddelande har skickats i en reklamation.')
                ->line('**Reklamation ID:** #' . $this->complaint->id)
                ->line('**Ämne:** ' . $this->complaint->subject)
                ->line('**Från:** ' . $this->message->sender_name)
                ->line('**Meddelande:** ' . substr($this->message->message, 0, 200) . '...')
                ->action('Svara på meddelande', route('company.complaints.show', $this->complaint))
                ->line('Vänligen svara på kundens meddelande så snart som möjligt.');
        }

        // For users
        return (new MailMessage)
            ->subject('💬 Nytt svar på din reklamation #' . $this->complaint->id)
            ->greeting('Hej ' . ($this->complaint->user ? $this->complaint->user->name : $this->complaint->customer_name) . '!')
            ->line('Du har fått ett nytt svar på din reklamation.')
            ->line('**Reklamation ID:** #' . $this->complaint->id)
            ->line('**Ämne:** ' . $this->complaint->subject)
            ->line('**Från:** ' . $this->message->sender_name)
            ->line('**Meddelande:** ' . substr($this->message->message, 0, 200) . '...')
            ->action('Se svar', route('user.complaints.show', $this->complaint))
            ->line('Tack för ditt tålamod medan vi hanterar din reklamation.');
    }

    public function toArray(object $notifiable): array
    {
        $isAdmin = $notifiable->isAdmin();
        $isCompany = $notifiable->isCompany();

        if ($isAdmin) {
            return [
                'type' => 'new_complaint_message',
                'title' => 'Nytt meddelande i reklamation',
                'message' => 'Meddelande från ' . $this->message->sender_name . ' i reklamation #' . $this->complaint->id,
                'icon' => '💬',
                'url' => route('admin.complaints.show', $this->complaint, false),
                'complaint_id' => $this->complaint->id,
                'message_id' => $this->message->id,
            ];
        }

        if ($isCompany) {
            return [
                'type' => 'new_complaint_message',
                'title' => 'Nytt meddelande i reklamation',
                'message' => 'Meddelande från ' . $this->message->sender_name . ' i reklamation #' . $this->complaint->id,
                'icon' => '💬',
                'url' => route('company.complaints.show', $this->complaint, false),
                'complaint_id' => $this->complaint->id,
                'message_id' => $this->message->id,
            ];
        }

        return [
            'type' => 'new_complaint_message',
            'title' => 'Nytt svar på reklamation',
            'message' => 'Svar från ' . $this->message->sender_name . ' på din reklamation #' . $this->complaint->id,
            'icon' => '💬',
            'url' => route('user.complaints.show', $this->complaint, false),
            'complaint_id' => $this->complaint->id,
            'message_id' => $this->message->id,
        ];
    }
}
