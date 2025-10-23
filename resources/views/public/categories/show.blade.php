@extends('layouts.public')

@section('title', $category->name . ' - Tjänster & Företag')
@section('meta_description', $category->description)

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-purple-600 to-pink-600 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumb -->
            <div class="text-sm text-purple-200 mb-4">
                <a href="{{ route('welcome') }}" class="hover:text-white">Hem</a>
                <span class="mx-2">›</span>
                <a href="{{ route('public.categories') }}" class="hover:text-white">Kategorier</a>
                <span class="mx-2">›</span>
                <span>{{ $category->name }}</span>
            </div>

            <h1 class="text-5xl font-bold mb-4">
                {{ $category->icon ?? '📂' }} {{ $category->name }}
            </h1>
            <p class="text-xl text-purple-100 max-w-3xl">
                {{ $category->description }}
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Services Section -->
        @if($category->services->isNotEmpty())
        <div class="mb-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">
                    🛠️ Tillgängliga Tjänster
                    <span class="text-gray-500 text-xl ml-2">({{ $category->services->count() }})</span>
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($category->services as $service)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <!-- Service Image -->
                    <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-600 overflow-hidden">
                        @if($service->image)
                            <img src="{{ Storage::url($service->image) }}" 
                                 alt="{{ $service->name }}" 
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white text-6xl">
                                {{ $service->icon ?? '🛠️' }}
                            </div>
                        @endif
                        
                        <!-- ROT Badge -->
                        @if($service->rot_eligible)
                            <div class="absolute top-4 right-4 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                                🏡 ROT {{ $service->rot_percent }}%
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <!-- Service Header -->
                        <div class="mb-4">
                            @if($service->category)
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full mb-2">
                                    {{ $service->category->name }}
                                </span>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition">
                                {{ $service->name }}
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-2">
                                {{ $service->description }}
                            </p>
                        </div>

                        <!-- Price -->
                        @if($service->base_price)
                            <div class="mb-4 flex items-center justify-between py-3 px-4 bg-blue-50 rounded-lg">
                                <span class="text-sm text-gray-600">Pris från:</span>
                                <span class="text-2xl font-bold text-blue-600">
                                    {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                </span>
                            </div>
                        @endif

                        <!-- What's Included Preview -->
                        @if($service->includes && count($service->includes) > 0)
                            <div class="mb-4">
                                <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                                    ✓ Vad Ingår:
                                </p>
                                <div class="space-y-1">
                                    @foreach(array_slice($service->includes, 0, 3) as $include)
                                        <div class="flex items-start text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="line-clamp-1">{{ $include }}</span>
                                        </div>
                                    @endforeach
                                    @if(count($service->includes) > 3)
                                        <p class="text-xs text-gray-500 italic ml-6">
                                            + {{ count($service->includes) - 3 }} mer...
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Cities Available -->
                        @if($service->cities && $service->cities->count() > 0)
                            <div class="mb-4 pb-4 border-b border-gray-200">
                                <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                                    📍 Tillgänglig i:
                                </p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($service->cities->take(3) as $city)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                            {{ $city->name }}
                                        </span>
                                    @endforeach
                                    @if($service->cities->count() > 3)
                                        <span class="px-2 py-1 bg-gray-200 text-gray-600 text-xs rounded-full font-semibold">
                                            +{{ $service->cities->count() - 3 }} till
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            @php
                                $activeForm = $service->active_form;
                                $hasForm = $activeForm && $activeForm->token;
                            @endphp
                            
                            @if($hasForm)
                                <a href="{{ route('public.form', $activeForm->token) }}" 
                                   class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    <svg class="w-5 h-5 inline mr-2 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    📅 Boka Nu
                                </a>
                            @endif
                            
                            <a href="{{ route('public.service.show', $service->slug) }}" 
                               class="block w-full text-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all">
                                📖 Läs Mer & Detaljer
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-lg mb-16">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Inga Tjänster Ännu</h3>
                <p class="text-gray-600">Det finns inga tjänster i denna kategori just nu.</p>
            </div>
        @endif
        
        <!-- Companies Section -->
        @if($companies->isNotEmpty())
        <div class="mb-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">
                    🏢 Våra Företag
                    <span class="text-gray-500 text-xl ml-2">({{ $companies->count() }})</span>
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($companies as $company)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-start space-x-4">
                            <!-- Company Logo -->
                            <div class="flex-shrink-0">
                                @if($company->logo)
                                    <img src="{{ Storage::url($company->logo) }}" 
                                         alt="{{ $company->company_name }}" 
                                         class="w-20 h-20 rounded-lg object-cover shadow-md">
                                @else
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-2xl shadow-md">
                                        {{ strtoupper(substr($company->company_name ?? 'C', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Company Info -->
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    {{ $company->company_name ?? 'Företag' }}
                                </h3>
                                
                                <!-- Rating -->
                                @if($company->reviews_avg_rating)
                                    <div class="flex items-center mb-2">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($company->reviews_avg_rating))
                                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm font-semibold text-gray-700">
                                            {{ number_format($company->reviews_avg_rating, 1) }}
                                        </span>
                                        <span class="ml-1 text-sm text-gray-500">
                                            ({{ $company->reviews_count ?? 0 }} recensioner)
                                        </span>
                                    </div>
                                @endif
                                
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                    {{ $company->description ?? 'Professionellt företag med hög kvalitet.' }}
                                </p>
                                
                                <!-- Services Tags -->
                                @if($company->services && $company->services->count() > 0)
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach($company->services->take(4) as $companyService)
                                            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                                                {{ $companyService->name }}
                                            </span>
                                        @endforeach
                                        @if($company->services->count() > 4)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-semibold">
                                                +{{ $company->services->count() - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Cities -->
                                @if($company->cities && $company->cities->count() > 0)
                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        @foreach($company->cities->take(2) as $companyCity)
                                            <span>{{ $companyCity->name }}@if(!$loop->last), @endif</span>
                                        @endforeach
                                        @if($company->cities->count() > 2)
                                            <span class="ml-1 text-gray-500">+{{ $company->cities->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Action Button -->
                                <a href="{{ route('public.company.show', $company->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                                    👁️ Visa Företag
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-lg">
                <div class="text-6xl mb-4">🏢</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Inga Företag Ännu</h3>
                <p class="text-gray-600">Det finns inga företag i denna kategori just nu.</p>
            </div>
        @endif

        <!-- CTA Section -->
        <div class="mt-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">
                Redo att Boka?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Välj en av våra professionella tjänster och få hjälp av våra verifierade företag.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('public.services') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition shadow-xl">
                    🛠️ Se Alla Tjänster
                </a>
                <a href="{{ route('public.companies') }}" 
                   class="inline-flex items-center px-8 py-4 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 transition shadow-xl border-2 border-white/30">
                    🏢 Bläddra Företag
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
