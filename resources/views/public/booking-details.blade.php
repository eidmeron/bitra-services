@extends('layouts.public')

@section('title', 'Bokningsdetaljer - ' . $booking->booking_number)

@section('content')
<!-- Toast Notification Component -->
<x-toast />

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full mb-4">
                <span class="text-4xl">📋</span>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Din bokning</h1>
            <p class="text-xl text-gray-600 font-mono font-bold">{{ $booking->booking_number }}</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Booking Status -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">📊</span>
                    Status
                </h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Aktuell status:</span>
                        <span>{!! bookingStatusBadge($booking->status) !!}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Skapad:</span>
                        <span class="font-semibold">{{ $booking->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($booking->preferred_date)
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Önskat datum:</span>
                        <span class="font-semibold">{{ $booking->preferred_date }}</span>
                    </div>
                    @endif
                    @if($booking->preferred_time)
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Önskad tid:</span>
                        <span class="font-semibold">{{ $booking->preferred_time }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Service Info -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">🛠️</span>
                    Tjänst
                </h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Tjänst:</span>
                        <span class="font-semibold">{{ $booking->service->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Stad:</span>
                        <span class="font-semibold">{{ $booking->city->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600 font-medium">Bokningstyp:</span>
                        <span class="font-semibold">
                            @if($booking->booking_type === 'one_time')
                                📅 Engångsbokning
                            @else
                                🔄 {{ getSubscriptionFrequencyLabel($booking->subscription_frequency) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Price Details -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl shadow-lg p-6 border-2 border-green-300">
                <h2 class="text-2xl font-bold text-green-900 mb-4 flex items-center">
                    <span class="mr-2">💰</span>
                    Pris
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-green-200">
                        <span class="text-green-800 font-medium">Grundpris:</span>
                        <span class="font-semibold">{{ number_format($booking->base_price, 2, ',', ' ') }} kr</span>
                    </div>
                    @if($booking->variable_additions > 0)
                    <div class="flex justify-between items-center py-2 border-b border-green-200">
                        <span class="text-green-800 font-medium">Tillägg:</span>
                        <span class="font-semibold text-blue-600">+{{ number_format($booking->variable_additions, 2, ',', ' ') }} kr</span>
                    </div>
                    @endif
                    @if($booking->subscription_multiplier && $booking->subscription_multiplier != 1.00)
                    <div class="flex justify-between items-center py-2 border-b border-green-200">
                        <span class="text-green-800 font-medium">Prenumeration:</span>
                        <span class="font-semibold text-purple-600">×{{ $booking->subscription_multiplier }}</span>
                    </div>
                    @endif
                    @if($booking->discount_amount > 0)
                    <div class="flex justify-between items-center py-2 border-b border-green-200">
                        <span class="text-green-800 font-medium">🎁 Rabatt:</span>
                        <span class="font-semibold text-green-600">-{{ number_format($booking->discount_amount, 2, ',', ' ') }} kr</span>
                    </div>
                    @endif
                    @if($booking->rot_deduction > 0)
                    <div class="flex justify-between items-center py-2 border-b border-green-200">
                        <span class="text-green-800 font-medium">💚 ROT-avdrag:</span>
                        <span class="font-semibold text-green-600">-{{ number_format($booking->rot_deduction, 2, ',', ' ') }} kr</span>
                    </div>
                    @endif
                    
                    <!-- Tax Section -->
                    @if($booking->tax_amount > 0)
                    <div class="flex justify-between items-center py-2 border-b border-green-200">
                        <span class="text-green-800 font-medium">📊 Moms ({{ number_format($booking->tax_rate * 100, 0, ',', ' ') }}%):</span>
                        <span class="font-semibold text-orange-600">+{{ number_format($booking->tax_amount, 2, ',', ' ') }} kr</span>
                    </div>
                    @endif
                    
                    <!-- Extra Fees Section -->
                    @if($booking->total_extra_fees > 0)
                    <div class="flex justify-between items-center py-2 border-b border-green-200">
                        <span class="text-green-800 font-medium">💰 Extra avgifter:</span>
                        <span class="font-semibold text-blue-600">+{{ number_format($booking->total_extra_fees, 2, ',', ' ') }} kr</span>
                    </div>
                    @endif
                    
                    <div class="pt-3 mt-3 border-t-2 border-green-400">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-green-900">Totalt pris (inkl. moms):</span>
                            <span class="text-2xl font-bold text-green-900">{{ number_format($booking->total_with_extra_fees, 2, ',', ' ') }} kr</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Info (if assigned) -->
            @if($booking->company)
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">🏢</span>
                    Tilldelat företag
                </h2>
                <div class="space-y-4">
                    <div class="py-3 border-b">
                        <span class="text-gray-600 font-medium block mb-1">Företagsnamn:</span>
                        <span class="text-lg font-bold text-gray-900">{{ $booking->company->company_name }}</span>
                    </div>
                    @if($booking->company->contact_phone)
                    <div class="py-3 border-b">
                        <span class="text-gray-600 font-medium block mb-1">📞 Telefon:</span>
                        <a href="tel:{{ $booking->company->contact_phone }}" class="text-blue-600 hover:underline font-semibold">
                            {{ $booking->company->contact_phone }}
                        </a>
                    </div>
                    @endif
                    @if($booking->company->contact_email)
                    <div class="py-3">
                        <span class="text-gray-600 font-medium block mb-1">📧 E-post:</span>
                        <a href="mailto:{{ $booking->company->contact_email }}" class="text-blue-600 hover:underline font-semibold">
                            {{ $booking->company->contact_email }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-yellow-50 rounded-xl shadow-lg p-6 border-2 border-yellow-300">
                <h2 class="text-2xl font-bold text-yellow-900 mb-4 flex items-center">
                    <span class="mr-2">⏳</span>
                    Tilldelning
                </h2>
                <p class="text-yellow-800">
                    Din bokning har ännu inte tilldelats något företag. Vi kommer att tilldela din bokning till en kvalificerad tjänsteleverantör inom kort.
                </p>
            </div>
            @endif

            <!-- Contact Info -->
            <div class="md:col-span-2 bg-white rounded-xl shadow-lg p-6 border-2 border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">👤</span>
                    Dina uppgifter
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="py-3 border-b">
                        <span class="text-gray-600 font-medium block mb-1">Namn:</span>
                        <span class="font-semibold">{{ $booking->customer_name }}</span>
                    </div>
                    <div class="py-3 border-b">
                        <span class="text-gray-600 font-medium block mb-1">E-post:</span>
                        <span class="font-semibold">{{ $booking->customer_email }}</span>
                    </div>
                    <div class="py-3 border-b">
                        <span class="text-gray-600 font-medium block mb-1">Telefon:</span>
                        <span class="font-semibold">{{ $booking->customer_phone }}</span>
                    </div>
                    @if($booking->customer_message)
                    <div class="md:col-span-2 py-3">
                        <span class="text-gray-600 font-medium block mb-1">Meddelande:</span>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $booking->customer_message }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat Section (for active bookings with assigned company) -->
        @if(in_array($booking->status, ['assigned', 'in_progress']) && $booking->company)
            <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden" id="chat">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chatta med {{ $booking->company->company_name }}
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Chat Messages -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4 max-h-96 overflow-y-auto" id="chatMessages">
                        @forelse($booking->chats()->orderBy('created_at', 'asc')->get() as $chat)
                            <div class="mb-4 {{ $chat->sender_type === 'guest' ? 'text-right' : 'text-left' }}">
                                <div class="inline-block max-w-xs lg:max-w-md">
                                    <div class="text-xs text-gray-500 mb-1">
                                        {{ $chat->sender_type === 'guest' ? 'Du' : $booking->company->company_name }}
                                        <span class="ml-1">{{ $chat->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="rounded-lg px-4 py-2 {{ $chat->sender_type === 'guest' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                                        {{ $chat->message }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="text-sm">Inga meddelanden ännu. Starta konversationen!</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Send Message Form -->
                    <form method="POST" action="{{ route('public.booking.chat.send', $booking) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ditt meddelande</label>
                            <textarea 
                                name="message" 
                                rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Skriv ditt meddelande..."
                                required
                            ></textarea>
                        </div>
                        <button 
                            type="submit" 
                            class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Skicka meddelande
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Review Section (for completed bookings without review) -->
        @if($booking->status === 'completed' && !$booking->review)
            <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden" id="review">
                <div class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-b">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Lämna recension
                    </h2>
                </div>
                <div class="p-6">
                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg mb-6">
                        <p class="text-sm text-green-800">
                            <strong>✅ Tjänsten är slutförd!</strong> Dela din upplevelse med andra kunder.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('public.booking.review.submit', $booking) }}">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Betyg *</label>
                            <div class="flex space-x-2 justify-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" required class="hidden peer">
                                        <span class="text-5xl peer-checked:text-yellow-400 text-gray-300 hover:text-yellow-400 transition-colors">★</span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Din recension</label>
                            <textarea 
                                name="review_text" 
                                rows="4" 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" 
                                placeholder="Berätta om din upplevelse..."
                            ></textarea>
                            @error('review_text')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button 
                            type="submit" 
                            class="w-full px-6 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold rounded-lg hover:from-yellow-600 hover:to-orange-600 transition shadow-lg hover:shadow-xl"
                        >
                            ⭐ Skicka recension
                        </button>
                    </form>
                </div>
            </div>
        @elseif($booking->review)
            <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Din recension
                    </h2>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <div class="flex justify-center space-x-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-8 h-8 {{ $i <= $booking->review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                    @if($booking->review->review_text)
                        <p class="text-gray-700 italic text-center mb-4">"{{ $booking->review->review_text }}"</p>
                    @endif
                    <p class="text-xs text-gray-500 text-center">
                        Status: 
                        @if($booking->review->status === 'approved')
                            <span class="text-green-600 font-semibold">✅ Godkänd</span>
                        @elseif($booking->review->status === 'pending')
                            <span class="text-yellow-600 font-semibold">⏳ Väntar på godkännande</span>
                        @else
                            <span class="text-red-600 font-semibold">❌ Avvisad</span>
                        @endif
                    </p>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 text-center space-y-4">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded max-w-2xl mx-auto">
                <p class="text-sm text-blue-800">
                    <strong>💡 Tips:</strong> Spara ditt bokningsnummer ({{ $booking->booking_number }}) för att kunna kolla din bokning senare.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
                <a href="{{ route('public.booking.check.form') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    🔍 Kolla en annan bokning
                </a>
                <a href="{{ route('welcome') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    🏠 Till startsidan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

