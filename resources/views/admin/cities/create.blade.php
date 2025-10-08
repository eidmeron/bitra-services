@extends('layouts.admin')

@section('title', 'Skapa stad')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.cities.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till städer</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Skapa ny stad</h2>

        <form method="POST" action="{{ route('admin.cities.store') }}">
            @csrf

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
                        <option value="">Välj zone...</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Stadsnamn *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="t.ex. Stockholm">
                    <p class="text-xs text-gray-500 mt-1">Namnet på staden</p>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Beskriv området...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Prismultiplikator *</label>
                    <input type="number" name="city_multiplier" value="{{ old('city_multiplier', 1.00) }}" step="0.01" min="0" max="10" required class="form-input">
                    <p class="text-xs text-gray-500 mt-1">
                        Prisjustering för denna stad. 1.00 = normalpris, 1.20 = 20% högre, 0.90 = 10% lägre
                    </p>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">💡 Tips om prismultiplikatorer</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li><strong>1.00</strong> - Normalpris (referensvärde)</li>
                        <li><strong>1.20</strong> - Storstad (t.ex. Stockholm, Göteborg)</li>
                        <li><strong>1.10</strong> - Mellanstor stad</li>
                        <li><strong>0.95</strong> - Mindre stad</li>
                    </ul>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Skapa stad
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

