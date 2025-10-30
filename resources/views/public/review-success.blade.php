@extends('layouts.public')

@section('title', 'Tack för din recension')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Success Icon -->
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
            <span class="text-5xl">✅</span>
        </div>

        <!-- Success Message -->
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Tack för dina recensioner!
        </h1>
        
        <p class="text-xl text-gray-600 mb-8">
            Dina recensioner har skickats in och kommer att granskas innan de publiceras. 
            Vi uppskattar din feedback och hjälper oss att förbättra vår service.
        </p>

        <!-- Booking Info -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Bokningsinformation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
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

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('welcome') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <span class="mr-2">🏠</span>
                Tillbaka till startsidan
            </a>
            
            <a href="{{ route('public.services') }}" 
               class="inline-flex items-center px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:border-blue-600 hover:text-blue-600 transition-all">
                <span class="mr-2">🛠️</span>
                Boka fler tjänster
            </a>
        </div>

        <!-- Additional Info -->
        <div class="mt-12 text-center">
            <p class="text-gray-500 mb-4">
                Har du frågor eller behöver hjälp?
            </p>
            <a href="{{ route('contact') }}" 
               class="text-blue-600 hover:text-blue-800 font-semibold">
                Kontakta vår kundsupport
            </a>
        </div>
    </div>
</div>
@endsection
