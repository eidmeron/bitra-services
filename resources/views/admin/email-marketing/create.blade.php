@extends('layouts.admin')

@section('title', 'Skapa E-postkampanj')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                <span class="text-4xl mr-3">📧</span>
                Skapa E-postkampanj
            </h2>
            <p class="text-gray-600 mt-2">Skapa en ny e-postkampanj för användare, gäster och företag</p>
        </div>
        <a href="{{ route('admin.email-marketing.index') }}" class="flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <span class="mr-2">←</span>
            Tillbaka till kampanjer
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="text-xl mr-2">✏️</span>
                    Kampanjinformation
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.email-marketing.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Campaign Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Kampanjnamn <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="T.ex. Välkomstkampanj 2024"
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject Line -->
                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Ämnesrad <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            value="{{ old('subject') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="T.ex. Välkommen till Bitra - 20% rabatt på din första bokning!"
                        >
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Content -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            E-postinnehåll <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="content"
                            name="content"
                            rows="12"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Skriv ditt e-postinnehåll här..."
                        >{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campaign Type -->
                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Kampanjtyp <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="type"
                            name="type"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                            <option value="">Välj kampanjtyp</option>
                            <option value="newsletter" {{ old('type') === 'newsletter' ? 'selected' : '' }}>Nyhetsbrev</option>
                            <option value="promotional" {{ old('type') === 'promotional' ? 'selected' : '' }}>Kampanj</option>
                            <option value="transactional" {{ old('type') === 'transactional' ? 'selected' : '' }}>Transaktionell</option>
                            <option value="welcome" {{ old('type') === 'welcome' ? 'selected' : '' }}>Välkomstmeddelande</option>
                            <option value="reminder" {{ old('type') === 'reminder' ? 'selected' : '' }}>Påminnelse</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview Text -->
                    <div class="mb-6">
                        <label for="preview_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Förhandsvisningstext
                        </label>
                        <input
                            type="text"
                            id="preview_text"
                            name="preview_text"
                            value="{{ old('preview_text') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Kort text som visas i e-postförhandsvisningen"
                        >
                        @error('preview_text')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.email-marketing.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Avbryt
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="mr-2">📧</span>
                            Skapa kampanj
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Target Audience -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="text-xl mr-2">🎯</span>
                    Målgrupp
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="target_users" name="target_audience[]" value="users" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="target_users" class="ml-3 text-sm text-gray-700">
                            <span class="font-medium">Användare</span>
                            <span class="text-gray-500">({{ \App\Models\User::where('type', 'user')->count() }} personer)</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="target_companies" name="target_audience[]" value="companies" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="target_companies" class="ml-3 text-sm text-gray-700">
                            <span class="font-medium">Företag</span>
                            <span class="text-gray-500">({{ \App\Models\Company::where('status', 'active')->count() }} företag)</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="target_guests" name="target_audience[]" value="guests" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="target_guests" class="ml-3 text-sm text-gray-700">
                            <span class="font-medium">Gäster</span>
                            <span class="text-gray-500">({{ \App\Models\EmailSubscriber::guests()->active()->count() }} personer)</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="target_all" name="target_audience[]" value="all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="target_all" class="ml-3 text-sm text-gray-700">
                            <span class="font-medium">Alla</span>
                            <span class="text-gray-500">({{ \App\Models\EmailSubscriber::active()->count() }} personer)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Settings -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="text-xl mr-2">⚙️</span>
                    Inställningar
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label for="send_immediately" class="flex items-center">
                            <input type="radio" id="send_immediately" name="send_type" value="immediate" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-3 text-sm text-gray-700">Skapa som utkast</span>
                        </label>
                    </div>
                    <div>
                        <label for="schedule_send" class="flex items-center">
                            <input type="radio" id="schedule_send" name="send_type" value="scheduled" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-3 text-sm text-gray-700">Schemalägg</span>
                        </label>
                    </div>
                    <div id="schedule_date" class="ml-7 hidden">
                        <input type="datetime-local" name="scheduled_at" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl border border-blue-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                <span class="text-xl mr-2">💡</span>
                Tips för bättre e-post
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="text-blue-500 mr-2">•</span>
                    Använd personliga namn i ämnesraden
                </li>
                <li class="flex items-start">
                    <span class="text-blue-500 mr-2">•</span>
                    Håll meddelandet kort och tydligt
                </li>
                <li class="flex items-start">
                    <span class="text-blue-500 mr-2">•</span>
                    Inkludera en tydlig uppmaning till handling
                </li>
                <li class="flex items-start">
                    <span class="text-blue-500 mr-2">•</span>
                    Testa på olika enheter innan du skickar
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle schedule radio button
    const scheduleRadio = document.getElementById('schedule_send');
    const scheduleDate = document.getElementById('schedule_date');
    
    scheduleRadio.addEventListener('change', function() {
        if (this.checked) {
            scheduleDate.classList.remove('hidden');
        } else {
            scheduleDate.classList.add('hidden');
        }
    });
    
    // Set minimum date to today
    const dateInput = document.querySelector('input[type="datetime-local"]');
    if (dateInput) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        dateInput.min = `${year}-${month}-${day}T${hours}:${minutes}`;
    }
});
</script>
@endsection
