@extends('layouts.company')

@section('title', 'Redigera Tidslucka')

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
                    <h2 class="text-2xl font-bold text-gray-900">Redigera Tidslucka</h2>
                    <p class="text-gray-600">Uppdatera tidslucka för {{ $slotTime->service->name }} i {{ $slotTime->city->name }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('company.slot-times.update', $slotTime) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
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
                                <option value="{{ $service->id }}" {{ (old('service_id', $slotTime->service_id) == $service->id) ? 'selected' : '' }}>
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
                                <option value="{{ $city->id }}" {{ (old('city_id', $slotTime->city_id) == $city->id) ? 'selected' : '' }}>
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
                               value="{{ old('date', $slotTime->date->format('Y-m-d')) }}"
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
                               value="{{ old('start_time', $slotTime->start_time) }}"
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
                               value="{{ old('end_time', $slotTime->end_time) }}"
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
                               value="{{ old('capacity', $slotTime->capacity) }}"
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
                                   {{ old('is_available', $slotTime->is_available) ? 'checked' : '' }}
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

                <!-- Booking Status -->
                @if($slotTime->booked_count > 0)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    ⚠️ Bokningar finns
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Denna tidslucka har {{ $slotTime->booked_count }} bokning(ar). Var försiktig när du ändrar kapacitet eller tider.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('company.slot-times.index') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Avbryt
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span class="mr-2">💾</span> Uppdatera tidslucka
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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
