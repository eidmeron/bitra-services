<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Företag') - {{ config('app.name') }}</title>
    
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
                    <a href="{{ route('company.dashboard') }}" class="flex items-center">
                        <span class="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                            Bitra Tjänster
                        </span>
                        <span class="ml-3 px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">
                            FÖRETAG
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <a href="{{ route('company.dashboard') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('company.dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">🏠</span> Översikt
                    </a>
                    
                    <a href="{{ route('company.bookings.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('company.bookings.*') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">📋</span> Bokningar
                        @php
                            $pendingCount = \App\Models\Booking::where('company_id', auth()->user()->company?->id)
                                ->where('status', 'assigned')
                                ->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="ml-1 px-2 py-0.5 text-xs font-semibold bg-red-500 text-white rounded-full">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('company.slot-times.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('company.slot-times.*') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">⏰</span> Tidsluckor
                    </a>
                    
                    <a href="{{ route('company.messages.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('company.messages.*') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">💬</span> Meddelanden
                        @php
                            $newMessagesCount = \App\Models\CompanyMessage::where('company_id', auth()->user()->company?->id)
                                ->where('status', 'new')
                                ->count();
                        @endphp
                        @if($newMessagesCount > 0)
                            <span class="ml-1 px-2 py-0.5 text-xs font-semibold bg-red-500 text-white rounded-full">
                                {{ $newMessagesCount }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('company.complaints.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('company.complaints.*') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">📝</span> Reklamationer
                    </a>
                    
                    <a href="{{ route('company.profile') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('company.profile') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <span class="mr-1">🏢</span> Företagsprofil
                    </a>
                    
                    <!-- Company Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                            <span class="mr-2">👤</span>
                            <span>{{ auth()->user()->company?->company_name ?? auth()->user()->name }}</span>
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
                             class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200"
                             style="display: none;">
                            
                            <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
                                <div class="font-semibold">{{ auth()->user()->company?->company_name }}</div>
                                <div>{{ auth()->user()->email }}</div>
                            </div>
                            
                            <a href="{{ route('company.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="mr-2">🏢</span> Företagsprofil
                            </a>
                            
                            <a href="{{ route('company.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <span class="mr-2">⚙️</span> Inställningar
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
                <a href="{{ route('company.dashboard') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('company.dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <span class="mr-2">🏠</span> Översikt
                </a>
                
                <a href="{{ route('company.bookings.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('company.bookings.*') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <span class="mr-2">📋</span> Bokningar
                </a>
                
                <a href="{{ route('company.slot-times.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('company.slot-times.*') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <span class="mr-2">⏰</span> Tidsluckor
                </a>
                
                <a href="{{ route('company.messages.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('company.messages.*') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <span class="mr-2">💬</span> Meddelanden
                </a>
                
                <a href="{{ route('company.profile') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <span class="mr-2">🏢</span> Företagsprofil
                </a>
                
                <a href="{{ route('company.settings') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    <span class="mr-2">⚙️</span> Inställningar
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

    <!-- Approval Banner (if pending approval) -->
    @if(auth()->user()->company && auth()->user()->company->status === 'pending')
        <div class="bg-yellow-50 border-b border-yellow-200">
            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">⏳</span>
                        <p class="text-sm font-medium text-yellow-900">
                            Ditt företag väntar på godkännande från administratören. Du kan inte ta emot bokningar ännu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->company && auth()->user()->company->status === 'suspended')
        <div class="bg-red-50 border-b border-red-200">
            <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">🚫</span>
                        <p class="text-sm font-medium text-red-900">
                            Ditt företagskonto har blivit inaktiverat. Kontakta support för mer information.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                    <a href="#" class="text-gray-600 hover:text-blue-600">Företagsvillkor</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600">Hjälp</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

