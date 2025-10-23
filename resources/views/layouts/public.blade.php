<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Dynamic SEO from CMS --}}
    <meta name="description" content="@yield('meta_description', setting('seo_default_description', 'Boka professionella tjänster i hela Sverige'))">
    <meta name="keywords" content="@yield('meta_keywords', setting('seo_keywords', 'tjänster, bokning, städning'))">
    
    {{-- Title with fallback to CMS setting --}}
    <title>@yield('title', setting('seo_default_title', 'Boka Professionella Tjänster')) - {{ site_name() }}</title>
    
    {{-- Open Graph / Social Media Meta Tags --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', setting('seo_default_title', 'Boka Professionella Tjänster'))">
    <meta property="og:description" content="@yield('og_description', setting('seo_default_description', 'Boka professionella tjänster i hela Sverige'))">
    @if(setting('seo_og_image'))
        <meta property="og:image" content="{{ Storage::url(setting('seo_og_image')) }}">
    @endif
    <meta property="og:site_name" content="{{ site_name() }}">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', setting('seo_default_title', 'Boka Professionella Tjänster'))">
    <meta name="twitter:description" content="@yield('twitter_description', setting('seo_default_description', 'Boka professionella tjänster i hela Sverige'))">
    @if(setting('seo_og_image'))
        <meta name="twitter:image" content="{{ Storage::url(setting('seo_og_image')) }}">
    @endif
    
    {{-- Favicon --}}
    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('head')
    @stack('styles')
    
    <!-- SEO -->
    <link rel="canonical" href="@yield('canonical', url()->current())">
    
    {{-- Additional SEO from page content --}}
    @stack('seo')
</head>
<body class="bg-gray-50">
    <!-- Toast Notification -->
    <x-toast />
    
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-4">
                    @if(setting('header_phone'))
                        <a href="tel:{{ setting('header_phone') }}" class="flex items-center hover:text-blue-200 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ setting('header_phone') }}
                        </a>
                    @endif
                    @if(setting('header_email'))
                        <a href="mailto:{{ setting('header_email') }}" class="hidden md:flex items-center hover:text-blue-200 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ setting('header_email') }}
                        </a>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    @auth
                        <a href="{{ route('public.booking.check.form') }}" class="hover:text-blue-200">
                            🔍 Kolla Bokning
                        </a>
                        <span class="text-blue-300">|</span>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-200">
                                <span class="mr-1">⚡</span> Admin
                            </a>
                        @elseif(auth()->user()->isCompany())
                            <a href="{{ route('company.dashboard') }}" class="hover:text-blue-200">
                                <span class="mr-1">🏢</span> Min Sida
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="hover:text-blue-200">
                                <span class="mr-1">👤</span> Min Sida
                            </a>
                        @endif
                    @else
                        <a href="{{ route('public.booking.check.form') }}" class="hover:text-blue-200">
                            🔍 Kolla Bokning
                        </a>
                        <span class="text-blue-300">|</span>
                        <a href="{{ route('login') }}" class="hover:text-blue-200">Logga in</a>
                        <span class="text-blue-300">|</span>
                        <a href="{{ route('register') }}" class="hover:text-blue-200">Skapa konto</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center">
                        @if(site_logo())
                            <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="h-12 w-auto mr-3">
                        @else
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-2 mr-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                {{ site_name() }}
                            </span>
                            <p class="text-xs text-gray-600">{{ setting('site_tagline', 'Sveriges bästa tjänsteplattform') }}</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-blue-600 font-medium transition {{ request()->routeIs('welcome') ? 'text-blue-600' : '' }}">
                        Hem
                    </a>
                    <a href="{{ route('public.categories') }}" class="text-gray-700 hover:text-blue-600 font-medium transition {{ request()->routeIs('public.categories*') ? 'text-blue-600' : '' }}">
                        Kategorier
                    </a>
                    <a href="{{ route('public.services') }}" class="text-gray-700 hover:text-blue-600 font-medium transition {{ request()->routeIs('public.services') ? 'text-blue-600' : '' }}">
                        Tjänster
                    </a>
                    
    <!-- Priser Dropdown -->
    <div class="relative group">
        <a href="{{ route('public.pricing.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition {{ request()->routeIs('public.pricing*') ? 'text-blue-600' : '' }}">
            💰 Priser
        </a>
        <div class="absolute top-full left-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
            <div class="py-2">
                <a href="{{ route('public.pricing.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 font-semibold">
                    📊 Alla priser
                </a>
                <div class="border-t border-gray-100 my-1"></div>
                @php
                    $categories = \App\Models\Category::where('status', 'active')
                        ->with(['services' => function($query) {
                            $query->where('status', 'active')->orderBy('name');
                        }])
                        ->orderBy('name')
                        ->get();
                @endphp
                <div class="grid grid-cols-1 gap-1">
                    @foreach($categories as $category)
                        @if($category->services->count() > 0)
                            <div class="px-4 py-2 hover:bg-gray-50">
                                <div class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                    @if($category->icon)
                                        <span class="mr-2">{{ $category->icon }}</span>
                                    @endif
                                    {{ $category->name }}
                                </div>
                                <div class="ml-4 space-y-1">
                                    @foreach($category->services->take(6) as $service)
                                        <a href="{{ route('public.pricing.service', $service->slug) }}" class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1 rounded transition-colors">
                                            {{ $service->name }}
                                        </a>
                                    @endforeach
                                    @if($category->services->count() > 6)
                                        <a href="{{ route('public.pricing.index', ['category' => $category->id]) }}" class="block text-xs text-blue-600 hover:text-blue-800 px-2 py-1 font-medium">
                                            + {{ $category->services->count() - 6 }} fler tjänster...
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @if(!$loop->last)
                                <div class="border-t border-gray-100 mx-4"></div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
                    
                <a href="{{ route('public.companies') }}" class="text-gray-700 hover:text-blue-600 font-medium transition {{ request()->routeIs('public.companies') ? 'text-blue-600' : '' }}">
                    Partner
                </a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 font-medium transition {{ request()->routeIs('about') ? 'text-blue-600' : '' }}">
                    Om oss
                </a>
                <a href="{{ route('reviews.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition {{ request()->routeIs('reviews.*') ? 'text-blue-600' : '' }}">
                    ⭐ Recensioner
                </a>
                    
                <!-- CTA Button -->
                    <a href="{{ route('public.services') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Boka nu
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <button x-data @click="$dispatch('toggle-mobile-menu')" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-data="{ open: false }" 
             @toggle-mobile-menu.window="open = !open"
             x-show="open" 
             x-transition
             class="md:hidden border-t border-gray-200 bg-white"
             style="display: none;">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('welcome') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                    Hem
                </a>
                <a href="{{ route('public.categories') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                    Kategorier
                </a>
                <a href="{{ route('public.services') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                    Tjänster
                </a>
                <a href="{{ route('public.pricing.index') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                    💰 Priser
                </a>
                <a href="{{ route('public.companies') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                    Partner
                </a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                    Om oss
                </a>
                <a href="{{ route('reviews.index') }}" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 font-medium">
                    ⭐ Recensioner
                </a>
                <a href="{{ route('public.services') }}" class="block px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold text-center">
                    Boka nu
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Component -->
    <x-footer />

    @stack('scripts')
    
</body>
</html>
