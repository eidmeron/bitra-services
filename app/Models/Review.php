<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'company_id',
        'user_id',
        'service_id',
        'review_type',
        'company_rating',
        'company_review_text',
        'company_status',
        'bitra_rating',
        'bitra_review_text',
        'bitra_status',
    ];

    protected $casts = [
        'company_rating' => 'integer',
        'bitra_rating' => 'integer',
    ];

    protected static function booted(): void
    {
        static::created(function (Review $review) {
            $review->company->updateReviewStats();
        });

        static::updated(function (Review $review) {
            if ($review->wasChanged('status')) {
                $review->company->updateReviewStats();
            }
        });

        static::deleted(function (Review $review) {
            $review->company->updateReviewStats();
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('company_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('company_status', 'pending');
    }

    public function scopeApprovedCompany($query)
    {
        return $query->where('company_status', 'approved');
    }

    public function scopeApprovedBitra($query)
    {
        return $query->where('bitra_status', 'approved');
    }

    public function scopePendingBitra($query)
    {
        return $query->where('bitra_status', 'pending');
    }

    public function scopeCompanyReviews($query)
    {
        return $query->where('review_type', 'company');
    }

    public function scopeBitraReviews($query)
    {
        return $query->where('review_type', 'bitra');
    }

    public function hasCompanyReview(): bool
    {
        return !is_null($this->company_rating);
    }

    public function hasBitraReview(): bool
    {
        return !is_null($this->bitra_rating);
    }

    public function isCompanyReviewApproved(): bool
    {
        return $this->company_status === 'approved';
    }

    public function isBitraReviewApproved(): bool
    {
        return $this->bitra_status === 'approved';
    }
}
