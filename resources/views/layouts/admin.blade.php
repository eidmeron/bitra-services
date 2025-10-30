<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ site_name() }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .notification-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 via-blue-800 to-blue-900 text-white flex-shrink-0 shadow-2xl flex flex-col h-screen fixed">
            <!-- Header (Fixed) -->
            <div class="p-6 border-b border-blue-700/50 flex-shrink-0">
                @if(site_logo())
                    <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="h-10 mb-2">
                @endif
                <h1 class="text-2xl font-bold text-white">{{ site_name() }}</h1>
                <p class="text-sm text-blue-200 mt-1">Admin Panel</p>
            </div>
            
            <!-- Navigation (Scrollable) -->
            <nav class="flex-1 overflow-y-auto mt-6 px-3 pb-4">
                @php 
                    $notificationService = app(\App\Services\NotificationService::class); 
                    $counts = $notificationService->getNotificationCounts(); 
                @endphp
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📊</span>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.bookings.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📅</span>
                    <span class="font-medium">Bokningar</span>
                    @if($counts['new_bookings'] > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 notification-badge">{{ $counts['new_bookings'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.companies.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.companies.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">🏢</span>
                    <span class="font-medium">Företag</span>
                    @if($counts['new_companies'] > 0 || $counts['pending_companies'] > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 notification-badge">{{ $counts['new_companies'] + $counts['pending_companies'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">👥</span>
                    <span class="font-medium">Användare</span>
                    @if($counts['new_users'] > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 notification-badge">{{ $counts['new_users'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📂</span>
                    <span class="font-medium">Kategorier</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.services.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">🛠️</span>
                    <span class="font-medium">Tjänster</span>
                </a>
                <a href="{{ route('admin.slot-times.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.slot-times.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">⏰</span>
                    <span class="font-medium">Tidsluckor</span>
                </a>
                <!-- Payments Section -->
                <div class="mt-6 mb-2 px-4">
                    <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Betalningar</p>
                </div>
                <a href="{{ route('admin.deposits.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.deposits.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">💰</span>
                    <span class="font-medium">Deposits</span>
                </a>
                <a href="{{ route('admin.deposits.weekly-reports') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.deposits.weekly-reports') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📈</span>
                    <span class="font-medium">Veckorapporter</span>
                </a>
                <a href="{{ route('admin.invoices.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.invoices.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">🧾</span>
                    <span class="font-medium">Fakturor</span>
                </a>
        <a href="{{ route('admin.loyalty-points.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.loyalty-points.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
            <span class="text-xl mr-3">⭐</span>
            <span class="font-medium">Lojalitetspoäng</span>
        </a>
        
        <!-- Analytics Section -->
        <div class="mt-6 mb-2 px-4">
            <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Analytics</p>
        </div>
        <a href="{{ route('admin.analytics.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.analytics.index') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
            <span class="text-xl mr-3">📊</span>
            <span class="font-medium">Analytics</span>
        </a>
        <a href="{{ route('admin.analytics.settings') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.analytics.settings') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
            <span class="text-xl mr-3">⚙️</span>
            <span class="font-medium">Analytics Inställningar</span>
        </a>
        
        <!-- Settings Section -->
        <div class="mt-6 mb-2 px-4">
            <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Inställningar</p>
        </div>
        <a href="{{ route('admin.notification-settings.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.notification-settings.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
            <span class="text-xl mr-3">🔔</span>
            <span class="font-medium">Notifieringar</span>
        </a>
        <a href="{{ route('admin.email-templates.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.email-templates.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
            <span class="text-xl mr-3">📧</span>
            <span class="font-medium">E-postmallar</span>
        </a>
                <a href="{{ route('admin.email-marketing.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.email-marketing.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📧</span>
                    <span class="font-medium">Email Marketing</span>
                </a>
                <a href="{{ route('admin.complaints.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.complaints.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📝</span>
                    <span class="font-medium">Reklamationer</span>
                </a>
                <a href="{{ route('admin.forms.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.forms.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📝</span>
                    <span class="font-medium">Formulär</span>
                </a>
                <a href="{{ route('admin.zones.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.zones.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">🗺️</span>
                    <span class="font-medium">Zoner</span>
                </a>
                <a href="{{ route('admin.cities.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.cities.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">🏙️</span>
                    <span class="font-medium">Städer</span>
                </a>
                
                <!-- CMS Section -->
                <div class="mt-6 mb-2 px-4">
                    <p class="text-xs font-semibold text-blue-300 uppercase tracking-wider">Innehåll & SEO</p>
                </div>
                <a href="{{ route('admin.pages.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.pages.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📄</span>
                    <span class="font-medium">Sidinnehåll</span>
                </a>
                {{-- SEO Pages removed as requested --}}
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">⚙️</span>
                    <span class="font-medium">Inställningar</span>
                </a>
                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.contact-messages.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">📧</span>
                    <span class="font-medium">Kontaktmeddelanden</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reviews.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">⭐</span>
                    <span class="font-medium">Recensioner</span>
                    @if(($counts['new_platform_reviews'] ?? 0) > 0 || ($counts['pending_platform_reviews'] ?? 0) > 0 || ($counts['new_company_reviews'] ?? 0) > 0 || ($counts['pending_company_reviews'] ?? 0) > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 notification-badge">{{ ($counts['new_platform_reviews'] ?? 0) + ($counts['pending_platform_reviews'] ?? 0) + ($counts['new_company_reviews'] ?? 0) + ($counts['pending_company_reviews'] ?? 0) }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.seo-pages.index') }}" class="flex items-center px-4 py-3 mb-1 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.seo-pages.*') ? 'bg-blue-700 shadow-lg' : 'hover:bg-blue-800/50' }}">
                    <span class="text-xl mr-3">🔍</span>
                    <span class="font-medium">SEO-sidor</span>
                </a>
            </nav>
            
            <!-- User Section (Fixed at Bottom) -->
            <div class="w-64 p-4 border-t border-blue-700/50 bg-blue-900/50 flex-shrink-0">
                <div class="flex items-center mb-3 px-2">
                    <div class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        {{ strtoupper(substr(auth()->user()->email, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->email }}</p>
                        <p class="text-xs text-blue-300">Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 font-medium">
                        <span class="mr-2">🚪</span>
                        Logga ut
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden ml-64">
            <!-- Top Bar -->
            <header class="bg-white shadow-md border-b border-gray-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500 mt-0.5">@yield('subtitle', 'Hantera din plattform')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @php
                                    $unreadCount = auth()->user()->unreadNotifications()->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 block h-5 w-5 rounded-full bg-red-500 text-white text-xs font-bold flex items-center justify-center notification-badge">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            
                            <!-- Notifications Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-2xl border border-gray-200 z-50" 
                                 style="display: none;">
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-bold text-gray-900">Notifikationer</h3>
                                        @if($unreadCount > 0)
                                            <a href="{{ route('admin.notifications.mark-all-read') }}" 
                                               onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();"
                                               class="text-xs text-blue-600 hover:text-blue-800">
                                                Markera alla som lästa
                                            </a>
                                            <form id="mark-all-read-form" action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="hidden">
                                                @csrf
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                        <a href="{{ route('admin.notifications.read', $notification->id) }}" 
                                           class="block p-4 hover:bg-gray-50 border-b border-gray-100 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mr-3">
                                                    <span class="text-2xl">{{ $notification->data['icon'] ?? '🔔' }}</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900">{{ $notification->data['title'] ?? 'Notifikation' }}</p>
                                                    <p class="text-xs text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="p-8 text-center text-gray-500">
                                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-sm">Inga notifikationer</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if(auth()->user()->notifications()->count() > 5)
                                    <div class="p-3 border-t border-gray-200 text-center">
                                        <a href="{{ route('admin.notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Visa alla notifikationer →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="flex items-center space-x-3 border-l pl-4">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800">{{ auth()->user()->email }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr(auth()->user()->email, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-900 p-4 rounded-lg mb-6 shadow-sm">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">✅</span>
                                <div>
                                    <p class="font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-900 p-4 rounded-lg mb-6 shadow-sm">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">❌</span>
                                <div>
                                    <p class="font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>
