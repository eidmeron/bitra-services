<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Bitra Services',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Webbplatsnamn',
                'description' => 'Namnet på din webbplats',
                'order' => 1,
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Din pålitliga partner för hemtjänster',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Slogan',
                'description' => 'En kort beskrivning av din webbplats',
                'order' => 2,
            ],
            [
                'key' => 'site_description',
                'value' => 'Bitra Services är Sveriges ledande plattform för att boka hemtjänster. Vi kopplar ihop kunder med verifierade företag för städning, trädgård, underhåll och mycket mer.',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Webbplatsbeskrivning',
                'description' => 'Använd för SEO och delning på sociala medier',
                'order' => 3,
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Logotyp',
                'description' => 'Huvudlogotyp (rekommenderad storlek: 200x60px)',
                'order' => 4,
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Favicon',
                'description' => 'Webbplatsikon (rekommenderad storlek: 32x32px)',
                'order' => 5,
            ],

            // Header Settings
            [
                'key' => 'header_phone',
                'value' => '+46 70 123 45 67',
                'type' => 'text',
                'group' => 'header',
                'label' => 'Telefonnummer',
                'description' => 'Telefonnummer i sidhuvudet',
                'order' => 1,
            ],
            [
                'key' => 'header_email',
                'value' => 'info@bitraservices.se',
                'type' => 'text',
                'group' => 'header',
                'label' => 'E-postadress',
                'description' => 'E-postadress i sidhuvudet',
                'order' => 2,
            ],
            [
                'key' => 'header_cta_text',
                'value' => 'Boka nu',
                'type' => 'text',
                'group' => 'header',
                'label' => 'CTA-knapptext',
                'description' => 'Text på call-to-action-knappen',
                'order' => 3,
            ],
            [
                'key' => 'header_cta_link',
                'value' => '#booking',
                'type' => 'text',
                'group' => 'header',
                'label' => 'CTA-länk',
                'description' => 'Länk för call-to-action-knappen',
                'order' => 4,
            ],

            // Footer Settings
            [
                'key' => 'footer_about',
                'value' => 'Bitra Services är Sveriges ledande plattform för att boka hemtjänster. Vi kopplar ihop kunder med verifierade företag för en pålitlig och bekväm upplevelse.',
                'type' => 'textarea',
                'group' => 'footer',
                'label' => 'Om oss-text',
                'description' => 'Kort beskrivning i sidfoten',
                'order' => 1,
            ],
            [
                'key' => 'footer_copyright',
                'value' => '© 2025 Bitra Services. Alla rättigheter förbehållna.',
                'type' => 'text',
                'group' => 'footer',
                'label' => 'Copyright-text',
                'description' => 'Copyright-meddelande',
                'order' => 2,
            ],

            // SEO Settings
            [
                'key' => 'seo_default_title',
                'value' => 'Bitra Services - Boka hemtjänster online',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Standard SEO-titel',
                'description' => 'Standard titel för sidor utan specifik titel',
                'order' => 1,
            ],
            [
                'key' => 'seo_default_description',
                'value' => 'Boka hemtjänster snabbt och enkelt. Städning, trädgård, underhåll och mer från verifierade företag. Transparenta priser, snabb service och kvalitetsgaranti.',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Standard SEO-beskrivning',
                'description' => 'Standard metabeskrivning (max 160 tecken)',
                'order' => 2,
            ],
            [
                'key' => 'seo_keywords',
                'value' => 'hemtjänster, städning, trädgård, underhåll, boka online, ROT-avdrag',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'SEO-nyckelord',
                'description' => 'Globala nyckelord separerade med komma',
                'order' => 3,
            ],
            [
                'key' => 'seo_og_image',
                'value' => null,
                'type' => 'image',
                'group' => 'seo',
                'label' => 'Standard Open Graph-bild',
                'description' => 'Standard bild för sociala medier (1200x630px)',
                'order' => 4,
            ],

            // Social Media Settings
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/bitraservices',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Facebook',
                'description' => 'Facebook-sidans URL',
                'order' => 1,
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/bitraservices',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Instagram',
                'description' => 'Instagram-profil URL',
                'order' => 2,
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/bitraservices',
                'type' => 'url',
                'group' => 'social',
                'label' => 'LinkedIn',
                'description' => 'LinkedIn-sidans URL',
                'order' => 3,
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/bitraservices',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Twitter/X',
                'description' => 'Twitter/X-profil URL',
                'order' => 4,
            ],

            // Contact Settings
            [
                'key' => 'contact_address',
                'value' => 'Storgatan 1, 111 22 Stockholm',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Adress',
                'description' => 'Företagsadress',
                'order' => 1,
            ],
            [
                'key' => 'contact_phone',
                'value' => '+46 70 123 45 67',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Kontakttelefon',
                'description' => 'Huvudtelefonnummer',
                'order' => 2,
            ],
            [
                'key' => 'contact_email',
                'value' => 'kontakt@bitraservices.se',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Kontakt-e-post',
                'description' => 'Huvudkontakt-e-post',
                'order' => 3,
            ],
            [
                'key' => 'contact_support_email',
                'value' => 'support@bitraservices.se',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Support-e-post',
                'description' => 'E-post för kundsupport',
                'order' => 4,
            ],
            [
                'key' => 'contact_hours',
                'value' => 'Mån-Fre: 08:00-17:00, Lör: 10:00-14:00',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Öppettider',
                'description' => 'Kontorstider',
                'order' => 5,
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
