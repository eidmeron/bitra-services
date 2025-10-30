<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AnalyticsService;

final class AnalyticsTrackingMiddleware
{
    public function __construct(
        private readonly AnalyticsService $analyticsService
    ) {}
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only track GET requests to public pages
        if ($request->isMethod('GET') && !$request->is('admin/*')) {
            try {
                $pageUrl = $request->url();
                $pageTitle = $this->extractPageTitle($response);
                
                $this->analyticsService->trackPageView($request, $pageUrl, $pageTitle);
            } catch (\Exception $e) {
                // Silently fail to not break the user experience
                \Log::error('Analytics tracking middleware error: ' . $e->getMessage());
            }
        }
        
        return $response;
    }
    
    private function extractPageTitle(Response $response): ?string
    {
        $content = $response->getContent();
        
        if (preg_match('/<title[^>]*>(.*?)<\/title>/i', $content, $matches)) {
            return trim(strip_tags($matches[1]));
        }
        
        return null;
    }
}
