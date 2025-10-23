<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PlatformReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'overall_rating',
        'service_quality_rating',
        'price_rating',
        'speed_rating',
        'staff_rating',
        'comment',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'overall_rating' => 'integer',
        'service_quality_rating' => 'integer',
        'price_rating' => 'integer',
        'speed_rating' => 'integer',
        'staff_rating' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeHighRated($query, int $minRating = 4)
    {
        return $query->where('overall_rating', '>=', $minRating);
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    public function toggleFeatured(): void
    {
        $this->update(['is_featured' => !$this->is_featured]);
    }

    public function getAverageRatingAttribute(): float
    {
        $ratings = array_filter([
            $this->overall_rating,
            $this->service_quality_rating,
            $this->price_rating,
            $this->speed_rating,
            $this->staff_rating,
        ]);

        return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0;
    }
}

