@extends('layouts.admin')

@section('title', 'Kategorier')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Kategorier</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        + Skapa ny kategori
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" class="flex space-x-4">
        <input 
            type="text" 
            name="search" 
            placeholder="Sök kategori..." 
            value="{{ request('search') }}"
            class="form-input flex-1"
        >
        <select name="status" class="form-input" onchange="this.form.submit()">
            <option value="">Alla statusar</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktiv</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
        </select>
        <button type="submit" class="btn btn-primary">Sök</button>
    </form>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Ordning</th>
                    <th>Kategori</th>
                    <th>Beskrivning</th>
                    <th>Tjänster</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-t hover:bg-gray-50">
                        <td>
                            <span class="badge bg-gray-100 text-gray-600">#{{ $category->sort_order }}</span>
                        </td>
                        <td>
                            <div class="flex items-center">
                                @if($category->icon)
                                    <span class="text-3xl mr-3">{{ $category->icon }}</span>
                                @endif
                                <div>
                                    <div class="font-medium text-lg">{{ $category->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $category->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="max-w-md">
                            <p class="text-sm text-gray-600 truncate">{{ $category->description }}</p>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $category->services_count }} tjänster</span>
                        </td>
                        <td>
                            @if($category->status === 'active')
                                <span class="badge badge-success">Aktiv</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600">Inaktiv</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline text-sm">
                                    Redigera
                                </a>
                                @if($category->services_count === 0)
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Är du säker?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">
                                            Radera
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm" title="Kan inte radera kategori med tjänster">
                                        Radera
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <p class="text-gray-500 mb-4">Inga kategorier hittades</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                Skapa din första kategori
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>

<!-- Info Box -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h4 class="font-semibold text-blue-900 mb-2">💡 Om kategorier</h4>
    <p class="text-sm text-blue-800 mb-2">
        Kategorier används för att organisera tjänster. Varje tjänst måste tillhöra en kategori.
    </p>
    <ul class="text-sm text-blue-800 list-disc ml-4 space-y-1">
        <li>Använd "Ordning" för att sortera hur kategorier visas</li>
        <li>Ikoner visas tillsammans med kategorinamnet (använd emoji)</li>
        <li>Kategorier med tjänster kan inte raderas</li>
    </ul>
</div>
@endsection

