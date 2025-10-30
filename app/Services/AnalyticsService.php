<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AnalyticsTracking;
use App\Models\SeoKeyword;
use App\Models\PagePerformance;
use App\Models\VisitorDemographics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

final class AnalyticsService
{
    public function trackPageView(Request $request, string $pageUrl, string $pageTitle = null): void
    {
        try {
            $sessionId = $request->session()->getId();
            $userAgent = $request->userAgent();
            $ipAddress = $request->ip();
            
            // Get device info
            $deviceInfo = $this->parseUserAgent($userAgent);
            $locationInfo = $this->getLocationInfo($ipAddress);
            
            // Track the page view
            AnalyticsTracking::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'page_url' => $pageUrl,
                'page_title' => $pageTitle,
                'referrer' => $request->header('referer'),
                'user_agent' => $userAgent,
                'ip_address' => $ipAddress,
                'country' => $locationInfo['country'],
                'city' => $locationInfo['city'],
                'device_type' => $deviceInfo['device_type'],
                'browser' => $deviceInfo['browser'],
                'os' => $deviceInfo['os'],
                'utm_source' => $request->get('utm_source'),
                'utm_medium' => $request->get('utm_medium'),
                'utm_campaign' => $request->get('utm_campaign'),
                'utm_term' => $request->get('utm_term'),
                'utm_content' => $request->get('utm_content'),
                'custom_events' => $this->extractCustomEvents($request),
            ]);
            
            // Update visitor demographics
            $this->updateVisitorDemographics($sessionId, $locationInfo, $deviceInfo);
            
        } catch (\Exception $e) {
            Log::error('Analytics tracking error: ' . $e->getMessage());
        }
    }
    
    public function trackConversion(string $sessionId, string $conversionType, float $conversionValue = 0): void
    {
        try {
            AnalyticsTracking::where('session_id', $sessionId)
                ->latest()
                ->first()
                ?->update([
                    'conversion' => 1,
                    'custom_events' => DB::raw("JSON_SET(COALESCE(custom_events, '{}'), '$.conversion_type', '$conversionType', '$.conversion_value', $conversionValue)")
                ]);
                
        } catch (\Exception $e) {
            Log::error('Conversion tracking error: ' . $e->getMessage());
        }
    }
    
    public function trackKeyword(string $keyword, string $pageUrl, int $position = null, int $clicks = 1, int $impressions = 1): void
    {
        try {
            $ctr = $impressions > 0 ? ($clicks / $impressions) * 100 : 0;
            
            SeoKeyword::updateOrCreate(
                ['keyword' => $keyword, 'page_url' => $pageUrl],
                [
                    'impressions' => DB::raw('impressions + ' . $impressions),
                    'clicks' => DB::raw('clicks + ' . $clicks),
                    'ctr' => $ctr,
                    'position' => $position,
                ]
            );
            
        } catch (\Exception $e) {
            Log::error('Keyword tracking error: ' . $e->getMessage());
        }
    }
    
    public function updatePagePerformance(string $pageUrl, string $pageTitle, array $performanceData): void
    {
        try {
            PagePerformance::updateOrCreate(
                ['page_url' => $pageUrl],
                [
                    'page_title' => $pageTitle,
                    'avg_load_time' => $performanceData['load_time'] ?? 0,
                    'total_visits' => DB::raw('total_visits + 1'),
                    'bounce_rate' => $performanceData['bounce_rate'] ?? 0,
                    'conversion_rate' => $performanceData['conversion_rate'] ?? 0,
                    'total_conversions' => $performanceData['conversions'] ?? 0,
                    'top_keywords' => $performanceData['top_keywords'] ?? null,
                    'traffic_sources' => $performanceData['traffic_sources'] ?? null,
                ]
            );
            
        } catch (\Exception $e) {
            Log::error('Page performance update error: ' . $e->getMessage());
        }
    }
    
    public function getAnalyticsData(string $period = '30d'): array
    {
        $dateRange = $this->getDateRange($period);
        
        return [
            'overview' => $this->getOverviewData($dateRange),
            'traffic_sources' => $this->getTrafficSources($dateRange),
            'top_pages' => $this->getTopPages($dateRange),
            'device_breakdown' => $this->getDeviceBreakdown($dateRange),
            'geographic_data' => $this->getGeographicData($dateRange),
            'seo_keywords' => $this->getSeoKeywords($dateRange),
            'performance_insights' => $this->getPerformanceInsights($dateRange),
        ];
    }
    
    public function getCityAnalytics(string $city, string $period = '30d'): array
    {
        $dateRange = $this->getDateRange($period);
        
        return [
            'city' => $city,
            'visitors' => $this->getCityVisitors($city, $dateRange),
            'top_services' => $this->getCityTopServices($city, $dateRange),
            'conversions' => $this->getCityConversions($city, $dateRange),
            'keywords' => $this->getCityKeywords($city, $dateRange),
        ];
    }
    
    public function getSeoAnalysis(string $pageUrl = null): array
    {
        $query = PagePerformance::query();
        
        if ($pageUrl) {
            $query->where('page_url', $pageUrl);
        }
        
        $pages = $query->get();
        
        return [
            'pages' => $pages->map(function ($page) {
                return [
                    'url' => $page->page_url,
                    'title' => $page->page_title,
                    'load_time' => $page->avg_load_time,
                    'bounce_rate' => $page->bounce_rate,
                    'conversion_rate' => $page->conversion_rate,
                    'seo_score' => $this->calculateSeoScore($page),
                    'issues' => $this->getSeoIssues($page),
                ];
            }),
            'recommendations' => $this->getSeoRecommendations($pages),
        ];
    }
    
    private function parseUserAgent(string $userAgent): array
    {
        // Simple user agent parsing - in production, use a proper library
        $deviceType = 'desktop';
        $browser = 'Unknown';
        $os = 'Unknown';
        
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            $deviceType = 'mobile';
        } elseif (preg_match('/Tablet|iPad/', $userAgent)) {
            $deviceType = 'tablet';
        }
        
        if (preg_match('/Chrome\/(\d+)/', $userAgent, $matches)) {
            $browser = 'Chrome ' . $matches[1];
        } elseif (preg_match('/Firefox\/(\d+)/', $userAgent, $matches)) {
            $browser = 'Firefox ' . $matches[1];
        } elseif (preg_match('/Safari\/(\d+)/', $userAgent, $matches)) {
            $browser = 'Safari ' . $matches[1];
        }
        
        if (preg_match('/Windows NT (\d+)/', $userAgent, $matches)) {
            $os = 'Windows ' . $matches[1];
        } elseif (preg_match('/Mac OS X (\d+)/', $userAgent, $matches)) {
            $os = 'macOS ' . $matches[1];
        } elseif (preg_match('/Linux/', $userAgent)) {
            $os = 'Linux';
        }
        
        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'os' => $os,
        ];
    }
    
    private function getLocationInfo(string $ipAddress): array
    {
        // Simple IP geolocation - in production, use a proper service
        return [
            'country' => 'Sweden', // Default for now
            'city' => 'Stockholm',
        ];
    }
    
    private function extractCustomEvents(Request $request): array
    {
        $events = [];
        
        // Extract UTM parameters
        if ($request->has('utm_source')) {
            $events['utm_source'] = $request->get('utm_source');
        }
        
        return $events;
    }
    
    private function updateVisitorDemographics(string $sessionId, array $locationInfo, array $deviceInfo): void
    {
        $today = Carbon::today();
        
        VisitorDemographics::updateOrCreate(
            [
                'date' => $today,
                'country' => $locationInfo['country'],
                'city' => $locationInfo['city'],
                'device_type' => $deviceInfo['device_type'],
                'browser' => $deviceInfo['browser'],
                'os' => $deviceInfo['os'],
            ],
            [
                'visitors' => DB::raw('visitors + 1'),
                'sessions' => DB::raw('sessions + 1'),
                'page_views' => DB::raw('page_views + 1'),
            ]
        );
    }
    
    private function getDateRange(string $period): array
    {
        $endDate = Carbon::now();
        
        switch ($period) {
            case '7d':
                $startDate = $endDate->copy()->subDays(7);
                break;
            case '30d':
                $startDate = $endDate->copy()->subDays(30);
                break;
            case '90d':
                $startDate = $endDate->copy()->subDays(90);
                break;
            default:
                $startDate = $endDate->copy()->subDays(30);
        }
        
        return [$startDate, $endDate];
    }
    
    private function getOverviewData(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        $totalVisitors = AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('session_id')
            ->count();
            
        $totalPageViews = AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $conversions = AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->where('conversion', 1)
            ->count();
            
        $bounceRate = $this->calculateBounceRate($startDate, $endDate);
        
        return [
            'total_visitors' => $totalVisitors,
            'total_page_views' => $totalPageViews,
            'conversions' => $conversions,
            'bounce_rate' => $bounceRate,
            'conversion_rate' => $totalVisitors > 0 ? ($conversions / $totalVisitors) * 100 : 0,
        ];
    }
    
    private function getTrafficSources(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->select('utm_source', DB::raw('count(*) as visits'))
            ->whereNotNull('utm_source')
            ->groupBy('utm_source')
            ->orderBy('visits', 'desc')
            ->get()
            ->toArray();
    }
    
    private function getTopPages(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->select('page_url', 'page_title', DB::raw('count(*) as visits'))
            ->groupBy('page_url', 'page_title')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }
    
    private function getDeviceBreakdown(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->select('device_type', DB::raw('count(*) as visits'))
            ->groupBy('device_type')
            ->orderBy('visits', 'desc')
            ->get()
            ->toArray();
    }
    
    private function getGeographicData(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->select('country', 'city', DB::raw('count(*) as visits'))
            ->whereNotNull('country')
            ->groupBy('country', 'city')
            ->orderBy('visits', 'desc')
            ->get()
            ->toArray();
    }
    
    private function getSeoKeywords(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return SeoKeyword::whereBetween('created_at', [$startDate, $endDate])
            ->select('keyword', 'page_url', 'clicks', 'impressions', 'ctr', 'position')
            ->orderBy('clicks', 'desc')
            ->limit(20)
            ->get()
            ->toArray();
    }
    
    private function getPerformanceInsights(array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return PagePerformance::whereBetween('created_at', [$startDate, $endDate])
            ->select('page_url', 'page_title', 'avg_load_time', 'bounce_rate', 'conversion_rate')
            ->orderBy('avg_load_time', 'asc')
            ->get()
            ->toArray();
    }
    
    private function getCityVisitors(string $city, array $dateRange): int
    {
        [$startDate, $endDate] = $dateRange;
        
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->where('city', $city)
            ->distinct('session_id')
            ->count();
    }
    
    private function getCityTopServices(string $city, array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->where('city', $city)
            ->select('page_url', DB::raw('count(*) as visits'))
            ->where('page_url', 'like', '%/services/%')
            ->groupBy('page_url')
            ->orderBy('visits', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }
    
    private function getCityConversions(string $city, array $dateRange): int
    {
        [$startDate, $endDate] = $dateRange;
        
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->where('city', $city)
            ->where('conversion', 1)
            ->count();
    }
    
    private function getCityKeywords(string $city, array $dateRange): array
    {
        [$startDate, $endDate] = $dateRange;
        
        return SeoKeyword::whereBetween('created_at', [$startDate, $endDate])
            ->where('page_url', 'like', "%/$city/%")
            ->select('keyword', 'clicks', 'ctr', 'position')
            ->orderBy('clicks', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }
    
    private function calculateBounceRate(Carbon $startDate, Carbon $endDate): float
    {
        $totalSessions = AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('session_id')
            ->count();
            
        $bouncedSessions = AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->where('bounce_rate', 1)
            ->distinct('session_id')
            ->count();
            
        return $totalSessions > 0 ? ($bouncedSessions / $totalSessions) * 100 : 0;
    }
    
    private function calculateSeoScore(PagePerformance $page): int
    {
        $score = 100;
        
        // Deduct points for slow loading
        if ($page->avg_load_time > 3000) $score -= 20;
        elseif ($page->avg_load_time > 2000) $score -= 10;
        
        // Deduct points for high bounce rate
        if ($page->bounce_rate > 70) $score -= 15;
        elseif ($page->bounce_rate > 50) $score -= 10;
        
        // Deduct points for low conversion rate
        if ($page->conversion_rate < 1) $score -= 10;
        
        return max(0, $score);
    }
    
    private function getSeoIssues(PagePerformance $page): array
    {
        $issues = [];
        
        if ($page->avg_load_time > 3000) {
            $issues[] = 'Page loads too slowly (>3s)';
        }
        
        if ($page->bounce_rate > 70) {
            $issues[] = 'High bounce rate (>70%)';
        }
        
        if ($page->conversion_rate < 1) {
            $issues[] = 'Low conversion rate (<1%)';
        }
        
        return $issues;
    }
    
    private function getSeoRecommendations($pages): array
    {
        $recommendations = [];
        
        $slowPages = $pages->where('avg_load_time', '>', 2000);
        if ($slowPages->count() > 0) {
            $recommendations[] = 'Optimize page loading speed for better user experience';
        }
        
        $highBouncePages = $pages->where('bounce_rate', '>', 60);
        if ($highBouncePages->count() > 0) {
            $recommendations[] = 'Improve content quality and user engagement to reduce bounce rate';
        }
        
        $lowConversionPages = $pages->where('conversion_rate', '<', 2);
        if ($lowConversionPages->count() > 0) {
            $recommendations[] = 'Optimize call-to-action buttons and conversion funnels';
        }
        
        return $recommendations;
    }
}
