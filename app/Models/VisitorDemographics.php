<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorDemographics extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'visitors',
        'sessions',
        'page_views',
        'avg_session_duration',
        'bounce_rate',
    ];

    protected $casts = [
        'date' => 'date',
        'visitors' => 'integer',
        'sessions' => 'integer',
        'page_views' => 'integer',
        'avg_session_duration' => 'decimal:2',
        'bounce_rate' => 'decimal:2',
    ];

    // Scopes
    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByDevice($query, $device)
    {
        return $query->where('device_type', $device);
    }

    public function scopeTopCountries($query, $limit = 10)
    {
        return $query->selectRaw('country, SUM(visitors) as total_visitors, SUM(sessions) as total_sessions')
                    ->groupBy('country')
                    ->orderBy('total_visitors', 'desc')
                    ->limit($limit);
    }

    public function scopeTopDevices($query, $limit = 5)
    {
        return $query->selectRaw('device_type, SUM(visitors) as total_visitors, SUM(sessions) as total_sessions')
                    ->groupBy('device_type')
                    ->orderBy('total_visitors', 'desc')
                    ->limit($limit);
    }
}
