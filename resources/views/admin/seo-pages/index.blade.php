@extends('layouts.admin')

@section('title', 'SEO Sidor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🎯 SEO Sidor</h1>
            <p class="text-gray-600 mt-2">Hantera SEO för dynamiska sidor (Städer, Tjänster, Kategorier)</p>
        </div>
        <a href="{{ route('admin.seo-pages.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Skapa SEO-sida
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sök</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Sök efter titel, slug...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sidtyp</label>
                <select name="page_type" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Alla</option>
                    <option value="service" {{ request('page_type') === 'service' ? 'selected' : '' }}>Tjänst</option>
                    <option value="category" {{ request('page_type') === 'category' ? 'selected' : '' }}>Kategori</option>
                    <option value="city" {{ request('page_type') === 'city' ? 'selected' : '' }}>Stad</option>
                    <option value="zone" {{ request('page_type') === 'zone' ? 'selected' : '' }}>Zon</option>
                    <option value="city_service" {{ request('page_type') === 'city_service' ? 'selected' : '' }}>Stad + Tjänst</option>
                    <option value="category_service" {{ request('page_type') === 'category_service' ? 'selected' : '' }}>Kategori + Tjänst</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg flex-1">
                    🔍 Filtrera
                </button>
                <a href="{{ route('admin.seo-pages.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg">
                    ✕ Rensa
                </a>
            </div>
        </form>
    </div>

    <!-- Results Summary -->
    <div class="mb-4 text-sm text-gray-600">
        Visar {{ $seoPages->count() }} av {{ $seoPages->total() }} SEO-sidor
    </div>

    @if($seoPages->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Inga SEO-sidor ännu</h3>
            <p class="text-gray-500 mb-6">Skapa din första SEO-sida för att komma igång</p>
            <a href="{{ route('admin.seo-pages.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Skapa SEO-sida
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sidtyp</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Titel</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Relaterat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($seoPages as $page)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                $page->page_type === 'city_service' ? 'bg-purple-100 text-purple-800' : 
                                ($page->page_type === 'city' ? 'bg-blue-100 text-blue-800' : 
                                ($page->page_type === 'service' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))
                            }}">
                                {{ $page->page_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $page->meta_title ?? 'Ingen titel' }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($page->meta_description, 60) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($page->city)
                                <div>📍 {{ $page->city->name }}</div>
                            @endif
                            @if($page->service)
                                <div>🔧 {{ $page->service->name }}</div>
                            @endif
                            @if($page->category)
                                <div>📂 {{ $page->category->name }}</div>
                            @endif
                            @if($page->zone)
                                <div>🗺️ {{ $page->zone->name }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                            {{ $page->slug }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                $page->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                            }}">
                                {{ $page->status === 'active' ? '✓ Aktiv' : '○ Inaktiv' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.seo-pages.edit', $page) }}" 
                               class="text-blue-600 hover:text-blue-900 font-semibold">Redigera</a>
                            <form method="POST" action="{{ route('admin.seo-pages.destroy', $page) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Är du säker på att du vill ta bort denna SEO-sida?')"
                                        class="text-red-600 hover:text-red-900 font-semibold">Ta bort</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $seoPages->links() }}
        </div>
    @endif
</div>
@endsection
