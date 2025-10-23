@extends('layouts.admin')

@section('title', 'Redigera Provisionsinställning')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">✏️ Redigera Provisionsinställning</h2>
                <p class="text-gray-600 mt-1">Uppdatera provision för {{ $commission->company->company_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.companies.show', $commission->company) }}" class="btn btn-secondary">
                    ← Tillbaka till Företag
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.commissions.update', $commission) }}" method="POST" class="bg-white rounded-xl shadow-lg overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Company Info (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Företag
                    </label>
                    <div class="p-4 bg-gray-50 border-2 border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($commission->company->logo)
                                    <img src="{{ Storage::url($commission->company->logo) }}" alt="{{ $commission->company->company_name }}" class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                                        {{ substr($commission->company->company_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900">{{ $commission->company->company_name }}</p>
                                <p class="text-sm text-gray-600">{{ $commission->company->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commission Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Provisionstyp <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ old('commission_type', $commission->commission_type) === 'percentage' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="commission_type" value="percentage" 
                                   {{ old('commission_type', $commission->commission_type) === 'percentage' ? 'checked' : '' }}
                                   class="text-purple-600 focus:ring-purple-500" 
                                   onchange="toggleCommissionFields()">
                            <span class="ml-3">
                                <span class="block font-semibold text-gray-900">📊 Procent</span>
                                <span class="text-sm text-gray-500">Provision baserat på bokningsbelopp (t.ex. 15%)</span>
                            </span>
                        </label>
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition {{ old('commission_type', $commission->commission_type) === 'fixed' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="commission_type" value="fixed" 
                                   {{ old('commission_type', $commission->commission_type) === 'fixed' ? 'checked' : '' }}
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
                <div id="percentage_field" class="{{ old('commission_type', $commission->commission_type) === 'percentage' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Provisionsprocent <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            name="commission_rate" 
                            value="{{ old('commission_rate', $commission->commission_rate) }}"
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
                <div id="fixed_field" class="{{ old('commission_type', $commission->commission_type) === 'fixed' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fast belopp <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="number" 
                            name="fixed_amount" 
                            value="{{ old('fixed_amount', $commission->fixed_amount) }}"
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
                            {{ old('is_active', $commission->is_active) ? 'checked' : '' }}
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
                    >{{ old('notes', $commission->notes) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Intern information som syns endast för administratörer</p>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Metadata -->
                <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600 space-y-1">
                    <p><strong>Skapad:</strong> {{ $commission->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Senast uppdaterad:</strong> {{ $commission->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t">
                <form action="{{ route('admin.commissions.destroy', $commission) }}" method="POST" onsubmit="return confirm('Är du säker på att du vill ta bort denna provisionsinställning?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                        🗑️ Ta bort
                    </button>
                </form>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg transition">
                    💾 Uppdatera Provisionsinställning
                </button>
            </div>
        </form>
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
