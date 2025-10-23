<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'full_content',
        'features',
        'includes',
        'faq',
        'booking_url',
        'icon',
        'status',
        'base_price',
        'hourly_rate',
        'pricing_unit',
        'discount_percent',
        'one_time_booking',
        'subscription_booking',
        'subscription_types',
        'daily_multiplier',
        'weekly_multiplier',
        'biweekly_multiplier',
        'monthly_multiplier',
        'rot_eligible',
        'rot_percent',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'rot_percent' => 'decimal:2',
        'daily_multiplier' => 'decimal:2',
        'weekly_multiplier' => 'decimal:2',
        'biweekly_multiplier' => 'decimal:2',
        'monthly_multiplier' => 'decimal:2',
        'subscription_types' => 'array',
        'features' => 'array',
        'includes' => 'array',
        'faq' => 'array',
        'one_time_booking' => 'boolean',
        'subscription_booking' => 'boolean',
        'rot_eligible' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_service')
            ->withTimestamps();
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_service')
            ->withTimestamps();
    }

    public function forms(): HasMany
    {
        return $this->hasMany(Form::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getActiveFormAttribute(): ?Form
    {
        return $this->forms()->where('status', 'active')->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
