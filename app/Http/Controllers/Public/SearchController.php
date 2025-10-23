<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\City;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchController extends Controller
{
    public function index(Request $request): View
    {
        // Get selected filters and cast to integers
        $selectedService = $request->input('service') ? (int) $request->input('service') : null;
        $selectedCity = $request->input('city') ? (int) $request->input('city') : null;
        $selectedCategory = $request->input('category') ? (int) $request->input('category') : null;
        
        // Get all options for filters
        $allServices = Service::active()->orderBy('name')->get();
        $allCities = City::orderBy('name')->get();
        $allCategories = Category::where('status', 'active')->orderBy('name')->get();
        
        // Get filtered services based on selection order: City -> Category -> Service
        $services = $this->getFilteredServices($selectedCity, $selectedCategory, $selectedService);
        
        // Get companies matching the criteria
        $companies = $this->getFilteredCompanies($selectedCity, $selectedCategory, $selectedService);
        
        // Get categories available in the selected city (for city-only search)
        $availableCategories = $this->getAvailableCategories($selectedCity);
        
        // Get selected models for display
        $city = $selectedCity ? City::find($selectedCity) : null;
        $category = $selectedCategory ? Category::find($selectedCategory) : null;
        $service = $selectedService ? Service::find($selectedService) : null;
        
        // Generate dynamic SEO title
        $seoTitle = $this->generateSeoTitle($city, $category, $service);
        $seoDescription = $this->generateSeoDescription($city, $category, $service);
        
        return view('public.search.index', compact(
            'services', 
            'companies',
            'availableCategories',
            'allServices', 
            'allCities', 
            'allCategories',
            'selectedService',
            'selectedCity',
            'selectedCategory',
            'city',
            'category',
            'service',
            'seoTitle',
            'seoDescription'
        ));
    }
    
    private function getFilteredServices(?int $cityId, ?int $categoryId, ?int $serviceId)
    {
        $query = Service::query()->active();
        
        // If specific service is selected, show only that service
        if ($serviceId) {
            $query->where('id', $serviceId);
        } else {
            // Filter by city and category
            if ($cityId) {
                $query->whereHas('cities', function($q) use ($cityId) {
                    $q->where('cities.id', $cityId);
                });
            }
            
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
        }
        
        return $query->with(['category', 'cities', 'forms'])->get();
    }
    
    private function getAvailableCategories(?int $cityId)
    {
        if (!$cityId) {
            return collect();
        }
        
        return Category::where('status', 'active')
            ->whereHas('services', function($query) use ($cityId) {
                $query->where('status', 'active')
                    ->whereHas('cities', function($q) use ($cityId) {
                        $q->where('cities.id', $cityId);
                    });
            })
            ->withCount(['services' => function($query) use ($cityId) {
                $query->where('status', 'active')
                    ->whereHas('cities', function($q) use ($cityId) {
                        $q->where('cities.id', $cityId);
                    });
            }])
            ->orderBy('name')
            ->get();
    }
    
    private function getFilteredCompanies(?int $cityId, ?int $categoryId, ?int $serviceId)
    {
        $query = Company::where('status', 'active');
        
        if ($serviceId) {
            $query->whereHas('services', function($q) use ($serviceId) {
                $q->where('services.id', $serviceId);
            });
        } elseif ($categoryId) {
            $query->whereHas('services', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        
        if ($cityId) {
            $query->whereHas('cities', function($q) use ($cityId) {
                $q->where('cities.id', $cityId);
            });
        }
        
        return $query
            ->with(['cities', 'services', 'user'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->get();
    }
    
    private function generateSeoTitle(?City $city, ?Category $category, ?Service $service): string
    {
        $baseTitle = 'Hitta professionella tjänster';
        
        if ($service && $city) {
            return "{$service->name} i {$city->name} | {$baseTitle}";
        } elseif ($service) {
            return "{$service->name} | {$baseTitle}";
        } elseif ($category && $city) {
            return "{$category->name} i {$city->name} | {$baseTitle}";
        } elseif ($category) {
            return "{$category->name} | {$baseTitle}";
        } elseif ($city) {
            return "Tjänster i {$city->name} | {$baseTitle}";
        }
        
        return $baseTitle . ' i hela Sverige';
    }
    
    private function generateSeoDescription(?City $city, ?Category $category, ?Service $service): string
    {
        $baseDescription = 'Hitta och jämför professionella tjänster från verifierade företag.';
        
        if ($service && $city) {
            return "Boka {$service->name} i {$city->name}. {$baseDescription} Få offerter från lokala experter och spara pengar.";
        } elseif ($service) {
            return "Boka {$service->name} från Sveriges bästa företag. {$baseDescription} Jämför priser och recensioner.";
        } elseif ($category && $city) {
            return "Hitta {$category->name} i {$city->name}. {$baseDescription} Professionella företag med hög kvalitet.";
        } elseif ($category) {
            return "Hitta {$category->name} från verifierade företag. {$baseDescription} Jämför priser och boka enkelt.";
        } elseif ($city) {
            return "Hitta professionella tjänster i {$city->name}. {$baseDescription} Lokala experter med hög kvalitet.";
        }
        
        return $baseDescription . ' Jämför priser och boka enkelt online.';
    }
}
