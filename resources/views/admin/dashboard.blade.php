@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Översikt och statistik')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">👋 Välkommen tillbaka!</h1>
            <p class="text-blue-100 text-lg">Här är en översikt över din plattform</p>
        </div>
        <div class="hidden md:block text-6xl opacity-50">
            📊
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

    <!-- Pending Bookings -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Väntande</p>
                <p class="text-4xl font-bold text-yellow-600 mt-2">{{ number_format($stats['pending_bookings']) }}</p>
                @if($stats['pending_bookings'] > 0)
                    <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="text-sm text-yellow-700 hover:underline mt-1 inline-block">
                        Hantera nu →
                    </a>
                @else
                    <p class="text-sm text-gray-500 mt-1">Inga väntande</p>
                @endif
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center text-3xl">
                ⏳
            </div>
        </div>
    </div>

    <!-- Active Companies -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Aktiva Företag</p>
                <p class="text-4xl font-bold text-green-600 mt-2">{{ number_format($stats['active_companies']) }}</p>
                <p class="text-sm text-gray-500 mt-1">av {{ $stats['total_companies'] }} totalt</p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-3xl">
                🏢
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600 hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Omsättning</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} kr</p>
                <p class="text-sm text-gray-500 mt-1">Denna månad: {{ number_format($stats['monthly_revenue'], 0, ',', ' ') }} kr</p>
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-3xl">
                💰
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Users -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm font-medium">Registrerade Användare</p>
                <p class="text-4xl font-bold mt-2">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="text-5xl opacity-75">👥</div>
        </div>
    </div>

    <!-- Services -->
    <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-pink-100 text-sm font-medium">Aktiva Tjänster</p>
                <p class="text-4xl font-bold mt-2">{{ number_format($stats['total_services']) }}</p>
            </div>
            <div class="text-5xl opacity-75">🛠️</div>
        </div>
    </div>

    <!-- Completion Rate -->
    <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-teal-100 text-sm font-medium">Slutförda Bokningar</p>
                <p class="text-4xl font-bold mt-2">{{ $bookingsByStatus['completed'] }}</p>
            </div>
            <div class="text-5xl opacity-75">✅</div>
        </div>
    </div>
</div>

<!-- Loyalty Points Stats -->
@if(setting('loyalty_points_enabled', true))
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Points in System -->
    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium uppercase">Totala Poäng</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($loyaltyStats['total_points_balance'], 0) }}</p>
                <p class="text-xs text-purple-100 mt-1">≈ {{ number_format($loyaltyStats['total_points_balance'] * (float)setting('loyalty_points_value', 1), 0, ',', ' ') }} kr värde</p>
            </div>
            <div class="text-4xl opacity-75">⭐</div>
        </div>
    </div>

    <!-- Points Earned This Month -->
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium uppercase">Intjänade (Månad)</p>
                <p class="text-3xl font-bold mt-2">+{{ number_format($loyaltyStats['points_earned_month'], 0) }}</p>
                <p class="text-xs text-green-100 mt-1">denna månaden</p>
            </div>
            <div class="text-4xl opacity-75">💰</div>
        </div>
    </div>

    <!-- Points Redeemed This Month -->
    <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium uppercase">Inlösta (Månad)</p>
                <p class="text-3xl font-bold mt-2">-{{ number_format($loyaltyStats['points_redeemed_month'], 0) }}</p>
                <p class="text-xs text-orange-100 mt-1">denna månaden</p>
            </div>
            <div class="text-4xl opacity-75">🎁</div>
        </div>
    </div>

    <!-- Users with Points -->
    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium uppercase">Användare med Poäng</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($loyaltyStats['users_with_points']) }}</p>
                <p class="text-xs text-blue-100 mt-1">aktiva användare</p>
            </div>
            <div class="text-4xl opacity-75">👥</div>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Main Content (2/3) -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Booking Status Breakdown -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3 text-xl">📊</span>
                Bokningsstatus Översikt
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-4 bg-yellow-50 rounded-lg border-2 border-yellow-200">
                    <div class="text-3xl mb-2">⏳</div>
                    <div class="text-2xl font-bold text-yellow-700">{{ $bookingsByStatus['pending'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Väntande</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                    <div class="text-3xl mb-2">✓</div>
                    <div class="text-2xl font-bold text-blue-700">{{ $bookingsByStatus['confirmed'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Bekräftad</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg border-2 border-purple-200">
                    <div class="text-3xl mb-2">🔄</div>
                    <div class="text-2xl font-bold text-purple-700">{{ $bookingsByStatus['in_progress'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Pågående</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg border-2 border-green-200">
                    <div class="text-3xl mb-2">✅</div>
                    <div class="text-2xl font-bold text-green-700">{{ $bookingsByStatus['completed'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Slutförd</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg border-2 border-red-200">
                    <div class="text-3xl mb-2">❌</div>
                    <div class="text-2xl font-bold text-red-700">{{ $bookingsByStatus['cancelled'] }}</div>
                    <div class="text-xs text-gray-600 mt-1">Avbruten</div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3 text-xl">📈</span>
                    Omsättning
                </h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.dashboard', ['revenue_filter' => 'weekly']) }}" 
                       class="px-3 py-1 text-sm rounded-lg {{ $filter === 'weekly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Vecka
                    </a>
                    <a href="{{ route('admin.dashboard', ['revenue_filter' => 'monthly']) }}" 
                       class="px-3 py-1 text-sm rounded-lg {{ $filter === 'monthly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Månad
                    </a>
                    <a href="{{ route('admin.dashboard', ['revenue_filter' => 'yearly']) }}" 
                       class="px-3 py-1 text-sm rounded-lg {{ $filter === 'yearly' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        År
                    </a>
                </div>
            </div>
            <div class="h-64 flex items-end justify-between space-x-2">
                @if(count($revenueData) > 0)
                    @foreach($revenueData as $data)
                        @php
                            $maxRevenue = collect($revenueData)->max('revenue');
                            $height = $maxRevenue > 0 ? ($data['revenue'] / $maxRevenue) * 100 : 0;
                        @endphp
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-gradient-to-t from-blue-600 to-purple-600 rounded-t-lg hover:from-blue-700 hover:to-purple-700 transition-all relative group" 
                                 style="height: {{ $height }}%">
                                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    {{ number_format($data['revenue'], 0, ',', ' ') }} kr
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 mt-2 font-medium">{{ $data['period'] }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <div class="text-4xl mb-2">📊</div>
                            <p class="text-sm">Ingen omsättningsdata tillgänglig</p>
                        </div>
                    </div>
                @endif
            </div>
            @if(collect($revenueData)->sum('revenue') > 0)
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Total omsättning: <span class="font-bold text-green-600">{{ number_format(collect($revenueData)->sum('revenue'), 0, ',', ' ') }} kr</span>
                    </p>
                </div>
            @else
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-500">Ingen omsättning under denna period</p>
                </div>
            @endif
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <span class="text-2xl mr-3">📋</span>
                        Senaste Bokningar
                    </h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pris</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärd</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $booking->booking_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->created_at->format('Y-m-d H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->customer_email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $booking->service->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->city->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ number_format($booking->final_price, 0, ',', ' ') }} kr</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                        Visa →
                                    </a>
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
                <a href="{{ route('admin.bookings.index') }}" class="block w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg transition text-center">
                    📅 Hantera Bokningar
                </a>
                <a href="{{ route('admin.companies.index') }}" class="block w-full px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg transition text-center">
                    🏢 Hantera Företag
                </a>
                <a href="{{ route('admin.services.index') }}" class="block w-full px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium rounded-lg transition text-center">
                    🛠️ Hantera Tjänster
                </a>
                <a href="{{ route('admin.settings.index') }}" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium rounded-lg transition text-center">
                    ⚙️ Inställningar
                </a>
            </div>
        </div>

        <!-- Recent Complaints -->
        @if(isset($recentComplaints) && $recentComplaints->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <span class="text-xl mr-2">📝</span>
                    Reklamationer
                </h3>
                <a href="{{ route('admin.complaints.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Visa alla →
                </a>
            </div>
            <div class="space-y-3">
                @foreach($recentComplaints as $complaint)
                    <a href="{{ route('admin.complaints.show', $complaint) }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
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

        <!-- Top Services -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">🏆</span>
                Populära Tjänster
            </h3>
            <div class="space-y-3">
                @forelse($topServices as $service)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $service->icon ?? '🛠️' }}</span>
                            <div>
                                <div class="font-medium text-gray-900 text-sm">{{ $service->name }}</div>
                                <div class="text-xs text-gray-500">{{ $service->bookings_count }} bokningar</div>
                            </div>
                        </div>
                        <div class="text-xl font-bold text-blue-600">{{ $service->bookings_count }}</div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">Ingen data ännu</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Companies Alert -->
        @if($pendingCompanies->count() > 0)
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center">
                    <span class="text-xl mr-2">⚠️</span>
                    Väntande Företag
                </h3>
                <p class="text-sm text-gray-600 mb-4">{{ $pendingCompanies->count() }} företag väntar på godkännande</p>
                <div class="space-y-2">
                    @foreach($pendingCompanies->take(3) as $company)
                        <div class="bg-white p-3 rounded-lg flex items-center justify-between">
                            <div>
                                <div class="font-medium text-sm">{{ $company->company_name ?? $company->user->email }}</div>
                                <div class="text-xs text-gray-500">{{ $company->company_org_number }}</div>
                            </div>
                            <a href="{{ route('admin.companies.show', $company) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Granska →
                            </a>
                        </div>
                    @endforeach
                </div>
                @if($pendingCompanies->count() > 3)
                    <a href="{{ route('admin.companies.index', ['status' => 'pending']) }}" class="block text-center mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Visa alla {{ $pendingCompanies->count() }} väntande →
                    </a>
                @endif
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
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <span class="text-xl flex-shrink-0">{{ $activity->data['icon'] ?? '🔔' }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $activity->data['title'] ?? 'Notifikation' }}</p>
                            <p class="text-xs text-gray-600 truncate">{{ $activity->data['message'] ?? '' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">Ingen aktivitet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">👥</span>
                Nya Användare
            </h3>
            <div class="space-y-2">
                @forelse($recentUsers as $user)
                    <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $user->name ?? $user->email }}</div>
                            <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">Inga nya användare</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chat History Section (Disabled) -->
<div class="mt-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">💬</span>
                Chattfunktion (Inaktiverad)
            </h3>
        </div>
        <div class="p-6">
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-lg font-medium text-gray-700 mb-2">Chattfunktionen har inaktiverats</p>
                <p class="text-sm text-gray-500">Chattfunktionaliteten har tagits bort från systemet. Använd bokningshantering och meddelanden istället.</p>
            </div>
        </div>
    </div>
</div>
@endsection
