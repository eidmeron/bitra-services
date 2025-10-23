<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrera - {{ site_name() }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .animated-gradient {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
    </style>
</head>
<body class="animated-gradient min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <a href="{{ route('welcome') }}" class="inline-block">
                    @if(setting('site_logo'))
                        <img src="{{ Storage::url(setting('site_logo')) }}" alt="{{ site_name() }}" class="h-20 mx-auto mb-4">
                    @else
                        <h1 class="text-5xl font-bold text-white mb-4">
                            {{ site_name() }}
                        </h1>
                    @endif
                </a>
                <h2 class="text-3xl font-extrabold text-white mb-2">
                    Skapa ditt konto! 🎉
                </h2>
                <p class="text-white/80">
                    Börja boka tjänster idag
                </p>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="py-8 px-6 sm:px-10">
                    <form class="space-y-6" method="POST" action="{{ route('register') }}">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <strong>Fel vid registrering</strong>
                                </div>
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(setting('loyalty_points_enabled', true) && setting('new_user_loyalty_bonus', 0) > 0)
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-purple-500 px-4 py-3 rounded-lg">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">⭐</span>
                                    <div>
                                        <p class="font-bold text-purple-900">Välkomsterbjudande!</p>
                                        <p class="text-sm text-purple-700">
                                            Få <strong>{{ setting('new_user_loyalty_bonus', 100) }} lojalitetspoäng</strong> 
                                            (värde: {{ setting('new_user_loyalty_bonus', 100) * setting('loyalty_points_value', 1) }} kr) när du registrerar dig!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                👤 Namn <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="name" 
                                name="name" 
                                type="text" 
                                autocomplete="name" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                value="{{ old('name') }}"
                                placeholder="Ditt fullständiga namn"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                📧 E-postadress <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                value="{{ old('email') }}"
                                placeholder="din@email.com"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                📱 Telefonnummer
                            </label>
                            <input 
                                id="phone" 
                                name="phone" 
                                type="tel" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                value="{{ old('phone') }}"
                                placeholder="070-123 45 67"
                            >
                            <p class="text-xs text-gray-500 mt-1">Valfritt, men rekommenderas</p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                🔒 Lösenord <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="new-password" 
                                required 
                                minlength="8"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                placeholder="Minst 8 tecken"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                🔒 Bekräfta lösenord <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                autocomplete="new-password" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                placeholder="Upprepa lösenord"
                            >
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-start">
                                <input 
                                    id="terms" 
                                    name="terms" 
                                    type="checkbox" 
                                    required
                                    class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-0.5"
                                >
                                <label for="terms" class="ml-3 block text-sm text-gray-700">
                                    Jag accepterar 
                                    <a href="{{ route('policy.terms') }}" target="_blank" class="text-purple-600 hover:underline font-semibold">användarvillkoren</a> 
                                    och 
                                    <a href="{{ route('policy.privacy') }}" target="_blank" class="text-purple-600 hover:underline font-semibold">integritetspolicyn</a>
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                                🎉 Skapa konto
                            </button>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="mt-8">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white text-gray-500 font-medium">
                                    Har du redan ett konto?
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition">
                                Logga in här →
                            </a>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600 mb-2">Är du ett företag?</p>
                            <a href="{{ route('company.register') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Registrera ditt företag här
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 sm:px-10">
                    <p class="text-xs text-center text-gray-500">
                        🔒 Dina uppgifter är säkra hos oss och används aldrig för annan marknadsföring.
                    </p>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-6">
                <a href="{{ route('welcome') }}" class="text-white hover:text-white/80 font-medium inline-flex items-center transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Tillbaka till startsidan
                </a>
            </div>
        </div>
    </div>
</body>
</html>
