@extends('layouts.admin')

@section('title', 'Bokningar')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📅 Bokningar</h1>
            <p class="text-gray-600 mt-1">Hantera och övervaka alla bokningar i systemet</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 text-2xl">📊</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Totalt</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-yellow-600 text-2xl">⏳</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Väntande</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-green-600 text-2xl">✅</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Slutförda</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <span class="text-purple-600 text-2xl">💰</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Total intäkt</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} kr</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">Tilldelade</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['assigned'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">Pågående</p>
            <p class="text-xl font-bold text-orange-600">{{ $stats['in_progress'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">Idag</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['today'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">Denna vecka</p>
            <p class="text-xl font-bold text-purple-600">{{ $stats['this_week'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">Månadsin täkt</p>
            <p class="text-xl font-bold text-indigo-600">{{ number_format($stats['monthly_revenue'], 0, ',', ' ') }} kr</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Filtrera bokningar</h3>
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sök</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Bokningsnr, namn, e-post, telefon..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Alla</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Väntande</option>
                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Tilldelad</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Pågående</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Slutförd</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Avbruten</option>
                </select>
            </div>

            <!-- Service -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tjänst</label>
                <select name="service_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Alla tjänster</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Company -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Företag</label>
                <select name="company_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Alla företag</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name ?? $company->user->name ?? 'Företag #' . $company->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sortera</label>
                <select name="sort" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Senaste</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Äldsta</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Högsta pris</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lägsta pris</option>
                    <option value="booking_number" {{ request('sort') == 'booking_number' ? 'selected' : '' }}>Bokningsnr</option>
                    <option value="customer_name" {{ request('sort') == 'customer_name' ? 'selected' : '' }}>Kundnamn</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="md:col-span-6 flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Filtrera
                </button>
                <a href="{{ route('admin.bookings.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Rensa
                </a>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Bokningar ({{ $bookings->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bokningsnr</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kund</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kundtyp</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tjänst</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Företag</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pris</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-blue-600">#{{ $booking->booking_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($booking->customer_name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="font-semibold text-gray-900">{{ $booking->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->customer_email }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->customer_phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($booking->customer_type === 'company')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    🏢 Företag
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    🏠 Privatperson
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $booking->service->name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->city->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($booking->company)
                                <div class="font-semibold text-gray-900">{{ $booking->company->company_name ?? $booking->company->user->name ?? 'N/A' }}</div>
                            @else
                                <span class="text-gray-400">Ej tilldelad</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ number_format($booking->final_price, 0, ',', ' ') }} kr</div>
                            @if($booking->rot_deduction > 0)
                                <div class="text-xs text-green-600">ROT: -{{ number_format($booking->rot_deduction, 0, ',', ' ') }} kr</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {!! bookingStatusBadge($booking->status) !!}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $booking->created_at->format('Y-m-d') }}</div>
                            <div class="text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</div>
                            @if($booking->created_at->isToday())
                                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full mt-1">
                                    🆕 Idag
                                </span>
                            @elseif($booking->created_at->isYesterday())
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full mt-1">
                                    📅 Igår
                                </span>
                            @elseif($booking->created_at->isCurrentWeek())
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full mt-1">
                                    📆 Denna vecka
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.bookings.show', $booking) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Visa detaljer">
                                    👁️
                                </a>

                                <!-- Quick Status Change -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" 
                                            class="text-orange-600 hover:text-orange-900" 
                                            title="Ändra status">
                                        🔄
                                    </button>
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 border border-gray-200"
                                         style="display: none;">
                                        <div class="p-2">
                                            <form action="{{ route('admin.bookings.change-status', $booking) }}" method="POST">
                                                @csrf
                                                <select name="status" 
                                                        onchange="this.form.submit()"
                                                        class="w-full rounded-lg border-gray-300 text-sm">
                                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>⏳ Väntande</option>
                                                    <option value="assigned" {{ $booking->status == 'assigned' ? 'selected' : '' }}>📋 Tilldelad</option>
                                                    <option value="in_progress" {{ $booking->status == 'in_progress' ? 'selected' : '' }}>🔄 Pågående</option>
                                                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>✅ Slutförd</option>
                                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>❌ Avbruten</option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Reassignment -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" 
                                            class="text-purple-600 hover:text-purple-900" 
                                            title="Ändra företag">
                                        🏢
                                    </button>
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition
                                         class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl z-50 border border-gray-200"
                                         style="display: none;">
                                        <div class="p-3">
                                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Tilldela till företag</h4>
                                            <form action="{{ route('admin.bookings.reassign-company', $booking) }}" method="POST">
                                                @csrf
                                                <select name="company_id" 
                                                        class="w-full rounded-lg border-gray-300 text-sm mb-3">
                                                    <option value="">-- Välj företag --</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->id }}" 
                                                                {{ $booking->company_id == $company->id ? 'selected' : '' }}>
                                                            {{ $company->company_name ?? $company->user->name ?? 'Företag #' . $company->id }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="flex space-x-2">
                                                    <button type="submit" 
                                                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-xs px-3 py-1 rounded-lg">
                                                        Tilldela
                                                    </button>
                                                    <button type="button" 
                                                            @click="open = false"
                                                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 text-xs px-3 py-1 rounded-lg">
                                                        Avbryt
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email Customer -->
                                <a href="{{ route('admin.bookings.show', $booking) }}#email" 
                                   class="text-green-600 hover:text-green-900" 
                                   title="Skicka e-post">
                                    📧
                                </a>

                                <form action="{{ route('admin.bookings.destroy', $booking) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('Är du säker på att du vill radera denna bokning?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900" 
                                            title="Radera">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-6xl mb-4">📭</div>
                            <p class="text-xl font-semibold">Inga bokningar hittades</p>
                            <p class="text-sm mt-2">Bokningar från kunder visas här när de skickas in.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
        <div class="bg-gray-50 px-6 py-4">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
