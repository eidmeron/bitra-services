@extends('layouts.admin')

@section('title', 'Redigera företag')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.companies.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till företag</a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Redigera: {{ $company->user->email }}</h2>

        <form method="POST" action="{{ route('admin.companies.update', $company) }}" enctype="multipart/form-data">
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

            <!-- User Account -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Användaruppgifter</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">E-post (inloggning) *</label>
                        <input type="email" name="email" value="{{ old('email', $company->user->email) }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Telefon</label>
                        <input type="tel" name="phone" value="{{ old('phone', $company->user->phone) }}" class="form-input">
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Nytt lösenord (lämna tomt för att behålla nuvarande)</label>
                        <input type="password" name="password" class="form-input" autocomplete="new-password">
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Företagsinformation</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Företagets e-post *</label>
                        <input type="email" name="company_email" value="{{ old('company_email', $company->company_email) }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Företagstelefon *</label>
                        <input type="text" name="company_number" value="{{ old('company_number', $company->company_number) }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Organisationsnummer *</label>
                        <input type="text" name="company_org_number" value="{{ old('company_org_number', $company->company_org_number) }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Webbplats</label>
                        <input type="url" name="site" value="{{ old('site', $company->site) }}" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Företagslogotyp</label>
                    @if($company->company_logo)
                        <div class="mb-2">
                            <img src="{{ Storage::url($company->company_logo) }}" alt="Logo" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="company_logo" accept="image/*" class="form-input">
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="pending" {{ $company->status === 'pending' ? 'selected' : '' }}>Väntande</option>
                        <option value="active" {{ $company->status === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ $company->status === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>
            </div>

            <!-- Services -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Tjänster</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($services as $service)
                        <label class="flex items-center">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                {{ in_array($service->id, old('services', $company->services->pluck('id')->toArray())) ? 'checked' : '' }} 
                                class="mr-2">
                            <span>{{ $service->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Cities -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Verksamhetsområden</h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($cities as $city)
                        <label class="flex items-center">
                            <input type="checkbox" name="cities[]" value="{{ $city->id }}" 
                                {{ in_array($city->id, old('cities', $company->cities->pluck('id')->toArray())) ? 'checked' : '' }} 
                                class="mr-2">
                            <span>{{ $city->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">📊 Statistik</h4>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Totala bokningar:</p>
                        <p class="font-semibold text-lg">{{ $company->bookings->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Genomsnittligt betyg:</p>
                        <p class="font-semibold text-lg">{{ number_format($company->review_average, 1) }} ⭐</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Antal recensioner:</p>
                        <p class="font-semibold text-lg">{{ $company->review_count }}</p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Uppdatera företag
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

