<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class NewComplaintNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Complaint $complaint
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isAdmin = $notifiable->isAdmin();
        $isCompany = $notifiable->isCompany();
        
        if ($isAdmin) {
            return (new MailMessage)
                ->subject('📝 Ny reklamation mottagen - #' . $this->complaint->id)
                ->greeting('Hej Admin!')
                ->line('En ny reklamation har skapats och behöver din uppmärksamhet.')
                ->line('**Reklamation ID:** #' . $this->complaint->id)
                ->line('**Ämne:** ' . $this->complaint->subject)
                ->line('**Prioritet:** ' . ucfirst($this->complaint->priority))
                ->line('**Företag:** ' . $this->complaint->company->company_name)
                ->line('**Kund:** ' . ($this->complaint->user ? $this->complaint->user->name : $this->complaint->customer_name))
                ->line('**Tjänst:** ' . $this->complaint->booking->service->name)
                ->line('**Beskrivning:** ' . substr($this->complaint->description, 0, 200) . '...')
                ->action('Hantera reklamation', route('admin.complaints.show', $this->complaint))
                ->line('Vänligen granska och hantera denna reklamation så snart som möjligt.');
        }
        
        if ($isCompany) {
            return (new MailMessage)
                ->subject('📝 Ny reklamation från kund - #' . $this->complaint->id)
                ->greeting('Hej ' . $this->complaint->company->company_name . '!')
                ->line('En av era kunder har skapat en reklamation som behöver er uppmärksamhet.')
                ->line('**Reklamation ID:** #' . $this->complaint->id)
                ->line('**Ämne:** ' . $this->complaint->subject)
                ->line('**Prioritet:** ' . ucfirst($this->complaint->priority))
                ->line('**Kund:** ' . ($this->complaint->user ? $this->complaint->user->name : $this->complaint->customer_name))
                ->line('**Tjänst:** ' . $this->complaint->booking->service->name)
                ->line('**Beskrivning:** ' . substr($this->complaint->description, 0, 200) . '...')
                ->action('Svara på reklamation', route('company.complaints.show', $this->complaint))
                ->line('Vänligen svara på denna reklamation inom 24 timmar för bästa kundservice.');
        }
        
        // For users
        return (new MailMessage)
            ->subject('📝 Din reklamation har mottagits - #' . $this->complaint->id)
            ->greeting('Hej ' . ($this->complaint->user ? $this->complaint->user->name : $this->complaint->customer_name) . '!')
            ->line('Tack för att du skapade en reklamation. Vi har mottagit din reklamation och kommer att hantera den så snart som möjligt.')
            ->line('**Reklamation ID:** #' . $this->complaint->id)
            ->line('**Ämne:** ' . $this->complaint->subject)
            ->line('**Prioritet:** ' . ucfirst($this->complaint->priority))
            ->line('**Företag:** ' . $this->complaint->company->company_name)
            ->line('**Tjänst:** ' . $this->complaint->booking->service->name)
            ->action('Se reklamation', route('user.complaints.show', $this->complaint))
            ->line('Du kommer att få uppdateringar via e-post när företaget eller admin svarar på din reklamation.');
    }

    public function toArray(object $notifiable): array
    {
        $isAdmin = $notifiable->isAdmin();
        $isCompany = $notifiable->isCompany();
        
        if ($isAdmin) {
            return [
                'type' => 'new_complaint',
                'title' => 'Ny reklamation mottagen',
                'message' => 'Reklamation #' . $this->complaint->id . ' från ' . ($this->complaint->user ? $this->complaint->user->name : $this->complaint->customer_name),
                'icon' => '📝',
                'url' => route('admin.complaints.show', $this->complaint, false),
                'complaint_id' => $this->complaint->id,
                'priority' => $this->complaint->priority,
            ];
        }
        
        if ($isCompany) {
            return [
                'type' => 'new_complaint',
                'title' => 'Ny reklamation från kund',
                'message' => 'Reklamation #' . $this->complaint->id . ' från ' . ($this->complaint->user ? $this->complaint->user->name : $this->complaint->customer_name),
                'icon' => '📝',
                'url' => route('company.complaints.show', $this->complaint, false),
                'complaint_id' => $this->complaint->id,
                'priority' => $this->complaint->priority,
            ];
        }
        
        return [
            'type' => 'complaint_created',
            'title' => 'Reklamation mottagen',
            'message' => 'Din reklamation #' . $this->complaint->id . ' har mottagits',
            'icon' => '📝',
            'url' => route('user.complaints.show', $this->complaint, false),
            'complaint_id' => $this->complaint->id,
        ];
    }
}
