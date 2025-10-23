@extends('layouts.admin')

@section('title', 'Redigera inställningar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">✏️ Redigera inställningar</h1>
            <p class="text-gray-600 mt-2">Uppdatera globala webbplatsinställningar</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Tillbaka
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-red-700 font-medium mb-2">Det finns några fel:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $groupSettings)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Group Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 capitalize">
                        @switch($group)
                            @case('general') 🏠 Allmänt @break
                            @case('header') 📋 Sidhuvud @break
                            @case('footer') 📄 Sidfot @break
                            @case('seo') 🎯 SEO @break
                            @case('social') 📱 Sociala medier @break
                            @case('contact') 📧 Kontakt @break
                            @default {{ ucfirst($group) }}
                        @endswitch
                    </h2>
                </div>

                <!-- Group Settings -->
                <div class="px-6 py-6">
                    <div class="space-y-6">
                        @foreach($groupSettings as $setting)
                            <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                <label for="setting_{{ $setting->key }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $setting->label }}
                                    @if($setting->description)
                                        <span class="block text-xs font-normal text-gray-500 mt-1">{{ $setting->description }}</span>
                                    @endif
                                </label>

                                @if($setting->type === 'text')
                                    <input 
                                        type="text" 
                                        name="settings[{{ $setting->key }}]" 
                                        id="setting_{{ $setting->key }}"
                                        value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >

                                @elseif($setting->type === 'textarea')
                                    <textarea 
                                        name="settings[{{ $setting->key }}]" 
                                        id="setting_{{ $setting->key }}"
                                        rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >{{ old('settings.' . $setting->key, $setting->value) }}</textarea>

                                @elseif($setting->type === 'url')
                                    <input 
                                        type="url" 
                                        name="settings[{{ $setting->key }}]" 
                                        id="setting_{{ $setting->key }}"
                                        value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                        placeholder="https://example.com"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >

                                @elseif($setting->type === 'boolean')
                                    <label class="flex items-center cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="settings[{{ $setting->key }}]" 
                                            id="setting_{{ $setting->key }}"
                                            value="1"
                                            {{ old('settings.' . $setting->key, $setting->value) ? 'checked' : '' }}
                                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        >
                                        <span class="ml-3 text-gray-700">Aktivera</span>
                                    </label>

                                @elseif($setting->type === 'image')
                                    <div class="space-y-3">
                                        @if($setting->value)
                                            <div class="relative inline-block">
                                                <img src="{{ Storage::url($setting->value) }}" alt="{{ $setting->label }}" class="max-w-xs h-32 object-contain rounded border border-gray-200">
                                                <span class="absolute top-2 right-2 px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded">Nuvarande</span>
                                            </div>
                                        @endif
                                        <input 
                                            type="file" 
                                            name="settings[{{ $setting->key }}]" 
                                            id="setting_{{ $setting->key }}"
                                            accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        >
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3 sticky bottom-0 bg-white p-4 rounded-lg shadow-lg border border-gray-200">
            <a href="{{ route('admin.settings.index') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                Avbryt
            </a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                💾 Spara inställningar
            </button>
        </div>
    </form>
</div>
@endsection

