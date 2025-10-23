@extends('layouts.app')

@section('title', 'Skapa reklamation')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-6">
        <a href="{{ auth()->check() ? route('user.bookings.show', $booking) : route('public.booking.show', $booking->public_token) }}" class="text-blue-600 hover:underline">
            &larr; Tillbaka till bokning
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">📝 Skapa reklamation</h1>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-900 mb-2">Bokningsinformation</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium">Bokningsnummer:</span>
                    <span>{{ $booking->booking_number }}</span>
                </div>
                <div>
                    <span class="font-medium">Tjänst:</span>
                    <span>{{ $booking->service->name }}</span>
                </div>
                <div>
                    <span class="font-medium">Stad:</span>
                    <span>{{ $booking->city->name }}</span>
                </div>
                <div>
                    <span class="font-medium">Företag:</span>
                    <span>{{ $booking->company->company_name ?? $booking->company->user->email }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('complaints.store', $booking) }}">
            @csrf
            
            <div class="mb-6">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Ämne *
                </label>
                <input 
                    type="text" 
                    id="subject" 
                    name="subject" 
                    value="{{ old('subject') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror"
                    placeholder="Beskriv kortfattat vad reklamationen gäller"
                    required
                >
                @error('subject')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                    Prioritet *
                </label>
                <select 
                    id="priority" 
                    name="priority" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('priority') border-red-500 @enderror"
                    required
                >
                    <option value="">Välj prioritet</option>
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>🟢 Låg - Mindre problem</option>
                    <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>🟡 Medium - Vanligt problem</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>🟠 Hög - Viktigt problem</option>
                    <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>🔴 Akut - Kritiskt problem</option>
                </select>
                @error('priority')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Beskrivning *
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="6"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                    placeholder="Beskriv detaljerat vad som är problemet, vad som hände, och vad du förväntar dig att vi ska göra åt det..."
                    required
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-2">Max 2000 tecken</p>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-yellow-900 mb-2">ℹ️ Viktig information</h4>
                <ul class="text-sm text-yellow-800 space-y-1">
                    <li>• Reklamationer hanteras av vår supportavdelning</li>
                    <li>• Du kan ladda upp bilder och dokument i chattfunktionen efter att reklamationen skapats</li>
                    <li>• Vi svarar vanligtvis inom 1-2 arbetsdagar</li>
                    <li>• Du får e-postnotifieringar när det finns uppdateringar</li>
                </ul>
            </div>

            <div class="flex gap-4">
                <button 
                    type="submit" 
                    class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
                >
                    📝 Skapa reklamation
                </button>
                <a 
                    href="{{ auth()->check() ? route('user.bookings.show', $booking) : route('public.booking.show', $booking->public_token) }}" 
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                >
                    Avbryt
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

