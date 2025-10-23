@extends('layouts.company')

@section('title', 'Tidsluckor')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    ⏰ Tidsluckor
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Hantera dina tillgängliga tidsluckor för bokningar
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <a href="{{ route('company.slot-times.bulk.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="mr-2">📅</span> Skapa flera
                </a>
                <a href="{{ route('company.slot-times.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <span class="mr-2">➕</span> Ny tidslucka
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700">Tjänst</label>
                    <select name="service_id" id="service_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="">Alla tjänster</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="city_id" class="block text-sm font-medium text-gray-700">Stad</label>
                    <select name="city_id" id="city_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                        <option value="">Alla städer</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">Från datum</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        🔍 Filtrera
                    </button>
                </div>
            </form>
        </div>

        <!-- Slot Times Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @if($slotTimes->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($slotTimes as $slotTime)
                        <li>
                            <div class="px-4 py-4 flex items-center justify-between hover:bg-gray-50">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($slotTime->is_available && $slotTime->hasAvailableSlots())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✅ Tillgänglig
                                            </span>
                                        @elseif($slotTime->is_available)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                ⚠️ Fullbokad
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ❌ Inte tillgänglig
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $slotTime->service->name }}
                                            </p>
                                            <span class="ml-2 text-sm text-gray-500">•</span>
                                            <p class="ml-2 text-sm text-gray-500">
                                                {{ $slotTime->city->name }}
                                            </p>
                                        </div>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <span class="mr-4">📅 {{ $slotTime->date->format('Y-m-d') }}</span>
                                            <span class="mr-4">🕐 {{ $slotTime->start_time }} - {{ $slotTime->end_time }}</span>
                                            <span class="mr-4">👥 {{ $slotTime->booked_count }}/{{ $slotTime->capacity }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('company.slot-times.show', $slotTime) }}" 
                                       class="text-green-600 hover:text-green-900 text-sm font-medium">
                                        Visa
                                    </a>
                                    <a href="{{ route('company.slot-times.edit', $slotTime) }}" 
                                       class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        Redigera
                                    </a>
                                    <form method="POST" action="{{ route('company.slot-times.destroy', $slotTime) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Är du säker på att du vill ta bort denna tidslucka?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 text-sm font-medium"
                                                {{ $slotTime->booked_count > 0 ? 'disabled' : '' }}>
                                            Ta bort
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    {{ $slotTimes->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">⏰</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Inga tidsluckor hittades</h3>
                    <p class="text-gray-500 mb-6">Skapa din första tidslucka för att börja ta emot bokningar.</p>
                    <div class="space-x-3">
                        <a href="{{ route('company.slot-times.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            <span class="mr-2">➕</span> Skapa tidslucka
                        </a>
                        <a href="{{ route('company.slot-times.bulk.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <span class="mr-2">📅</span> Skapa flera
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
