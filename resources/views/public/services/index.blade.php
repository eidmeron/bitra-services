@extends('layouts.public')

@section('title', 'Alla Tjänster - Professionella Tjänster i Hela Sverige')

@section('meta_description', 'Utforska vårt kompletta utbud av professionella tjänster. Städning, trädgårdsskötsel, målning och mycket mer. Boka direkt online med verifierade företag.')

@section('meta_keywords', 'tjänster, professionella tjänster, städning, trädgård, målning, ROT-avdrag, bokning')

@push('seo')
<meta property="og:title" content="Alla Tjänster - Professionella Tjänster i Hela Sverige">
<meta property="og:description" content="Utforska vårt kompletta utbud av professionella tjänster. Boka direkt online med verifierade företag.">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ route('public.services') }}">
@endpush

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    🛠️ Våra Tjänster
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    Utforska vårt kompletta utbud av professionella tjänster. Från städning till trädgårdsskötsel - vi har allt du behöver.
                </p>
                <p class="mt-4 text-lg">
                    <span class="font-semibold">{{ $services->total() }}</span> tjänster tillgängliga
                </p>
            </div>
        </div>
    </div>

    <!-- Filters and Services Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form method="GET" action="{{ route('public.services') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Kategori
                        </label>
                        <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla kategorier</option>
                            @foreach($allCategories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->services_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            Stad
                        </label>
                        <select name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla städer</option>
                            @foreach($allCities as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t mt-4">
                    <div class="text-sm text-gray-600">
                        Visar {{ $services->count() }} av {{ $services->total() }} tjänster
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('public.services') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                            Rensa filter
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition shadow-lg">
                            🔍 Filtrera
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Services Grid -->
        @if($services->isEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Inga tjänster hittades</h3>
                <p class="text-gray-600 mb-8">Prova att ändra dina filter för att se fler resultat</p>
                <a href="{{ route('public.services') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                    Visa alla tjänster
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                    @php
                        $activeForm = $service->active_form;
                        $hasForm = $activeForm && $activeForm->token;
                    @endphp
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                        <!-- Service Image -->
                        @if($service->image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ Storage::url($service->image) }}" 
                                     alt="{{ $service->name }}" 
                                     class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-300">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                                <span class="text-6xl">{{ $service->icon ?? '🛠️' }}</span>
                            </div>
                        @endif

                        <!-- Service Content -->
                        <div class="p-6">
                            <!-- Category Badge -->
                            @if($service->category)
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full mb-3">
                                    {{ $service->category->name }}
                                </span>
                            @endif

                            <!-- Service Name -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                {{ $service->name }}
                            </h3>

                            <!-- Service Description -->
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $service->description ?? 'Professionell tjänst med hög kvalitet.' }}
                            </p>

                            <!-- Service Includes -->
                            @if($service->includes && count($service->includes) > 0)
                                <div class="mb-4">
                                    <div class="text-xs font-semibold text-gray-700 mb-2">Inkluderar:</div>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        @foreach(array_slice($service->includes, 0, 3) as $include)
                                            <li class="flex items-center">
                                                <svg class="w-3 h-3 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $include }}
                                            </li>
                                        @endforeach
                                        @if(count($service->includes) > 3)
                                            <li class="text-blue-600 font-medium">+ {{ count($service->includes) - 3 }} mer...</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <!-- Price & ROT -->
                            <div class="flex items-center justify-between mb-4 pb-4 border-b">
                                @if($service->base_price)
                                    <div>
                                        <div class="text-xs text-gray-500">Från</div>
                                        <div class="flex items-center space-x-2">
                                            @if($service->discount_percent && $service->discount_percent > 0)
                                                <div class="text-lg text-gray-400 line-through">
                                                    {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                                </div>
                                                <div class="text-2xl font-bold text-green-600">
                                                    {{ number_format($service->base_price * (1 - $service->discount_percent / 100), 0, ',', ' ') }} kr
                                                </div>
                                            @else
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                                </div>
                                            @endif
                                        </div>
                                        @if($service->discount_percent && $service->discount_percent > 0)
                                            <div class="text-sm text-green-600 font-semibold">
                                                {{ number_format($service->discount_percent, 0) }}% rabatt!
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if($service->rot_eligible)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        🏡 ROT {{ $service->rot_percent }}%
                                    </span>
                                @endif
                            </div>

                            <!-- Cities Available -->
                            @if($service->cities->count() > 0)
                                <div class="mb-4">
                                    <div class="text-xs font-semibold text-gray-700 mb-2">📍 Tillgänglig i:</div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($service->cities->take(3) as $city)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                                {{ $city->name }}
                                            </span>
                                        @endforeach
                                        @if($service->cities->count() > 3)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-medium">
                                                +{{ $service->cities->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                @if($hasForm)
                                    <a href="{{ route('public.form', $activeForm->token) }}" 
                                       class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        📅 Boka Nu
                                    </a>
                                @else
                                    <a href="{{ route('public.service.show', $service->slug) }}" 
                                       class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        📅 Boka Nu
                                    </a>
                                @endif
                                
                                <a href="{{ route('public.service.show', $service->slug) }}" 
                                   class="block w-full text-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                                    ℹ️ Läs mer
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($services->hasPages())
                <div class="mt-12">
                    {{ $services->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- SEO Content Sections -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Intro Paragraph -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="prose prose-lg max-w-none text-center">
                    <p class="text-gray-700 leading-relaxed text-xl">
                        Upptäck Sveriges mest omfattande plattform för professionella tjänster. Vi samarbetar med över 1000 verifierade företag 
                        för att erbjuda dig de bästa tjänsterna inom alla kategorier. Från städning och renovering till trädgårdsarbete 
                        och flytt - vi har allt du behöver under ett tak med garanterad kvalitet och transparenta priser.
                    </p>
                </div>
            </div>
        </section>

        <!-- Features/Benefits Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Varför välja vår plattform för tjänster?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">⚡</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Snabb och responsiv design</h3>
                            <p class="text-gray-600">Vår plattform är optimerad för snabb laddning och enkel navigation på alla enheter.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🔍</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">SEO-optimerade webbplatser</h3>
                            <p class="text-gray-600">Alla våra tjänster är optimerade för sökmotorer för bättre synlighet.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🔗</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Integrerad med API och databaser</h3>
                            <p class="text-gray-600">Avancerad teknisk integration för smidig användarupplevelse.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">⭐</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Verifierade företag</h3>
                            <p class="text-gray-600">Alla våra partners är noggrant valda och verifierade för kvalitet.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">💰</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Bästa priserna</h3>
                            <p class="text-gray-600">Konkurrenskraftiga priser utan dolda avgifter eller överraskningar.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🛡️</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Säker betalning</h3>
                            <p class="text-gray-600">Säker och krypterad betalningshantering för din trygghet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process/How It Works Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Så här fungerar det</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            1
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Analys</h3>
                        <p class="text-gray-600">Vi analyserar dina behov och matchar dig med rätt tjänst och företag.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            2
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Design</h3>
                        <p class="text-gray-600">Vi skapar en skräddarsydd lösning som passar dina specifika krav.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            3
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Utveckling</h3>
                        <p class="text-gray-600">Våra experter genomför tjänsten med högsta kvalitet och precision.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            4
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Lansering</h3>
                        <p class="text-gray-600">Du får en färdig lösning som överstiger dina förväntningar.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vanliga frågor om våra tjänster</h2>
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Vad kostar en tjänst i Sverige?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Priserna varierar beroende på tjänst och omfattning. Vi erbjuder transparenta priser utan dolda avgifter. Kontakta oss för en personlig offert.
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Hur lång tid tar det att få en tjänst?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Tidsramen beror på tjänstens komplexitet. Enkla tjänster kan vara klara samma dag, medan större projekt kan ta veckor. Vi ger alltid en realistisk tidsram innan vi börjar.
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Är alla företag verifierade?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Ja, alla våra partnerföretag genomgår en noggrann verifieringsprocess inklusive bakgrundskontroll, försäkring och kvalitetscertifiering.
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Vad händer om jag inte är nöjd?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Vi erbjuder 100% nöjdhetsgaranti. Om du inte är helt nöjd, arbetar vi tillsammans för att lösa problemet eller ger dig pengarna tillbaka.
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                            <span class="font-semibold text-gray-900">Kan jag boka tjänster online?</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4 text-gray-600 hidden">
                            Absolut! Vår plattform är designad för enkel online-bokning. Du kan boka, betala och hantera dina tjänster helt digitalt.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vad våra kunder säger</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <span class="text-yellow-400">⭐</span>
                            @endfor
                        </div>
                        <p class="text-gray-700 mb-4 italic">"Fantastisk plattform! Fick min städning bokad på 5 minuter och resultatet var perfekt."</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">A</div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900">Anna L.</p>
                                <p class="text-sm text-gray-500">Stockholm</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <span class="text-yellow-400">⭐</span>
                            @endfor
                        </div>
                        <p class="text-gray-700 mb-4 italic">"Professionell service och konkurrenskraftiga priser. Rekommenderas varmt!"</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">M</div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900">Marcus K.</p>
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
                        <p class="text-gray-700 mb-4 italic">"Enkel att använda och mycket tillförlitlig. Har använt flera gånger nu."</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">S</div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900">Sofia H.</p>
                                <p class="text-sm text-gray-500">Malmö</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="mb-12">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white text-center">
                <h2 class="text-3xl font-bold mb-4">Redo att komma igång?</h2>
                <p class="text-xl text-blue-100 mb-6">Upptäck alla våra tjänster och hitta den perfekta lösningen för dina behov.</p>
                <a href="{{ route('public.categories') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span class="mr-2">🚀</span>
                    Utforska alla kategorier
                </a>
            </div>
        </section>
    </div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form method="GET" action="{{ route('public.services') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Kategori
                        </label>
                        <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla kategorier</option>
                            @foreach($allCategories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->services_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            Stad
                        </label>
                        <select name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla städer</option>
                            @foreach($allCities as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t mt-4">
                    <div class="text-sm text-gray-600">
                        Visar {{ $services->count() }} av {{ $services->total() }} tjänster
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('public.services') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                            Rensa filter
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition shadow-lg">
                            🔍 Filtrera
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Services Grid -->
        @if($services->isEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Inga tjänster hittades</h3>
                <p class="text-gray-600 mb-8">Prova att ändra dina filter för att se fler resultat</p>
                <a href="{{ route('public.services') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                    Visa alla tjänster
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($services as $service)
                    @php
                        $activeForm = $service->active_form;
                        $hasForm = $activeForm && $activeForm->token;
                    @endphp
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                        <!-- Service Image -->
                        @if($service->image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ Storage::url($service->image) }}" 
                                     alt="{{ $service->name }}" 
                                     class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-300">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                                <span class="text-6xl">{{ $service->icon ?? '🛠️' }}</span>
                            </div>
                        @endif

                        <!-- Service Content -->
                        <div class="p-6">
                            <!-- Category Badge -->
                            @if($service->category)
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full mb-3">
                                    {{ $service->category->name }}
                                </span>
                            @endif

                            <!-- Service Name -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $service->name }}</h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ $service->description }}
                            </p>

                            <!-- Features/Includes Preview -->
                            @if($service->includes && count($service->includes) > 0)
                                <div class="mb-4">
                                    <div class="text-xs font-semibold text-gray-700 mb-2">✅ Inkluderar:</div>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        @foreach(array_slice($service->includes, 0, 3) as $include)
                                            <li class="flex items-start">
                                                <span class="text-green-500 mr-1">•</span>
                                                <span>{{ $include }}</span>
                                            </li>
                                        @endforeach
                                        @if(count($service->includes) > 3)
                                            <li class="text-blue-600 font-medium">+ {{ count($service->includes) - 3 }} mer...</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <!-- Price & ROT -->
                            <div class="flex items-center justify-between mb-4 pb-4 border-b">
                                @if($service->base_price)
                                    <div>
                                        <div class="text-xs text-gray-500">Från</div>
                                        <div class="flex items-center space-x-2">
                                            @if($service->discount_percent && $service->discount_percent > 0)
                                                <div class="text-lg text-gray-400 line-through">
                                                    {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                                </div>
                                                <div class="text-2xl font-bold text-green-600">
                                                    {{ number_format($service->base_price * (1 - $service->discount_percent / 100), 0, ',', ' ') }} kr
                                                </div>
                                            @else
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                                </div>
                                            @endif
                                        </div>
                                        @if($service->discount_percent && $service->discount_percent > 0)
                                            <div class="text-sm text-green-600 font-semibold">
                                                {{ number_format($service->discount_percent, 0) }}% rabatt!
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if($service->rot_eligible)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        🏡 ROT {{ $service->rot_percent }}%
                                    </span>
                                @endif
                            </div>

                            <!-- Cities Available -->
                            @if($service->cities->count() > 0)
                                <div class="mb-4">
                                    <div class="text-xs font-semibold text-gray-700 mb-2">📍 Tillgänglig i:</div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($service->cities->take(3) as $city)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                                {{ $city->name }}
                                            </span>
                                        @endforeach
                                        @if($service->cities->count() > 3)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-medium">
                                                +{{ $service->cities->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                @if($hasForm)
                                    <a href="{{ route('public.form', $activeForm->token) }}" 
                                       class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        📅 Boka Nu
                                    </a>
                                @endif
                                <a href="{{ route('public.service.show', $service->slug) }}" 
                                   class="block w-full text-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:border-blue-600 hover:text-blue-600 transition-all">
                                    📖 Läs Mer
                                </a>
                            </div>
                        </div>
        </div>
        @endforeach
            </div>

            <!-- Pagination -->
            @if($services->hasPages())
                <div class="mt-12">
                    {{ $services->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

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
