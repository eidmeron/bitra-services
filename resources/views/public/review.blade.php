@extends('layouts.public')

@section('title', 'Lämna en recension')

@push('styles')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    
    .star-rating input {
        display: none;
    }
    
    .star-rating label {
        font-size: 2rem;
        color: #d1d5db;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #fbbf24;
    }
    
    .star-rating input:checked ~ label {
        color: #f59e0b;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                ⭐ Lämna en recension
            </h1>
            <p class="text-xl text-gray-600">
                Hjälp oss att förbättra våra tjänster genom att dela din upplevelse
            </p>
        </div>

        <!-- Booking Info -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Bokningsinformation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Bokningsnummer</p>
                    <p class="font-semibold text-gray-900">#{{ $booking->booking_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tjänst</p>
                    <p class="font-semibold text-gray-900">{{ $booking->service->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Företag</p>
                    <p class="font-semibold text-gray-900">{{ $booking->company->company_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Datum</p>
                    <p class="font-semibold text-gray-900">{{ $booking->booking_date }}</p>
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('public.booking.review.submit.token', $booking->public_token) }}" class="space-y-6">
                @csrf
                
                <!-- Rating -->
                <div>
                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                        Hur nöjd är du med tjänsten? *
                    </label>
                    <div class="star-rating" x-data="{ rating: 0 }">
                        <input type="radio" id="star5" name="rating" value="5" x-model="rating">
                        <label for="star5">★</label>
                        <input type="radio" id="star4" name="rating" value="4" x-model="rating">
                        <label for="star4">★</label>
                        <input type="radio" id="star3" name="rating" value="3" x-model="rating">
                        <label for="star3">★</label>
                        <input type="radio" id="star2" name="rating" value="2" x-model="rating">
                        <label for="star2">★</label>
                        <input type="radio" id="star1" name="rating" value="1" x-model="rating">
                        <label for="star1">★</label>
                    </div>
                    <div x-show="rating > 0" class="mt-2 text-sm text-gray-600">
                        <span x-text="rating === 1 ? 'Mycket missnöjd' : 
                                     rating === 2 ? 'Missnöjd' : 
                                     rating === 3 ? 'Neutral' : 
                                     rating === 4 ? 'Nöjd' : 
                                     rating === 5 ? 'Mycket nöjd' : ''"></span>
                    </div>
                    @error('rating')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Review Text -->
                <div>
                    <label for="review_text" class="block text-lg font-semibold text-gray-900 mb-2">
                        Berätta mer om din upplevelse (valfritt)
                    </label>
                    <textarea 
                        id="review_text" 
                        name="review_text" 
                        rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        placeholder="Dela din upplevelse med andra kunder..."
                        maxlength="1000"
                    >{{ old('review_text') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Max 1000 tecken</p>
                    @error('review_text')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-8 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                        Skicka recension
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
@endsection
