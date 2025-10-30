<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagePerformance extends Model
{
    use HasFactory;

    protected $table = 'page_performance';

    protected $fillable = [
        'page_url',
        'page_title',
        'avg_load_time',
        'total_visits',
        'unique_visitors',
        'bounce_rate',
        'conversion_rate',
        'total_conversions',
        'top_keywords',
        'traffic_sources',
    ];

    protected $casts = [
        'avg_load_time' => 'integer',
        'total_visits' => 'integer',
        'unique_visitors' => 'integer',
        'bounce_rate' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'total_conversions' => 'integer',
        'top_keywords' => 'array',
        'traffic_sources' => 'array',
    ];

    // Scopes
    public function scopeTopPerforming($query, $limit = 10)
    {
        return $query->orderBy('total_visits', 'desc')
                    ->orderBy('conversion_rate', 'desc')
                    ->limit($limit);
    }

    public function scopeSlowPages($query, $threshold = 3000)
    {
        return $query->where('avg_load_time', '>', $threshold)
                    ->orderBy('avg_load_time', 'desc');
    }

    public function scopeHighBounceRate($query, $threshold = 70)
    {
        return $query->where('bounce_rate', '>', $threshold)
                    ->orderBy('bounce_rate', 'desc');
    }
}
