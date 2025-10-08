<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Boka tjänster i hela Sverige</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="container mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-blue-600">{{ config('app.name') }}</h1>
                        <p class="text-sm text-gray-600">Sveriges största bokningsplattform för hemtjänster</p>
                    </div>
                    <div class="flex space-x-4">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Admin Panel</a>
                            @elseif(auth()->user()->isCompany())
                                <a href="{{ route('company.dashboard') }}" class="btn btn-primary">Företagspanel</a>
                            @else
                                <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Min Sida</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary">Logga in</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Registrera</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-5xl font-bold mb-4">Boka hemtjänster enkelt och smidigt</h2>
                <p class="text-xl mb-8">Med ROT-avdrag och transparenta priser</p>
                <div class="flex justify-center space-x-4">
                    <a href="#services" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Utforska tjänster
                    </a>
                    <a href="#how-it-works" class="border-2 border-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                        Hur det fungerar
                    </a>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-6">
                <h3 class="text-3xl font-bold text-center mb-12">Varför välja oss?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-5xl mb-4">✓</div>
                        <h4 class="text-xl font-semibold mb-2">ROT-avdrag</h4>
                        <p class="text-gray-600">Få upp till 30% avdrag på din skatt för godkända tjänster</p>
                    </div>
                    <div class="text-center">
                        <div class="text-5xl mb-4">💰</div>
                        <h4 class="text-xl font-semibold mb-2">Transparenta priser</h4>
                        <p class="text-gray-600">Se exakt pris innan du bokar - inga dolda avgifter</p>
                    </div>
                    <div class="text-center">
                        <div class="text-5xl mb-4">🏆</div>
                        <h4 class="text-xl font-semibold mb-2">Verifierade företag</h4>
                        <p class="text-gray-600">Alla företag är granskade och godkända</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it Works -->
        <section id="how-it-works" class="py-16 bg-gray-50">
            <div class="container mx-auto px-6">
                <h3 class="text-3xl font-bold text-center mb-12">Så fungerar det</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                        <h4 class="font-semibold mb-2">Välj tjänst</h4>
                        <p class="text-gray-600">Hitta den tjänst du behöver</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                        <h4 class="font-semibold mb-2">Fyll i formulär</h4>
                        <p class="text-gray-600">Beskriv ditt behov och se pris direkt</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                        <h4 class="font-semibold mb-2">Få matchning</h4>
                        <p class="text-gray-600">Vi matchar dig med rätt företag</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">4</div>
                        <h4 class="font-semibold mb-2">Jobbet utförs</h4>
                        <p class="text-gray-600">Företaget utför tjänsten professionellt</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-blue-600 text-white">
            <div class="container mx-auto px-6 text-center">
                <h3 class="text-3xl font-bold mb-4">Redo att komma igång?</h3>
                <p class="text-xl mb-8">Boka din första tjänst idag och få professionell hjälp</p>
                @guest
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                        Skapa konto gratis
                    </a>
                @endguest
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-8">
            <div class="container mx-auto px-6 text-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Alla rättigheter förbehållna.</p>
                <div class="mt-4 space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white">Om oss</a>
                    <a href="#" class="text-gray-400 hover:text-white">Kontakt</a>
                    <a href="#" class="text-gray-400 hover:text-white">Integritetspolicy</a>
                    <a href="#" class="text-gray-400 hover:text-white">Användarvillkor</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
