<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
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
        'area_map_selection',
        'status',
    ];

    protected $casts = [
        'features_benefits' => 'array',
        'process_steps' => 'array',
        'faq_items' => 'array',
        'testimonials' => 'array',
        'area_map_selection' => 'array',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function activeCities(): HasMany
    {
        return $this->cities()->where('status', 'active');
    }
}
