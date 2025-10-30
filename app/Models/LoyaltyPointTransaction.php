<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyPointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'type',
        'points',
        'value',
        'description',
        'notes',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'value' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeBadgeColor(): string
    {
        return match ($this->type) {
            'earned' => 'green',
            'spent' => 'blue',
            'expired' => 'red',
            'adjusted' => 'yellow',
            'refunded' => 'purple',
            default => 'gray',
        };
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public static function getAvailablePointsForUser(int $userId): float
    {
        $earned = static::where('user_id', $userId)
            ->where('type', 'earned')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->sum('points');

        $spent = static::where('user_id', $userId)
            ->whereIn('type', ['spent', 'expired'])
            ->sum('points');

        return max(0, $earned - $spent);
    }
}