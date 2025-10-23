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
@endsection
