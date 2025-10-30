@extends('layouts.admin')

@section('title', 'Tidsluckor')
@section('subtitle', 'Hantera tillgängliga tidsluckor för bokningar')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow-lg">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <span class="mr-3">⏰</span>
                Tidsluckor
            </h2>
            <p class="text-gray-600 mt-1">Hantera tillgängliga tidsluckor för alla tjänster och städer</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.slot-times.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                ➕ Lägg till tidslucka
            </a>
            <a href="{{ route('admin.slot-times.bulk-create') }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                📅 Massskapande
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
            <div class="flex">
                <div class="text-green-500 text-xl mr-3">✅</div>
                <div>
                    <h3 class="text-lg font-semibold text-green-800">Framgång</h3>
                    <p class="text-green-700 mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex">
                <div class="text-red-500 text-xl mr-3">❌</div>
                <div>
                    <h3 class="text-lg font-semibold text-red-800">Fel</h3>
                    <p class="text-red-700 mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-blue-100 text-sm font-medium">Totalt</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_slots']) }}</p>
            <p class="text-xs text-blue-100 mt-1">tidsluckor</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-green-100 text-sm font-medium">Tillgängliga</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($stats['available_slots']) }}</p>
            <p class="text-xs text-green-100 mt-1">kan bokas</p>
        </div>
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-orange-100 text-sm font-medium">Fullbokade</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($stats['full_slots']) }}</p>
            <p class="text-xs text-orange-100 mt-1">ingen plats</p>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-red-100 text-sm font-medium">Otillgängliga</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($stats['unavailable_slots']) }}</p>
            <p class="text-xs text-red-100 mt-1">inaktiverade</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-purple-100 text-sm font-medium">Total kapacitet</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_capacity']) }}</p>
            <p class="text-xs text-purple-100 mt-1">platser</p>
        </div>
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-4 text-white">
            <p class="text-indigo-100 text-sm font-medium">Bokade</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_booked']) }}</p>
            <p class="text-xs text-indigo-100 mt-1">bokningar</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 rounded-xl p-6 mb-8">
        <form method="GET" action="{{ route('admin.slot-times.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sök</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Tjänst, stad eller företag..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tjänst</label>
                <select name="service_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alla tjänster</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stad</label>
                <select name="city_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alla städer</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tillgänglighet</label>
                <select name="availability" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Alla</option>
                    <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>Tillgängliga</option>
                    <option value="full" {{ request('availability') === 'full' ? 'selected' : '' }}>Fullbokade</option>
                    <option value="unavailable" {{ request('availability') === 'unavailable' ? 'selected' : '' }}>Otillgängliga</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Från datum</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Till datum</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    🔍 Filtrera
                </button>
                <a href="{{ route('admin.slot-times.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    🗑️ Rensa
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
        <form method="POST" action="{{ route('admin.slot-times.bulk-delete') }}" id="bulk-delete-form" class="hidden">
            @csrf
            <div id="bulk-delete-inputs"></div>
        </form>
        
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Välj alla</span>
                </label>
                <button type="button" id="bulk-delete-btn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    🗑️ Radera valda
                </button>
            </div>
            <div class="text-sm text-gray-600">
                <span id="selected-count">0</span> valda
            </div>
        </div>
    </div>

    <!-- Slot Times Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Välj</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum & Tid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tjänst</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Företag</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapacitet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pris</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($slotTimes as $slotTime)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="slot-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                   value="{{ $slotTime->id }}" data-booked="{{ $slotTime->booked_count }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $slotTime->date->format('Y-m-d') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($slotTime->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($slotTime->end_time)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $slotTime->service->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $slotTime->city->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $slotTime->company ? $slotTime->company->company_name : 'Alla företag' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <span class="font-medium">{{ $slotTime->booked_count }}</span> / 
                                <span class="text-gray-500">{{ $slotTime->capacity }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                @php
                                    $percentage = $slotTime->capacity > 0 ? ($slotTime->booked_count / $slotTime->capacity) * 100 : 0;
                                @endphp
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <span class="font-medium text-blue-600">{{ number_format($slotTime->price_multiplier ?? 1.00, 2) }}x</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                @if(($slotTime->price_multiplier ?? 1.00) > 1.00)
                                    <span class="text-orange-600">Högre pris</span>
                                @elseif(($slotTime->price_multiplier ?? 1.00) < 1.00)
                                    <span class="text-green-600">Rabatt</span>
                                @else
                                    <span class="text-gray-500">Normalpris</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($slotTime->booked_count >= $slotTime->capacity)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Fullbokad
                                </span>
                            @elseif($slotTime->is_available)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Tillgänglig
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Otillgänglig
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.slot-times.show', $slotTime) }}" 
                                   class="text-blue-600 hover:text-blue-900">Visa</a>
                                <a href="{{ route('admin.slot-times.edit', $slotTime) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Redigera</a>
                                <form method="POST" action="{{ route('admin.slot-times.toggle-availability', $slotTime) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-{{ $slotTime->is_available ? 'red' : 'green' }}-600 hover:text-{{ $slotTime->is_available ? 'red' : 'green' }}-900">
                                        {{ $slotTime->is_available ? 'Inaktivera' : 'Aktivera' }}
                                    </button>
                                </form>
                                @if($slotTime->booked_count === 0)
                                    <form method="POST" action="{{ route('admin.slot-times.destroy', $slotTime) }}" class="inline" 
                                          onsubmit="return confirm('Är du säker på att du vill radera denna tidslucka?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Radera</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-5xl mb-4">⏰</div>
                            <p class="text-lg mb-2">Inga tidsluckor hittades</p>
                            <p class="text-sm text-gray-400">Skapa din första tidslucka för att komma igång.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $slotTimes->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const slotCheckboxes = document.querySelectorAll('.slot-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkDeleteForm = document.getElementById('bulk-delete-form');
    const bulkDeleteInputs = document.getElementById('bulk-delete-inputs');

    function updateSelectedCount() {
        const selected = document.querySelectorAll('.slot-checkbox:checked');
        const count = selected.length;
        selectedCountSpan.textContent = count;
        bulkDeleteBtn.disabled = count === 0;
        
        // Check if any selected slots have bookings
        const hasBookings = Array.from(selected).some(checkbox => 
            parseInt(checkbox.dataset.booked) > 0
        );
        
        if (hasBookings) {
            bulkDeleteBtn.disabled = true;
            bulkDeleteBtn.title = 'Kan inte radera tidsluckor med bokningar';
        } else {
            bulkDeleteBtn.title = '';
        }
    }

    function updateSelectAll() {
        const allChecked = slotCheckboxes.length > 0 && 
                          Array.from(slotCheckboxes).every(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = !allChecked && 
                                         Array.from(slotCheckboxes).some(cb => cb.checked);
    }

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        slotCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Individual checkbox functionality
    slotCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            updateSelectAll();
        });
    });

    // Bulk delete functionality
    bulkDeleteBtn.addEventListener('click', function() {
        console.log('Bulk delete button clicked');
        
        const selected = document.querySelectorAll('.slot-checkbox:checked');
        console.log('Selected checkboxes:', selected.length);
        
        const hasBookings = Array.from(selected).some(checkbox => 
            parseInt(checkbox.dataset.booked) > 0
        );
        
        if (hasBookings) {
            alert('Kan inte radera tidsluckor som har bokningar.');
            return;
        }
        
        if (selected.length === 0) {
            alert('Välj tidsluckor att radera.');
            return;
        }
        
        if (confirm(`Är du säker på att du vill radera ${selected.length} tidsluckor?`)) {
            console.log('User confirmed deletion');
            
            // Clear previous inputs
            bulkDeleteInputs.innerHTML = '';
            
            // Add selected slot IDs
            const slotIds = [];
            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'slot_ids[]';
                input.value = checkbox.value;
                bulkDeleteInputs.appendChild(input);
                slotIds.push(checkbox.value);
            });
            
            console.log('Slot IDs to delete:', slotIds);
            console.log('Form action:', bulkDeleteForm.action);
            console.log('Form method:', bulkDeleteForm.method);
            
            // Show loading state
            bulkDeleteBtn.disabled = true;
            bulkDeleteBtn.textContent = 'Raderar...';
            
            bulkDeleteForm.submit();
        }
    });

    // Initialize
    updateSelectedCount();
    updateSelectAll();
});
</script>
@endsection
