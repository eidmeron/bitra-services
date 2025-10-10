@extends('layouts.admin')

@section('title', 'Skapa kategori')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till kategorier</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Skapa ny kategori</h2>

        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
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
                    <label class="form-label">Kategorinamn *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="t.ex. Städning">
                    <p class="text-xs text-gray-500 mt-1">Namnet på kategorin</p>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Beskriv vilka typer av tjänster som ingår i denna kategori...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Ikon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" class="form-input" placeholder="🧹" maxlength="10">
                    <p class="text-xs text-gray-500 mt-1">Emoji som representerar kategorin. Exempel: 🧹 🔧 🌱 📦</p>
                </div>

                <div>
                    <label class="form-label">Bild</label>
                    <input type="file" name="image" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB. Format: JPG, PNG, GIF</p>
                </div>

                <div>
                    <label class="form-label">Sorteringsordning</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Lägre nummer visas först. Standard: 0</p>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="font-semibold text-green-900 mb-2">✨ Förslag på kategorier</h4>
                    <div class="text-sm text-green-800 space-y-1">
                        <p>• 🧹 Städning - Hemstädning, kontorsstädning, flyttstädning</p>
                        <p>• 🔧 Hantverkare - VVS, el, snickare, målare</p>
                        <p>• 🌱 Trädgård - Gräsklippning, beskärning, trädgårdsdesign</p>
                        <p>• 📦 Flytt - Flytthjälp, packning, magasinering</p>
                        <p>• 🛠️ Reparationer - Vitvaror, elektronik, möbler</p>
                        <p>• 🚗 Fordon - Biltvätt, däckskifte, service</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Skapa kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

