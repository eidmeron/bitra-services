<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ComplaintStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Complaint $complaint,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'open' => 'Öppen',
            'in_progress' => 'Pågående',
            'resolved' => 'Löst',
            'closed' => 'Stängd',
        ];

        $oldStatusLabel = $statusLabels[$this->oldStatus] ?? $this->oldStatus;
        $newStatusLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        if ($notifiable->isAdmin()) {
            return (new MailMessage)
                ->subject('📝 Reklamation status ändrad - #' . $this->complaint->id)
                ->greeting('Hej Admin!')
                ->line('En reklamation har fått sin status ändrad.')
                ->line('**Reklamation ID:** #' . $this->complaint->id)
                ->line('**Ämne:** ' . $this->complaint->subject)
                ->line('**Status ändrad från:** ' . $oldStatusLabel)
                ->line('**Status ändrad till:** ' . $newStatusLabel)
                ->line('**Företag:** ' . $this->complaint->company->company_name)
                ->action('Visa reklamation', route('admin.complaints.show', $this->complaint))
                ->line('Statusuppdatering för reklamation #' . $this->complaint->id);
        }

        if ($notifiable->isCompany()) {
            return (new MailMessage)
                ->subject('📝 Reklamation status uppdaterad - #' . $this->complaint->id)
                ->greeting('Hej ' . $this->complaint->company->company_name . '!')
                ->line('En reklamation har fått sin status uppdaterad.')
                ->line('**Reklamation ID:** #' . $this->complaint->id)
                ->line('**Ämne:** ' . $this->complaint->subject)
                ->line('**Status ändrad från:** ' . $oldStatusLabel)
                ->line('**Status ändrad till:** ' . $newStatusLabel)
                ->line('**Kund:** ' . ($this->complaint->user ? $this->complaint->user->name : $this->complaint->customer_name))
                ->action('Visa reklamation', route('company.complaints.show', $this->complaint))
                ->line('Statusuppdatering för reklamation #' . $this->complaint->id);
        }

        // For users
        return (new MailMessage)
            ->subject('📝 Din reklamation har uppdaterats - #' . $this->complaint->id)
            ->greeting('Hej ' . ($this->complaint->user ? $this->complaint->user->name : $this->complaint->customer_name) . '!')
            ->line('Din reklamation har fått en statusuppdatering.')
            ->line('**Reklamation ID:** #' . $this->complaint->id)
            ->line('**Ämne:** ' . $this->complaint->subject)
            ->line('**Status ändrad från:** ' . $oldStatusLabel)
            ->line('**Status ändrad till:** ' . $newStatusLabel)
            ->line('**Företag:** ' . $this->complaint->company->company_name)
            ->action('Se reklamation', route('user.complaints.show', $this->complaint))
            ->line('Tack för ditt tålamod medan vi hanterar din reklamation.');
    }

    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'open' => 'Öppen',
            'in_progress' => 'Pågående',
            'resolved' => 'Löst',
            'closed' => 'Stängd',
        ];

        $newStatusLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        if ($notifiable->isAdmin()) {
            return [
                'type' => 'complaint_status_changed',
                'title' => 'Reklamation status ändrad',
                'message' => 'Reklamation #' . $this->complaint->id . ' är nu ' . $newStatusLabel,
                'icon' => '📝',
                'url' => route('admin.complaints.show', $this->complaint, false),
                'complaint_id' => $this->complaint->id,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
            ];
        }

        if ($notifiable->isCompany()) {
            return [
                'type' => 'complaint_status_changed',
                'title' => 'Reklamation status uppdaterad',
                'message' => 'Reklamation #' . $this->complaint->id . ' är nu ' . $newStatusLabel,
                'icon' => '📝',
                'url' => route('company.complaints.show', $this->complaint, false),
                'complaint_id' => $this->complaint->id,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
            ];
        }

        return [
            'type' => 'complaint_status_changed',
            'title' => 'Reklamation uppdaterad',
            'message' => 'Din reklamation #' . $this->complaint->id . ' är nu ' . $newStatusLabel,
            'icon' => '📝',
            'url' => route('user.complaints.show', $this->complaint, false),
            'complaint_id' => $this->complaint->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
