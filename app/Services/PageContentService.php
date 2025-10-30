<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PageContent;
use Illuminate\Support\Facades\Cache;

final class PageContentService
{
    /**
     * Get page content by key with fallback to default values
     */
    public static function getPageContent(string $pageKey, array $defaults = []): array
    {
        $page = PageContent::getByKey($pageKey);
        
        if (!$page) {
            return $defaults;
        }
        
        return [
            'meta_title' => $page->meta_title ?: $defaults['meta_title'] ?? '',
            'meta_description' => $page->meta_description ?: $defaults['meta_description'] ?? '',
            'meta_keywords' => $page->meta_keywords ?: $defaults['meta_keywords'] ?? '',
            'og_title' => $page->og_title ?: $page->meta_title ?: $defaults['og_title'] ?? '',
            'og_description' => $page->og_description ?: $page->meta_description ?: $defaults['og_description'] ?? '',
            'og_image' => $page->og_image ?: $defaults['og_image'] ?? '',
            'canonical_url' => $page->canonical_url ?: $defaults['canonical_url'] ?? '',
            'hero_title' => $page->hero_title ?: $defaults['hero_title'] ?? '',
            'hero_subtitle' => $page->hero_subtitle ?: $defaults['hero_subtitle'] ?? '',
            'hero_image' => $page->hero_image ?: $defaults['hero_image'] ?? '',
            'hero_cta_text' => $page->hero_cta_text ?: $defaults['hero_cta_text'] ?? '',
            'hero_cta_link' => $page->hero_cta_link ?: $defaults['hero_cta_link'] ?? '',
            'sections' => $page->sections ?: $defaults['sections'] ?? [],
            'features' => $page->features ?: $defaults['features'] ?? [],
            'faqs' => $page->faqs ?: $defaults['faqs'] ?? [],
            'testimonials' => $page->testimonials ?: $defaults['testimonials'] ?? [],
            'schema_markup' => $page->schema_markup ?: $defaults['schema_markup'] ?? [],
        ];
    }
    
    /**
     * Get SEO data for a page
     */
    public static function getSeoData(string $pageKey, array $defaults = []): array
    {
        $content = self::getPageContent($pageKey, $defaults);
        
        return [
            'title' => $content['meta_title'],
            'description' => $content['meta_description'],
            'keywords' => $content['meta_keywords'],
            'og_title' => $content['og_title'],
            'og_description' => $content['og_description'],
            'og_image' => $content['og_image'],
            'canonical' => $content['canonical_url'],
            'schema' => $content['schema_markup'],
        ];
    }
    
    /**
     * Get hero section data
     */
    public static function getHeroData(string $pageKey, array $defaults = []): array
    {
        $content = self::getPageContent($pageKey, $defaults);
        
        return [
            'title' => $content['hero_title'],
            'subtitle' => $content['hero_subtitle'],
            'image' => $content['hero_image'],
            'cta_text' => $content['hero_cta_text'],
            'cta_link' => $content['hero_cta_link'],
        ];
    }
    
    /**
     * Get sections data
     */
    public static function getSections(string $pageKey, array $defaults = []): array
    {
        $content = self::getPageContent($pageKey, $defaults);
        return $content['sections'] ?? [];
    }
    
    /**
     * Get features data
     */
    public static function getFeatures(string $pageKey, array $defaults = []): array
    {
        $content = self::getPageContent($pageKey, $defaults);
        return $content['features'] ?? [];
    }
    
    /**
     * Get FAQs data
     */
    public static function getFaqs(string $pageKey, array $defaults = []): array
    {
        $content = self::getPageContent($pageKey, $defaults);
        return $content['faqs'] ?? [];
    }
    
    /**
     * Get testimonials data
     */
    public static function getTestimonials(string $pageKey, array $defaults = []): array
    {
        $content = self::getPageContent($pageKey, $defaults);
        return $content['testimonials'] ?? [];
    }
    
    /**
     * Check if page content exists
     */
    public static function hasPageContent(string $pageKey): bool
    {
        return PageContent::getByKey($pageKey) !== null;
    }
    
    /**
     * Get all available page keys
     */
    public static function getAvailablePageKeys(): array
    {
        return Cache::remember('available_page_keys', 3600, function () {
            return PageContent::active()->pluck('page_key')->toArray();
        });
    }
}
