@extends('layouts.public')

@php
$page = page_content('why-us');
@endphp

<x-page-seo pageKey="why-us" />

@section('content')
<x-toast />

<div class="bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-green-600 to-blue-600 text-white py-20"
         @if($page && $page->hero_image) style="background-image: linear-gradient(rgba(34, 197, 94, 0.8), rgba(37, 99, 235, 0.8)), url('{{ Storage::url($page->hero_image) }}'); background-size: cover; background-position: center;" @endif>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">
                    {{ $page && $page->hero_title ? $page->hero_title : '🏆 Varför Välja Bitra?' }}
                </h1>
                <p class="text-xl text-green-100 mb-8 max-w-3xl mx-auto">
                    {{ $page && $page->hero_subtitle ? $page->hero_subtitle : 'Sveriges ledande plattform för professionella tjänster - säker, snabb och prisvärd' }}
                </p>
                @if($page && $page->hero_cta_text && $page->hero_cta_link)
                    <a href="{{ $page->hero_cta_link }}" 
                       class="inline-flex items-center px-8 py-4 bg-white text-green-600 rounded-xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                        {{ $page->hero_cta_text }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Key Benefits Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <!-- Transparent Pricing -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 mx-auto">
                    💰
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    Transparenta Priser
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Inga dolda avgifter eller överraskningar. Få alltid ett tydligt pris innan du bokar.
                </p>
            </div>

            <!-- Quality Guarantee -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 mx-auto">
                    ✅
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    Kvalitetsgaranti
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Alla företag är verifierade, försäkrade och kvalitetssäkrade av vårt team.
                </p>
            </div>

            <!-- Fast Service -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 mx-auto">
                    ⚡
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    Snabb Service
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Vi matchar dig med kvalificerade företag inom 24 timmar. Ingen väntan.
                </p>
            </div>

            <!-- ROT Deduction -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 mx-auto">
                    🏠
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    ROT-avdrag
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Få tillbaka upp till 30% av kostnaden genom ROT-avdrag på kvalificerade tjänster.
                </p>
            </div>
        </div>

        <!-- Detailed Benefits -->
        <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                🎯 Våra Konkurrensfördelar
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🛡️</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Säkerhet & Trygghet</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Alla våra företag är noggrant granskade, försäkrade och verifierade. Vi kontrollerar referenser, 
                                certifikat och försäkringar för att garantera din trygghet.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">⭐</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Bästa Företag</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Vi arbetar endast med Sveriges bästa tjänsteleverantörer. Våra företag har höga betyg, 
                                positiva recensioner och bevisad expertis inom sina områden.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">💬</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Personlig Service</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Vårt kundservice-team hjälper dig genom hela processen. Från första bokningen till 
                                slutförd tjänst - vi är här för dig.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🏆</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Prisvärda Lösningar</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Genom att jämföra offerter från flera företag får du alltid bästa möjliga pris. 
                                Våra företag konkurrerar om din uppgift.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">🔄</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Enkel Bokning</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Vårt smarta bokningssystem gör det enkelt att hitta och boka tjänster. 
                                Få offerter, jämför priser och boka - allt på en plats.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-2xl">📱</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Modern Teknik</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Vår plattform använder senaste teknik för att ge dig smidigaste möjliga upplevelse. 
                                Bokning, kommunikation och betalning - allt digitalt.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 lg:p-12 text-white mb-16">
            <h2 class="text-3xl font-bold mb-8 text-center">
                📊 Våra Siffror
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">10,000+</div>
                    <div class="text-blue-100">Nöjda Kunder</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-blue-100">Verifierade Företag</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">50+</div>
                    <div class="text-blue-100">Tjänstekategorier</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">4.8/5</div>
                    <div class="text-blue-100">Genomsnittsbetyg</div>
                </div>
            </div>
        </div>

        <!-- Customer Testimonials -->
        <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                💬 Vad Våra Kunder Säger
            </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 italic mb-4">
                        "Fantastisk service! Fick en städare samma dag och resultatet var perfekt. 
                        Kommer definitivt använda Bitra igen."
                    </p>
                    <div class="font-semibold text-gray-900">- Anna L.</div>
                    <div class="text-sm text-gray-500">Stockholm</div>
                </div>

                <div class="text-center">
                    <div class="text-4xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 italic mb-4">
                        "Så enkelt att boka flytthjälp! Sparade både tid och pengar. 
                        Företaget var professionellt och snabbt."
                    </p>
                    <div class="font-semibold text-gray-900">- Erik M.</div>
                    <div class="text-sm text-gray-500">Göteborg</div>
                </div>

        <div class="text-center">
                    <div class="text-4xl mb-4">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 italic mb-4">
                        "Bästa plattformen för att hitta hantverkare! Transparenta priser och 
                        kvalitetsgaranti. Rekommenderas varmt!"
                    </p>
                    <div class="font-semibold text-gray-900">- Maria K.</div>
                    <div class="text-sm text-gray-500">Malmö</div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                ❓ Vanliga Frågor
            </h2>

            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Hur vet jag att företaget är pålitligt?</h3>
                    <p class="text-gray-600">
                        Alla våra företag genomgår en noggrann verifieringsprocess där vi kontrollerar försäkringar, 
                        referenser och certifikat. Vi granskar också kundrecensioner och betyg regelbundet.
                    </p>
                </div>

                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Vad händer om jag inte är nöjd med tjänsten?</h3>
                    <p class="text-gray-600">
                        Vi erbjuder 100% nöjdkundgaranti. Om du inte är nöjd kontaktar du oss så löser vi problemet 
                        tillsammans med företaget eller hittar en alternativ lösning.
                    </p>
                </div>

                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Hur snabbt får jag svar på min bokning?</h3>
                    <p class="text-gray-600">
                        De flesta bokningar får svar inom 24 timmar. För akuta tjänster kan vi ofta matcha dig 
                        med ett företag samma dag.
                    </p>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Finns det några dolda avgifter?</h3>
                    <p class="text-gray-600">
                        Nej, vi är transparenta med alla kostnader. Du ser exakt vad tjänsten kostar innan du bokar. 
                        Vår service är kostnadsfri för dig som kund.
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">
                Redo att uppleva skillnaden?
            </h2>
            <p class="text-xl text-green-100 mb-8 max-w-2xl mx-auto">
                Börja din bokning idag och upplev varför tusentals svenskar väljer Bitra för sina tjänstebehov
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.services') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-green-600 rounded-xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                    <span class="mr-2">🚀</span>
                    Boka Nu
                </a>
                <a href="{{ route('how-it-works') }}" 
                   class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-bold text-lg hover:bg-white hover:text-green-600 transition-all">
                    <span class="mr-2">📖</span>
                    Så Fungerar Det
                </a>
            </div>
        </div>
    </div>
</div>
@endsection