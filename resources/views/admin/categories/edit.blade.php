@extends('layouts.admin')

@section('title', 'Redigera kategori')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till kategorier</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Redigera: {{ $category->name }}</h2>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
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
                    <label class="form-label">Kategorinamn *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="form-input">
                </div>

                <div>
                    <label class="form-label">Slug</label>
                    <input type="text" value="{{ $category->slug }}" disabled class="form-input bg-gray-100">
                    <p class="text-xs text-gray-500 mt-1">Slug genereras automatiskt från namnet</p>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $category->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Ikon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="form-input" maxlength="10">
                    <p class="text-xs text-gray-500 mt-1">Nuvarande: {{ $category->icon ?? 'Ingen ikon' }}</p>
                </div>

                <div>
                    <label class="form-label">Bild</label>
                    @if($category->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="form-input">
                </div>

                <div>
                    <label class="form-label">Sorteringsordning</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Lägre nummer visas först</p>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ $category->status === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ $category->status === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <!-- Statistics -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">📊 Statistik</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Antal tjänster:</p>
                            <p class="font-semibold text-lg">{{ $category->services_count }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Skapad:</p>
                            <p class="font-semibold">{{ $category->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-6 pt-6 border-t">
                @if($category->services_count === 0)
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Är du säker på att du vill radera denna kategori?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Radera kategori
                        </button>
                    </form>
                @else
                    <div class="text-sm text-gray-500">
                        Kan inte radera kategori med tjänster
                    </div>
                @endif

                <div class="flex space-x-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        Avbryt
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Uppdatera kategori
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

