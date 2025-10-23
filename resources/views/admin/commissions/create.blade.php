@extends('layouts.admin')

@section('title', 'Skapa Provisionsinställning')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">💰 Ny Provisionsinställning</h2>
                <p class="text-gray-600 mt-1">Konfigurera provision för ett företag</p>
            </div>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
                ← Tillbaka till Företag
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.commissions.store') }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                <!-- Company Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Företag <span class="text-red-500">*</span>
                    </label>
                    @if(request('company_id'))
                        @php
                            $selectedCompany = \App\Models\Company::find(request('company_id'));
                        @endphp
                        @if($selectedCompany)
                            <input type="hidden" name="company_id" value="{{ $selectedCompany->id }}">
                            <div class="p-4 bg-purple-50 border-2 border-purple-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($selectedCompany->logo)
                                            <img src="{{ Storage::url($selectedCompany->logo) }}" alt="{{ $selectedCompany->company_name }}" class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                                                {{ substr($selectedCompany->company_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-semibold text-gray-900">{{ $selectedCompany->company_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $selectedCompany->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <select name="company_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Välj ett företag</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }} - {{ $company->email }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    @error('company_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Commission Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Provisionstyp <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ old('commission_type', 'percentage') === 'percentage' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="commission_type" value="percentage" 
                                   {{ old('commission_type', 'percentage') === 'percentage' ? 'checked' : '' }}
                                   class="text-purple-600 focus:ring-purple-500" 
                                   onchange="toggleCommissionFields()">
                            <span class="ml-3">
                                <span class="block font-semibold text-gray-900">📊 Procent</span>
                                <span class="text-sm text-gray-500">Provision baserat på bokningsbelopp (t.ex. 15%)</span>
                            </span>
                        </label>
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ old('commission_type') === 'fixed' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="commission_type" value="fixed" 
                                   {{ old('commission_type') === 'fixed' ? 'checked' : '' }}
                                   class="text-purple-600 focus:ring-purple-500"
                                   onchange="toggleCommissionFields()">
                            <span class="ml-3">
                                <span class="block font-semibold text-gray-900">💵 Fast belopp</span>
                                <span class="text-sm text-gray-500">Fast provision per bokning (t.ex. 100 kr)</span>
                            </span>
                        </label>
                    </div>
                    @error('commission_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Percentage Rate -->
                <div id="percentage_field" class="{{ old('commission_type', 'percentage') === 'percentage' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Provisionsprocent <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            name="commission_rate" 
                            value="{{ old('commission_rate', '15.00') }}"
                            step="0.01"
                            min="0"
                            max="100"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="15.00"
                        >
                        <span class="absolute right-4 top-2.5 text-gray-500">%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Ange provision i procent (0-100)</p>
                    @error('commission_rate')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fixed Amount -->
                <div id="fixed_field" class="{{ old('commission_type') === 'fixed' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fast belopp <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            name="fixed_amount" 
                            value="{{ old('fixed_amount') }}"
                            step="0.01"
                            min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="100.00"
                        >
                        <span class="absolute right-4 top-2.5 text-gray-500">kr</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Ange fast provision i kronor</p>
                    @error('fixed_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                        >
                        <span class="ml-3 text-sm font-medium text-gray-700">
                            Aktiv provisionsinställning
                        </span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Om inaktiverad, tas ingen provision ut för detta företag</p>
                    @error('is_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Anteckningar (valfritt)
                    </label>
                    <textarea 
                        name="notes" 
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        placeholder="Ytterligare information om provisionsinställningen..."
                    >{{ old('notes') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Intern information som syns endast för administratörer</p>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t">
                <a href="{{ route('admin.companies.index') }}" class="text-gray-600 hover:text-gray-900">
                    Avbryt
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg transition">
                    💾 Skapa Provisionsinställning
                </button>
            </div>
        </form>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Information om Provisioner</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Provisioner beräknas automatiskt när en bokning slutförs</li>
                            <li>En utbetalningspost skapas automatiskt för företaget</li>
                            <li>Procent-baserade provisioner beräknas på bokningens slutpris</li>
                            <li>Fasta provisioner dras av oavsett bokningsbelopp</li>
                            <li>Inaktiva provisionsinställningar innebär att ingen provision tas ut</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleCommissionFields() {
    const commissionType = document.querySelector('input[name="commission_type"]:checked');
    const percentageField = document.getElementById('percentage_field');
    const fixedField = document.getElementById('fixed_field');
    
    if (commissionType && commissionType.value === 'percentage') {
        percentageField.classList.remove('hidden');
        fixedField.classList.add('hidden');
    } else if (commissionType && commissionType.value === 'fixed') {
        percentageField.classList.add('hidden');
        fixedField.classList.remove('hidden');
    }
}

// Initialize on page load
toggleCommissionFields();
</script>
@endpush
@endsection

