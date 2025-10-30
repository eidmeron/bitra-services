@php
    use App\Services\PageContentService;
    
    $pageContent = PageContentService::getPageContent('why-us', [
        'meta_title' => 'Varför välja Bitra - Din pålitliga plattform för hemtjänster',
        'meta_description' => 'Upptäck varför Bitra är Sveriges bästa tjänsteplattform. Verifierade företag, transparenta priser och kvalitetsgaranti.',
        'meta_keywords' => 'varför bitra, tjänsteplattform, verifierade företag, kvalitetsgaranti, Sverige',
        'hero_title' => 'Varför välja <span class="text-yellow-300">Bitra</span>?',
        'hero_subtitle' => 'Din pålitliga plattform för verifierade och högkvalitativa tjänster i hela Sverige och internationellt.',
    ]);
    
    $seoData = PageContentService::getSeoData('why-us', [
        'title' => $pageContent['meta_title'],
        'description' => $pageContent['meta_description'],
        'keywords' => $pageContent['meta_keywords'],
        'og_title' => $pageContent['og_title'],
        'og_description' => $pageContent['og_description'],
        'og_image' => $pageContent['og_image'],
    ]);
@endphp

@extends('layouts.public')

@section('title', $seoData['title'])
@section('meta_description', $seoData['description'])
@section('meta_keywords', $seoData['keywords'])

@push('meta')
    <meta property="og:title" content="{{ $seoData['og_title'] }}">
    <meta property="og:description" content="{{ $seoData['og_description'] }}">
    @if($seoData['og_image'])
        <meta property="og:image" content="{{ $seoData['og_image'] }}">
    @endif
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                {!! $pageContent['hero_title'] ?: 'Varför välja <span class="text-yellow-300">Bitra</span>?' !!}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-4xl mx-auto">
                {{ $pageContent['hero_subtitle'] ?: 'Din pålitliga plattform för verifierade och högkvalitativa tjänster i hela Sverige och internationellt. Vi förenklar att hitta, boka och hantera professionella tjänster — med tydliga priser, garantier och förmåner.' }}
            </p>
        </div>
    </div>
</section>

<!-- How Bitra Works -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Så fungerar Bitra
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Enkelt, säkert och transparent - så får du den bästa tjänsten
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">🔍</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Noggrant utvalda partners</h3>
                <p class="text-gray-600">
                    Vi samarbetar endast med utvalda och granskade företag i varje stad. Varje partner uppfyller Bitras kvalitetskrav så att du alltid får pålitlig och professionell service.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">🏢</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Enhetlig plattform</h3>
                <p class="text-gray-600">
                    Alla tjänster samlade på ett ställe: sök, jämför, boka och betala utan mellanhänder. Priserna är tydliga och fasta — inga dolda avgifter eller prismanipulation.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Enkel bokning & säkra register</h3>
                <p class="text-gray-600">
                    Boka på några klick. Varje bokning dokumenteras och sparas säkert så att din servicehistorik är tillgänglig när du behöver den.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">✅</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Nöjd kund-garanti</h3>
                <p class="text-gray-600">
                    Bitra står bakom varje företag och varje tjänst. Om något inte motsvarar förväntningarna ser vårt supportteam till att snabbt åtgärda det.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Bitra -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Fördelar med Bitra
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Varför våra kunder väljer oss framför andra
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Save Time -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-3xl">⏰</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Spara tid</h3>
                </div>
                <p class="text-gray-600 text-lg">
                    Slipp leta efter pålitliga leverantörer. Vi har redan gjort jobbet åt dig genom att verifiera alla våra partners.
                </p>
            </div>

            <!-- Best Value -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-3xl">💰</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Bästa värde</h3>
                </div>
                <p class="text-gray-600 text-lg">
                    Konkurrenskraftiga och transparenta priser som passar dina behov. Inga dolda avgifter eller prismanipulation.
                </p>
            </div>

            <!-- Confidence -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-3xl">🛡️</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Trygghet</h3>
                </div>
                <p class="text-gray-600 text-lg">
                    Endast förhandsvalda och kvalitetsgaranterade företag. Varje partner uppfyller Bitras kvalitetskrav.
                </p>
            </div>

            <!-- Lifetime Records -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-3xl">📋</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Livstidsregister</h3>
                </div>
                <p class="text-gray-600 text-lg">
                    All din servicehistorik samlad och säker. Tillgänglig för alltid i vårt enkla och säkra system.
                </p>
            </div>

            <!-- Loyalty Benefits -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-3xl">🎁</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Belöningar</h3>
                </div>
                <p class="text-gray-600 text-lg">
                    Tjäna poäng på varje bokning och vid nya medlemsregistreringar. Använd poängen för framtida tjänster.
                </p>
            </div>

            <!-- Regular Discounts -->
            <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-3xl">🏷️</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Regelbundna rabatter</h3>
                </div>
                <p class="text-gray-600 text-lg">
                    Njut av regelbundna rabatter och kampanjer. Få tillgång till exklusiva medlemserbjudanden.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Perfect for Individuals and Businesses -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Perfekt för privatpersoner och företag
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Oavsett om du är privatperson, småföretagare eller ansvarig i en organisation erbjuder Bitra skräddarsydda lösningar och professionell support för trygg och effektiv tjänsteleverans.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- For Individuals -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">👤</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">För privatpersoner</h3>
                    <p class="text-gray-600 text-lg">
                        Få tillgång till professionella tjänster för ditt hem. Från städning och trädgårdsarbete till renovering och flytt - allt under ett tak.
                    </p>
                </div>
                <ul class="space-y-4">
                    <li class="flex items-center">
                        <span class="text-green-500 mr-3">✓</span>
                        <span class="text-gray-700">Enkel bokning online</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-3">✓</span>
                        <span class="text-gray-700">Transparenta priser</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-3">✓</span>
                        <span class="text-gray-700">Kvalitetsgaranti</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-3">✓</span>
                        <span class="text-gray-700">Poäng och rabatter</span>
                    </li>
                </ul>
            </div>

            <!-- For Businesses -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🏢</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">För företag</h3>
                    <p class="text-gray-600 text-lg">
                        Skräddarsydda lösningar för företag av alla storlekar. Professionell support och flexibla avtal som passar dina behov.
                    </p>
                </div>
                <ul class="space-y-4">
                    <li class="flex items-center">
                        <span class="text-green-500 mr-3">✓</span>
                        <span class="text-gray-700">Företagsavtal</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-3">✓</span>
                        <span class="text-gray-700">Dedikerad support</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-3">✓</span>
                        <span class="text-gray-700">Volymrabatter</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-green-500 mr-3">✓</span>
                        <span class="text-gray-700">Rapporter och analyser</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews -->
@if($platformReviews->isNotEmpty())
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Vad våra kunder säger
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Läs recensioner från våra nöjda kunder och upplev kvaliteten själv
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($platformReviews as $review)
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-4">
                        @for($i = 0; $i < $review->bitra_rating; $i++)
                            <span class="text-yellow-400 text-xl">⭐</span>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-6 italic text-lg">"{{ $review->bitra_review_text }}"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($review->booking->user->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <p class="font-semibold text-gray-900">{{ $review->booking->user->name ?? 'Anonym' }}</p>
                            <p class="text-sm text-gray-500">{{ $review->service->name ?? 'Tjänst' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Bli medlem i Bitra idag
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Skapa ett konto, tjäna poäng vid din första bokning och upplev enkla, säkra och högkvalitativa tjänster. 
            Boka nu så förbinder vi dig med de bästa leverantörerna i din närhet.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('public.categories') }}" 
               class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <span class="mr-2">🚀</span>
                Börja boka nu
            </a>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-blue-600 transition-colors duration-300">
                <span>Kontakta oss</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

@include('components.cta-section')
@endsection
