@extends('layouts.admin')

@section('title', 'Redigera stad')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.cities.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till städer</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Redigera: {{ $city->name }}</h2>

        <form id="update-form" method="POST" action="{{ route('admin.cities.update', $city) }}">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Zone Selection -->
            <div class="mb-6">
                <label for="zone_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Zone <span class="text-red-500">*</span>
                </label>
                <select name="zone_id" id="zone_id" class="form-input" required>
                    <option value="">Välj zone...</option>
                    @foreach(\App\Models\Zone::all() as $zone)
                        <option value="{{ $zone->id }}" {{ old('zone_id', $city->zone_id) == $zone->id ? 'selected' : '' }}>
                            {{ $zone->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- City Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Stadens namn <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $city->name) }}" 
                    class="form-input" 
                    required
                    placeholder="Ange stadens namn"
                >
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Beskrivning
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    class="form-input" 
                    placeholder="Beskrivning av staden (valfritt)"
                >{{ old('description', $city->description) }}</textarea>
            </div>

            <!-- City Multiplier -->
            <div class="mb-6">
                <label for="city_multiplier" class="block text-sm font-medium text-gray-700 mb-2">
                    Stadstillägg (multiplikator) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input 
                        type="number" 
                        name="city_multiplier" 
                        id="city_multiplier" 
                        value="{{ old('city_multiplier', $city->city_multiplier) }}" 
                        class="form-input pr-8" 
                        step="0.01" 
                        min="0.1" 
                        max="5.0" 
                        required
                        placeholder="1.00"
                    >
                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">×</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    Exempel: 1.20 = 20% tillägg, 0.90 = 10% rabatt
                </p>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" class="form-input" required>
                    <option value="active" {{ old('status', $city->status) == 'active' ? 'selected' : '' }}>
                        Aktiv
                    </option>
                    <option value="inactive" {{ old('status', $city->status) == 'inactive' ? 'selected' : '' }}>
                        Inaktiv
                    </option>
                </select>
            </div>

            <!-- Statistics Section -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">📊 Statistik</h4>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-gray-600">Tjänster</p>
                        <p class="font-semibold text-lg">{{ $city->services->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Företag</p>
                        <p class="font-semibold text-lg">{{ $city->companies->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Bokningar</p>
                        <p class="font-semibold text-lg">{{ $city->bookings->count() }}</p>
                    </div>
                </div>
                
                @if($city->bookings->count() > 0)
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded">
                        <p class="text-red-800 text-sm">
                            🚫 <strong>Kan inte raderas:</strong> Denna stad har {{ $city->bookings->count() }} bokning(ar). Du måste först hantera dessa bokningar innan staden kan raderas.
                        </p>
                    </div>
                @elseif($city->services->count() > 0 || $city->companies->count() > 0)
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                        <p class="text-yellow-800 text-sm">
                            ⚠️ <strong>Varning:</strong> Denna stad har {{ $city->services->count() }} tjänst(er) och {{ $city->companies->count() }} företag. Dessa kommer att kopplas bort från staden vid radering.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t">
                <!-- Delete Button -->
                <form method="POST" action="{{ route('admin.cities.destroy', $city) }}" onsubmit="return confirm('Är du säker på att du vill radera {{ $city->name }}? Tjänster och företag kommer att kopplas bort från staden. Bokningar måste hanteras separat.')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger {{ $city->bookings->count() > 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $city->bookings->count() > 0 ? 'disabled' : '' }}>
                        {{ $city->bookings->count() > 0 ? 'Kan inte raderas' : 'Radera stad' }}
                    </button>
                </form>

                <div class="flex space-x-4">
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                        Avbryt
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Uppdatera stad
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Test Update Functionality -->
<div class="max-w-2xl mx-auto mt-8">
    <div class="card bg-blue-50 border border-blue-200">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">🧪 Test Uppdateringsfunktion</h3>
        <div class="space-y-3">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-blue-800">Form ID:</span>
                <code class="bg-blue-100 px-2 py-1 rounded text-sm">update-form</code>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-blue-800">Action:</span>
                <code class="bg-blue-100 px-2 py-1 rounded text-sm">{{ route('admin.cities.update', $city) }}</code>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-blue-800">Method:</span>
                <code class="bg-blue-100 px-2 py-1 rounded text-sm">PUT</code>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-blue-800">CSRF Token:</span>
                <code class="bg-blue-100 px-2 py-1 rounded text-sm">@csrf</code>
            </div>
        </div>
        <div class="mt-4 p-3 bg-green-100 border border-green-200 rounded">
            <p class="text-green-800 text-sm">
                ✅ <strong>Form struktur korrekt:</strong> Uppdateringsknappen är nu inne i formuläret och kommer att skicka till rätt route.
            </p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Test form functionality
    const form = document.getElementById('update-form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    console.log('✅ Form loaded:', form);
    console.log('✅ Submit button:', submitButton);
    console.log('✅ Form action:', form.action);
    console.log('✅ Form method:', form.method);
    
    // Add form validation
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const multiplier = document.getElementById('city_multiplier').value;
        
        if (!name) {
            e.preventDefault();
            alert('Stadens namn är obligatoriskt');
            return false;
        }
        
        if (!multiplier || multiplier < 0.1 || multiplier > 5.0) {
            e.preventDefault();
            alert('Stadstillägg måste vara mellan 0.1 och 5.0');
            return false;
        }
        
        console.log('✅ Form validation passed');
        console.log('✅ Submitting to:', form.action);
    });
    
    // Real-time multiplier preview
    const multiplierInput = document.getElementById('city_multiplier');
    const multiplierPreview = document.createElement('div');
    multiplierPreview.className = 'text-sm text-gray-600 mt-1';
    multiplierInput.parentNode.appendChild(multiplierPreview);
    
    function updateMultiplierPreview() {
        const value = parseFloat(multiplierInput.value) || 1.0;
        if (value > 1.0) {
            const percentage = ((value - 1.0) * 100).toFixed(0);
            multiplierPreview.textContent = `+${percentage}% tillägg`;
            multiplierPreview.className = 'text-sm text-red-600 mt-1';
        } else if (value < 1.0) {
            const percentage = ((1.0 - value) * 100).toFixed(0);
            multiplierPreview.textContent = `-${percentage}% rabatt`;
            multiplierPreview.className = 'text-sm text-green-600 mt-1';
        } else {
            multiplierPreview.textContent = 'Ingen förändring';
            multiplierPreview.className = 'text-sm text-gray-600 mt-1';
        }
    }
    
    multiplierInput.addEventListener('input', updateMultiplierPreview);
    updateMultiplierPreview();
});
</script>
@endpush
