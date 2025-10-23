@extends('layouts.company')

@section('title', 'Utbetalningsdetaljer')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('company.payouts.index') }}" class="text-purple-600 hover:text-purple-800 mb-2 inline-block">
            ← Tillbaka till Utbetalningar
        </a>
        <h2 class="text-3xl font-bold text-gray-900">💰 Utbetalningsdetaljer</h2>
        <p class="text-gray-600 mt-1">{{ $payout->payout_number }}</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Status Banner -->
        @php
            $statusConfig = [
                'pending' => ['bg' => 'bg-yellow-500', 'text' => 'text-white', 'label' => '⏳ Väntande', 'desc' => 'Utbetalningen väntar på godkännande'],
                'approved' => ['bg' => 'bg-blue-500', 'text' => 'text-white', 'label' => '✅ Godkänd', 'desc' => 'Utbetalningen är godkänd och väntar på betalning'],
                'paid' => ['bg' => 'bg-green-500', 'text' => 'text-white', 'label' => '💰 Utbetald', 'desc' => 'Utbetalningen har genomförts'],
                'cancelled' => ['bg' => 'bg-red-500', 'text' => 'text-white', 'label' => '❌ Avbruten', 'desc' => 'Utbetalningen har avbrutits'],
            ];
            $config = $statusConfig[$payout->status] ?? ['bg' => 'bg-gray-500', 'text' => 'text-white', 'label' => ucfirst($payout->status), 'desc' => ''];
        @endphp
        <div class="{{ $config['bg'] }} {{ $config['text'] }} p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">{{ $config['label'] }}</h3>
                    <p class="text-sm opacity-90 mt-1">{{ $config['desc'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-90">Utbetalningsbelopp</p>
                    <p class="text-3xl font-bold">{{ number_format($payout->payout_amount, 2, ',', ' ') }} kr</p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Booking Info -->
            @if($payout->booking)
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3">📋 Bokningsinformation</h4>
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bokningsnummer:</span>
                        <a href="{{ route('company.bookings.show', $payout->booking) }}" class="font-semibold text-purple-600 hover:underline">
                            #{{ $payout->booking->booking_number }}
                        </a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kund:</span>
                        <span class="font-semibold">{{ $payout->booking->user->name ?? $payout->booking->user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tjänst:</span>
                        <span class="font-semibold">{{ $payout->booking->service->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bokningsdatum:</span>
                        <span class="font-semibold">{{ $payout->booking->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Financial Breakdown -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3">💵 Ekonomisk Uppdelning</h4>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div class="flex justify-between items-center border-b pb-2">
                        <span class="text-gray-700 font-medium">Totalt Bokningsbelopp:</span>
                        <span class="text-xl font-bold text-gray-900">{{ number_format($payout->booking_amount, 2, ',', ' ') }} kr</span>
                    </div>
                    <div class="flex justify-between items-center text-red-600">
                        <span class="font-medium">
                            Plattformsprovision ({{ number_format($payout->commission_rate ?? 0, 1) }}%):
                        </span>
                        <span class="text-lg font-semibold">-{{ number_format($payout->commission_amount, 2, ',', ' ') }} kr</span>
                    </div>
                    <div class="flex justify-between items-center border-t pt-3 text-green-600">
                        <span class="text-lg font-semibold">Din Utbetalning:</span>
                        <span class="text-2xl font-bold">{{ number_format($payout->payout_amount, 2, ',', ' ') }} kr</span>
                    </div>
                </div>
            </div>

            <!-- Period Info -->
            @if($payout->payout_period)
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3">📅 Utbetalningsperiod</h4>
                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                    <p class="text-purple-800 font-semibold">{{ $payout->payout_period }}</p>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3">🕒 Tidslinje</h4>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-bold">✓</span>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-semibold text-gray-900">Skapad</p>
                            <p class="text-sm text-gray-600">{{ $payout->created_at->format('Y-m-d H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $payout->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($payout->approved_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 font-bold">✓</span>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-semibold text-gray-900">Godkänd</p>
                            <p class="text-sm text-gray-600">{{ $payout->approved_at->format('Y-m-d H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $payout->approved_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif

                    @if($payout->paid_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-bold">✓</span>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-semibold text-gray-900">Utbetald</p>
                            <p class="text-sm text-gray-600">{{ $payout->paid_at->format('Y-m-d H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $payout->paid_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($payout->notes)
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-3">📝 Anteckningar</h4>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <p class="text-gray-700">{{ $payout->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Help Section -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Hjälp & Support</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Om du har frågor om denna utbetalning, kontakta vår support eller använd chatten i ditt dashboard.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

