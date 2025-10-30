@extends('layouts.public')

@section('title', 'Alla Företag - Hitta Professionella Tjänsteleverantörer')

@section('meta_description', 'Bläddra bland Sveriges bästa tjänsteföretag. Jämför priser, läs recensioner och boka direkt online. Verifierade företag med höga betyg.')

@section('meta_keywords', 'företag, tjänster, recensioner, bokning, professionella, Sverige')

@push('seo')
<meta property="og:title" content="Alla Företag - Hitta Professionella Tjänsteleverantörer">
<meta property="og:description" content="Bläddra bland Sveriges bästa tjänsteföretag. Jämför priser, läs recensioner och boka direkt online.">
<meta property="og:type" content="website">
<link rel="canonical" href="{{ route('public.companies') }}">
@endpush

@section('content')
<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                🏢 Alla Företag
            </h1>
            <p class="text-xl text-gray-600">
                Hitta och jämför verifierade och professionella företag
            </p>
            <p class="text-gray-500 mt-2">
                <span class="font-semibold text-gray-900">{{ $companies->total() }}</span> företag tillgängliga
            </p>
        </div>

        <!-- SEO Content Sections -->
        <div class="mb-12">
            <!-- Intro Paragraph -->
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="prose prose-lg max-w-none text-center">
                        <p class="text-gray-700 leading-relaxed text-xl">
                            Upptäck Sveriges mest omfattande nätverk av verifierade tjänsteföretag. Vi samarbetar med över 1000 professionella företag 
                            som har genomgått vår noggranna verifieringsprocess. Från städning och renovering till trädgårdsarbete 
                            och flytt - alla våra partners är verifierade för kvalitet, säkerhet och tillförlitlighet.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Features/Benefits Section -->
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Varför välja våra verifierade företag?</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">✅</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Verifierade företag</h3>
                                <p class="text-gray-600">Alla företag genomgår bakgrundskontroll, försäkring och kvalitetscertifiering.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">⭐</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Höga betyg</h3>
                                <p class="text-gray-600">Genomsnittligt 4.8+ stjärnor från tusentals nöjda kunder.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">🛡️</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Försäkrade</h3>
                                <p class="text-gray-600">Alla företag har fullständig försäkring för din trygghet.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">💰</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Transparenta priser</h3>
                                <p class="text-gray-600">Inga dolda avgifter eller överraskningar - du vet alltid vad det kostar.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">⚡</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Snabb service</h3>
                                <p class="text-gray-600">Många tjänster kan bokas samma dag eller inom 24 timmar.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">🔄</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Nöjdhetsgaranti</h3>
                                <p class="text-gray-600">100% nöjdhetsgaranti - annars löser vi problemet eller ger pengarna tillbaka.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Process/How It Works Section -->
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Så här hittar du rätt företag</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                                1
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Sök & Filtrera</h3>
                            <p class="text-gray-600">Använd våra filter för att hitta företag baserat på stad, tjänst och betyg.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                                2
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Jämför & Välj</h3>
                            <p class="text-gray-600">Läs recensioner, jämför priser och välj det företag som passar dig bäst.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                                3
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Boka Direkt</h3>
                            <p class="text-gray-600">Boka enkelt online med säker betalning och omedelbar bekräftelse.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                                4
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Få Service</h3>
                            <p class="text-gray-600">Få professionell service och lämna din recension efteråt.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vanliga frågor om våra företag</h2>
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                                <span class="font-semibold text-gray-900">Hur verifierar ni företagen?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 text-gray-600 hidden">
                                Alla företag genomgår en omfattande verifieringsprocess inklusive bakgrundskontroll, försäkringsverifiering, kvalitetscertifiering och referenskontroll.
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                                <span class="font-semibold text-gray-900">Kan jag lita på betygen och recensionerna?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 text-gray-600 hidden">
                                Ja, alla recensioner kommer från verifierade kunder som faktiskt har använt tjänsten. Vi modererar alla recensioner för att säkerställa äkthet.
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                                <span class="font-semibold text-gray-900">Vad händer om jag inte är nöjd med företaget?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 text-gray-600 hidden">
                                Vi erbjuder 100% nöjdhetsgaranti. Om du inte är nöjd, arbetar vi tillsammans med företaget för att lösa problemet eller ger dig pengarna tillbaka.
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                                <span class="font-semibold text-gray-900">Är betalningen säker?</span>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="px-6 pb-4 text-gray-600 hidden">
                                Absolut! Vi använder banknivå kryptering och säkra betalningsmetoder. Dina betalningsuppgifter är alltid skyddade.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="mb-12">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vad våra kunder säger om företagen</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                @for($i = 0; $i < 5; $i++)
                                    <span class="text-yellow-400">⭐</span>
                                @endfor
                            </div>
                            <p class="text-gray-700 mb-4 italic">"Fantastiskt företag! Mycket professionellt och snabbt. Rekommenderas varmt!"</p>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">E</div>
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">Erik M.</p>
                                    <p class="text-sm text-gray-500">Uppsala</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                @for($i = 0; $i < 5; $i++)
                                    <span class="text-yellow-400">⭐</span>
                                @endfor
                            </div>
                            <p class="text-gray-700 mb-4 italic">"Perfekt service och mycket nöjd med resultatet. Kommer definitivt använda igen!"</p>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">L</div>
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">Lisa P.</p>
                                    <p class="text-sm text-gray-500">Västerås</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                @for($i = 0; $i < 5; $i++)
                                    <span class="text-yellow-400">⭐</span>
                                @endfor
                            </div>
                            <p class="text-gray-700 mb-4 italic">"Enkelt att boka och företaget var mycket tillförlitligt. Bra pris också!"</p>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">J</div>
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">Johan K.</p>
                                    <p class="text-sm text-gray-500">Linköping</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="mb-12">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white text-center">
                    <h2 class="text-3xl font-bold mb-4">Redo att hitta ditt perfekta företag?</h2>
                    <p class="text-xl text-blue-100 mb-6">Bläddra bland våra verifierade företag och hitta den bästa matchningen för dina behov.</p>
                    <a href="#companies-grid" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <span class="mr-2">🔍</span>
                        Utforska företag
                    </a>
                </div>
            </section>
        </div>

        <!-- Filters & Sorting -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form method="GET" action="{{ route('public.companies') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

                    <!-- Service Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Tjänst
                        </label>
                        <select name="service" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla tjänster</option>
                            @foreach($allServices as $service)
                                <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rating Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Min. Betyg
                        </label>
                        <select name="rating" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Alla betyg</option>
                            <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>⭐ 4+ stjärnor</option>
                            <option value="4.5" {{ request('rating') == 4.5 ? 'selected' : '' }}>⭐ 4.5+ stjärnor</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                            Sortera
                        </label>
                        <select name="sort" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Högsta betyg</option>
                            <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>Flest recensioner</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Namn (A-Ö)</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="text-sm text-gray-600">
                        Visar {{ $companies->count() }} av {{ $companies->total() }} företag
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('public.companies') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                            Rensa filter
                        </a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition shadow-lg">
                            🔍 Filtrera
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Companies Grid -->
        @if($companies->isEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Inga företag hittades</h3>
                <p class="text-gray-600 mb-8">Prova att ändra dina filter för att se fler resultat</p>
                <a href="{{ route('public.companies') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                    Visa alla företag
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($companies as $company)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                        <!-- Company Logo/Avatar -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <!-- Logo -->
                                    <div class="flex-shrink-0">
                                        @if($company->logo)
                                            <img src="{{ Storage::url($company->logo) }}" 
                                                 alt="{{ $company->company_name }}" 
                                                 class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200">
                                        @else
                                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white text-xl font-bold">
                                                {{ strtoupper(substr($company->company_name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 line-clamp-2">{{ $company->company_name }}</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Rating -->
                            @if($company->reviews_avg_rating)
                                <div class="flex items-center bg-yellow-50 px-4 py-2 rounded-lg mb-4">
                                    <svg class="w-5 h-5 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="font-bold text-gray-900 text-lg">{{ number_format($company->reviews_avg_rating, 1) }}</span>
                                    <span class="text-gray-500 text-sm ml-2">({{ $company->reviews_count }} recensioner)</span>
                                </div>
                            @endif

                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $company->description }}</p>

                            <!-- Services -->
                            @if($company->services && $company->services->isNotEmpty())
                                <div class="mb-4 flex flex-wrap gap-2">
                                    @foreach($company->services->take(3) as $service)
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $service->name }}</span>
                                    @endforeach
                                    @if($company->services->count() > 3)
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">+{{ $company->services->count() - 3 }} fler</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Cities -->
                            @if($company->cities && $company->cities->isNotEmpty())
                                <div class="mb-4 flex flex-wrap gap-2">
                                    @foreach($company->cities->take(3) as $city)
                                        <span class="text-xs px-2 py-1 bg-purple-50 text-purple-700 rounded flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $city->name }}
                                        </span>
                                    @endforeach
                                    @if($company->cities->count() > 3)
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">+{{ $company->cities->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif

                            <a href="{{ route('public.company.show', $company->id) }}" 
                               class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                                Visa företag →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $companies->links() }}
            </div>
        @endif
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
