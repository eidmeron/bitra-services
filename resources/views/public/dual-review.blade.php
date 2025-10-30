@extends('layouts.public')

@section('title', 'Recensera din bokning')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                🎉 Tack för din bokning!
            </h1>
            <p class="text-xl text-gray-600">
                Vi hoppas att du är nöjd med tjänsten. Hjälp oss att förbättra genom att lämna en recension.
            </p>
        </div>

        <!-- Booking Info -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Bokningsinformation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Bokningsnummer</p>
                    <p class="text-lg font-semibold">{{ $booking->booking_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tjänst</p>
                    <p class="text-lg font-semibold">{{ $booking->service->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Företag</p>
                    <p class="text-lg font-semibold">{{ $booking->company->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Datum</p>
                    <p class="text-lg font-semibold">{{ $booking->scheduled_date->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Dual Review Form -->
        <form method="POST" action="{{ route('public.dual-review.submit', $booking) }}" class="space-y-8">
            @csrf

            <!-- Company Review Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-2xl">🏢</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Recensera företaget</h3>
                        <p class="text-gray-600">Hur var din upplevelse med {{ $booking->company->name }}?</p>
                    </div>
                </div>

                <!-- Company Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Betyg för företaget</label>
                    <div class="flex space-x-2" x-data="{ rating: {{ old('company_rating', 0) }} }">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    @click="rating = {{ $i }}"
                                    :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
                                    class="text-4xl hover:text-yellow-400 transition-colors">
                                ★
                            </button>
                        @endfor
                        <input type="hidden" name="company_rating" x-model="rating" required>
                    </div>
                    @error('company_rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company Review Text -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Berätta mer om din upplevelse</label>
                    <textarea name="company_review_text" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Berätta om vad du tyckte om tjänsten, personalen, kvaliteten...">{{ old('company_review_text') }}</textarea>
                    @error('company_review_text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Bitra Review Section -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-2xl">⭐</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Recensera Bitra</h3>
                        <p class="text-gray-600">Hur var din upplevelse med vår plattform och bokningsprocess?</p>
                    </div>
                </div>

                <!-- Bitra Rating -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Betyg för Bitra</label>
                    <div class="flex space-x-2" x-data="{ rating: {{ old('bitra_rating', 0) }} }">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" 
                                    @click="rating = {{ $i }}"
                                    :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300'"
                                    class="text-4xl hover:text-yellow-400 transition-colors">
                                ★
                            </button>
                        @endfor
                        <input type="hidden" name="bitra_rating" x-model="rating" required>
                    </div>
                    @error('bitra_rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bitra Review Text -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Berätta om din upplevelse med Bitra</label>
                    <textarea name="bitra_review_text" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Berätta om bokningsprocessen, kundsupport, användarvänlighet...">{{ old('bitra_review_text') }}</textarea>
                    @error('bitra_review_text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" 
                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span class="mr-2">📝</span>
                    Skicka recensioner
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
