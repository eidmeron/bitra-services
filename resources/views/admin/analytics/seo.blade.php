@extends('layouts.admin')

@section('title', 'SEO Analytics')
@section('subtitle', 'Sökoptimeringsanalys och prestanda')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow-lg">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <span class="mr-3">🔍</span>
                SEO Analytics
            </h2>
            <p class="text-gray-600 mt-1">Sökoptimeringsanalys och prestanda</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="refreshData()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                🔄 Uppdatera
            </button>
        </div>
    </div>

    <!-- SEO Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-blue-100 text-sm font-medium">Totalt Nyckelord</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($topKeywords->count()) }}</p>
            <p class="text-xs text-blue-100 mt-1">spårade nyckelord</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-green-100 text-sm font-medium">Hög CTR</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($highCtrKeywords->count()) }}</p>
            <p class="text-xs text-green-100 mt-1">nyckelord >20% CTR</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-purple-100 text-sm font-medium">Topp Sidor</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($pagePerformance->count()) }}</p>
            <p class="text-xs text-purple-100 mt-1">spårade sidor</p>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-orange-100 text-sm font-medium">SEO Förslag</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($seoSuggestions->count()) }}</p>
            <p class="text-xs text-orange-100 mt-1">förbättringsförslag</p>
        </div>
    </div>

    <!-- Top Keywords -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">🔍 Topp Nyckelord</h3>
            <p class="text-sm text-gray-600">Mest sökta nyckelord denna månad</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nyckelord</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sökningar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klick</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CTR</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topKeywords as $keyword)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $keyword->keyword }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($keyword->impressions) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($keyword->clicks) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $keyword->ctr > 20 ? 'bg-green-100 text-green-800' : 
                                   ($keyword->ctr > 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ number_format($keyword->ctr, 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($keyword->average_position, 1) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Inga nyckelord hittades
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- High CTR Keywords -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">🎯 Hög CTR Nyckelord</h3>
            <p class="text-sm text-gray-600">Nyckelord med högst klickfrekvens (>20%)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nyckelord</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CTR</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sökningar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klick</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($highCtrKeywords as $keyword)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $keyword->keyword }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ number_format($keyword->ctr, 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($keyword->impressions) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($keyword->clicks) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Inga höga CTR nyckelord hittades
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Page Performance -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">📊 Sidprestanda</h3>
            <p class="text-sm text-gray-600">Mest besökta sidor</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sida</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Besök</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unika Besökare</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Genomsnittlig Tid</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pagePerformance as $page)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $page->page_url }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($page->total_visits) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($page->unique_visitors) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($page->average_time_on_page, 1) }}s</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Ingen sidprestanda hittades
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- SEO Suggestions -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">💡 SEO Förslag</h3>
            <p class="text-sm text-gray-600">Förbättringsförslag baserat på analys</p>
        </div>
        <div class="p-6">
            @forelse($seoSuggestions as $suggestion)
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <div class="text-blue-500 text-xl mr-3">💡</div>
                    <div>
                        <h4 class="font-semibold text-blue-800">{{ $suggestion['title'] }}</h4>
                        <p class="text-blue-700 mt-1">{{ $suggestion['description'] }}</p>
                        @if(isset($suggestion['priority']))
                        <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full
                            {{ $suggestion['priority'] === 'high' ? 'bg-red-100 text-red-800' : 
                               ($suggestion['priority'] === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($suggestion['priority']) }} Priority
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">
                <div class="text-4xl mb-4">🎉</div>
                <p>Inga SEO-förslag just nu. Din webbplats presterar bra!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function refreshData() {
    location.reload();
}
</script>
@endsection
