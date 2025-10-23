@extends('layouts.company')

@section('title', 'Företagsprofil')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">🏢 Företagsprofil</h2>
    <p class="text-gray-600 mt-1">Hantera ditt företags information och inställningar</p>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
        <div class="flex items-center">
            <span class="mr-2">✅</span>
            <p>{{ session('success') }}</p>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Grundläggande Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Company Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Företagsnamn <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="company_name" 
                            value="{{ old('company_name', $company->company_name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Beskriv ditt företags tjänster och specialiteter..."
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
                            name="org_number" 
                            id="org_number"
                            value="{{ old('org_number', $company->company_org_number) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="XXXXXX-XXXX"
                            maxlength="11"
                            required
                        >
                        <p class="text-xs text-gray-500 mt-1">Format: XXXXXX-XXXX (t.ex. 123456-7890)</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">📧 Kontaktinformation</h3>
                </div>
                <div class="p-6 space-y-4">
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
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="https://example.com"
                        >
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">📍 Adressinformation</h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Gatuadress
                        </label>
                        <input 
                            type="text" 
                            name="address" 
                            value="{{ old('address', $company->address) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Storgatan 1"
                        >
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Postal Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Postnummer
                            </label>
                            <input 
                                type="text" 
                                name="postal_code" 
                                value="{{ old('postal_code', $company->postal_code) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="12345"
                            >
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ort
                            </label>
                            <input 
                                type="text" 
                                name="city" 
                                value="{{ old('city', $company->city) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Stockholm"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payout Information -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">💰 Utbetalningsinformation</h3>
                    <p class="text-sm text-gray-600 mt-1">Konfigurera hur du vill ta emot betalningar</p>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Payout Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Utbetalningsmetod
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ old('payout_method', $company->payout_method) === 'swish' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="payout_method" value="swish" 
                                       {{ old('payout_method', $company->payout_method) === 'swish' ? 'checked' : '' }}
                                       class="text-purple-600 focus:ring-purple-500" 
                                       onchange="togglePayoutFields()">
                                <span class="ml-3">
                                    <span class="block font-semibold text-gray-900">Swish</span>
                                    <span class="text-sm text-gray-500">Snabb och enkel utbetalning</span>
                                </span>
                            </label>
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ old('payout_method', $company->payout_method) === 'bank_account' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <input type="radio" name="payout_method" value="bank_account" 
                                       {{ old('payout_method', $company->payout_method) === 'bank_account' ? 'checked' : '' }}
                                       class="text-purple-600 focus:ring-purple-500"
                                       onchange="togglePayoutFields()">
                                <span class="ml-3">
                                    <span class="block font-semibold text-gray-900">Bankkonto</span>
                                    <span class="text-sm text-gray-500">Traditionell bankutbetalning</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Swish Fields -->
                    <div id="swish_fields" class="space-y-4 {{ old('payout_method', $company->payout_method) === 'swish' ? '' : 'hidden' }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Swish-nummer <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="swish_number" 
                                value="{{ old('swish_number', $company->swish_number) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="070-123 45 67"
                            >
                            <p class="text-xs text-gray-500 mt-1">Mobilnummer kopplat till Swish</p>
                        </div>
                    </div>

                    <!-- Bank Account Fields -->
                    <div id="bank_account_fields" class="space-y-4 {{ old('payout_method', $company->payout_method) === 'bank_account' ? '' : 'hidden' }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Banknamn <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="bank_name" 
                                value="{{ old('bank_name', $company->bank_name) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="t.ex. Swedbank, SEB, Nordea"
                            >
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Clearingnummer <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="clearing_number" 
                                    value="{{ old('clearing_number', $company->clearing_number) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="1234"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kontonummer <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="account_number" 
                                    value="{{ old('account_number', $company->account_number) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="123 456 789"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Payout Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Anteckningar (valfritt)
                        </label>
                        <textarea 
                            name="payout_notes" 
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Ytterligare information för utbetalningar..."
                        >{{ old('payout_notes', $company->payout_notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Company Logo -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">🖼️ Logotyp</h3>
                </div>
                <div class="p-6">
                    @if($company->company_logo)
                        <div class="mb-4">
                            <img src="{{ Storage::url($company->company_logo) }}" alt="Company Logo" class="w-full h-48 object-contain bg-gray-100 rounded-lg">
                        </div>
                    @else
                        <div class="mb-4 bg-gray-100 rounded-lg h-48 flex items-center justify-center">
                            <span class="text-gray-400 text-sm">Ingen logotyp uppladdad</span>
                        </div>
                    @endif
                    
                    <label class="block">
                        <span class="sr-only">Välj fil</span>
                        <input 
                            type="file" 
                            name="logo" 
                            accept="image/*"
                            class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-green-50 file:text-green-700
                                hover:file:bg-green-100"
                        >
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Max 2MB, PNG eller JPG</p>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">📊 Status</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status</span>
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'suspended' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'active' => 'Aktiv',
                                'pending' => 'Väntar',
                                'suspended' => 'Inaktiverad',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$company->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$company->status] ?? ucfirst($company->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Medlem sedan</span>
                        <span class="text-sm font-semibold">{{ $company->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 space-y-3">
                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-blue-700 transition-all shadow-md">
                        💾 Spara ändringar
                    </button>
                    <a href="{{ route('company.dashboard') }}" class="block w-full text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                        ← Tillbaka till Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
// Auto-format organization number
document.getElementById('org_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, ''); // Remove all non-digits
    
    if (value.length > 6) {
        value = value.substring(0, 6) + '-' + value.substring(6, 10);
    }
    
    e.target.value = value;
});

// Validate on submit
document.querySelector('form').addEventListener('submit', function(e) {
    const orgNumber = document.getElementById('org_number').value;
    const pattern = /^\d{6}-\d{4}$/;
    
    if (!pattern.test(orgNumber)) {
        e.preventDefault();
        alert('Organisationsnummer måste vara i formatet XXXXXX-XXXX (t.ex. 123456-7890)');
        document.getElementById('org_number').focus();
    }
});

// Toggle payout fields based on selected method
function togglePayoutFields() {
    const payoutMethod = document.querySelector('input[name="payout_method"]:checked');
    const swishFields = document.getElementById('swish_fields');
    const bankFields = document.getElementById('bank_account_fields');
    
    if (payoutMethod && payoutMethod.value === 'swish') {
        swishFields.classList.remove('hidden');
        bankFields.classList.add('hidden');
    } else if (payoutMethod && payoutMethod.value === 'bank_account') {
        swishFields.classList.add('hidden');
        bankFields.classList.remove('hidden');
    } else {
        swishFields.classList.add('hidden');
        bankFields.classList.add('hidden');
    }
}

// Initialize on page load
togglePayoutFields();
</script>
@endpush
@endsection

