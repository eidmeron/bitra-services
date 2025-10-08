<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>
                <p class="text-sm text-gray-400">Admin Panel</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-800' : '' }}">
                    📅 Bokningar
                </a>
                <a href="{{ route('admin.companies.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.companies.*') ? 'bg-gray-800' : '' }}">
                    🏢 Företag
                </a>
                <a href="{{ route('admin.services.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.services.*') ? 'bg-gray-800' : '' }}">
                    🛠️ Tjänster
                </a>
                <a href="{{ route('admin.forms.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.forms.*') ? 'bg-gray-800' : '' }}">
                    📝 Formulär
                </a>
                <a href="{{ route('admin.cities.index') }}" class="block px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.cities.*') ? 'bg-gray-800' : '' }}">
                    🏙️ Städer
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-800 rounded">
                        🚪 Logga ut
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>

