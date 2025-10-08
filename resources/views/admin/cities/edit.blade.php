@extends('layouts.admin')

@section('title', 'Redigera stad')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.cities.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till städer</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Redigera: {{ $city->name }}</h2>

        <form method="POST" action="{{ route('admin.cities.update', $city) }}">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label class="form-label">Zone *</label>
                    <select name="zone_id" required class="form-input">
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ $city->zone_id == $zone->id ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Stadsnamn *</label>
                    <input type="text" name="name" value="{{ old('name', $city->name) }}" required class="form-input">
                </div>

                <div>
                    <label class="form-label">Slug</label>
                    <input type="text" value="{{ $city->slug }}" disabled class="form-input bg-gray-100">
                    <p class="text-xs text-gray-500 mt-1">Slug genereras automatiskt från namnet</p>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $city->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Prismultiplikator *</label>
                    <input type="number" name="city_multiplier" value="{{ old('city_multiplier', $city->city_multiplier) }}" step="0.01" min="0" max="10" required class="form-input">
                    <p class="text-xs text-gray-500 mt-1">
                        Nuvarande: ×{{ $city->city_multiplier }} 
                        @if($city->city_multiplier > 1.00)
                            ({{ number_format(($city->city_multiplier - 1) * 100, 0) }}% högre än baslinjen)
                        @elseif($city->city_multiplier < 1.00)
                            ({{ number_format((1 - $city->city_multiplier) * 100, 0) }}% lägre än baslinjen)
                        @else
                            (Baslinjenivå)
                        @endif
                    </p>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ $city->status === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ $city->status === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <!-- Statistics -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">📊 Statistik</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Tillgängliga tjänster:</p>
                            <p class="font-semibold text-lg">{{ $city->services->count() }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Aktiva företag:</p>
                            <p class="font-semibold text-lg">{{ $city->companies->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-6 pt-6 border-t">
                <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="return confirm('Är du säker på att du vill radera denna stad? Detta kan påverka befintliga bokningar.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Radera stad
                    </button>
                </form>

                <div class="flex space-x-4">
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                        Avbryt
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Uppdatera stad
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

