<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'page_url',
        'impressions',
        'clicks',
        'ctr',
        'position',
        'conversion_rate',
        'conversions',
    ];

    protected $casts = [
        'ctr' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
    ];

    // Scopes for SEO analysis
    public function scopeTopPerforming($query, $limit = 10)
    {
        return $query->orderBy('conversions', 'desc')
                    ->orderBy('ctr', 'desc')
                    ->limit($limit);
    }

    public function scopeHighTraffic($query, $limit = 10)
    {
        return $query->orderBy('impressions', 'desc')
                    ->limit($limit);
    }

    public function scopeHighCtr($query, $limit = 10)
    {
        return $query->where('ctr', '>', 0)
                    ->orderBy('ctr', 'desc')
                    ->limit($limit);
    }

    public function scopeByPage($query, $page)
    {
        return $query->where('page_url', $page);
    }

    public function scopeByKeyword($query, $keyword)
    {
        return $query->where('keyword', 'like', "%{$keyword}%");
    }

    // Calculate performance metrics
    public function getPerformanceScoreAttribute()
    {
        $score = 0;
        
        // CTR weight (40%)
        if ($this->ctr > 0) {
            $score += min($this->ctr * 4, 40);
        }
        
        // Conversion rate weight (30%)
        if ($this->conversion_rate > 0) {
            $score += min($this->conversion_rate * 3, 30);
        }
        
        // Position weight (20%) - lower position is better
        if ($this->position && $this->position <= 10) {
            $score += (11 - $this->position) * 2;
        }
        
        // Traffic weight (10%)
        if ($this->impressions > 100) {
            $score += min(10, $this->impressions / 100);
        }
        
        return round($score, 1);
    }
}
