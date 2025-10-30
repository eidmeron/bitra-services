<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationSetting;

class AdvancedNotificationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [
            // Booking Notifications
            [
                'type' => 'email',
                'category' => 'booking',
                'event' => 'booking_created',
                'subject' => 'Bokningsbekräftelse - {{service_name}}',
                'template' => "Hej {{user_name}}!\n\nDin bokning har bekräftats och vi ser fram emot att hjälpa dig med {{service_name}}.\n\n📋 Bokningsdetaljer:\nBokningsnummer: #{{booking_number}}\nTjänst: {{service_name}}\nDatum: {{booking_date}}\nTid: {{booking_time}}\nTotalt belopp: {{total_amount}} kr\n\nEn faktura kommer att skickas från {{company_name}} inom kort.\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['user_name', 'service_name', 'booking_number', 'booking_date', 'booking_time', 'total_amount', 'company_name'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 5,
            ],
            [
                'type' => 'email',
                'category' => 'booking',
                'event' => 'booking_confirmed',
                'subject' => 'Bokning bekräftad - {{service_name}}',
                'template' => "Hej {{user_name}}!\n\nVi har goda nyheter! {{company_name}} har bekräftat din bokning för {{service_name}}.\n\n📋 Bekräftade detaljer:\nBokningsnummer: #{{booking_number}}\nDatum: {{booking_date}}\nTid: {{booking_time}}\nFöretag: {{company_name}}\n\nFakturan kommer att skickas från {{company_name}} inom kort.\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['user_name', 'company_name', 'service_name', 'booking_number', 'booking_date', 'booking_time'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 5,
            ],
            [
                'type' => 'email',
                'category' => 'booking',
                'event' => 'booking_completed',
                'subject' => 'Tack för att du valde {{company_name}}!',
                'template' => "Hej {{user_name}}!\n\nTack för att du valde {{company_name}} för {{service_name}}! Vi hoppas att du är nöjd med tjänsten.\n\n⭐ Du har tjänat lojalitetspoäng!\nDu har tjänat {{loyalty_points_earned}} lojalitetspoäng för denna bokning.\nDina totala lojalitetspoäng: {{total_loyalty_points}}\nAnvänd dina poäng som rabatt på framtida bokningar!\n\nVi skulle uppskatta om du kunde lämna en recension för {{company_name}} för att hjälpa andra kunder.\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['user_name', 'company_name', 'service_name', 'loyalty_points_earned', 'total_loyalty_points'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 4,
            ],
            [
                'type' => 'email',
                'category' => 'booking',
                'event' => 'booking_cancelled',
                'subject' => 'Bokning avbruten - {{service_name}}',
                'template' => "Hej {{user_name}}!\n\nVi meddelar att din bokning för {{service_name}} har avbrutits.\n\nBokningsnummer: #{{booking_number}}\nDatum: {{booking_date}}\nAnledning: {{cancellation_reason}}\n\nOm du har frågor, kontakta oss gärna.\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['user_name', 'service_name', 'booking_number', 'booking_date', 'cancellation_reason'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 5,
            ],

            // Company Notifications
            [
                'type' => 'email',
                'category' => 'company',
                'event' => 'new_booking',
                'subject' => 'Ny bokning mottagen - {{service_name}}',
                'template' => "Hej {{company_name}}!\n\nDu har fått en ny bokning för {{service_name}} som väntar på din bekräftelse.\n\n📋 Bokningsdetaljer:\nBokningsnummer: #{{booking_number}}\nKund: {{customer_name}}\nEmail: {{customer_email}}\nTelefon: {{customer_phone}}\nDatum: {{booking_date}}\nTid: {{booking_time}}\nTotalt belopp: {{total_amount}} kr\n\nLogga in på din dashboard för att bekräfta bokningen.\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['company_name', 'service_name', 'booking_number', 'customer_name', 'customer_email', 'customer_phone', 'booking_date', 'booking_time', 'total_amount'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['company'],
                'conditions' => null,
                'priority' => 5,
            ],

            // User Registration
            [
                'type' => 'email',
                'category' => 'user',
                'event' => 'user_registered',
                'subject' => 'Välkommen till Bitra! 🎉',
                'template' => "Hej {{user_name}}!\n\nTack för att du registrerade dig på Bitra! Vi är glada att ha dig med oss.\n\n⭐ Välkomstbonus!\nSom välkomstpresent har du fått 100 lojalitetspoäng!\nDessa poäng kan användas som rabatt på framtida bokningar (1 poäng = 1 SEK).\n\n🚀 Vad du kan göra på Bitra:\n• Hitta och boka tjänster i din stad\n• Tjäna lojalitetspoäng på varje bokning\n• Använd poäng som rabatt\n• Hantera alla dina bokningar enkelt\n• Lämna recensioner och hjälp andra\n\nUtforska våra tjänster och boka din första städning idag!\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['user_name', 'user_email'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 4,
            ],

            // Payment Notifications
            [
                'type' => 'email',
                'category' => 'payment',
                'event' => 'invoice_sent',
                'subject' => 'Faktura {{invoice_number}} - Bitra',
                'template' => "Hej {{company_name}}!\n\nHär är din faktura för kommissioner och avgifter från Bitra.\n\n📋 Fakturadetaljer:\nFakturanummer: {{invoice_number}}\nFaktureringsperiod: {{invoice_period}}\nFörfallodatum: {{due_date}}\nTotalt belopp: {{total_amount}} kr\n\nBetalningsinstruktioner:\nBankgiro: {{bankgiro_number}}\nBetalning ska ske inom 30 dagar från fakturadatum.\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['company_name', 'invoice_number', 'invoice_period', 'due_date', 'total_amount', 'bankgiro_number'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['company'],
                'conditions' => null,
                'priority' => 4,
            ],

            // SMS Notifications
            [
                'type' => 'sms',
                'category' => 'booking',
                'event' => 'booking_reminder',
                'subject' => null,
                'template' => 'Hej {{user_name}}! Påminnelse: Din bokning för {{service_name}} är imorgon {{booking_date}} kl {{booking_time}}. Kontakta {{company_name}} om du har frågor. Bitra',
                'variables' => ['user_name', 'service_name', 'booking_date', 'booking_time', 'company_name'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 3,
            ],
            [
                'type' => 'sms',
                'category' => 'booking',
                'event' => 'booking_confirmed_sms',
                'subject' => null,
                'template' => 'Hej {{user_name}}! Din bokning #{{booking_number}} för {{service_name}} är bekräftad för {{booking_date}} kl {{booking_time}}. Bitra',
                'variables' => ['user_name', 'booking_number', 'service_name', 'booking_date', 'booking_time'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 5,
            ],

            // Push Notifications
            [
                'type' => 'push',
                'category' => 'booking',
                'event' => 'booking_status_changed',
                'subject' => null,
                'template' => 'Din bokning #{{booking_number}} har uppdaterats: {{new_status}}',
                'variables' => ['booking_number', 'new_status'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 4,
            ],

            // In-App Notifications
            [
                'type' => 'in_app',
                'category' => 'loyalty',
                'event' => 'loyalty_points_earned',
                'subject' => null,
                'template' => '🎉 Du tjänade {{loyalty_points}} lojalitetspoäng! Totala poäng: {{total_points}}',
                'variables' => ['loyalty_points', 'total_points'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 3,
            ],
        ];

        foreach ($notifications as $notification) {
            NotificationSetting::updateOrCreate(
                [
                    'type' => $notification['type'],
                    'category' => $notification['category'],
                    'event' => $notification['event'],
                ],
                $notification
            );
        }

        $this->command->info('Created ' . count($notifications) . ' advanced notification settings.');
    }
}