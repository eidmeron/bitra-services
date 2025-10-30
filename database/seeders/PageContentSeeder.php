<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

final class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_key' => 'homepage',
                'page_name' => 'Startsida',
                'page_type' => 'static',
                'meta_title' => 'Hitta och boka professionella tjänster i hela Sverige',
                'meta_description' => 'Boka professionella tjänster i hela Sverige - från hemstädning till renovering. Vi kopplar dig till Sveriges bästa företag.',
                'meta_keywords' => 'tjänster, bokning, städning, renovering, hantverk, Sverige',
                'og_title' => 'Bitra - Sveriges bästa tjänsteplattform',
                'og_description' => 'Boka professionella tjänster i hela Sverige med garanterad kvalitet och transparenta priser.',
                'canonical_url' => '/',
                'hero_title' => 'Hitta och boka <span class="text-yellow-300">professionella tjänster</span> i hela Sverige',
                'hero_subtitle' => 'Från hemstädning till renovering - Vi kopplar dig till Sveriges bästa företag',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'page_key' => 'about',
                'page_name' => 'Om oss',
                'page_type' => 'static',
                'meta_title' => 'Om oss - Bitra Services | Sveriges Ledande Plattform för Hemtjänster',
                'meta_description' => 'Lär dig mer om Bitra - Sveriges ledande plattform för att hitta och boka professionella tjänster. Vår mission och vision.',
                'meta_keywords' => 'om oss, bitra, tjänsteplattform, mission, vision, Sverige',
                'og_title' => 'Om Bitra - Sveriges ledande tjänsteplattform',
                'og_description' => 'Upptäck vår mission att göra tjänstebokning enkelt, säkert och transparent för alla.',
                'canonical_url' => '/about',
                'hero_title' => 'Om Bitra',
                'hero_subtitle' => 'Din pålitliga plattform för verifierade och högkvalitativa tjänster i hela Sverige och internationellt.',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'page_key' => 'why-us',
                'page_name' => 'Varför välja oss',
                'page_type' => 'static',
                'meta_title' => 'Varför välja Bitra - Din pålitliga plattform för hemtjänster',
                'meta_description' => 'Upptäck varför Bitra är Sveriges bästa tjänsteplattform. Verifierade företag, transparenta priser och kvalitetsgaranti.',
                'meta_keywords' => 'varför bitra, tjänsteplattform, verifierade företag, kvalitetsgaranti, Sverige',
                'og_title' => 'Varför välja Bitra - Sveriges bästa tjänsteplattform',
                'og_description' => 'Upptäck fördelarna med att välja Bitra för dina tjänstebehov.',
                'canonical_url' => '/why-us',
                'hero_title' => 'Varför välja <span class="text-yellow-300">Bitra</span>?',
                'hero_subtitle' => 'Din pålitliga plattform för verifierade och högkvalitativa tjänster i hela Sverige och internationellt.',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'page_key' => 'how-it-works',
                'page_name' => 'Så fungerar det',
                'page_type' => 'static',
                'meta_title' => 'Så fungerar Bitra - Enkelt, säkert och transparent',
                'meta_description' => 'Lär dig hur Bitra fungerar - från sökning till bokning. Enkelt, säkert och transparent system för alla dina tjänstebehov.',
                'meta_keywords' => 'så fungerar bitra, tjänstebokning, process, enkelt, säkert',
                'og_title' => 'Så fungerar Bitra - Enkelt och säkert',
                'og_description' => 'Upptäck vår enkla 4-stegs process för att boka tjänster.',
                'canonical_url' => '/how-it-works',
                'hero_title' => 'Så fungerar Bitra',
                'hero_subtitle' => 'Enkelt, säkert och transparent - så får du den bästa tjänsten',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'page_key' => 'reviews',
                'page_name' => 'Recensioner',
                'page_type' => 'static',
                'meta_title' => 'Kundrecensioner - Vad våra kunder säger om Bitra',
                'meta_description' => 'Läs äkta kundrecensioner och upptäck varför tusentals kunder litar på Bitra för sina tjänstebehov.',
                'meta_keywords' => 'kundrecensioner, recensioner, bitra, kundomdömen, betyg',
                'og_title' => 'Kundrecensioner - Bitra',
                'og_description' => 'Läs vad våra nöjda kunder säger om våra tjänster.',
                'canonical_url' => '/reviews',
                'hero_title' => 'Vad våra kunder säger',
                'hero_subtitle' => 'Läs äkta recensioner från våra nöjda kunder',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'page_key' => 'categories',
                'page_name' => 'Kategorier',
                'page_type' => 'static',
                'meta_title' => 'Tjänstekategorier - Alla våra tjänster på ett ställe',
                'meta_description' => 'Utforska alla våra tjänstekategorier - från städning och renovering till trädgårdsarbete och flytt.',
                'meta_keywords' => 'tjänstekategorier, kategorier, städning, renovering, trädgård, flytt',
                'og_title' => 'Tjänstekategorier - Bitra',
                'og_description' => 'Utforska alla våra tjänstekategorier och hitta precis vad du behöver.',
                'canonical_url' => '/categories',
                'hero_title' => 'Alla våra tjänster',
                'hero_subtitle' => 'Utforska våra tjänstekategorier och hitta precis vad du behöver',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'page_key' => 'pricing',
                'page_name' => 'Priser',
                'page_type' => 'static',
                'meta_title' => 'Priser - Transparenta priser för alla tjänster',
                'meta_description' => 'Se våra transparenta priser för alla tjänster. Inga dolda avgifter, inga överraskningar - bara rättvisa priser.',
                'meta_keywords' => 'priser, priskalkylator, transparenta priser, kostnad, prislista',
                'og_title' => 'Priser - Transparenta priser för alla tjänster',
                'og_description' => 'Se våra transparenta priser och beräkna kostnaden för din tjänst.',
                'canonical_url' => '/pricing',
                'hero_title' => 'Transparenta priser',
                'hero_subtitle' => 'Inga dolda avgifter, inga överraskningar - bara rättvisa priser',
                'is_active' => true,
                'order' => 7,
            ],
        ];

        foreach ($pages as $pageData) {
            PageContent::updateOrCreate(
                ['page_key' => $pageData['page_key']],
                $pageData
            );
        }
    }
}