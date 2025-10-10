@extends('layouts.admin')

@section('title', 'Formulär')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Formulär</h2>
    <a href="{{ route('admin.forms.create') }}" class="btn btn-primary">
        + Skapa nytt formulär
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" class="flex space-x-4">
        <input 
            type="text" 
            name="search" 
            placeholder="Sök formulär..." 
            value="{{ request('search') }}"
            class="form-input flex-1"
        >
        <select name="service_id" class="form-input" onchange="this.form.submit()">
            <option value="">Alla tjänster</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
        <select name="status" class="form-input" onchange="this.form.submit()">
            <option value="">Alla statusar</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktiv</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Utkast</option>
        </select>
        <button type="submit" class="btn btn-primary">Sök</button>
    </form>
</div>

<!-- Forms Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($forms as $form)
        <div class="card hover:shadow-lg transition">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <h3 class="font-bold text-lg mb-1">{{ $form->form_name }}</h3>
                    <p class="text-sm text-gray-600">{{ $form->service->name }}</p>
                </div>
                @if($form->status === 'active')
                    <span class="badge badge-success">Aktiv</span>
                @elseif($form->status === 'draft')
                    <span class="badge badge-warning">Utkast</span>
                @else
                    <span class="badge bg-gray-100 text-gray-600">Inaktiv</span>
                @endif
            </div>

            <div class="text-sm text-gray-600 mb-4">
                <p>Fält: {{ $form->fields->count() }}</p>
                <p>Bokningar: {{ $form->bookings->count() }}</p>
                <p>Skapad: {{ $form->created_at->format('Y-m-d') }}</p>
            </div>

            <div class="border-t pt-4">
                <div class="flex flex-wrap gap-2 mb-3">
                    <a href="{{ route('admin.forms.edit', $form) }}" class="text-blue-600 hover:underline text-sm">
                        ✏️ Redigera
                    </a>
                    <a href="{{ route('admin.forms.preview', $form) }}" class="text-green-600 hover:underline text-sm" target="_blank">
                        👁️ Förhandsgranska
                    </a>
                    <a href="{{ route('admin.forms.shortcode', $form) }}" class="text-purple-600 hover:underline text-sm">
                        📋 Kortkod
                    </a>
                </div>
                
                <div class="flex justify-between items-center">
                    <form method="POST" action="{{ route('admin.forms.duplicate', $form) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-indigo-600 hover:underline text-sm">
                            📑 Duplicera
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('admin.forms.destroy', $form) }}" onsubmit="return confirm('Är du säker?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-sm">
                            🗑️ Radera
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-12">
            <p class="text-gray-500 mb-4">Inga formulär ännu</p>
            <a href="{{ route('admin.forms.create') }}" class="btn btn-primary">
                Skapa ditt första formulär
            </a>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $forms->links() }}
</div>
@endsection

