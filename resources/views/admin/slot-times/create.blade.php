@extends('layouts.admin')

@section('title', 'Skapa Tidslucka')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">➕</span>
                    Skapa Tidslucka
                </h1>
                <p class="text-gray-600 mt-2">Lägg till en ny tidslucka för bokningar</p>
            </div>
            <a href="{{ route('admin.slot-times.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                ← Tillbaka till tidsluckor
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <form method="POST" action="{{ route('admin.slot-times.store') }}">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Service and City Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Tjänst *</label>
                        <select name="service_id" id="service_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Välj tjänst</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">Stad *</label>
                        <select name="city_id" id="city_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Välj stad</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Company Selection (Optional) -->
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-2">Företag (valfritt)</label>
                    <select name="company_id" id="company_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Alla företag (öppet för alla)</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Lämna tomt för att göra tidsluckan tillgänglig för alla företag</p>
                    @error('company_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Datum *</label>
                        <input type="date" name="date" id="date" value="{{ old('date') }}" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Starttid *</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Sluttid *</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Capacity, Price Multiplier and Availability -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Kapacitet *</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 1) }}" required
                               min="1" max="100"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Antal platser som kan bokas</p>
                        @error('capacity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="price_multiplier" class="block text-sm font-medium text-gray-700 mb-2">Prismultiplikator</label>
                        <input type="number" name="price_multiplier" id="price_multiplier" 
                               value="{{ old('price_multiplier', 1.00) }}" 
                               step="0.01" min="0.01" max="10.00"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Multiplikator för priset (1.00 = normalpris)</p>
                        @error('price_multiplier')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_available" id="is_available" value="1" 
                                   {{ old('is_available', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_available" class="ml-2 block text-sm text-gray-900">
                                Tillgänglig för bokning
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                <a href="{{ route('admin.slot-times.index') }}" 
                   class="text-gray-600 hover:text-gray-800 font-medium">
                    Avbryt
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Skapa Tidslucka
                </button>
            </div>
        </form>
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Om Tidsluckor</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• Tidsluckor definierar när en tjänst kan bokas i en specifik stad</p>
                    <p>• Du kan skapa tidsluckor för specifika företag eller för alla företag</p>
                    <p>• Kapaciteten anger hur många bokningar som kan göras för denna tidslucka</p>
                    <p>• Prismultiplikatorn låter dig sätta olika priser för olika tidsluckor</p>
                    <p>• Tidsluckor kan aktiveras/inaktiveras efter behov</p>
                    <p>• Använd "Massskapande" för att skapa flera tidsluckor samtidigt</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    // Set default times
    if (!startTimeInput.value) {
        startTimeInput.value = '09:00';
    }
    if (!endTimeInput.value) {
        endTimeInput.value = '10:00';
    }
    
    // Validate end time is after start time
    function validateTimes() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        
        if (startTime && endTime && startTime >= endTime) {
            endTimeInput.setCustomValidity('Sluttid måste vara efter starttid');
        } else {
            endTimeInput.setCustomValidity('');
        }
    }
    
    startTimeInput.addEventListener('change', validateTimes);
    endTimeInput.addEventListener('change', validateTimes);
    
    // Set minimum date to today
    const dateInput = document.getElementById('date');
    if (!dateInput.value) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
});
</script>
@endsection
