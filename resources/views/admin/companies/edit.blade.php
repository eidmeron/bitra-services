@extends('layouts.admin')

@section('title', 'Redigera Företag')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Redigera Företag</h2>
            <p class="text-gray-600 mt-1">Uppdatera företagsinformation och inställningar</p>
        </div>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
            ← Tillbaka till lista
        </a>
    </div>
</div>

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.companies.update', $company) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">📋 Grundläggande Information</h3>
                </div>
                <div class="card-body space-y-4">
                    <!-- Company Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Företagsnamn <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="company_name" 
                            value="{{ old('company_name', $company->company_name) }}"
                            class="form-input w-full"
                            required
                        >
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Företagsbeskrivning
                        </label>
                        <textarea 
                            name="description" 
                            rows="4"
                            class="form-input w-full"
                            placeholder="Beskriv företagets tjänster och specialiteter..."
                        >{{ old('description', $company->description) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Max 2000 tecken</p>
                    </div>

                    <!-- Org Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Organisationsnummer <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="company_org_number" 
                            value="{{ old('company_org_number', $company->company_org_number) }}"
                            class="form-input w-full"
                            required
                        >
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">📧 Kontaktinformation</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Company Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Företags-epost <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="company_email" 
                                value="{{ old('company_email', $company->company_email) }}"
                                class="form-input w-full"
                                required
                            >
                        </div>

                        <!-- Company Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telefon <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="company_number" 
                                value="{{ old('company_number', $company->company_number) }}"
                                class="form-input w-full"
                                required
                            >
                        </div>
                    </div>

                    <!-- Website -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Webbplats
                        </label>
                        <input 
                            type="url" 
                            name="site" 
                            value="{{ old('site', $company->site) }}"
                            class="form-input w-full"
                            placeholder="https://example.com"
                        >
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">📍 Adressinformation</h3>
                </div>
                <div class="card-body space-y-4">
                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Gatuadress
                        </label>
                        <input 
                            type="text" 
                            name="address" 
                            value="{{ old('address', $company->address) }}"
                            class="form-input w-full"
                            placeholder="Storgatan 1"
                        >
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Stad
                            </label>
                            <input 
                                type="text" 
                                name="city" 
                                value="{{ old('city', $company->city) }}"
                                class="form-input w-full"
                                placeholder="Stockholm"
                            >
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Postnummer
                            </label>
                            <input 
                                type="text" 
                                name="postal_code" 
                                value="{{ old('postal_code', $company->postal_code) }}"
                                class="form-input w-full"
                                placeholder="123 45"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Credentials -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">🔐 Inloggningsuppgifter</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- User Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Inloggnings-epost <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email', $company->user->email) }}"
                                class="form-input w-full"
                                required
                            >
                        </div>

                        <!-- User Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Användartelefon
                            </label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone', $company->user->phone) }}"
                                class="form-input w-full"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nytt Lösenord
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-input w-full"
                            placeholder="Lämna tomt för att behålla nuvarande"
                        >
                        <p class="text-xs text-gray-500 mt-1">Minst 8 tecken</p>
                    </div>
                </div>
            </div>

            <!-- Services & Cities -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Services -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold">🛠️ Tjänster</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($services as $service)
                                <label class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="services[]" 
                                        value="{{ $service->id }}"
                                        {{ in_array($service->id, old('services', $company->services->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="form-checkbox text-blue-600"
                                    >
                                    <span class="text-sm">{{ $service->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Cities -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold">🏙️ Städer</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($cities as $city)
                                <label class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="cities[]" 
                                        value="{{ $city->id }}"
                                        {{ in_array($city->id, old('cities', $company->cities->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="form-checkbox text-blue-600"
                                    >
                                    <span class="text-sm">{{ $city->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Logo -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">🖼️ Logotyp</h3>
                </div>
                <div class="card-body">
                    @if($company->logo)
                        <div class="mb-4">
                            <img src="{{ Storage::url($company->logo) }}" alt="Current Logo" class="w-full h-48 object-cover rounded-lg border-2 border-gray-200">
                            <p class="text-xs text-gray-500 mt-2 text-center">Nuvarande logotyp</p>
                        </div>
                    @else
                        <div class="mb-4 bg-gradient-to-br from-blue-600 to-purple-600 h-48 rounded-lg flex items-center justify-center text-white text-4xl font-bold">
                            {{ $company->company_name ? strtoupper(substr($company->company_name, 0, 2)) : '🏢' }}
                        </div>
                    @endif
                    <label class="block">
                        <span class="sr-only">Välj logotyp</span>
                        <input 
                            type="file" 
                            name="company_logo" 
                            accept="image/*"
                            class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100"
                        >
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Max 2MB, JPG/PNG</p>
                </div>
            </div>

            <!-- Status -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">⚙️ Status</h3>
                </div>
                <div class="card-body">
                    <select name="status" class="form-input w-full">
                        <option value="active" {{ old('status', $company->status) === 'active' ? 'selected' : '' }}>
                            ✅ Aktiv
                        </option>
                        <option value="pending" {{ old('status', $company->status) === 'pending' ? 'selected' : '' }}>
                            ⏳ Väntande
                        </option>
                        <option value="inactive" {{ old('status', $company->status) === 'inactive' ? 'selected' : '' }}>
                            ❌ Inaktiv
                        </option>
                    </select>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card bg-gradient-to-br from-blue-50 to-purple-50">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">📊 Statistik</h3>
                </div>
                <div class="card-body space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Bokningar:</span>
                        <span class="font-semibold">{{ $company->bookings->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Recensioner:</span>
                        <span class="font-semibold">{{ $company->review_count }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Genomsnittligt betyg:</span>
                        <span class="font-semibold">
                            @if($company->review_count > 0)
                                ⭐ {{ number_format($company->review_average, 1) }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t">
                        <span class="text-sm text-gray-600">Medlem sedan:</span>
                        <span class="font-semibold text-sm">{{ $company->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body space-y-3">
                    <button type="submit" class="btn btn-primary w-full">
                        💾 Uppdatera Företag
                    </button>
                    <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-secondary w-full text-center">
                        👁️ Visa Företag
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Delete Form (Separate from Update Form) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <div class="lg:col-start-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Är du säker på att du vill radera detta företag? Denna åtgärd kan inte ångras.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full">
                        🗑️ Radera Företag
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
