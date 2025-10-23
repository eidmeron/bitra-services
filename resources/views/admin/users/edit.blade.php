@extends('layouts.admin')

@section('title', 'Redigera Användare: ' . $user->email)

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800 font-medium mb-2 inline-block">
        ← Tillbaka till användare
    </a>
    <h1 class="text-3xl font-bold text-gray-900">✏️ Redigera Användare</h1>
    <p class="text-gray-600 mt-1">Uppdatera användarinformation</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
            @csrf
            @method('PUT')

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
                        value="{{ old('email', $user->email) }}" 
                        required
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
                        value="{{ old('name', $user->name) }}"
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
                        value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-gray-200">

                <!-- Password Section -->
                <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">🔐 Ändra Lösenord</h3>
                    <p class="text-sm text-gray-600 mb-4">Lämna tomt om du inte vill ändra lösenordet</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                🔑 Nytt Lösenord
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            >
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                🔑 Bekräfta Lösenord
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center space-x-4 pt-6">
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-medium shadow-lg">
                        💾 Uppdatera Användare
                    </button>
                    <a href="{{ route('admin.users.show', $user) }}" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
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
                    <span>Lösenord måste vara minst 8 tecken</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">•</span>
                    <span>Lämna lösenordsfälten tomma för att behålla nuvarande lösenord</span>
                </li>
            </ul>
        </div>

        <!-- User Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">📊</span>
                Statistik
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Totala bokningar</span>
                    <span class="text-lg font-bold text-blue-600">{{ $user->bookings()->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Medlem sedan</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('Y-m-d') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Registrerad</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

