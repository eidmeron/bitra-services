@php
    use App\Services\PageContentService;
    
    $pageContent = PageContentService::getPageContent('about', [
        'meta_title' => 'Om oss - Bitra Services | Sveriges Ledande Plattform för Hemtjänster',
        'meta_description' => 'Lär dig mer om Bitra - Sveriges ledande plattform för att hitta och boka professionella tjänster. Vår mission och vision.',
        'meta_keywords' => 'om oss, bitra, tjänsteplattform, mission, vision, Sverige',
        'hero_title' => 'Om Bitra',
        'hero_subtitle' => 'Din pålitliga plattform för verifierade och högkvalitativa tjänster i hela Sverige och internationellt.',
    ]);
    
    $seoData = PageContentService::getSeoData('about', [
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
                {{ $pageContent['hero_title'] ?: 'Om Bitra' }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-4xl mx-auto">
                {{ $pageContent['hero_subtitle'] ?: 'Din pålitliga plattform för verifierade och högkvalitativa tjänster i hela Sverige och internationellt. Vi förenklar att hitta, boka och hantera professionella tjänster — med tydliga priser, garantier och förmåner.' }}
            </p>
        </div>
    </div>
</section>

<!-- About Bitra -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    Om Bitra
                </h2>
                <p class="text-lg text-gray-600 mb-6">
                    Bitra är en pålitlig plattform som kopplar samman kunder med verifierade företag och professionella tjänsteleverantörer i hela Sverige och andra länder. Vårt mål är att göra det enklare att hitta pålitliga och högkvalitativa tjänster genom ett transparent och säkert system.
                </p>
                <p class="text-lg text-gray-600 mb-6">
                    Vi samarbetar med noggrant utvalda företag och partners i varje stad och region. Varje partner väljs ut baserat på förtroende, kvalitet och kundnöjdhet. Bitra garanterar kvaliteten på alla företag och tjänster som finns på vår plattform – för att du som kund alltid ska bli nöjd.
                </p>
                <p class="text-lg text-gray-600 mb-8">
                    Vårt integrerade system eliminerar mellanhänder, dolda avgifter och prismanipulation. Alla priser är tydliga, rättvisa och kommer direkt från källan. Oavsett om du är privatperson eller företag erbjuder Bitra ett heltäckande utbud av tjänster i ett enda smidigt system, hanterat av ett professionellt, kreativt och engagerat team.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('public.categories') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <span class="mr-2">🚀</span>
                        Upptäck våra tjänster
                    </a>
                    <a href="{{ route('public.why-us') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="mr-2">ℹ️</span>
                        Varför välja oss
                    </a>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-8">
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 mb-2">1000+</div>
                        <div class="text-gray-600">Verifierade företag</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-green-600 mb-2">50+</div>
                        <div class="text-gray-600">Städer i Sverige</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-purple-600 mb-2">10+</div>
                        <div class="text-gray-600">Tjänstkategorier</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-orange-600 mb-2">24/7</div>
                        <div class="text-gray-600">Kundsupport</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How Bitra Works -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Så fungerar Bitra
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Enkelt, säkert och transparent - så får du den bästa tjänsten
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">🔍</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Noggrant utvalda partners</h3>
                <p class="text-gray-600">
                    Vi samarbetar endast med utvalda och granskade företag i varje stad. Varje partner uppfyller Bitras kvalitetskrav så att du alltid får pålitlig och professionell service.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">🏢</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Enhetlig plattform</h3>
                <p class="text-gray-600">
                    Alla tjänster samlade på ett ställe: sök, jämför, boka och betala utan mellanhänder. Priserna är tydliga och fasta — inga dolda avgifter eller prismanipulation.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">📱</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Enkel bokning & säkra register</h3>
                <p class="text-gray-600">
                    Boka på några klick. Varje bokning dokumenteras och sparas säkert så att din servicehistorik är tillgänglig när du behöver den.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">✅</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Nöjd kund-garanti</h3>
                <p class="text-gray-600">
                    Bitra står bakom varje företag och varje tjänst. Om något inte motsvarar förväntningarna ser vårt supportteam till att snabbt åtgärda det.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Advantages of Using Bitra -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Fördelar med Bitra
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Varför våra kunder älskar att använda vår plattform
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Save Time -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-2xl">⏰</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Spara tid</h3>
                <p class="text-gray-600">
                    Slipp leta efter pålitliga företag och jämföra otaliga erbjudanden. Vi har redan gjort jobbet åt dig genom att verifiera alla våra partners.
                </p>
            </div>

            <!-- Best Value -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-2xl">💰</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Bästa värde</h3>
                <p class="text-gray-600">
                    Få de bästa priserna för varje tjänst, anpassade efter dina behov. Konkurrenskraftiga och transparenta priser utan dolda avgifter.
                </p>
            </div>

            <!-- Regular Discounts -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-2xl">🏷️</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Regelbundna rabatter</h3>
                <p class="text-gray-600">
                    Njut av regelbundna rabatter och kampanjer. Få tillgång till exklusiva medlemserbjudanden och säsongskampanjer.
                </p>
            </div>

            <!-- Reward Points -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-2xl">🎁</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Belöningar</h3>
                <p class="text-gray-600">
                    Tjäna poäng vid varje bokning och vid nyregistrering av medlemmar – poängen kan användas för framtida tjänster.
                </p>
            </div>

            <!-- Lifetime Records -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-2xl">📋</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Livstidsregister</h3>
                <p class="text-gray-600">
                    Ha all din servicehistorik samlad i ett lättanvänt och säkert system – tillgängligt för alltid.
                </p>
            </div>

            <!-- Quality Guarantee -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                    <span class="text-2xl">🛡️</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Kvalitetsgaranti</h3>
                <p class="text-gray-600">
                    Vi samarbetar endast med certifierade och godkända företag. I varje stad är våra partnerskap begränsade för att säkerställa hög kvalitet och exklusivitet.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Perfect for Individuals and Businesses -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                För privatpersoner och företag
            </h2>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                Oavsett om du är privatkund, småföretagare eller ansvarig i en organisation erbjuder Bitra skräddarsydda lösningar och professionell support för trygg och effektiv tjänsteleverans.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Individuals -->
            <div class="bg-white rounded-2xl p-8 shadow-lg text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">👤</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Privatpersoner</h3>
                <p class="text-gray-600 mb-6">
                    Perfekt för hemägare som behöver pålitliga tjänster. Få tillgång till verifierade leverantörer för allt från städning till renovering.
                </p>
                <ul class="text-left text-gray-600 space-y-2">
                    <li>• Hemstädning & underhåll</li>
                    <li>• Renovering & hantverk</li>
                    <li>• Trädgårdsarbete</li>
                    <li>• Flytt & transport</li>
                </ul>
            </div>

            <!-- Small Business -->
            <div class="bg-white rounded-2xl p-8 shadow-lg text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">🏢</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Småföretag</h3>
                <p class="text-gray-600 mb-6">
                    Idealisk för småföretag som behöver professionella tjänster utan att bryta budgeten. Få konkurrenskraftiga priser och garanterad kvalitet.
                </p>
                <ul class="text-left text-gray-600 space-y-2">
                    <li>• Kontorsstädning</li>
                    <li>• IT-support & teknik</li>
                    <li>• Marknadsföring & design</li>
                    <li>• Juridisk rådgivning</li>
                </ul>
            </div>

            <!-- Enterprise -->
            <div class="bg-white rounded-2xl p-8 shadow-lg text-center">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">🏭</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Stora organisationer</h3>
                <p class="text-gray-600 mb-6">
                    Skräddarsydda lösningar för stora organisationer. Få tillgång till en heltäckande plattform med professionell support och mätbara resultat.
                </p>
                <ul class="text-left text-gray-600 space-y-2">
                    <li>• Anläggningsunderhåll</li>
                    <li>• Säkerhet & övervakning</li>
                    <li>• HR & rekrytering</li>
                    <li>• Finansiella tjänster</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Customer Reviews -->
@if($platformReviews->isNotEmpty())
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Vad våra kunder säger
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Läs recensioner från våra nöjda kunder och upplev kvaliteten själv
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($platformReviews as $review)
                <div class="bg-white rounded-2xl p-8 shadow-lg">
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
        <h2 class="text-4xl font-bold text-white mb-6">
            Bli medlem i Bitra idag
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-4xl mx-auto">
            Skapa ett konto, tjäna poäng vid din första bokning och upplev enkla, säkra och högkvalitativa tjänster. 
            Boka nu så förbinder vi dig med de bästa leverantörerna i din närhet.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('public.categories') }}" 
               class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <span class="mr-2">🚀</span>
                Börja boka nu
            </a>
            <a href="{{ route('public.how-it-works') }}" 
               class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-blue-600 transition-colors duration-300">
                <span>Lär dig hur det fungerar</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

@include('components.cta-section')
@endsection
