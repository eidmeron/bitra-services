@extends('layouts.company')

@section('title', 'Skapa Tidslucka')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('company.slot-times.index') }}" 
                   class="text-gray-400 hover:text-gray-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Skapa Ny Tidslucka</h2>
                    <p class="text-gray-600">Lägg till en ny tillgänglig tidslucka för bokningar</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('company.slot-times.store') }}" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Service -->
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700">
                            Tjänst <span class="text-red-500">*</span>
                        </label>
                        <select name="service_id" id="service_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                            <option value="">Välj tjänst</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city_id" class="block text-sm font-medium text-gray-700">
                            Stad <span class="text-red-500">*</span>
                        </label>
                        <select name="city_id" id="city_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                            <option value="">Välj stad</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">
                            Datum <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required
                               value="{{ old('date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">
                            Starttid <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" required
                               value="{{ old('start_time') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">
                            Sluttid <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" required
                               value="{{ old('end_time') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Capacity -->
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">
                            Kapacitet <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="capacity" id="capacity" required
                               value="{{ old('capacity', 1) }}"
                               min="1" max="100"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Antal bokningar som kan göras för denna tidslucka</p>
                        @error('capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Availability -->
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_available" id="is_available" value="1"
                                   {{ old('is_available', true) ? 'checked' : '' }}
                                   class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_available" class="font-medium text-gray-700">
                                Tillgänglig för bokning
                            </label>
                            <p class="text-gray-500">Avmarkera för att göra tidsluckan otillgänglig</p>
                        </div>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                💡 Tips för tidsluckor
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Skapa tidsluckor för de dagar och tider du är tillgänglig</li>
                                    <li>Ange kapacitet baserat på hur många bokningar du kan hantera samtidigt</li>
                                    <li>Du kan alltid redigera eller ta bort tidsluckor senare</li>
                                    <li>Använd "Skapa flera" för att snabbt skapa tidsluckor för flera dagar</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('company.slot-times.index') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Avbryt
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span class="mr-2">💾</span> Skapa tidslucka
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-update end time when start time changes
document.getElementById('start_time').addEventListener('change', function() {
    const startTime = this.value;
    const endTimeInput = document.getElementById('end_time');
    
    if (startTime && !endTimeInput.value) {
        // Set end time to 1 hour after start time
        const [hours, minutes] = startTime.split(':');
        const endHours = parseInt(hours) + 1;
        const endTime = endHours.toString().padStart(2, '0') + ':' + minutes;
        endTimeInput.value = endTime;
    }
});

// Validate end time is after start time
document.getElementById('end_time').addEventListener('change', function() {
    const startTime = document.getElementById('start_time').value;
    const endTime = this.value;
    
    if (startTime && endTime && endTime <= startTime) {
        alert('Sluttiden måste vara efter starttiden');
        this.value = '';
    }
});
</script>
@endsection
