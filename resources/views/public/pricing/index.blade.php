@extends('layouts.public')

@section('title', $seoTitle)
@section('description', $seoDescription)

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $heroTitle }}</h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">{{ $heroDescription }}</p>
        </div>
    </div>
</section>

<!-- Reviews Slider Section -->
@if(isset($platformReviews) && $platformReviews->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Vad våra kunder säger om våra priser</h2>
            <p class="text-xl text-gray-600">Äkta recensioner från nöjda kunder</p>
        </div>
        
        <div class="relative">
            <div class="overflow-hidden">
                <div class="flex space-x-6 animate-scroll" id="reviews-slider">
                    @foreach($platformReviews->take(10) as $review)
                    <div class="flex-shrink-0 w-80 bg-white rounded-2xl p-6 shadow-lg">
                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->bitra_rating)
                                    <span class="text-yellow-400 text-lg">⭐</span>
                                @else
                                    <span class="text-gray-300 text-lg">⭐</span>
                                @endif
                            @endfor
                            <span class="ml-2 text-gray-600 text-sm font-semibold">{{ $review->bitra_rating }}/5</span>
                        </div>

                        <!-- Review Text -->
                        <blockquote class="text-gray-700 mb-4 text-sm leading-relaxed">
                            "{{ Str::limit($review->bitra_review_text, 120) }}"
                        </blockquote>

                        <!-- Customer Info -->
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($review->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900 text-sm">{{ $review->name ?? 'Anonym' }}</p>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('public.reviews') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                <span class="mr-2">⭐</span>
                Se alla recensioner
            </a>
        </div>
    </div>
</section>

<style>
@keyframes scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-100%); }
}

.animate-scroll {
    animation: scroll 30s linear infinite;
}

.animate-scroll:hover {
    animation-play-state: paused;
}
</style>
@endif

<!-- SEO Content Sections -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Intro Paragraph -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="prose prose-lg max-w-none text-center">
                <p class="text-gray-700 leading-relaxed text-xl">
                    Upptäck Sveriges mest transparenta prissättning för professionella tjänster. Vi erbjuder konkurrenskraftiga priser 
                    utan dolda avgifter eller överraskningar. Alla våra priser inkluderar moms och är baserade på faktisk arbetsinsats, 
                    material och komplexitet. Få en personlig offert baserad på dina specifika behov och budget.
                </p>
            </div>
        </div>
    </section>

    <!-- Features/Benefits Section -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Varför våra priser är bäst?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Transparenta priser</h3>
                        <p class="text-gray-600">Inga dolda avgifter eller överraskningar - du vet alltid vad det kostar innan vi börjar.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">📊</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Personliga offerter</h3>
                        <p class="text-gray-600">Alla priser anpassas efter dina specifika behov och omständigheter.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🏆</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kvalitetsgaranti</h3>
                        <p class="text-gray-600">Du får alltid värde för pengarna med vår 100% nöjdhetsgaranti.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Snabb offert</h3>
                        <p class="text-gray-600">Få din personliga offert inom 24 timmar - ofta samma dag.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🔄</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Flexibel betalning</h3>
                        <p class="text-gray-600">Betalning efter utfört arbete eller i delar för större projekt.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">📈</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">ROT-avdrag</h3>
                        <p class="text-gray-600">Många av våra tjänster är ROT-avdragsgilla för att spara pengar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process/How It Works Section -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Så här får du din offert</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        1
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Beskriv ditt behov</h3>
                    <p class="text-gray-600">Berätta för oss vad du behöver hjälp med och när du vill ha det gjort.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        2
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Vi analyserar</h3>
                    <p class="text-gray-600">Våra experter bedömer omfattning, material och tidsåtgång.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        3
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Få din offert</h3>
                    <p class="text-gray-600">Du får en detaljerad offert med fast pris och inga överraskningar.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        4
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Boka & Betala</h3>
                    <p class="text-gray-600">Boka enkelt online och betala säkert efter utfört arbete.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vanliga frågor om priser</h2>
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900">Vad inkluderar priset?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="px-6 pb-4 text-gray-600 hidden">
                        Alla våra priser inkluderar arbete, material, moms och transport. Inga dolda avgifter eller överraskningar.
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900">Kan jag få ROT-avdrag?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="px-6 pb-4 text-gray-600 hidden">
                        Ja, många av våra tjänster är ROT-avdragsgilla. Vi hjälper dig med all nödvändig dokumentation.
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900">Hur snabbt får jag en offert?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="px-6 pb-4 text-gray-600 hidden">
                        De flesta offerter får du inom 24 timmar, ofta samma dag. Komplexa projekt kan ta några dagar.
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900">Kan priset ändras efter att arbetet börjat?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="px-6 pb-4 text-gray-600 hidden">
                        Endast om du begär ändringar som inte var med i den ursprungliga offerten. Vi informerar alltid om eventuella ändringar i förväg.
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                        <span class="font-semibold text-gray-900">Vad händer om jag inte är nöjd med priset?</span>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="px-6 pb-4 text-gray-600 hidden">
                        Vi arbetar alltid för att hitta en lösning som fungerar för båda parter. Kontakta oss så diskuterar vi alternativen.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vad våra kunder säger om våra priser</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <span class="text-yellow-400">⭐</span>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-4 italic">"Mycket transparenta priser! Inga överraskningar och fick exakt vad som avtalats."</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">K</div>
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Karin S.</p>
                            <p class="text-sm text-gray-500">Göteborg</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <span class="text-yellow-400">⭐</span>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-4 italic">"Bra pris för kvaliteten! Mycket nöjd och fick ROT-avdrag också."</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">T</div>
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Tomas L.</p>
                            <p class="text-sm text-gray-500">Malmö</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <span class="text-yellow-400">⭐</span>
                        @endfor
                    </div>
                    <p class="text-gray-700 mb-4 italic">"Konkurrenskraftiga priser och snabb offert. Rekommenderas varmt!"</p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">M</div>
                        <div class="ml-3">
                            <p class="font-semibold text-gray-900">Maria H.</p>
                            <p class="text-sm text-gray-500">Stockholm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="mb-12">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white text-center">
            <h2 class="text-3xl font-bold mb-4">Redo att få din personliga offert?</h2>
            <p class="text-xl text-blue-100 mb-6">Kontakta oss idag för en kostnadsfri offert baserad på dina specifika behov.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <span class="mr-2">📞</span>
                Få offert nu
            </a>
        </div>
    </section>
</div>

<!-- Services Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Välj din tjänst</h2>
            <p class="text-lg text-gray-600">Se priser för alla våra professionella tjänster</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-2xl mr-4">
                                @switch($service->name)
                                    @case('Städning')
                                    @case('Hemstädning')
                                        🧹
                                        @break
                                    @case('Hantverk')
                                    @case('VVS')
                                    @case('El')
                                        🔧
                                        @break
                                    @case('Flytt')
                                    @case('Flytthjälp')
                                        🚚
                                        @break
                                    @default
                                        🏠
                                @endswitch
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $service->name }}</h3>
                                @if($service->category)
                                    <p class="text-sm text-gray-500">{{ $service->category->name }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="text-2xl font-bold text-green-600">
                                Från {{ number_format($service->base_price ?? 500, 0, ',', ' ') }} SEK
                            </div>
                            <div class="text-sm text-gray-500">
                                Per {{ $service->pricing_unit ?? 'tjänst' }}
                            </div>
                        </div>

                        <p class="text-gray-600 mb-6">{{ Str::limit($service->description, 100) }}</p>

                        <div class="space-y-2">
                            <a href="{{ route('public.pricing.service', $service->slug) }}" 
                               class="block w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white text-center py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                                Se priser
                            </a>
                            
                            @if($cities->count() > 0)
                                <div class="text-center">
                                    <p class="text-sm text-gray-500 mb-2">Eller välj stad:</p>
                                    <select onchange="window.location.href=this.value" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2">
                                        <option value="">Välj stad för specifika priser</option>
                                        @foreach($cities->take(10) as $city)
                                            <option value="{{ route('public.pricing.city-service', [$city->slug, $service->slug]) }}">
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Varför välja oss?</h2>
            <p class="text-lg text-gray-600">Transparenta priser och professionell service</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">💰</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Transparenta priser</h3>
                <p class="text-gray-600">Inga dolda avgifter. Du vet exakt vad du betalar innan arbetet påbörjas.</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">⭐</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Kvalitetsgaranti</h3>
                <p class="text-gray-600">Alla våra partners är verifierade och vi garanterar kvaliteten på arbetet.</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🛡️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Försäkring & Garanti</h3>
                <p class="text-gray-600">Alla tjänster är försäkrade och vi ger garanti på utfört arbete.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Redo att boka din tjänst?</h2>
        <p class="text-xl mb-8 text-blue-100">Få en gratis offert på bara några minuter</p>
        <a href="{{ route('public.search') }}" 
           class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors duration-300">
            Boka nu
        </a>
    </div>
</section>

<!-- Schema Markup -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "{{ $seoTitle }}",
    "description": "{{ $seoDescription }}",
    "url": "{{ url()->current() }}",
    "mainEntity": {
        "@@type": "ItemList",
        "numberOfItems": {{ $services->count() }},
        "itemListElement": [
            @foreach($services as $index => $service)
            {
                "@@type": "Service",
                "position": {{ $index + 1 }},
                "name": "{{ $service->name }}",
                "description": "{{ $service->description }}",
                "url": "{{ route('public.pricing.service', $service->slug) }}",
                "offers": {
                    "@@type": "Offer",
                    "price": "{{ $service->base_price ?? 500 }}",
                    "priceCurrency": "SEK",
                    "availability": "https://schema.org/InStock"
                }
            }{{ $loop->last ? '' : ',' }}
            @endforeach
        ]
    }
}
</script>

@include('components.cta-section')

<script>
// FAQ Toggle Function
function toggleFaq(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('svg');
    
    if (answer.classList.contains('hidden')) {
        answer.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        answer.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>
@endsection
