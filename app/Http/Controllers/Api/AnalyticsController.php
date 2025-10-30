<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService
    ) {}
    
    public function trackPageView(Request $request): JsonResponse
    {
        try {
            $this->analyticsService->trackPageView(
                $request,
                $request->input('page_url'),
                $request->input('page_title')
            );
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function trackScroll(Request $request): JsonResponse
    {
        try {
            $sessionId = $request->input('session_id');
            $scrollDepth = $request->input('scroll_depth');
            $pageUrl = $request->input('page_url');
            
            // Update the latest analytics record with scroll depth
            \App\Models\AnalyticsTracking::where('session_id', $sessionId)
                ->where('page_url', $pageUrl)
                ->latest()
                ->first()
                ?->update(['scroll_depth' => $scrollDepth]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function trackClicks(Request $request): JsonResponse
    {
        try {
            $sessionId = $request->input('session_id');
            $clickedElements = $request->input('clicked_elements', []);
            
            // Update the latest analytics record with clicked elements
            \App\Models\AnalyticsTracking::where('session_id', $sessionId)
                ->latest()
                ->first()
                ?->update(['clicked_elements' => $clickedElements]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function trackFormInteraction(Request $request): JsonResponse
    {
        try {
            $sessionId = $request->input('session_id');
            $formInteraction = $request->input('form_interaction');
            
            // Get existing form interactions and add new one
            $latestRecord = \App\Models\AnalyticsTracking::where('session_id', $sessionId)
                ->latest()
                ->first();
                
            if ($latestRecord) {
                $existingInteractions = $latestRecord->form_interactions ?? [];
                $existingInteractions[] = $formInteraction;
                
                $latestRecord->update(['form_interactions' => $existingInteractions]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function trackTime(Request $request): JsonResponse
    {
        try {
            $sessionId = $request->input('session_id');
            $timeOnPage = $request->input('time_on_page');
            $pageUrl = $request->input('page_url');
            
            // Update the latest analytics record with time on page
            \App\Models\AnalyticsTracking::where('session_id', $sessionId)
                ->where('page_url', $pageUrl)
                ->latest()
                ->first()
                ?->update(['time_on_page' => $timeOnPage]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function trackConversion(Request $request): JsonResponse
    {
        try {
            $sessionId = $request->input('session_id');
            $conversionType = $request->input('conversion_type');
            $conversionValue = $request->input('conversion_value', 0);
            
            $this->analyticsService->trackConversion($sessionId, $conversionType, $conversionValue);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function trackKeyword(Request $request): JsonResponse
    {
        try {
            $keyword = $request->input('keyword');
            $pageUrl = $request->input('page_url');
            $position = $request->input('position');
            
            $this->analyticsService->trackKeyword($keyword, $pageUrl, $position);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function trackCustomEvent(Request $request): JsonResponse
    {
        try {
            $sessionId = $request->input('session_id');
            $eventName = $request->input('event_name');
            $eventData = $request->input('event_data', []);
            $pageUrl = $request->input('page_url');
            
            // Get existing custom events and add new one
            $latestRecord = \App\Models\AnalyticsTracking::where('session_id', $sessionId)
                ->latest()
                ->first();
                
            if ($latestRecord) {
                $existingEvents = $latestRecord->custom_events ?? [];
                $existingEvents[$eventName] = array_merge($existingEvents[$eventName] ?? [], $eventData);
                
                $latestRecord->update(['custom_events' => $existingEvents]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
