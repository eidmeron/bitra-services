<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_logo',
        'company_email',
        'company_number',
        'company_org_number',
        'site',
        'review_average',
        'review_count',
        'status',
    ];

    protected $casts = [
        'review_average' => 'decimal:2',
        'review_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'company_service')
            ->withTimestamps();
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_company')
            ->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function updateReviewStats(): void
    {
        $this->review_count = $this->reviews()->where('status', 'approved')->count();
        $this->review_average = $this->reviews()->where('status', 'approved')->avg('rating') ?? 0;
        $this->save();
    }
}
