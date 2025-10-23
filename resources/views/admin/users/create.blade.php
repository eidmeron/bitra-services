@extends('layouts.admin')

@section('title', 'Skapa Ny Användare')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium mb-2 inline-block">
        ← Tillbaka till användare
    </a>
    <h1 class="text-3xl font-bold text-gray-900">➕ Skapa Ny Användare</h1>
    <p class="text-gray-600 mt-1">Lägg till en ny användare i systemet</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
            @csrf

            <div class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        📧 Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        placeholder="anvandare@exempel.se"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        👤 Namn
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        placeholder="För- och efternamn"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        📱 Telefon
                    </label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        placeholder="+46 70 123 45 67"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-gray-200">

                <!-- Password Section -->
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">🔐 Lösenord</h3>
                    <p class="text-sm text-gray-600 mb-4">Ange ett säkert lösenord för användaren</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                🔑 Lösenord <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                required
                                placeholder="Minst 8 tecken"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                🔑 Bekräfta Lösenord <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                required
                                placeholder="Ange lösenordet igen"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center space-x-4 pt-6">
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-medium shadow-lg">
                        ➕ Skapa Användare
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        ❌ Avbryt
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <!-- Info Card -->
        <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                <span class="text-xl mr-2">ℹ️</span>
                Information
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Email är obligatoriskt och måste vara unikt</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Lösenord måste vara minst 8 tecken långt</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Användaren kommer att kunna logga in direkt efter skapandet</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Namn och telefon är valfritt men rekommenderas</span>
                </li>
            </ul>
        </div>

        <!-- Tips Card -->
        <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                <span class="text-xl mr-2">💡</span>
                Tips
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span>Använd en stark lösenordskombination</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span>Dubbelkolla att emailadressen är korrekt</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span>Informera användaren om deras inloggningsuppgifter</span>
                </li>
            </ul>
        </div>

        <!-- Quick Stats -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                <span class="text-xl mr-2">📊</span>
                Statistik
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Totala användare</span>
                    <span class="text-2xl font-bold text-purple-600">{{ \App\Models\User::where('type', 'user')->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Nya denna månad</span>
                    <span class="text-2xl font-bold text-pink-600">{{ \App\Models\User::where('type', 'user')->whereMonth('created_at', now()->month)->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

