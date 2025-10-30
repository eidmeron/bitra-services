@php
    use App\Services\PageContentService;
    
    $pageContent = PageContentService::getPageContent('reviews', [
        'meta_title' => 'Recensioner - Vad våra kunder säger | Bitra Services',
        'meta_description' => 'Läs äkta recensioner från våra nöjda kunder. Verifierade recensioner med genomsnittsbetyg 4.8/5. Alla recensioner kommer från kunder som faktiskt har använt våra tjänster.',
        'meta_keywords' => 'recensioner, kundrecensioner, Bitra recensioner, tjänstrecensioner, verifierade recensioner, kundnöjdhet, betyg, omdömen',
        'hero_title' => 'Recensioner - Vad våra kunder säger',
        'hero_subtitle' => 'Läs äkta recensioner från våra nöjda kunder och upplev kvaliteten själv.',
    ]);
    
    $seoData = PageContentService::getSeoData('reviews', [
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
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $seoData['og_title'] }}">
    <meta name="twitter:description" content="{{ $seoData['og_description'] }}">
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                {{ $pageContent['hero_title'] ?: 'Recensioner - Vad våra kunder säger' }}
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-4xl mx-auto">
                {{ $pageContent['hero_subtitle'] ?: 'Läs äkta recensioner från våra nöjda kunder och upplev kvaliteten själv. Alla recensioner kommer från kunder som faktiskt har använt våra tjänster.' }}
            </p>
            <div class="flex justify-center items-center space-x-8 text-lg">
                <div class="flex items-center">
                    <span class="text-3xl mr-2">⭐</span>
                    <span class="font-bold">{{ number_format($stats['average_rating'] ?? 4.8, 1) }}/5</span>
                </div>
                <div class="flex items-center">
                    <span class="text-3xl mr-2">👥</span>
                    <span class="font-bold">{{ $stats['total_reviews'] ?? 0 }} recensioner</span>
                </div>
                <div class="flex items-center">
                    <span class="text-3xl mr-2">✅</span>
                    <span class="font-bold">100% verifierade</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Review Statistics -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Våra recensioner i siffror
            </h2>
            <p class="text-lg text-gray-600">
                {{ $stats['total_reviews'] }} verifierade recensioner från riktiga kunder
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Overall Rating -->
            <div class="bg-white rounded-2xl p-8 shadow-lg text-center">
                <div class="text-6xl font-bold text-blue-600 mb-4">
                    {{ number_format($stats['average_rating'], 1) }}
                </div>
                <div class="flex justify-center mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($stats['average_rating']))
                            <span class="text-yellow-400 text-2xl">⭐</span>
                        @elseif($i - 0.5 <= $stats['average_rating'])
                            <span class="text-yellow-400 text-2xl">⭐</span>
                        @else
                            <span class="text-gray-300 text-2xl">⭐</span>
                        @endif
                    @endfor
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Genomsnittligt betyg</h3>
                <p class="text-gray-600">Baserat på {{ $stats['total_reviews'] }} recensioner</p>
            </div>

            <!-- Rating Distribution -->
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">Betygsfördelning</h3>
                <div class="space-y-3">
                    @for($rating = 5; $rating >= 1; $rating--)
                        @php
                            $count = $stats[$rating . '_star'];
                            $percentage = $stats['total_reviews'] > 0 ? ($count / $stats['total_reviews']) * 100 : 0;
                        @endphp
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-700 w-8">{{ $rating }}⭐</span>
                            <div class="flex-1 mx-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-600 w-8">{{ $count }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Total Reviews -->
            <div class="bg-white rounded-2xl p-8 shadow-lg text-center">
                <div class="text-6xl font-bold text-green-600 mb-4">
                    {{ $stats['total_reviews'] }}
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Verifierade recensioner</h3>
                <p class="text-gray-600">Från riktiga kunder som har använt våra tjänster</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Our Reviews Matter -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Varför våra recensioner är trovärdiga</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Alla recensioner på Bitra kommer från kunder som faktiskt har använt våra tjänster. 
                Vi verifierar varje recension för att säkerställa äkthet och kvalitet.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🔒</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Verifierade recensioner</h3>
                <p class="text-gray-600">Alla recensioner kommer från kunder som har bokat och använt våra tjänster</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">⚖️</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Balanserade omdömen</h3>
                <p class="text-gray-600">Vi publicerar både positiva och negativa recensioner för fullständig transparens</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🛡️</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Kvalitetskontroll</h3>
                <p class="text-gray-600">Alla recensioner granskas innan publicering för att säkerställa relevans</p>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Grid -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Vad våra kunder säger</h2>
            <p class="text-xl text-gray-600">Äkta recensioner från riktiga kunder</p>
        </div>
        
        @if($platformReviews->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($platformReviews as $review)
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->bitra_rating)
                                    <span class="text-yellow-400 text-xl">⭐</span>
                                @else
                                    <span class="text-gray-300 text-xl">⭐</span>
                                @endif
                            @endfor
                            <span class="ml-2 text-gray-600 text-sm font-semibold">{{ $review->bitra_rating }}/5</span>
                        </div>

                        <!-- Review Text -->
                        <blockquote class="text-gray-700 mb-6 text-lg leading-relaxed">
                            "{{ $review->bitra_review_text }}"
                        </blockquote>

                        <!-- Customer Info -->
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($review->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900">{{ $review->name ?? 'Anonym' }}</p>
                                <p class="text-sm text-gray-500">Verifierad kund</p>
                            </div>
                        </div>

                        <!-- Date and Verified Badge -->
                        <div class="border-t pt-4 flex justify-between items-center">
                            <span class="text-sm text-gray-500">📅 {{ $review->created_at->format('d M Y') }}</span>
                            <div class="flex items-center text-green-600 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Verifierad
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($platformReviews->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $platformReviews->links() }}
                </div>
            @endif
        @else
            <!-- No Reviews State -->
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-4xl">⭐</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Inga recensioner än</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Vi har ännu inte fått några recensioner. Bli den första att lämna en recension efter att du har använt våra tjänster!
                </p>
                <a href="{{ route('public.categories') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <span class="mr-2">🚀</span>
                    Boka en tjänst
                </a>
            </div>
        @endif
    </div>
</section>

<!-- How Reviews Work -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                Så fungerar våra recensioner
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Alla recensioner kommer från kunder som faktiskt har använt våra tjänster
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">📅</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">1. Boka en tjänst</h3>
                <p class="text-gray-600">
                    Boka en tjänst genom vår plattform. Alla recensioner kommer från kunder som faktiskt har använt våra tjänster.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">✅</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">2. Tjänsten utförs</h3>
                <p class="text-gray-600">
                    Våra verifierade partners utför tjänsten med högsta kvalitet. Vi följer upp för att säkerställa nöjdhet.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center bg-white rounded-2xl p-8 shadow-lg">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-2xl">⭐</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">3. Lämna recension</h3>
                <p class="text-gray-600">
                    Efter att tjänsten är klar kan du lämna en recension. Vi granskar alla recensioner innan de publiceras.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
            Redo att uppleva kvaliteten?
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Boka en tjänst idag och se varför våra kunder älskar Bitra. 
            Du kan också lämna din egen recension efter att tjänsten är klar.
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
