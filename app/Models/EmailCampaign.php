<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

final class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'status',
        'type',
        'target_audience',
        'filters',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'sent_count',
        'opened_count',
        'clicked_count',
        'unsubscribed_count',
        'open_rate',
        'click_rate',
    ];

    protected $casts = [
        'target_audience' => 'array',
        'filters' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'open_rate' => 'decimal:2',
        'click_rate' => 'decimal:2',
    ];

    /**
     * Scope: Only active campaigns
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '!=', 'cancelled');
    }

    /**
     * Scope: Draft campaigns
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Scheduled campaigns
     */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope: Sent campaigns
     */
    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', 'sent');
    }

    /**
     * Get campaign statistics
     */
    public function getStatsAttribute(): array
    {
        return [
            'total_recipients' => $this->total_recipients,
            'sent_count' => $this->sent_count,
            'opened_count' => $this->opened_count,
            'clicked_count' => $this->clicked_count,
            'unsubscribed_count' => $this->unsubscribed_count,
            'open_rate' => $this->open_rate,
            'click_rate' => $this->click_rate,
            'delivery_rate' => $this->total_recipients > 0 ? round(($this->sent_count / $this->total_recipients) * 100, 2) : 0,
        ];
    }

    /**
     * Check if campaign can be sent
     */
    public function canBeSent(): bool
    {
        return $this->status === 'draft' || $this->status === 'scheduled';
    }

    /**
     * Check if campaign is scheduled
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at && $this->scheduled_at->isFuture();
    }

    /**
     * Mark campaign as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Update campaign statistics
     */
    public function updateStats(): void
    {
        $this->update([
            'open_rate' => $this->sent_count > 0 ? round(($this->opened_count / $this->sent_count) * 100, 2) : 0,
            'click_rate' => $this->sent_count > 0 ? round(($this->clicked_count / $this->sent_count) * 100, 2) : 0,
        ]);
    }
}