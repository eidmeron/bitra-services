@extends('layouts.public')

@section('title', $seoTitle ?? 'Sökresultat')

@section('meta_description', $seoDescription ?? 'Hitta och jämför professionella tjänster från verifierade företag. Läs recensioner och boka online.')

@section('meta_keywords', ($service ? $service->name . ', ' : '') . ($category ? $category->name . ', ' : '') . ($city ? $city->name . ', ' : '') . 'tjänster, bokning, recensioner, priser, företag')

@push('seo')
<meta property="og:title" content="{{ $seoTitle ?? 'Sök tjänster' }}">
<meta property="og:description" content="{{ $seoDescription ?? 'Hitta och jämför professionella tjänster från verifierade företag.' }}">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ url()->current() }}">

<!-- Schema.org structured data -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SearchResultsPage",
    "name": "{{ $seoTitle ?? 'Sökresultat' }}",
    "description": "{{ $seoDescription ?? 'Hitta och jämför professionella tjänster' }}",
    "url": "{{ url()->current() }}",
    "mainEntity": {
        "@@type": "ItemList",
        "numberOfItems": {{ $companies->count() }},
        "itemListElement": [
            @foreach($companies->take(10) as $index => $company)
            {
                "@@type": "LocalBusiness",
                "position": {{ $index + 1 }},
                "name": "{{ $company->company_name }}",
                "description": "{{ $company->description }}",
                "url": "{{ route('public.company.show', $company) }}",
                "telephone": "{{ $company->phone }}",
                "address": {
                    "@@type": "PostalAddress",
                    "addressLocality": "{{ $company->cities->first()->name ?? '' }}",
                    "addressCountry": "SE"
                },
                "aggregateRating": {
                    "@@type": "AggregateRating",
                    "ratingValue": "{{ round($company->reviews_avg_rating ?? 0, 1) }}",
                    "reviewCount": "{{ $company->reviews_count ?? 0 }}"
                }
            }{{ $loop->last ? '' : ',' }}
            @endforeach
        ]
    }
}
</script>
@endpush

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                🔍 Sökresultat
            </h1>
            @if($city && $service)
                <p class="text-xl text-gray-600">
                    {{ $service->name }} i {{ $city->name }}
                </p>
            @elseif($city && $category)
                <p class="text-xl text-gray-600">
                    {{ $category->name }} i {{ $city->name }}
                </p>
            @elseif($city)
                <p class="text-xl text-gray-600">
                    Alla kategorier och tjänster i {{ $city->name }}
                </p>
            @elseif($service)
                <p class="text-xl text-gray-600">
                    {{ $service->name }} i alla städer
                </p>
            @else
                <p class="text-xl text-gray-600">
                    Hitta rätt tjänst för dig
                </p>
            @endif
        </div>

        <!-- Enhanced Search Filters -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8" x-data="searchFilters()">
            <form method="GET" action="{{ route('public.search') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- City Filter (First) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            📍 Välj Stad
                        </label>
                        <select name="city" 
                                x-model="selectedCity"
                                @change="onCityChange()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Välj stad först</option>
                            @foreach($allCities as $c)
                                <option value="{{ $c->id }}" {{ $selectedCity == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter (Second) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            📂 Välj Kategori
                        </label>
                        <select name="category" 
                                x-model="selectedCategory"
                                @change="onCategoryChange()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Välj kategori</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Service Filter (Third) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            🔧 Välj Tjänst
                        </label>
                        <select name="service" 
                                x-model="selectedService"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Välj tjänst</option>
                            @foreach($allServices as $s)
                                <option value="{{ $s->id }}" {{ $selectedService == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Search Buttons -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="text-sm text-gray-600">
                        <span class="font-semibold">{{ $services->count() }}</span> tjänster • 
                        <span class="font-semibold">{{ $companies->count() }}</span> företag
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('public.search') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                            Rensa filter
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition shadow-lg">
                            🔍 Sök
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results -->
        @if($services->isEmpty() && $companies->isEmpty())
            <!-- No Results -->
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Inga resultat hittades</h3>
                <p class="text-gray-600 mb-8">Prova att ändra dina sökkriterier eller rensa filtren</p>
                <a href="{{ route('public.search') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                    Visa alla tjänster
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Categories Results (when only city is selected) -->
                    @if($city && !$category && !$service && $availableCategories->isNotEmpty())
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Tillgängliga Kategorier i {{ $city->name }} ({{ $availableCategories->count() }})
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($availableCategories as $categoryItem)
                                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                                        <div class="p-6">
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="flex-1">
                                                    <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center">
                                                        @if($categoryItem->icon)
                                                            <span class="text-2xl mr-3">{{ $categoryItem->icon }}</span>
                                                        @endif
                                                        {{ $categoryItem->name }}
                                                    </h3>
                                                    @if($categoryItem->description)
                                                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $categoryItem->description }}</p>
                                                    @endif
                                                </div>
                                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                    {{ $categoryItem->services_count }} tjänster
                                                </span>
                                            </div>
                                            
                                            <!-- Action Button -->
                                            <div class="flex space-x-3">
                                                <a href="{{ route('public.search', ['city' => $city->id, 'category' => $categoryItem->id]) }}" 
                                                   class="flex-1 text-center px-4 py-3 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                    📂 Visa {{ $categoryItem->name }} i {{ $city->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Services Results -->
                    @if($services->isNotEmpty())
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                @if($city && $category)
                                    {{ $category->name }} i {{ $city->name }} ({{ $services->count() }} tjänster)
                                @else
                                    Tillgängliga Tjänster ({{ $services->count() }})
                                @endif
                            </h2>
                            <div class="grid grid-cols-1 gap-6">
                                @foreach($services as $serviceItem)
                                    @php
                                        $activeForm = $serviceItem->active_form;
                                        $hasForm = $activeForm && $activeForm->token;
                                    @endphp
                                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100">
                                        <div class="p-6">
                                            <div class="flex justify-between items-start mb-4">
                                                <div class="flex-1">
                                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $serviceItem->name }}</h3>
                                                    @if($serviceItem->category)
                                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                            {{ $serviceItem->category->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($hasForm)
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                        ✓ Bokningsbar
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $serviceItem->description }}</p>
                                            
                                            <!-- Available Cities -->
                                            @if($serviceItem->cities->isNotEmpty())
                                                <div class="mb-4 flex flex-wrap gap-2">
                                                    <span class="text-sm text-gray-500">Tillgänglig i:</span>
                                                    @foreach($serviceItem->cities->take(5) as $cityItem)
                                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ $cityItem->name }}</span>
                                                    @endforeach
                                                    @if($serviceItem->cities->count() > 5)
                                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">+{{ $serviceItem->cities->count() - 5 }} fler</span>
                                                    @endif
                                                </div>
                                            @endif
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex space-x-3">
                                                @if($city && $city->id)
                                                    <!-- Navigate to City + Service page -->
                                                    <a href="{{ route('public.city-service.landing', [$city->slug, $serviceItem->slug]) }}" 
                                                       class="flex-1 text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                        📍 {{ $serviceItem->name }} i {{ $city->name }}
                                                    </a>
                                                @elseif($hasForm)
                                                    <!-- Direct booking -->
                                                    <a href="{{ route('public.form', $activeForm->token) }}" 
                                                       class="flex-1 text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                        📝 Boka nu
                                                    </a>
                                                @else
                                                    <!-- View service details -->
                                                    <a href="{{ route('public.service.show', $serviceItem->slug) }}" 
                                                       class="flex-1 text-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                                        Visa mer
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Companies Results -->
                    @if($companies->isNotEmpty())
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Företag ({{ $companies->count() }})
                            </h2>
                            <div class="grid grid-cols-1 gap-6">
                                @foreach($companies as $company)
                                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
                                        <div class="p-6">
                                            <div class="flex items-start space-x-4">
                                                <!-- Company Logo -->
                                                <div class="flex-shrink-0">
                                                    @if($company->logo)
                                                        <img src="{{ Storage::url($company->logo) }}" 
                                                             alt="{{ $company->company_name }}" 
                                                             class="w-20 h-20 rounded-lg object-cover border-2 border-gray-200">
                                                    @else
                                                        <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold">
                                                            {{ strtoupper(substr($company->company_name, 0, 2)) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Company Info -->
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start mb-3">
                                                        <div>
                                                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $company->company_name }}</h3>
                                                            <!-- Cities -->
                                                            @if($company->cities && $company->cities->isNotEmpty())
                                                                <div class="flex flex-wrap gap-2 mb-2">
                                                                    @foreach($company->cities->take(3) as $companyCity)
                                                                        <span class="inline-flex items-center text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded">
                                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                                            </svg>
                                                                            {{ $companyCity->name }}
                                                                        </span>
                                                                    @endforeach
                                                                    @if($company->cities->count() > 3)
                                                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">
                                                                            +{{ $company->cities->count() - 3 }} fler
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <!-- Rating -->
                                                        @if($company->reviews_avg_rating)
                                                            <div class="flex items-center bg-yellow-50 px-3 py-2 rounded-lg">
                                                                <svg class="w-5 h-5 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                                <div class="text-left">
                                                                    <div class="font-bold text-gray-900">{{ number_format($company->reviews_avg_rating, 1) }}</div>
                                                                    <div class="text-xs text-gray-500">{{ $company->reviews_count }} rec.</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $company->description }}</p>
                                                    
                                                    <!-- Services -->
                                                    @if($company->services && $company->services->isNotEmpty())
                                                        <div class="mb-4 flex flex-wrap gap-2">
                                                            @foreach($company->services->take(4) as $companyService)
                                                                <span class="text-xs px-2 py-1 bg-purple-50 text-purple-700 rounded">
                                                                    {{ $companyService->name }}
                                                                </span>
                                                            @endforeach
                                                            @if($company->services->count() > 4)
                                                                <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">
                                                                    +{{ $company->services->count() - 4 }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    
                                                    <a href="{{ route('public.company.show', $company->id) }}" 
                                                       class="inline-block px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                                                        Visa företag →
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">💡 Tips för bästa resultat</h3>
                        <ul class="space-y-3 text-sm text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Välj både stad och tjänst för bästa matchning
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Jämför flera företag innan du bokar
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Läs recensioner från andra kunder
                            </li>
                        </ul>

                        <div class="mt-6 pt-6 border-t">
                            <h4 class="font-semibold text-gray-900 mb-3">Behöver du hjälp?</h4>
                            <p class="text-sm text-gray-600 mb-4">Kontakta oss om du behöver hjälp att hitta rätt tjänst</p>
                            <a href="{{ route('contact') }}" class="block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                Kontakta oss
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function searchFilters() {
    return {
        selectedCity: '{{ $selectedCity }}',
        selectedCategory: '{{ $selectedCategory }}',
        selectedService: '{{ $selectedService }}',
        
        onCityChange() {
            // Reset category and service when city changes
            this.selectedCategory = '';
            this.selectedService = '';
            this.submitForm();
        },
        
        onCategoryChange() {
            // Reset service when category changes
            this.selectedService = '';
            this.submitForm();
        },
        
        submitForm() {
            // Build URL with current selections
            const params = new URLSearchParams();
            if (this.selectedCity) params.append('city', this.selectedCity);
            if (this.selectedCategory) params.append('category', this.selectedCategory);
            if (this.selectedService) params.append('service', this.selectedService);
            
            // Redirect to new URL
            window.location.href = '{{ route("public.search") }}?' + params.toString();
        }
    }
}
</script>
@endpush
@endsection
