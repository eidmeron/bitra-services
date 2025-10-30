<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
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
        'city_multiplier',
        'price_multiplier',
        'status',
    ];

    protected $casts = [
        'features_benefits' => 'array',
        'process_steps' => 'array',
        'faq_items' => 'array',
        'testimonials' => 'array',
        'area_map_selection' => 'array',
        'city_multiplier' => 'decimal:2',
        'price_multiplier' => 'decimal:2',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'city_service')
            ->withTimestamps();
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'city_company')
            ->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function slotTimes(): HasMany
    {
        return $this->hasMany(SlotTime::class);
    }

    /**
     * Scope a query to only include active cities
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
