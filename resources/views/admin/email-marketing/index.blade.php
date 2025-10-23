@extends('layouts.admin')

@section('title', 'Email Marketing')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                <span class="text-4xl mr-3">📧</span>
                Email Marketing
            </h2>
            <p class="text-gray-600 mt-2">Hantera email-kampanjer och prenumeranter</p>
        </div>
        <a href="{{ route('admin.email-marketing.create') }}" class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <span class="mr-2">➕</span>
            Skapa kampanj
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Aktiva Prenumeranter</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($subscribers) }}</p>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl">
                👥
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Totala Användare</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($totalUsers) }}</p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-3xl">
                👤
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Aktiva Företag</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($totalCompanies) }}</p>
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-3xl">
                🏢
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Kampanjer</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($campaigns->total()) }}</p>
            </div>
            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center text-3xl">
                📊
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('admin.email-marketing.subscribers') }}" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                👥
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Prenumeranter</h3>
                <p class="text-sm text-gray-600">Hantera email-prenumeranter</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.email-marketing.create') }}" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                ✉️
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Ny Kampanj</h3>
                <p class="text-sm text-gray-600">Skapa en ny email-kampanj</p>
            </div>
        </div>
    </a>

    <a href="#" class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-all transform hover:-translate-y-1">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                📈
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Statistik</h3>
                <p class="text-sm text-gray-600">Visa kampanj-statistik</p>
            </div>
        </div>
    </a>
</div>

<!-- Campaigns Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <span class="text-xl mr-2">📊</span>
            Email Kampanjer ({{ $campaigns->total() }})
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kampanj</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mottagare</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skapad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($campaigns as $campaign)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-bold mr-3">
                                    📧
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $campaign->subject }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($campaign->content, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($campaign->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($campaign->recipient_count ?? 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($campaign->status)
                                @case('draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        📝 Utkast
                                    </span>
                                    @break
                                @case('scheduled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        ⏰ Schemalagd
                                    </span>
                                    @break
                                @case('sent')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅ Skickad
                                    </span>
                                    @break
                                @case('failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        ❌ Misslyckades
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        ❓ Okänd
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $campaign->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.email-marketing.show', $campaign) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-all">
                                    <span class="mr-1">👁️</span>
                                    Visa
                                </a>
                                @if($campaign->status === 'draft')
                                    <a href="{{ route('admin.email-marketing.edit', $campaign) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-all">
                                        <span class="mr-1">✏️</span>
                                        Redigera
                                    </a>
                                @endif
                                @if($campaign->status === 'draft' || $campaign->status === 'scheduled')
                                    <form action="{{ route('admin.email-marketing.send', $campaign) }}" method="POST" onsubmit="return confirm('Är du säker på att du vill skicka denna kampanj?')" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg transition-all">
                                            <span class="mr-1">📤</span>
                                            Skicka
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <div class="text-4xl mb-4">📧</div>
                                <p class="text-lg font-medium">Inga kampanjer hittades</p>
                                <p class="text-sm">Skapa din första email-kampanj</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
