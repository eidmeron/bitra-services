@extends('layouts.admin')

@section('title', 'Skapa företag')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.companies.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till företag</a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Lägg till nytt företag</h2>

        <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data">
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

            <!-- User Account -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Användaruppgifter</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">E-post (inloggning) *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Telefon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input">
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Lösenord *</label>
                        <input type="password" name="password" required class="form-input" autocomplete="new-password">
                        <p class="text-xs text-gray-500 mt-1">Minst 8 tecken</p>
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Företagsinformation</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Företagets e-post *</label>
                        <input type="email" name="company_email" value="{{ old('company_email') }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Företagstelefon *</label>
                        <input type="text" name="company_number" value="{{ old('company_number') }}" required class="form-input" placeholder="0701-234567">
                    </div>

                    <div>
                        <label class="form-label">Organisationsnummer *</label>
                        <input type="text" name="company_org_number" value="{{ old('company_org_number') }}" required class="form-input" placeholder="556789-1234">
                        <p class="text-xs text-gray-500 mt-1">Format: XXXXXX-XXXX</p>
                    </div>

                    <div>
                        <label class="form-label">Webbplats</label>
                        <input type="url" name="site" value="{{ old('site') }}" class="form-input" placeholder="https://example.com">
                    </div>
                </div>

                <div>
                    <label class="form-label">Företagslogotyp</label>
                    <input type="file" name="company_logo" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB. Format: JPG, PNG</p>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Väntande</option>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>
            </div>

            <!-- Services -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Tjänster</h3>
                <p class="text-sm text-gray-600">Välj vilka tjänster detta företag erbjuder</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($services as $service)
                        <label class="flex items-center">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                {{ in_array($service->id, old('services', [])) ? 'checked' : '' }} 
                                class="mr-2">
                            <span>{{ $service->name }} ({{ $service->category->name }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Cities -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Verksamhetsområden</h3>
                <p class="text-sm text-gray-600">Välj i vilka städer företaget är verksamt</p>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($cities as $city)
                        <label class="flex items-center">
                            <input type="checkbox" name="cities[]" value="{{ $city->id }}" 
                                {{ in_array($city->id, old('cities', [])) ? 'checked' : '' }} 
                                class="mr-2">
                            <span>{{ $city->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Skapa företag
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

