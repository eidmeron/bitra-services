@extends('layouts.admin')

@section('title', 'Städer')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Städer</h2>
    <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
        + Lägg till stad
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" class="flex space-x-4">
        <input 
            type="text" 
            name="search" 
            placeholder="Sök stad..." 
            value="{{ request('search') }}"
            class="form-input flex-1"
        >
        <select name="zone_id" class="form-input" onchange="this.form.submit()">
            <option value="">Alla zoner</option>
            @foreach($zones as $zone)
                <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                    {{ $zone->name }}
                </option>
            @endforeach
        </select>
        <select name="status" class="form-input" onchange="this.form.submit()">
            <option value="">Alla statusar</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktiv</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
        </select>
        <button type="submit" class="btn btn-primary">Sök</button>
    </form>
</div>

<!-- Cities Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Stad</th>
                    <th>Zone</th>
                    <th>Prismultiplikator</th>
                    <th>Tjänster</th>
                    <th>Företag</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                    <tr class="border-t hover:bg-gray-50">
                        <td>
                            <div class="font-medium">{{ $city->name }}</div>
                            <div class="text-xs text-gray-500">{{ $city->slug }}</div>
                        </td>
                        <td>{{ $city->zone->name }}</td>
                        <td>
                            <span class="font-semibold text-blue-600">×{{ $city->city_multiplier }}</span>
                            @if($city->city_multiplier > 1.10)
                                <span class="text-xs text-orange-600">(Hög)</span>
                            @elseif($city->city_multiplier > 1.00)
                                <span class="text-xs text-yellow-600">(Medium)</span>
                            @else
                                <span class="text-xs text-green-600">(Normal)</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $city->services->count() }}</span>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $city->companies->count() }}</span>
                        </td>
                        <td>
                            @if($city->status === 'active')
                                <span class="badge badge-success">Aktiv</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600">Inaktiv</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.cities.edit', $city) }}" class="text-blue-600 hover:underline text-sm">
                                    Redigera
                                </a>
                                <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="return confirm('Är du säker?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">
                                        Radera
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            Inga städer hittades
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $cities->links() }}
    </div>
</div>

<!-- Info Box -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h4 class="font-semibold text-blue-900 mb-2">💡 Om prismultiplikatorer</h4>
    <p class="text-sm text-blue-800 mb-2">
        Prismultiplikatorer används för att justera tjänstepriser baserat på stadens kostnadsnivå.
    </p>
    <ul class="text-sm text-blue-800 list-disc ml-4 space-y-1">
        <li><strong>1.00</strong> - Normal kostnadsnivå (referenspris)</li>
        <li><strong>1.10</strong> - 10% högre kostnader</li>
        <li><strong>1.20</strong> - 20% högre kostnader (t.ex. Stockholm)</li>
        <li><strong>0.90</strong> - 10% lägre kostnader</li>
    </ul>
</div>
@endsection

