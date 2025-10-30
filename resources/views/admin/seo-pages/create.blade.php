@extends('layouts.admin')

@section('title', 'Skapa SEO-sida')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('admin.seo-pages.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Skapa ny SEO-sida</h1>
            <p class="text-gray-600 mt-2">Lägg till SEO-data för en ny sida</p>
        </div>
    </div>

    <form action="{{ route('admin.seo-pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Grundläggande information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="page_type" class="block text-sm font-semibold text-gray-700 mb-2">Sidtyp *</label>
                    <select name="page_type" id="page_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Välj sidtyp</option>
                        @foreach($pageTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('page_type') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('page_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Related Models -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Relaterade modeller</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="service_id" class="block text-sm font-semibold text-gray-700 mb-2">Tjänst</label>
                        <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Välj tjänst</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Välj kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="city_id" class="block text-sm font-semibold text-gray-700 mb-2">Stad</label>
                        <select name="city_id" id="city_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Välj stad</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="zone_id" class="block text-sm font-semibold text-gray-700 mb-2">Zon</label>
                        <select name="zone_id" id="zone_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Välj zon</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Meta Tags -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">SEO Meta-taggar</h2>
            
            <div class="space-y-6">
                <div>
                    <label for="meta_title" class="block text-sm font-semibold text-gray-700 mb-2">Meta Titel</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Använd {service}, {category}, {city} för dynamisk innehåll">
                    @error('meta_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-semibold text-gray-700 mb-2">Meta Beskrivning</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Använd {service}, {category}, {city} för dynamisk innehåll">{{ old('meta_description') }}</textarea>
                    @error('meta_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="meta_keywords" class="block text-sm font-semibold text-gray-700 mb-2">Meta Nyckelord</label>
                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Komma-separerade nyckelord">
                    @error('meta_keywords')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Open Graph -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Open Graph (Social Media)</h2>
            
            <div class="space-y-6">
                <div>
                    <label for="og_title" class="block text-sm font-semibold text-gray-700 mb-2">OG Titel</label>
                    <input type="text" name="og_title" id="og_title" value="{{ old('og_title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="og_description" class="block text-sm font-semibold text-gray-700 mb-2">OG Beskrivning</label>
                    <textarea name="og_description" id="og_description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('og_description') }}</textarea>
                </div>

                <div>
                    <label for="og_image" class="block text-sm font-semibold text-gray-700 mb-2">OG Bild</label>
                    <input type="file" name="og_image" id="og_image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    @error('og_image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Sidinnehåll</h2>
            
            <div class="space-y-6">
                <div>
                    <label for="h1_title" class="block text-sm font-semibold text-gray-700 mb-2">H1 Titel</label>
                    <input type="text" name="h1_title" id="h1_title" value="{{ old('h1_title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="hero_text" class="block text-sm font-semibold text-gray-700 mb-2">Hero Text</label>
                    <textarea name="hero_text" id="hero_text" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('hero_text') }}</textarea>
                </div>

                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">Innehåll</label>
                    <textarea name="content" id="content" rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('content') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Inställningar</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">Sorteringsordning</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Aktiv
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.seo-pages.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                Avbryt
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                Skapa SEO-sida
            </button>
        </div>
    </form>
</div>

<script>
// Auto-generate slug from page type and related models
document.addEventListener('DOMContentLoaded', function() {
    const pageTypeSelect = document.getElementById('page_type');
    const serviceSelect = document.getElementById('service_id');
    const categorySelect = document.getElementById('category_id');
    const citySelect = document.getElementById('city_id');
    const slugInput = document.getElementById('slug');

    function generateSlug() {
        const pageType = pageTypeSelect.value;
        const service = serviceSelect.value;
        const category = categorySelect.value;
        const city = citySelect.value;

        if (!pageType) return;

        let slug = pageType;
        
        if (service) {
            const serviceName = serviceSelect.options[serviceSelect.selectedIndex].text.toLowerCase().replace(/\s+/g, '-');
            slug += '-' + serviceName;
        }
        if (category) {
            const categoryName = categorySelect.options[categorySelect.selectedIndex].text.toLowerCase().replace(/\s+/g, '-');
            slug += '-' + categoryName;
        }
        if (city) {
            const cityName = citySelect.options[citySelect.selectedIndex].text.toLowerCase().replace(/\s+/g, '-');
            slug += '-' + cityName;
        }

        slugInput.value = slug;
    }

    pageTypeSelect.addEventListener('change', generateSlug);
    serviceSelect.addEventListener('change', generateSlug);
    categorySelect.addEventListener('change', generateSlug);
    citySelect.addEventListener('change', generateSlug);
});
</script>
@endsection