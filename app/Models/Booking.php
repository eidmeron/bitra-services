<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'user_id',
        'company_id',
        'service_id',
        'form_id',
        'city_id',
        'slot_time_id',
        'customer_type',
        'org_number',
        'personnummer',
        'booking_type',
        'subscription_frequency',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_message',
        'form_data',
        'base_price',
        'variable_additions',
        'city_multiplier',
        'subscription_multiplier',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'rot_deduction',
        'discount_amount',
        'loyalty_points_used',
        'loyalty_points_value',
        'commission_amount',
        'commission_rate',
        'final_price',
        'total_with_tax',
        'status',
        'preferred_date',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'base_price' => 'decimal:2',
        'variable_additions' => 'decimal:2',
        'city_multiplier' => 'decimal:2',
        'subscription_multiplier' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'rot_deduction' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'loyalty_points_used' => 'decimal:2',
        'loyalty_points_value' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'final_price' => 'decimal:2',
        'total_with_tax' => 'decimal:2',
        'preferred_date' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = 'BK' . date('Ymd') . strtoupper(Str::random(6));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function chats(): HasMany
    {
        return $this->hasMany(BookingChat::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function extraFees(): HasMany
    {
        return $this->hasMany(ExtraFee::class);
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'completed' && !$this->review;
    }

    public function getTotalExtraFeesAttribute(): float
    {
        return (float) $this->extraFees()
            ->where('status', 'approved')
            ->sum('amount');
    }

    public function getTotalWithExtraFeesAttribute(): float
    {
        $baseTotal = $this->total_with_tax ?? $this->final_price;
        return $baseTotal + $this->total_extra_fees;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class);
    }

    public function loyaltyPointTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyPointTransaction::class);
    }

    public function slotTime(): BelongsTo
    {
        return $this->belongsTo(SlotTime::class);
    }
}
