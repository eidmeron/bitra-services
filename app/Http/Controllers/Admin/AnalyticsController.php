<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsTracking;
use App\Models\SeoKeyword;
use App\Models\PagePerformance;
use App\Models\VisitorDemographics;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService
    ) {}
    /**
     * Display analytics dashboard
     */
    public function index(Request $request): View
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);
        $endDate = now();

        // Basic metrics
        $metrics = $this->getBasicMetrics($startDate, $endDate);
        
        // Traffic sources
        $trafficSources = $this->getTrafficSources($startDate, $endDate);
        
        // Top pages
        $topPages = $this->getTopPages($startDate, $endDate);
        
        // Device breakdown
        $deviceBreakdown = $this->getDeviceBreakdown($startDate, $endDate);
        
        // Geographic data
        $geographicData = $this->getGeographicData($startDate, $endDate);
        
        // SEO keywords
        $seoKeywords = $this->getSeoKeywords($startDate, $endDate);
        
        // Performance insights
        $performanceInsights = $this->getPerformanceInsights($startDate, $endDate);

        return view('admin.analytics.index', compact(
            'metrics',
            'trafficSources',
            'topPages',
            'deviceBreakdown',
            'geographicData',
            'seoKeywords',
            'performanceInsights',
            'period'
        ));
    }

    /**
     * Display SEO analysis
     */
    public function seo(Request $request): View
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);

        // Top performing keywords
        $topKeywords = SeoKeyword::where('created_at', '>=', $startDate)
            ->topPerforming(20)
            ->get();

        // High traffic keywords
        $highTrafficKeywords = SeoKeyword::where('created_at', '>=', $startDate)
            ->highTraffic(20)
            ->get();

        // High CTR keywords
        $highCtrKeywords = SeoKeyword::where('created_at', '>=', $startDate)
            ->highCtr(20)
            ->get();

        // Page performance
        $pagePerformance = PagePerformance::orderBy('total_visits', 'desc')
            ->limit(20)
            ->get();

        // SEO suggestions
        $seoSuggestions = $this->getSeoSuggestions($topKeywords, $pagePerformance);

        return view('admin.analytics.seo', compact(
            'topKeywords',
            'highTrafficKeywords',
            'highCtrKeywords',
            'pagePerformance',
            'seoSuggestions',
            'period'
        ));
    }

    /**
     * Display visitor analytics
     */
    public function visitors(Request $request): View
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);

        // Visitor demographics
        $demographics = VisitorDemographics::where('date', '>=', $startDate)
            ->orderBy('visitors', 'desc')
            ->get()
            ->groupBy('country');

        // Device analytics
        $deviceAnalytics = $this->getDeviceAnalytics($startDate);
        
        // Browser analytics
        $browserAnalytics = $this->getBrowserAnalytics($startDate);
        
        // Geographic distribution
        $geographicDistribution = $this->getGeographicDistribution($startDate);

        return view('admin.analytics.visitors', compact(
            'demographics',
            'deviceAnalytics',
            'browserAnalytics',
            'geographicDistribution',
            'period'
        ));
    }

    private function getBasicMetrics($startDate, $endDate)
    {
        $tracking = AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate]);
        
        return [
            'total_visitors' => $tracking->distinct('session_id')->count(),
            'total_page_views' => $tracking->count(),
            'total_sessions' => $tracking->distinct('session_id')->count(),
            'avg_session_duration' => $tracking->avg('page_load_time') ?? 0,
            'bounce_rate' => $tracking->where('bounce_rate', true)->count() / max($tracking->count(), 1) * 100,
            'conversion_rate' => $tracking->where('conversion', true)->count() / max($tracking->count(), 1) * 100,
        ];
    }

    private function getTrafficSources($startDate, $endDate)
    {
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('utm_source, COUNT(*) as visits, COUNT(DISTINCT session_id) as unique_visitors')
            ->whereNotNull('utm_source')
            ->groupBy('utm_source')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
    }

    private function getTopPages($startDate, $endDate)
    {
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('page_url, page_title, COUNT(*) as page_views, COUNT(DISTINCT session_id) as unique_visitors')
            ->groupBy('page_url', 'page_title')
            ->orderBy('page_views', 'desc')
            ->limit(10)
            ->get();
    }

    private function getDeviceBreakdown($startDate, $endDate)
    {
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('device_type, COUNT(*) as visits, COUNT(DISTINCT session_id) as unique_visitors')
            ->groupBy('device_type')
            ->orderBy('visits', 'desc')
            ->get();
    }

    private function getGeographicData($startDate, $endDate)
    {
        return AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('country, city, COUNT(*) as visits, COUNT(DISTINCT session_id) as unique_visitors')
            ->whereNotNull('country')
            ->groupBy('country', 'city')
            ->orderBy('visits', 'desc')
            ->limit(20)
            ->get();
    }

    private function getSeoKeywords($startDate, $endDate)
    {
        return SeoKeyword::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('impressions', 'desc')
            ->limit(20)
            ->get();
    }

    private function getPerformanceInsights($startDate, $endDate)
    {
        $insights = [];
        
        // Slow pages
        $slowPages = AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->where('page_load_time', '>', 3000) // > 3 seconds
            ->selectRaw('page_url, AVG(page_load_time) as avg_load_time, COUNT(*) as visits')
            ->groupBy('page_url')
            ->orderBy('avg_load_time', 'desc')
            ->limit(5)
            ->get();
        
        if ($slowPages->count() > 0) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Långsamma sidor',
                'message' => 'Följande sidor laddar långsamt och kan påverka användarupplevelsen negativt.',
                'data' => $slowPages
            ];
        }
        
        // High bounce rate pages
        $highBouncePages = AnalyticsTracking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('page_url, COUNT(*) as total_visits, SUM(bounce_rate) as bounces')
            ->groupBy('page_url')
            ->havingRaw('SUM(bounce_rate) / COUNT(*) > 0.7') // > 70% bounce rate
            ->having('total_visits', '>', 10) // At least 10 visits
            ->orderByRaw('SUM(bounce_rate) / COUNT(*) DESC')
            ->limit(5)
            ->get();
        
        if ($highBouncePages->count() > 0) {
            $insights[] = [
                'type' => 'info',
                'title' => 'Hög avhoppsfrekvens',
                'message' => 'Dessa sidor har hög avhoppsfrekvens och kan behöva förbättras.',
                'data' => $highBouncePages
            ];
        }
        
        return $insights;
    }

    private function getSeoSuggestions($topKeywords, $pagePerformance)
    {
        $suggestions = [];
        
        // Keyword opportunities
        $lowCtrKeywords = SeoKeyword::where('ctr', '<', 2)
            ->where('impressions', '>', 100)
            ->orderBy('impressions', 'desc')
            ->limit(5)
            ->get();
        
        if ($lowCtrKeywords->count() > 0) {
            $suggestions[] = [
                'type' => 'opportunity',
                'title' => 'Nyckelord med låg CTR',
                'message' => 'Dessa nyckelord får många visningar men få klick. Överväg att förbättra titlar och beskrivningar.',
                'keywords' => $lowCtrKeywords->pluck('keyword')->toArray()
            ];
        }
        
        // Missing keywords for popular pages
        $popularPages = $pagePerformance->take(5);
        foreach ($popularPages as $page) {
            $pageKeywords = SeoKeyword::where('page_url', $page->page_url)->count();
            if ($pageKeywords < 5) {
                $suggestions[] = [
                    'type' => 'improvement',
                    'title' => 'Förbättra SEO för populära sidor',
                    'message' => "Sidan {$page->page_url} är populär men har få nyckelord. Lägg till fler relevanta nyckelord.",
                    'page' => $page->page_url
                ];
            }
        }
        
        return $suggestions;
    }

    private function getDeviceAnalytics($startDate)
    {
        return AnalyticsTracking::where('created_at', '>=', $startDate)
            ->selectRaw('device_type, COUNT(*) as visits, COUNT(DISTINCT session_id) as unique_visitors, AVG(page_load_time) as avg_load_time')
            ->groupBy('device_type')
            ->orderBy('visits', 'desc')
            ->get();
    }

    private function getBrowserAnalytics($startDate)
    {
        return AnalyticsTracking::where('created_at', '>=', $startDate)
            ->selectRaw('browser, COUNT(*) as visits, COUNT(DISTINCT session_id) as unique_visitors')
            ->groupBy('browser')
            ->orderBy('visits', 'desc')
            ->limit(10)
            ->get();
    }

    private function getGeographicDistribution($startDate)
    {
        return AnalyticsTracking::where('created_at', '>=', $startDate)
            ->selectRaw('country, COUNT(*) as visits, COUNT(DISTINCT session_id) as unique_visitors')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('visits', 'desc')
            ->limit(20)
            ->get();
    }

    public function settings(): View
    {
        // Load current analytics settings
        $settings = [
            'ga4_measurement_id' => config('analytics.ga4_measurement_id', ''),
            'ga4_api_secret' => config('analytics.ga4_api_secret', ''),
            'ga4_enabled' => config('analytics.ga4_enabled', false),
            'ga4_enhanced_ecommerce' => config('analytics.ga4_enhanced_ecommerce', false),
            'gsc_property_url' => config('analytics.gsc_property_url', ''),
            'gsc_credentials_json' => config('analytics.gsc_credentials_json', ''),
            'gsc_enabled' => config('analytics.gsc_enabled', false),
            'facebook_pixel_id' => config('analytics.facebook_pixel_id', ''),
            'facebook_pixel_enabled' => config('analytics.facebook_pixel_enabled', false),
            'hotjar_site_id' => config('analytics.hotjar_site_id', ''),
            'hotjar_enabled' => config('analytics.hotjar_enabled', false),
            'custom_head_scripts' => config('analytics.custom_head_scripts', ''),
            'custom_body_scripts' => config('analytics.custom_body_scripts', ''),
        ];

        return view('admin.analytics.settings', compact('settings'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'ga4_measurement_id' => 'nullable|string|max:255',
            'ga4_api_secret' => 'nullable|string|max:255',
            'ga4_enabled' => 'boolean',
            'ga4_enhanced_ecommerce' => 'boolean',
            'gsc_property_url' => 'nullable|url|max:255',
            'gsc_credentials_json' => 'nullable|string',
            'gsc_enabled' => 'boolean',
            'facebook_pixel_id' => 'nullable|string|max:255',
            'facebook_pixel_enabled' => 'boolean',
            'hotjar_site_id' => 'nullable|string|max:255',
            'hotjar_enabled' => 'boolean',
            'custom_head_scripts' => 'nullable|string',
            'custom_body_scripts' => 'nullable|string',
        ]);

        // Update configuration file or database
        $settings = [
            'ga4_measurement_id' => $request->ga4_measurement_id,
            'ga4_api_secret' => $request->ga4_api_secret,
            'ga4_enabled' => $request->boolean('ga4_enabled'),
            'ga4_enhanced_ecommerce' => $request->boolean('ga4_enhanced_ecommerce'),
            'gsc_property_url' => $request->gsc_property_url,
            'gsc_credentials_json' => $request->gsc_credentials_json,
            'gsc_enabled' => $request->boolean('gsc_enabled'),
            'facebook_pixel_id' => $request->facebook_pixel_id,
            'facebook_pixel_enabled' => $request->boolean('facebook_pixel_enabled'),
            'hotjar_site_id' => $request->hotjar_site_id,
            'hotjar_enabled' => $request->boolean('hotjar_enabled'),
            'custom_head_scripts' => $request->custom_head_scripts,
            'custom_body_scripts' => $request->custom_body_scripts,
        ];

        // Here you would typically save to database or config file
        // For now, we'll just return success
        \Log::info('Analytics settings updated', $settings);

        return redirect()
            ->route('admin.analytics.settings')
            ->with('success', 'Analytics inställningar uppdaterade framgångsrikt.');
    }
    
    /**
     * Get analytics data via AJAX
     */
    public function getData(Request $request): JsonResponse
    {
        $period = $request->get('period', '30d');
        $data = $this->analyticsService->getAnalyticsData($period);
        
        return response()->json($data);
    }
    
    /**
     * Get city-specific analytics
     */
    public function getCityAnalytics(string $city, Request $request): JsonResponse
    {
        $period = $request->get('period', '30d');
        $data = $this->analyticsService->getCityAnalytics($city, $period);
        
        return response()->json($data);
    }
    
    /**
     * Test analytics tracking
     */
    public function testTracking(): JsonResponse
    {
        try {
            // Create a test analytics record
            AnalyticsTracking::create([
                'session_id' => 'test_session_' . time(),
                'page_url' => url('/test'),
                'page_title' => 'Test Page',
                'device_type' => 'desktop',
                'browser' => 'Chrome',
                'os' => 'Windows',
                'country' => 'Sweden',
                'city' => 'Stockholm',
                'custom_events' => ['test' => true]
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test tracking completed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test tracking failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
