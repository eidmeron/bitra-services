@extends('layouts.company')

@section('title', 'Inställningar')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">⚙️ Inställningar</h2>
    <p class="text-gray-600 mt-1">Hantera ditt konto och företagsinställningar</p>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
        <div class="flex items-center">
            <span class="mr-2">✅</span>
            <p>{{ session('success') }}</p>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Account Settings -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">👤</span>
                Kontoinställningar
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('company.settings.account') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Namn
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', auth()->user()->name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            E-post
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', auth()->user()->email) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                        >
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-blue-700 transition-all shadow-md">
                        💾 Spara ändringar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">🔒</span>
                Ändra Lösenord
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('company.settings.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Current Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nuvarande lösenord
                        </label>
                        <input 
                            type="password" 
                            name="current_password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            required
                        >
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nytt lösenord
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            required
                        >
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bekräfta nytt lösenord
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            required
                        >
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all shadow-md">
                        🔐 Ändra Lösenord
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Preferences -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">🔔</span>
                Aviseringsinställningar
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('company.settings.notifications') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b">
                        <div>
                            <p class="font-medium text-gray-900">E-postaviseringar</p>
                            <p class="text-sm text-gray-500">Få e-post för nya bokningar</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_notifications" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b">
                        <div>
                            <p class="font-medium text-gray-900">Bokningsaviseringar</p>
                            <p class="text-sm text-gray-500">Notifiering när bokning tilldelats</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="booking_notifications" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="font-medium text-gray-900">Månatlig rapport</p>
                            <p class="text-sm text-gray-500">Få månadsrapport via e-post</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="monthly_report" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md">
                        💾 Spara inställningar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Information -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">ℹ️</span>
                Kontoinformation
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b">
                    <span class="text-sm text-gray-600">Användare</span>
                    <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b">
                    <span class="text-sm text-gray-600">E-post</span>
                    <span class="text-sm font-semibold">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b">
                    <span class="text-sm text-gray-600">Företag</span>
                    <span class="text-sm font-semibold">{{ auth()->user()->company?->company_name }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b">
                    <span class="text-sm text-gray-600">Status</span>
                    @php
                        $statusColors = [
                            'active' => 'bg-green-100 text-green-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'suspended' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[auth()->user()->company?->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst(auth()->user()->company?->status ?? 'N/A') }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm text-gray-600">Medlem sedan</span>
                    <span class="text-sm font-semibold">{{ auth()->user()->created_at->format('Y-m-d') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danger Zone -->
<div class="mt-6 bg-red-50 border-2 border-red-200 rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 bg-red-100 border-b border-red-200">
        <h3 class="text-lg font-semibold text-red-900 flex items-center">
            <span class="text-2xl mr-3">⚠️</span>
            Farlig Zon
        </h3>
    </div>
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-red-900">Radera konto</h4>
                <p class="text-sm text-red-700 mt-1">
                    När du raderar ditt konto kommer all data permanent att tas bort. Denna åtgärd kan inte ångras.
                </p>
            </div>
            <button 
                onclick="confirm('Är du säker på att du vill radera ditt konto? Detta kan inte ångras!') && document.getElementById('delete-account-form').submit()"
                class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-all shadow-md whitespace-nowrap ml-4">
                🗑️ Radera Konto
            </button>
        </div>
        
        <form id="delete-account-form" action="{{ route('company.settings.delete') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection

