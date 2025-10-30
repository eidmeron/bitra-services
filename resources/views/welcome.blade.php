@php
    use App\Services\PageContentService;
    
    $pageContent = PageContentService::getPageContent('homepage', [
        'meta_title' => 'Hitta och boka professionella tjänster i hela Sverige',
        'meta_description' => 'Boka professionella tjänster i hela Sverige - från hemstädning till renovering. Vi kopplar dig till Sveriges bästa företag.',
        'meta_keywords' => 'tjänster, bokning, städning, renovering, hantverk, Sverige',
        'hero_title' => 'Hitta och boka professionella tjänster i hela Sverige',
        'hero_subtitle' => 'Från hemstädning till renovering - Vi kopplar dig till Sveriges bästa företag',
    ]);
    
    $seoData = PageContentService::getSeoData('homepage', [
        'title' => $pageContent['meta_title'],
        'description' => $pageContent['meta_description'],
        'keywords' => $pageContent['meta_keywords'],
    ]);
@endphp

@extends('layouts.public')

@section('title', $seoData['title'])
@section('meta_description', $seoData['description'])
@section('meta_keywords', $seoData['keywords'])

@push('styles')
<style>
    /* Dark Mode Variables */
    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f9fafb;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --border-color: #e5e7eb;
    }
    
    [data-theme="dark"] {
        --bg-primary: #1f2937;
        --bg-secondary: #111827;
        --text-primary: #f9fafb;
        --text-secondary: #d1d5db;
        --border-color: #374151;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Hero Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .delay-100 { animation-delay: 0.1s; opacity: 0; }
    .delay-200 { animation-delay: 0.2s; opacity: 0; }
    .delay-300 { animation-delay: 0.3s; opacity: 0; }
    .delay-400 { animation-delay: 0.4s; opacity: 0; }
    
    /* Gradient Text */
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Card Hover Effects */
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Image Shadow */
    .image-shadow {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Search Input */
    .search-container {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        border: 2px solid rgba(102, 126, 234, 0.2);
    }

    [data-theme="dark"] .search-container {
        background: rgba(31, 41, 55, 0.95);
        border-color: rgba(102, 126, 234, 0.3);
    }
            </style>
@endpush

@section('content')
<div>

    <!-- Enhanced Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 text-white py-12 lg:py-16">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-float" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold mb-4 animate-fadeInUp">
                        {!! $pageContent['hero_title'] ?: 'Hitta och boka <span class="text-yellow-300">professionella tjänster</span> i hela Sverige' !!}
                    </h1>
                    <p class="text-lg md:text-xl mb-6 text-blue-100 animate-fadeInUp delay-100">
                        {{ $pageContent['hero_subtitle'] ?: 'Från hemstädning till renovering - Vi kopplar dig till Sveriges bästa företag' }}
                    </p>

                    <!-- Enhanced Search Bar -->
                    <div class="mb-8 animate-fadeInUp delay-200">
                        <div class="search-container rounded-2xl shadow-2xl p-4" x-data="enhancedSearch()">
                            <div class="space-y-4">
                                <!-- Enhanced Search Input -->
                                <div class="relative">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Sök tjänster, städer eller kategorier</label>
                                    <div class="relative">
                                        <input type="text" 
                                               x-model="searchQuery"
                                               @input="performSearch()"
                                               @focus="showSuggestions = true"
                                               @blur="setTimeout(() => showSuggestions = false, 200)"
                                               @keydown.enter.prevent="handleSearch()"
                                               placeholder="Ex: hemstädning helsingborg, flyttstädning stockholm, trädgårdsarbete..."
                                               class="w-full px-4 py-4 bg-white border-2 border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl text-gray-900 shadow-sm">
                                        
                                        <!-- Search Suggestions -->
                                        <div x-show="showSuggestions && (searchResults.cities.length > 0 || searchResults.services.length > 0 || searchResults.categories.length > 0 || searchResults.companies.length > 0 || searchResults.suggestions.length > 0)" 
                                             x-transition
                                             class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-80 overflow-y-auto">
                                            
                                            <!-- Loading State -->
                                            <template x-if="isLoading">
                                                <div class="px-4 py-3 text-center text-gray-500">
                                                    <div class="flex items-center justify-center">
                                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Söker...
                                                    </div>
                                                </div>
                                            </template>
                                            
                                            <!-- Smart Suggestions -->
                                            <template x-if="!isLoading && searchResults.suggestions.length > 0">
                                                <div class="px-4 py-2 bg-blue-50 border-b">
                                                    <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide">🎯 Rekommenderat</div>
                                                </div>
                                            </template>
                                            <template x-for="suggestion in searchResults.suggestions" :key="'suggestion-' + suggestion.text">
                                                <div @click="selectResult(suggestion)" 
                                                     class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                    <div class="font-medium text-gray-900 flex items-center">
                                                        <span class="mr-2" x-text="suggestion.icon"></span>
                                                        <span x-text="suggestion.text"></span>
                                                    </div>
                                                    <div class="text-sm text-blue-600">Smart förslag</div>
                                                </div>
                                            </template>
                                            
                                            <!-- Cities -->
                                            <template x-if="!isLoading && searchResults.cities.length > 0">
                                                <div class="px-4 py-2 bg-gray-50 border-b">
                                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">📍 Städer</div>
                                                </div>
                                            </template>
                                            <template x-for="city in searchResults.cities" :key="'city-' + city.id">
                                                <div @click="selectResult(city)" 
                                                     class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                    <div class="font-medium text-gray-900 flex items-center">
                                                        <span class="mr-2" x-text="city.icon"></span>
                                                        <span x-text="city.name"></span>
                                                    </div>
                                                    <div class="text-sm text-gray-500" x-text="city.description"></div>
                                                </div>
                                            </template>
                                            
                                            <!-- Services -->
                                            <template x-if="!isLoading && searchResults.services.length > 0">
                                                <div class="px-4 py-2 bg-gray-50 border-b">
                                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">🛠️ Tjänster</div>
                                                </div>
                                            </template>
                                            <template x-for="service in searchResults.services" :key="'service-' + service.id">
                                                <div @click="selectResult(service)" 
                                                     class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                    <div class="font-medium text-gray-900 flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <span class="mr-2" x-text="service.icon"></span>
                                                            <span x-text="service.name"></span>
                                                        </div>
                                                        <span x-show="service.price" class="text-sm font-semibold text-green-600" x-text="service.price"></span>
                                                    </div>
                                                    <div class="text-sm text-gray-500" x-text="service.description"></div>
                                                </div>
                                            </template>
                                            
                                            <!-- Categories -->
                                            <template x-if="!isLoading && searchResults.categories.length > 0">
                                                <div class="px-4 py-2 bg-gray-50 border-b">
                                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">📂 Kategorier</div>
                                                </div>
                                            </template>
                                            <template x-for="category in searchResults.categories" :key="'category-' + category.id">
                                                <div @click="selectResult(category)" 
                                                     class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                    <div class="font-medium text-gray-900 flex items-center">
                                                        <span class="mr-2" x-text="category.icon"></span>
                                                        <span x-text="category.name"></span>
                                                    </div>
                                                    <div class="text-sm text-gray-500" x-text="category.description"></div>
                                                </div>
                                            </template>
                                            
                                            <!-- Companies -->
                                            <template x-if="!isLoading && searchResults.companies.length > 0">
                                                <div class="px-4 py-2 bg-gray-50 border-b">
                                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">🏢 Företag</div>
                                                </div>
                                            </template>
                                            <template x-for="company in searchResults.companies" :key="'company-' + company.id">
                                                <div @click="selectResult(company)" 
                                                     class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                    <div class="font-medium text-gray-900 flex items-center justify-between">
                                                        <div class="flex items-center">
                                                            <span class="mr-2" x-text="company.icon"></span>
                                                            <span x-text="company.name"></span>
                                                        </div>
                                                        <div x-show="company.rating" class="flex items-center text-sm">
                                                            <span class="text-yellow-500">★</span>
                                                            <span class="ml-1 text-gray-600" x-text="company.rating"></span>
                                                        </div>
                                                    </div>
                                                    <div class="text-sm text-gray-500" x-text="company.description"></div>
                                                </div>
                                            </template>
                                            
                                            <!-- Show All Results -->
                                            <template x-if="!isLoading && getTotalResults() > 0">
                                                <div class="px-4 py-3 bg-gray-100 border-t">
                                                    <div @click="handleSearch()" class="text-center text-blue-600 font-medium cursor-pointer hover:text-blue-800">
                                                        Visa alla resultat (<span x-text="getTotalResults()"></span>)
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Search Button -->
                                <button type="button" 
                                        @click="handleSearch()"
                                        data-conversion="search"
                                        class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Sök tjänster
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="flex flex-wrap justify-center lg:justify-start items-center gap-6 text-sm animate-fadeInUp delay-300">
                        <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                            <svg class="w-5 h-5 text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span><strong>4.8/5</strong> från 10,000+ recensioner</span>
                        </div>
                        <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                            <svg class="w-5 h-5 text-green-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span><strong>500+</strong> verifierade företag</span>
                        </div>
                        <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                            <svg class="w-5 h-5 text-blue-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            <span><strong>24/7</strong> kundsupport</span>
                        </div>
                    </div>
                </div>

                <!-- Right Service Images -->
                <div class="relative animate-fadeInUp delay-400">
                    <div class="relative z-10">
                        <!-- Service Images Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Cleaning Service -->
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 shadow-2xl hover:scale-105 transition-transform duration-300">
                                <div class="text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                                        <span class="text-3xl">🧹</span>
                                    </div>
                                    <h4 class="text-white font-bold text-lg mb-2">Städning</h4>
                                    <p class="text-blue-100 text-sm">Hemstädning & kontorsstädning</p>
                                </div>
                            </div>

                            <!-- Handyman Service -->
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 shadow-2xl hover:scale-105 transition-transform duration-300" style="animation-delay: 0.2s;">
                                <div class="text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                                        <span class="text-3xl">🔧</span>
                                    </div>
                                    <h4 class="text-white font-bold text-lg mb-2">Hantverk</h4>
                                    <p class="text-blue-100 text-sm">Reparationer & installationer</p>
                                </div>
                            </div>

                            <!-- Moving Service -->
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 shadow-2xl hover:scale-105 transition-transform duration-300" style="animation-delay: 0.4s;">
                                <div class="text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-full flex items-center justify-center">
                                        <span class="text-3xl">🚚</span>
                                    </div>
                                    <h4 class="text-white font-bold text-lg mb-2">Flytt</h4>
                                    <p class="text-blue-100 text-sm">Flytthjälp & transport</p>
                                </div>
                            </div>

                            <!-- Home Service -->
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 shadow-2xl hover:scale-105 transition-transform duration-300" style="animation-delay: 0.6s;">
                                <div class="text-center">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center">
                                        <span class="text-3xl">🏠</span>
                                    </div>
                                    <h4 class="text-white font-bold text-lg mb-2">Hemtjänst</h4>
                                    <p class="text-blue-100 text-sm">Allt för hemmet</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Section -->
                        <div class="mt-8 bg-white/10 backdrop-blur-sm rounded-2xl p-6 shadow-2xl">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-white mb-4">Sveriges #1 Tjänsteplattform</h3>
                                <div class="grid grid-cols-3 gap-4 text-center">
                                    <div>
                                        <div class="text-3xl font-bold text-yellow-300">10,000+</div>
                                        <div class="text-blue-100 text-sm">Nöjda kunder</div>
                                    </div>
                                    <div>
                                        <div class="text-3xl font-bold text-green-300">500+</div>
                                        <div class="text-blue-100 text-sm">Verifierade företag</div>
                                    </div>
                                    <div>
                                        <div class="text-3xl font-bold text-blue-300">4.8/5</div>
                                        <div class="text-blue-100 text-sm">Genomsnittsbetyg</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Happy People & Benefits Section -->
    <section class="py-16 lg:py-24 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Happy People Image -->
                <div class="order-2 lg:order-1">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                             alt="Nöjda kunder som använder Bitra" 
                             class="rounded-2xl shadow-2xl w-full h-96 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-2xl"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">10,000+ nöjda kunder</h3>
                            <p class="text-lg">Sveriges mest betrodda tjänsteplattform</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Benefits Content -->
                <div class="order-1 lg:order-2">
                    <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-8">
                        Varför välja <span class="text-blue-600">Bitra</span>?
                    </h2>
                    
                    <div class="space-y-8">
                        <!-- Save Time -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">⏰</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Spara tid</h3>
                                <p class="text-gray-600 dark:text-gray-300">Slipp leta efter pålitliga leverantörer. Vi har redan gjort jobbet åt dig genom att verifiera alla våra partners.</p>
                            </div>
                        </div>

                        <!-- Best Value -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">💰</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Bästa värde</h3>
                                <p class="text-gray-600 dark:text-gray-300">Konkurrenskraftiga och transparenta priser som passar dina behov. Inga dolda avgifter eller prismanipulation.</p>
                            </div>
                        </div>

                        <!-- Confidence -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">🛡️</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Trygghet</h3>
                                <p class="text-gray-600 dark:text-gray-300">Endast förhandsvalda och kvalitetsgaranterade företag. Varje partner uppfyller Bitras kvalitetskrav.</p>
                            </div>
                        </div>

                        <!-- Lifetime Records -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">📋</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Livstidsregister</h3>
                                <p class="text-gray-600 dark:text-gray-300">All din servicehistorik samlad och säker. Tillgänglig för alltid i vårt enkla och säkra system.</p>
                            </div>
                        </div>

                        <!-- Loyalty Benefits -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">🎁</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Belöningar</h3>
                                <p class="text-gray-600 dark:text-gray-300">Tjäna poäng på varje bokning och vid nya medlemsregistreringar. Använd poängen för framtida tjänster och ta del av regelbundna rabatter.</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="mt-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white">
                        <h3 class="text-2xl font-bold mb-4">Få en offert nu med enkelhet och transparens</h3>
                        <p class="text-blue-100 mb-6">Vi garanterar kvaliteten på vår service. De bästa priserna någonsin, utan konkurrens, i Sverige. Vi erbjuder också periodiska rabatter.</p>
                        <a href="{{ route('public.categories') }}" 
                           data-conversion="booking"
                           class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300">
                            <span class="mr-2">🚀</span>
                            Börja boka nu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
    function enhancedSearch() {
        return {
            searchQuery: '',
            showSuggestions: false,
            searchResults: {
                cities: [],
                services: [],
                categories: [],
                companies: [],
                suggestions: []
            },
            isLoading: false,
            searchTimeout: null,
            
            init() {
                // Initialize with empty state
            },
            
            async performSearch() {
                if (this.searchQuery.length < 2) {
                    this.searchResults = {
                        cities: [],
                        services: [],
                        categories: [],
                        companies: [],
                        suggestions: []
                    };
                    return;
                }
                
                // Clear previous timeout
                if (this.searchTimeout) {
                    clearTimeout(this.searchTimeout);
                }
                
                // Debounce search requests
                this.searchTimeout = setTimeout(async () => {
                    this.isLoading = true;
                    
                    try {
                        const response = await fetch(`/api/search?q=${encodeURIComponent(this.searchQuery)}&limit=5`);
                        if (response.ok) {
                            this.searchResults = await response.json();
                        }
                    } catch (error) {
                        console.error('Search error:', error);
                    } finally {
                        this.isLoading = false;
                    }
                }, 300);
            },
            
            selectResult(result) {
                this.searchQuery = result.name || result.text;
                this.showSuggestions = false;
                window.location.href = result.url;
            },
            
            handleSearch() {
                if (!this.searchQuery.trim()) return;
                
                // Try to find exact matches first
                const query = this.searchQuery.toLowerCase();
                
                // Check for exact matches in results
                for (const city of this.searchResults.cities) {
                    if (city.name.toLowerCase() === query) {
                        window.location.href = city.url;
                        return;
                    }
                }
                
                for (const service of this.searchResults.services) {
                    if (service.name.toLowerCase() === query) {
                        window.location.href = service.url;
                        return;
                    }
                }
                
                for (const category of this.searchResults.categories) {
                    if (category.name.toLowerCase() === query) {
                        window.location.href = category.url;
                        return;
                    }
                }
                
                for (const company of this.searchResults.companies) {
                    if (company.name.toLowerCase() === query) {
                        window.location.href = company.url;
                        return;
                    }
                }
                
                // Check for smart suggestions
                for (const suggestion of this.searchResults.suggestions) {
                    if (suggestion.text.toLowerCase() === query) {
                        window.location.href = suggestion.url;
                        return;
                    }
                }
                
                // If no exact match, do a general search
                window.location.href = `/search?q=${encodeURIComponent(this.searchQuery)}`;
            },
            
            getTotalResults() {
                return this.searchResults.cities.length + 
                       this.searchResults.services.length + 
                       this.searchResults.categories.length + 
                       this.searchResults.companies.length;
            }
        }
    }
    </script>
    @endpush

    <!-- How It Works Section -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Så fungerar Bitra
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Enkelt, säkert och transparent - så får du den bästa tjänsten
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center hover-lift bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-3xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Noggrant utvalda partners</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Vi samarbetar endast med utvalda och granskade företag i varje stad. Varje partner uppfyller Bitras kvalitetskrav så att du alltid får pålitlig och professionell service.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center hover-lift bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Enhetlig plattform</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Alla tjänster samlade på ett ställe: sök, jämför, boka och betala utan mellanhänder. Priserna är tydliga och fasta — inga dolda avgifter eller prismanipulation.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center hover-lift bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Enkel bokning & säkra register</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Boka på några klick. Varje bokning dokumenteras och sparas säkert så att din servicehistorik är tillgänglig när du behöver den.
                    </p>
                </div>
            </div>
            
            <!-- Link to detailed page -->
            <div class="text-center mt-12">
                <a href="{{ route('how-it-works') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:shadow-lg transform hover:scale-105 transition-all">
                    <span class="mr-2">📖</span>
                    Läs mer om vår process
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Utforska våra kategorier
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Välj en kategori för att se alla tjänster och företag
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(\App\Models\Category::where('status', 'active')->withCount('services')->get() as $category)
                    <a href="{{ route('public.category.show', $category->slug) }}" 
                       class="group hover-lift bg-gradient-to-br from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-2xl overflow-hidden shadow-lg border-2 border-transparent hover:border-blue-500 transition-all">
                        
                        <div class="relative h-40 bg-gradient-to-br from-{{ ['blue', 'purple', 'green', 'orange', 'pink'][array_rand(['blue', 'purple', 'green', 'orange', 'pink'])] }}-400 to-{{ ['blue', 'purple', 'green', 'orange', 'pink'][array_rand(['blue', 'purple', 'green', 'orange', 'pink'])] }}-600 flex items-center justify-center overflow-hidden">
                            @if($category->icon)
                                <img src="{{ Storage::url($category->icon) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-20 h-20 object-contain group-hover:scale-110 transition-transform duration-500">
                    @else
                                <span class="text-7xl group-hover:scale-110 transition-transform duration-500">
                                    {{ ['🏠', '🔧', '🌳', '🎨', '💼'][array_rand(['🏠', '🔧', '🌳', '🎨', '💼'])] }}
                                </span>
                            @endif
                            
                            <!-- Badge with service count -->
                            <div class="absolute top-4 right-4 bg-white dark:bg-gray-800 rounded-full px-3 py-1 shadow-lg">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $category->services_count }} tjänster
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-2 group-hover:text-blue-600 transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ $category->description }}
                            </p>
                            
                            <div class="flex items-center text-blue-600 dark:text-blue-400 font-semibold group-hover:translate-x-2 transition-transform">
                                <span>Se alla tjänster</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- View All Categories Button -->
            <div class="text-center mt-12">
                <a href="{{ route('public.categories') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-full hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span>Se alla kategorier</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Popular Services Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Populära tjänster
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Utforska våra mest bokade tjänster
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(\App\Models\Service::active()->take(6)->get() as $service)
                    <a href="{{ route('public.service.show', $service->slug) }}" 
                       class="group hover-lift bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg">
                        @if($service->image)
                            <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 overflow-hidden">
                                <img src="{{ Storage::url($service->image) }}" 
                                     alt="{{ $service->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <span class="text-6xl">🏠</span>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-2 group-hover:text-blue-600 transition-colors">
                                {{ $service->name }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ $service->description }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                    Från {{ formatPrice($service->base_price) }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                    </svg>
                                    {{ rand(50, 200) }} företag
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('public.services') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-bold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    Se alla tjänster
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Cities Section -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Vi finns i hela Sverige
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Hitta tjänster i din stad
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                @foreach(\App\Models\City::withCount('bookings')->orderByDesc('bookings_count')->limit(12)->get() as $city)
                    <a href="{{ route('public.city.show', $city->slug) }}" 
                       class="group bg-white dark:bg-gray-800 rounded-xl p-6 text-center hover-lift shadow-md">
                        <div class="text-4xl mb-3">📍</div>
                        <h3 class="font-bold text-lg group-hover:text-blue-600 transition-colors">
                            {{ $city->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $city->zone->name ?? 'Sverige' }}
                        </p>
                        @if($city->bookings_count > 0)
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-2 font-semibold">
                                {{ $city->bookings_count }} bokningar
                            </p>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="text-center">
                <a href="{{ route('public.cities') }}" 
                   class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl">
                    Se alla städer
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Fördelar med Bitra
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Varför våra kunder väljer oss framför andra
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Save Time -->
                <div class="text-center">
                    <div class="bg-blue-100 dark:bg-blue-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">⏰</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Spara tid</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Slipp leta efter pålitliga företag och jämföra otaliga erbjudanden.
                    </p>
                </div>

                <!-- Best Value -->
                <div class="text-center">
                    <div class="bg-green-100 dark:bg-green-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">💰</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Bästa värde</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Få de bästa priserna för varje tjänst, anpassade efter dina behov.
                    </p>
                </div>

                <!-- Confidence -->
                <div class="text-center">
                    <div class="bg-purple-100 dark:bg-purple-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🛡️</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Trygghet</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Endast förhandsvalda och kvalitetsgaranterade företag.
                    </p>
                </div>

                <!-- Lifetime Records -->
                <div class="text-center">
                    <div class="bg-yellow-100 dark:bg-yellow-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📋</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Livstidsregister</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        All din servicehistorik samlad och säker - tillgänglig för alltid.
                    </p>
                </div>

                <!-- Regular Discounts -->
                <div class="text-center">
                    <div class="bg-indigo-100 dark:bg-indigo-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🏷️</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Regelbundna rabatter</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Njut av regelbundna rabatter och kampanjer.
                    </p>
                </div>

                <!-- Points System -->
                <div class="text-center">
                    <div class="bg-pink-100 dark:bg-pink-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">⭐</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Poängsystem</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Tjäna poäng vid varje bokning och vid nyregistrering av medlemmar.
                    </p>
                </div>
            </div>
                <div class="text-center">
                    <div class="bg-purple-100 dark:bg-purple-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Snabb service</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Vi matchar dig med ett företag inom 24 timmar.
                    </p>
                </div>

                <!-- ROT Deduction -->
                <div class="text-center">
                    <div class="bg-yellow-100 dark:bg-yellow-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">ROT-avdrag</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Få tillbaka upp till 30% genom ROT-avdrag.
                    </p>
                </div>
            </div>
            
            <!-- Link to detailed page -->
            <div class="text-center mt-12">
                <a href="{{ route('why-us') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:shadow-lg transform hover:scale-105 transition-all">
                    <span class="mr-2">🔍</span>
                    Läs mer om våra fördelar
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Loyalty Points Section -->
    @if(setting('loyalty_points_enabled', true))
    <section class="py-20 bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 dark:from-purple-900 dark:via-pink-900 dark:to-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Content -->
                <div class="space-y-6 animate-fadeInUp">
                    <div class="inline-flex items-center px-4 py-2 bg-purple-100 dark:bg-purple-800 rounded-full">
                        <span class="text-2xl mr-2">⭐</span>
                        <span class="text-sm font-semibold text-purple-800 dark:text-purple-200">LOJALITETSPOÄNG</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
                        Få belönad för varje bokning! 🎉
                    </h2>
                    
                    <p class="text-xl text-gray-600 dark:text-gray-300">
                        Tjäna poäng på dina bokningar och använd dem för att få rabatt på framtida tjänster. Ju mer du använder vår tjänst, desto mer sparar du!
                    </p>

                    <!-- Benefits -->
                    <div class="space-y-4 pt-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Tjäna {{ setting('loyalty_points_earn_rate', 1) }}% på varje bokning</h4>
                                <p class="text-gray-600 dark:text-gray-400">För varje krona du spenderar får du poäng</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">1 poäng = {{ setting('loyalty_points_value', 1) }} kr</h4>
                                <p class="text-gray-600 dark:text-gray-400">Enkel och transparent värdering</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Välkomstbonus för nya användare!</h4>
                                <p class="text-gray-600 dark:text-gray-400">Få {{ setting('new_user_loyalty_bonus', 100) }} poäng när du registrerar dig</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Inga utgångsdatum</h4>
                                <p class="text-gray-600 dark:text-gray-400">Dina poäng är giltiga i {{ setting('loyalty_points_expiry_days', 365) }} dagar</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="pt-6">
                        @auth
                            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <span>Se dina poäng</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <span>Registrera dig & få bonus</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right: Visual -->
                <div class="relative">
                    <!-- Animated Card -->
                    <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 transform hover:scale-105 transition-all duration-300 animate-float">
                        <!-- Points Display -->
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full mb-4">
                                <span class="text-4xl">⭐</span>
                            </div>
                            <h3 class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">
                                1,250
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">Lojalitetspoäng</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">≈ {{ number_format(1250 * (float)setting('loyalty_points_value', 1), 0) }} kr värde</p>
                        </div>

                        <!-- Example Earnings -->
                        <div class="space-y-3 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Hemstädning</span>
                                </div>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">+50 poäng</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Flyttstädning</span>
                                </div>
                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400">+150 poäng</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Välkomstbonus</span>
                                </div>
                                <span class="text-sm font-bold text-purple-600 dark:text-purple-400">+100 poäng</span>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-purple-200 dark:bg-purple-800 rounded-full blur-xl opacity-50"></div>
                    <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-pink-200 dark:bg-pink-800 rounded-full blur-xl opacity-50"></div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Top Companies Section -->
    <section class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Topprankade företag
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Företag som våra kunder älskar
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(\App\Models\Company::where('status', 'active')->withAvg('reviews', 'company_rating')->orderByDesc('reviews_avg_company_rating')->take(6)->get() as $company)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover-lift">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-1">{{ $company->company_name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $company->cities->pluck('name')->join(', ') }}
                                </p>
                            </div>
                            @if($company->logo)
                                <img src="{{ Storage::url($company->logo) }}" 
                                     alt="{{ $company->company_name }}" 
                                     class="w-16 h-16 rounded-lg object-cover">
                            @endif
                        </div>

                        <!-- Rating -->
                        <div class="flex items-center mb-4">
                            @php
                                $avgRating = $company->reviews_avg_company_rating ?? 0;
                                $fullStars = floor($avgRating);
                                $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                            @endphp
                            
                            @for($i = 0; $i < 5; $i++)
                                @if($i < $fullStars)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @elseif($i == $fullStars && $hasHalfStar)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" opacity="0.5"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-2 text-sm font-semibold">
                                {{ number_format($avgRating, 1) }} ({{ $company->reviews_count }} recensioner)
                            </span>
                        </div>

                        <!-- Services -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($company->services->take(3) as $service)
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-xs font-medium">
                                    {{ $service->name }}
                                </span>
                            @endforeach
                            @if($company->services->count() > 3)
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs font-medium">
                                    +{{ $company->services->count() - 3 }} till
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('public.company.show', $company->id) }}" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Se företag & recensioner
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('public.companies') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold text-lg hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    Se alla företag
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                Redo att komma igång?
            </h2>
            <p class="text-xl mb-8 text-blue-100">
                Hitta och boka din tjänst idag - det tar mindre än 2 minuter
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;" 
                   data-conversion="search"
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 rounded-xl font-bold text-lg hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Sök tjänster nu
                </a>
                <a href="{{ route('about') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-bold text-lg hover:bg-white hover:text-blue-600 transform hover:scale-105 transition-all duration-300">
                    Läs mer om oss
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                </div>
        </div>
    </section>
</div>
@endsection
