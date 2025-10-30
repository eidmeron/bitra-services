@php
    use App\Services\PageContentService;
    
    $pageContent = PageContentService::getPageContent('how-it-works', [
        'meta_title' => 'Så fungerar Bitra - Enkel bokning av hemtjänster',
        'meta_description' => 'Lär dig hur Bitra fungerar - från sökning till bokning. Enkelt, säkert och transparent system för alla dina tjänstebehov.',
        'meta_keywords' => 'så fungerar bitra, tjänstebokning, process, enkelt, säkert',
        'hero_title' => 'Så fungerar Bitra',
        'hero_subtitle' => 'Enkelt, säkert och transparent - så får du den bästa tjänsten. Vi förenklar att hitta, boka och hantera professionella tjänster.',
    ]);
    
    $seoData = PageContentService::getSeoData('how-it-works', [
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
                {{ $pageContent['hero_title'] ?: 'Så fungerar Bitra' }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-4xl mx-auto">
                {{ $pageContent['hero_subtitle'] ?: 'Enkelt, säkert och transparent - så får du den bästa tjänsten. Vi förenklar att hitta, boka och hantera professionella tjänster.' }}
            </p>
        </div>
    </div>
</section>

<!-- Main Process -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Vår process i fyra enkla steg
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Från första klicket till färdig tjänst - vi guidar dig genom hela processen
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="relative">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-3xl">🔍</span>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        1
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Noggrant utvalda partners</h3>
                <p class="text-gray-600 text-lg">
                    Vi samarbetar endast med utvalda och granskade företag i varje stad. Varje partner uppfyller Bitras kvalitetskrav så att du alltid får pålitlig och professionell service.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="relative">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-3xl">🏢</span>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        2
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Enhetlig plattform</h3>
                <p class="text-gray-600 text-lg">
                    Alla tjänster samlade på ett ställe: sök, jämför, boka och betala utan mellanhänder. Priserna är tydliga och fasta — inga dolda avgifter eller prismanipulation.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="relative">
                    <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-3xl">📱</span>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        3
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Enkel bokning & säkra register</h3>
                <p class="text-gray-600 text-lg">
                    Boka på några klick. Varje bokning dokumenteras och sparas säkert så att din servicehistorik är tillgänglig när du behöver den.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="text-center">
                <div class="relative">
                    <div class="w-24 h-24 bg-gradient-to-br from-red-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-3xl">✅</span>
                    </div>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        4
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Nöjd kund-garanti</h3>
                <p class="text-gray-600 text-lg">
                    Bitra står bakom varje företag och varje tjänst. Om något inte motsvarar förväntningarna ser vårt supportteam till att snabbt åtgärda det.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Process -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Detaljerad process
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Lär dig mer om hur vi säkerställer kvalitet och nöjdhet i varje steg
            </p>
        </div>

        <div class="space-y-12">
            <!-- Step 1 Detail -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🔍</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">1. Noggrant utvalda partners</h3>
                        <p class="text-gray-600 mb-4 text-lg">
                            Vi samarbetar endast med utvalda och granskade företag i varje stad. Varje partner genomgår en omfattande verifieringsprocess:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Bakgrundskontroll och referensverifiering</li>
                            <li>Försäkring och certifieringar</li>
                            <li>Kvalitetstester och kundnöjdhetsundersökningar</li>
                            <li>Löpande övervakning och utvärdering</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 2 Detail -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🏢</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">2. Enhetlig plattform</h3>
                        <p class="text-gray-600 mb-4 text-lg">
                            Alla tjänster samlade på ett ställe med transparenta priser och enkel jämförelse:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Sök och filtrera tjänster efter dina behov</li>
                            <li>Jämför priser och recensioner från olika företag</li>
                            <li>Boka direkt online utan mellanhänder</li>
                            <li>Transparenta priser utan dolda avgifter</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 3 Detail -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">📱</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">3. Enkel bokning & säkra register</h3>
                        <p class="text-gray-600 mb-4 text-lg">
                            Bokning på några klick med säker dokumentation av hela processen:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Fyll i vårt enkla bokningsformulär</li>
                            <li>Få bekräftelse och detaljer via e-post</li>
                            <li>Alla bokningar sparas säkert i vårt system</li>
                            <li>Tillgång till din servicehistorik för alltid</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Step 4 Detail -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">✅</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">4. Nöjd kund-garanti</h3>
                        <p class="text-gray-600 mb-4 text-lg">
                            Vi står bakom varje tjänst och säkerställer din nöjdhet:
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Kvalitetsgaranti på alla tjänster</li>
                            <li>Snabb support om något inte stämmer</li>
                            <li>Pengarna tillbaka om du inte är nöjd</li>
                            <li>Löpande uppföljning och förbättringar</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Fördelar med vår process
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Varför våra kunder älskar att använda Bitra
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Save Time -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">⏰</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Spara tid</h3>
                <p class="text-gray-600">
                    Slipp leta efter pålitliga leverantörer. Vi har redan gjort jobbet åt dig genom att verifiera alla våra partners.
                </p>
            </div>

            <!-- Best Value -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">💰</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Bästa värde</h3>
                <p class="text-gray-600">
                    Konkurrenskraftiga och transparenta priser som passar dina behov. Inga dolda avgifter eller prismanipulation.
                </p>
            </div>

            <!-- Confidence -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">🛡️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Trygghet</h3>
                <p class="text-gray-600">
                    Endast förhandsvalda och kvalitetsgaranterade företag. Varje partner uppfyller Bitras kvalitetskrav.
                </p>
            </div>

            <!-- Lifetime Records -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">📋</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Livstidsregister</h3>
                <p class="text-gray-600">
                    All din servicehistorik samlad och säker. Tillgänglig för alltid i vårt enkla och säkra system.
                </p>
            </div>

            <!-- Loyalty Benefits -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">🎁</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Belöningar</h3>
                <p class="text-gray-600">
                    Tjäna poäng på varje bokning och vid nya medlemsregistreringar. Använd poängen för framtida tjänster.
                </p>
            </div>

            <!-- Regular Discounts -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">🏷️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">Regelbundna rabatter</h3>
                <p class="text-gray-600">
                    Njut av regelbundna rabatter och kampanjer. Få tillgång till exklusiva medlemserbjudanden.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
            Redo att komma igång?
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Upptäck vår enkla process och boka din första tjänst idag. 
            Vi guidar dig genom hela resan från bokning till färdig tjänst.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('public.categories') }}" 
               class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <span class="mr-2">🚀</span>
                Börja boka nu
            </a>
            <a href="{{ route('public.why-us') }}" 
               class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-blue-600 transition-colors duration-300">
                <span>Läs mer om oss</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

@include('components.cta-section')
@endsection
