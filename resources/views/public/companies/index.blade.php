@extends('layouts.public')

@section('title', 'Alla Företag - Hitta Professionella Tjänsteleverantörer')

@section('meta_description', 'Bläddra bland Sveriges bästa tjänsteföretag. Jämför priser, läs recensioner och boka direkt online. Verifierade företag med höga betyg.')

@section('meta_keywords', 'företag, tjänster, recensioner, bokning, professionella, Sverige')

@push('seo')
<meta property="og:title" content="Alla Företag - Hitta Professionella Tjänsteleverantörer">
<meta property="og:description" content="Bläddra bland Sveriges bästa tjänsteföretag. Jämför priser, läs recensioner och boka direkt online.">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ route('public.companies') }}">
@endpush

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                🏢 Alla Företag
            </h1>
            <p class="text-xl text-gray-600">
                Hitta och jämför verifierade och professionella företag
            </p>
            <p class="text-gray-500 mt-2">
                <span class="font-semibold text-gray-900">{{ $companies->total() }}</span> företag tillgängliga
            </p>
        </div>

        <!-- Filters & Sorting -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form method="GET" action="{{ route('public.companies') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- City Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            Stad
                        </label>
                        <select name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla städer</option>
                            @foreach($allCities as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Service Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Tjänst
                        </label>
                        <select name="service" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla tjänster</option>
                            @foreach($allServices as $service)
                                <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rating Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Min. Betyg
                        </label>
                        <select name="rating" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla betyg</option>
                            <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>⭐ 4+ stjärnor</option>
                            <option value="4.5" {{ request('rating') == 4.5 ? 'selected' : '' }}>⭐ 4.5+ stjärnor</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                            Sortera
                        </label>
                        <select name="sort" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Högsta betyg</option>
                            <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>Flest recensioner</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Namn (A-Ö)</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="text-sm text-gray-600">
                        Visar {{ $companies->count() }} av {{ $companies->total() }} företag
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('public.companies') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                            Rensa filter
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition shadow-lg">
                            🔍 Filtrera
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Companies Grid -->
        @if($companies->isEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Inga företag hittades</h3>
                <p class="text-gray-600 mb-8">Prova att ändra dina filter för att se fler resultat</p>
                <a href="{{ route('public.companies') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                    Visa alla företag
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($companies as $company)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                        <!-- Company Logo/Avatar -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Logo -->
                                    <div class="flex-shrink-0">
                                        @if($company->logo)
                                            <img src="{{ Storage::url($company->logo) }}" 
                                                 alt="{{ $company->company_name }}" 
                                                 class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200">
                                        @else
                                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white text-xl font-bold">
                                                {{ strtoupper(substr($company->company_name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 line-clamp-2">{{ $company->company_name }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Rating -->
                            @if($company->reviews_avg_rating)
                                <div class="flex items-center bg-yellow-50 px-4 py-2 rounded-lg mb-4">
                                    <svg class="w-5 h-5 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="font-bold text-gray-900 text-lg">{{ number_format($company->reviews_avg_rating, 1) }}</span>
                                    <span class="text-gray-500 text-sm ml-2">({{ $company->reviews_count }} recensioner)</span>
                                </div>
                            @endif

                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $company->description }}</p>

                            <!-- Services -->
                            @if($company->services && $company->services->isNotEmpty())
                                <div class="mb-4 flex flex-wrap gap-2">
                                    @foreach($company->services->take(3) as $service)
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $service->name }}</span>
                                    @endforeach
                                    @if($company->services->count() > 3)
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">+{{ $company->services->count() - 3 }} fler</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Cities -->
                            @if($company->cities && $company->cities->isNotEmpty())
                                <div class="mb-4 flex flex-wrap gap-2">
                                    @foreach($company->cities->take(3) as $city)
                                        <span class="text-xs px-2 py-1 bg-purple-50 text-purple-700 rounded flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $city->name }}
                                        </span>
                                    @endforeach
                                    @if($company->cities->count() > 3)
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">+{{ $company->cities->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif

                            <a href="{{ route('public.company.show', $company->id) }}" 
                               class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                                Visa företag →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
