@extends('layouts.admin')

@section('title', 'Skapa formulär')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.forms.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till formulär</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Skapa nytt formulär</h2>

        <form method="POST" action="{{ route('admin.forms.store') }}">
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
                <!-- Service Selection -->
                <div>
                    <label class="form-label">Tjänst *</label>
                    <select name="service_id" required class="form-input">
                        <option value="">Välj tjänst...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" 
                                {{ old('service_id', $serviceId) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} ({{ $service->category->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Form Name -->
                <div>
                    <label class="form-label">Formulärnamn *</label>
                    <input type="text" name="form_name" value="{{ old('form_name') }}" required class="form-input" placeholder="t.ex. Hemstädning - Bokningsformulär">
                </div>

                <!-- Success Message -->
                <div>
                    <label class="form-label">Meddelande vid lyckad bokning</label>
                    <textarea name="success_message" rows="3" class="form-input">{{ old('success_message', 'Tack för din bokning! Vi återkommer inom kort.') }}</textarea>
                </div>

                <!-- Redirect Options -->
                <div>
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="redirect_after_submit" value="1" {{ old('redirect_after_submit') ? 'checked' : '' }} class="mr-2">
                        <span>Omdirigera efter bokning</span>
                    </label>

                    <input type="url" name="redirect_url" value="{{ old('redirect_url') }}" class="form-input" placeholder="https://example.com/tack">
                    <p class="text-xs text-gray-500 mt-1">Lämna tomt för att visa standardbekräftelse</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Utkast</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Du kan lägga till fält efter att formuläret skapats</p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">Nästa steg:</h4>
                    <ol class="list-decimal ml-4 text-sm text-blue-800 space-y-1">
                        <li>Skapa formuläret här</li>
                        <li>Lägg till fält med formulärbyggaren</li>
                        <li>Konfigurera prisregler</li>
                        <li>Aktivera formuläret</li>
                        <li>Generera shortcode för WordPress</li>
                    </ol>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <a href="{{ route('admin.forms.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Skapa formulär
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

