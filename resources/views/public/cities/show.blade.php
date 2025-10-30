@extends('layouts.public')

@section('title', $city->og_title ?: $seoTitle)
@section('description', $city->og_description ?: $seoDescription)

@push('meta')
    @if($city->meta_keywords)
        <meta name="keywords" content="{{ $city->meta_keywords }}">
    @endif
    @if($city->og_title)
        <meta property="og:title" content="{{ $city->og_title }}">
    @endif
    @if($city->og_description)
        <meta property="og:description" content="{{ $city->og_description }}">
    @endif
    @if($city->og_image)
        <meta property="og:image" content="{{ Storage::url($city->og_image) }}">
    @endif
    @if($city->twitter_title)
        <meta name="twitter:title" content="{{ $city->twitter_title }}">
    @endif
    @if($city->twitter_description)
        <meta name="twitter:description" content="{{ $city->twitter_description }}">
    @endif
    @if($city->twitter_image)
        <meta name="twitter:image" content="{{ Storage::url($city->twitter_image) }}">
    @endif
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Tjänster i {{ $city->name }}
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Hitta de bästa tjänsterna och företagen i {{ $city->name }}
                </p>
                
                @if($services->count() > 0)
                    <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-500">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                            {{ $services->count() }} tjänster
                        </span>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">
                            {{ $companies->count() }} företag
                        </span>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                            {{ $categories->count() }} kategorier
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SEO Content Sections -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Intro Paragraph -->
        @if($city->intro_paragraph)
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="prose prose-lg max-w-none">
                        <p class="text-gray-700 leading-relaxed">{{ $city->intro_paragraph }}</p>
                    </div>
                </div>
            </section>
        @endif

        <!-- Features/Benefits Section -->
        @if($city->features_benefits && count($city->features_benefits) > 0)
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Varför välja oss i {{ $city->name }}?</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($city->features_benefits as $feature)
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <span class="text-2xl">✨</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $feature['title'] ?? 'Fördel' }}</h3>
                                    <p class="text-gray-600">{{ $feature['description'] ?? $feature }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Process/How It Works Section -->
        @if($city->process_steps && count($city->process_steps) > 0)
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Så här fungerar det</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($city->process_steps as $index => $step)
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                                    {{ $index + 1 }}
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $step['title'] ?? 'Steg ' . ($index + 1) }}</h3>
                                <p class="text-gray-600">{{ $step['description'] ?? $step }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- FAQ Section -->
        @if($city->faq_items && count($city->faq_items) > 0)
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vanliga frågor om tjänster i {{ $city->name }}</h2>
                    <div class="space-y-4">
                        @foreach($city->faq_items as $faq)
                            <div class="border border-gray-200 rounded-lg">
                                <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                                    <span class="font-semibold text-gray-900">{{ $faq['question'] ?? 'Fråga' }}</span>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="px-6 pb-4 text-gray-600 hidden">
                                    {{ $faq['answer'] ?? $faq }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Testimonials Section -->
        @if($city->testimonials && count($city->testimonials) > 0)
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vad våra kunder säger</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($city->testimonials as $testimonial)
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="flex items-center mb-4">
                                    @for($i = 0; $i < 5; $i++)
                                        <span class="text-yellow-400">⭐</span>
                                    @endfor
                                </div>
                                <p class="text-gray-700 mb-4 italic">"{{ $testimonial['content'] ?? $testimonial }}"</p>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ substr($testimonial['name'] ?? 'K', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-gray-900">{{ $testimonial['name'] ?? 'Kund' }}</p>
                                        <p class="text-sm text-gray-500">{{ $testimonial['location'] ?? $city->name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- CTA Section -->
        @if($city->cta_text || $city->cta_button_text)
            <section class="mb-12">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white text-center">
                    <h2 class="text-3xl font-bold mb-4">{{ $city->cta_text ?: 'Redo att komma igång?' }}</h2>
                    @if($city->cta_button_text && $city->cta_button_url)
                        <a href="{{ $city->cta_button_url }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <span class="mr-2">🚀</span>
                            {{ $city->cta_button_text }}
                        </a>
                    @endif
                </div>
            </section>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($services->count() > 0)
            <!-- Services Section -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Tillgängliga Tjänster</h2>
                
                <!-- Categories Filter -->
                @if($categories->count() > 1)
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-2">
                            <button class="category-filter bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium" data-category="all">
                                Alla ({{ $services->count() }})
                            </button>
                            @foreach($categories as $category)
                                <button class="category-filter bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300" data-category="{{ $category->slug }}">
                                    {{ $category->name }} ({{ $category->services_count }})
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Services Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="services-grid">
                    @foreach($services as $service)
                        <div class="service-card bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6" data-category="{{ $service->category->slug ?? 'uncategorized' }}">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('public.city-service.landing', ['city' => $city->slug, 'service' => $service->slug]) }}" class="hover:text-blue-600">
                                            {{ $service->name }}
                                        </a>
                                    </h3>
                                    @if($service->category)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                            {{ $service->category->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($service->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ Str::limit($service->description, 120) }}
                                </p>
                            @endif

                            <!-- Pricing -->
                            @if($service->base_price)
                                <div class="mb-4">
                                    <span class="text-2xl font-bold text-green-600">
                                        {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                    </span>
                                    @if($service->subscription_types)
                                        <span class="text-sm text-gray-500 ml-2">
                                            från
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Companies offering this service -->
                            @if($service->companies->count() > 0)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ $service->companies->count() }} företag erbjuder denna tjänst
                                    </p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($service->companies->take(3) as $company)
                                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                                {{ $company->company_name }}
                                            </span>
                                        @endforeach
                                        @if($service->companies->count() > 3)
                                            <span class="text-xs text-gray-500">
                                                +{{ $service->companies->count() - 3 }} fler
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action Button -->
                            <div class="flex gap-2">
                                <a href="{{ route('public.city-service.landing', ['city' => $city->slug, 'service' => $service->slug]) }}" 
                                   class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    Se detaljer
                                </a>
                                @if($service->forms->count() > 0)
                                    <a href="{{ route('public.form', $service->forms->first()->token) }}" 
                                       class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                        Boka nu
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Companies Section -->
            @if($companies->count() > 0)
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Företag i {{ $city->name }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($companies as $company)
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                            <a href="{{ route('public.company.show', $company) }}" class="hover:text-blue-600">
                                                {{ $company->company_name }}
                                            </a>
                                        </h3>
                                        @if($company->reviews_avg_company_rating)
                                            <div class="flex items-center mb-2">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($company->reviews_avg_company_rating))
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="ml-2 text-sm text-gray-600">
                                                    {{ number_format($company->reviews_avg_company_rating, 1) }} ({{ $company->reviews_count }} recensioner)
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($company->services->count() > 0)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-2">
                                            Erbjuder {{ $company->services->count() }} tjänster
                                        </p>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($company->services->take(3) as $service)
                                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                                    {{ $service->name }}
                                                </span>
                                            @endforeach
                                            @if($company->services->count() > 3)
                                                <span class="text-xs text-gray-500">
                                                    +{{ $company->services->count() - 3 }} fler
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('public.company.show', $company) }}" 
                                   class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    Se företag
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @else
            <!-- No Services Available -->
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Inga tjänster tillgängliga än
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Vi arbetar på att lägga till fler tjänster i {{ $city->name }}. 
                        Kolla gärna igen senare eller titta på våra andra städer.
                    </p>
                    <a href="{{ route('public.cities') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Se alla städer
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@if($services->count() > 0 && $categories->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilters = document.querySelectorAll('.category-filter');
    const serviceCards = document.querySelectorAll('.service-card');
    
    categoryFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active filter
            categoryFilters.forEach(f => {
                f.classList.remove('bg-blue-600', 'text-white');
                f.classList.add('bg-gray-200', 'text-gray-700');
            });
            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('bg-blue-600', 'text-white');
            
            // Filter services
            serviceCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});

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
@endif
@endsection