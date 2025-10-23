@extends('layouts.admin')

@section('title', 'Skapa SEO-sida')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">➕ Skapa SEO-sida</h1>
            <p class="text-gray-600 mt-2">Skapa SEO-optimerat innehåll för dynamiska sidor</p>
        </div>
        <a href="{{ route('admin.seo-pages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Tillbaka
        </a>
    </div>

    <form method="POST" action="{{ route('admin.seo-pages.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📋 Grundinställningar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sidtyp *</label>
                    <select name="page_type" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Välj sidtyp</option>
                        <option value="service">Tjänst</option>
                        <option value="category">Kategori</option>
                        <option value="city">Stad</option>
                        <option value="zone">Zon</option>
                        <option value="city_service">Stad + Tjänst</option>
                        <option value="category_service">Kategori + Tjänst</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Slug *</label>
                    <input type="text" name="slug" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="stad-tjanst-kombo">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tjänst</label>
                    <select name="service_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Ingen</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Ingen</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stad</label>
                    <select name="city_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Ingen</option>
                        @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Zon</label>
                    <select name="zone_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">Ingen</option>
                        @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">🎯 SEO-inställningar</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" maxlength="60" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="SEO-titel (max 60 tecken)">
                    <p class="text-xs text-gray-500 mt-1">Optimal längd: 50-60 tecken</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description</label>
                    <textarea name="meta_description" rows="3" maxlength="160" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="SEO-beskrivning (max 160 tecken)"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Optimal längd: 150-160 tecken</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="nyckelord, separerade, med, komma">
                </div>
            </div>
        </div>

        <!-- Open Graph -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📱 Open Graph (Social Media)</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">OG Title</label>
                    <input type="text" name="og_title" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Titel för sociala medier">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">OG Description</label>
                    <textarea name="og_description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Beskrivning för sociala medier"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">OG Image</label>
                    <input type="file" name="og_image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">Rekommenderad storlek: 1200x630px</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📝 Innehåll</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">H1 Title</label>
                    <input type="text" name="h1_title" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Huvudrubrik på sidan">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hero Text</label>
                    <textarea name="hero_text" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Undertext i hero-sektion"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Huvudinnehåll</label>
                    <textarea name="content" rows="6" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Sidans huvudinnehåll"></textarea>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">⚙️ Status</h2>
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="ml-3 text-sm font-semibold text-gray-700">Aktivera sidan</span>
            </label>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.seo-pages.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg">
                Avbryt
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                💾 Skapa SEO-sida
            </button>
        </div>
    </form>
</div>
@endsection
