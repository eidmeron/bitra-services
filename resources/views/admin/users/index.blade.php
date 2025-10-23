@extends('layouts.admin')

@section('title', 'Användare')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">👥 Användare</h1>
            <p class="text-gray-600 mt-1">Hantera alla registrerade användare</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-medium shadow-lg">
            ➕ Skapa Ny Användare
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Totala Användare</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl">👥</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Nya Idag</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['new_today']) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl">✨</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Denna Vecka</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($stats['new_this_week']) }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-2xl">📅</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Denna Månad</p>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($stats['new_this_month']) }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-2xl">📊</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">🔍 Sök Användare</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Email, namn, telefon..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">📅 Från Datum</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ request('date_from') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">📅 Till Datum</label>
                <input 
                    type="date" 
                    name="date_to" 
                    value="{{ request('date_to') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">🔄 Sortera</label>
                <select 
                    name="sort" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Senaste först</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Äldsta först</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Email A-Ö</option>
                </select>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                🔍 Filtrera
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                ↺ Rensa
            </a>
            <span class="text-sm text-gray-600">
                Visar {{ $users->count() }} av {{ number_format($stats['total_users']) }} användare
            </span>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b-2 border-blue-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Användare</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontakt</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bokningar</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Registrerad</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <!-- Avatar -->
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                    {{ strtoupper(substr($user->email, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->email }}</div>
                                    @if($user->name)
                                        <div class="text-sm text-gray-500">{{ $user->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if($user->phone)
                                    <span class="flex items-center">
                                        📱 {{ $user->phone }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">Ingen telefon</span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $user->bookings_count }} bokningar
                                </span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">
                                    {{ $user->created_at->format('Y-m-d H:i') }}
                                </div>
                                <div class="text-xs {{ $user->created_at->isToday() ? 'text-green-600 font-bold' : 'text-gray-500' }}">
                                    ⏰ {{ $user->created_at->diffForHumans() }}
                                </div>
                                @if($user->created_at->isToday())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                        ✨ Ny idag!
                                    </span>
                                @elseif($user->created_at->isCurrentWeek())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        🆕 Denna vecka
                                    </span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-medium">
                                👁️ Visa
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition font-medium">
                                ✏️ Redigera
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-5xl mb-4">👤</div>
                            <p class="text-gray-500 text-lg">Inga användare hittades</p>
                            @if(request()->hasAny(['search', 'date_from', 'date_to']))
                                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
                                    Rensa filter och visa alla
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- Quick Stats -->
@if($users->count() > 0)
<div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">📊 Snabb Översikt</h3>
            <p class="text-sm text-gray-600">Senaste 30 dagarna</p>
        </div>
        <div class="grid grid-cols-3 gap-8">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['new_this_month']) }}</p>
                <p class="text-xs text-gray-600">Nya denna månad</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['new_this_week']) }}</p>
                <p class="text-xs text-gray-600">Nya denna vecka</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['new_today']) }}</p>
                <p class="text-xs text-gray-600">Nya idag</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

