<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\WeeklyPayoutReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WeeklyPayoutReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public WeeklyPayoutReport $report
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $company = $this->report->company;
        $weekPeriod = "Vecka {$this->report->week_number}, {$this->report->year}";
        
        return (new MailMessage)
            ->subject("Veckorapport för {$weekPeriod} - {$company->company_name}")
            ->greeting("Hej {$company->company_name}!")
            ->line("Här är din veckorapport för {$weekPeriod}.")
            ->line("**Sammanfattning:**")
            ->line("• Antal bokningar: {$this->report->total_bookings}")
            ->line("• Total omsättning: " . number_format($this->report->total_revenue, 0, ',', ' ') . " SEK")
            ->line("• Provision ({$this->report->company->commissionSetting->commission_rate ?? 15}%): " . number_format($this->report->total_commission, 0, ',', ' ') . " SEK")
            ->line("• ROT-avdrag: " . number_format($this->report->total_rot_deduction, 0, ',', ' ') . " SEK")
            ->line("• **Utbetalning: " . number_format($this->report->net_payout, 0, ',', ' ') . " SEK**")
            ->line("")
            ->line("**Faktura:** {$this->report->invoice_number}")
            ->line("**Period:** {$this->report->period_start->format('Y-m-d')} - {$this->report->period_end->format('Y-m-d')}")
            ->line("")
            ->line("**Viktig information om skatter:**")
            ->line("• Denna utbetalning är före skatt")
            ->line("• Du ansvarar för att deklarera inkomsten till Skatteverket")
            ->line("• ROT-avdrag hanteras av kunden direkt med Skatteverket")
            ->line("• Moms (25%) ska redovisas separat om du är momsregistrerad")
            ->line("")
            ->line("**Nästa utbetalning:**")
            ->line("Utbetalningar sker veckovis varje måndag för föregående veckas slutförda bokningar.")
            ->line("")
            ->action('Visa detaljerad rapport', route('company.payouts.show', $this->report->id))
            ->line("Tack för att du är en del av Bitra!")
            ->salutation('Med vänliga hälsningar,<br>Bitra Team');
    }

    public function toArray($notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'week_number' => $this->report->week_number,
            'year' => $this->report->year,
            'total_bookings' => $this->report->total_bookings,
            'net_payout' => $this->report->net_payout,
            'invoice_number' => $this->report->invoice_number,
        ];
    }
}
