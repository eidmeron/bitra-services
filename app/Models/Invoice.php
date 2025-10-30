<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'period_start',
        'period_end',
        'week_number',
        'year',
        'subtotal',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'total_bookings',
        'total_revenue',
        'total_commission',
        'total_loyalty_points_used',
        'total_loyalty_points_value',
        'net_deposit',
        'status',
        'sent_at',
        'paid_at',
        'payment_reference',
        'notes',
        'bankgiro_number',
        'payment_reference_number',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'subtotal' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'total_loyalty_points_used' => 'decimal:2',
        'total_loyalty_points_value' => 'decimal:2',
        'net_deposit' => 'decimal:2',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'sent' && $this->due_date < now()->toDateString();
    }

    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'sent' => 'blue',
            'paid' => 'green',
            'overdue' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getPeriodLabel(): string
    {
        if ($this->week_number && $this->year) {
            return "Vecka {$this->week_number}, {$this->year}";
        }
        
        return $this->period_start->format('Y-m-d') . ' - ' . $this->period_end->format('Y-m-d');
    }

    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $prefix = 'INV';
        
        // Get the last invoice number for this year
        $lastInvoice = static::where('invoice_number', 'like', "{$prefix}-{$year}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return sprintf('%s-%d-%06d', $prefix, $year, $newNumber);
    }
}