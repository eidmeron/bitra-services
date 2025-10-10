@extends('layouts.admin')

@section('title', 'Skapa zone')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.zones.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till zoner</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Skapa ny zone</h2>

        <form method="POST" action="{{ route('admin.zones.store') }}">
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
                    <label class="form-label">Zonnamn *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="t.ex. Stor-Stockholm">
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Beskriv det geografiska området...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">💡 Nästa steg</h4>
                    <p class="text-sm text-blue-800">
                        Efter att du skapat zonen kan du lägga till städer under "Städer" menyn.
                    </p>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <a href="{{ route('admin.zones.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Skapa zone
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

