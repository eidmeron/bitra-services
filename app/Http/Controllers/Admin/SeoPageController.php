<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use App\Models\Service;
use App\Models\Category;
use App\Models\City;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

final class SeoPageController extends Controller
{
    public function index(Request $request): View
    {
        $query = SeoPage::with(['service', 'category', 'city', 'zone']);

        // Filter by page type
        if ($request->filled('page_type')) {
            $query->where('page_type', $request->page_type);
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('meta_title', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%")
                  ->orWhere('h1_title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $seoPages = $query->orderBy('page_type')->orderBy('sort_order')->paginate(20);
        $pageTypes = SeoPage::getPageTypes();

        return view('admin.seo-pages.index', compact('seoPages', 'pageTypes'));
    }

    public function create(): View
    {
        $pageTypes = SeoPage::getPageTypes();
        $services = Service::select('id', 'name')->orderBy('name')->get();
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $cities = City::select('id', 'name')->orderBy('name')->get();
        $zones = Zone::select('id', 'name')->orderBy('name')->get();

        return view('admin.seo-pages.create', compact('pageTypes', 'services', 'categories', 'cities', 'zones'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page_type' => 'required|string|in:' . implode(',', array_keys(SeoPage::getPageTypes())),
            'service_id' => 'nullable|exists:services,id',
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'zone_id' => 'nullable|exists:zones,id',
            'slug' => 'required|string|unique:seo_pages,slug|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'h1_title' => 'nullable|string|max:255',
            'hero_text' => 'nullable|string',
            'content' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.title' => 'required_with:features|string|max:255',
            'features.*.description' => 'required_with:features|string|max:500',
            'features.*.icon' => 'nullable|string|max:10',
            'faq' => 'nullable|array',
            'faq.*.question' => 'required_with:faq|string|max:255',
            'faq.*.answer' => 'required_with:faq|string|max:1000',
            'schema_markup' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle file upload
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('seo-images', 'public');
        }

        SeoPage::create($validated);

        return redirect()->route('admin.seo-pages.index')
            ->with('success', 'SEO-sida har skapats framgångsrikt.');
    }

    public function show(SeoPage $seoPage): View
    {
        $seoPage->load(['service', 'category', 'city', 'zone']);
        return view('admin.seo-pages.show', compact('seoPage'));
    }

    public function edit(SeoPage $seoPage): View
    {
        $pageTypes = SeoPage::getPageTypes();
        $services = Service::select('id', 'name')->orderBy('name')->get();
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $cities = City::select('id', 'name')->orderBy('name')->get();
        $zones = Zone::select('id', 'name')->orderBy('name')->get();

        return view('admin.seo-pages.edit', compact('seoPage', 'pageTypes', 'services', 'categories', 'cities', 'zones'));
    }

    public function update(Request $request, SeoPage $seoPage): RedirectResponse
    {
        $validated = $request->validate([
            'page_type' => 'required|string|in:' . implode(',', array_keys(SeoPage::getPageTypes())),
            'service_id' => 'nullable|exists:services,id',
            'category_id' => 'nullable|exists:categories,id',
            'city_id' => 'nullable|exists:cities,id',
            'zone_id' => 'nullable|exists:zones,id',
            'slug' => 'required|string|max:255|unique:seo_pages,slug,' . $seoPage->id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'h1_title' => 'nullable|string|max:255',
            'hero_text' => 'nullable|string',
            'content' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.title' => 'required_with:features|string|max:255',
            'features.*.description' => 'required_with:features|string|max:500',
            'features.*.icon' => 'nullable|string|max:10',
            'faq' => 'nullable|array',
            'faq.*.question' => 'required_with:faq|string|max:255',
            'faq.*.answer' => 'required_with:faq|string|max:1000',
            'schema_markup' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        // Handle file upload
        if ($request->hasFile('og_image')) {
            // Delete old image
            if ($seoPage->og_image) {
                Storage::disk('public')->delete($seoPage->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('seo-images', 'public');
        }

        // Handle JSON fields
        if ($request->has('features') && is_string($request->features)) {
            $validated['features'] = json_decode($request->features, true);
        }
        if ($request->has('faq') && is_string($request->faq)) {
            $validated['faq'] = json_decode($request->faq, true);
        }
        if ($request->has('schema_markup') && is_string($request->schema_markup)) {
            $validated['schema_markup'] = json_decode($request->schema_markup, true);
        }

        $seoPage->update($validated);

        return redirect()->route('admin.seo-pages.index')
            ->with('success', 'SEO-sida har uppdaterats framgångsrikt.');
    }

    public function destroy(SeoPage $seoPage): RedirectResponse
    {
        // Delete associated image
        if ($seoPage->og_image) {
            Storage::disk('public')->delete($seoPage->og_image);
        }

        $seoPage->delete();

        return redirect()->route('admin.seo-pages.index')
            ->with('success', 'SEO-sida har tagits bort.');
    }

    public function toggleStatus(SeoPage $seoPage): RedirectResponse
    {
        $seoPage->update(['is_active' => !$seoPage->is_active]);

        $status = $seoPage->is_active ? 'aktiverad' : 'inaktiverad';
        return redirect()->back()->with('success', "SEO-sida har {$status}.");
    }
}