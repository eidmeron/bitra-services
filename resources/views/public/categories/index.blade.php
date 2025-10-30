@extends('layouts.public')

@section('title', 'Alla kategorier - Professionella tjänster i hela Sverige | Städning, Hantverkare, Trädgård, Flytt, Reparationer')

@push('meta')
    <meta name="keywords" content="städning Solna, städning Sundbyberg, städning Nacka, städning Huddinge, städning Göteborg, städning Mölndal, städning Borås, städning Trollhättan, städning Malmö, städning Lund, städning Helsingborg, städning Kristianstad, städning Uppsala, städning Enköping, hantverkare Solna, hantverkare Sundbyberg, hantverkare Nacka, hantverkare Huddinge, hantverkare Göteborg, hantverkare Mölndal, hantverkare Borås, hantverkare Trollhättan, hantverkare Malmö, hantverkare Lund, hantverkare Helsingborg, hantverkare Kristianstad, hantverkare Uppsala, hantverkare Enköping, trädgård Solna, trädgård Sundbyberg, trädgård Nacka, trädgård Huddinge, trädgård Göteborg, trädgård Mölndal, trädgård Borås, trädgård Trollhättan, trädgård Malmö, trädgård Lund, trädgård Helsingborg, trädgård Kristianstad, trädgård Uppsala, trädgård Enköping, flytt Solna, flytt Sundbyberg, flytt Nacka, flytt Huddinge, flytt Göteborg, flytt Mölndal, flytt Borås, flytt Trollhättan, flytt Malmö, flytt Lund, flytt Helsingborg, flytt Kristianstad, flytt Uppsala, flytt Enköping, reparationer Solna, reparationer Sundbyberg, reparationer Nacka, reparationer Huddinge, reparationer Göteborg, reparationer Mölndal, reparationer Borås, reparationer Trollhättan, reparationer Malmö, reparationer Lund, reparationer Helsingborg, reparationer Kristianstad, reparationer Uppsala, reparationer Enköping, professionella tjänster, bokning online, Sverige">
    <meta property="og:title" content="Alla kategorier - Professionella tjänster i hela Sverige">
    <meta property="og:description" content="Boka professionella tjänster i hela Sverige: städning, hantverkare, trädgård, flytt, reparationer i Solna, Sundbyberg, Nacka, Huddinge, Göteborg, Mölndal, Borås, Trollhättan, Malmö, Lund, Helsingborg, Kristianstad, Uppsala, Enköping. Verifierade företag, transparenta priser, enkel bokning online.">
    <meta name="twitter:title" content="Alla kategorier - Professionella tjänster i hela Sverige">
    <meta name="twitter:description" content="Boka professionella tjänster i hela Sverige: städning, hantverkare, trädgård, flytt, reparationer i Solna, Sundbyberg, Nacka, Huddinge, Göteborg, Mölndal, Borås, Trollhättan, Malmö, Lund, Helsingborg, Kristianstad, Uppsala, Enköping. Verifierade företag, transparenta priser, enkel bokning online.">
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                🏠 Alla Kategorier
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-4xl mx-auto">
                Upptäck vårt kompletta utbud av professionella tjänster: städning, hantverkare, trädgård, flytt, reparationer i Solna, Sundbyberg, Nacka, Huddinge, Göteborg, Mölndal, Borås, Trollhättan, Malmö, Lund, Helsingborg, Kristianstad, Uppsala, Enköping. Vi har allt du behöver för ditt hem och företag.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#categories" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span class="mr-2">🔍</span> Utforska kategorier
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-blue-600 transition-colors duration-300">
                    <span>Kontakta oss</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section id="categories" class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Utforska alla kategorier
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Hitta den perfekta tjänsten för dina behov. Vi har samarbetat med Sveriges bästa företag för att erbjuda tjänster inom alla kategorier.
            </p>
        </div>
        
        @if($categories->isEmpty())
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto text-gray-400 mb-6">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Inga kategorier tillgängliga</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8">För närvarande finns det inga kategorier att visa</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('public.category.show', $category->slug) }}" 
                       class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-200 dark:border-gray-700">
                        <!-- Category Image/Icon -->
                        <div class="relative h-48 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center overflow-hidden">
                            @if($category->icon)
                                <span class="text-8xl group-hover:scale-110 transition-transform duration-500">
                                    {{ $category->icon }}
                                </span>
                            @elseif($category->image)
                                <img src="{{ Storage::url($category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-24 h-24 object-contain group-hover:scale-110 transition-transform duration-500">
                            @elseif($category->image)
                                <img src="{{ Storage::url($category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <span class="text-8xl group-hover:scale-110 transition-transform duration-500">
                                    {{ ['🏠', '🔧', '🌳', '🎨', '💼', '🚗', '💡', '🏗️'][array_rand(['🏠', '🔧', '🌳', '🎨', '💼', '🚗', '💡', '🏗️'])] }}
                                </span>
                            @endif
                            
                            <!-- Service Count Badge -->
                            <div class="absolute top-4 right-4 bg-white dark:bg-gray-800 rounded-full px-4 py-2 shadow-lg">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                    {{ $category->services_count }} tjänster
                                </span>
                            </div>
                        </div>
                        
                        <!-- Category Info -->
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $category->name }}
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ $category->description ?? 'Upptäck professionella tjänster inom ' . strtolower($category->name) . '.' }}
                            </p>
                            
                            <!-- CTA -->
                            <div class="flex items-center text-blue-600 dark:text-blue-400 font-semibold group-hover:translate-x-2 transition-transform">
                                <span>Utforska tjänster</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- SEO Content Sections -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Intro Paragraph -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="prose prose-lg max-w-none text-center">
                    <p class="text-gray-700 leading-relaxed text-xl">
                        Upptäck Sveriges mest omfattande plattform för professionella tjänster. Vi samarbetar med över 1000 verifierade företag 
                        för att erbjuda dig de bästa tjänsterna inom alla kategorier: städning, hantverkare, trädgård, flytt, reparationer i Solna, Sundbyberg, Nacka, Huddinge, Göteborg, Mölndal, Borås, Trollhättan, Malmö, Lund, Helsingborg, Kristianstad, Uppsala, Enköping. Vi har allt du behöver under ett tak.
                    </p>
            </div>
        </div>
    </section>

    <!-- Features/Benefits Section -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Fördelar med Bitra</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">⏰</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Spara tid</h3>
                        <p class="text-gray-600">Slipp leta efter pålitliga leverantörer. Vi har redan gjort jobbet åt dig genom att verifiera alla våra partners.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Bästa värde</h3>
                        <p class="text-gray-600">Konkurrenskraftiga och transparenta priser som passar dina behov. Inga dolda avgifter eller prismanipulation.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🛡️</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Trygghet</h3>
                        <p class="text-gray-600">Endast förhandsvalda och kvalitetsgaranterade företag. Varje partner uppfyller Bitras kvalitetskrav.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">📋</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Livstidsregister</h3>
                        <p class="text-gray-600">All din servicehistorik samlad och säker. Tillgänglig för alltid i vårt enkla och säkra system.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🎁</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belöningar</h3>
                        <p class="text-gray-600">Tjäna poäng på varje bokning och vid nya medlemsregistreringar. Använd poängen för framtida tjänster.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🏷️</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Regelbundna rabatter</h3>
                        <p class="text-gray-600">Njut av regelbundna rabatter och kampanjer. Få tillgång till exklusiva medlemserbjudanden.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process/How It Works Section -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Så fungerar Bitra</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        1
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Noggrant utvalda partners</h3>
                    <p class="text-gray-600">Vi samarbetar endast med utvalda och granskade företag i varje stad. Varje partner uppfyller Bitras kvalitetskrav.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        2
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Enhetlig plattform</h3>
                    <p class="text-gray-600">Alla tjänster samlade på ett ställe: sök, jämför, boka och betala utan mellanhänder. Tydliga och fasta priser.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        3
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Enkel bokning & säkra register</h3>
                    <p class="text-gray-600">Boka på några klick. Varje bokning dokumenteras och sparas säkert så att din servicehistorik är tillgänglig.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                        4
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nöjd kund-garanti</h3>
                    <p class="text-gray-600">Bitra står bakom varje företag och varje tjänst. Om något inte motsvarar förväntningarna ser vårt supportteam till att snabbt åtgärda det.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Vanliga frågor</h2>
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

<!-- Categories Grid -->
<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($categories->isEmpty())
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📂</div>
                <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">
                    Inga kategorier tillgängliga
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Vi arbetar på att lägga till fler tjänster. Kom tillbaka snart!
                </p>
            </div>
        @else

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('public.category.show', $category->slug) }}" 
                       class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                        
                        <!-- Category Image/Icon -->
                        <div class="relative h-48 bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center overflow-hidden">
                            @if($category->icon)
                                <img src="{{ Storage::url($category->icon) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-24 h-24 object-contain group-hover:scale-110 transition-transform duration-500">
                            @elseif($category->image)
                                <img src="{{ Storage::url($category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <span class="text-8xl group-hover:scale-110 transition-transform duration-500">
                                    {{ ['🏠', '🔧', '🌳', '🎨', '💼', '🚗', '💡', '🏗️'][array_rand(['🏠', '🔧', '🌳', '🎨', '💼', '🚗', '💡', '🏗️'])] }}
                                </span>
                            @endif
                            
                            <!-- Service Count Badge -->
                            <div class="absolute top-4 right-4 bg-white dark:bg-gray-800 rounded-full px-4 py-2 shadow-lg">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                    {{ $category->services_count }} tjänster
                                </span>
                            </div>
                        </div>
                        
                        <!-- Category Info -->
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $category->name }}
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ $category->description ?? 'Upptäck professionella tjänster inom ' . strtolower($category->name) . '.' }}
                            </p>
                            
                            <!-- CTA -->
                            <div class="flex items-center text-blue-600 dark:text-blue-400 font-semibold group-hover:translate-x-2 transition-transform">
                                <span>Utforska tjänster</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-4">
            Hittar du inte vad du söker?
        </h2>
        <p class="text-xl text-blue-100 mb-8">
            Kontakta oss så hjälper vi dig att hitta rätt tjänst
        </p>
        <a href="{{ route('contact') }}" 
           class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-full hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            <span>Kontakta oss</span>
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</section>

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

