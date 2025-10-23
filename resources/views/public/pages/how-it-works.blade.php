@extends('layouts.public')

@php
$page = page_content('how-it-works');
@endphp

<x-page-seo pageKey="how-it-works" />

@section('content')
<x-toast />

<div class="bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20"
         @if($page && $page->hero_image) style="background-image: linear-gradient(rgba(37, 99, 235, 0.8), rgba(147, 51, 234, 0.8)), url('{{ Storage::url($page->hero_image) }}'); background-size: cover; background-position: center;" @endif>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">
                    {{ $page && $page->hero_title ? $page->hero_title : '🚀 Så Fungerar Det' }}
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    {{ $page && $page->hero_subtitle ? $page->hero_subtitle : 'Enkel och snabb bokning av professionella tjänster i 4 enkla steg' }}
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

    <!-- Main Steps -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Steps Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <!-- Step 1 -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 mx-auto">
                    1
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    📍 Välj Tjänst
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Bläddra bland våra kategorier och välj den tjänst du behöver. Vi har allt från städning till flytthjälp.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 mx-auto">
                    2
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    📝 Fyll i Formulär
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Berätta om dina behov genom vårt enkla formulär. Ange tid, plats och specifika önskemål.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 mx-auto">
                    3
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    🏢 Få Offert
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Vi matchar din förfrågan med kvalificerade företag som kontaktar dig med offert.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 mx-auto">
                    4
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                    ✅ Bekräfta
                </h3>
                <p class="text-gray-600 text-center leading-relaxed">
                    Granska och acceptera offerten. Företaget utför tjänsten vid överenskommen tid.
                </p>
            </div>
        </div>

        <!-- Detailed Process -->
        <div class="bg-white rounded-2xl shadow-xl p-8 lg:p-12 mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                💡 Detaljerad Process
            </h2>

            <div class="space-y-8">
                <!-- Detail 1 -->
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🔍</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Sök och Hitta</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Använd vår sökfunktion eller bläddra genom kategorier för att hitta exakt den tjänst du behöver. 
                            Alla våra tjänsteleverantörer är noggrant granskade och verifierade.
                        </p>
                    </div>
                </div>

                <!-- Detail 2 -->
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">📋</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Skicka Förfrågan</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Fyll i vårt smarta bokningsformulär med dina behov. Du kan ange önskad tid, 
                            specifika krav och ladda upp bilder om det behövs. Allt för att ge företagen bästa möjliga information.
                        </p>
                    </div>
                </div>

                <!-- Detail 3 -->
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">💬</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Matchning och Kommunikation</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Vårt system matchar automatiskt din förfrågan med lämpliga företag i ditt område. 
                            Företagen får din förfrågan och kan svara med en offert. Du kan kommunicera direkt med företaget 
                            via vår plattform.
                        </p>
                    </div>
                </div>

                <!-- Detail 4 -->
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">✔️</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Godkänn och Genomför</h3>
                        <p class="text-gray-600 leading-relaxed">
                            När du har fått en offert som passar dig, godkänner du den och företaget utför tjänsten. 
                            Efter genomförandet kan du betygsätta och recensera tjänsten för att hjälpa andra kunder.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefits -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 border border-blue-200">
                <div class="text-5xl mb-4 text-center">⚡</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Snabbt & Enkelt</h3>
                <p class="text-gray-700 text-center">
                    Boka tjänster på bara några minuter. Inget krångel, bara resultat.
                </p>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-8 border border-purple-200">
                <div class="text-5xl mb-4 text-center">🛡️</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Säkert & Tryggt</h3>
                <p class="text-gray-700 text-center">
                    Alla företag är verifierade och försäkrade. Din trygghet är vår prioritet.
                </p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 border border-green-200">
                <div class="text-5xl mb-4 text-center">💰</div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 text-center">Bästa Pris</h3>
                <p class="text-gray-700 text-center">
                    Jämför offerter från flera företag och få bästa möjliga pris.
                </p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-12 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">
                Redo att komma igång?
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Börja din bokning idag och upplev hur enkelt det kan vara att få professionell hjälp
            </p>
            <a href="{{ route('public.services') }}" 
               class="inline-flex items-center px-8 py-4 bg-white text-blue-600 rounded-xl font-bold text-lg hover:shadow-2xl transform hover:scale-105 transition-all">
                <span class="mr-2">🚀</span>
                Boka Nu
            </a>
        </div>
    </div>
</div>
@endsection
