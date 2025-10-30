@extends('layouts.admin')

@section('title', 'Redigera SEO-sida')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Redigera SEO-sida</h1>
            <p class="text-gray-600 mt-2">{{ $seoPage->meta_title ?? $seoPage->slug }}</p>
        </div>
        <a href="{{ route('admin.seo-pages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Tillbaka
        </a>
    </div>

    <form method="POST" action="{{ route('admin.seo-pages.update', $seoPage) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Grundinställningar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sidtyp</label>
                    <input type="text" value="{{ $seoPage->page_type }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                    <input type="text" value="{{ $seoPage->slug }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>

                @if($seoPage->service)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tjänst</label>
                    <input type="text" value="{{ $seoPage->service->name }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>
                @endif

                @if($seoPage->category)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <input type="text" value="{{ $seoPage->category->name }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>
                @endif

                @if($seoPage->city)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stad</label>
                    <input type="text" value="{{ $seoPage->city->name }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>
                @endif

                @if($seoPage->zone)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Zon</label>
                    <input type="text" value="{{ $seoPage->zone->name }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                </div>
                @endif
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">SEO-inställningar</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $seoPage->meta_title) }}" 
                           maxlength="60" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="SEO-titel (max 60 tecken)">
                    <p class="text-xs text-gray-500 mt-1">Optimal längd: 50-60 tecken</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" rows="3" maxlength="160" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="SEO-beskrivning (max 160 tecken)">{{ old('meta_description', $seoPage->meta_description) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Optimal längd: 150-160 tecken</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $seoPage->meta_keywords) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="nyckelord, separerade, med, komma">
                </div>
            </div>
        </div>

        <!-- Open Graph -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Open Graph (Social Media)</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">OG Title</label>
                    <input type="text" name="og_title" value="{{ old('og_title', $seoPage->og_title) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Titel för sociala medier">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">OG Description</label>
                    <textarea name="og_description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Beskrivning för sociala medier">{{ old('og_description', $seoPage->og_description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">OG Image</label>
                    @if($seoPage->og_image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($seoPage->og_image) }}" alt="Current OG Image" class="max-w-xs h-32 object-cover rounded border">
                            <p class="text-xs text-gray-500 mt-1">Nuvarande bild</p>
                        </div>
                    @endif
                    <input type="file" name="og_image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Rekommenderad storlek: 1200x630px</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Innehåll</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">H1 Title</label>
                    <input type="text" name="h1_title" value="{{ old('h1_title', $seoPage->h1_title) }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Huvudrubrik på sidan">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hero Text</label>
                    <textarea name="hero_text" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Undertext i hero-sektion">{{ old('hero_text', $seoPage->hero_text) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Huvudinnehåll</label>
                    <textarea name="content" rows="6" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Sidans huvudinnehåll">{{ old('content', $seoPage->content) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Features (JSON)</label>
                    <textarea name="features" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                              placeholder='[{"title": "Fördel", "description": "Beskrivning"}]'>{{ old('features', $seoPage->features ? json_encode($seoPage->features, JSON_PRETTY_PRINT) : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">FAQ (JSON)</label>
                    <textarea name="faq" rows="4" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                              placeholder='[{"question": "Fråga?", "answer": "Svar"}]'>{{ old('faq', $seoPage->faq ? json_encode($seoPage->faq, JSON_PRETTY_PRINT) : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Schema Markup (JSON-LD)</label>
                    <textarea name="schema_markup" rows="6" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                              placeholder='{"@context": "https://schema.org", "@type": "Service"}'>{{ old('schema_markup', $seoPage->schema_markup ? json_encode($seoPage->schema_markup, JSON_PRETTY_PRINT) : '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Status</h2>
            <div class="space-y-4">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ old('is_active', $seoPage->is_active) ? 'checked' : '' }} 
                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-3 text-sm font-semibold text-gray-700">Aktivera sidan</span>
                </label>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.seo-pages.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg">
                Avbryt
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                Uppdatera SEO-sida
            </button>
        </div>
    </form>
</div>
@endsection