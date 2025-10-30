<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\City;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdvancedSearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $limit = min((int) $request->input('limit', 10), 20); // Max 20 results
        
        if (strlen($query) < 2) {
            return response()->json([
                'cities' => [],
                'services' => [],
                'categories' => [],
                'companies' => [],
                'suggestions' => []
            ]);
        }

        $searchTerm = strtolower(trim($query));
        
        // Search cities
        $cities = City::where('status', 'active')
            ->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$searchTerm}%"]);
            })
            ->withCount(['services', 'companies'])
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(function($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->name,
                    'slug' => $city->slug,
                    'type' => 'city',
                    'url' => route('public.city.show', $city->slug),
                    'description' => "Stad med {$city->services_count} tjänster och {$city->companies_count} företag",
                    'icon' => '📍'
                ];
            });

        // Search services
        $services = Service::where('status', 'active')
            ->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$searchTerm}%"]);
            })
            ->with(['category', 'cities'])
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(function($service) {
                $cities = $service->cities->pluck('name')->take(3)->join(', ');
                $citiesText = $cities ? " i {$cities}" : '';
                if ($service->cities->count() > 3) {
                    $citiesText .= " och " . ($service->cities->count() - 3) . " fler städer";
                }
                
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'slug' => $service->slug,
                    'type' => 'service',
                    'url' => route('public.service.show', $service->slug),
                    'description' => ($service->category->name ?? 'Tjänst') . $citiesText,
                    'icon' => '🛠️',
                    'price' => $service->base_price ? number_format((float)$service->base_price, 0, ',', ' ') . ' kr' : null
                ];
            });

        // Search categories
        $categories = Category::where('status', 'active')
            ->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$searchTerm}%"]);
            })
            ->withCount('services')
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'type' => 'category',
                    'url' => route('public.category.show', $category->slug),
                    'description' => "Kategori med {$category->services_count} tjänster",
                    'icon' => '📂'
                ];
            });

        // Search companies
        $companies = Company::where('status', 'active')
            ->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(company_name) LIKE ?', ["%{$searchTerm}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"]);
            })
            ->with(['cities', 'services'])
            ->withAvg('reviews', 'company_rating')
            ->withCount('reviews')
            ->orderBy('company_name')
            ->limit($limit)
            ->get()
            ->map(function($company) {
                $cities = $company->cities->pluck('name')->take(2)->join(', ');
                $citiesText = $cities ? " i {$cities}" : '';
                if ($company->cities->count() > 2) {
                    $citiesText .= " och " . ($company->cities->count() - 2) . " fler städer";
                }
                
                return [
                    'id' => $company->id,
                    'name' => $company->company_name,
                    'type' => 'company',
                    'url' => route('public.company.show', $company->id),
                    'description' => "Företag{$citiesText}",
                    'icon' => '🏢',
                    'rating' => $company->reviews_avg_company_rating ? number_format((float)$company->reviews_avg_company_rating, 1) : null,
                    'reviews_count' => $company->reviews_count
                ];
            });

        // Generate smart suggestions based on search patterns
        $suggestions = $this->generateSmartSuggestions($searchTerm, $cities, $services, $categories);

        return response()->json([
            'cities' => $cities,
            'services' => $services,
            'categories' => $categories,
            'companies' => $companies,
            'suggestions' => $suggestions,
            'total_results' => $cities->count() + $services->count() + $categories->count() + $companies->count()
        ]);
    }

    public function autocomplete(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $limit = min((int) $request->input('limit', 5), 10);
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $searchTerm = strtolower(trim($query));
        $results = collect();

        // Get popular searches first
        $popularSearches = $this->getPopularSearches($searchTerm, $limit);
        $results = $results->merge($popularSearches);

        // If we need more results, get from database
        if ($results->count() < $limit) {
            $remaining = $limit - $results->count();
            
            // Search services
            $services = Service::where('status', 'active')
                ->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                ->orderBy('name')
                ->limit($remaining)
                ->get()
                ->map(function($service) {
                    return [
                        'text' => $service->name,
                        'type' => 'service',
                        'url' => route('public.service.show', $service->slug)
                    ];
                });
            
            $results = $results->merge($services);
        }

        return response()->json($results->take($limit)->values());
    }

    private function generateSmartSuggestions(string $searchTerm, $cities, $services, $categories): array
    {
        $suggestions = [];
        
        // If user searches for a service + city combination
        if ($cities->count() > 0 && $services->count() > 0) {
            foreach ($cities->take(2) as $city) {
                foreach ($services->take(2) as $service) {
                    $suggestions[] = [
                        'text' => "{$service['name']} i {$city['name']}",
                        'type' => 'city-service',
                        'url' => route('public.city-service.landing', [
                            'city' => $city['slug'],
                            'service' => $service['slug']
                        ]),
                        'icon' => '🎯'
                    ];
                }
            }
        }
        
        // If user searches for a category + city combination
        if ($cities->count() > 0 && $categories->count() > 0) {
            foreach ($cities->take(2) as $city) {
                foreach ($categories->take(2) as $category) {
                    $suggestions[] = [
                        'text' => "{$category['name']} i {$city['name']}",
                        'type' => 'category-city',
                        'url' => route('public.search', ['city' => $city['id'], 'category' => $category['id']]),
                        'icon' => '🏷️'
                    ];
                }
            }
        }

        return array_slice($suggestions, 0, 4); // Max 4 suggestions
    }

    private function getPopularSearches(string $searchTerm, int $limit): array
    {
        // Define popular search combinations
        $popularSearches = [
            'hemstädning' => [
                ['text' => 'Hemstädning Stockholm', 'type' => 'popular', 'url' => '/search?q=hemstädning+stockholm'],
                ['text' => 'Hemstädning Göteborg', 'type' => 'popular', 'url' => '/search?q=hemstädning+göteborg'],
                ['text' => 'Hemstädning Malmö', 'type' => 'popular', 'url' => '/search?q=hemstädning+malmö']
            ],
            'flyttstädning' => [
                ['text' => 'Flyttstädning Stockholm', 'type' => 'popular', 'url' => '/search?q=flyttstädning+stockholm'],
                ['text' => 'Flyttstädning Göteborg', 'type' => 'popular', 'url' => '/search?q=flyttstädning+göteborg']
            ],
            'trädgårdsarbete' => [
                ['text' => 'Trädgårdsarbete Stockholm', 'type' => 'popular', 'url' => '/search?q=trädgårdsarbete+stockholm'],
                ['text' => 'Trädgårdsarbete Göteborg', 'type' => 'popular', 'url' => '/search?q=trädgårdsarbete+göteborg']
            ],
            'måleri' => [
                ['text' => 'Måleri Stockholm', 'type' => 'popular', 'url' => '/search?q=måleri+stockholm'],
                ['text' => 'Måleri Göteborg', 'type' => 'popular', 'url' => '/search?q=måleri+göteborg']
            ]
        ];

        // Find matching popular searches
        foreach ($popularSearches as $key => $searches) {
            if (strpos($searchTerm, $key) !== false) {
                return array_slice($searches, 0, $limit);
            }
        }

        return [];
    }
}
