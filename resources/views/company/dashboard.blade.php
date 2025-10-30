@extends('layouts.company')

@section('title', 'Företagets Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">🏢 Välkommen, {{ $company->company_name }}!</h1>
            <p class="text-green-100 text-lg">Hantera dina bokningar och följ din verksamhet</p>
        </div>
        <div class="hidden md:block">
            @if($company->logo)
                <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->company_name }}" class="w-20 h-20 rounded-full border-4 border-white/30">
            @else
                <div class="text-6xl opacity-50">📊</div>
            @endif
        </div>
    </div>
</div>

<!-- Main Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Bookings -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Totala Bokningar</p>
                <p class="text-4xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_bookings']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Alla tider</p>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl">
                📅
            </div>
        </div>
    </div>

    <!-- Assigned Bookings -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Tilldelade</p>
                <p class="text-4xl font-bold text-yellow-600 mt-2">{{ number_format($stats['assigned_bookings']) }}</p>
                @if($stats['assigned_bookings'] > 0)
                    <p class="text-sm text-yellow-700 mt-1">Kräver åtgärd</p>
                @else
                    <p class="text-sm text-gray-500 mt-1">Inga väntande</p>
                @endif
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center text-3xl">
                ⏳
            </div>
        </div>
    </div>

    <!-- In Progress -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Pågående</p>
                <p class="text-4xl font-bold text-purple-600 mt-2">{{ number_format($stats['in_progress']) }}</p>
                <p class="text-sm text-gray-500 mt-1">Aktiva uppdrag</p>
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-3xl">
                🔄
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Intäkt</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} kr</p>
                <p class="text-sm text-gray-500 mt-1">Denna månad: {{ number_format($stats['monthly_revenue'], 0, ',', ' ') }} kr</p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-3xl">
                💰
            </div>
        </div>
    </div>
</div>

<!-- Payout Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Pending Payouts -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Väntande Deposits</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($depositStats['pending'], 0, ',', ' ') }} kr</p>
                <p class="text-xs text-orange-100 mt-1">{{ $depositStats['pending_count'] }} st</p>
            </div>
            <div class="text-4xl opacity-75">⏳</div>
        </div>
    </div>

    <!-- Approved Payouts -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Skickade</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($depositStats['sent'], 0, ',', ' ') }} kr</p>
                <p class="text-xs text-blue-100 mt-1">{{ $depositStats['sent_count'] }} st</p>
            </div>
            <div class="text-4xl opacity-75">✅</div>
        </div>
    </div>

    <!-- Paid Payouts -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Betalda</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($depositStats['paid'], 0, ',', ' ') }} kr</p>
                <p class="text-xs text-green-100 mt-1">{{ $depositStats['paid_count'] }} st</p>
            </div>
            <div class="text-4xl opacity-75">💰</div>
        </div>
    </div>

    <!-- Total Commission -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Total Kommission</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($depositStats['total_commission'], 0, ',', ' ') }} kr</p>
                <p class="text-xs text-purple-100 mt-1">Alla tider</p>
            </div>
            <div class="text-4xl opacity-75">💸</div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Completed Today -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm font-medium">Slutförda Idag</p>
                <p class="text-4xl font-bold mt-2">{{ number_format($stats['completed_today']) }}</p>
            </div>
            <div class="text-5xl opacity-75">✅</div>
        </div>
    </div>

    <!-- Average Rating -->
    <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-pink-100 text-sm font-medium">Snittbetyg</p>
                <div class="flex items-baseline mt-2">
                    <p class="text-4xl font-bold">{{ number_format($stats['average_rating'], 1) }}</p>
                    <p class="text-2xl ml-2 opacity-75">⭐</p>
                </div>
                <p class="text-pink-100 text-xs mt-1">{{ $stats['total_reviews'] }} recensioner</p>
            </div>
            <div class="text-5xl opacity-75">🏆</div>
        </div>
    </div>

    <!-- Completed Total -->
    <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-teal-100 text-sm font-medium">Totalt Slutförda</p>
                <p class="text-4xl font-bold mt-2">{{ number_format($stats['completed_bookings']) }}</p>
            </div>
            <div class="text-5xl opacity-75">📈</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Main Content (2/3) -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3 text-xl">📈</span>
                Intäktsutveckling (Senaste 6 Månader)
            </h3>
            <div class="h-64 flex items-end justify-between space-x-2">
                @if(count($monthlyRevenueData) > 0)
                    @foreach($monthlyRevenueData as $data)
                        @php
                            $maxRevenue = collect($monthlyRevenueData)->max('revenue');
                            $height = $maxRevenue > 0 ? max(($data['revenue'] / $maxRevenue) * 200, 20) : 20; // Minimum height of 20px
                        @endphp
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-gradient-to-t from-green-600 to-teal-600 rounded-t-lg hover:from-green-700 hover:to-teal-700 transition-all relative group" 
                                 style="height: {{ $height }}px">
                                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    {{ number_format($data['revenue'], 0, ',', ' ') }} kr
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 mt-2 font-medium">{{ $data['month'] }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <div class="text-4xl mb-2">📊</div>
                            <p class="text-sm">Ingen intäktsdata tillgänglig</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Deposits -->
        @if(isset($recentDeposits) && $recentDeposits->count() > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">💰</span>
                    Senaste Deposits
                </h3>
                <a href="{{ route('company.deposits.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold text-sm flex items-center">
                    Visa alla
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bokning</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Belopp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provision</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utbetalning</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentDeposits as $deposit)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($deposit->booking)
                                    <a href="{{ route('company.bookings.show', $deposit->booking) }}" class="text-blue-600 hover:underline font-medium text-sm">
                                        #{{ $deposit->booking->booking_number }}
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 font-medium">{{ number_format($deposit->booking_amount, 0, ',', ' ') }} kr</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-red-600 font-medium">-{{ number_format($deposit->commission_amount, 0, ',', ' ') }} kr</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-green-600 font-bold">{{ number_format($deposit->deposit_amount, 0, ',', ' ') }} kr</span>
                                @if($deposit->loyalty_points_value > 0)
                                    <p class="text-xs text-blue-600">Lojalitetspoäng: -{{ number_format($deposit->loyalty_points_value, 0, ',', ' ') }} kr</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Väntande'],
                                        'sent' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Skickad'],
                                        'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Utbetald'],
                                        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Avbruten'],
                                    ];
                                    $config = $statusConfig[$payout->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($payout->status)];
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payout->created_at->format('Y-m-d') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($recentPayouts->count() >= 5)
                <div class="px-6 py-4 bg-gray-50 border-t text-center">
                    <a href="{{ route('company.payouts.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Visa alla utbetalningar →
                    </a>
                </div>
            @endif
        </div>
        @endif

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
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-4 hover:shadow-md transition-shadow border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="text-2xl">{{ $booking->service->icon ?? '🛠️' }}</span>
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $booking->service->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $booking->city->name }} • Bokning: {{ $booking->booking_number }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        @if($booking->booking_date)
                                            {{ $booking->booking_date->format('Y-m-d H:i') }}
                                        @elseif($booking->preferred_date)
                                            {{ \Carbon\Carbon::parse($booking->preferred_date)->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-gray-400">Ej angivet</span>
                                        @endif
                                    </span>
                                    <span class="flex items-center">
                                        👤 {{ $booking->customer_name }}
                                    </span>
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
                                    
                                    @if(in_array($booking->status, ['assigned', 'in_progress']))
                                        <a href="{{ route('company.bookings.show', $booking) }}#chat" 
                                           class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Chatta
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('company.bookings.show', $booking) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        Hantera →
                                    </a>
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
                    <a href="{{ route('company.bookings.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                        Visa alla →
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokning</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kund</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tjänst</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pris</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $booking->booking_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->created_at->format('Y-m-d') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->customer_email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl">{{ $booking->service->icon ?? '🛠️' }}</span>
                                        <div>
                                            <div class="text-sm text-gray-900">{{ $booking->service->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->city->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($booking->preferred_date)
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->preferred_date)->format('Y-m-d') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->preferred_date)->format('H:i') }}</div>
                                    @else
                                        <div class="text-sm text-gray-500">Ej angivet</div>
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
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ bookingStatusLabel($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="text-5xl mb-4">📭</div>
                                    <p>Inga bokningar ännu</p>
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
                <a href="{{ route('company.bookings.index') }}" class="block w-full px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg transition text-center">
                    📅 Hantera Bokningar
                </a>
                <a href="{{ route('company.messages.index') }}" class="block w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg transition text-center">
                    💬 Meddelanden
                </a>
                <a href="{{ route('company.profile') }}" class="block w-full px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium rounded-lg transition text-center">
                    🏢 Min Profil
                </a>
                <a href="{{ route('company.settings') }}" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium rounded-lg transition text-center">
                    ⚙️ Inställningar
                </a>
            </div>
        </div>

        <!-- Unread Messages Alert -->
        @if($recentMessages->count() > 0)
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                    <span class="text-xl mr-2">💬</span>
                    Nya Meddelanden
                </h3>
                <p class="text-sm text-gray-600 mb-4">{{ $recentMessages->count() }} olästa meddelanden</p>
                <div class="space-y-2">
                    @foreach($recentMessages->take(3) as $message)
                        <div class="bg-white p-3 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-sm text-gray-900 truncate">{{ $message->subject }}</div>
                                    <div class="text-xs text-gray-500">{{ $message->guest_name }} ({{ $message->guest_email }})</div>
                                </div>
                                <a href="{{ route('company.messages.show', $message) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium ml-2">
                                    →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($recentMessages->count() > 3)
                    <a href="{{ route('company.messages.index') }}" class="block text-center mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Visa alla {{ $recentMessages->count() }} meddelanden →
                    </a>
                @endif
            </div>
        @endif

        <!-- Recent Complaints -->
        @if(isset($recentComplaints) && $recentComplaints->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <span class="text-xl mr-2">📝</span>
                    Reklamationer
                </h3>
                <a href="{{ route('company.complaints.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Visa alla →
                </a>
            </div>
            <div class="space-y-3">
                @foreach($recentComplaints as $complaint)
                    <a href="{{ route('company.complaints.show', $complaint) }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-medium text-sm">{{ $complaint->subject }}</p>
                                <p class="text-xs text-gray-500">{{ $complaint->booking->service->name }}</p>
                            </div>
                            <div class="flex flex-col items-end space-y-1">
                                {!! $complaint->status_badge !!}
                                <span class="text-xs text-gray-400">{{ $complaint->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">🔔</span>
                Senaste Aktivitet
            </h3>
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                    @php
                        $activityIcons = [
                            'new_booking' => '📋',
                            'booking_assigned' => '✅',
                            'booking_status_changed' => '🔄',
                            'new_chat_message' => '💬',
                            'new_review' => '⭐',
                        ];
                        $type = $activity->data['type'] ?? 'notification';
                        $icon = $activityIcons[$type] ?? '🔔';
                    @endphp
                    <form method="POST" action="{{ route('company.notifications.read', $activity->id) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-teal-50 transition {{ $activity->read_at ? 'opacity-75' : 'bg-teal-50 border-l-4 border-teal-500' }}">
                            <span class="text-2xl flex-shrink-0">{{ $icon }}</span>
                            <div class="flex-1 min-w-0">
                                @if($type === 'new_booking')
                                    <p class="text-sm font-semibold text-gray-900">Ny Bokning</p>
                                    <p class="text-xs text-gray-600">Bokning #{{ $activity->data['booking_number'] ?? '' }}</p>
                                @elseif($type === 'booking_assigned')
                                    <p class="text-sm font-semibold text-gray-900">Bokning Tilldelad</p>
                                    <p class="text-xs text-gray-600">Du har tilldelats bokning #{{ $activity->data['booking_number'] ?? '' }}</p>
                                @elseif($type === 'booking_status_changed')
                                    <p class="text-sm font-semibold text-gray-900">Status Ändrad</p>
                                    <p class="text-xs text-gray-600">Bokning #{{ $activity->data['booking_number'] ?? '' }} - {{ $activity->data['new_status'] ?? '' }}</p>
                                @elseif($type === 'new_chat_message')
                                    <p class="text-sm font-semibold text-gray-900">Nytt Meddelande</p>
                                    <p class="text-xs text-gray-600 truncate">{{ $activity->data['message_preview'] ?? '' }}</p>
                                @elseif($type === 'new_review')
                                    <p class="text-sm font-semibold text-gray-900">Ny Recension</p>
                                    <p class="text-xs text-gray-600">{{ $activity->data['rating'] ?? '5' }} stjärnor</p>
                                @else
                                    <p class="text-sm font-semibold text-gray-900">{{ $activity->data['title'] ?? 'Notifikation' }}</p>
                                    <p class="text-xs text-gray-600 truncate">{{ $activity->data['message'] ?? '' }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$activity->read_at)
                                <span class="flex-shrink-0 w-2 h-2 bg-teal-600 rounded-full"></span>
                            @endif
                        </button>
                    </form>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">Ingen aktivitet</p>
                @endforelse
            </div>
            
            @if($recentActivity->count() >= 5)
                <div class="px-6 py-4 bg-gray-50 border-t text-center">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Visa alla aktiviteter →
                    </a>
                </div>
            @endif
        </div>

        <!-- Company Performance -->
        <div class="bg-gradient-to-br from-green-50 to-teal-50 border-2 border-green-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">📊</span>
                Prestanda
            </h3>
            <div class="space-y-4">
                <!-- Rating -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Betyg</span>
                        <span class="text-lg font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }} ⭐</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full" style="width: {{ ($stats['average_rating'] / 5) * 100 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['total_reviews'] }} recensioner</p>
                </div>
                
                <!-- Completion Rate -->
                @if($stats['total_bookings'] > 0)
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Slutförandegrad</span>
                        <span class="text-lg font-bold text-gray-900">{{ round(($stats['completed_bookings'] / $stats['total_bookings']) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full" style="width: {{ round(($stats['completed_bookings'] / $stats['total_bookings']) * 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['completed_bookings'] }} av {{ $stats['total_bookings'] }} bokningar</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
