@extends('layouts.company')

@section('title', 'Deposit Detaljer')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">💰</span>
                    Deposit Detaljer
                </h1>
                <p class="text-gray-600 mt-2">Faktura #{{ $deposit->invoice_number }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('company.deposits.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ← Tillbaka
                </a>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if($deposit->status === 'overdue')
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
        <div class="flex items-center">
            <div class="text-red-500 text-2xl mr-3">⚠️</div>
            <div>
                <h3 class="text-lg font-semibold text-red-800">Försenad Betalning</h3>
                <p class="text-red-700 mt-1">
                    Denna deposit är försenad sedan {{ \Carbon\Carbon::parse($deposit->due_date)->format('d/m/Y') }}.
                    Vänligen betala snarast möjligt.
                </p>
            </div>
        </div>
    </div>
    @elseif($deposit->status === 'sent')
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
        <div class="flex items-center">
            <div class="text-blue-500 text-2xl mr-3">📤</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Faktura Skickad</h3>
                <p class="text-blue-700 mt-1">
                    Fakturan skickades {{ $deposit->sent_at ? \Carbon\Carbon::parse($deposit->sent_at)->format('d/m/Y H:i') : 'N/A' }}.
                    Förfallodatum: {{ \Carbon\Carbon::parse($deposit->due_date)->format('d/m/Y') }}.
                </p>
            </div>
        </div>
    </div>
    @elseif($deposit->status === 'paid')
    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
        <div class="flex items-center">
            <div class="text-green-500 text-2xl mr-3">✅</div>
            <div>
                <h3 class="text-lg font-semibold text-green-800">Betalning Mottagen</h3>
                <p class="text-green-700 mt-1">
                    Betalningen mottogs {{ $deposit->paid_at ? \Carbon\Carbon::parse($deposit->paid_at)->format('d/m/Y H:i') : 'N/A' }}.
                    Tack för din betalning!
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Booking Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">📋</span>
                    Bokningsinformation
                </h3>
                
                @if($deposit->booking)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Bokningsnummer</p>
                        <a href="{{ route('company.bookings.show', $deposit->booking) }}" class="text-blue-600 hover:underline font-medium">
                            #{{ $deposit->booking->booking_number }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tjänst</p>
                        <p class="font-medium">{{ $deposit->booking->service->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Stad</p>
                        <p class="font-medium">{{ $deposit->booking->city->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Bokningsdatum</p>
                        <p class="font-medium">{{ $deposit->booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Kund</p>
                        <p class="font-medium">{{ $deposit->booking->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ ucfirst($deposit->booking->status) }}
                        </span>
                    </div>
                </div>
                @else
                <p class="text-gray-500">Ingen bokning kopplad till denna deposit.</p>
                @endif
            </div>

            <!-- Payment Details -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">💳</span>
                    Betalningsdetaljer
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Bokningsbelopp</span>
                        <span class="font-semibold">{{ number_format($deposit->booking_amount, 0, ',', ' ') }} kr</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Kommission ({{ $deposit->commission_rate }}%)</span>
                        <span class="font-semibold text-red-600">-{{ number_format($deposit->commission_amount, 0, ',', ' ') }} kr</span>
                    </div>
                    
                    @if($deposit->loyalty_points_value > 0)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Lojalitetspoäng ({{ number_format($deposit->loyalty_points_used, 0, ',', ' ') }} poäng)</span>
                        <span class="font-semibold text-blue-600">-{{ number_format($deposit->loyalty_points_value, 0, ',', ' ') }} kr</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center py-3 bg-gray-50 rounded-lg px-4">
                        <span class="text-lg font-bold text-gray-900">Deposit att betala</span>
                        <span class="text-lg font-bold text-green-600">{{ number_format($deposit->deposit_amount, 0, ',', ' ') }} kr</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($deposit->notes)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">📝</span>
                    Anteckningar
                </h3>
                <p class="text-gray-700">{{ $deposit->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
                @php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Väntande', 'icon' => '⏳'],
                        'sent' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Skickad', 'icon' => '📤'],
                        'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Betald', 'icon' => '✅'],
                        'overdue' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Försenad', 'icon' => '⚠️'],
                        'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Avbruten', 'icon' => '❌'],
                    ];
                    $config = $statusConfig[$deposit->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($deposit->status), 'icon' => '❓'];
                @endphp
                
                <div class="text-center">
                    <div class="text-4xl mb-2">{{ $config['icon'] }}</div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                        {{ $config['label'] }}
                    </span>
                </div>
            </div>

            <!-- Invoice Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Faktura</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Fakturanummer</p>
                        <p class="font-medium">{{ $deposit->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fakturadatum</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($deposit->invoice_date)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Förfallodatum</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($deposit->due_date)->format('d/m/Y') }}</p>
                    </div>
                    @if($deposit->sent_at)
                    <div>
                        <p class="text-sm text-gray-500">Skickad</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($deposit->sent_at)->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($deposit->paid_at)
                    <div>
                        <p class="text-sm text-gray-500">Betald</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($deposit->paid_at)->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Instructions -->
            @if($deposit->status === 'sent' || $deposit->status === 'overdue')
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-4">Betalningsinstruktioner</h3>
                <div class="space-y-3 text-sm text-blue-700">
                    <p><strong>Bankgiro:</strong> 123-4567</p>
                    <p><strong>Belopp:</strong> {{ number_format($deposit->deposit_amount, 0, ',', ' ') }} kr</p>
                    <p><strong>Meddelande:</strong> {{ $deposit->invoice_number }}</p>
                </div>
                <p class="text-xs text-blue-600 mt-3">
                    Använd fakturanummer som betalningsreferens för att säkerställa korrekt registrering.
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
