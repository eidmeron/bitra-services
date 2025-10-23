@extends('layouts.public')

@section('title', 'Alla Tjänster - Professionella Tjänster i Hela Sverige')

@section('meta_description', 'Utforska vårt kompletta utbud av professionella tjänster. Städning, trädgårdsskötsel, målning och mycket mer. Boka direkt online med verifierade företag.')

@section('meta_keywords', 'tjänster, professionella tjänster, städning, trädgård, målning, ROT-avdrag, bokning')

@push('seo')
<meta property="og:title" content="Alla Tjänster - Professionella Tjänster i Hela Sverige">
<meta property="og:description" content="Utforska vårt kompletta utbud av professionella tjänster. Boka direkt online med verifierade företag.">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ route('public.services') }}">
@endpush

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    🛠️ Våra Tjänster
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Utforska vårt kompletta utbud av professionella tjänster. Från städning till trädgårdsskötsel - vi har allt du behöver.
                </p>
                <p class="mt-4 text-lg">
                    <span class="font-semibold">{{ $services->total() }}</span> tjänster tillgängliga
                </p>
            </div>
        </div>
    </div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form method="GET" action="{{ route('public.services') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Kategori
                        </label>
                        <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla kategorier</option>
                            @foreach($allCategories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->services_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

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
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t mt-4">
                    <div class="text-sm text-gray-600">
                        Visar {{ $services->count() }} av {{ $services->total() }} tjänster
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('public.services') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                            Rensa filter
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition shadow-lg">
                            🔍 Filtrera
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Services Grid -->
        @if($services->isEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Inga tjänster hittades</h3>
                <p class="text-gray-600 mb-8">Prova att ändra dina filter för att se fler resultat</p>
                <a href="{{ route('public.services') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                    Visa alla tjänster
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($services as $service)
                    @php
                        $activeForm = $service->active_form;
                        $hasForm = $activeForm && $activeForm->token;
                    @endphp
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                        <!-- Service Image -->
                        @if($service->image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ Storage::url($service->image) }}" 
                                     alt="{{ $service->name }}" 
                                     class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-300">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                                <span class="text-6xl">{{ $service->icon ?? '🛠️' }}</span>
                            </div>
                        @endif

                        <!-- Service Content -->
                        <div class="p-6">
                            <!-- Category Badge -->
                            @if($service->category)
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full mb-3">
                                    {{ $service->category->name }}
                                </span>
                            @endif

                            <!-- Service Name -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $service->description }}
                            </p>

                            <!-- Features/Includes Preview -->
                            @if($service->includes && count($service->includes) > 0)
                                <div class="mb-4">
                                    <div class="text-xs font-semibold text-gray-700 mb-2">✅ Inkluderar:</div>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        @foreach(array_slice($service->includes, 0, 3) as $include)
                                            <li class="flex items-start">
                                                <span class="text-green-500 mr-1">•</span>
                                                <span>{{ $include }}</span>
                                            </li>
                                        @endforeach
                                        @if(count($service->includes) > 3)
                                            <li class="text-blue-600 font-medium">+ {{ count($service->includes) - 3 }} mer...</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <!-- Price & ROT -->
                            <div class="flex items-center justify-between mb-4 pb-4 border-b">
                                @if($service->base_price)
                                    <div>
                                        <div class="text-xs text-gray-500">Från</div>
                                        <div class="text-2xl font-bold text-gray-900">
                                            {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                        </div>
                                    </div>
                                @endif
                                @if($service->rot_eligible)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        🏡 ROT {{ $service->rot_percent }}%
                                    </span>
                                @endif
                            </div>

                            <!-- Cities Available -->
                            @if($service->cities->count() > 0)
                                <div class="mb-4">
                                    <div class="text-xs font-semibold text-gray-700 mb-2">📍 Tillgänglig i:</div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($service->cities->take(3) as $city)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                                {{ $city->name }}
                                            </span>
                                        @endforeach
                                        @if($service->cities->count() > 3)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-medium">
                                                +{{ $service->cities->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                @if($hasForm)
                                    <a href="{{ route('public.form', $activeForm->token) }}" 
                                       class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        📅 Boka Nu
                                    </a>
                                @endif
                                <a href="{{ route('public.service.show', $service->slug) }}" 
                                   class="block w-full text-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:border-blue-600 hover:text-blue-600 transition-all">
                                    📖 Läs Mer
                                </a>
                            </div>
                        </div>
        </div>
        @endforeach
            </div>

            <!-- Pagination -->
            @if($services->hasPages())
                <div class="mt-12">
                    {{ $services->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
