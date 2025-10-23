@extends('layouts.public')

@section('title', 'Alla Städer i Sverige - Boka Professionella Tjänster')
@section('meta_description', 'Hitta och boka professionella tjänster i alla svenska städer. Verifierade företag, snabb bokning och transparenta priser.')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="mb-4">
                <a href="{{ route('welcome') }}" class="text-blue-200 hover:text-white text-sm">
                    ← Tillbaka till startsidan
                </a>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in-up">
                🗺️ Alla Städer i Sverige
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-8 animate-fade-in-up delay-1">
                Välj din stad och hitta verifierade professionella tjänster. Vi täcker hela Sverige med kvalitetsgaranti!
            </p>

            <!-- Quick Stats -->
            <div class="flex flex-wrap justify-center gap-6 mt-8 animate-fade-in-up delay-2">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-4 border border-white/30">
                    <div class="text-3xl font-bold">{{ $cities->count() }}+</div>
                    <div class="text-sm text-blue-200">Städer</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-4 border border-white/30">
                    <div class="text-3xl font-bold">{{ $cities->sum('services_count') }}+</div>
                    <div class="text-sm text-blue-200">Tjänster</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-4 border border-white/30">
                    <div class="text-3xl font-bold">{{ $cities->sum('companies_count') }}+</div>
                    <div class="text-sm text-blue-200">Företag</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @foreach($citiesByZone as $zoneName => $zoneCities)
        <div class="mb-16">
            <!-- Zone Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 flex items-center mb-2">
                    <span class="text-4xl mr-3">📍</span>
                    {{ $zoneName }}
                </h2>
                <p class="text-gray-600 ml-14">
                    {{ $zoneCities->count() }} {{ $zoneCities->count() === 1 ? 'stad' : 'städer' }} • {{ $zoneCities->sum('services_count') }} tjänster
                </p>
            </div>

            <!-- Cities Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($zoneCities as $city)
                <a href="{{ route('public.city.show', $city->slug) }}" 
                   class="group bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <!-- City Icon -->
                    <div class="text-5xl mb-4 text-center group-hover:scale-110 transition-transform">
                        🏙️
                    </div>
                    
                    <!-- City Name -->
                    <h3 class="text-xl font-bold text-gray-900 text-center mb-2 group-hover:text-blue-600 transition-colors">
                        {{ $city->name }}
                    </h3>
                    
                    <!-- Stats -->
                    <div class="space-y-2 mt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Tjänster
                            </span>
                            <span class="font-semibold text-blue-600">{{ $city->services_count ?? 0 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Företag
                            </span>
                            <span class="font-semibold text-purple-600">{{ $city->companies_count ?? 0 }}</span>
                        </div>
                    </div>

                    <!-- Action Arrow -->
                    <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                        <span class="text-blue-600 font-semibold text-sm group-hover:underline flex items-center justify-center">
                            Utforska tjänster
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- CTA Section -->
        <div class="mt-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">
                Hittar du inte din stad?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Vi expanderar ständigt! Kontakta oss så hjälper vi dig hitta professionella tjänster i din stad.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Kontakta Oss
                </a>
                <a href="{{ route('welcome') }}" 
                   class="inline-flex items-center px-8 py-4 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 transition shadow-xl border-2 border-white/30">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Tillbaka Till Startsidan
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .delay-1 {
        animation-delay: 0.2s;
        opacity: 0;
    }

    .delay-2 {
        animation-delay: 0.4s;
        opacity: 0;
    }
</style>
@endsection
