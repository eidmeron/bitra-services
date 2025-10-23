<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'page_key' => 'homepage',
                'page_name' => 'Startsida',
                'page_type' => 'static',
                'meta_title' => 'Bitra Services - Boka hemtjänster online | Snabbt, Enkelt & Tryggt',
                'meta_description' => 'Boka hemtjänster snabbt och enkelt. Städning, trädgård, underhåll och mer från verifierade företag. Transparenta priser, snabb service och kvalitetsgaranti.',
                'meta_keywords' => 'hemtjänster, boka online, städning, trädgård, underhåll, ROT-avdrag, Sverige',
                'hero_title' => 'Boka Hemtjänster Online',
                'hero_subtitle' => 'Snabbt, Enkelt och Tryggt med Verifierade Företag',
                'hero_cta_text' => 'Börja Boka Nu',
                'hero_cta_link' => '#services',
                'features' => [
                    [
                        'icon' => '💰',
                        'title' => 'Transparenta Priser',
                        'description' => 'Inga dolda kostnader. Se exakt pris innan du bokar.',
                    ],
                    [
                        'icon' => '⚡',
                        'title' => 'Snabb Service',
                        'description' => 'Boka online på 2 minuter. Få svar inom 24 timmar.',
                    ],
                    [
                        'icon' => '✓',
                        'title' => 'Kvalitetsgaranti',
                        'description' => 'Alla företag är verifierade och försäkrade.',
                    ],
                    [
                        'icon' => '🏆',
                        'title' => 'ROT-avdrag',
                        'description' => 'Få upp till 50% rabatt med ROT-avdrag.',
                    ],
                ],
                'is_active' => true,
                'order' => 1,
            ],
            [
                'page_key' => 'about',
                'page_name' => 'Om Oss',
                'page_type' => 'static',
                'meta_title' => 'Om Bitra Services - Sveriges Ledande Plattform för Hemtjänster',
                'meta_description' => 'Lär känna Bitra Services. Vi kopplar ihop kunder med verifierade företag för städning, trädgård, underhåll och mer. Din pålitliga partner sedan 2020.',
                'hero_title' => 'Om Bitra Services',
                'hero_subtitle' => 'Din pålitliga partner för hemtjänster sedan 2020',
                'sections' => [
                    [
                        'type' => 'text',
                        'title' => 'Vår Historia',
                        'content' => 'Bitra Services grundades 2020 med en enkel vision: att göra det enkelt och tryggt att boka hemtjänster online. Idag är vi Sveriges ledande plattform med över 500 verifierade företag och tusentals nöjda kunder.',
                    ],
                    [
                        'type' => 'text',
                        'title' => 'Vårt Uppdrag',
                        'content' => 'Vi strävar efter att skapa den bästa upplevelsen för både kunder och företag. Genom att erbjuda transparenta priser, snabb service och kvalitetsgaranti bygger vi förtroende och långsiktiga relationer.',
                    ],
                ],
                'is_active' => true,
                'order' => 2,
            ],
            [
                'page_key' => 'how-it-works',
                'page_name' => 'Så Fungerar Det',
                'page_type' => 'static',
                'meta_title' => 'Så Fungerar Det - Boka Hemtjänster på 3 Enkla Steg',
                'meta_description' => 'Lär dig hur du bokar hemtjänster på Bitra Services. Välj tjänst, fyll i detaljer och få offerter från verifierade företag. Enkelt, snabbt och tryggt.',
                'hero_title' => 'Så Fungerar Det',
                'hero_subtitle' => 'Boka hemtjänster på 3 enkla steg',
                'sections' => [
                    [
                        'type' => 'steps',
                        'title' => 'Enkel Bokningsprocess',
                        'steps' => [
                            [
                                'number' => 1,
                                'title' => 'Välj Tjänst',
                                'description' => 'Välj den tjänst du behöver från vårt breda utbud av hemtjänster.',
                            ],
                            [
                                'number' => 2,
                                'title' => 'Fyll i Detaljer',
                                'description' => 'Beskriv dina behov och när du vill ha tjänsten utförd.',
                            ],
                            [
                                'number' => 3,
                                'title' => 'Få Offerter',
                                'description' => 'Få offerter från verifierade företag inom 24 timmar.',
                            ],
                        ],
                    ],
                ],
                'is_active' => true,
                'order' => 3,
            ],
            [
                'page_key' => 'why-us',
                'page_name' => 'Varför Välja Oss',
                'page_type' => 'static',
                'meta_title' => 'Varför Välja Bitra Services? - Fördelarna med Vår Plattform',
                'meta_description' => 'Upptäck fördelarna med Bitra Services. Verifierade företag, transparenta priser, snabb service, kvalitetsgaranti och ROT-avdrag. Din trygghet är vår prioritet.',
                'hero_title' => 'Varför Välja Bitra Services?',
                'hero_subtitle' => 'Vi gör det enkelt, tryggt och bekvämt att boka hemtjänster',
                'features' => [
                    [
                        'icon' => '✓',
                        'title' => 'Verifierade Företag',
                        'description' => 'Alla våra partner är noggrant kontrollerade och försäkrade.',
                    ],
                    [
                        'icon' => '💰',
                        'title' => 'Bästa Pris',
                        'description' => 'Jämför offerter och välj det bästa priset för dig.',
                    ],
                    [
                        'icon' => '⭐',
                        'title' => 'Kundrecensioner',
                        'description' => 'Läs äkta recensioner från tidigare kunder.',
                    ],
                    [
                        'icon' => '🔒',
                        'title' => 'Säker Betalning',
                        'description' => 'Trygga betalningar med SSL-kryptering.',
                    ],
                    [
                        'icon' => '📞',
                        'title' => 'Support 24/7',
                        'description' => 'Vi finns alltid här för att hjälpa dig.',
                    ],
                    [
                        'icon' => '🏆',
                        'title' => 'Kvalitetsgaranti',
                        'description' => 'Om du inte är nöjd, gör vi om jobbet.',
                    ],
                ],
                'is_active' => true,
                'order' => 4,
            ],
            [
                'page_key' => 'contact',
                'page_name' => 'Kontakta Oss',
                'page_type' => 'static',
                'meta_title' => 'Kontakta Oss - Bitra Services Kundservice',
                'meta_description' => 'Har du frågor? Kontakta Bitra Services kundservice via telefon, e-post eller vårt kontaktformulär. Vi svarar inom 24 timmar.',
                'hero_title' => 'Kontakta Oss',
                'hero_subtitle' => 'Vi finns här för att hjälpa dig',
                'is_active' => true,
                'order' => 5,
            ],
        ];

        foreach ($pages as $page) {
            PageContent::updateOrCreate(
                ['page_key' => $page['page_key']],
                $page
            );
        }
    }
}
