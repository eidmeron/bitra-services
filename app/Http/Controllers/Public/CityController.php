<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CityController extends Controller
{
    public function show(City $city): View
    {
        // Load services for this city with related data
        $services = $city->services()
            ->active()
            ->with(['category', 'forms', 'companies' => function($query) {
                $query->where('status', 'active')
                    ->withAvg('reviews', 'company_rating')
                    ->withCount('reviews');
            }])
            ->get();

        // Get companies that offer services in this city
        $companies = \App\Models\Company::where('status', 'active')
            ->whereHas('services', function($query) use ($city) {
                $query->whereHas('cities', function($q) use ($city) {
                    $q->where('cities.id', $city->id);
                });
            })
            ->with(['services' => function($query) use ($city) {
                $query->whereHas('cities', function($q) use ($city) {
                    $q->where('cities.id', $city->id);
                });
            }])
            ->withAvg('reviews', 'company_rating')
            ->withCount('reviews')
            ->take(12)
            ->get();

        // Get categories that have services in this city
        $categories = \App\Models\Category::where('status', 'active')
            ->whereHas('services', function($query) use ($city) {
                $query->active()->whereHas('cities', function($q) use ($city) {
                    $q->where('cities.id', $city->id);
                });
            })
            ->withCount(['services' => function($query) use ($city) {
                $query->active()->whereHas('cities', function($q) use ($city) {
                    $q->where('cities.id', $city->id);
                });
            }])
            ->orderBy('name')
            ->get();

        // SEO data
        $seoTitle = "Tjänster i {$city->name} | Bitra";
        $seoDescription = "Hitta de bästa tjänsterna i {$city->name}. Vi hjälper dig att hitta pålitliga företag för alla dina behov.";
        
        if ($services->count() > 0) {
            $serviceNames = $services->pluck('name')->take(5)->implode(', ');
            $seoDescription = "Boka {$serviceNames} och mer i {$city->name}. Jämför priser och hitta de bästa företagen.";
        }

        return view('public.cities.show', compact(
            'city', 
            'services', 
            'companies', 
            'categories',
            'seoTitle',
            'seoDescription'
        ));
    }
}
