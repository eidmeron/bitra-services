<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Min Sida') - {{ config('app.name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Toast Notification -->
    <x-toast />
    
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center">
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Bitra Tjänster
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <a href="{{ route('user.dashboard') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">🏠</span> Översikt
                    </a>
                    
                    <a href="{{ route('user.bookings.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('user.bookings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">📋</span> Mina Bokningar
                    </a>
                    
                    <a href="{{ route('welcome') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                        <span class="mr-1">🏪</span> Boka Tjänst
                    </a>
                    
                    <a href="{{ route('user.complaints.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('user.complaints.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">📝</span> Mina Reklamationer
                    </a>
                    
                    <!-- Notifications -->
                    <div class="relative">
                        <a href="{{ route('user.dashboard') }}#activity" class="relative p-2 text-gray-700 hover:bg-gray-100 rounded-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                    {{ auth()->user()->unreadNotifications()->count() }}
                                </span>
                            @endif
                        </a>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                            <span class="mr-2">👤</span>
                            <span>{{ auth()->user()->name ?? auth()->user()->email }}</span>
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                             style="display: none;">
                            
                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="mr-2">⚙️</span> Min Profil
                            </a>
                            
                            <a href="{{ route('public.booking.check.form') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="mr-2">🔍</span> Kolla Bokning
                            </a>
                            
                            <div class="border-t border-gray-100"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <span class="mr-2">🚪</span> Logga ut
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button x-data @click="$dispatch('toggle-mobile-menu')" class="p-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-data="{ open: false }" 
             @toggle-mobile-menu.window="open = !open"
             x-show="open" 
             x-transition
             class="md:hidden border-t border-gray-200"
             style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('user.dashboard') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('user.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <span class="mr-2">🏠</span> Översikt
                </a>
                
                <a href="{{ route('user.bookings.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('user.bookings.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <span class="mr-2">📋</span> Mina Bokningar
                </a>
                
                <a href="{{ route('welcome') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <span class="mr-2">🏪</span> Boka Tjänst
                </a>
                
                <a href="{{ route('user.profile') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <span class="mr-2">⚙️</span> Min Profil
                </a>
                
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                            <span class="mr-2">🚪</span> Logga ut
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        @if(View::hasSection('breadcrumb'))
            <nav class="mb-6 text-sm">
                @yield('breadcrumb')
            </nav>
        @endif

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                <div class="flex items-center">
                    <span class="mr-2">✅</span>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                <div class="flex items-center">
                    <span class="mr-2">❌</span>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                <div class="flex items-center mb-2">
                    <span class="mr-2">⚠️</span>
                    <p class="font-semibold">Det finns fel i formuläret:</p>
                </div>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    © {{ date('Y') }} {{ config('app.name') }}. Alla rättigheter förbehållna.
                </div>
                <div class="flex space-x-6 text-sm">
                    <a href="#" class="text-gray-600 hover:text-blue-600">Support</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Integritetspolicy</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Användarvillkor</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

