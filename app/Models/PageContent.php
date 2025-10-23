<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

final class PageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'page_name',
        'page_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'hero_cta_text',
        'hero_cta_link',
        'sections',
        'features',
        'faqs',
        'testimonials',
        'schema_markup',
        'is_active',
        'order',
    ];

    protected $casts = [
        'sections' => 'array',
        'features' => 'array',
        'faqs' => 'array',
        'testimonials' => 'array',
        'schema_markup' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope: Only active pages
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: By page key
     */
    public function scopeByKey(Builder $query, string $key): Builder
    {
        return $query->where('page_key', $key);
    }

    /**
     * Scope: By page type
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('page_type', $type);
    }

    /**
     * Get page content by key with caching
     */
    public static function getByKey(string $key): ?self
    {
        return Cache::remember("page_content_{$key}", 3600, function () use ($key) {
            return self::active()->byKey($key)->first();
        });
    }

    /**
     * Get all active pages
     */
    public static function getAllPages(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('all_page_contents', 3600, function () {
            return self::active()->orderBy('order')->get();
        });
    }

    /**
     * Clear cache when model is saved or deleted
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saved(function ($page) {
            Cache::forget("page_content_{$page->page_key}");
            Cache::forget('all_page_contents');
        });

        static::deleted(function ($page) {
            Cache::forget("page_content_{$page->page_key}");
            Cache::forget('all_page_contents');
        });
    }
}
