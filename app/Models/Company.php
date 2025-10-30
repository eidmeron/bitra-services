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
        'company_name',
        'description',
        'address',
        'city',
        'postal_code',
        'company_logo',
        'company_email',
        'company_number',
        'company_org_number',
        'site',
        'logo', // Alias for company_logo
        'phone', // Alias for company_number
        'email', // Alias for company_email
        'org_number', // Alias for company_org_number
        'zip_code', // Alias for postal_code
        'website', // Alias for site
        'review_average',
        'review_count',
        'status',
        'payout_method',
        'deposit_method',
        'swish_number',
        'bank_name',
        'clearing_number',
        'account_number',
        'payout_notes',
        'deposit_notes',
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

    public function messages(): HasMany
    {
        return $this->hasMany(CompanyMessage::class);
    }

    public function commissionSetting(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CommissionSetting::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function updateReviewStats(): void
    {
        $this->review_count = $this->reviews()->where('company_status', 'approved')->count();
        $this->review_average = $this->reviews()->where('company_status', 'approved')->avg('company_rating') ?? 0;
        $this->save();
    }

    // Accessors for backward compatibility
    public function getLogoAttribute(): ?string
    {
        return $this->company_logo;
    }

    public function getEmailAttribute(): ?string
    {
        return $this->company_email;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->company_number;
    }

    public function getWebsiteAttribute(): ?string
    {
        return $this->site;
    }

    public function getReviewsAvgRatingAttribute(): ?float
    {
        return $this->review_average ? (float)$this->review_average : null;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->review_count ?? 0;
    }

    public function weeklyCommissionReports(): HasMany
    {
        return $this->hasMany(WeeklyCommissionReport::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }
}
