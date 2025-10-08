<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlotTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'service_id',
        'date',
        'start_time',
        'end_time',
        'capacity',
        'booked_count',
        'is_available',
    ];

    protected $casts = [
        'date' => 'date',
        'capacity' => 'integer',
        'booked_count' => 'integer',
        'is_available' => 'boolean',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function hasAvailableSlots(): bool
    {
        return $this->is_available && $this->booked_count < $this->capacity;
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
            ->whereColumn('booked_count', '<', 'capacity');
    }
}
