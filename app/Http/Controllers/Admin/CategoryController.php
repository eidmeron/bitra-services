<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount('services')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'intro_paragraph' => 'nullable|string|max:1000',
            'features_benefits' => 'nullable|array',
            'features_benefits.*.title' => 'nullable|string|max:255',
            'features_benefits.*.description' => 'nullable|string|max:500',
            'process_steps' => 'nullable|array',
            'process_steps.*.title' => 'nullable|string|max:255',
            'process_steps.*.description' => 'nullable|string|max:500',
            'faq_items' => 'nullable|array',
            'faq_items.*.question' => 'nullable|string|max:500',
            'faq_items.*.answer' => 'nullable|string|max:1000',
            'testimonials' => 'nullable|array',
            'testimonials.*.name' => 'nullable|string|max:255',
            'testimonials.*.content' => 'nullable|string|max:1000',
            'testimonials.*.location' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:500',
            'cta_button_text' => 'nullable|string|max:100',
            'cta_button_url' => 'nullable|url|max:500',
            'meta_keywords' => 'nullable|string|max:1000',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:2048000',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|image|max:2048000',
            'icon' => 'nullable|string|max:10',
            'image' => 'nullable|image|max:2048000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Kategorinamn är obligatoriskt.',
            'icon.max' => 'Ikonen får max vara 10 tecken.',
            'image.image' => 'Filen måste vara en bild.',
            'image.max' => 'Bilden får max vara 2000MB.',
            'og_image.image' => 'OG-bilden måste vara en bild.',
            'og_image.max' => 'OG-bilden får max vara 2000MB.',
            'twitter_image.image' => 'Twitter-bilden måste vara en bild.',
            'twitter_image.max' => 'Twitter-bilden får max vara 2000MB.',
            'status.required' => 'Status är obligatoriskt.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('categories', 'public');
        }

        if ($request->hasFile('twitter_image')) {
            $validated['twitter_image'] = $request->file('twitter_image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori skapad framgångsrikt.');
    }

    public function edit(Category $category): View
    {
        $category->loadCount('services');

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        try {
            // Debug: Log the request data
            \Log::info('Category update request', [
                'category_id' => $category->id,
                'request_data' => $request->all(),
                'files' => $request->files->all()
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'intro_paragraph' => 'nullable|string|max:1000',
                'features_benefits' => 'nullable|array',
                'features_benefits.*.title' => 'nullable|string|max:255',
                'features_benefits.*.description' => 'nullable|string|max:500',
                'process_steps' => 'nullable|array',
                'process_steps.*.title' => 'nullable|string|max:255',
                'process_steps.*.description' => 'nullable|string|max:500',
                'faq_items' => 'nullable|array',
                'faq_items.*.question' => 'nullable|string|max:500',
                'faq_items.*.answer' => 'nullable|string|max:1000',
                'testimonials' => 'nullable|array',
                'testimonials.*.name' => 'nullable|string|max:255',
                'testimonials.*.content' => 'nullable|string|max:1000',
                'testimonials.*.location' => 'nullable|string|max:255',
                'cta_text' => 'nullable|string|max:500',
                'cta_button_text' => 'nullable|string|max:100',
                'cta_button_url' => 'nullable|url|max:500',
                'meta_keywords' => 'nullable|string|max:1000',
                'og_title' => 'nullable|string|max:255',
                'og_description' => 'nullable|string|max:500',
                'og_image' => 'nullable|image|max:2048000',
                'twitter_title' => 'nullable|string|max:255',
                'twitter_description' => 'nullable|string|max:500',
                'twitter_image' => 'nullable|image|max:2048000',
                'icon' => 'nullable|string|max:10',
                'image' => 'nullable|image|max:2048000',
                'status' => 'required|in:active,inactive',
                'sort_order' => 'nullable|integer|min:0',
            ], [
                'name.required' => 'Kategorinamn är obligatoriskt.',
                'icon.max' => 'Ikonen får max vara 10 tecken.',
                'image.image' => 'Filen måste vara en bild.',
                'image.max' => 'Bilden får max vara 2000MB.',
                'og_image.image' => 'OG-bilden måste vara en bild.',
                'og_image.max' => 'OG-bilden får max vara 2000MB.',
                'twitter_image.image' => 'Twitter-bilden måste vara en bild.',
                'twitter_image.max' => 'Twitter-bilden får max vara 2000MB.',
                'status.required' => 'Status är obligatoriskt.',
            ]);

            $validated['slug'] = Str::slug($validated['name']);

            // Debug: Log validated data
            \Log::info('Category update validated data', [
                'category_id' => $category->id,
                'validated_data' => $validated
            ]);

            // Handle file uploads
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image) {
                    \Storage::disk('public')->delete($category->image);
                }
                $validated['image'] = $request->file('image')->store('categories', 'public');
            }

            if ($request->hasFile('og_image')) {
                // Delete old image if exists
                if ($category->og_image) {
                    \Storage::disk('public')->delete($category->og_image);
                }
                $validated['og_image'] = $request->file('og_image')->store('categories', 'public');
            }

            if ($request->hasFile('twitter_image')) {
                // Delete old image if exists
                if ($category->twitter_image) {
                    \Storage::disk('public')->delete($category->twitter_image);
                }
                $validated['twitter_image'] = $request->file('twitter_image')->store('categories', 'public');
            }

            $category->update($validated);

            // Debug: Log successful update
            \Log::info('Category update successful', [
                'category_id' => $category->id,
                'updated_data' => $category->fresh()->toArray()
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori uppdaterad framgångsrikt.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Category update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ett fel uppstod vid uppdatering av kategorin: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            if ($category->services()->count() > 0) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Kan inte radera kategori med tillhörande tjänster.');
            }

            // Delete associated images
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }
            if ($category->og_image) {
                \Storage::disk('public')->delete($category->og_image);
            }
            if ($category->twitter_image) {
                \Storage::disk('public')->delete($category->twitter_image);
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori raderad framgångsrikt.');

        } catch (\Exception $e) {
            \Log::error('Category delete error: ' . $e->getMessage());
            return redirect()->route('admin.categories.index')
                ->with('error', 'Ett fel uppstod vid radering av kategorin: ' . $e->getMessage());
        }
    }
}
