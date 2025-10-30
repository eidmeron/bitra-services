<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SeoPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type',
        'service_id',
        'category_id',
        'city_id',
        'zone_id',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'h1_title',
        'hero_text',
        'content',
        'features',
        'faq',
        'schema_markup',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'faq' => 'array',
        'schema_markup' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Get SEO data for a specific page type and related models
     */
    public static function getSeoData(string $pageType, ?int $serviceId = null, ?int $categoryId = null, ?int $cityId = null, ?int $zoneId = null): ?self
    {
        $query = self::where('page_type', $pageType)
            ->where('is_active', true);

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        return $query->first();
    }

    /**
     * Get default SEO data for a page type
     */
    public static function getDefaultSeoData(string $pageType): ?self
    {
        return self::where('page_type', $pageType)
            ->where('is_active', true)
            ->whereNull('service_id')
            ->whereNull('category_id')
            ->whereNull('city_id')
            ->whereNull('zone_id')
            ->first();
    }

    /**
     * Generate dynamic meta title based on page data
     */
    public function getDynamicMetaTitle(): string
    {
        $title = $this->meta_title ?? '';
        
        if ($this->service) {
            $title = str_replace('{service}', $this->service->name, $title);
        }
        if ($this->category) {
            $title = str_replace('{category}', $this->category->name, $title);
        }
        if ($this->city) {
            $title = str_replace('{city}', $this->city->name, $title);
        }
        if ($this->zone) {
            $title = str_replace('{zone}', $this->zone->name, $title);
        }

        return $title;
    }

    /**
     * Generate dynamic meta description based on page data
     */
    public function getDynamicMetaDescription(): string
    {
        $description = $this->meta_description ?? '';
        
        if ($this->service) {
            $description = str_replace('{service}', $this->service->name, $description);
        }
        if ($this->category) {
            $description = str_replace('{category}', $this->category->name, $description);
        }
        if ($this->city) {
            $description = str_replace('{city}', $this->city->name, $description);
        }
        if ($this->zone) {
            $description = str_replace('{zone}', $this->zone->name, $description);
        }

        return $description;
    }

    /**
     * Get page type options
     */
    public static function getPageTypes(): array
    {
        return [
            'service' => 'Tjänst',
            'category' => 'Kategori',
            'city' => 'Stad',
            'zone' => 'Zon',
            'city_service' => 'Stad + Tjänst',
            'category_service' => 'Kategori + Tjänst',
            'homepage' => 'Startsida',
            'about' => 'Om oss',
            'contact' => 'Kontakt',
            'pricing' => 'Priser',
            'reviews' => 'Recensioner',
        ];
    }
}