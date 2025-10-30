<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationSetting;

class NotificationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [
            // Booking notifications
            [
                'type' => 'email',
                'category' => 'booking',
                'event' => 'booking_completed',
                'subject' => 'Tack för din bokning hos {{company_name}}!',
                'template' => "Hej {{user_name}}!\n\nTack för att du valde {{company_name}} för {{service_name}}!\n\nDin bokning (#{{booking_number}}) är nu slutförd.\n\nEn faktura kommer att skickas från {{company_name}} inom kort.\n\nVi hoppas du är nöjd med vår service! Vänligen lämna en recension för att hjälpa oss förbättra vår kvalitet.\n\nMed vänliga hälsningar,\n{{company_name}}",
                'variables' => ['user_name', 'company_name', 'service_name', 'booking_number', 'booking_date', 'booking_time', 'total_amount'],
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
                'subject' => 'Bokning slutförd - {{user_name}}',
                'template' => "Hej {{company_name}}!\n\nEn bokning har slutförts:\n\nKund: {{user_name}}\nTjänst: {{service_name}}\nBokningsnummer: {{booking_number}}\nDatum: {{booking_date}}\nTid: {{booking_time}}\nBelopp: {{total_amount}}\n\nEn faktura kommer att genereras automatiskt.\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['user_name', 'company_name', 'service_name', 'booking_number', 'booking_date', 'booking_time', 'total_amount'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['company'],
                'conditions' => null,
                'priority' => 4,
            ],

            // User notifications
            [
                'type' => 'email',
                'category' => 'user',
                'event' => 'user_registered',
                'subject' => 'Välkommen till Bitra, {{user_name}}!',
                'template' => "Hej {{user_name}}!\n\nVälkommen till Bitra! Vi är glada att du har anslutit dig till vår plattform.\n\nSom välkomstbonus har du fått {{loyalty_points}} lojalitetspoäng som du kan använda på dina framtida bokningar!\n\nLojalitetspoäng fungerar så här:\n- 1 poäng = 1 SEK rabatt\n- Du får poäng för varje slutförd bokning\n- Poängen kan användas direkt vid nästa bokning\n\nUtforska våra tjänster och boka din första städning idag!\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['user_name', 'user_email', 'loyalty_points'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 5,
            ],
            [
                'type' => 'in_app',
                'category' => 'user',
                'event' => 'loyalty_points_earned',
                'subject' => null,
                'template' => "🎉 Grattis! Du har tjänat {{loyalty_points}} lojalitetspoäng för din bokning #{{booking_number}}!\n\nDu har nu totalt {{total_points}} poäng som du kan använda på framtida bokningar.\n\nBoka mer för att tjäna fler poäng!",
                'variables' => ['user_name', 'loyalty_points', 'booking_number', 'service_name', 'total_points'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['user'],
                'conditions' => null,
                'priority' => 3,
            ],

            // Company notifications
            [
                'type' => 'email',
                'category' => 'company',
                'event' => 'invoice_sent',
                'subject' => 'Faktura från Bitra - {{invoice_number}}',
                'template' => "Hej {{company_name}}!\n\nEn ny faktura har genererats för dina kommissioner.\n\nFakturanummer: {{invoice_number}}\nBelopp: {{invoice_amount}}\nFörfallodatum: {{due_date}}\n\nBetalning sker via bankgiro enligt svenska standarder.\n\nMed vänliga hälsningar,\nBitra Team",
                'variables' => ['company_name', 'invoice_number', 'invoice_amount', 'due_date'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['company'],
                'conditions' => null,
                'priority' => 4,
            ],

            // Admin notifications
            [
                'type' => 'email',
                'category' => 'admin',
                'event' => 'new_company_registration',
                'subject' => 'Nytt företag registrerat - {{company_name}}',
                'template' => "Hej Admin!\n\nEtt nytt företag har registrerat sig:\n\nFöretag: {{company_name}}\nKontaktperson: {{user_name}}\nE-post: {{user_email}}\n\nVänligen granska och godkänn företaget i admin-panelen.\n\nMed vänliga hälsningar,\nBitra System",
                'variables' => ['company_name', 'user_name', 'user_email'],
                'enabled' => true,
                'auto_send' => true,
                'recipients' => ['admin'],
                'conditions' => null,
                'priority' => 3,
            ],

            // System notifications
            [
                'type' => 'in_app',
                'category' => 'system',
                'event' => 'maintenance_scheduled',
                'subject' => null,
                'template' => "🔧 Systemunderhåll planerat\n\nSystemet kommer att vara otillgängligt från {{start_time}} till {{end_time}} för planerat underhåll.\n\nVi ber om ursäkt för eventuella besvär.",
                'variables' => ['start_time', 'end_time'],
                'enabled' => true,
                'auto_send' => false,
                'recipients' => ['user', 'company', 'admin'],
                'conditions' => null,
                'priority' => 2,
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
    }
}
