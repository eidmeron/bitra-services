<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

final class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'booking_id',
        'payout_period',
        'period_start',
        'period_end',
        'booking_amount',
        'commission_amount',
        'commission_rate',
        'payout_amount',
        'status',
        'notes',
        'approved_at',
        'paid_at',
        'approved_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'booking_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'payout_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the company that owns the payout
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the booking associated with the payout
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the admin who approved the payout
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Pending payouts
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Approved payouts
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Paid payouts
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Approve payout
     */
    public function approve(int $approvedBy): bool
    {
        $this->status = 'approved';
        $this->approved_by = $approvedBy;
        $this->approved_at = now();
        return $this->save();
    }

    /**
     * Mark payout as paid
     */
    public function markAsPaid(): bool
    {
        $this->status = 'paid';
        $this->paid_at = now();
        return $this->save();
    }

    /**
     * Create payout from completed booking
     */
    public static function createFromBooking(Booking $booking): ?self
    {
        if ($booking->status !== 'completed' || !$booking->company_id) {
            return null;
        }

        // Get commission setting
        $commissionSetting = CommissionSetting::getForCompany($booking->company_id);
        if (!$commissionSetting) {
            $commissionSetting = CommissionSetting::getOrCreateForCompany($booking->company_id);
        }

        $bookingAmount = (float) $booking->final_price;
        $commissionAmount = $commissionSetting->calculateCommission($bookingAmount);
        $payoutAmount = $bookingAmount - $commissionAmount;

        return self::create([
            'company_id' => $booking->company_id,
            'booking_id' => $booking->id,
            'booking_amount' => $bookingAmount,
            'commission_amount' => $commissionAmount,
            'commission_rate' => $commissionSetting->commission_rate,
            'payout_amount' => $payoutAmount,
            'status' => 'pending',
        ]);
    }

    /**
     * Generate weekly payout reports
     */
    public static function generateWeeklyReports(\DateTimeInterface $weekStart, \DateTimeInterface $weekEnd): array
    {
        $weekNumber = date('W', $weekStart->getTimestamp());
        $year = date('Y', $weekStart->getTimestamp());
        $payoutPeriod = "Week {$weekNumber}, {$year}";

        // Get all completed bookings for the week that don't have payouts yet
        $bookings = Booking::where('status', 'completed')
            ->whereBetween('updated_at', [$weekStart, $weekEnd])
            ->whereNotNull('company_id')
            ->whereDoesntHave('payout')
            ->get();

        $reports = [];

        foreach ($bookings as $booking) {
            $payout = self::createFromBooking($booking);
            if ($payout) {
                $payout->update([
                    'payout_period' => $payoutPeriod,
                    'period_start' => $weekStart,
                    'period_end' => $weekEnd,
                ]);
                $reports[] = $payout;
            }
        }

        return $reports;
    }
}
