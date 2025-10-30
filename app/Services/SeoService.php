<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SeoPage;
use App\Models\Service;
use App\Models\Category;
use App\Models\City;
use App\Models\Zone;

final class SeoService
{
    /**
     * Get SEO data for a specific page
     */
    public static function getSeoData(string $pageType, ?int $serviceId = null, ?int $categoryId = null, ?int $cityId = null, ?int $zoneId = null): array
    {
        // Try to get specific SEO data first
        $seoPage = SeoPage::getSeoData($pageType, $serviceId, $categoryId, $cityId, $zoneId);
        
        // If no specific data found, try to get default data
        if (!$seoPage) {
            $seoPage = SeoPage::getDefaultSeoData($pageType);
        }

        if (!$seoPage) {
            return self::getDefaultSeoData($pageType);
        }

        return [
            'meta_title' => $seoPage->getDynamicMetaTitle(),
            'meta_description' => $seoPage->getDynamicMetaDescription(),
            'meta_keywords' => $seoPage->meta_keywords,
            'og_title' => $seoPage->og_title ?: $seoPage->getDynamicMetaTitle(),
            'og_description' => $seoPage->og_description ?: $seoPage->getDynamicMetaDescription(),
            'og_image' => $seoPage->og_image ? asset('storage/' . $seoPage->og_image) : null,
            'h1_title' => $seoPage->h1_title,
            'hero_text' => $seoPage->hero_text,
            'content' => $seoPage->content,
            'features' => $seoPage->features ?? [],
            'faq' => $seoPage->faq ?? [],
            'schema_markup' => $seoPage->schema_markup ?? [],
        ];
    }

    /**
     * Get SEO data for service page
     */
    public static function getServiceSeoData(Service $service): array
    {
        return self::getSeoData('service', $service->id, $service->category_id);
    }

    /**
     * Get SEO data for category page
     */
    public static function getCategorySeoData(Category $category): array
    {
        return self::getSeoData('category', null, $category->id);
    }

    /**
     * Get SEO data for city page
     */
    public static function getCitySeoData(City $city): array
    {
        return self::getSeoData('city', null, null, $city->id);
    }

    /**
     * Get SEO data for city-service page
     */
    public static function getCityServiceSeoData(City $city, Service $service): array
    {
        return self::getSeoData('city_service', $service->id, $service->category_id, $city->id);
    }

    /**
     * Get SEO data for category-service page
     */
    public static function getCategoryServiceSeoData(Category $category, Service $service): array
    {
        return self::getSeoData('category_service', $service->id, $category->id);
    }

    /**
     * Get default SEO data when no specific data is found
     */
    private static function getDefaultSeoData(string $pageType): array
    {
        $defaults = [
            'homepage' => [
                'meta_title' => 'Bitra Services - Boka hemtjänster online | Snabbt, Enkelt & Tryggt',
                'meta_description' => 'Boka hemtjänster snabbt och enkelt. Städning, trädgård, underhåll och mer från verifierade företag. Transparenta priser, snabb service och kvalitetsgaranti.',
                'meta_keywords' => 'hemtjänster, boka online, städning, trädgård, underhåll, ROT-avdrag, Sverige',
                'h1_title' => 'Boka Hemtjänster Online',
                'hero_text' => 'Snabbt, Enkelt och Tryggt med Verifierade Företag',
            ],
            'service' => [
                'meta_title' => '{service} - Professionell tjänst | Bitra Services',
                'meta_description' => 'Boka {service} från verifierade företag. Transparenta priser, snabb service och kvalitetsgaranti. Boka online idag!',
                'meta_keywords' => '{service}, professionell tjänst, boka online, Sverige',
                'h1_title' => '{service}',
                'hero_text' => 'Professionell {service} från verifierade företag',
            ],
            'category' => [
                'meta_title' => '{category} - Alla tjänster | Bitra Services',
                'meta_description' => 'Upptäck alla {category} tjänster. Boka från verifierade företag med transparenta priser och kvalitetsgaranti.',
                'meta_keywords' => '{category}, tjänster, boka online, Sverige',
                'h1_title' => '{category}',
                'hero_text' => 'Alla {category} tjänster på ett ställe',
            ],
            'city' => [
                'meta_title' => 'Hemtjänster i {city} | Bitra Services',
                'meta_description' => 'Boka hemtjänster i {city}. Städning, trädgård, underhåll och mer från verifierade företag i {city}.',
                'meta_keywords' => 'hemtjänster {city}, städning {city}, trädgård {city}, underhåll {city}',
                'h1_title' => 'Hemtjänster i {city}',
                'hero_text' => 'Professionella tjänster i {city}',
            ],
            'city_service' => [
                'meta_title' => '{service} i {city} | Bitra Services',
                'meta_description' => 'Boka {service} i {city}. Professionella företag med transparenta priser och kvalitetsgaranti.',
                'meta_keywords' => '{service} {city}, professionell {service}, boka {service} {city}',
                'h1_title' => '{service} i {city}',
                'hero_text' => 'Professionell {service} i {city}',
            ],
            'about' => [
                'meta_title' => 'Om oss - Bitra Services',
                'meta_description' => 'Lär känna Bitra Services. Vi kopplar ihop kunder med verifierade företag för hemtjänster.',
                'meta_keywords' => 'om oss, Bitra Services, hemtjänster, företag',
                'h1_title' => 'Om Bitra Services',
                'hero_text' => 'Din pålitliga partner för hemtjänster',
            ],
            'contact' => [
                'meta_title' => 'Kontakta oss - Bitra Services',
                'meta_description' => 'Kontakta Bitra Services kundservice. Vi svarar inom 24 timmar.',
                'meta_keywords' => 'kontakt, kundservice, Bitra Services, support',
                'h1_title' => 'Kontakta oss',
                'hero_text' => 'Vi finns här för att hjälpa dig',
            ],
            'pricing' => [
                'meta_title' => 'Priser - Transparenta priser | Bitra Services',
                'meta_description' => 'Se våra transparenta priser för hemtjänster. Inga dolda kostnader, bara rättvist pris.',
                'meta_keywords' => 'priser, transparenta priser, hemtjänster, kostnad',
                'h1_title' => 'Våra priser',
                'hero_text' => 'Transparenta priser utan dolda kostnader',
            ],
            'reviews' => [
                'meta_title' => 'Recensioner - Vad våra kunder säger | Bitra Services',
                'meta_description' => 'Läs recensioner från våra nöjda kunder. Se vad de säger om våra hemtjänster.',
                'meta_keywords' => 'recensioner, kundrecensioner, hemtjänster, nöjda kunder',
                'h1_title' => 'Vad våra kunder säger',
                'hero_text' => 'Läs recensioner från våra nöjda kunder',
            ],
        ];

        return $defaults[$pageType] ?? [
            'meta_title' => 'Bitra Services - Hemtjänster online',
            'meta_description' => 'Boka hemtjänster snabbt och enkelt från verifierade företag.',
            'meta_keywords' => 'hemtjänster, boka online, Sverige',
            'h1_title' => 'Bitra Services',
            'hero_text' => 'Professionella hemtjänster',
        ];
    }

    /**
     * Generate schema markup for a page
     */
    public static function generateSchemaMarkup(array $seoData, string $pageType, $model = null): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $seoData['meta_title'],
            'description' => $seoData['meta_description'],
        ];

        if ($seoData['og_image']) {
            $schema['image'] = $seoData['og_image'];
        }

        // Add specific schema based on page type
        switch ($pageType) {
            case 'service':
                if ($model instanceof Service) {
                    $schema['@type'] = 'Service';
                    $schema['name'] = $model->name;
                    $schema['description'] = $model->description;
                    if ($model->base_price) {
                        $schema['offers'] = [
                            '@type' => 'Offer',
                            'price' => $model->base_price,
                            'priceCurrency' => 'SEK',
                        ];
                    }
                }
                break;
            case 'category':
                if ($model instanceof Category) {
                    $schema['@type'] = 'ItemList';
                    $schema['name'] = $model->name;
                    $schema['description'] = $model->description;
                }
                break;
        }

        return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
