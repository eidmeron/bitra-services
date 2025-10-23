@extends('layouts.user')

@section('title', 'Min Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">👋 Välkommen, {{ auth()->user()->name ?: 'tillbaka' }}!</h1>
            <p class="text-blue-100 text-lg">Här kan du hantera dina bokningar och se din aktivitet</p>
        </div>
        <div class="hidden md:block text-6xl opacity-50">
            📊
        </div>
    </div>
</div>

<!-- Main Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Loyalty Points -->
    @if(setting('loyalty_points_enabled', true))
    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-purple-100 uppercase tracking-wide">Lojalitetspoäng</p>
                <p class="text-4xl font-bold mt-2">{{ number_format(auth()->user()->loyalty_points_balance, 0) }}</p>
                <p class="text-sm text-purple-100 mt-1">≈ {{ number_format(auth()->user()->loyalty_points_balance * (float)setting('loyalty_points_value', 1), 2) }} kr</p>
            </div>
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-3xl">
                ⭐
            </div>
        </div>
    </div>
    @endif

    <!-- Total Bookings -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Totala Bokningar</p>
                <p class="text-4xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_bookings']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Alla bokningar</p>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl">
                📅
            </div>
        </div>
    </div>

    <!-- Pending Bookings -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Väntande</p>
                <p class="text-4xl font-bold text-yellow-600 mt-2">{{ number_format($stats['pending_bookings']) }}</p>
                @if($stats['pending_bookings'] > 0)
                    <p class="text-sm text-yellow-700 mt-1">Väntar på bekräftelse</p>
                @else
                    <p class="text-sm text-gray-500 mt-1">Inga väntande</p>
                @endif
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center text-3xl">
                ⏳
            </div>
        </div>
    </div>

    <!-- Completed Bookings -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Slutförda</p>
                <p class="text-4xl font-bold text-green-600 mt-2">{{ number_format($stats['completed_bookings']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Genomförda tjänster</p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-3xl">
                ✅
            </div>
        </div>
    </div>

    <!-- Total Spent -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Totalt Spenderat</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($stats['total_spent'], 0, ',', ' ') }} kr</p>
                @if($stats['rot_savings'] > 0)
                    <p class="text-sm text-green-600 mt-1">🎉 ROT-sparad: {{ number_format($stats['rot_savings'], 0, ',', ' ') }} kr</p>
                @else
                    <p class="text-sm text-gray-500 mt-1">Över alla bokningar</p>
                @endif
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-3xl">
                💰
            </div>
        </div>
    </div>
</div>

<!-- ROT-avdrag Info (if applicable) -->
@if($stats['rot_savings'] > 0)
<div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center space-x-4">
        <div class="text-5xl">🎉</div>
        <div class="flex-1">
            <h3 class="text-xl font-bold text-gray-900 mb-1">ROT-avdrag Sparat</h3>
            <p class="text-gray-700">Du har sparat <span class="font-bold text-green-600">{{ number_format($stats['rot_savings'], 0, ',', ' ') }} kr</span> genom ROT-avdrag!</p>
            <p class="text-sm text-gray-600 mt-1">Detta dras automatiskt av vid din skattedeklaration.</p>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Main Content (2/3) -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Upcoming Bookings -->
        @if($upcomingBookings->count() > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">📅</span>
                    Kommande Bokningar
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @foreach($upcomingBookings as $booking)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="text-2xl">{{ $booking->service->icon ?? '🛠️' }}</span>
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $booking->service->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $booking->city->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        @if($booking->booking_date)
                                            {{ $booking->booking_date->format('Y-m-d') }}
                                        @elseif($booking->preferred_date)
                                            {{ \Carbon\Carbon::parse($booking->preferred_date)->format('Y-m-d') }}
                                        @else
                                            <span class="text-gray-400">Ej angivet</span>
                                        @endif
                                    </span>
                                    @if($booking->company)
                                        <span class="flex items-center">
                                            🏢 {{ $booking->company->company_name ?? 'Ej tilldelat' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'assigned' => 'bg-orange-100 text-orange-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'in_progress' => 'bg-purple-100 text-purple-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ bookingStatusLabel($booking->status) }}
                                    </span>
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($booking->final_price, 0, ',', ' ') }} kr</p>
                                    
                                    @if(in_array($booking->status, ['assigned', 'in_progress']) && $booking->company)
                                        <a href="{{ route('user.bookings.show', $booking) }}#chat" 
                                           class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Chatta
                                        </a>
                                    @endif
                                    
                                    @if($booking->status === 'completed' && !$booking->review)
                                        <a href="{{ route('user.bookings.show', $booking) }}#review" 
                                           class="inline-flex items-center px-3 py-1 bg-yellow-600 text-white text-xs font-semibold rounded-lg hover:bg-yellow-700 transition">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                            Recensera
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($upcomingBookings->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $upcomingBookings->links() }}
                </div>
            @endif
        </div>
        @endif

        <!-- Recent Bookings -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="text-2xl mr-3">📋</span>
                        Senaste Bokningar
                    </h3>
                    <a href="{{ route('user.bookings.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        Visa alla →
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tjänst</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Företag</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pris</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärd</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl">{{ $booking->service->icon ?? '🛠️' }}</span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->service->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->city->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($booking->booking_date)
                                        <div class="text-sm text-gray-900">{{ $booking->booking_date->format('Y-m-d') }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->booking_date->format('H:i') }}</div>
                                    @elseif($booking->preferred_date)
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->preferred_date)->format('Y-m-d') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->preferred_date)->format('H:i') }}</div>
                                    @else
                                        <div class="text-sm text-gray-500">Ej angivet</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($booking->company)
                                        <div class="text-sm text-gray-900">{{ $booking->company->company_name ?? 'N/A' }}</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Ej tilldelat</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ number_format($booking->final_price, 0, ',', ' ') }} kr</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'assigned' => 'bg-orange-100 text-orange-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Väntande',
                                            'assigned' => 'Tilldelad',
                                            'confirmed' => 'Bekräftad',
                                            'in_progress' => 'Pågående',
                                            'completed' => 'Slutförd',
                                            'cancelled' => 'Avbruten',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($booking->status === 'completed' && !$booking->review)
                                        <a href="{{ route('user.bookings.show', $booking) }}#review" 
                                           class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            Recensera
                                        </a>
                                    @elseif($booking->review)
                                        <span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 font-semibold rounded-lg text-xs">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Recenserad
                                        </span>
                                    @else
                                        <a href="{{ route('user.bookings.show', $booking) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            Visa →
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="text-5xl mb-4">📭</div>
                                    <p class="text-lg mb-2">Inga bokningar ännu</p>
                                    <p class="text-sm text-gray-400">Börja boka en tjänst nu!</p>
                                    <a href="{{ route('welcome') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        Bläddra Tjänster
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($recentBookings->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $recentBookings->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar (1/3) -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">⚡</span>
                Snabbåtgärder
            </h3>
            <div class="space-y-2">
                <a href="{{ route('welcome') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg transition text-center shadow-md">
                    ✨ Boka Ny Tjänst
                </a>
                <a href="{{ route('user.bookings.index') }}" class="block w-full px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium rounded-lg transition text-center">
                    📋 Alla Bokningar
                </a>
                <a href="{{ route('user.profile') }}" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium rounded-lg transition text-center">
                    👤 Min Profil
                </a>
            </div>
        </div>

        <!-- Loyalty Points History -->
        @if(setting('loyalty_points_enabled', true) && isset($recentLoyaltyPoints) && $recentLoyaltyPoints->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">⭐</span>
                Senaste Poängtransaktioner
            </h3>
            <div class="space-y-3">
                @foreach($recentLoyaltyPoints as $transaction)
                    <div class="flex items-start justify-between p-3 rounded-lg {{ $transaction->points > 0 ? 'bg-green-50 border-l-4 border-green-500' : 'bg-purple-50 border-l-4 border-purple-500' }}">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg">
                                    @if($transaction->type === 'earned')
                                        💰
                                    @elseif($transaction->type === 'redeemed')
                                        🎁
                                    @else
                                        ⭐
                                    @endif
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ ucfirst($transaction->type === 'earned' ? 'Intjänade' : ($transaction->type === 'redeemed' ? 'Inlösta' : $transaction->type)) }}
                                    </p>
                                    <p class="text-xs text-gray-600 truncate">{{ $transaction->description }}</p>
                                    @if($transaction->booking)
                                        <a href="{{ route('user.bookings.show', $transaction->booking) }}" class="text-xs text-blue-600 hover:underline">
                                            Bokning #{{ $transaction->booking->booking_number }}
                                        </a>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">{{ $transaction->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right ml-3 flex-shrink-0">
                            <p class="text-sm font-bold {{ $transaction->points > 0 ? 'text-green-600' : 'text-purple-600' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points, 0) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ number_format(abs($transaction->points) * (float)setting('loyalty_points_value', 1), 2) }} kr
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 pt-3 border-t border-gray-200 text-center">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Totalt Saldo:</span>
                    <span class="text-lg font-bold text-purple-600">{{ number_format(auth()->user()->loyalty_points_balance, 0) }} poäng</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    ≈ {{ number_format(auth()->user()->loyalty_points_balance * (float)setting('loyalty_points_value', 1), 2) }} kr värde
                </p>
            </div>
        </div>
        @endif

        <!-- Activity Feed -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">🔔</span>
                Senaste Aktivitet
            </h3>
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                    @php
                        $activityIcons = [
                            'booking_status_changed' => '📋',
                            'new_chat_message' => '💬',
                            'booking_review_reminder' => '⭐',
                            'booking_assigned' => '🏢',
                            'new_booking' => '✅',
                        ];
                        $type = $activity->data['type'] ?? 'notification';
                        $icon = $activityIcons[$type] ?? '🔔';
                    @endphp
                    <form method="POST" action="{{ route('user.notifications.read', $activity->id) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition {{ $activity->read_at ? 'opacity-75' : 'bg-blue-50 border-l-4 border-blue-500' }}">
                        <span class="text-2xl flex-shrink-0">{{ $icon }}</span>
                        <div class="flex-1 min-w-0">
                            @if($type === 'booking_status_changed')
                                <p class="text-sm font-semibold text-gray-900">Bokningsstatus ändrad</p>
                                <p class="text-xs text-gray-600">Bokning #{{ $activity->data['booking_number'] ?? '' }} - {{ $activity->data['new_status'] ?? '' }}</p>
                            @elseif($type === 'new_chat_message')
                                <p class="text-sm font-semibold text-gray-900">Nytt meddelande</p>
                                <p class="text-xs text-gray-600 truncate">{{ $activity->data['message_preview'] ?? '' }}</p>
                            @elseif($type === 'booking_review_reminder')
                                <p class="text-sm font-semibold text-gray-900">Påminnelse: Lämna recension</p>
                                <p class="text-xs text-gray-600">{{ $activity->data['service_name'] ?? '' }}</p>
                            @else
                                <p class="text-sm font-semibold text-gray-900">{{ $activity->data['title'] ?? 'Notifikation' }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $activity->data['message'] ?? '' }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$activity->read_at)
                            <span class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full"></span>
                        @endif
                        </button>
                    </form>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">Ingen aktivitet ännu</p>
                @endforelse
            </div>
            
            @if($recentActivity->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $recentActivity->links() }}
                </div>
            @endif
        </div>

        <!-- Complaints Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <span class="text-xl mr-2">📝</span>
                    Mina Reklamationer
                </h3>
                <div class="flex space-x-2">
                    <a href="{{ route('user.complaints.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Visa alla →
                    </a>
                </div>
            </div>

            @if(isset($recentComplaints) && $recentComplaints->count() > 0)
                <div class="space-y-4">
                    @foreach($recentComplaints as $complaint)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $complaint->subject }}</h4>
                                    <p class="text-xs text-gray-600 mb-2">{{ $complaint->booking->service->name }} - {{ $complaint->booking->city->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($complaint->description, 80) }}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    @php
                                        $statusColors = [
                                            'open' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                            'resolved' => 'bg-green-100 text-green-800',
                                            'closed' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $statusLabels = [
                                            'open' => 'Öppen',
                                            'in_progress' => 'Pågående',
                                            'resolved' => 'Löst',
                                            'closed' => 'Stängd',
                                        ];
                                        $priorityColors = [
                                            'low' => 'bg-gray-100 text-gray-600',
                                            'medium' => 'bg-yellow-100 text-yellow-600',
                                            'high' => 'bg-orange-100 text-orange-600',
                                            'urgent' => 'bg-red-100 text-red-600',
                                        ];
                                        $priorityLabels = [
                                            'low' => 'Låg',
                                            'medium' => 'Medium',
                                            'high' => 'Hög',
                                            'urgent' => 'Akut',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$complaint->status] }}">
                                        {{ $statusLabels[$complaint->status] }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $priorityColors[$complaint->priority] }}">
                                        {{ $priorityLabels[$complaint->priority] }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>📅 {{ $complaint->created_at->format('Y-m-d') }}</span>
                                    <span>💬 {{ $complaint->messages->count() }} meddelanden</span>
                                    @if($complaint->messages->where('sender_type', 'company')->count() > 0)
                                        <span class="text-green-600">✅ Svar från företag</span>
                                    @endif
                                </div>
                                <a href="{{ route('user.complaints.show', $complaint) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                    <span class="mr-1">👁️</span>
                                    Visa detaljer
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ $recentComplaints->count() }}</span> av 
                            <span class="font-medium">{{ auth()->user()->complaints->count() }}</span> reklamationer
                        </div>
                        <a href="{{ route('user.complaints.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-sm font-semibold rounded-lg transition-all">
                            <span class="mr-2">📋</span>
                            Hantera alla reklamationer
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">📝</div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Inga reklamationer</h4>
                    <p class="text-gray-500 text-sm mb-4">Du har inte skapat några reklamationer än.</p>
                    <a href="{{ route('user.bookings.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <span class="mr-2">📅</span>
                        Se mina bokningar
                    </a>
                </div>
            @endif
        </div>

        <!-- Help & Support -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                <span class="text-xl mr-2">💬</span>
                Behöver Du Hjälp?
            </h3>
            <p class="text-sm text-gray-600 mb-4">Vi är här för att hjälpa dig!</p>
            <div class="space-y-2">
                <a href="{{ route('contact') }}" class="block w-full px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition text-center text-sm border border-gray-200">
                    📧 Kontakta Oss
                </a>
                <a href="{{ route('how-it-works') }}" class="block w-full px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition text-center text-sm border border-gray-200">
                    ❓ Så Fungerar Det
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
