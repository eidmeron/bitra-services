@extends('layouts.user')

@section('title', 'Lojalitetspoäng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">⭐</span>
                    Mina Lojalitetspoäng
                </h1>
                <p class="text-gray-600 mt-2">Hantera dina lojalitetspoäng och se din transaktionshistorik</p>
            </div>
        </div>
    </div>

    <!-- Current Balance Card -->
    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Dina Lojalitetspoäng</h2>
                <p class="text-yellow-100 mb-4">1 poäng = 1 SEK värde</p>
                <div class="text-4xl font-bold">{{ number_format($summary['current_balance'], 0, ',', ' ') }}</div>
                <p class="text-yellow-100 mt-2">≈ {{ number_format($summary['current_balance'], 0, ',', ' ') }} kr värde</p>
            </div>
            <div class="text-6xl opacity-75">⭐</div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Earned -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Totala Poäng</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($summary['total_earned'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">≈ {{ number_format($summary['total_earned'], 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">🎁</div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Använda Poäng</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($summary['total_spent'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">≈ {{ number_format($summary['total_spent'], 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">💳</div>
            </div>
        </div>

        <!-- Total Refunded -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Återbetalda</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($summary['total_refunded'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">≈ {{ number_format($summary['total_refunded'], 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">🔄</div>
            </div>
        </div>

        <!-- Expired Points -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Expirerade</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($summary['total_expired'], 0, ',', ' ') }}</p>
                    <p class="text-xs text-gray-500 mt-1">≈ {{ number_format($summary['total_expired'], 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">⏰</div>
            </div>
        </div>
    </div>

    <!-- Expiring Soon Alert -->
    @if($summary['expiring_soon'] > 0)
    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 mb-8">
        <div class="flex items-center">
            <div class="text-orange-500 text-2xl mr-3">⚠️</div>
            <div>
                <h3 class="text-lg font-semibold text-orange-800">Poäng som snart upphör</h3>
                <p class="text-orange-700 mt-1">
                    Du har {{ number_format($summary['expiring_soon'], 0, ',', ' ') }} poäng som upphör inom 30 dagar.
                    Använd dem snart för att inte förlora dem!
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">📋</span>
                Transaktionshistorik
            </h3>
        </div>

        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poäng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beskrivning</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokning</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Upphör</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeConfig = [
                                    'earned' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Tjänade', 'icon' => '🎁'],
                                    'spent' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Använda', 'icon' => '💳'],
                                    'refunded' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Återbetalda', 'icon' => '🔄'],
                                    'expired' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Expirerade', 'icon' => '⏰'],
                                    'adjusted' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Justerade', 'icon' => '⚙️'],
                                ];
                                $config = $typeConfig[$transaction->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($transaction->type), 'icon' => '❓'];
                            @endphp
                            <div class="flex items-center">
                                <span class="text-lg mr-2">{{ $config['icon'] }}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium {{ $transaction->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points, 0, ',', ' ') }}
                            </span>
                            <p class="text-xs text-gray-500">{{ number_format($transaction->value_sek, 0, ',', ' ') }} kr</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $transaction->description }}</p>
                            @if($transaction->notes)
                                <p class="text-xs text-gray-500 mt-1">{{ $transaction->notes }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->booking)
                                <a href="{{ route('user.bookings.show', $transaction->booking) }}" class="text-blue-600 hover:underline text-sm">
                                    #{{ $transaction->booking->booking_number }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $transaction->booking->service->name ?? 'N/A' }}</p>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($transaction->expires_at)
                                @php
                                    $expiresAt = \Carbon\Carbon::parse($transaction->expires_at);
                                    $daysLeft = $expiresAt->diffInDays(now());
                                @endphp
                                <span class="{{ $daysLeft <= 30 ? 'text-orange-600' : 'text-gray-500' }}">
                                    {{ $expiresAt->format('d/m/Y') }}
                                </span>
                                @if($daysLeft <= 30)
                                    <p class="text-xs text-orange-600">{{ $daysLeft }} dagar kvar</p>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $transactions->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <div class="text-5xl mb-4">⭐</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga transaktioner ännu</h3>
            <p class="text-gray-500">Dina lojalitetspoäng transaktioner kommer att visas här när du börjar boka tjänster.</p>
        </div>
        @endif
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Hur fungerar lojalitetspoäng?</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• Du tjänar 1 poäng per 10 SEK du spenderar på bokningar</p>
                    <p>• 1 poäng = 1 SEK värde som du kan använda på framtida bokningar</p>
                    <p>• Poäng upphör efter 1 år från när de tjänades</p>
                    <p>• Du kan använda poäng för att minska kostnaden för nya bokningar</p>
                    <p>• Poäng kan återbetalas om du avbokar en tjänst</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
