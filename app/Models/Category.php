<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'intro_paragraph',
        'features_benefits',
        'process_steps',
        'faq_items',
        'testimonials',
        'cta_text',
        'cta_button_text',
        'cta_button_url',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'image',
        'icon',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'features_benefits' => 'array',
        'process_steps' => 'array',
        'faq_items' => 'array',
        'testimonials' => 'array',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function activeServices(): HasMany
    {
        return $this->services()->where('status', 'active');
    }

    /**
     * Scope: Only active categories
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
