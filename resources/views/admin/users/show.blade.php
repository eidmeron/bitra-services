@extends('layouts.admin')

@section('title', 'Användare: ' . $user->email)

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium mb-2 inline-block">
                ← Tillbaka till användare
            </a>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <span class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-3">
                    {{ strtoupper(substr($user->email, 0, 1)) }}
                </span>
                {{ $user->email }}
            </h1>
            <p class="text-gray-600 mt-1">Registrerad {{ $user->created_at->diffForHumans() }} ({{ $user->created_at->format('Y-m-d H:i') }})</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium shadow-lg">
                ✏️ Redigera
            </a>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Är du säker på att du vill ta bort denna användare?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium shadow-lg">
                    🗑️ Ta Bort
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- User Info -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="text-2xl mr-3">👤</span>
                Användarinformation
            </h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Namn</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $user->name ?? 'Ej angivet' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Telefon</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $user->phone ?? 'Ej angivet' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Användartyp</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ ucfirst($user->type) }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Registreringsdatum</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                    <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Senast uppdaterad</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                    <p class="text-sm text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <!-- Bookings Stats -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg p-6 border-2 border-blue-200">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="text-2xl mr-3">📊</span>
                Bokningsstatistik
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white rounded-lg p-4 text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $userStats['total_bookings'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Totala Bokningar</p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $userStats['completed_bookings'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Slutförda</p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $userStats['pending_bookings'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Väntande</p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($userStats['total_spent'], 0, ',', ' ') }} kr</p>
                    <p class="text-sm text-gray-600 mt-1">Totalt Spenderat</p>
                </div>
                @if(setting('loyalty_points_enabled', true))
                <div class="bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg p-4 text-center border-2 border-purple-300">
                    <p class="text-3xl font-bold text-purple-700">{{ number_format($user->loyalty_points_balance, 0) }}</p>
                    <p class="text-sm text-gray-700 mt-1 font-medium">Lojalitetspoäng</p>
                    <p class="text-xs text-gray-600 mt-1">≈ {{ number_format($user->loyalty_points_balance * (float)setting('loyalty_points_value', 1), 2) }} kr</p>
                </div>
                @endif
            </div>
            @if($userStats['rot_savings'] > 0)
                <div class="bg-white rounded-lg p-4 mt-4 text-center border-2 border-green-200">
                    <p class="text-2xl font-bold text-green-600">{{ number_format($userStats['rot_savings'], 0, ',', ' ') }} kr</p>
                    <p class="text-sm text-gray-600 mt-1">💚 ROT-avdrag Sparat</p>
                </div>
            @endif
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">📋</span>
                    Bokningar
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bokning</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tjänst</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Företag</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pris</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Åtgärd</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($user->bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $booking->booking_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->created_at->format('Y-m-d') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl">{{ $booking->service->icon ?? '🛠️' }}</span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $booking->service->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->city->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($booking->company)
                                        <div class="text-sm text-gray-900">{{ $booking->company->company_name ?? 'N/A' }}</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Ej tilldelat</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($booking->booking_date)
                                        <div class="text-sm text-gray-900">{{ $booking->booking_date->format('Y-m-d') }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->booking_date->format('H:i') }}</div>
                                    @elseif($booking->preferred_date)
                                        <div class="text-sm text-gray-900">{{ $booking->preferred_date->format('Y-m-d') }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->preferred_date->format('H:i') }}</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Inget datum satt</span>
                                    @endif
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                        Visa →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <div class="text-4xl mb-2">📭</div>
                                    <p>Inga bokningar ännu</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Loyalty Points -->
        @if(setting('loyalty_points_enabled', true))
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <span class="text-xl mr-2">⭐</span>
                Lojalitetspoäng
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-purple-100 uppercase tracking-wide">Aktuellt Saldo</label>
                    <p class="text-4xl font-bold">{{ number_format($user->loyalty_points_balance, 0) }}</p>
                    <p class="text-sm text-purple-100 mt-1">≈ {{ number_format($user->loyalty_points_balance * (float)setting('loyalty_points_value', 1), 2) }} kr</p>
                </div>
                
                @php
                    $totalEarned = $user->loyaltyPoints()->sum('points');
                    $totalSpent = abs($user->loyaltyPoints()->where('points', '<', 0)->sum('points'));
                @endphp
                
                <div class="pt-4 border-t border-purple-400 border-opacity-40">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-purple-100">Totalt Intjänat</label>
                            <p class="text-lg font-bold">+{{ number_format($totalEarned, 0) }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-purple-100">Totalt Använt</label>
                            <p class="text-lg font-bold">-{{ number_format($totalSpent, 0) }}</p>
                        </div>
                    </div>
                </div>
                
                @if($user->loyaltyPoints->count() > 0)
                <div class="pt-4 border-t border-purple-400 border-opacity-40">
                    <label class="text-xs text-purple-100 uppercase tracking-wide">Senaste Transaktioner</label>
                    <div class="mt-2 space-y-2">
                        @foreach($user->loyaltyPoints->take(3) as $transaction)
                            <div class="bg-white bg-opacity-20 rounded-lg p-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs">{{ $transaction->description }}</span>
                                    <span class="text-sm font-bold {{ $transaction->points > 0 ? 'text-green-200' : 'text-red-200' }}">
                                        {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points, 0) }}
                                    </span>
                                </div>
                                <p class="text-xs text-purple-100 mt-1">{{ $transaction->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">⚡</span>
                Snabbåtgärder
            </h3>
            <div class="space-y-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="block w-full px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium rounded-lg transition text-center">
                    ✏️ Redigera Användare
                </a>
                <a href="{{ route('admin.bookings.index', ['user_id' => $user->id]) }}" class="block w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg transition text-center">
                    📋 Alla Bokningar
                </a>
                <button onclick="if(confirm('Skicka återställningslänk till {{ $user->email }}?')) alert('Funktion kommer snart')" class="block w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium rounded-lg transition text-center">
                    🔑 Återställ Lösenord
                </button>
            </div>
        </div>

        <!-- Account Status -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">✅</span>
                Kontostatus
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Status</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        Aktiv
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Typ</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        {{ ucfirst($user->type) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Medlemskap</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $user->created_at->diffInDays(now()) }} dagar
                    </span>
                </div>
            </div>
        </div>

        <!-- Registration Info -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-xl mr-2">📅</span>
                Registreringsinformation
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-xs text-gray-600">Registrerad</label>
                    <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-600">Senast uppdaterad</label>
                    <p class="text-sm font-medium text-gray-900">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

