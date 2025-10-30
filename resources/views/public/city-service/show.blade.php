@extends('layouts.public')

@php
    $multiplier = $multiplier ?? (float) ($city->price_multiplier ?? 1.0);
    $basePrice = (float) ($service->base_price ?? 500);
    $hourBase = (float) ($service->hourly_rate ?? 400);
    $priceFrom = $priceFrom ?? (int) round($basePrice * $multiplier);
    $priceRangeMax = $priceRangeMax ?? (int) round($priceFrom * 2.5);
    $hourlyRate = $hourlyRate ?? $hourBase;
    
    // Enhanced SEO variables
    $seoTitle = $seoTitle ?? ($service->name . ' i ' . $city->name . ' - Professionell service från ' . number_format($priceFrom, 0, ',', ' ') . ' SEK');
    $seoDescription = $seoDescription ?? ('Boka ' . strtolower($service->name) . ' i ' . $city->name . '. Professionell service från ' . number_format($priceFrom, 0, ',', ' ') . ' SEK. Verifierade utförare, transparenta priser, ROT-avdrag. Snabb bokning online.');
    $heroTitle = $heroTitle ?? ($service->name . ' i ' . $city->name);
    $heroSubtitle = $heroSubtitle ?? 'Professionell ' . strtolower($service->name) . ' i ' . $city->name . ' från ' . number_format($priceFrom, 0, ',', ' ') . ' SEK • Verifierade utförare • ROT-avdrag';
    
    // Prefer booking form for this service
    $activeForm = $service->active_form ?? null;
    $formToken = $activeForm->public_token ?? ($activeForm->token ?? null);
    $ctaUrl = $ctaUrl ?? ($formToken ? route('public.form', ['token' => $formToken]) : route('public.pricing.service', $service->slug));
    
    // Service-specific content based on content.md
    $serviceBenefits = match($service->name) {
        'Hemstädning' => [
            'Spara tid med professionell städning',
            'Miljövänliga produkter och metoder',
            'ROT-avdrag 30% på alla städtjänster',
            'Regelbundna rabatter för återkommande kunder',
            'Flexibla tider som passar dig',
            'Garanti på utfört arbete'
        ],
        'Flyttstädning' => [
            'Städning enligt Folksams standard',
            'Professionell personal med erfarenhet',
            'Alla städmaterial inkluderade',
            'Flexibla tider för flyttdagen',
            'Garanti på utfört arbete',
            'Snabb och effektiv service'
        ],
        'VVS-tjänster' => [
            'Licensierade VVS-tekniker',
            'ROT-avdrag 30% på VVS-arbeten',
            'Snabb service och akutreparationer',
            'Garanti på utfört arbete',
            'Professionell rådgivning',
            'Alla verktyg och material inkluderade'
        ],
        'Målning' => [
            'Licensierade målare med erfarenhet',
            'ROT-avdrag 30% på målning',
            'Högkvalitativa färger och produkter',
            'Förberedelse av ytor inkluderat',
            'Garanti på utfört arbete',
            'Professionell rådgivning om färgval'
        ],
        'Gräsklippning' => [
            'Professionell trädgårdsutrustning',
            'ROT-avdrag 30% på trädgårdstjänster',
            'Miljövänliga metoder',
            'Regelbunden service enligt överenskommelse',
            'Avfallshantering inkluderat',
            'Erfaren personal med kännedom om växter'
        ],
        default => [
            'Professionell service med erfaren personal',
            'Transparenta priser utan dolda avgifter',
            'Garanti på utfört arbete',
            'Snabb och pålitlig service',
            'Flexibla betalningsalternativ',
            'Kundsupport via telefon och chat'
        ]
    };
    
    $howItWorks = [
        [
            'step' => '1',
            'title' => 'Boka enkelt online',
            'description' => 'Fyll i vårt formulär och få en omedelbar bekräftelse på din bokning.'
        ],
        [
            'step' => '2', 
            'title' => 'Vi matchar dig med rätt utförare',
            'description' => 'Vårt system hittar den bästa verifierade utföraren i ' . $city->name . ' för ditt behov.'
        ],
        [
            'step' => '3',
            'title' => 'Professionell service',
            'description' => 'Din utförare kommer i tid och genomför arbetet med högsta kvalitet.'
        ],
        [
            'step' => '4',
            'title' => 'Nöjd kund-garanti',
            'description' => 'Vi garanterar din tillfredsställelse. Om du inte är nöjd åtgärdar vi det.'
        ]
    ];
@endphp

@section('title', $seoTitle)
@section('description', $seoDescription)

@push('meta')
    <meta name="keywords" content="{{ $service->name }} i {{ $city->name }}, {{ strtolower($service->name) }} {{ $city->name }}, professionell {{ strtolower($service->name) }}, {{ $city->name }} tjänster, ROT-avdrag, transparenta priser">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:type" content="service">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="geo.region" content="SE">
    <meta name="geo.placename" content="{{ $city->name }}">
    <meta name="geo.position" content="{{ $city->latitude ?? '' }};{{ $city->longitude ?? '' }}">
    <meta name="ICBM" content="{{ $city->latitude ?? '' }}, {{ $city->longitude ?? '' }}">
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4">{{ $heroTitle }}</h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8">{{ $heroSubtitle }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ $ctaUrl }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-700 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl">
                    🚀 Boka nu
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
                <a href="#priser" class="inline-flex items-center px-6 py-3 border-2 border-white text-white rounded-xl font-semibold hover:bg-white hover:text-blue-700 transition-all">
                    📊 Se priser
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Intro Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Professionell {{ strtolower($service->name) }} Stockholm - Sveriges bästa tjänsteplattform
            </h2>
            <p class="text-xl text-gray-700 leading-relaxed max-w-4xl mx-auto">
                Upptäck pålitlig och effektiv {{ strtolower($service->name) }} i {{ $city->name }} med Bitra. 
                Vi samarbetar med noggrant utvalda och verifierade partners som levererar högsta kvalitet, 
                punktlighet och trygghet. Våra priser är transparenta och anpassade efter lokala förutsättningar för {{ strtolower($service->name) }} Stockholm.
            </p>
        </div>
    </div>
</section>

<!-- How Bitra Works -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Så fungerar Bitra för {{ strtolower($service->name) }} Stockholm</h2>
            <p class="text-xl text-gray-600">Enhetlig plattform för enkel bokning och säkra register</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($howItWorks as $step)
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                    {{ $step['step'] }}
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $step['title'] }}</h3>
                <p class="text-gray-600">{{ $step['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Varför välja Bitra för {{ strtolower($service->name) }} Stockholm?</h2>
            <p class="text-xl text-gray-600">Fördelar med vår plattform</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($serviceBenefits as $benefit)
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">✨</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $benefit }}</h3>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="priser" class="py-16 bg-gradient-to-r from-blue-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Priser för {{ strtolower($service->name) }} Stockholm</h2>
            <p class="text-xl text-gray-600">Transparenta priser utan dolda avgifter</p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="text-center">
                    <div class="flex justify-center items-baseline mb-4">
                        <span class="text-5xl font-bold text-green-600">{{ number_format($priceFrom, 0, ',', ' ') }}</span>
                        <span class="text-2xl text-gray-600 ml-2">SEK</span>
                        <span class="text-lg text-gray-500 ml-2">från</span>
                    </div>
                    <p class="text-lg text-gray-600 mb-6">
                        Priser varierar beroende på omfattning och komplexitet i {{ $city->name }}. 
                        Kontakta oss för en personlig offert anpassad efter dina behov.
                    </p>
                    <a href="{{ $ctaUrl }}" class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Få offert nu
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What's Included/Excluded -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- What's Included -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">✅ Vad ingår i {{ strtolower($service->name) }} i {{ $city->name }}?</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Noggrant utvalda och verifierade partners i {{ $city->name }}</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Professionell personal med erfarenhet</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Alla nödvändiga material och verktyg</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Försäkring och garanti</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">ROT-avdrag för berättigade tjänster</span>
                    </li>
                </ul>
            </div>

            <!-- What's Not Included -->
            <div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">❌ Vad ingår inte?</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Specialutrustning som kräver extra kostnad</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Material som kräver särskild beställning</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Arbete som kräver bygglov</span>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Arbete utanför normal arbetstid (helger/kvällar)</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Add-ons Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Tilläggstjänster för {{ strtolower($service->name) }} i {{ $city->name }}</h2>
            <p class="text-xl text-gray-600">Anpassa din service efter dina behov</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg p-6 shadow-md">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Extra arbetsuppgifter</h4>
                <div class="text-2xl font-bold text-blue-600 mb-2">+ 100 SEK</div>
                <p class="text-gray-600 text-sm">Om det finns andra arbetsuppgifter som inte nämns i bokningen</p>
            </div>
            <div class="bg-white rounded-lg p-6 shadow-md">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Helger och kvällar</h4>
                <div class="text-2xl font-bold text-blue-600 mb-2">+ 25%</div>
                <p class="text-gray-600 text-sm">Service på helger och efter 18:00 i {{ $city->name }}</p>
            </div>
            <div class="bg-white rounded-lg p-6 shadow-md">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Extra material</h4>
                <div class="text-2xl font-bold text-blue-600 mb-2">Kostnad + 15%</div>
                <p class="text-gray-600 text-sm">Specialmaterial som inte ingår i standardpriset</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Vanliga frågor om {{ strtolower($service->name) }} i {{ $city->name }}</h2>
            <p class="text-xl text-gray-600">Svar på de vanligaste frågorna</p>
        </div>
        
        <div class="space-y-4">
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Hur beräknas priset för {{ strtolower($service->name) }} i {{ $city->name }}?</h4>
                <p class="text-gray-600">Priset baseras på tjänstens omfattning, tidsåtgång och eventuella specialkrav. Vi ger alltid en transparent offert innan arbetet påbörjas. Lokala faktorer i {{ $city->name }} påverkar prissättningen.</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Finns det några dolda avgifter?</h4>
                <p class="text-gray-600">Nej, alla priser är transparenta. Det enda som kan tillkomma är specialmaterial som diskuteras i förväg. Vi eliminerar mellanhänder och prismanipulation.</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Kan jag få ROT-avdrag på {{ strtolower($service->name) }}?</h4>
                <p class="text-gray-600">Ja, många av våra tjänster är ROT-berättigade. Vi hjälper dig med all nödvändig dokumentation för ROT-avdrag på {{ strtolower($service->name) }} i {{ $city->name }}.</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Vad händer om jag inte är nöjd med {{ strtolower($service->name) }}?</h4>
                <p class="text-gray-600">Vi garanterar din tillfredsställelse. Om du inte är nöjd kommer vi att åtgärda problemet utan extra kostnad. Bitra står bakom varje tjänst vi erbjuder.</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Kan jag boka {{ strtolower($service->name) }} online i {{ $city->name }}?</h4>
                <p class="text-gray-600">Absolut! Vår plattform är designad för enkel online-bokning. Du kan boka, betala och hantera dina tjänster helt digitalt. Alla bokningar dokumenteras säkert.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Redo att boka {{ strtolower($service->name) }} i {{ $city->name }}?</h2>
        <p class="text-xl mb-8 text-blue-100">Få en gratis offert på bara några minuter. Transparenta priser, verifierade utförare, garanti.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ $ctaUrl }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl">
                🚀 Boka nu
            </a>
            <a href="{{ route('public.pricing.service', $service->slug) }}" class="inline-block border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-all">
                📊 Se alla priser
            </a>
        </div>
    </div>
</section>

<!-- Enhanced Schema Markup -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Service",
    "name": "{{ $service->name }} i {{ $city->name }}",
    "description": "Professionell {{ strtolower($service->name) }} i {{ $city->name }}. Verifierade utförare, transparenta priser från {{ number_format($priceFrom, 0, ',', ' ') }} SEK, ROT-avdrag. Boka enkelt online.",
    "url": "{{ url()->current() }}",
    "provider": {
        "@@type": "Organization",
        "name": "Bitra",
        "url": "{{ url('/') }}",
        "description": "Sveriges bästa tjänsteplattform för verifierade och högkvalitativa tjänster"
    },
    "offers": {
        "@@type": "Offer",
        "price": "{{ $priceFrom }}",
        "priceCurrency": "SEK",
        "availability": "https://schema.org/InStock",
        "priceRange": "{{ $priceFrom }}-{{ $priceRangeMax }} SEK",
        "description": "Professionell {{ strtolower($service->name) }} i {{ $city->name }} med transparenta priser"
    },
    "areaServed": {
        "@@type": "City",
        "name": "{{ $city->name }}",
        "containedInPlace": {
            "@@type": "Country",
            "name": "Sweden"
        }
    },
    "serviceType": "{{ $service->name }}",
    "category": "{{ $service->category->name ?? 'Tjänster' }}",
    "hasOfferCatalog": {
        "@@type": "OfferCatalog",
        "name": "{{ $service->name }} tjänster i {{ $city->name }}",
        "itemListElement": [
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "Service",
                    "name": "{{ $service->name }} i {{ $city->name }}"
                }
            }
        ]
    }
}
</script>
@endsection

