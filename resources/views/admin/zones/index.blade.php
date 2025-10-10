@extends('layouts.admin')

@section('title', 'Zoner')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Zoner</h2>
    <a href="{{ route('admin.zones.create') }}" class="btn btn-primary">
        + Skapa ny zone
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" class="flex space-x-4">
        <input 
            type="text" 
            name="search" 
            placeholder="Sök zone..." 
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

<!-- Zones Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Zone</th>
                    <th>Beskrivning</th>
                    <th>Antal städer</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($zones as $zone)
                    <tr class="border-t hover:bg-gray-50">
                        <td>
                            <div class="font-medium text-lg">{{ $zone->name }}</div>
                            <div class="text-xs text-gray-500">{{ $zone->slug }}</div>
                        </td>
                        <td class="max-w-md">
                            <p class="text-sm text-gray-600 truncate">{{ $zone->description }}</p>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $zone->cities_count }} städer</span>
                        </td>
                        <td>
                            @if($zone->status === 'active')
                                <span class="badge badge-success">Aktiv</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600">Inaktiv</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.zones.edit', $zone) }}" class="text-blue-600 hover:underline text-sm">
                                    Redigera
                                </a>
                                @if($zone->cities_count === 0)
                                    <form method="POST" action="{{ route('admin.zones.destroy', $zone) }}" onsubmit="return confirm('Är du säker?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">
                                            Radera
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm" title="Kan inte radera zone med städer">
                                        Radera
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <p class="text-gray-500 mb-4">Inga zoner hittades</p>
                            <a href="{{ route('admin.zones.create') }}" class="btn btn-primary">
                                Skapa din första zone
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $zones->links() }}
    </div>
</div>

<!-- Info -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h4 class="font-semibold text-blue-900 mb-2">💡 Om zoner</h4>
    <p class="text-sm text-blue-800 mb-2">
        Zoner används för att gruppera städer i geografiska områden. Detta förenklar hanteringen av många städer.
    </p>
    <p class="text-sm text-blue-800">
        <strong>Exempel:</strong> Zone "Stor-Stockholm" kan innehålla städer som Stockholm, Solna, Sundbyberg, etc.
    </p>
</div>
@endsection

