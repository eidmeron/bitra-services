<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CommissionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'commission_rate',
        'commission_type',
        'fixed_amount',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the company that owns the commission setting
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope: Only active commission settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Calculate commission amount based on booking amount
     */
    public function calculateCommission(float $bookingAmount): float
    {
        if (!$this->is_active) {
            return 0;
        }

        if ($this->commission_type === 'fixed') {
            return (float) $this->fixed_amount;
        }

        // Percentage based
        return ($bookingAmount * $this->commission_rate) / 100;
    }

    /**
     * Get commission setting for a company
     */
    public static function getForCompany(int $companyId): ?self
    {
        return self::where('company_id', $companyId)->first();
    }

    /**
     * Get or create default commission setting for a company
     */
    public static function getOrCreateForCompany(int $companyId, float $defaultRate = 15.00): self
    {
        return self::firstOrCreate(
            ['company_id' => $companyId],
            [
                'commission_rate' => $defaultRate,
                'commission_type' => 'percentage',
                'is_active' => true,
            ]
        );
    }
}
