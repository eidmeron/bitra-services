<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\LoyaltyPointTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoyaltyPointService
{
    /**
     * Earn loyalty points for a completed booking
     */
    public function earnPointsForBooking(Booking $booking, float $points, string $description = null): LoyaltyPointTransaction
    {
        if (!$booking->user_id) {
            throw new \Exception('Booking must have a user to earn loyalty points');
        }
        
        $description = $description ?? "Poäng för bokning #{$booking->booking_number}";
        
        return LoyaltyPointTransaction::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'type' => 'earned',
            'points' => $points,
            'value' => $points, // 1 point = 1 SEK
            'description' => $description,
            'expires_at' => Carbon::now()->addYear(), // Points expire after 1 year
        ]);
    }
    
    /**
     * Spend loyalty points for a booking
     */
    public function spendPointsForBooking(Booking $booking, float $points): LoyaltyPointTransaction
    {
        if (!$booking->user_id) {
            throw new \Exception('Booking must have a user to spend loyalty points');
        }
        
        $user = $booking->user;
        $availablePoints = $this->getAvailablePointsForUser($user->id);
        
        if ($availablePoints < $points) {
            throw new \Exception("Insufficient loyalty points. Available: {$availablePoints}, Requested: {$points}");
        }
        
        return LoyaltyPointTransaction::create([
            'user_id' => $user->id,
            'booking_id' => $booking->id,
            'type' => 'spent',
            'points' => -$points, // Negative for spending
            'value' => $points, // Value in SEK
            'description' => "Använda poäng för bokning #{$booking->booking_number}",
        ]);
    }
    
    /**
     * Get available loyalty points for a user
     */
    public function getAvailablePointsForUser(int $userId): float
    {
        return LoyaltyPointTransaction::getAvailablePointsForUser($userId);
    }
    
    /**
     * Calculate loyalty points to earn based on booking amount
     */
    public function calculatePointsToEarn(float $bookingAmount): float
    {
        // Earn 1 point per 10 SEK spent (10% return)
        return floor($bookingAmount / 10);
    }
    
    /**
     * Apply loyalty points to a booking
     */
    public function applyLoyaltyPointsToBooking(Booking $booking, float $pointsToUse): array
    {
        if (!$booking->user_id) {
            return [
                'success' => false,
                'message' => 'Bokningen måste ha en användare för att använda lojalitetspoäng',
                'points_used' => 0,
                'points_value' => 0,
            ];
        }
        
        $availablePoints = $this->getAvailablePointsForUser($booking->user_id);
        
        if ($availablePoints < $pointsToUse) {
            return [
                'success' => false,
                'message' => "Otillräckliga lojalitetspoäng. Tillgängliga: {$availablePoints}, Begärda: {$pointsToUse}",
                'points_used' => 0,
                'points_value' => 0,
            ];
        }
        
        // Don't allow using more points than the booking amount
        $maxPointsToUse = min($pointsToUse, $booking->final_price);
        
        try {
            DB::beginTransaction();
            
            // Create spending transaction
            $transaction = $this->spendPointsForBooking($booking, $maxPointsToUse);
            
            // Update booking with loyalty points used
            $booking->update([
                'loyalty_points_used' => $maxPointsToUse,
                'loyalty_points_value' => $maxPointsToUse, // 1 point = 1 SEK
            ]);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => "Använde {$maxPointsToUse} lojalitetspoäng",
                'points_used' => $maxPointsToUse,
                'points_value' => $maxPointsToUse,
                'transaction_id' => $transaction->id,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'points_used' => 0,
                'points_value' => 0,
            ];
        }
    }
    
    /**
     * Refund loyalty points for a cancelled booking
     */
    public function refundPointsForBooking(Booking $booking): ?LoyaltyPointTransaction
    {
        if (!$booking->user_id || !$booking->loyalty_points_used) {
            return null;
        }
        
        return LoyaltyPointTransaction::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'type' => 'refunded',
            'points' => $booking->loyalty_points_used,
            'value' => $booking->loyalty_points_value,
            'description' => "Återbetalning av poäng för avbokad bokning #{$booking->booking_number}",
        ]);
    }
    
    /**
     * Expire old loyalty points
     */
    public function expireOldPoints(): int
    {
        $expiredTransactions = LoyaltyPointTransaction::where('type', 'earned')
            ->where('expires_at', '<', now())
            ->whereDoesntHave('booking', function ($query) {
                $query->whereIn('status', ['completed', 'cancelled']);
            })
            ->get();
        
        $expiredCount = 0;
        
        foreach ($expiredTransactions as $transaction) {
            // Create expiration transaction
            LoyaltyPointTransaction::create([
                'user_id' => $transaction->user_id,
                'booking_id' => $transaction->booking_id,
                'type' => 'expired',
                'points' => -$transaction->points,
                'value' => $transaction->value,
                'description' => "Utgångna lojalitetspoäng från {$transaction->description}",
            ]);
            
            $expiredCount++;
        }
        
        return $expiredCount;
    }
    
    /**
     * Get loyalty point summary for a user
     */
    public function getUserLoyaltySummary(int $userId): array
    {
        $user = User::findOrFail($userId);
        
        $earned = LoyaltyPointTransaction::where('user_id', $userId)
            ->where('type', 'earned')
            ->sum('points');
        
        $spent = LoyaltyPointTransaction::where('user_id', $userId)
            ->whereIn('type', ['spent', 'expired'])
            ->sum('points');
        
        $available = $this->getAvailablePointsForUser($userId);
        
        $expiringSoon = LoyaltyPointTransaction::where('user_id', $userId)
            ->where('type', 'earned')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(30))
            ->sum('points');
        
        return [
            'total_earned' => $earned,
            'total_spent' => abs($spent),
            'available' => $available,
            'expiring_soon' => $expiringSoon,
            'user' => $user,
        ];
    }

    /**
     * Award welcome points to new user
     */
    public function awardWelcomePoints(User $user, int $points): void
    {
        // Create welcome transaction
        LoyaltyPointTransaction::create([
            'user_id' => $user->id,
            'booking_id' => null,
            'points' => $points,
            'value_sek' => $points, // 1 point = 1 SEK
            'type' => 'earned',
            'description' => "Välkomstbonus för nya användare",
        ]);

        // Update user's balance
        $user->increment('loyalty_points_balance', $points);

        \Illuminate\Support\Facades\Log::info("Awarded {$points} welcome loyalty points to new user {$user->id}");
    }
}
