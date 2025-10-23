@extends('layouts.public')

@php
    $seoTitle = $seoTitle ?? ($service->name . ' i ' . $city->name);
    $seoDescription = $seoDescription ?? ('Boka ' . strtolower($service->name) . ' i ' . $city->name . '. Transparenta priser och verifierade utförare.');
    $heroTitle = $heroTitle ?? ($service->name . ' i ' . $city->name);
    $heroSubtitle = $heroSubtitle ?? 'Pris från ' . number_format(($priceFrom ?? (int) round(((float)($service->base_price ?? 500)) * (float)($city->price_multiplier ?? 1.0))), 0, ',', ' ') . ' SEK • Snabb bokning • Verifierade utförare';
    $multiplier = $multiplier ?? (float) ($city->price_multiplier ?? 1.0);
    $basePrice = (float) ($service->base_price ?? 500);
    $hourBase = (float) ($service->hourly_rate ?? 400);
    $priceFrom = $priceFrom ?? (int) round($basePrice * $multiplier);
    $priceRangeMax = $priceRangeMax ?? (int) round($priceFrom * 2.5);
    $hourlyRate = $hourlyRate ?? $hourBase;
    // Prefer booking form for this service
    $activeForm = $service->active_form ?? null;
    $formToken = $activeForm->public_token ?? ($activeForm->token ?? null);
    $ctaUrl = $ctaUrl ?? ($formToken ? route('public.form', ['token' => $formToken]) : route('public.pricing.service', $service->slug));
@endphp
@section('title', $seoTitle)
@section('description', $seoDescription)

@section('content')
<section class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-3">{{ $heroTitle }}</h1>
            <p class="text-lg md:text-2xl text-blue-100">{{ $heroSubtitle }}</p>
            <div class="mt-6">
                <a href="{{ $ctaUrl }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-700 rounded-xl font-bold text-lg hover:bg-gray-100 transition">
                    Boka nu
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        </div>
    </div>
    
</section>

<!-- Content -->
<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main -->
        <div class="lg:col-span-2 space-y-10">
            <!-- Intro -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Professionell {{ strtolower($service->name) }} i {{ $city->name }}</h2>
                <p class="text-gray-700 leading-7">
                    Upptäck pålitlig och effektiv {{ strtolower($service->name) }} i {{ $city->name }}. Vi samarbetar med verifierade partners som levererar hög kvalitet, punktlighet och trygghet. Våra priser är transparenta och anpassade efter lokala förutsättningar.
                </p>
            </div>

            <!-- What Included -->
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Det här ingår</h3>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <li class="flex items-start"><span class="text-green-600 mr-2">✔</span> Professionell personal med erfarenhet</li>
                    <li class="flex items-start"><span class="text-green-600 mr-2">✔</span> All utrustning och material</li>
                    <li class="flex items-start"><span class="text-green-600 mr-2">✔</span> Garanti på utfört arbete</li>
                    <li class="flex items-start"><span class="text-green-600 mr-2">✔</span> Trygg bokning och support</li>
                </ul>
            </div>

            <!-- Local SEO Content -->
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Varför välja oss i {{ $city->name }}?</h3>
                <p class="text-gray-700 leading-7 mb-3">Vi kan {{ $city->name }}. Våra lokala team känner till områdena, trafiken och de vanligaste utmaningarna – vilket betyder snabbare, smidigare service för dig.</p>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>Snabb tillgänglighet i hela {{ $city->name }}</li>
                    <li>Transparent prissättning utan dolda avgifter</li>
                    <li>ROT-avdrag för berättigade tjänster</li>
                    <li>Verifierade och betygsatta partners</li>
                </ul>
            </div>

            <!-- FAQ -->
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Vanliga frågor</h3>
                <div class="space-y-3">
                    <details class="bg-gray-50 rounded-lg p-4">
                        <summary class="font-medium cursor-pointer">Hur beräknas priset?</summary>
                        <p class="mt-2 text-gray-700">Priset baseras på uppdragets omfattning, tidsåtgång och eventuella tillägg. Du får alltid en tydlig offert innan arbetet startar.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-4">
                        <summary class="font-medium cursor-pointer">Kan jag boka helger och kvällar?</summary>
                        <p class="mt-2 text-gray-700">Ja, det går bra. Ett tillägg kan tillkomma enligt vår prislista.</p>
                    </details>
                    <details class="bg-gray-50 rounded-lg p-4">
                        <summary class="font-medium cursor-pointer">Är material inkluderat?</summary>
                        <p class="mt-2 text-gray-700">Standardmaterial ingår. Specialmaterial hanteras i dialog och offereras separat.</p>
                    </details>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <aside>
            <div class="bg-white rounded-2xl shadow-md p-6 sticky top-24">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Boka {{ strtolower($service->name) }}</h3>
                <p class="text-gray-600 mb-4">Pris från <strong>{{ number_format($priceFrom, 0, ',', ' ') }} SEK</strong>. Snabb bokning på 2 minuter.</p>
                <a href="{{ $ctaUrl }}" class="block w-full text-center bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition">Boka nu</a>
                <div class="border-t mt-6 pt-4 text-sm text-gray-500">
                    Behöver du mer information? <a href="{{ route('public.pricing.service', $service->slug) }}" class="text-blue-600 hover:text-blue-800">Läs mer om pris och innehåll</a>.
                </div>
            </div>
        </aside>
    </div>
</section>

<!-- Schema -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Service",
    "name": "{{ $service->name }} i {{ $city->name }}",
    "description": "Boka {{ strtolower($service->name) }} i {{ $city->name }}. Pris från {{ $priceFrom }} SEK.",
    "areaServed": {"@@type": "City", "name": "{{ $city->name }}"},
    "offers": {"@@type": "Offer", "price": "{{ $priceFrom }}", "priceCurrency": "SEK"}
}
</script>
@endsection

@extends('layouts.public')
@section('title', $service->name . ' i ' . $city->name)
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-4">{{ $service->name }} i {{ $city->name }}</h1>
    <p class="text-xl text-gray-600 mb-8">{{ $service->description }}</p>
    
    @php
        $activeForm = $service->active_form;
        $hasForm = $activeForm && $activeForm->token;
    @endphp
    
    @if($hasForm)
    <div class="mb-12">
        <a href="{{ route('public.form', $activeForm->token) }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-lg">
            Boka {{ $service->name }} i {{ $city->name }} →
        </a>
    </div>
    @endif
    
    @if($companies->isNotEmpty())
    <div>
        <h2 class="text-2xl font-bold mb-6">Företag som erbjuder {{ $service->name }} i {{ $city->name }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($companies as $company)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-2">{{ $company->company_name }}</h3>
                <p class="text-gray-600 mb-4">{{ Str::limit($company->description, 100) }}</p>
                @if($company->reviews_avg_rating)
                <div class="flex items-center mb-4">
                    <span class="text-yellow-500">★</span>
                    <span class="ml-1">{{ number_format($company->reviews_avg_rating, 1) }}</span>
                    <span class="text-gray-500 ml-2">({{ $company->reviews_count }} recensioner)</span>
                </div>
                @endif
                <a href="{{ route('public.company.show', $company->id) }}" class="text-blue-600 hover:underline">
                    Visa företag →
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
        <p class="text-yellow-700">Det finns inga företag som erbjuder {{ $service->name }} i {{ $city->name }} ännu.</p>
    </div>
    @endif
</div>
@endsection
