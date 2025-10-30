<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class PageContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pages = PageContent::orderBy('page_type')->orderBy('order')->get();
        
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Debug: Log the request data
        \Log::info('Page store request data:', $request->all());
        
        $validated = $request->validate([
            'page_key' => 'required|string|unique:page_contents,page_key|max:255',
            'page_name' => 'required|string|max:255',
            'page_type' => 'required|in:static,dynamic,landing',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|image|max:2048000',
            'canonical_url' => 'nullable|url',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_image' => 'nullable|image|max:2048000',
            'hero_cta_text' => 'nullable|string|max:100',
            'hero_cta_link' => 'nullable|string|max:255',
            'sections' => 'nullable|json',
            'features' => 'nullable|json',
            'faqs' => 'nullable|json',
            'testimonials' => 'nullable|json',
            'schema_markup' => 'nullable|json',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        // Handle file uploads
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('pages/og', 'public');
        }

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $request->file('hero_image')->store('pages/hero', 'public');
        }

        // Ensure page_key is slug format
        $validated['page_key'] = Str::slug($validated['page_key']);

        // Decode JSON fields if they are strings
        foreach (['sections', 'features', 'faqs', 'testimonials', 'schema_markup'] as $field) {
            if (isset($validated[$field]) && is_string($validated[$field]) && !empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validated[$field] = $decoded;
                } else {
                    // If JSON is invalid, set to null
                    $validated[$field] = null;
                }
            } elseif (isset($validated[$field]) && empty($validated[$field])) {
                $validated[$field] = null;
            }
        }

        try {
            PageContent::create($validated);

            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Sida skapad framgångsrikt!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ett fel uppstod: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PageContent $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PageContent $page): RedirectResponse
    {
        // Debug: Log the request data
        \Log::info('Page update request data:', $request->all());
        
        $validated = $request->validate([
            'page_name' => 'required|string|max:255',
            'page_type' => 'required|in:static,dynamic,landing',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|image|max:2048000',
            'canonical_url' => 'nullable|url',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_image' => 'nullable|image|max:2048000',
            'hero_cta_text' => 'nullable|string|max:100',
            'hero_cta_link' => 'nullable|string|max:255',
            'sections' => 'nullable|json',
            'features' => 'nullable|json',
            'faqs' => 'nullable|json',
            'testimonials' => 'nullable|json',
            'schema_markup' => 'nullable|json',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        // Handle file uploads
        if ($request->hasFile('og_image')) {
            // Delete old image
            if ($page->og_image && Storage::disk('public')->exists($page->og_image)) {
                Storage::disk('public')->delete($page->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('pages/og', 'public');
        }

        if ($request->hasFile('hero_image')) {
            // Delete old image
            if ($page->hero_image && Storage::disk('public')->exists($page->hero_image)) {
                Storage::disk('public')->delete($page->hero_image);
            }
            $validated['hero_image'] = $request->file('hero_image')->store('pages/hero', 'public');
        }

        // Decode JSON fields if they are strings
        foreach (['sections', 'features', 'faqs', 'testimonials', 'schema_markup'] as $field) {
            if (isset($validated[$field]) && is_string($validated[$field]) && !empty($validated[$field])) {
                $decoded = json_decode($validated[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validated[$field] = $decoded;
                } else {
                    // If JSON is invalid, set to null
                    $validated[$field] = null;
                }
            } elseif (isset($validated[$field]) && empty($validated[$field])) {
                $validated[$field] = null;
            }
        }

        try {
            $page->update($validated);
            
            return redirect()
                ->route('admin.pages.index')
                ->with('success', 'Sida uppdaterad framgångsrikt!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ett fel uppstod: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PageContent $page): RedirectResponse
    {
        // Delete associated images
        if ($page->og_image && Storage::disk('public')->exists($page->og_image)) {
            Storage::disk('public')->delete($page->og_image);
        }

        if ($page->hero_image && Storage::disk('public')->exists($page->hero_image)) {
            Storage::disk('public')->delete($page->hero_image);
        }

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Sida raderad framgångsrikt!');
    }
}
