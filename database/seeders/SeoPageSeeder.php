<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SeoPage;
use App\Models\Service;
use App\Models\Category;
use App\Models\City;
use Illuminate\Database\Seeder;

class SeoPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Homepage SEO
        SeoPage::updateOrCreate(
            ['page_type' => 'homepage', 'service_id' => null, 'category_id' => null, 'city_id' => null, 'zone_id' => null],
            [
                'page_type' => 'homepage',
                'slug' => 'homepage',
                'meta_title' => 'Bitra Services - Boka hemtjänster online | Snabbt, Enkelt & Tryggt',
                'meta_description' => 'Boka hemtjänster snabbt och enkelt. Städning, trädgård, underhåll och mer från verifierade företag. Transparenta priser, snabb service och kvalitetsgaranti.',
                'meta_keywords' => 'hemtjänster, boka online, städning, trädgård, underhåll, ROT-avdrag, Sverige',
                'og_title' => 'Bitra Services - Boka hemtjänster online',
                'og_description' => 'Boka hemtjänster snabbt och enkelt från verifierade företag. Transparenta priser och kvalitetsgaranti.',
                'h1_title' => 'Boka Hemtjänster Online',
                'hero_text' => 'Snabbt, Enkelt och Tryggt med Verifierade Företag',
                'content' => 'Vi kopplar ihop dig med Sveriges bästa företag för hemtjänster. Alla våra partners är verifierade och försäkrade.',
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
                ],
                'faq' => [
                    [
                        'question' => 'Vad kostar en tjänst?',
                        'answer' => 'Priserna varierar beroende på tjänst och omfattning. Vi erbjuder transparenta priser utan dolda avgifter.',
                    ],
                    [
                        'question' => 'Hur snabbt får jag svar?',
                        'answer' => 'De flesta offerter får du inom 24 timmar, ofta samma dag.',
                    ],
                    [
                        'question' => 'Är alla företag verifierade?',
                        'answer' => 'Ja, alla våra partnerföretag genomgår en noggrann verifieringsprocess.',
                    ],
                ],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // About page SEO
        SeoPage::updateOrCreate(
            ['page_type' => 'about', 'service_id' => null, 'category_id' => null, 'city_id' => null, 'zone_id' => null],
            [
                'page_type' => 'about',
                'slug' => 'om-oss',
                'meta_title' => 'Om oss - Bitra Services | Sveriges Ledande Plattform för Hemtjänster',
                'meta_description' => 'Lär känna Bitra Services. Vi kopplar ihop kunder med verifierade företag för städning, trädgård, underhåll och mer. Din pålitliga partner sedan 2020.',
                'meta_keywords' => 'om oss, Bitra Services, hemtjänster, företag, historia, uppdrag',
                'h1_title' => 'Om Bitra Services',
                'hero_text' => 'Din pålitliga partner för hemtjänster sedan 2020',
                'content' => 'Bitra Services grundades 2020 med en enkel vision: att göra det enkelt och tryggt att boka hemtjänster online. Idag är vi Sveriges ledande plattform med över 500 verifierade företag och tusentals nöjda kunder.',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // Contact page SEO
        SeoPage::updateOrCreate(
            ['page_type' => 'contact', 'service_id' => null, 'category_id' => null, 'city_id' => null, 'zone_id' => null],
            [
                'page_type' => 'contact',
                'slug' => 'kontakt',
                'meta_title' => 'Kontakta oss - Bitra Services Kundservice',
                'meta_description' => 'Har du frågor? Kontakta Bitra Services kundservice via telefon, e-post eller vårt kontaktformulär. Vi svarar inom 24 timmar.',
                'meta_keywords' => 'kontakt, kundservice, Bitra Services, support, telefon, e-post',
                'h1_title' => 'Kontakta oss',
                'hero_text' => 'Vi finns här för att hjälpa dig',
                'content' => 'Har du frågor om våra tjänster eller behöver hjälp med din bokning? Vår kundservice är här för att hjälpa dig.',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        // Pricing page SEO
        SeoPage::updateOrCreate(
            ['page_type' => 'pricing', 'service_id' => null, 'category_id' => null, 'city_id' => null, 'zone_id' => null],
            [
                'page_type' => 'pricing',
                'slug' => 'priser',
                'meta_title' => 'Priser - Transparenta priser för hemtjänster | Bitra Services',
                'meta_description' => 'Se våra transparenta priser för hemtjänster. Inga dolda kostnader, bara rättvist pris. Jämför offerter från verifierade företag.',
                'meta_keywords' => 'priser, transparenta priser, hemtjänster, kostnad, offert, jämför',
                'h1_title' => 'Våra priser',
                'hero_text' => 'Transparenta priser utan dolda kostnader',
                'content' => 'Vi tror på transparenta priser. Se exakt vad du betalar innan du bokar, utan dolda avgifter eller överraskningar.',
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        // Reviews page SEO
        SeoPage::updateOrCreate(
            ['page_type' => 'reviews', 'service_id' => null, 'category_id' => null, 'city_id' => null, 'zone_id' => null],
            [
                'page_type' => 'reviews',
                'slug' => 'recensioner',
                'meta_title' => 'Recensioner - Vad våra kunder säger | Bitra Services',
                'meta_description' => 'Läs recensioner från våra nöjda kunder. Se vad de säger om våra hemtjänster och upplev kvaliteten själv.',
                'meta_keywords' => 'recensioner, kundrecensioner, hemtjänster, nöjda kunder, omdömen',
                'h1_title' => 'Vad våra kunder säger',
                'hero_text' => 'Läs recensioner från våra nöjda kunder',
                'content' => 'Våra kunder är vår bästa rekommendation. Läs vad de säger om sina upplevelser med våra tjänster.',
                'is_active' => true,
                'sort_order' => 5,
            ]
        );

        // Create SEO pages for each category
        $categories = Category::where('status', 'active')->get();
        foreach ($categories as $category) {
            SeoPage::updateOrCreate(
                [
                    'page_type' => 'category',
                    'category_id' => $category->id,
                ],
                [
                    'slug' => 'kategori-' . $category->slug,
                    'meta_title' => $category->name . ' - Alla tjänster | Bitra Services',
                    'meta_description' => 'Upptäck alla ' . strtolower($category->name) . ' tjänster. Boka från verifierade företag med transparenta priser och kvalitetsgaranti.',
                    'meta_keywords' => strtolower($category->name) . ', tjänster, boka online, Sverige, professionell',
                    'h1_title' => $category->name,
                    'hero_text' => 'Alla ' . strtolower($category->name) . ' tjänster på ett ställe',
                    'content' => 'Hitta den perfekta ' . strtolower($category->name) . ' tjänsten för dina behov. Vi samarbetar med Sveriges bästa företag för att erbjuda dig kvalitet och service.',
                    'is_active' => true,
                    'sort_order' => 10 + $category->id,
                ]
            );
        }

        // Create SEO pages for each city
        $cities = City::all();
        foreach ($cities as $city) {
            SeoPage::updateOrCreate(
                [
                    'page_type' => 'city',
                    'city_id' => $city->id,
                ],
                [
                    'slug' => 'hemtjanster-' . strtolower(str_replace(' ', '-', $city->name)),
                    'meta_title' => 'Hemtjänster i ' . $city->name . ' | Bitra Services',
                    'meta_description' => 'Boka hemtjänster i ' . $city->name . '. Städning, trädgård, underhåll och mer från verifierade företag i ' . $city->name . '.',
                    'meta_keywords' => 'hemtjänster ' . $city->name . ', städning ' . $city->name . ', trädgård ' . $city->name . ', underhåll ' . $city->name,
                    'h1_title' => 'Hemtjänster i ' . $city->name,
                    'hero_text' => 'Professionella tjänster i ' . $city->name,
                    'content' => 'Upptäck vårt utbud av hemtjänster i ' . $city->name . '. Vi samarbetar med lokala verifierade företag för att erbjuda dig de bästa tjänsterna.',
                    'is_active' => true,
                    'sort_order' => 100 + $city->id,
                ]
            );
        }

        // Create SEO pages for each service
        $services = Service::where('status', 'active')->get();
        foreach ($services as $service) {
            SeoPage::updateOrCreate(
                [
                    'page_type' => 'service',
                    'service_id' => $service->id,
                ],
                [
                    'slug' => 'tjanst-' . $service->slug,
                    'meta_title' => $service->name . ' - Professionell tjänst | Bitra Services',
                    'meta_description' => 'Boka ' . strtolower($service->name) . ' från verifierade företag. Transparenta priser, snabb service och kvalitetsgaranti. Boka online idag!',
                    'meta_keywords' => strtolower($service->name) . ', professionell tjänst, boka online, Sverige, kvalitet',
                    'h1_title' => $service->name,
                    'hero_text' => 'Professionell ' . strtolower($service->name) . ' från verifierade företag',
                    'content' => $service->description ?: 'Hitta den perfekta ' . strtolower($service->name) . ' tjänsten för dina behov. Vi samarbetar med Sveriges bästa företag.',
                    'is_active' => true,
                    'sort_order' => 200 + $service->id,
                ]
            );
        }
    }
}
