@extends('layouts.public')

@section('title', 'Kontakta Oss')
@section('meta_description', 'Har du frågor? Kontakta oss så hjälper vi dig. Vi svarar inom 24 timmar.')

@section('content')
<x-toast />

<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">📧 Kontakta Oss</h1>
            <p class="text-xl text-blue-100 mb-6">Vi finns här för att hjälpa dig! Skicka ett meddelande så återkommer vi inom 24 timmar.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-2xl p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Skicka Ett Meddelande</h2>
                    
                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ditt Namn *
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    required
                                    value="{{ auth()->user()->name ?? old('name') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="John Doe">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Din E-post *
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    required
                                    value="{{ auth()->user()->email ?? old('email') }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="john@example.com">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                Telefonnummer <span class="text-gray-500 font-normal">(valfritt)</span>
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone"
                                value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="+46 70 123 45 67">
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                Ämne *
                            </label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                required
                                value="{{ old('subject') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Vad gäller ditt meddelande?">
                            @error('subject')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                Meddelande *
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="8" 
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
                                placeholder="Berätta hur vi kan hjälpa dig...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-2">Max 2000 tecken</p>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit"
                                class="w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-2xl transform hover:scale-105 text-lg">
                                📧 Skicka Meddelande
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Info Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Contact -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">📞 Snabbkontakt</h3>
                    <div class="space-y-4">
                        @if(setting('contact_email'))
                            <a href="mailto:{{ setting('contact_email') }}" class="flex items-start p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition group">
                                <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">E-post</p>
                                    <p class="font-semibold text-gray-900 group-hover:text-blue-600">{{ setting('contact_email') }}</p>
                                </div>
                            </a>
                        @endif

                        @if(setting('contact_phone'))
                            <a href="tel:{{ setting('contact_phone') }}" class="flex items-start p-4 bg-green-50 rounded-lg hover:bg-green-100 transition group">
                                <svg class="w-6 h-6 text-green-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Telefon</p>
                                    <p class="font-semibold text-gray-900 group-hover:text-green-600">{{ setting('contact_phone') }}</p>
                                </div>
                            </a>
                        @endif

                        @php $contact = contact_info(); @endphp
                        @if($contact['address'])
                            <div class="flex items-start p-4 bg-purple-50 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Adress</p>
                                    <p class="font-semibold text-gray-900">{{ $contact['address'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Opening Hours -->
                <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                    <h3 class="text-xl font-bold mb-4">⏰ Öppettider</h3>
                    @if($contact['hours'])
                        <p class="text-blue-100">{{ $contact['hours'] }}</p>
                    @else
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Måndag - Fredag:</span>
                                <span class="font-semibold">09:00 - 17:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Lördag:</span>
                                <span class="font-semibold">10:00 - 14:00</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Söndag:</span>
                                <span class="font-semibold">Stängt</span>
                            </div>
                        </div>
                    @endif
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <p class="text-sm text-blue-100">
                            <span class="font-semibold">⚡ Snabb respons:</span> Vi svarar inom 24 timmar!
                        </p>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">❓ Vanliga Frågor</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="font-semibold text-gray-900">Hur snabbt svarar ni?</p>
                            <p class="text-gray-600">Vi svarar inom 24 timmar på alla meddelanden.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Kan jag boka direkt?</p>
                            <p class="text-gray-600">Ja! Använd vårt bokningsformulär på startsidan.</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Behöver jag ett konto?</p>
                            <p class="text-gray-600">Nej, du kan kontakta oss som gäst.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
