<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\City;
use App\Models\Category;
use App\Models\Company;
use App\Models\PageContent;
use Illuminate\Http\Response;

final class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Main sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-main.xml') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '</sitemap>';
        
        // Services sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-services.xml') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '</sitemap>';
        
        // Cities sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-cities.xml') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '</sitemap>';
        
        // Companies sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-companies.xml') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '</sitemap>';
        
        // Pricing sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-pricing.xml') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '</sitemap>';

        // Search pages sitemap
        $sitemap .= '<sitemap>';
        $sitemap .= '<loc>' . url('/sitemap-search.xml') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '</sitemap>';
        
        $sitemap .= '</sitemapindex>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    public function main(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Homepage
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . url('/') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>';
        
        // Static pages
        $staticPages = [
            'about' => 0.8,
            'how-it-works' => 0.8,
            'why-us' => 0.8,
            'contact' => 0.7,
            'reviews' => 0.9,
        ];
        
        foreach ($staticPages as $page => $priority) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/' . $page) . '</loc>';
            $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>' . $priority . '</priority>';
            $sitemap .= '</url>';
        }
        
        // Page content pages
        $pageContents = PageContent::where('is_active', true)->get();
        foreach ($pageContents as $page) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/' . $page->page_key) . '</loc>';
            $sitemap .= '<lastmod>' . $page->updated_at->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>monthly</changefreq>';
            $sitemap .= '<priority>0.6</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    public function services(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $services = Service::active()->get();
        foreach ($services as $service) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/services/' . $service->slug) . '</loc>';
            $sitemap .= '<lastmod>' . $service->updated_at->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.8</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    public function pricing(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $services = Service::active()->get();
        $cities = City::orderBy('name')->get();

        // /priser
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . url('/priser') . '</loc>';
        $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
        $sitemap .= '<changefreq>weekly</changefreq>';
        $sitemap .= '<priority>0.8</priority>';
        $sitemap .= '</url>';

        // /priser/{service}
        foreach ($services as $service) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/priser/' . $service->slug) . '</loc>';
            $sitemap .= '<lastmod>' . $service->updated_at->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }

        // /priser/{city}/{service}
        foreach ($cities as $city) {
            foreach ($services as $service) {
                $sitemap .= '<url>';
                $sitemap .= '<loc>' . url('/priser/' . $city->slug . '/' . $service->slug) . '</loc>';
                $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
                $sitemap .= '<changefreq>weekly</changefreq>';
                $sitemap .= '<priority>0.6</priority>';
                $sitemap .= '</url>';
            }
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    public function cities(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $cities = City::orderBy('name')->get();
        foreach ($cities as $city) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/' . $city->slug) . '</loc>';
            $sitemap .= '<lastmod>' . $city->updated_at->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    public function companies(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $companies = Company::where('status', 'active')->get();
        foreach ($companies as $company) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . url('/company/' . $company->id) . '</loc>';
            $sitemap .= '<lastmod>' . $company->updated_at->toISOString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.6</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    public function search(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Generate search URLs for all combinations
        $cities = City::orderBy('name')->get();
        $categories = Category::where('status', 'active')->get();
        $services = Service::active()->get();
        
        // City + Category combinations
        foreach ($cities as $city) {
            foreach ($categories as $category) {
                $sitemap .= '<url>';
                $sitemap .= '<loc>' . url('/search?city=' . $city->id . '&category=' . $category->id) . '</loc>';
                $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
                $sitemap .= '<changefreq>daily</changefreq>';
                $sitemap .= '<priority>0.8</priority>';
                $sitemap .= '</url>';
            }
        }
        
        // City + Service combinations
        foreach ($cities as $city) {
            foreach ($services as $service) {
                $sitemap .= '<url>';
                $sitemap .= '<loc>' . url('/search?city=' . $city->id . '&service=' . $service->id) . '</loc>';
                $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
                $sitemap .= '<changefreq>daily</changefreq>';
                $sitemap .= '<priority>0.9</priority>';
                $sitemap .= '</url>';
            }
        }
        
        // Category + Service combinations
        foreach ($categories as $category) {
            foreach ($services as $service) {
                if ($service->category_id == $category->id) {
                    $sitemap .= '<url>';
                    $sitemap .= '<loc>' . url('/search?category=' . $category->id . '&service=' . $service->id) . '</loc>';
                    $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
                    $sitemap .= '<changefreq>daily</changefreq>';
                    $sitemap .= '<priority>0.8</priority>';
                    $sitemap .= '</url>';
                }
            }
        }
        
        // City + Category + Service combinations (top combinations only)
        foreach ($cities->take(20) as $city) {
            foreach ($categories->take(10) as $category) {
                foreach ($services->where('category_id', $category->id)->take(5) as $service) {
                    $sitemap .= '<url>';
                    $sitemap .= '<loc>' . url('/search?city=' . $city->id . '&category=' . $category->id . '&service=' . $service->id) . '</loc>';
                    $sitemap .= '<lastmod>' . now()->toISOString() . '</lastmod>';
                    $sitemap .= '<changefreq>daily</changefreq>';
                    $sitemap .= '<priority>1.0</priority>';
                    $sitemap .= '</url>';
                }
            }
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
