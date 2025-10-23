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
            @php
                $activeForm = $service->active_form ?? null;
                $formToken = $activeForm->public_token ?? ($activeForm->token ?? null);
                $bookingUrl = $formToken ? route('public.form', ['token' => $formToken]) : route('public.pricing.service', $service->slug);
            @endphp
            <!-- Price Overview -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 mb-12">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Priser för {{ $service->name }}</h2>
                    <div class="flex justify-center items-baseline mb-6">
                        <span class="text-5xl font-bold text-green-600">{{ number_format($pricing['base_price'], 0, ',', ' ') }}</span>
                        <span class="text-2xl text-gray-600 ml-2">SEK</span>
                        <span class="text-lg text-gray-500 ml-2">från</span>
                    </div>
                    <p class="text-lg text-gray-600 mb-6">
                        Priser varierar beroende på omfattning och komplexitet. Kontakta oss för en personlig offert.
                    </p>
                    <a href="{{ $bookingUrl }}" 
                       class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-lg font-bold text-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                        Få offert nu
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

            <!-- City Selection -->
            @if($cities->count() > 0)
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Se priser i din stad</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($cities->take(12) as $city)
                            <a href="{{ route('public.pricing.city-service', [$city->slug, $service->slug]) }}" 
                               class="block bg-white border border-gray-200 rounded-lg p-4 text-center hover:border-blue-500 hover:shadow-md transition-all duration-300">
                                <div class="font-semibold text-gray-900">{{ $city->name }}</div>
                                <div class="text-sm text-gray-500">Se priser</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

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
        <h2 class="text-3xl font-bold mb-4">Redo att boka {{ $service->name }}?</h2>
        <p class="text-xl mb-8 text-blue-100">Få en gratis offert på bara några minuter</p>
        <a href="{{ $bookingUrl }}" 
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
    "name": "{{ $service->name }}",
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
        "@@type": "Country",
        "name": "Sweden"
    }
}
</script>
@endsection
