@extends('layouts.public')

@section('title', 'Kundrecensioner - Vad Våra Kunder Säger')
@section('meta_description', 'Läs vad våra kunder tycker om våra tjänster. ' . $stats['total'] . ' verifierade recensioner med genomsnittligt betyg ' . $stats['average_rating'] . '/5.')

@section('content')
<x-toast />

<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">
                    ⭐ Kundrecensioner
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                    Se vad våra kunder tycker om {{ site_name() }} och våra tjänster
                </p>

                <!-- Stats -->
                <div class="flex flex-wrap justify-center gap-6 mt-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-8 py-4 border border-white/30">
                        <div class="text-4xl font-bold">{{ $stats['average_rating'] }}</div>
                        <div class="flex justify-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($stats['average_rating']))
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-white/50 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <div class="text-sm text-blue-200 mt-1">Genomsnitt</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-8 py-4 border border-white/30">
                        <div class="text-4xl font-bold">{{ $stats['total'] }}</div>
                        <div class="text-sm text-blue-200">Recensioner</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-8 py-4 border border-white/30">
                        <div class="text-4xl font-bold">{{ $stats['five_star'] + $stats['four_star'] }}</div>
                        <div class="text-sm text-blue-200">4-5 Stjärnor</div>
                    </div>
                </div>

                <!-- Detailed Ratings -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 max-w-3xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/30">
                        <div class="text-sm text-blue-200">Tjänstekvalitet</div>
                        <div class="flex items-center justify-center mt-1">
                            <span class="text-2xl font-bold mr-2">{{ $stats['service_quality_avg'] }}</span>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/30">
                        <div class="text-sm text-blue-200">Priser</div>
                        <div class="flex items-center justify-center mt-1">
                            <span class="text-2xl font-bold mr-2">{{ $stats['price_avg'] }}</span>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/30">
                        <div class="text-sm text-blue-200">Snabbhet</div>
                        <div class="flex items-center justify-center mt-1">
                            <span class="text-2xl font-bold mr-2">{{ $stats['speed_avg'] }}</span>
                            <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Content Sections -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Intro Paragraph -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="prose prose-lg max-w-none text-center">
                    <p class="text-gray-700 leading-relaxed text-xl">
                        Läs över {{ $stats['total'] }} verifierade kundrecensioner med genomsnittligt betyg {{ $stats['average_rating'] }}/5 stjärnor. 
                        Våra kunder delar sina upplevelser av både vår plattform och de professionella företag vi samarbetar med. 
                        Alla recensioner kommer från verkliga kunder som har använt våra tjänster och genomgår vår moderationsprocess.
                    </p>
                </div>
            </div>
        </section>

        <!-- Features/Benefits Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Varför lita på våra recensioner?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">✅</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Verifierade recensioner</h3>
                            <p class="text-gray-600">Alla recensioner kommer från kunder som faktiskt har använt våra tjänster.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🔍</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Modererade innehåll</h3>
                            <p class="text-gray-600">Alla recensioner granskas för att säkerställa äkthet och kvalitet.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">⭐</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Höga betyg</h3>
                            <p class="text-gray-600">Genomsnittligt {{ $stats['average_rating'] }}/5 stjärnor från tusentals nöjda kunder.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">📊</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Detaljerad statistik</h3>
                            <p class="text-gray-600">Se exakt hur många som gav varje betyg för full transparens.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🔄</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Dual review system</h3>
                            <p class="text-gray-600">Kunder recenserar både företaget och vår plattform separat.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">💬</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Detaljerade kommentarer</h3>
                            <p class="text-gray-600">Läs vad kunderna verkligen tycker om sina upplevelser.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process/How It Works Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Så här fungerar vårt recensionssystem</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            1
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tjänst utförd</h3>
                        <p class="text-gray-600">Kunden får sin tjänst utförd av ett verifierat företag.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            2
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Dual recension</h3>
                        <p class="text-gray-600">Kunden recenserar både företaget och vår plattform.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            3
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Moderering</h3>
                        <p class="text-gray-600">Vi granskar alla recensioner för äkthet och kvalitet.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            4
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Publicering</h3>
                        <p class="text-gray-600">Godkända recensioner visas för andra kunder att läsa.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vanliga frågor om recensioner</h2>
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Är alla recensioner äkta?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Ja, alla recensioner kommer från verifierade kunder som faktiskt har använt våra tjänster. Vi modererar alla recensioner för att säkerställa äkthet.
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Vad är dual review system?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Vårt dual review system låter kunder recensera både det specifika företaget de använde och vår plattform separat, vilket ger mer detaljerad feedback.
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Kan jag lämna en recension?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Ja, om du har använt våra tjänster kan du lämna recensioner för både företaget och vår plattform. Du får en länk via email efter att tjänsten är slutförd.
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Hur modererar ni recensioner?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Vi granskar alla recensioner för att säkerställa att de är äkta, relevanta och följer våra riktlinjer innan de publiceras.
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Kan jag redigera min recension?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Ja, du kan kontakta oss för att redigera din recension om dina åsikter har förändrats efter att tjänsten är slutförd.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="mb-12">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white text-center">
                <h2 class="text-3xl font-bold mb-4">Redo att lämna din recension?</h2>
                <p class="text-xl text-blue-100 mb-6">Har du använt våra tjänster? Dela din upplevelse med andra kunder.</p>
                <a href="{{ route('public.categories') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span class="mr-2">⭐</span>
                    Börja boka nu
                </a>
            </div>
        </section>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Reviews List (Left - 2 columns) -->
            <div class="lg:col-span-2">
                <!-- Filters -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <form method="GET" action="{{ route('reviews.index') }}" class="flex flex-wrap gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sortera:</label>
                            <select name="sort" onchange="this.form.submit()" 
                                    class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="best" {{ $sort == 'best' ? 'selected' : '' }}>Bäst först</option>
                                <option value="recent" {{ $sort == 'recent' ? 'selected' : '' }}>Senaste först</option>
                                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Äldsta först</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Minst betyg:</label>
                            <select name="rating" onchange="this.form.submit()" 
                                    class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Alla</option>
                                <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5 stjärnor</option>
                                <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4+ stjärnor</option>
                                <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3+ stjärnor</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Reviews -->
                @forelse($reviews as $review)
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6 hover:shadow-xl transition border border-gray-100">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($review->name, 0, 1)) }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $review->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-gray-900 mr-2">{{ $review->overall_rating }}</span>
                                    <svg class="w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Detailed Ratings -->
                            <div class="flex flex-wrap gap-3 mb-3">
                                @if($review->service_quality_rating)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-600 mr-1">Kvalitet:</span>
                                        <span class="font-semibold text-blue-600">{{ $review->service_quality_rating }}/5</span>
                                    </div>
                                @endif
                                @if($review->price_rating)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-600 mr-1">Pris:</span>
                                        <span class="font-semibold text-green-600">{{ $review->price_rating }}/5</span>
                                    </div>
                                @endif
                                @if($review->speed_rating)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-600 mr-1">Snabbhet:</span>
                                        <span class="font-semibold text-purple-600">{{ $review->speed_rating }}/5</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Comment -->
                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>

                            @if($review->is_featured)
                                <span class="inline-block mt-3 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                    ⭐ Utvald recension
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div class="text-6xl mb-4">😔</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Inga recensioner ännu</h3>
                    <p class="text-gray-600">Bli den första att lämna en recension!</p>
                </div>
                @endforelse

                <!-- Pagination -->
                @if($reviews->hasPages())
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
                @endif
            </div>

            <!-- Submit Review Form (Right - 1 column) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        ✍️ Skriv en recension
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Dela din upplevelse och hjälp andra kunder!
                    </p>

                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Namn <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required readonly
                                   value="{{ auth()->check() ? auth()->user()->name : 'Anonym' }}"
                                   class="w-full rounded-lg border-gray-300 bg-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Ditt namn">
                            @if(!auth()->check())
                                <p class="text-xs text-gray-500 mt-1">
                                    💡 Din recension publiceras anonymt. <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Logga in</a> för att visa ditt namn.
                                </p>
                            @endif
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (optional) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                E-post (valfritt)
                            </label>
                            <input type="email" name="email"
                                   value="{{ auth()->check() ? auth()->user()->email : old('email') }}"
                                   {{ auth()->check() ? 'readonly' : '' }}
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ auth()->check() ? 'bg-gray-100' : '' }}"
                                   placeholder="din@email.com (visas ej)">
                            @if(!auth()->check())
                                <p class="text-xs text-gray-500 mt-1">
                                    📧 Din e-post visas aldrig publikt och används endast för verifiering.
                                </p>
                            @endif
                        </div>

                        <!-- Overall Rating -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Totalbetyg <span class="text-red-500">*</span>
                            </label>
                            <div class="flex space-x-2" x-data="{ rating: {{ old('overall_rating', 0) }} }">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" @click="rating = {{ $i }}"
                                            class="focus:outline-none transform hover:scale-110 transition">
                                        <svg class="w-10 h-10 fill-current" 
                                             :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
                                             viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    </button>
                                @endfor
                                <input type="hidden" name="overall_rating" x-model="rating">
                            </div>
                            @error('overall_rating')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Detailed Ratings -->
                        <div class="grid grid-cols-3 gap-3">
                            <!-- Service Quality -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kvalitet</label>
                                <select name="service_quality_rating" 
                                        class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }}⭐</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pris</label>
                                <select name="price_rating" 
                                        class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }}⭐</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Speed -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Snabbhet</label>
                                <select name="speed_rating" 
                                        class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }}⭐</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Comment -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Din recension <span class="text-red-500">*</span>
                            </label>
                            <textarea name="comment" rows="4" required
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Berätta om din upplevelse...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-lg hover:shadow-xl">
                            📤 Skicka Recension
                        </button>

                        <p class="text-xs text-gray-500 text-center">
                            Din recension granskas innan publicering
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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

