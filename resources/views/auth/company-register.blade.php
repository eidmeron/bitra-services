@extends('layouts.public')

@section('title', 'Bli Partner - Registrera ditt företag')

@push('styles')
<style>
    .step-item {
        transition: all 0.3s ease;
    }
    
    .step-item.active {
        transform: scale(1.1);
    }
    
    .step-content {
        display: none;
    }
    
    .step-content.active {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Hide elements with x-cloak until Alpine.js loads */
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-pink-50 dark:from-gray-900 dark:via-purple-900 dark:to-blue-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <a href="{{ route('welcome') }}" class="inline-block mb-6">
                @if(setting('site_logo'))
                    <img src="{{ Storage::url(setting('site_logo')) }}" alt="{{ site_name() }}" class="h-16 mx-auto">
                @else
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                        {{ site_name() }}
                    </h1>
                @endif
            </a>
            
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                🚀 Bli Partner hos oss!
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Nå tusentals nya kunder och utveckla ditt företag med Sveriges ledande plattform
            </p>
        </div>

        <!-- Global Errors -->
        @if($errors->any())
            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="text-red-800 font-semibold mb-2">Några fel upptäcktes:</h3>
                        <ul class="list-disc list-inside text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Progress Steps -->
        <div class="mb-12">
            <div class="flex items-center justify-between max-w-2xl mx-auto">
                <div class="step-item flex flex-col items-center flex-1 relative" data-step="1">
                    <div class="w-12 h-12 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold mb-2 step-circle">
                        <span class="step-number">1</span>
                        <svg class="w-6 h-6 hidden step-check" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 text-center">Företagsinfo</span>
                    <div class="absolute top-6 left-1/2 w-full h-1 bg-gray-300 dark:bg-gray-700 -z-10 step-line"></div>
                </div>

                <div class="step-item flex flex-col items-center flex-1 relative" data-step="2">
                    <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-700 text-white flex items-center justify-center font-bold mb-2 step-circle">
                        <span class="step-number">2</span>
                        <svg class="w-6 h-6 hidden step-check" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 text-center">Tjänster</span>
                    <div class="absolute top-6 left-1/2 w-full h-1 bg-gray-300 dark:bg-gray-700 -z-10 step-line"></div>
                </div>

                <div class="step-item flex flex-col items-center flex-1" data-step="3">
                    <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-700 text-white flex items-center justify-center font-bold mb-2 step-circle">
                        <span class="step-number">3</span>
                        <svg class="w-6 h-6 hidden step-check" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 text-center">Konto</span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('company.register.submit') }}" enctype="multipart/form-data" id="companyRegisterForm" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8" novalidate>
            @csrf

            <!-- Step 1: Company Information -->
            <div class="step-content active" data-step="1">
                <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">📋 Företagsinformation</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Företagsnamn <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Organisationsnummer <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="org_number" value="{{ old('org_number') }}" required
                               placeholder="XXXXXX-XXXX"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('org_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Företagstelefon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="company_phone" value="{{ old('company_phone') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('company_phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Företagsepost <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="company_email" value="{{ old('company_email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('company_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Adress <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_address" value="{{ old('company_address') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('company_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Stad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_city" value="{{ old('company_city') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('company_city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Postnummer <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_zip" value="{{ old('company_zip') }}" required
                               placeholder="XXX XX"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('company_zip')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Webbplats (valfritt)
                        </label>
                        <input type="url" name="company_website" value="{{ old('company_website') }}"
                               placeholder="https://example.com"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('company_website')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2" x-data="{ 
                        fileName: '', 
                        imagePreview: null,
                        handleFileSelect(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.fileName = file.name;
                                
                                // Validate file size (2MB)
                                if (file.size > 2 * 1024 * 1024) {
                                    alert('Filen är för stor! Max 2MB tillåtet.');
                                    event.target.value = '';
                                    this.fileName = '';
                                    this.imagePreview = null;
                                    return;
                                }
                                
                                // Show preview
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.imagePreview = e.target.result;
                                };
                                reader.readAsDataURL(file);
                            }
                        },
                        clearFile() {
                            this.fileName = '';
                            this.imagePreview = null;
                            this.$refs.fileInput.value = '';
                        }
                    }">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            <span class="inline-flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Företagslogotyp (valfritt)
                            </span>
                        </label>

                        <!-- Upload Area -->
                        <div class="relative">
                            <!-- Hidden File Input -->
                            <input 
                                type="file" 
                                name="logo" 
                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                x-ref="fileInput"
                                @change="handleFileSelect($event)"
                                class="hidden"
                                id="logo-upload"
                            >

                            <!-- Preview Area (shown when image is selected) -->
                            <div x-show="imagePreview" x-cloak class="mb-4">
                                <div class="relative inline-block">
                                    <img :src="imagePreview" alt="Preview" class="max-w-xs max-h-48 rounded-lg border-2 border-purple-300 shadow-lg">
                                    <button 
                                        type="button"
                                        @click="clearFile()"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition-all transform hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm text-green-600 mt-2 font-medium" x-text="'📁 ' + fileName"></p>
                            </div>

                            <!-- Upload Button/Area (shown when no image) -->
                            <label 
                                for="logo-upload" 
                                x-show="!imagePreview"
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-300 group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-12 h-12 mb-3 text-gray-400 group-hover:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold text-purple-600 dark:text-purple-400">Klicka för att ladda upp</span> 
                                        eller dra och släpp
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG eller GIF (MAX. 2MB)</p>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-start mt-3 space-x-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <p>Rekommenderad storlek: 400x400px. Logotypen visas på din företagsprofil.</p>
                        </div>

                        @error('logo')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="button" class="next-step px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-lg transition-all">
                        Nästa →
                    </button>
                </div>
            </div>

            <!-- Step 2: Services & Coverage -->
            <div class="step-content" data-step="2">
                <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">🛠️ Tjänster & Täckningsområde</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Välj tjänster du erbjuder <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-4 border border-gray-300 dark:border-gray-600 rounded-lg">
                            @foreach($services as $service)
                                <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-purple-50 dark:hover:bg-purple-900 transition">
                                    <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                           {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}
                                           class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $service->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('services')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Välj städer du täcker <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-4 border border-gray-300 dark:border-gray-600 rounded-lg">
                            @foreach($cities as $city)
                                <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-purple-50 dark:hover:bg-purple-900 transition">
                                    <input type="checkbox" name="cities[]" value="{{ $city->id }}" 
                                           {{ in_array($city->id, old('cities', [])) ? 'checked' : '' }}
                                           class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $city->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('cities')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Företagsbeskrivning <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="5" required minlength="50"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Berätta om ditt företag, vad ni erbjuder och varför kunder ska välja er... (minst 50 tecken)">{{ old('description') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Minst 50 tecken</p>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" class="prev-step px-8 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold rounded-lg transition-all">
                        ← Tillbaka
                    </button>
                    <button type="button" class="next-step px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-lg transition-all">
                        Nästa →
                    </button>
                </div>
            </div>

            <!-- Step 3: Account Setup -->
            <div class="step-content" data-step="3">
                <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">👤 Skapa ditt konto</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Ditt namn <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Din e-post <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Lösenord <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        <p class="text-xs text-gray-500 mt-1">Minst 8 tecken</p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Bekräfta lösenord <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" name="terms" value="1" required
                                   class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500 mt-0.5">
                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                Jag accepterar <a href="{{ route('policy.terms') }}" target="_blank" class="text-purple-600 hover:underline">användarvillkoren</a> 
                                och <a href="{{ route('policy.privacy') }}" target="_blank" class="text-purple-600 hover:underline">integritetspolicyn</a>
                            </span>
                        </label>
                        @error('terms')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            ℹ️ <strong>Observera:</strong> Ditt konto kommer att granskas av vår admin innan det aktiveras. 
                            Du får ett e-postmeddelande när ditt konto har godkänts.
                        </p>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" class="prev-step px-8 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold rounded-lg transition-all">
                        ← Tillbaka
                    </button>
                    <button 
                        type="submit" 
                        id="submit-btn"
                        class="px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-lg shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submit-text">🚀 Skapa konto</span>
                        <span id="submit-loading" class="hidden">
                            <svg class="animate-spin inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Skapar konto...
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Already have account -->
        <div class="text-center mt-8">
            <p class="text-gray-600 dark:text-gray-400">
                Har du redan ett konto?
                <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
                    Logga in här
                </a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;
    
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
        
        // Show current step
        document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');
        
        // Update progress circles
        document.querySelectorAll('.step-item').forEach((el, index) => {
            const stepNum = index + 1;
            const circle = el.querySelector('.step-circle');
            const number = el.querySelector('.step-number');
            const check = el.querySelector('.step-check');
            const line = el.querySelector('.step-line');
            
            if (stepNum < step) {
                // Completed step
                circle.classList.remove('bg-gray-300', 'dark:bg-gray-700', 'bg-purple-600');
                circle.classList.add('bg-green-500');
                number.classList.add('hidden');
                check.classList.remove('hidden');
                if (line) {
                    line.classList.remove('bg-gray-300', 'dark:bg-gray-700');
                    line.classList.add('bg-green-500');
                }
                el.classList.remove('active');
            } else if (stepNum === step) {
                // Current step
                circle.classList.remove('bg-gray-300', 'dark:bg-gray-700', 'bg-green-500');
                circle.classList.add('bg-purple-600');
                number.classList.remove('hidden');
                check.classList.add('hidden');
                el.classList.add('active');
            } else {
                // Future step
                circle.classList.remove('bg-purple-600', 'bg-green-500');
                circle.classList.add('bg-gray-300', 'dark:bg-gray-700');
                number.classList.remove('hidden');
                check.classList.add('hidden');
                if (line) {
                    line.classList.remove('bg-green-500');
                    line.classList.add('bg-gray-300', 'dark:bg-gray-700');
                }
                el.classList.remove('active');
            }
        });
        
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Next step buttons
    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        });
    });
    
    // Previous step buttons
    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', function() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });
    });
    
    // Initialize
    showStep(1);
    
    // Validate current step before proceeding
    function validateStep(step) {
        const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
        if (!stepContent) return true;
        
        const inputs = stepContent.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                if (input.hasAttribute('required') && !input.checked) {
                    isValid = false;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            } else if (input.type === 'radio') {
                const radioGroup = document.querySelectorAll(`input[name="${input.name}"]`);
                const anyChecked = Array.from(radioGroup).some(r => r.checked);
                if (!anyChecked) {
                    isValid = false;
                    input.classList.add('border-red-500');
                }
            } else {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            }
        });
        
        // Special validation for checkboxes (services and cities)
        if (step === 2) {
            const servicesChecked = stepContent.querySelectorAll('input[name="services[]"]:checked');
            const citiesChecked = stepContent.querySelectorAll('input[name="cities[]"]:checked');
            
            if (servicesChecked.length === 0) {
                alert('Vänligen välj minst en tjänst.');
                isValid = false;
            }
            if (citiesChecked.length === 0) {
                alert('Vänligen välj minst en stad.');
                isValid = false;
            }
        }
        
        if (!isValid && step !== 3) {
            alert('Vänligen fyll i alla obligatoriska fält.');
        }
        
        return isValid;
    }
    
    // Update next button click handlers
    document.querySelectorAll('.next-step').forEach(btn => {
        btn.removeEventListener('click', function() {});
        btn.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
            }
        });
    });
    
    // Handle form submission
    const form = document.querySelector('#companyRegisterForm');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all steps
            let allValid = true;
            for (let i = 1; i <= totalSteps; i++) {
                if (!validateStep(i)) {
                    allValid = false;
                    showStep(i);
                    break;
                }
            }
            
            if (!allValid) {
                return false;
            }
            
            // Show loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                if (submitText) submitText.classList.add('hidden');
                if (submitLoading) submitLoading.classList.remove('hidden');
            }
            
            // Log form data for debugging
            const formData = new FormData(form);
            console.log('Submitting company registration...');
            console.log('Form data:', Object.fromEntries(formData.entries()));
            
            // Submit the form
            form.submit();
        });
    }
});
</script>
@endpush
@endsection

