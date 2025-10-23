@extends('layouts.public')
@section('title', $company->company_name)
@section('meta_description', $company->description)
@section('content')
<x-toast />

<div class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="flex-1">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $company->company_name }}</h1>
                    <p class="text-xl text-blue-100 mb-6">{{ $company->description }}</p>
                    
                    @if($company->reviews_avg_rating)
                        <div class="flex items-center bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg inline-flex">
                            <svg class="w-6 h-6 text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="font-bold text-2xl">{{ number_format($company->reviews_avg_rating, 1) }}</span>
                            <span class="text-blue-100 ml-2">({{ $company->reviews_count }} recensioner)</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Company Info -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Om {{ $company->company_name }}</h2>
                    <div class="prose prose-lg max-w-none text-gray-700">
                        <p>{{ $company->description }}</p>
                    </div>
                </div>

                <!-- Services -->
                @if($company->services && $company->services->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Tjänster vi erbjuder
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($company->services as $service)
                                <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-semibold text-gray-900">{{ $service->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Coverage Area -->
                @if($company->cities && $company->cities->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Vi finns i
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($company->cities as $city)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">{{ $city->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Reviews Section -->
                @if($company->reviews && $company->reviews->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-xl p-8" id="reviews">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Kundrecensioner
                            </h2>
                            @if($company->reviews_avg_rating)
                                <div class="flex items-center bg-yellow-50 px-4 py-2 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <div>
                                        <span class="font-bold text-2xl text-gray-900">{{ number_format($company->reviews_avg_rating, 1) }}</span>
                                        <span class="text-sm text-gray-600 ml-1">av 5</span>
                                        <p class="text-xs text-gray-500">{{ $company->reviews_count }} {{ $company->reviews_count === 1 ? 'recension' : 'recensioner' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="space-y-6">
                            @foreach($company->reviews->take(10) as $review)
                                <div class="border-b border-gray-200 pb-6 last:border-0 hover:bg-gray-50 p-4 rounded-lg transition">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-3 flex-shrink-0">
                                                @php
                                                    $userName = $review->booking->user->name ?? 'Anonym';
                                                @endphp
                                                {{ strtoupper(substr($userName, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $userName }}</p>
                                                <p class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-lg flex-shrink-0">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                            <span class="font-bold text-gray-900 ml-2">{{ $review->rating }}/5</span>
                                        </div>
                                    </div>
                                    @if($review->review_text)
                                        <p class="text-gray-700 italic">"{{ $review->review_text }}"</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        @if($company->reviews->count() > 10)
                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-500">Visar 10 av {{ $company->reviews_count }} recensioner</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8" id="contact-form">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Kontakta {{ $company->company_name }}
                    </h2>
                    <form action="{{ route('company.message.send', $company) }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Ditt namn *
                                </label>
                                <input type="text" id="name" name="name" required
                                       value="{{ auth()->user()->name ?? old('name') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Din e-post *
                                </label>
                                <input type="email" id="email" name="email" required
                                       value="{{ auth()->user()->email ?? old('email') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                Telefonnummer
                            </label>
                            <input type="tel" id="phone" name="phone"
                                   value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                Ämne *
                            </label>
                            <input type="text" id="subject" name="subject" required
                                   value="{{ old('subject') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('subject')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                Meddelande *
                            </label>
                            <textarea id="message" name="message" rows="6" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                            📧 Skicka meddelande
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24 space-y-6">
                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📞 Kontaktinformation</h3>
                        <div class="space-y-3">
                            @if($company->email)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <a href="mailto:{{ $company->email }}" class="text-gray-700 hover:text-blue-600 break-all">
                                        {{ $company->email }}
                                    </a>
                                </div>
                            @endif
                            @if($company->phone)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <a href="tel:{{ $company->phone }}" class="text-gray-700 hover:text-green-600">
                                        {{ $company->phone }}
                                    </a>
                                </div>
                            @endif
                            @if($company->website)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <a href="{{ $company->website }}" target="_blank" rel="noopener" class="text-gray-700 hover:text-purple-600 break-all">
                                        {{ str_replace(['http://', 'https://'], '', $company->website) }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="pt-6 border-t">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Statistik</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tjänster:</span>
                                <span class="font-bold text-gray-900">{{ $company->services->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Städer:</span>
                                <span class="font-bold text-gray-900">{{ $company->cities->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Recensioner:</span>
                                <span class="font-bold text-gray-900">{{ $company->reviews_count }}</span>
                            </div>
                            @if($company->reviews_avg_rating)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Betyg:</span>
                                    <span class="font-bold text-yellow-600">{{ number_format($company->reviews_avg_rating, 1) }} ⭐</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="pt-6 border-t">
                        <a href="#contact-form" class="block w-full text-center px-6 py-4 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                            💬 Kontakta oss
                        </a>
                    </div>

                    <!-- Trust Badges -->
                    <div class="pt-6 border-t">
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Verifierat företag
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                ROT/RUT-avdrag
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Kundservice
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
