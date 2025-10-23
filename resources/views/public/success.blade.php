@extends('layouts.public')

@section('title', 'Bokning mottagen!')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .delay-1 { animation-delay: 0.2s; opacity: 0; }
    .delay-2 { animation-delay: 0.4s; opacity: 0; }
    .delay-3 { animation-delay: 0.6s; opacity: 0; }
    .delay-4 { animation-delay: 0.8s; opacity: 0; }
</style>
@endpush

@section('content')
<!-- Toast Notification Component -->
<x-toast />

<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Success Message -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 lg:p-12 animate-fade-in-up delay-1">
            <div class="text-center mb-8">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    🎉 Bokning mottagen!
                </h1>
                <p class="text-xl text-gray-600">
                    Tack för din bokning! Vi har tagit emot din förfrågan.
                </p>
            </div>

            <!-- Booking Number -->
            <div class="bg-gradient-to-r from-blue-100 to-purple-100 border-2 border-blue-300 rounded-xl p-6 mb-8 animate-fade-in-up delay-2">
                <div class="flex items-center justify-center">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-1">Ditt bokningsnummer</p>
                        <p class="text-3xl font-bold text-gray-900 font-mono">#{{ $booking->booking_number }}</p>
                        <p class="text-xs text-gray-500 mt-2">Spara detta nummer för framtida referens</p>
                        @guest
                            <a href="{{ route('public.booking.check.form') }}" class="inline-block mt-3 text-sm text-blue-600 hover:underline font-medium">
                                🔍 Kolla din bokning senare
                            </a>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- What Happens Next -->
            <div class="animate-fade-in-up delay-3">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                    📋 Vad händer nu?
                </h2>

                <div class="space-y-4">
                    <!-- Step 1 -->
                    <div class="flex items-start bg-blue-50 rounded-lg p-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                            1
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Vi granskar din bokning</h3>
                            <p class="text-sm text-gray-600">
                                Vår admin kommer att granska din bokning och tilldela den till en lämplig tjänsteleverantör inom kort.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-start bg-purple-50 rounded-lg p-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                            2
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Företag kontaktar dig</h3>
                            <p class="text-sm text-gray-600">
                                När vi har tilldelat ett företag kommer de att kontakta dig för att bekräfta detaljer och boka in en tid.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-start bg-green-50 rounded-lg p-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold mr-4">
                            3
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Tjänsten utförs</h3>
                            <p class="text-sm text-gray-600">
                                Företaget kommer och utför tjänsten enligt överenskommelse. Efter det kan du lämna ett omdöme!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation Email Notice -->
            <div class="mt-8 bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4 animate-fade-in-up delay-4">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">📧</span>
                    <div>
                        <h3 class="font-bold text-gray-900 mb-1">Bekräftelsemail skickat!</h3>
                        <p class="text-sm text-gray-600">
                            Vi har skickat en bekräftelse till <strong>{{ $booking->customer_email }}</strong>. 
                            Kolla din inkorg (och skräppost) för mer information.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Account Options for Guests -->
            @guest
                <div class="mt-8 bg-gradient-to-r from-purple-50 to-blue-50 border-2 border-purple-200 rounded-xl p-6 animate-fade-in-up delay-4">
                    @if(!$emailExists)
                        <!-- Quick Account Creation Form -->
                        <div x-data="{ showForm: false }">
                            <div x-show="!showForm">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                                    🎁 Skapa ett konto nu - Snabbt & Enkelt!
                                </h2>
                                <p class="text-gray-700 mb-6 text-center">
                                    Vi har redan din information. Sätt bara ett lösenord så är du klar!
                                </p>
                                
                                <div class="bg-white rounded-xl p-6 mb-4 shadow-md">
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">📧 E-post:</span>
                                            <span class="font-semibold">{{ $booking->customer_email }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">👤 Namn:</span>
                                            <span class="font-semibold">{{ $booking->customer_name }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-600">📱 Telefon:</span>
                                            <span class="font-semibold">{{ $booking->customer_phone }}</span>
                                        </div>
                                    </div>
                                </div>

                                <button 
                                    @click="showForm = true"
                                    class="w-full px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold text-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                                >
                                    <span class="text-2xl mr-2">✨</span>
                                    Skapa Mitt Konto Nu!
                                </button>

                                <p class="text-xs text-gray-500 text-center mt-3">
                                    🔒 Säkert och tar bara 10 sekunder
                                </p>
                            </div>

                            <!-- Password Form -->
                            <div x-show="showForm" x-cloak style="display: none;">
                                <button 
                                    @click="showForm = false"
                                    class="mb-4 text-blue-600 hover:underline text-sm"
                                >
                                    ← Tillbaka
                                </button>

                                <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">
                                    🔐 Sätt ditt lösenord
                                </h3>

                                <form action="{{ route('public.booking.create-account', $booking->booking_number) }}" method="POST" class="space-y-4">
                                    @csrf

                                    <!-- Display existing info (read-only) -->
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                                        <p><strong>E-post:</strong> {{ $booking->customer_email }}</p>
                                        <p><strong>Namn:</strong> {{ $booking->customer_name }}</p>
                                    </div>

                                    <!-- Password -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Lösenord <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="password" 
                                            name="password" 
                                            required
                                            minlength="8"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="Minst 8 tecken"
                                        >
                                        @error('password')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Password Confirmation -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Bekräfta lösenord <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="password" 
                                            name="password_confirmation" 
                                            required
                                            minlength="8"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            placeholder="Bekräfta ditt lösenord"
                                        >
                                    </div>

                                    <!-- Submit Button -->
                                    <button 
                                        type="submit"
                                        class="w-full px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold text-lg hover:shadow-xl transition-all"
                                    >
                                        ✅ Skapa Konto & Logga In
                                    </button>

                                    <p class="text-xs text-gray-500 text-center">
                                        Genom att skapa ett konto accepterar du våra <a href="#" class="text-blue-600 hover:underline">användarvillkor</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Email already exists -->
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center">
                            🔐 Välkommen tillbaka!
                        </h2>
                        <p class="text-gray-700 mb-6 text-center">
                            Du har redan ett konto med e-postadressen <strong>{{ $booking->customer_email }}</strong>
                        </p>
                        
                        <a 
                            href="{{ route('login') }}" 
                            class="block px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl font-bold text-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 text-center"
                        >
                            <span class="text-2xl mr-2">🔐</span>
                            Logga in för att se din bokning
                        </a>
                    @endif

                    <!-- Guest Check Booking Link -->
                    <div class="mt-6 text-center pt-6 border-t border-gray-300">
                        <p class="text-gray-600 mb-3">Eller kolla din bokning utan konto:</p>
                        <a 
                            href="{{ route('public.booking.check.form') }}" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-blue-500 text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-300"
                        >
                            <span class="mr-2">🔍</span>
                            Kolla Bokning
                        </a>
                        <p class="text-sm text-gray-500 mt-2">Använd ditt bokningsnummer: <strong class="text-blue-600">{{ $booking->booking_number }}</strong></p>
                    </div>
                </div>
            @else
                <div class="mt-8 text-center">
                    <a 
                        href="{{ route('user.dashboard') }}" 
                        class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-bold text-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300"
                    >
                        <span class="mr-2">👤</span>
                        Se din bokning
                    </a>
                </div>
            @endauth

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up delay-4">
                @if($booking->form)
                    <a 
                        href="{{ route('public.form', $booking->form->public_token) }}" 
                        class="inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white rounded-xl font-bold text-lg hover:bg-green-700 hover:shadow-lg transition-all duration-300"
                    >
                        <span class="mr-2">➕</span>
                        Skapa ny bokning
                    </a>
                @endif

                <a 
                    href="{{ route('welcome') }}" 
                    class="inline-flex items-center justify-center px-8 py-4 bg-gray-200 text-gray-700 rounded-xl font-bold text-lg hover:bg-gray-300 transition-all duration-300"
                >
                    <span class="mr-2">🏠</span>
                    Tillbaka till startsidan
                </a>
            </div>
            
            <!-- Auto-Redirect Notice -->
            @if($booking->form && $booking->form->redirect_after_submit && $booking->form->redirect_url)
                <div id="redirect-notice" class="mt-6 bg-blue-50 border-2 border-blue-300 rounded-lg p-4 text-center">
                    <p class="text-gray-700">
                        <span class="animate-pulse inline-block">⏳</span>
                        Du kommer att omdirigeras om <span id="countdown" class="font-bold text-blue-600">3</span> sekunder...
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        Eller <a href="{{ $booking->form->redirect_url }}" class="text-blue-600 hover:underline font-semibold">klicka här för att fortsätta nu</a>
                    </p>
                </div>
            @endif

            <!-- Support Notice -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>
                    Behöver du hjälp? Kontakta oss på 
                    <a href="mailto:support@bitratjanster.se" class="text-blue-600 hover:underline font-medium">
                        support@bitratjanster.se
                    </a>
                </p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center text-sm text-gray-500 animate-fade-in-up delay-4">
            <p>Du kommer att få uppdateringar via email när statusen på din bokning ändras.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Lottie Animation Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>

<script>
    // Load Lottie success animation
    const animation = lottie.loadAnimation({
        container: document.getElementById('lottie-success'),
        renderer: 'svg',
        loop: false,
        autoplay: true,
        path: 'https://lottie.host/4c8b2467-a07a-4fb7-90f7-4c9c1c20e75b/hZfnAOVZiH.json' // Success checkmark animation
    });
    
    // Auto-redirect functionality
    @if($booking->form && $booking->form->redirect_after_submit && $booking->form->redirect_url)
        let countdown = 3;
        const redirectUrl = "{{ $booking->form->redirect_url }}";
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = redirectUrl;
            }
        }, 1000);
        
        console.log('Auto-redirect enabled. Redirecting to:', redirectUrl, 'in 3 seconds');
    @endif
</script>
@endpush
