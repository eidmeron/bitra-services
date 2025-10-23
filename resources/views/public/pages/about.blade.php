@extends('layouts.public')

@php
$page = page_content('about');
@endphp

<x-page-seo pageKey="about" />

@section('content')
<x-toast />

<div class="bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20"
         @if($page && $page->hero_image) style="background-image: linear-gradient(rgba(37, 99, 235, 0.8), rgba(147, 51, 234, 0.8)), url('{{ Storage::url($page->hero_image) }}'); background-size: cover; background-position: center;" @endif>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">
                    {{ $page && $page->hero_title ? $page->hero_title : '👋 Om ' . site_name() }}
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    {{ $page && $page->hero_subtitle ? $page->hero_subtitle : 'Din pålitliga partner för professionella tjänster i hela Sverige' }}
                </p>
                @if($page && $page->hero_cta_text && $page->hero_cta_link)
                    <a href="{{ $page->hero_cta_link }}" 
                       class="inline-flex items-center px-8 py-4 bg-white text-blue-600 rounded-xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                        {{ $page->hero_cta_text }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {{-- Dynamic Sections from CMS --}}
        @if($page && $page->sections && count($page->sections) > 0)
            @foreach($page->sections as $section)
                <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-16">
                    @if(isset($section['title']))
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">
                            {{ $section['icon'] ?? '' }} {{ $section['title'] }}
                        </h2>
                    @endif
                    @if(isset($section['content']))
                        <div class="prose prose-lg max-w-4xl mx-auto text-gray-700">
                            {!! nl2br(e($section['content'])) !!}
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <!-- Our Story (Default) -->
            <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">
                    📖 Vår Historia
                </h2>
                <div class="prose prose-lg max-w-4xl mx-auto text-gray-700">
                    <p class="text-lg leading-relaxed mb-4">
                        {{ site_name() }} grundades med en vision att förenkla och förbättra hur människor hittar och bokar professionella tjänster. 
                        Vi såg ett behov av en pålitlig plattform där privatpersoner enkelt kan få kontakt med kvalificerade tjänsteleverantörer.
                    </p>
                    <p class="text-lg leading-relaxed mb-4">
                        Idag är vi en av Sveriges ledande bokningsplattformar med hundratals verifierade företag och tusentals nöjda kunder. 
                        Vi täcker ett brett spektrum av tjänster - från hemstädning och flytthjälp till snöskottning och trädgårdsskötsel.
                    </p>
                    <p class="text-lg leading-relaxed">
                        Vår mission är att göra vardagen enklare för våra kunder genom att erbjuda en smidig, trygg och transparent bokningsupplevelse.
                    </p>
                </div>
            </div>
        @endif

        {{-- Features from CMS --}}
        @if($page && $page->features && count($page->features) > 0)
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                    Våra Fördelar
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($page->features as $feature)
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                            @if(isset($feature['icon']))
                                <div class="text-5xl mb-4 text-center">{{ $feature['icon'] }}</div>
                            @endif
                            @if(isset($feature['title']))
                                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">{{ $feature['title'] }}</h3>
                            @endif
                            @if(isset($feature['description']))
                                <p class="text-gray-700 text-center">{{ $feature['description'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Values (Default) -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                    💎 Våra Värderingar
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <div class="text-5xl mb-4 text-center">🎯</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Kvalitet</h3>
                    <p class="text-gray-700 text-center">
                        Vi samarbetar endast med noggrant utvalda och verifierade företag som uppfyller våra höga kvalitetsstandarder.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <div class="text-5xl mb-4 text-center">🤝</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Trygghet</h3>
                    <p class="text-gray-700 text-center">
                        Din säkerhet och integritet är vår högsta prioritet. Alla transaktioner och personuppgifter hanteras säkert.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <div class="text-5xl mb-4 text-center">⚡</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Effektivitet</h3>
                    <p class="text-gray-700 text-center">
                        Vi gör bokningsprocessen snabb och enkel. Mindre tid åt administration, mer tid åt det som verkligen betyder något.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
                    <div class="text-5xl mb-4 text-center">💚</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Hållbarhet</h3>
                    <p class="text-gray-700 text-center">
                        Vi arbetar aktivt för att främja hållbara lösningar och lokala företag som tar ansvar för miljön.
                    </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 lg:p-12 mb-16">
            <h2 class="text-3xl font-bold text-white mb-8 text-center">
                📊 I Siffror
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-white mb-2">500+</div>
                    <div class="text-blue-100">Verifierade Företag</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-white mb-2">10,000+</div>
                    <div class="text-blue-100">Nöjda Kunder</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-white mb-2">50+</div>
                    <div class="text-blue-100">Tjänstekategorier</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-white mb-2">4.8/5</div>
                    <div class="text-blue-100">Genomsnittligt Betyg</div>
                </div>
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                🌟 Varför Välja Oss?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Verifierade Företag</h3>
                        <p class="text-gray-600">
                            Alla våra samarbetspartners genomgår en noggrann granskning innan de godkänns. 
                            Vi kontrollerar F-skattsedel, försäkringar och referenser.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">💬</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Transparenta Recensioner</h3>
                        <p class="text-gray-600">
                            Läs äkta recensioner från riktiga kunder. Vi visar både positiva och negativa omdömen 
                            för att du ska kunna göra ett informerat val.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Inga Dolda Avgifter</h3>
                        <p class="text-gray-600">
                            Det du ser är vad du betalar. Inga överraskningar eller dolda kostnader. 
                            Priserna du får är bindande offerter från företagen.
                        </p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🎧</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Kundservice</h3>
                        <p class="text-gray-600">
                            Vårt supportteam finns här för att hjälpa dig. Kontakta oss via chatt, e-post 
                            eller telefon om du har några frågor.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">
                Bli en del av vår community
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Oavsett om du är privatperson som behöver hjälp eller företag som vill erbjuda tjänster - vi välkomnar dig!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.services') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 rounded-xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                    <span class="mr-2">🚀</span>
                    Boka Tjänst
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-bold text-lg hover:bg-white hover:text-blue-600 transition-all">
                    <span class="mr-2">📧</span>
                    Kontakta Oss
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
