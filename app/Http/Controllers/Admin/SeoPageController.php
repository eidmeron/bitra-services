<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use App\Models\Service;
use App\Models\Category;
use App\Models\City;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

final class SeoPageController extends Controller
{
    public function index(Request $request)
    {
        $query = SeoPage::with(['service', 'category', 'city', 'zone']);
        
        if ($request->filled('page_type')) {
            $query->where('page_type', $request->page_type);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('meta_title', 'like', "%{$search}%")
                  ->orWhere('h1_title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        $seoPages = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.seo-pages.index', compact('seoPages'));
    }

    public function create()
    {
        $services = Service::active()->orderBy('name')->get();
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $zones = Zone::orderBy('name')->get();
        
        return view('admin.seo-pages.create', compact('services', 'categories', 'cities', 'zones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_type' => 'required|in:service,category,city,zone,city_service,category_service',
            'service_id' => 'nullable|exists:services,id',
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'zone_id' => 'nullable|exists:zones,id',
            'slug' => 'required|string|unique:seo_pages,slug',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'h1_title' => 'nullable|string|max:255',
            'hero_text' => 'nullable|string',
            'content' => 'nullable|string',
            'features' => 'nullable|array',
            'faq' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('seo-pages', 'public');
        }

        $seoPage = SeoPage::create($validated);

        return redirect()
            ->route('admin.seo-pages.index')
            ->with('success', 'SEO Page created successfully!');
    }

    public function edit(SeoPage $seoPage)
    {
        $services = Service::active()->orderBy('name')->get();
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $zones = Zone::orderBy('name')->get();
        
        return view('admin.seo-pages.edit', compact('seoPage', 'services', 'categories', 'cities', 'zones'));
    }

    public function update(Request $request, SeoPage $seoPage)
    {
        $validated = $request->validate([
            'page_type' => 'required|in:service,category,city,zone,city_service,category_service',
            'service_id' => 'nullable|exists:services,id',
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'zone_id' => 'nullable|exists:zones,id',
            'slug' => 'required|string|unique:seo_pages,slug,' . $seoPage->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'h1_title' => 'nullable|string|max:255',
            'hero_text' => 'nullable|string',
            'content' => 'nullable|string',
            'features' => 'nullable|array',
            'faq' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('og_image')) {
            if ($seoPage->og_image) {
                Storage::disk('public')->delete($seoPage->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('seo-pages', 'public');
        }

        $seoPage->update($validated);

        return redirect()
            ->route('admin.seo-pages.index')
            ->with('success', 'SEO Page updated successfully!');
    }

    public function destroy(SeoPage $seoPage)
    {
        if ($seoPage->og_image) {
            Storage::disk('public')->delete($seoPage->og_image);
        }
        
        $seoPage->delete();

        return redirect()
            ->route('admin.seo-pages.index')
            ->with('success', 'SEO Page deleted successfully!');
    }

    public function generate(Request $request)
    {
        $type = $request->input('type', 'city_service');
        $generated = 0;

        if ($type === 'city_service') {
            $cities = City::all();
            $services = Service::active()->get();

            foreach ($cities as $city) {
                foreach ($services as $service) {
                    $slug = "{$city->slug}/{$service->slug}";
                    
                    if (!SeoPage::where('slug', $slug)->exists()) {
                        SeoPage::create([
                            'page_type' => 'city_service',
                            'city_id' => $city->id,
                            'service_id' => $service->id,
                            'slug' => $slug,
                            'meta_title' => "{$service->name} {$city->name} | Bitra Tjänster",
                            'meta_description' => "Boka {$service->name} i {$city->name}. Jämför priser från verifierade företag. Snabb service, transparenta priser och kvalitetsgaranti.",
                            'meta_keywords' => "{$service->name}, {$city->name}, boka, pris, företag",
                            'h1_title' => "{$service->name} i {$city->name}",
                            'hero_text' => "Hitta och boka {$service->name} från verifierade företag i {$city->name}. Transparenta priser och kvalitetsgaranti.",
                            'is_active' => true,
                        ]);
                        $generated++;
                    }
                }
            }
        }

        return redirect()
            ->route('admin.seo-pages.index')
            ->with('success', "Generated {$generated} SEO pages successfully!");
    }
}
