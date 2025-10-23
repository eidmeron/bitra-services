@extends('layouts.public')

@section('title', $service->name . ' - Professionell Tjänst')

@section('meta_description', $service->description)

@section('meta_keywords', $service->name . ', ' . ($service->category->name ?? 'tjänster') . ', bokning, professionell')

@push('seo')
<meta property="og:title" content="{{ $service->name }}">
<meta property="og:description" content="{{ $service->description }}">
@if($service->image)
<meta property="og:image" content="{{ Storage::url($service->image) }}">
@endif
<meta property="og:type" content="service">
<link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section with Image -->
    <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Text Content -->
                <div class="z-10">
                    <!-- Breadcrumb -->
                    <div class="text-sm text-blue-200 mb-4">
                        <a href="{{ route('welcome') }}" class="hover:text-white">Hem</a>
                        <span class="mx-2">›</span>
                        <a href="{{ route('public.services') }}" class="hover:text-white">Tjänster</a>
                        @if($service->category)
                            <span class="mx-2">›</span>
                            <a href="{{ route('public.category.show', $service->category->slug) }}" class="hover:text-white">
                                {{ $service->category->name }}
                            </a>
                        @endif
                        <span class="mx-2">›</span>
                        <span>{{ $service->name }}</span>
                    </div>

                    <!-- Category Badge -->
                    @if($service->category)
                        <span class="inline-block px-4 py-1 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full mb-4">
                            {{ $service->category->name }}
                        </span>
                    @endif

                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        {{ $service->icon ?? '🛠️' }} {{ $service->name }}
                    </h1>
                    <p class="text-xl text-blue-100 mb-8">
                        {{ $service->description }}
                    </p>

                    <div class="flex flex-wrap gap-4">
                        @php
                            $activeForm = $service->active_form;
                            $hasForm = $activeForm && $activeForm->token;
                        @endphp
                        @if($hasForm)
                            <a href="{{ route('public.form', $activeForm->token) }}" 
                               class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Boka {{ $service->name }} Nu
    </a>
    @endif
                        @if($service->base_price)
                            <div class="inline-flex items-center px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-bold rounded-lg border-2 border-white/30">
                                <span class="text-sm mr-2">Från</span>
                                <span class="text-2xl">{{ number_format($service->base_price, 0, ',', ' ') }} kr</span>
                            </div>
                        @endif
                        @if($service->rot_eligible)
                            <div class="inline-flex items-center px-6 py-4 bg-green-500 text-white font-bold rounded-lg shadow-lg">
                                🏡 ROT-avdrag {{ $service->rot_percent }}%
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Hero Image -->
                <div class="relative z-10">
                    @if($service->image)
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-300">
                            <img src="{{ Storage::url($service->image) }}" 
                                 alt="{{ $service->name }}" 
                                 class="w-full h-[400px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        </div>
                    @else
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-12 flex items-center justify-center h-[400px] border-2 border-white/30">
                            <span class="text-9xl">{{ $service->icon ?? '🛠️' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Decorative Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full filter blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Full Content -->
                @if($service->full_content)
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="text-3xl mr-3">📝</span>
                            Om Tjänsten
                        </h2>
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($service->full_content)) !!}
                        </div>
                    </div>
                @endif

                <!-- What's Included - Enhanced Card -->
                @if($service->includes && count($service->includes) > 0)
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-xl p-8 border-2 border-green-200">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-2xl mr-4 shadow-lg">✅</span>
                            Vad Ingår
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($service->includes as $include)
                                <div class="flex items-start space-x-3 p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-800 font-medium">{{ $include }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Features - Enhanced Card -->
                @if($service->features && count($service->features) > 0)
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-xl p-8 border-2 border-blue-200">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-2xl mr-4 shadow-lg">⭐</span>
                            Funktioner & Fördelar
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($service->features as $feature)
                                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-lg transition-all hover:-translate-y-1">
                                    <div class="flex items-start space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 text-2xl">
                                            {{ is_array($feature) && isset($feature['icon']) ? $feature['icon'] : '✨' }}
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 mb-2">
                                                {{ is_array($feature) && isset($feature['title']) ? $feature['title'] : $feature }}
                                            </h3>
                                            @if(is_array($feature) && isset($feature['description']))
                                                <p class="text-sm text-gray-600 leading-relaxed">{{ $feature['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- FAQ - Enhanced Card -->
                @if($service->faq && count($service->faq) > 0)
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-xl p-8 border-2 border-purple-200">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-2xl mr-4 shadow-lg">❓</span>
                            Vanliga Frågor
                        </h2>
                        <div class="space-y-4">
                            @foreach($service->faq as $index => $faq)
                                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow" x-data="{ open: false }">
                                    <button 
                                        type="button"
                                        @click="open = !open"
                                        class="w-full text-left px-6 py-5 hover:bg-gray-50 transition flex justify-between items-center">
                                        <span class="font-bold text-gray-900 pr-4">{{ is_array($faq) ? $faq['question'] : $faq }}</span>
                                        <svg class="w-5 h-5 text-purple-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="open" x-collapse class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                        <p class="text-gray-700 leading-relaxed">{{ is_array($faq) ? $faq['answer'] : 'Kontakta oss för mer information.' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Available Companies -->
                @if($companies->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="text-3xl mr-3">🏢</span>
                            Företag som Erbjuder Denna Tjänst
                        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($companies as $company)
                                <a href="{{ route('public.company.show', $company) }}" 
                                   class="block p-6 border border-gray-200 rounded-lg hover:border-blue-600 hover:shadow-lg transition">
                                    <div class="flex items-start space-x-4">
                                        @if($company->logo)
                                            <img src="{{ Storage::url($company->logo) }}" 
                                                 alt="{{ $company->company_name }}" 
                                                 class="w-16 h-16 rounded-lg object-cover">
                                        @else
                                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                                                {{ strtoupper(substr($company->company_name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 mb-1">{{ $company->company_name }}</h3>
                                            @if($company->reviews_avg_rating)
                                                <div class="flex items-center text-yellow-500 mb-2">
                                                    <span>⭐</span>
                                                    <span class="ml-1 text-gray-900 font-semibold">{{ number_format($company->reviews_avg_rating, 1) }}</span>
                                                    <span class="text-gray-500 text-sm ml-1">({{ $company->reviews_count }})</span>
                                                </div>
                                            @endif
                                            <p class="text-sm text-gray-600 line-clamp-2">{{ $company->description }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Book Card -->
                @if($hasForm)
                    <div class="bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-2xl shadow-2xl p-6 sticky top-6">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <span class="text-2xl mr-2">📅</span>
                            Boka Direkt
                        </h3>
                        <p class="text-blue-100 mb-6">
                            Välj tid och datum som passar dig. Få bekräftelse direkt och prisberäkning!
                        </p>
                        <a href="{{ route('public.form', $activeForm->token) }}" 
                           class="block w-full text-center px-6 py-4 bg-white text-blue-600 font-bold rounded-lg hover:bg-blue-50 transition shadow-lg text-lg">
                            Boka Nu & Beräkna Pris →
                        </a>
                        @if($service->base_price)
                            <div class="mt-4 pt-4 border-t border-white/30 text-center">
                                <div class="text-sm text-blue-200">Priser från</div>
                                <div class="text-3xl font-bold mt-1">
                                    {{ number_format($service->base_price, 0, ',', ' ') }} kr
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Pricing Info -->
                @if($service->base_price)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <span class="text-2xl mr-2">💰</span>
                            Priser
                        </h3>
                        <div class="mb-4">
                            <div class="text-sm text-gray-600">Från</div>
                            <div class="text-3xl font-bold text-gray-900">
                                {{ number_format($service->base_price, 0, ',', ' ') }} kr
                            </div>
                        </div>
                        @if($service->rot_eligible)
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center mb-2">
                                    <span class="text-2xl mr-2">🏡</span>
                                    <span class="font-bold text-green-800">ROT-avdrag</span>
                                </div>
                                <p class="text-sm text-green-700">
                                    Du får {{ $service->rot_percent }}% ROT-avdrag på arbetskostnaden.
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Service Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <span class="text-2xl mr-2">ℹ️</span>
                        Information
                    </h3>
                    <div class="space-y-3">
                        @if($service->category)
                            <div class="flex items-center text-sm">
                                <span class="text-gray-600 w-24">Kategori:</span>
                                <span class="font-medium">{{ $service->category->name }}</span>
                            </div>
                        @endif
                        @if($service->one_time_booking)
                            <div class="flex items-center text-sm">
                                <span class="text-gray-600 w-24">Typ:</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Engångsbokning</span>
                            </div>
                        @endif
                        @if($service->subscription_booking)
                            <div class="flex items-center text-sm">
                                <span class="text-gray-600 w-24">Typ:</span>
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">Prenumeration</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cities Available -->
                @if($service->cities->count() > 0)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <span class="text-2xl mr-2">📍</span>
                            Tillgänglig i
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($service->cities as $city)
                                <a href="{{ route('public.city.show', $city->slug) }}" 
                                   class="px-3 py-1 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 text-sm rounded transition">
                                    {{ $city->name }}
                                </a>
            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Contact Support -->
                <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl shadow-lg p-6 border border-purple-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                        <span class="text-2xl mr-2">💬</span>
                        Behöver du hjälp?
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Har du frågor om denna tjänst? Kontakta oss så hjälper vi dig!
                    </p>
                    <a href="{{ route('contact') }}" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition">
                        Kontakta Oss
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
