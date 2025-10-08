@extends('layouts.public')

@section('title', 'Tack för din bokning!')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <div class="bg-white rounded-lg shadow-xl p-12">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Tack för din bokning!
            </h1>
            
            <p class="text-lg text-gray-600 mb-8">
                Din bokning har tagits emot och vår administratör kommer att granska den inom kort. 
                Du kommer att få en bekräftelse via e-post när en lämplig tjänsteleverantör har tilldelats.
            </p>

            @if(isset($booking))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <p class="text-sm text-gray-600 mb-2">Ditt bokningsnummer:</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $booking }}</p>
                </div>
            @endif

            <!-- What Happens Next -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h3 class="font-semibold mb-4">Vad händer nu?</h3>
                <ol class="space-y-3">
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 mt-0.5">1</span>
                        <span class="text-gray-700">Vi granskar din bokning och dina önskemål</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 mt-0.5">2</span>
                        <span class="text-gray-700">Vi matchar dig med en lämplig tjänsteleverantör</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 mt-0.5">3</span>
                        <span class="text-gray-700">Tjänsteleverantören accepterar och kontaktar dig</span>
                    </li>
                    <li class="flex items-start">
                        <span class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3 mt-0.5">4</span>
                        <span class="text-gray-700">Arbetet utförs professionellt</span>
                    </li>
                </ol>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    Tillbaka till startsidan
                </a>
                @auth
                    @if(auth()->user()->isUser())
                        <a href="{{ route('user.bookings.index') }}" class="btn btn-primary">
                            Se mina bokningar
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Contact Info -->
            <div class="mt-8 pt-8 border-t">
                <p class="text-sm text-gray-600">
                    Har du frågor? Kontakta oss på 
                    <a href="mailto:support@bitratjanster.se" class="text-blue-600 hover:underline">support@bitratjanster.se</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

