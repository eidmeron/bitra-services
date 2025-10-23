@extends('layouts.public')

@section('title', 'Professionella Tjänster i ' . $city->name . ' - Boka Online')
@section('meta_description', 'Hitta och boka professionella tjänster i ' . $city->name . '. Verifierade partners, snabb bokning, transparenta priser. ROT-avdrag och kvalitetsgaranti.')
@section('meta_keywords', $city->name . ', tjänster, bokning, städning, hantverkare, trädgård, ROT-avdrag, ' . ($city->zone->name ?? ''))

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumb -->
            <div class="text-sm text-blue-200 mb-4">
                <a href="{{ route('welcome') }}" class="hover:text-white">Hem</a>
                <span class="mx-2">›</span>
                <a href="{{ route('public.cities') }}" class="hover:text-white">Städer</a>
                <span class="mx-2">›</span>
                <span>{{ $city->name }}</span>
            </div>

            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-4">
                    🏙️ Professionella Tjänster i {{ $city->name }}
                </h1>
                <p class="text-xl text-blue-100 mb-2">
                    {{ $city->zone->name ?? 'Sverige' }}
                </p>
                <p class="text-lg text-blue-200 max-w-3xl mx-auto mb-8">
                    Hitta och boka verifierade tjänster från topprankade partners i {{ $city->name }}. 
                    Snabb bokning, transparenta priser och kvalitetsgaranti.
                </p>

                <!-- Quick Stats -->
                <div class="flex flex-wrap justify-center gap-6 mt-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-4 border border-white/30">
                        <div class="text-3xl font-bold">{{ $stats['total_services'] }}+</div>
                        <div class="text-sm text-blue-200">Tjänster</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-4 border border-white/30">
                        <div class="text-3xl font-bold">{{ $stats['total_companies'] }}+</div>
                        <div class="text-sm text-blue-200">Partners</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-4 border border-white/30">
                        <div class="text-3xl font-bold">{{ $stats['total_categories'] }}+</div>
                        <div class="text-sm text-blue-200">Kategorier</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Categories with Services Sliders -->
        @if($categories->isNotEmpty())
            @foreach($categories as $category)
            <div class="mb-16">
                <!-- Category Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                            <span class="text-4xl mr-3">{{ $category->icon ?? '📂' }}</span>
                            {{ $category->name }} i {{ $city->name }}
                        </h2>
                        <p class="text-gray-600 mt-2 max-w-3xl">
                            {{ $category->description }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $category->services_count }} tjänster tillgängliga
                        </p>
                    </div>
                    <a href="{{ route('public.category.show', $category->slug) }}" 
                       class="hidden md:inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        Se alla →
                    </a>
                </div>

                <!-- Services Slider -->
                @if($category->services->isNotEmpty())
                <div class="relative" x-data="{ 
                    currentSlide: 0,
                    totalSlides: {{ ceil($category->services->count() / 3) }},
                    nextSlide() {
                        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                    },
                    prevSlide() {
                        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                    }
                }">
                    <!-- Slider Container -->
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-500 ease-in-out" 
                             :style="`transform: translateX(-${currentSlide * 100}%)`">
                            @foreach($category->services->chunk(3) as $serviceChunk)
                            <div class="min-w-full">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-1">
                                    @foreach($serviceChunk as $service)
                                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                                        <!-- Service Image/Icon -->
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
                                            
                                            @if($service->rot_eligible)
                                                <div class="absolute top-4 right-4 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                                                    🏡 ROT {{ $service->rot_percent }}%
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="p-6">
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                                {{ $service->name }}
                                            </h3>
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                                {{ $service->description }}
                                            </p>

                                            @if($service->base_price)
                                                <div class="mb-4 flex items-center justify-between py-2 px-3 bg-blue-50 rounded-lg">
                                                    <span class="text-sm text-gray-600">Från:</span>
                                                    <span class="text-xl font-bold text-blue-600">
                                                        {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="space-y-2">
                                                @php
                                                    $activeForm = $service->active_form;
                                                    $hasForm = $activeForm && $activeForm->token;
                                                @endphp
                                                
                                                @if($hasForm)
                                                    <a href="{{ route('public.form', $activeForm->token) }}" 
                                                       class="block w-full text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                                                        📅 Boka Nu
                                                    </a>
                                                @endif
                                                
                                                <a href="{{ route('public.city-service.landing', [$city->slug, $service->slug]) }}" 
                                                   class="block w-full text-center px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all">
                                                    📖 Läs Mer
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    @if($category->services->count() > 3)
                    <button @click="prevSlide" 
                            class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-12 h-12 bg-white rounded-full shadow-lg hover:bg-gray-100 transition flex items-center justify-center text-gray-700 hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button @click="nextSlide" 
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-12 h-12 bg-white rounded-full shadow-lg hover:bg-gray-100 transition flex items-center justify-center text-gray-700 hover:scale-110">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Dots Indicator -->
                    <div class="flex justify-center mt-6 space-x-2">
                        <template x-for="i in totalSlides" :key="i">
                            <button @click="currentSlide = i - 1" 
                                    class="w-3 h-3 rounded-full transition-all"
                                    :class="currentSlide === (i - 1) ? 'bg-blue-600 w-8' : 'bg-gray-300'">
                            </button>
                        </template>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Mobile: View All Button -->
                <div class="md:hidden mt-6 text-center">
                    <a href="{{ route('public.category.show', $category->slug) }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        Se alla {{ $category->name }} →
                    </a>
                </div>
            </div>
            @endforeach
        @endif

        <!-- Why Choose Us Features -->
        <div class="my-16 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-xl p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">
                ⭐ Varför välja våra tjänster i {{ $city->name }}?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg">
                        ✓
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Verifierade Partners</h3>
                    <p class="text-gray-600">
                        Alla partners är noggrant granskade och verifierade för din trygghet.
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-600 to-teal-600 rounded-full flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg">
                        ⚡
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Snabb Bokning</h3>
                    <p class="text-gray-600">
                        Boka online på några minuter. Få bekräftelse direkt och välj tid som passar dig.
                    </p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg">
                        💰
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Transparenta Priser</h3>
                    <p class="text-gray-600">
                        Se priser innan du bokar. ROT-avdrag ingår automatiskt på kvalificerade tjänster.
                    </p>
                </div>
            </div>
        </div>

        <!-- Companies Section -->
        @if($companies->isNotEmpty())
        <div class="my-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    🏢 Topprankade Partner i {{ $city->name }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Professionella partners med hög kvalitet och goda recensioner
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($companies as $company)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-start space-x-4 mb-4">
                            @if($company->logo)
                                <img src="{{ Storage::url($company->logo) }}" 
                                     alt="{{ $company->company_name }}" 
                                     class="w-16 h-16 rounded-lg object-cover shadow-md">
                            @else
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md">
                                    {{ strtoupper(substr($company->company_name ?? 'C', 0, 2)) }}
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">
                                    {{ $company->company_name ?? 'Partner' }}
                                </h3>
                                
                                @if($company->reviews_avg_rating)
                                    <div class="flex items-center">
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($company->reviews_avg_rating))
                                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm font-semibold text-gray-700">
                                            {{ number_format($company->reviews_avg_rating, 1) }}
                                        </span>
                                        <span class="ml-1 text-sm text-gray-500">
                                            ({{ $company->reviews_count ?? 0 }})
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ $company->description ?? 'Professionell partner med hög kvalitet.' }}
                        </p>
                        
                        @if($company->services && $company->services->count() > 0)
                            <div class="flex flex-wrap gap-1 mb-4">
                                @foreach($company->services->take(3) as $companyService)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                                        {{ $companyService->name }}
                                    </span>
                                @endforeach
                                @if($company->services->count() > 3)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full font-semibold">
                                        +{{ $company->services->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                        @endif
                        
                        <a href="{{ route('public.company.show', $company->id) }}" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                            👁️ Visa Partner
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('public.companies', ['city' => $city->id]) }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition shadow-lg">
                    Se alla partners i {{ $city->name }} →
                </a>
            </div>
        </div>
        @endif

        <!-- SEO Content Section -->
        <div class="my-16 prose prose-lg max-w-none">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Om tjänster i {{ $city->name }}
                </h2>
                <p class="text-gray-700 leading-relaxed mb-4">
                    {{ $city->name }} erbjuder ett brett utbud av professionella tjänster för både privatpersoner och företag. 
                    Oavsett om du behöver hjälp med städning, hantverkstjänster, trädgårdsskötsel eller andra tjänster, 
                    hittar du verifierade och pålitliga partners här.
                </p>
                <p class="text-gray-700 leading-relaxed mb-4">
                    Alla våra samarbetspartners i {{ $city->name }} är noggrant utvalda och genomgår regelbunden kvalitetskontroll. 
                    Vi erbjuder transparenta priser, snabb bokning och möjlighet till ROT-avdrag på kvalificerade tjänster.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Genom vår plattform får du tillgång till {{ $stats['total_services'] }}+ tjänster från {{ $stats['total_companies'] }}+ 
                    verifierade partners i {{ $city->name }}. Boka enkelt online och få bekräftelse direkt!
                </p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="my-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">
                Redo att Boka en Tjänst i {{ $city->name }}?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Välj bland {{ $stats['total_services'] }}+ tjänster från verifierade partners. Snabb bokning, transparenta priser och kvalitetsgaranti.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('public.services', ['city' => $city->id]) }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition shadow-xl">
                    🛠️ Bläddra Tjänster
                </a>
                <a href="{{ route('public.companies', ['city' => $city->id]) }}" 
                   class="inline-flex items-center px-8 py-4 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 transition shadow-xl border-2 border-white/30">
                    🏢 Se Partner
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
