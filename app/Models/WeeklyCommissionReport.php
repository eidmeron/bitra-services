<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyCommissionReport extends Model
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
        'total_loyalty_points_used',
        'total_loyalty_points_value',
        'net_deposit',
        'status',
        'sent_at',
        'invoice_number',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_revenue' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'total_loyalty_points_used' => 'decimal:2',
        'total_loyalty_points_value' => 'decimal:2',
        'net_deposit' => 'decimal:2',
        'sent_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'sent' => 'blue',
            'paid' => 'green',
            default => 'gray',
        };
    }

    public function getPeriodLabel(): string
    {
        return "Vecka {$this->week_number}, {$this->year}";
    }
}