@extends('layouts.admin')

@section('title', 'Skapa ny sida')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">➕ Skapa ny sida</h1>
            <p class="text-gray-600 mt-2">Lägg till en ny sida till din webbplats</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Tillbaka
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-red-700 font-medium mb-2">Det finns några fel:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">📋 Grundinformation</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="page_key" class="block text-sm font-semibold text-gray-700 mb-2">
                        Sidnyckel (Slug) <span class="text-red-500">*</span>
                        <span class="block text-xs font-normal text-gray-500 mt-1">Används i URL (t.ex. "om-oss")</span>
                    </label>
                    <input 
                        type="text" 
                        name="page_key" 
                        id="page_key"
                        value="{{ old('page_key') }}"
                        required
                        placeholder="om-oss"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label for="page_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Sidnamn <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="page_name" 
                        id="page_name"
                        value="{{ old('page_name') }}"
                        required
                        placeholder="Om Oss"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label for="page_type" class="block text-sm font-semibold text-gray-700 mb-2">
                        Sidtyp <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="page_type" 
                        id="page_type"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="static" {{ old('page_type') === 'static' ? 'selected' : '' }}>Statisk</option>
                        <option value="dynamic" {{ old('page_type') === 'dynamic' ? 'selected' : '' }}>Dynamisk</option>
                        <option value="landing" {{ old('page_type') === 'landing' ? 'selected' : '' }}>Landningssida</option>
                    </select>
                </div>

                <div>
                    <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">
                        Ordning
                    </label>
                    <input 
                        type="number" 
                        name="order" 
                        id="order"
                        value="{{ old('order', 0) }}"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>

            <div class="mt-6">
                <label class="flex items-center cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="ml-3 text-sm font-semibold text-gray-700">Aktivera sidan</span>
                </label>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">🎯 SEO-inställningar</h2>
            
            <div class="space-y-6">
                <div>
                    <label for="meta_title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Meta-titel
                        <span class="block text-xs font-normal text-gray-500 mt-1">Optimal längd: 50-60 tecken</span>
                    </label>
                    <input 
                        type="text" 
                        name="meta_title" 
                        id="meta_title"
                        value="{{ old('meta_title') }}"
                        maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Meta-beskrivning
                        <span class="block text-xs font-normal text-gray-500 mt-1">Optimal längd: 150-160 tecken</span>
                    </label>
                    <textarea 
                        name="meta_description" 
                        id="meta_description"
                        rows="3"
                        maxlength="1000"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >{{ old('meta_description') }}</textarea>
                </div>

                <div>
                    <label for="meta_keywords" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nyckelord
                        <span class="block text-xs font-normal text-gray-500 mt-1">Separera med komma</span>
                    </label>
                    <input 
                        type="text" 
                        name="meta_keywords" 
                        id="meta_keywords"
                        value="{{ old('meta_keywords') }}"
                        placeholder="nyckelord1, nyckelord2, nyckelord3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label for="canonical_url" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kanonisk URL
                    </label>
                    <input 
                        type="url" 
                        name="canonical_url" 
                        id="canonical_url"
                        value="{{ old('canonical_url') }}"
                        placeholder="https://example.com/page"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>
        </div>

        <!-- Open Graph Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">📱 Open Graph (Social Media)</h2>
            
            <div class="space-y-6">
                <div>
                    <label for="og_title" class="block text-sm font-semibold text-gray-700 mb-2">
                        OG-titel
                    </label>
                    <input 
                        type="text" 
                        name="og_title" 
                        id="og_title"
                        value="{{ old('og_title') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label for="og_description" class="block text-sm font-semibold text-gray-700 mb-2">
                        OG-beskrivning
                    </label>
                    <textarea 
                        name="og_description" 
                        id="og_description"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >{{ old('og_description') }}</textarea>
                </div>

                <div>
                    <label for="og_image" class="block text-sm font-semibold text-gray-700 mb-2">
                        OG-bild
                        <span class="block text-xs font-normal text-gray-500 mt-1">Rekommenderad storlek: 1200x630px</span>
                    </label>
                    <input 
                        type="file" 
                        name="og_image" 
                        id="og_image"
                        accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    >
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">🎨 Hero-sektion</h2>
            
            <div class="space-y-6">
                <div>
                    <label for="hero_title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Hero-titel
                    </label>
                    <input 
                        type="text" 
                        name="hero_title" 
                        id="hero_title"
                        value="{{ old('hero_title') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label for="hero_subtitle" class="block text-sm font-semibold text-gray-700 mb-2">
                        Hero-undertext
                    </label>
                    <textarea 
                        name="hero_subtitle" 
                        id="hero_subtitle"
                        rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >{{ old('hero_subtitle') }}</textarea>
                </div>

                <div>
                    <label for="hero_image" class="block text-sm font-semibold text-gray-700 mb-2">
                        Hero-bild
                    </label>
                    <input 
                        type="file" 
                        name="hero_image" 
                        id="hero_image"
                        accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="hero_cta_text" class="block text-sm font-semibold text-gray-700 mb-2">
                            CTA-knapptext
                        </label>
                        <input 
                            type="text" 
                            name="hero_cta_text" 
                            id="hero_cta_text"
                            value="{{ old('hero_cta_text') }}"
                            placeholder="Kom igång"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label for="hero_cta_link" class="block text-sm font-semibold text-gray-700 mb-2">
                            CTA-länk
                        </label>
                        <input 
                            type="text" 
                            name="hero_cta_link" 
                            id="hero_cta_link"
                            value="{{ old('hero_cta_link') }}"
                            placeholder="/contact"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- JSON Content Fields -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">📝 Innehåll (JSON-format)</h2>
            <p class="text-sm text-gray-600 mb-6">Lägg till innehåll i JSON-format för flexibel strukturering</p>
            
            <div class="space-y-6">
                <div>
                    <label for="sections" class="block text-sm font-semibold text-gray-700 mb-2">
                        Sektioner
                    </label>
                    <textarea 
                        name="sections" 
                        id="sections"
                        rows="4"
                        placeholder='[{"type": "text", "title": "Rubrik", "content": "Innehåll"}]'
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                    >{{ old('sections') }}</textarea>
                </div>

                <div>
                    <label for="features" class="block text-sm font-semibold text-gray-700 mb-2">
                        Funktioner/Fördelar
                    </label>
                    <textarea 
                        name="features" 
                        id="features"
                        rows="4"
                        placeholder='[{"icon": "✓", "title": "Fördel", "description": "Beskrivning"}]'
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                    >{{ old('features') }}</textarea>
                </div>

                <div>
                    <label for="faqs" class="block text-sm font-semibold text-gray-700 mb-2">
                        Vanliga frågor (FAQ)
                    </label>
                    <textarea 
                        name="faqs" 
                        id="faqs"
                        rows="4"
                        placeholder='[{"question": "Fråga?", "answer": "Svar"}]'
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                    >{{ old('faqs') }}</textarea>
                </div>

                <div>
                    <label for="testimonials" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kundrecensioner
                    </label>
                    <textarea 
                        name="testimonials" 
                        id="testimonials"
                        rows="4"
                        placeholder='[{"name": "Namn", "text": "Recension", "rating": 5}]'
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                    >{{ old('testimonials') }}</textarea>
                </div>

                <div>
                    <label for="schema_markup" class="block text-sm font-semibold text-gray-700 mb-2">
                        Schema Markup (JSON-LD)
                    </label>
                    <textarea 
                        name="schema_markup" 
                        id="schema_markup"
                        rows="6"
                        placeholder='{"@@context": "https://schema.org", "@@type": "WebPage"}'
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                    >{{ old('schema_markup') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 sticky bottom-0 bg-white p-4 rounded-lg shadow-lg border border-gray-200">
            <a href="{{ route('admin.pages.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                Avbryt
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                💾 Skapa sida
            </button>
        </div>
    </form>
</div>
@endsection

