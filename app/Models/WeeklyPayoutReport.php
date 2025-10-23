<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

final class WeeklyPayoutReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'week_number',
        'year',
        'period_start',
        'period_end',
        'total_bookings',
        'total_revenue',
        'total_commission',
        'total_rot_deduction',
        'net_payout',
        'status',
        'sent_at',
        'invoice_number',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_revenue' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'total_rot_deduction' => 'decimal:2',
        'net_payout' => 'decimal:2',
        'sent_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'company_id', 'company_id')
            ->whereBetween('period_start', [$this->period_start, $this->period_end]);
    }

    public function scopeForWeek($query, int $weekNumber, int $year)
    {
        return $query->where('week_number', $weekNumber)->where('year', $year);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function markAsSent(): bool
    {
        $this->status = 'sent';
        $this->sent_at = now();
        return $this->save();
    }

    /**
     * Generate weekly report for a company
     */
    public static function generateForCompany(int $companyId, Carbon $weekStart, Carbon $weekEnd): self
    {
        $weekNumber = $weekStart->week;
        $year = $weekStart->year;

        // Get completed bookings for the week
        $bookings = Booking::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereBetween('completed_at', [$weekStart, $weekEnd])
            ->get();

        $totalRevenue = $bookings->sum(function ($b) { return (float) $b->final_price; });
        $totalRotDeduction = $bookings->sum(function ($b) { return (float) $b->rot_deduction; });

        // Calculate commission
        $commissionSetting = CommissionSetting::getForCompany($companyId);
        $totalCommission = $bookings->sum(function ($booking) use ($commissionSetting) {
            $amount = (float) $booking->final_price;
            return $commissionSetting ? $commissionSetting->calculateCommission($amount) : 0.0;
        });

        $netPayout = $totalRevenue - $totalCommission - $totalRotDeduction;

        return self::updateOrCreate(
            [
                'company_id' => $companyId,
                'week_number' => $weekNumber,
                'year' => $year,
            ],
            [
                'period_start' => $weekStart,
                'period_end' => $weekEnd,
                'total_bookings' => $bookings->count(),
                'total_revenue' => $totalRevenue,
                'total_commission' => $totalCommission,
                'total_rot_deduction' => $totalRotDeduction,
                'net_payout' => $netPayout,
                'status' => 'pending',
                'invoice_number' => 'INV-' . $year . '-' . str_pad($weekNumber, 2, '0', STR_PAD_LEFT) . '-' . str_pad($companyId, 4, '0', STR_PAD_LEFT),
            ]
        );
    }

    /**
     * Generate all weekly reports for a specific week
     */
    public static function generateAllForWeek(Carbon $weekStart, Carbon $weekEnd): array
    {
        $companies = Company::where('status', 'active')->get();
        $reports = [];

        foreach ($companies as $company) {
            $report = self::generateForCompany($company->id, $weekStart, $weekEnd);
            if ($report->total_bookings > 0) {
                $reports[] = $report;
            }
        }

        return $reports;
    }
}
