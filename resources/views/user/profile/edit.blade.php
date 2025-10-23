@extends('layouts.user')

@section('title', 'Min Profil')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">👤 Min Profil</h1>
            <p class="text-blue-100 text-lg">Hantera din personliga information och inställningar</p>
        </div>
        <div class="hidden md:block">
            @if(auth()->user()->photo)
                <img src="{{ Storage::url(auth()->user()->photo) }}" alt="Profil" class="w-20 h-20 rounded-full border-4 border-white/30 object-cover shadow-lg">
            @else
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center text-5xl border-4 border-white/30">
                    👤
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Success Message -->
@if(session('success'))
    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md animate-fade-in">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <span class="text-green-500 text-2xl">✅</span>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<!-- Error Messages -->
@if($errors->any())
    <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="text-red-500 text-2xl">⚠️</span>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-red-800 mb-2">Det uppstod några fel:</h3>
                <ul class="mt-2 text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="text-2xl mr-3">📝</span>
                        Personlig Information
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Fullständigt Namn <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-lg">👤</span>
                            </div>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ old('name', auth()->user()->name) }}"
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-300"
                                placeholder="Ditt fullständiga namn"
                                required
                            >
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <span class="mr-1">⚠️</span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            E-postadress <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-lg">📧</span>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email', auth()->user()->email) }}"
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-300"
                                placeholder="din@email.com"
                                required
                            >
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <span class="mr-1">⚠️</span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Telefonnummer
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-lg">📱</span>
                            </div>
                            <input 
                                type="tel" 
                                name="phone" 
                                value="{{ old('phone', auth()->user()->phone) }}"
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-300"
                                placeholder="+46 70 123 45 67"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-1">Används för att kontakta dig angående dina bokningar</p>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <span class="mr-1">⚠️</span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Profile Photo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Profilbild
                        </label>
                        @if(auth()->user()->photo)
                            <div class="mb-4 flex items-center space-x-4">
                                <img src="{{ Storage::url(auth()->user()->photo) }}" alt="Profilbild" class="w-24 h-24 rounded-full object-cover border-4 border-blue-200 shadow-lg">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600">Nuvarande profilbild</p>
                                    <p class="text-xs text-gray-500 mt-1">Ladda upp en ny bild för att ersätta den</p>
                                </div>
                            </div>
                        @endif
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-lg">📸</span>
                            </div>
                            <input 
                                type="file" 
                                name="photo" 
                                accept="image/*"
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-1">Max 2MB. Format: JPG, PNG, GIF</p>
                        @error('photo')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <span class="mr-1">⚠️</span>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-purple-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="text-2xl mr-3">🔐</span>
                        Säkerhet & Lösenord
                    </h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="text-blue-500 text-xl">💡</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 font-medium">
                                    Lämna fälten tomma om du inte vill ändra ditt lösenord
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nuvarande Lösenord
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-lg">🔒</span>
                            </div>
                            <input 
                                type="password" 
                                name="current_password" 
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-300"
                                placeholder="Ditt nuvarande lösenord"
                            >
                        </div>
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <span class="mr-1">⚠️</span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nytt Lösenord
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-lg">🆕</span>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-300"
                                placeholder="Minst 8 tecken"
                                minlength="8"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ml-1">Minst 8 tecken, blanda stora/små bokstäver och siffror</p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <span class="mr-1">⚠️</span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Bekräfta Nytt Lösenord
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-400 text-lg">✅</span>
                            </div>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all hover:border-gray-300"
                                placeholder="Upprepa ditt nya lösenord"
                                minlength="8"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Account Info -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="text-xl mr-2">ℹ️</span>
                        Kontoinformation
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <span class="text-blue-500 mr-2">👤</span>
                            <span class="text-sm text-gray-600">Kontotyp</span>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Användare</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            <span class="text-sm text-gray-600">Status</span>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">{{ ucfirst(auth()->user()->status) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <span class="text-purple-500 mr-2">📅</span>
                            <span class="text-sm text-gray-600">Medlem sedan</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center">
                            <span class="text-orange-500 mr-2">🕐</span>
                            <span class="text-sm text-gray-600">Senast uppdaterad</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-xl shadow-lg overflow-hidden border-2 border-purple-200">
                <div class="px-6 py-4 border-b border-purple-200 bg-white/50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="text-xl mr-2">⚡</span>
                        Snabbåtgärder
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('user.dashboard') }}" class="block w-full px-4 py-3 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition text-center shadow-sm hover:shadow-md transform hover:scale-105">
                        <span class="mr-2">📊</span> Min Dashboard
                    </a>
                    <a href="{{ route('user.bookings.index') }}" class="block w-full px-4 py-3 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition text-center shadow-sm hover:shadow-md transform hover:scale-105">
                        <span class="mr-2">📋</span> Mina Bokningar
                    </a>
                    <a href="{{ route('welcome') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg transition text-center shadow-md hover:shadow-lg transform hover:scale-105">
                        <span class="mr-2">✨</span> Boka Ny Tjänst
                    </a>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl shadow-lg overflow-hidden border-2 border-yellow-200">
                <div class="px-6 py-4 border-b border-yellow-200 bg-white/50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="text-xl mr-2">🔒</span>
                        Säkerhetstips
                    </h3>
                </div>
                <div class="p-4">
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2 mt-0.5">✓</span>
                            <span>Använd ett starkt och unikt lösenord</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2 mt-0.5">✓</span>
                            <span>Dela aldrig dina inloggningsuppgifter</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2 mt-0.5">✓</span>
                            <span>Håll din e-postadress uppdaterad</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2 mt-0.5">✓</span>
                            <span>Granska dina bokningar regelbundet</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2 mt-0.5">✓</span>
                            <span>Logga alltid ut från delade enheter</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-indigo-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="text-xl mr-2">📊</span>
                        Min Aktivitet
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-lg">📋</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Totala bokningar</p>
                                <p class="text-lg font-bold text-gray-900">{{ auth()->user()->bookings()->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-lg">⏳</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Pågående</p>
                                <p class="text-lg font-bold text-gray-900">{{ auth()->user()->bookings()->whereIn('status', ['pending', 'assigned', 'in_progress'])->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-lg">✅</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Slutförda</p>
                                <p class="text-lg font-bold text-gray-900">{{ auth()->user()->bookings()->where('status', 'completed')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-8">
        <button 
            type="submit" 
            class="w-full lg:w-auto px-12 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:scale-105 text-lg"
        >
            <span class="mr-2">💾</span> Spara Ändringar
        </button>
    </div>
</form>

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
</style>
@endpush
@endsection
