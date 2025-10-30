@extends('layouts.admin')

@section('title', 'Deposits')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">💰</span>
                    Deposits & Kommissioner
                </h1>
                <p class="text-gray-600 mt-2">Hantera företagsbetalningar och kommissioner</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.deposits.weekly-reports') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    📈 Veckorapporter
                </a>
                <a href="{{ route('admin.invoices.index') }}" 
                   class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    🧾 Fakturor
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Deposits -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Totala Deposits</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['total_deposits'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($statistics['total_amount'] ?? 0, 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">💰</div>
            </div>
        </div>

        <!-- Pending Deposits -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Väntande</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['pending_deposits'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($statistics['pending_amount'] ?? 0, 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">⏳</div>
            </div>
        </div>

        <!-- Sent Deposits -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Skickade</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['sent_deposits'] ?? 0 }}</p>
                </div>
                <div class="text-4xl opacity-75">📤</div>
            </div>
        </div>

        <!-- Paid Deposits -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Betalda</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['paid_deposits'] ?? 0 }}</p>
                </div>
                <div class="text-4xl opacity-75">✅</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Alla statusar</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Väntande</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Skickade</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Betalda</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Försenade</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Företag</label>
                <select name="company_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Alla företag</option>
                    @foreach(\App\Models\Company::orderBy('company_name')->get() as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
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

    <!-- Deposits Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">📋</span>
                Deposits Lista
            </h3>
        </div>

        @if(isset($deposits) && $deposits->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Företag</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokning</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Belopp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kommission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lojalitetspoäng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deposit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Förfallodatum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($deposits as $deposit)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $deposit->company->company_name }}</div>
                            <div class="text-sm text-gray-500">{{ $deposit->company->user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($deposit->booking)
                                <a href="{{ route('admin.bookings.show', $deposit->booking) }}" class="text-blue-600 hover:underline text-sm">
                                    #{{ $deposit->booking->booking_number }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $deposit->booking->service->name ?? 'N/A' }}</p>
                            @else
                                <span class="text-gray-500 text-sm">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 font-medium">{{ number_format($deposit->booking_amount, 0, ',', ' ') }} kr</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-red-600 font-medium">{{ number_format($deposit->commission_amount, 0, ',', ' ') }} kr</span>
                            <p class="text-xs text-gray-500">{{ $deposit->commission_rate }}%</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($deposit->loyalty_points_value > 0)
                                <span class="text-sm text-blue-600 font-medium">-{{ number_format($deposit->loyalty_points_value, 0, ',', ' ') }} kr</span>
                                <p class="text-xs text-gray-500">{{ number_format($deposit->loyalty_points_used, 0, ',', ' ') }} poäng</p>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-green-600 font-bold">{{ number_format($deposit->deposit_amount, 0, ',', ' ') }} kr</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Väntande'],
                                    'sent' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Skickad'],
                                    'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Betald'],
                                    'overdue' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Försenad'],
                                    'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Avbruten'],
                                ];
                                $config = $statusConfig[$deposit->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($deposit->status)];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($deposit->due_date)->format('d/m/Y') }}
                            @if($deposit->status === 'sent' && $deposit->due_date < now()->toDateString())
                                <span class="text-red-500 text-xs block">Försenad</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.deposits.show', $deposit) }}" class="text-blue-600 hover:text-blue-900">Visa</a>
                                @if($deposit->status === 'pending')
                                    <form method="POST" action="{{ route('admin.deposits.send', $deposit) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">Skicka</button>
                                    </form>
                                @endif
                                @if($deposit->status === 'sent')
                                    <form method="POST" action="{{ route('admin.deposits.mark-paid', $deposit) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-purple-600 hover:text-purple-900">Markera som betald</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $deposits->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <div class="text-5xl mb-4">💰</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga deposits ännu</h3>
            <p class="text-gray-500">Deposits kommer att visas här när bokningar slutförs och kommissioner beräknas.</p>
        </div>
        @endif
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Hur fungerar deposits?</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• När en bokning slutförs skapas automatiskt en deposit</p>
                    <p>• Företag betalar kommission baserat på deras kommissionssats</p>
                    <p>• Om kunden använder lojalitetspoäng minskar företagets deposit</p>
                    <p>• Veckorapporter skickas automatiskt varje måndag</p>
                    <p>• Betalning sker via bankgiro enligt svenska standarder</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
