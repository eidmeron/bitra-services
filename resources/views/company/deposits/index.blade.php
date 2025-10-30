@extends('layouts.company')

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
                <p class="text-gray-600 mt-2">Hantera dina kommissionsbetalningar till Bitra</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('company.deposits.weekly-reports') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    📈 Veckorapporter
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
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['total_deposits'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($statistics['total_amount'], 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">💰</div>
            </div>
        </div>

        <!-- Pending Deposits -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Väntande</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['pending_deposits'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($statistics['pending_amount'], 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">⏳</div>
            </div>
        </div>

        <!-- Sent Deposits -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Skickade</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['sent_deposits'] }}</p>
                </div>
                <div class="text-4xl opacity-75">📤</div>
            </div>
        </div>

        <!-- Paid Deposits -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Betalda</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['paid_deposits'] }}</p>
                </div>
                <div class="text-4xl opacity-75">✅</div>
            </div>
        </div>
    </div>

    <!-- Overdue Warning -->
    @if($statistics['overdue_deposits'] > 0)
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
        <div class="flex items-center">
            <div class="text-red-500 text-2xl mr-3">⚠️</div>
            <div>
                <h3 class="text-lg font-semibold text-red-800">Försenade Deposits</h3>
                <p class="text-red-700 mt-1">
                    Du har {{ $statistics['overdue_deposits'] }} försenade deposits till ett värde av 
                    {{ number_format($statistics['overdue_amount'], 0, ',', ' ') }} kr.
                </p>
                <p class="text-sm text-red-600 mt-2">
                    Vänligen betala dessa snarast möjligt för att undvika ytterligare avgifter.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Deposits Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">📋</span>
                Deposits Historik
            </h3>
        </div>

        @if($deposits->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokning</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokningsbelopp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kommission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lojalitetspoäng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deposit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Förfallodatum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($deposits as $deposit)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($deposit->booking)
                                <a href="{{ route('company.bookings.show', $deposit->booking) }}" class="text-blue-600 hover:underline font-medium text-sm">
                                    #{{ $deposit->booking->booking_number }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">{{ $deposit->booking->service->name ?? 'N/A' }}</p>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $deposit->created_at->format('d/m/Y') }}
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
            <p class="text-gray-500">Deposits kommer att visas här när dina bokningar slutförs och kommissioner beräknas.</p>
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
                    <p>• Du betalar kommission baserat på din kommissionssats</p>
                    <p>• Om kunden använder lojalitetspoäng minskar din deposit</p>
                    <p>• Veckorapporter skickas automatiskt varje måndag</p>
                    <p>• Betalning sker via bankgiro enligt svenska standarder</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
