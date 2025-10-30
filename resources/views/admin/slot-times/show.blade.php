@extends('layouts.admin')

@section('title', 'Visa Tidslucka')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">👁️</span>
                    Visa Tidslucka
                </h1>
                <p class="text-gray-600 mt-2">Detaljerad information om tidslucka #{{ $slotTime->id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.slot-times.edit', $slotTime) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ✏️ Redigera
                </a>
                <a href="{{ route('admin.slot-times.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ← Tillbaka
                </a>
            </div>
        </div>
    </div>

    <!-- Slot Time Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="text-2xl mr-3">📅</span>
                        Grundläggande Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Datum</label>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $slotTime->date->format('Y-m-d') }} ({{ $slotTime->date->format('l') }})
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tid</label>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($slotTime->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($slotTime->end_time)->format('H:i') }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tjänst</label>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $slotTime->service->name }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stad</label>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $slotTime->city->name }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Företag</label>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $slotTime->company ? $slotTime->company->company_name : 'Alla företag' }}
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prismultiplikator</label>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ number_format($slotTime->price_multiplier ?? 1.00, 2) }}x
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div>
                                @if($slotTime->booked_count >= $slotTime->capacity)
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Fullbokad
                                    </span>
                                @elseif($slotTime->is_available)
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Tillgänglig
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Otillgänglig
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Capacity Information -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="text-2xl mr-3">👥</span>
                        Kapacitet & Bokningar
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $slotTime->capacity }}</div>
                            <div class="text-sm text-gray-600">Total kapacitet</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold text-orange-600">{{ $slotTime->booked_count }}</div>
                            <div class="text-sm text-gray-600">Bokade platser</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $slotTime->capacity - $slotTime->booked_count }}</div>
                            <div class="text-sm text-gray-600">Tillgängliga platser</div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Bokningsstatus</span>
                            <span>{{ round(($slotTime->booked_count / $slotTime->capacity) * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            @php
                                $percentage = $slotTime->capacity > 0 ? ($slotTime->booked_count / $slotTime->capacity) * 100 : 0;
                            @endphp
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-300" 
                                 style="width: {{ min(100, $percentage) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="text-xl mr-3">⚡</span>
                        Snabbåtgärder
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <form method="POST" action="{{ route('admin.slot-times.toggle-availability', $slotTime) }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full {{ $slotTime->is_available ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            {{ $slotTime->is_available ? '🚫 Inaktivera' : '✅ Aktivera' }}
                        </button>
                    </form>
                    
                    @if($slotTime->booked_count === 0)
                        <form method="POST" action="{{ route('admin.slot-times.destroy', $slotTime) }}" 
                              onsubmit="return confirm('Är du säker på att du vill radera denna tidslucka?')" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                🗑️ Radera
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                            🗑️ Kan inte radera (har bokningar)
                        </button>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-slate-50 border-b">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="text-xl mr-3">ℹ️</span>
                        Metadata
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Skapad</label>
                        <div class="text-sm text-gray-900">{{ $slotTime->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Senast uppdaterad</label>
                        <div class="text-sm text-gray-900">{{ $slotTime->updated_at->format('Y-m-d H:i') }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID</label>
                        <div class="text-sm text-gray-900 font-mono">{{ $slotTime->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
