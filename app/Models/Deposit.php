<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'booking_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'booking_amount',
        'commission_amount',
        'commission_rate',
        'loyalty_points_used',
        'loyalty_points_value',
        'deposit_amount',
        'status',
        'notes',
        'sent_at',
        'paid_at',
        'sent_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'booking_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'loyalty_points_used' => 'decimal:2',
        'loyalty_points_value' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'sent' && $this->due_date < now()->toDateString();
    }

    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'sent' => 'blue',
            'paid' => 'green',
            'overdue' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}