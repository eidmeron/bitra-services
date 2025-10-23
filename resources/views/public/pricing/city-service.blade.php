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

<!-- Pricing Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Price Overview -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 mb-12">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $service->name }} priser i {{ $city->name }}</h2>
                    <div class="flex justify-center items-baseline mb-6">
                        <span class="text-5xl font-bold text-green-600">{{ number_format($pricing['base_price'], 0, ',', ' ') }}</span>
                        <span class="text-2xl text-gray-600 ml-2">SEK</span>
                        <span class="text-lg text-gray-500 ml-2">från</span>
                    </div>
                    @if($pricing['city_multiplier'] != 1.0)
                        <div class="text-sm text-gray-600 mb-4">
                            Priser i {{ $city->name }} ({{ $pricing['city_multiplier'] > 1 ? '+' : '' }}{{ number_format(($pricing['city_multiplier'] - 1) * 100, 0) }}% jämfört med genomsnitt)
                        </div>
                    @endif
                    <p class="text-lg text-gray-600 mb-6">
                        Lokala priser för {{ $service->name }} i {{ $city->name }}. Kontakta oss för en personlig offert.
                    </p>
                    <a href="{{ route('public.search', ['service' => $service->id, 'city' => $city->id]) }}" 
                       class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                        Få offert i {{ $city->name }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- What's Included -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Vad ingår?</h3>
                    <ul class="space-y-4">
                        @foreach($pricing['includes'] as $include)
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">{{ $include }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- What's Not Included -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Vad ingår inte?</h3>
                    <ul class="space-y-4">
                        @foreach($pricing['excludes'] as $exclude)
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">{{ $exclude }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Local Information -->
            <div class="mt-12 bg-blue-50 rounded-2xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Lokal service i {{ $city->name }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Fördelar med lokal service</h4>
                        <ul class="space-y-2 text-gray-600">
                            <li>• Kännedom om lokala förhållanden</li>
                            <li>• Snabbare respons och service</li>
                            <li>• Lokala partners och leverantörer</li>
                            <li>• Personlig service och kontakt</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Serviceområde</h4>
                        <p class="text-gray-600 mb-4">
                            Vi erbjuder {{ $service->name }} i {{ $city->name }} och omgivande områden. 
                            Kontakta oss för att bekräfta att vi kan hjälpa dig.
                        </p>
                        <div class="text-sm text-gray-500">
                            <strong>Zon:</strong> {{ $city->zone->name ?? 'Allmän zon' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add-ons -->
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Tilläggstjänster</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($pricing['add_ons'] as $addon)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $addon['name'] }}</h4>
                            <div class="text-2xl font-bold text-blue-600 mb-2">{{ $addon['price'] }}</div>
                            <p class="text-gray-600 text-sm">{{ $addon['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Vanliga frågor</h3>
                <div class="space-y-4">
                    @foreach($pricing['faqs'] as $faq)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $faq['question'] }}</h4>
                            <p class="text-gray-600">{{ $faq['answer'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Redo att boka {{ $service->name }} i {{ $city->name }}?</h2>
        <p class="text-xl mb-8 text-blue-100">Få en gratis offert från lokala experter</p>
        <a href="{{ route('public.search', ['service' => $service->id, 'city' => $city->id]) }}" 
           class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-100 transition-colors duration-300">
            Boka nu
        </a>
    </div>
</section>

<!-- Schema Markup -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Service",
    "name": "{{ $service->name }} i {{ $city->name }}",
    "description": "{{ $service->description }}",
    "url": "{{ url()->current() }}",
    "provider": {
        "@@type": "Organization",
        "name": "Bitra",
        "url": "{{ url('/') }}"
    },
    "offers": {
        "@@type": "Offer",
        "price": "{{ $pricing['base_price'] }}",
        "priceCurrency": "SEK",
        "availability": "https://schema.org/InStock",
        "priceRange": "{{ $pricing['price_range']['min'] }}-{{ $pricing['price_range']['max'] }} SEK"
    },
    "areaServed": {
        "@@type": "City",
        "name": "{{ $city->name }}",
        "containedInPlace": {
            "@@type": "Country",
            "name": "Sweden"
        }
    }
}
</script>
@endsection
