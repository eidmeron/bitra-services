@extends('layouts.admin')

@section('title', 'Tjänster')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Tjänster</h2>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        + Skapa ny tjänst
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" class="flex space-x-4">
        <input 
            type="text" 
            name="search" 
            placeholder="Sök tjänst..." 
            value="{{ request('search') }}"
            class="form-input flex-1"
        >
        <select name="category_id" class="form-input" onchange="this.form.submit()">
            <option value="">Alla kategorier</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
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

<!-- Services Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Namn</th>
                    <th>Kategori</th>
                    <th>Grundpris</th>
                    <th>ROT-avdrag</th>
                    <th>Bokningar</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr class="border-t hover:bg-gray-50">
                        <td>
                            <div class="flex items-center">
                                @if($service->icon)
                                    <span class="text-2xl mr-2">{{ $service->icon }}</span>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $service->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $service->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $service->category->name }}</td>
                        <td class="font-semibold">{{ number_format($service->base_price, 2, ',', ' ') }} kr</td>
                        <td>
                            @if($service->rot_eligible)
                                <span class="badge badge-success">{{ $service->rot_percent }}%</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600">Nej</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">
                                {{ $service->one_time_booking ? 'En gång' : '' }}
                                {{ $service->subscription_booking ? 'Prenumeration' : '' }}
                            </span>
                        </td>
                        <td>
                            @if($service->status === 'active')
                                <span class="badge badge-success">Aktiv</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600">Inaktiv</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.services.edit', $service) }}" class="text-blue-600 hover:underline">
                                    Redigera
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Är du säker?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Radera
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            Inga tjänster hittades
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $services->links() }}
    </div>
</div>
@endsection

