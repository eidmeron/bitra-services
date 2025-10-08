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
        'icon',
        'status',
        'base_price',
        'discount_percent',
        'one_time_booking',
        'subscription_booking',
        'rot_eligible',
        'rot_percent',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'rot_percent' => 'decimal:2',
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
