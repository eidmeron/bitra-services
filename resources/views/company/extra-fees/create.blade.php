@extends('layouts.company')

@section('title', 'Lägg till Extra Avgift')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">💰 Lägg till Extra Avgift</h2>
    <p class="text-gray-600">Bokning #{{ $booking->booking_number }} - {{ $booking->service->name }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Form Section (Left) -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="POST" action="{{ route('company.extra-fees.store', $booking) }}" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Titel <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ex: Extra städning av kök"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Beskrivning
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Beskriv varför denna extra avgift behövs...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Belopp (SEK) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           value="{{ old('amount') }}"
                           step="0.01"
                           min="0.01"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00"
                           required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                        📷 Foto (valfritt)
                    </label>
                    <div class="relative">
                        <input type="file" 
                               id="photo" 
                               name="photo" 
                               accept="image/*"
                               class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all hover:border-blue-400 cursor-pointer">
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-500 mt-1">Klicka för att ladda upp bild</p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Max 2MB. Tillåtna format: JPEG, PNG, JPG, GIF</p>
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                        ✅ Lägg till extra avgift
                    </button>
                    <a href="{{ route('company.bookings.show', $booking) }}" 
                       class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-colors text-center">
                        ❌ Avbryt
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Instructions Section (Right) -->
    <div class="space-y-6">
        <!-- Important Instructions -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-3">📋 Viktiga instruktioner för extra avgifter</h3>
                    <div class="text-sm text-yellow-700 space-y-2">
                        <p><strong>⚠️ KUNDENS RÄTTIGHETER ENLIGT SVENSK LAG:</strong></p>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li>Kunden måste <strong>godkänna</strong> extra avgiften innan arbetet påbörjas</li>
                            <li>Alla extra avgifter måste vara <strong>rimliga och motiverade</strong></li>
                            <li>Kunden har rätt att <strong>avslå</strong> extra avgifter som inte varit avtalade</li>
                            <li>Extra avgifter får <strong>inte</strong> överskrida ursprungligt avtalat pris med mer än 20%</li>
                            <li>Kunden ska få <strong>skriftlig information</strong> om extra avgifter innan utförande</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Process Information -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3">🔄 Process för extra avgifter</h3>
                    <div class="text-sm text-blue-700 space-y-2">
                        <ol class="list-decimal list-inside space-y-1">
                            <li><strong>Lägg till avgift:</strong> Fyll i formuläret och lägg till extra avgiften</li>
                            <li><strong>Automatisk godkännande:</strong> Extra avgiften godkänns automatiskt</li>
                            <li><strong>Kundmeddelande:</strong> Kunden får meddelande om extra avgiften</li>
                            <li><strong>Fakturering:</strong> Extra avgiften läggs till i sluträkningen</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Practices -->
        <div class="bg-green-50 border-l-4 border-green-400 p-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-green-800 mb-3">✅ Bästa praxis</h3>
                    <div class="text-sm text-green-700 space-y-2">
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Var tydlig:</strong> Beskriv exakt vad extra avgiften gäller</li>
                            <li><strong>Ladda upp bilder:</strong> Visa bevis på varför extra avgiften behövs</li>
                            <li><strong>Rimliga priser:</strong> Sätt rättvisa priser baserat på arbetsinsats</li>
                            <li><strong>Kommunikation:</strong> Förklara för kunden varför extra avgiften behövs</li>
                            <li><strong>Dokumentation:</strong> Spara all kommunikation om extra avgifter</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Booking Info -->
        <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">📊 Aktuell bokning</h3>
            <div class="text-sm text-gray-700 space-y-2">
                <div class="flex justify-between">
                    <span>Bokningsnummer:</span>
                    <span class="font-mono font-semibold">{{ $booking->booking_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tjänst:</span>
                    <span class="font-semibold">{{ $booking->service->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Stad:</span>
                    <span class="font-semibold">{{ $booking->city->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Nuvarande pris:</span>
                    <span class="font-semibold text-green-600">{{ number_format($booking->final_price, 0, ',', ' ') }} kr</span>
                </div>
                <div class="flex justify-between">
                    <span>Status:</span>
                    <span>{!! bookingStatusBadge($booking->status) !!}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('photo');
    const fileUploadArea = fileInput.parentElement;
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Update the display to show selected file
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            // Create preview
            const preview = document.createElement('div');
            preview.className = 'mt-2 p-3 bg-green-50 border border-green-200 rounded-lg';
            preview.innerHTML = `
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-800">${fileName}</p>
                        <p class="text-xs text-green-600">${fileSize} MB</p>
                    </div>
                    <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            // Remove any existing preview
            const existingPreview = fileUploadArea.querySelector('.mt-2');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            fileUploadArea.appendChild(preview);
            
            // Hide the upload area text
            const uploadText = fileUploadArea.querySelector('.absolute');
            if (uploadText) {
                uploadText.style.display = 'none';
            }
        }
    });
    
    // Handle drag and drop
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('border-blue-500', 'bg-blue-50');
    });
    
    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('border-blue-500', 'bg-blue-50');
    });
    
    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });
});

function removeFile() {
    const fileInput = document.getElementById('photo');
    const fileUploadArea = fileInput.parentElement;
    
    // Clear the file input
    fileInput.value = '';
    
    // Remove preview
    const preview = fileUploadArea.querySelector('.mt-2');
    if (preview) {
        preview.remove();
    }
    
    // Show upload area text again
    const uploadText = fileUploadArea.querySelector('.absolute');
    if (uploadText) {
        uploadText.style.display = 'flex';
    }
}
</script>
@endpush
