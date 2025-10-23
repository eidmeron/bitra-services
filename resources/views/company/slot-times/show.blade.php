@extends('layouts.company')

@section('title', 'Tidslucka Detaljer')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('company.slot-times.index') }}" 
                       class="text-gray-400 hover:text-gray-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Tidslucka Detaljer</h2>
                        <p class="text-gray-600">{{ $slotTime->service->name }} i {{ $slotTime->city->name }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('company.slot-times.edit', $slotTime) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="mr-2">✏️</span> Redigera
                    </a>
                    <form method="POST" action="{{ route('company.slot-times.destroy', $slotTime) }}" 
                          class="inline" 
                          onsubmit="return confirm('Är du säker på att du vill ta bort denna tidslucka?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                {{ $slotTime->booked_count > 0 ? 'disabled' : '' }}>
                            <span class="mr-2">🗑️</span> Ta bort
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">📋 Tidslucka Information</h3>
                    </div>
                    <div class="px-6 py-4 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tjänst</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->service->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Stad</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->city->name }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Datum</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->date->format('Y-m-d') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Starttid</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->start_time }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Sluttid</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->end_time }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Kapacitet</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->capacity }} bokningar</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Status</label>
                                <div class="mt-1">
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
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Bokningar</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->booked_count }} / {{ $slotTime->capacity }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tillgängliga platser</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->capacity - $slotTime->booked_count }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Skapad</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Senast uppdaterad</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bookings -->
                @if($slotTime->booked_count > 0)
                    <div class="mt-6 bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">📋 Bokningar för denna tidslucka</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="text-center py-8">
                                <div class="text-4xl mb-4">📋</div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Bokningar kommer snart</h4>
                                <p class="text-gray-500">Bokningshantering för tidsluckor kommer att implementeras i nästa version.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">⚡ Snabbåtgärder</h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <a href="{{ route('company.slot-times.edit', $slotTime) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span class="mr-2">✏️</span> Redigera tidslucka
                        </a>
                        
                        @if($slotTime->is_available)
                            <form method="POST" action="{{ route('company.slot-times.update', $slotTime) }}" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="service_id" value="{{ $slotTime->service_id }}">
                                <input type="hidden" name="city_id" value="{{ $slotTime->city_id }}">
                                <input type="hidden" name="date" value="{{ $slotTime->date->format('Y-m-d') }}">
                                <input type="hidden" name="start_time" value="{{ $slotTime->start_time }}">
                                <input type="hidden" name="end_time" value="{{ $slotTime->end_time }}">
                                <input type="hidden" name="capacity" value="{{ $slotTime->capacity }}">
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <span class="mr-2">⏸️</span> Gör otillgänglig
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('company.slot-times.update', $slotTime) }}" class="w-full">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="service_id" value="{{ $slotTime->service_id }}">
                                <input type="hidden" name="city_id" value="{{ $slotTime->city_id }}">
                                <input type="hidden" name="date" value="{{ $slotTime->date->format('Y-m-d') }}">
                                <input type="hidden" name="start_time" value="{{ $slotTime->start_time }}">
                                <input type="hidden" name="end_time" value="{{ $slotTime->end_time }}">
                                <input type="hidden" name="capacity" value="{{ $slotTime->capacity }}">
                                <input type="hidden" name="is_available" value="1">
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <span class="mr-2">▶️</span> Gör tillgänglig
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">📊 Statistik</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Utnyttjningsgrad</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $slotTime->capacity > 0 ? round(($slotTime->booked_count / $slotTime->capacity) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" 
                                 style="width: {{ $slotTime->capacity > 0 ? ($slotTime->booked_count / $slotTime->capacity) * 100 : 0 }}%"></div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Tillgängliga platser</span>
                            <span class="text-sm font-medium text-gray-900">{{ $slotTime->capacity - $slotTime->booked_count }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Total kapacitet</span>
                            <span class="text-sm font-medium text-gray-900">{{ $slotTime->capacity }}</span>
                        </div>
                    </div>
                </div>

                <!-- Service Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">🔧 Tjänstinformation</h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tjänstnamn</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $slotTime->service->name }}</p>
                        </div>
                        
                        @if($slotTime->service->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Beskrivning</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $slotTime->service->description }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Grundpris</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($slotTime->service->base_price, 0) }} kr</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
