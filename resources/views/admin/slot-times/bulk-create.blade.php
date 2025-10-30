@extends('layouts.admin')

@section('title', 'Massskapande av Tidsluckor')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">📅</span>
                    Massskapande av Tidsluckor
                </h1>
                <p class="text-gray-600 mt-2">Skapa flera tidsluckor samtidigt för en tjänst och stad</p>
            </div>
            <a href="{{ route('admin.slot-times.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                ← Tillbaka till tidsluckor
            </a>
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

        <form method="POST" action="{{ route('admin.slot-times.bulk-store') }}">
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
                    <p class="text-sm text-gray-500 mt-1">Lämna tomt för att göra tidsluckorna tillgängliga för alla företag</p>
                    @error('company_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Från datum *</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Till datum *</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                               min="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Days of Week -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Veckodagar *</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @php
                            $days = [
                                1 => 'Måndag',
                                2 => 'Tisdag', 
                                3 => 'Onsdag',
                                4 => 'Torsdag',
                                5 => 'Fredag',
                                6 => 'Lördag',
                                0 => 'Söndag'
                            ];
                        @endphp
                        @foreach($days as $dayNumber => $dayName)
                            <label class="flex items-center">
                                <input type="checkbox" name="days_of_week[]" value="{{ $dayNumber }}"
                                       {{ in_array($dayNumber, old('days_of_week', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-900">{{ $dayName }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('days_of_week')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity, Price Multiplier and Availability -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Kapacitet *</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 1) }}" required
                               min="1" max="100"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Antal platser som kan bokas per tidslucka</p>
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

                <!-- Preview -->
                <div id="preview-section" class="hidden">
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Förhandsvisning</h3>
                        <div id="preview-content" class="bg-gray-50 rounded-lg p-4">
                            <!-- Preview content will be generated by JavaScript -->
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
                <div class="flex space-x-3">
                    <button type="button" id="preview-btn" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        👁️ Förhandsvisning
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        Skapa Tidsluckor
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Massskapande av Tidsluckor</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• Välj datumintervall och veckodagar för att skapa tidsluckor</p>
                    <p>• Systemet skapar automatiskt en tidslucka för varje vald veckodag inom datumintervallet</p>
                    <p>• Befintliga tidsluckor med samma datum och tid hoppas över</p>
                    <p>• Använd förhandsvisningen för att se hur många tidsluckor som kommer att skapas</p>
                    <p>• Perfekt för att sätta upp regelbundna tidsluckor för en tjänst</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const daysCheckboxes = document.querySelectorAll('input[name="days_of_week[]"]');
    const previewBtn = document.getElementById('preview-btn');
    const previewSection = document.getElementById('preview-section');
    const previewContent = document.getElementById('preview-content');
    
    // Set default values
    if (!startDateInput.value) {
        startDateInput.value = new Date().toISOString().split('T')[0];
    }
    if (!endDateInput.value) {
        const nextWeek = new Date();
        nextWeek.setDate(nextWeek.getDate() + 7);
        endDateInput.value = nextWeek.toISOString().split('T')[0];
    }
    if (!startTimeInput.value) {
        startTimeInput.value = '09:00';
    }
    if (!endTimeInput.value) {
        endTimeInput.value = '10:00';
    }
    
    // Validate end date is after start date
    function validateDates() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (startDate && endDate && startDate > endDate) {
            endDateInput.setCustomValidity('Slutdatum måste vara efter startdatum');
        } else {
            endDateInput.setCustomValidity('');
        }
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
    
    startDateInput.addEventListener('change', validateDates);
    endDateInput.addEventListener('change', validateDates);
    startTimeInput.addEventListener('change', validateTimes);
    endTimeInput.addEventListener('change', validateTimes);
    
    // Preview functionality
    previewBtn.addEventListener('click', function() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        const selectedDays = Array.from(daysCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => parseInt(cb.value));
        
        if (!startDate || !endDate || !startTime || !endTime || selectedDays.length === 0) {
            alert('Fyll i alla obligatoriska fält för att se förhandsvisning.');
            return;
        }
        
        // Calculate dates
        const start = new Date(startDate);
        const end = new Date(endDate);
        const dates = [];
        
        const current = new Date(start);
        while (current <= end) {
            if (selectedDays.includes(current.getDay())) {
                dates.push(new Date(current));
            }
            current.setDate(current.getDate() + 1);
        }
        
        // Generate preview
        let previewHtml = `
            <div class="mb-4">
                <h4 class="font-semibold text-gray-900">Sammanfattning:</h4>
                <p class="text-sm text-gray-600">${dates.length} tidsluckor kommer att skapas</p>
                <p class="text-sm text-gray-600">Tid: ${startTime} - ${endTime}</p>
            </div>
            <div class="max-h-60 overflow-y-auto">
                <h4 class="font-semibold text-gray-900 mb-2">Datum som kommer att skapas:</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
        `;
        
        dates.forEach(date => {
            const dayNames = ['Söndag', 'Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag'];
            const dayName = dayNames[date.getDay()];
            const dateStr = date.toISOString().split('T')[0];
            previewHtml += `
                <div class="bg-white border border-gray-200 rounded p-2 text-sm">
                    <div class="font-medium">${dayName}</div>
                    <div class="text-gray-600">${dateStr}</div>
                </div>
            `;
        });
        
        previewHtml += `
                </div>
            </div>
        `;
        
        previewContent.innerHTML = previewHtml;
        previewSection.classList.remove('hidden');
    });
});
</script>
@endsection
