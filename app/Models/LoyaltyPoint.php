<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'type',
        'description',
        'booking_id',
        'expires_at',
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the loyalty point
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking associated with the loyalty point
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope: Only earned points
     */
    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    /**
     * Scope: Only spent points
     */
    public function scopeSpent($query)
    {
        return $query->where('type', 'spent');
    }

    /**
     * Scope: Non-expired points
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Add points to user
     */
    public static function addPoints(int $userId, float $points, string $description, ?int $bookingId = null, ?\DateTime $expiresAt = null): self
    {
        $loyaltyPoint = self::create([
            'user_id' => $userId,
            'points' => abs($points),
            'type' => 'earned',
            'description' => $description,
            'booking_id' => $bookingId,
            'expires_at' => $expiresAt,
        ]);

        // Update user balance
        $user = User::find($userId);
        if ($user) {
            $user->increment('loyalty_points_balance', abs($points));
        }

        return $loyaltyPoint;
    }

    /**
     * Deduct points from user
     */
    public static function deductPoints(int $userId, float $points, string $description, ?int $bookingId = null): ?self
    {
        $user = User::find($userId);
        if (!$user || $user->loyalty_points_balance < $points) {
            return null;
        }

        $loyaltyPoint = self::create([
            'user_id' => $userId,
            'points' => abs($points),
            'type' => 'spent',
            'description' => $description,
            'booking_id' => $bookingId,
        ]);

        // Update user balance
        $user->decrement('loyalty_points_balance', abs($points));

        return $loyaltyPoint;
    }
}
