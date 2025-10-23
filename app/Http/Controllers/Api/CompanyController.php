<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CompanyController extends Controller
{
    /**
     * Get available companies for a service and city
     */
    public function getAvailableCompanies(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
        ]);

        // Get companies that:
        // 1. Are active/approved
        // 2. Provide the requested service
        // 3. Serve the requested city
        // 4. Are sorted by average rating (highest first)
        $companies = Company::where('status', 'active')
            ->whereHas('services', function ($query) use ($validated) {
                $query->where('services.id', $validated['service_id']);
            })
            ->whereHas('cities', function ($query) use ($validated) {
                $query->where('cities.id', $validated['city_id']);
            })
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['reviews' => function ($query) {
                $query->where('status', 'approved')
                    ->latest()
                    ->limit(3);
            }])
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->company_name,
                    'logo' => $company->logo ? \Storage::url($company->logo) : null,
                    'rating' => round($company->reviews_avg_rating ?? 0, 1),
                    'review_count' => $company->reviews_count ?? 0,
                    'description' => $company->description,
                    'phone' => $company->phone,
                    'recent_reviews' => $company->reviews->map(function ($review) {
                        return [
                            'rating' => $review->rating,
                            'comment' => \Str::limit($review->review_text, 100),
                            'user_name' => $review->booking->user->name ?? 'Anonym',
                            'created_at' => $review->created_at->diffForHumans(),
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'companies' => $companies,
            'count' => $companies->count(),
        ]);
    }
}
