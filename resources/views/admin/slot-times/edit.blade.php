@extends('layouts.admin')

@section('title', 'Redigera Tidslucka')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">✏️</span>
                    Redigera Tidslucka
                </h1>
                <p class="text-gray-600 mt-2">Modifiera tidslucka #{{ $slotTime->id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.slot-times.show', $slotTime) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    👁️ Visa
                </a>
                <a href="{{ route('admin.slot-times.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ← Tillbaka
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 m-6">
                <div class="flex">
                    <div class="text-red-500 text-xl mr-3">⚠️</div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800">Valideringsfel</h3>
                        <ul class="text-red-700 mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 m-6">
                <div class="flex">
                    <div class="text-red-500 text-xl mr-3">❌</div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800">Fel</h3>
                        <p class="text-red-700 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 m-6">
                <div class="flex">
                    <div class="text-green-500 text-xl mr-3">✅</div>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Framgång</h3>
                        <p class="text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.slot-times.update', $slotTime) }}">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-6">
                <!-- Service and City Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Tjänst *</label>
                        <select name="service_id" id="service_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Välj tjänst</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id', $slotTime->service_id) == $service->id ? 'selected' : '' }}>
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
                                <option value="{{ $city->id }}" {{ old('city_id', $slotTime->city_id) == $city->id ? 'selected' : '' }}>
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
                            <option value="{{ $company->id }}" {{ old('company_id', $slotTime->company_id) == $company->id ? 'selected' : '' }}>
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
                        <input type="date" name="date" id="date" value="{{ old('date', $slotTime->date->format('Y-m-d')) }}" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Starttid *</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $slotTime->start_time) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Sluttid *</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $slotTime->end_time) }}" required
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
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $slotTime->capacity) }}" required
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
                               value="{{ old('price_multiplier', $slotTime->price_multiplier ?? 1.00) }}" 
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
                                   {{ old('is_available', $slotTime->is_available) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_available" class="ml-2 block text-sm text-gray-900">
                                Tillgänglig för bokning
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Current Booking Status -->
                @if($slotTime->booked_count > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="text-yellow-500 text-xl mr-3">⚠️</div>
                        <div>
                            <h4 class="text-sm font-semibold text-yellow-800">Bokningar finns</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                Denna tidslucka har {{ $slotTime->booked_count }} bokningar. 
                                Ändringar kan påverka befintliga bokningar.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.slot-times.show', $slotTime) }}" 
                       class="text-gray-600 hover:text-gray-800 font-medium">
                        Avbryt
                    </a>
                    @if($slotTime->booked_count === 0)
                        <form method="POST" action="{{ route('admin.slot-times.destroy', $slotTime) }}" class="inline" 
                              onsubmit="return confirm('Är du säker på att du vill radera denna tidslucka?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                Radera
                            </button>
                        </form>
                    @endif
                </div>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Uppdatera Tidslucka
                </button>
            </div>
        </form>
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Redigera Tidslucka</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• Du kan ändra alla detaljer för tidsluckan</p>
                    <p>• Om tidsluckan har bokningar, var försiktig med ändringar</p>
                    <p>• Kapaciteten kan inte sättas lägre än antal befintliga bokningar</p>
                    <p>• Datum och tid kan ändras även om det finns bokningar</p>
                    <p>• Inaktivera tidsluckan för att stoppa nya bokningar</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
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
    
    // Validate capacity is not less than booked count
    const capacityInput = document.getElementById('capacity');
    const bookedCount = {{ $slotTime->booked_count }};
    
    function validateCapacity() {
        const capacity = parseInt(capacityInput.value);
        
        if (capacity < bookedCount) {
            capacityInput.setCustomValidity(`Kapaciteten kan inte vara mindre än ${bookedCount} (befintliga bokningar)`);
        } else {
            capacityInput.setCustomValidity('');
        }
    }
    
    capacityInput.addEventListener('change', validateCapacity);
    validateCapacity(); // Run on page load
    
    // Debug form submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const formData = new FormData(form);
            console.log('Form submission data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            // Check if price_multiplier is being submitted
            const priceMultiplier = formData.get('price_multiplier');
            console.log('Price multiplier value:', priceMultiplier);
        });
    }
});
</script>
@endsection
