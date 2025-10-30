<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsTracking extends Model
{
    use HasFactory;

    protected $table = 'analytics_tracking';

    protected $fillable = [
        'session_id',
        'user_id',
        'page_url',
        'page_title',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'ip_address',
        'page_load_time',
        'bounce_rate',
        'conversion',
        'custom_events',
    ];

    protected $casts = [
        'custom_events' => 'array',
        'bounce_rate' => 'boolean',
        'conversion' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for analytics queries
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    public function scopeByDevice($query, $device)
    {
        return $query->where('device_type', $device);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByPage($query, $page)
    {
        return $query->where('page_url', 'like', "%{$page}%");
    }

    public function scopeConversions($query)
    {
        return $query->where('conversion', true);
    }
}
