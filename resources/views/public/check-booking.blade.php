@extends('layouts.public')

@section('title', 'Kolla din bokning')

@section('content')
<!-- Toast Notification Component -->
<x-toast />

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 border-2 border-blue-200">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mb-4">
                    <span class="text-4xl">🔍</span>
                </div>
                <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Kolla din bokning</h1>
                <p class="text-gray-600">Ange ditt bokningsnummer och e-postadress</p>
            </div>

            <!-- Errors -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-red-400 text-xl">⚠️</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('public.booking.check') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Booking Number -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="inline-flex items-center">
                            <span class="mr-2">📋</span>
                            Bokningsnummer <span class="text-red-500 ml-1">*</span>
                        </span>
                    </label>
                    <input 
                        type="text" 
                        name="booking_number" 
                        value="{{ old('booking_number') }}"
                        placeholder="t.ex. BK20251010ABCDEF"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-lg font-mono uppercase"
                        required
                        maxlength="20"
                    >
                    <p class="text-xs text-gray-500 mt-2">Du hittar bokningsnumret i bekräftelsemailet</p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="inline-flex items-center">
                            <span class="mr-2">📧</span>
                            E-postadress <span class="text-red-500 ml-1">*</span>
                        </span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="din@email.se"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-2">Den e-postadress du använde vid bokningen</p>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg text-lg font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center"
                >
                    <span class="mr-2">🔍</span>
                    Kolla bokning
                </button>
            </form>

            <!-- Info Box -->
            <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <p class="text-sm text-blue-800 font-medium mb-2">💡 Tips:</p>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li>• Bokningsnumret skickades till din e-post efter bokningen</li>
                    <li>• Bokningsnumret börjar med "BK" följt av datum och kod</li>
                    <li>• Kontrollera även skräppost om du inte hittar mailet</li>
                </ul>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-6">
                <a href="{{ route('welcome') }}" class="text-blue-600 hover:underline text-sm">
                    ← Tillbaka till startsidan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

