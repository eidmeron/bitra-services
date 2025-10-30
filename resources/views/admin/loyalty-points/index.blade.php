@extends('layouts.admin')

@section('title', 'Lojalitetspoäng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">⭐</span>
                    Lojalitetspoäng
                </h1>
                <p class="text-gray-600 mt-2">Hantera användares lojalitetspoäng och transaktioner</p>
            </div>
            <div class="flex space-x-3">
                <form method="POST" action="{{ route('admin.loyalty-points.expire-old') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            onclick="return confirm('Är du säker på att du vill förfalla gamla lojalitetspoäng?')">
                        ⏰ Förfalla Gamla Poäng
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Points -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Totala Poäng</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($statistics['total_points'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Alla användare</p>
                </div>
                <div class="text-4xl opacity-75">⭐</div>
            </div>
        </div>

        <!-- Active Points -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Aktiva Poäng</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($statistics['active_points'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tillgängliga</p>
                </div>
                <div class="text-4xl opacity-75">💎</div>
            </div>
        </div>

        <!-- Expired Points -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-sm font-medium">Förfallna Poäng</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($statistics['expired_points'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Denna månad</p>
                </div>
                <div class="text-4xl opacity-75">⏰</div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Användare med Poäng</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($statistics['users_with_points'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Aktiva användare</p>
                </div>
                <div class="text-4xl opacity-75">👥</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Användare</label>
                <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Alla användare</option>
                    @foreach(\App\Models\User::where('loyalty_points_balance', '>', 0)->orderBy('name')->get() as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->loyalty_points_balance }} poäng)
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Typ</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Alla typer</option>
                    <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Tjänade</option>
                    <option value="spent" {{ request('type') === 'spent' ? 'selected' : '' }}>Använda</option>
                    <option value="refunded" {{ request('type') === 'refunded' ? 'selected' : '' }}>Återbetalade</option>
                    <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>Förfallna</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Från datum</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Filtrera
                </button>
            </div>
        </form>
    </div>

    <!-- Loyalty Points Transactions -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">📋</span>
                Lojalitetspoäng Transaktioner
            </h3>
        </div>

        @if(isset($transactions) && $transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Användare</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poäng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Värde (SEK)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beskrivning</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokning</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $transaction->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeConfig = [
                                    'earned' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Tjänade'],
                                    'spent' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Använda'],
                                    'refunded' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Återbetalade'],
                                    'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Förfallna'],
                                    'adjusted' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Justerade'],
                                ];
                                $config = $typeConfig[$transaction->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($transaction->type)];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium {{ $transaction->type === 'earned' || $transaction->type === 'refunded' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'earned' || $transaction->type === 'refunded' ? '+' : '-' }}{{ number_format($transaction->points, 0, ',', ' ') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ number_format($transaction->value_sek, 0, ',', ' ') }} kr</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">{{ $transaction->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaction->booking)
                                <a href="{{ route('admin.bookings.show', $transaction->booking) }}" class="text-blue-600 hover:underline text-sm">
                                    #{{ $transaction->booking->booking_number }}
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('Y-m-d H:i') }}
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
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga lojalitetspoäng transaktioner</h3>
            <p class="text-gray-500">Transaktioner kommer att visas här när användare tjänar eller använder lojalitetspoäng.</p>
        </div>
        @endif
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Lojalitetspoäng System</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• Användare tjänar poäng för slutförda bokningar</p>
                    <p>• 1 poäng = 1 SEK rabatt på framtida bokningar</p>
                    <p>• Poäng förfaller efter 12 månader</p>
                    <p>• Automatisk hantering av poäng vid bokning och avbokning</p>
                    <p>• Välkomstbonus: 100 poäng för nya användare</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
