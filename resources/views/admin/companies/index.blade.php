@extends('layouts.admin')

@section('title', 'Företag')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🏢 Företag</h1>
            <p class="text-gray-600 mt-1">Hantera alla registrerade företag</p>
        </div>
        <a href="{{ route('admin.companies.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-medium shadow-lg">
            ➕ Skapa Nytt Företag
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Totala Företag</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_companies']) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl">🏢</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Aktiva</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['active_companies']) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl">✅</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Väntande</p>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($stats['pending_companies']) }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-2xl">⏳</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Nya Denna Månad</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($stats['new_this_month']) }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-2xl">📊</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <form method="GET" action="{{ route('admin.companies.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">🔍 Sök Företag</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Företag, org.nr, email..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">📊 Status</label>
                <select 
                    name="status" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Alla statusar</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktiv</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Väntande</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                </select>
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

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">🔄 Sortera</label>
                <select 
                    name="sort" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Senaste först</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Äldsta först</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Namn A-Ö</option>
                </select>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                🔍 Filtrera
            </button>
            <a href="{{ route('admin.companies.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                ↺ Rensa
            </a>
            <span class="text-sm text-gray-600">
                Visar {{ $companies->count() }} av {{ number_format($stats['total_companies']) }} företag
            </span>
        </div>
    </form>
</div>

<!-- Companies Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b-2 border-blue-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Företag</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontakt</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Org.Nummer</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tjänster</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Betyg</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Registrerad</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($companies as $company)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                @if($company->logo)
                                    <img src="{{ Storage::url($company->logo) }}" 
                                         alt="{{ $company->company_name }}" 
                                         class="w-12 h-12 rounded-lg object-cover shadow-md">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                        {{ strtoupper(substr($company->company_name ?? 'C', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $company->company_name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $company->user->email ?? 'No email' }}</div>
                                    @if($company->site)
                                        <a href="{{ $company->site }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            🌐 {{ Str::limit($company->site, 25) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if($company->company_email)
                                    📧 {{ $company->company_email }}
                                @else
                                    <span class="text-gray-400">Ingen email</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($company->company_number)
                                    📱 {{ $company->company_number }}
                                @else
                                    <span class="text-gray-400">Ingen telefon</span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-900">
                                {{ $company->company_org_number ?? 'N/A' }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                {{ $company->services->count() }} tjänster
                            </span>
                            @if($company->cities->count() > 0)
                                <div class="text-xs text-gray-500 mt-1">
                                    📍 {{ $company->cities->count() }} städer
                                </div>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4">
                            @if($company->reviews_avg_rating)
                                <div class="flex items-center">
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($company->reviews_avg_rating))
                                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm font-semibold text-gray-700">
                                        {{ number_format($company->reviews_avg_rating, 1) }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    ({{ $company->reviews_count ?? 0 }} recensioner)
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Inga betyg</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'inactive' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$company->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($company->status) }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">
                                    {{ $company->created_at->format('Y-m-d') }}
                                </div>
                                <div class="text-xs {{ $company->created_at->isToday() ? 'text-green-600 font-bold' : 'text-gray-500' }}">
                                    ⏰ {{ $company->created_at->diffForHumans() }}
                                </div>
                                @if($company->created_at->isToday())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                        ✨ Ny idag!
                                    </span>
                                @elseif($company->created_at->isCurrentWeek())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        🆕 Denna vecka
                                    </span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.companies.show', $company) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-medium">
                                👁️ Visa
                            </a>
                            <a href="{{ route('admin.companies.edit', $company) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition font-medium">
                                ✏️ Redigera
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-5xl mb-4">🏢</div>
                            <p class="text-gray-500 text-lg">Inga företag hittades</p>
                            @if(request()->hasAny(['search', 'status', 'date_from']))
                                <a href="{{ route('admin.companies.index') }}" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
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
    @if($companies->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t">
            {{ $companies->links() }}
        </div>
    @endif
</div>

<!-- Quick Stats -->
@if($companies->count() > 0)
<div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">📊 Snabb Översikt</h3>
            <p class="text-sm text-gray-600">Företagsstatistik</p>
        </div>
        <div class="grid grid-cols-3 gap-8">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active_companies']) }}</p>
                <p class="text-xs text-gray-600">Aktiva</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_companies']) }}</p>
                <p class="text-xs text-gray-600">Väntande</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['new_this_month']) }}</p>
                <p class="text-xs text-gray-600">Nya denna månad</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
