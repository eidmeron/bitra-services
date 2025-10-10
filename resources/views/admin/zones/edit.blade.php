@extends('layouts.admin')

@section('title', 'Redigera zone')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.zones.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till zoner</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Redigera: {{ $zone->name }}</h2>

        <form method="POST" action="{{ route('admin.zones.update', $zone) }}">
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
                    <label class="form-label">Zonnamn *</label>
                    <input type="text" name="name" value="{{ old('name', $zone->name) }}" required class="form-input">
                </div>

                <div>
                    <label class="form-label">Slug</label>
                    <input type="text" value="{{ $zone->slug }}" disabled class="form-input bg-gray-100">
                    <p class="text-xs text-gray-500 mt-1">Slug genereras automatiskt från namnet</p>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $zone->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ $zone->status === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ $zone->status === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <!-- Statistics -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">📊 Statistik</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Antal städer:</span>
                            <span class="font-semibold">{{ $zone->cities_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Skapad:</span>
                            <span class="font-semibold">{{ $zone->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-6 pt-6 border-t">
                @if($zone->cities_count === 0)
                    <form method="POST" action="{{ route('admin.zones.destroy', $zone) }}" onsubmit="return confirm('Är du säker på att du vill radera denna zone?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Radera zone
                        </button>
                    </form>
                @else
                    <div class="text-sm text-gray-500">
                        Kan inte radera zone med städer
                    </div>
                @endif

                <div class="flex space-x-4">
                    <a href="{{ route('admin.zones.index') }}" class="btn btn-secondary">
                        Avbryt
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Uppdatera zone
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

