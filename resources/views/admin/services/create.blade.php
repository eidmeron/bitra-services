@extends('layouts.admin')

@section('title', 'Skapa tjänst')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.services.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till tjänster</a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Skapa ny tjänst</h2>

        <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
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

            <!-- Basic Information -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Grundinformation</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tjänstnamn *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Kategori *</label>
                        <select name="category_id" required class="form-input">
                            <option value="">Välj kategori...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Slug *</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" required class="form-input">
                        <p class="text-xs text-gray-500 mt-1">URL-vänligt namn (t.ex. hemstadning)</p>
                    </div>

                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" required class="form-input">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktiv</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" class="form-input" placeholder="🧹">
                </div>

                <div>
                    <label class="form-label">Bild</label>
                    <input type="file" name="image" accept="image/*" class="form-input">
                </div>
            </div>

            <!-- Pricing -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Prissättning</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Grundpris (kr) *</label>
                        <input type="number" name="base_price" value="{{ old('base_price', 0) }}" step="0.01" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Rabatt (%)</label>
                        <input type="number" name="discount_percent" value="{{ old('discount_percent', 0) }}" step="0.01" min="0" max="100" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Booking Options -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Bokningsalternativ</h3>

                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="one_time_booking" value="1" {{ old('one_time_booking', true) ? 'checked' : '' }} class="mr-2">
                        <span>Engångsbokning</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="subscription_booking" value="1" {{ old('subscription_booking') ? 'checked' : '' }} class="mr-2">
                        <span>Prenumerationsbokning</span>
                    </label>
                </div>
            </div>

            <!-- ROT-avdrag -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">ROT-avdrag</h3>

                <div class="space-y-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="rot_eligible" value="1" {{ old('rot_eligible') ? 'checked' : '' }} class="mr-2">
                        <span>Berättigad till ROT-avdrag</span>
                    </label>

                    <div>
                        <label class="form-label">ROT-procent (%)</label>
                        <input type="number" name="rot_percent" value="{{ old('rot_percent', 30) }}" step="0.01" min="0" max="100" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Standard är 30%</p>
                    </div>
                </div>
            </div>

            <!-- Cities -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Tillgängliga städer</h3>
                <p class="text-sm text-gray-600">Välj i vilka städer denna tjänst ska vara tillgänglig</p>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($cities as $city)
                        <label class="flex items-center">
                            <input type="checkbox" name="cities[]" value="{{ $city->id }}" 
                                {{ in_array($city->id, old('cities', [])) ? 'checked' : '' }} 
                                class="mr-2">
                            <span>{{ $city->name }} (×{{ $city->city_multiplier }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Skapa tjänst
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

