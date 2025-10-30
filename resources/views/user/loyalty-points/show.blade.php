@extends('layouts.user')

@section('title', 'Lojalitetspoäng Transaktion')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">⭐</span>
                    Lojalitetspoäng Transaktion
                </h1>
                <p class="text-gray-600 mt-2">Detaljer för din lojalitetspoäng transaktion</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('user.loyalty-points.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ← Tillbaka
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Transaction Details -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">📋</span>
                    Transaktionsdetaljer
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Transaktionstyp</p>
                        @php
                            $typeConfig = [
                                'earned' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Tjänade', 'icon' => '🎁'],
                                'spent' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Använda', 'icon' => '💳'],
                                'refunded' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Återbetalda', 'icon' => '🔄'],
                                'expired' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Expirerade', 'icon' => '⏰'],
                                'adjusted' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Justerade', 'icon' => '⚙️'],
                            ];
                            $config = $typeConfig[$loyaltyPoint->type] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($loyaltyPoint->type), 'icon' => '❓'];
                        @endphp
                        <div class="flex items-center mt-1">
                            <span class="text-lg mr-2">{{ $config['icon'] }}</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ $config['label'] }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Poäng</p>
                        <p class="font-medium text-lg {{ $loyaltyPoint->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $loyaltyPoint->points > 0 ? '+' : '' }}{{ number_format($loyaltyPoint->points, 0, ',', ' ') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Värde (SEK)</p>
                        <p class="font-medium text-lg text-gray-900">{{ number_format($loyaltyPoint->value_sek, 0, ',', ' ') }} kr</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Datum</p>
                        <p class="font-medium">{{ $loyaltyPoint->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">📝</span>
                    Beskrivning
                </h3>
                <p class="text-gray-700 text-lg">{{ $loyaltyPoint->description }}</p>
                @if($loyaltyPoint->notes)
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600"><strong>Anteckningar:</strong></p>
                        <p class="text-gray-700 mt-1">{{ $loyaltyPoint->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Booking Information -->
            @if($loyaltyPoint->booking)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">📋</span>
                    Relaterad Bokning
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Bokningsnummer</p>
                        <a href="{{ route('user.bookings.show', $loyaltyPoint->booking) }}" class="text-blue-600 hover:underline font-medium">
                            #{{ $loyaltyPoint->booking->booking_number }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tjänst</p>
                        <p class="font-medium">{{ $loyaltyPoint->booking->service->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Stad</p>
                        <p class="font-medium">{{ $loyaltyPoint->booking->city->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Bokningsdatum</p>
                        <p class="font-medium">{{ $loyaltyPoint->booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ ucfirst($loyaltyPoint->booking->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total kostnad</p>
                        <p class="font-medium">{{ number_format($loyaltyPoint->booking->final_price, 0, ',', ' ') }} kr</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Points Summary -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Poäng Sammanfattning</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Poäng</span>
                        <span class="font-semibold {{ $loyaltyPoint->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $loyaltyPoint->points > 0 ? '+' : '' }}{{ number_format($loyaltyPoint->points, 0, ',', ' ') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Värde</span>
                        <span class="font-semibold">{{ number_format($loyaltyPoint->value_sek, 0, ',', ' ') }} kr</span>
                    </div>
                    @if($loyaltyPoint->expires_at)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Upphör</span>
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($loyaltyPoint->expires_at)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Expiration Warning -->
            @if($loyaltyPoint->expires_at && $loyaltyPoint->type === 'earned')
            @php
                $expiresAt = \Carbon\Carbon::parse($loyaltyPoint->expires_at);
                $daysLeft = $expiresAt->diffInDays(now());
            @endphp
            @if($daysLeft <= 30)
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                <div class="flex items-center">
                    <div class="text-orange-500 text-2xl mr-3">⚠️</div>
                    <div>
                        <h3 class="text-lg font-semibold text-orange-800">Upphör snart</h3>
                        <p class="text-orange-700 mt-1">
                            Dessa poäng upphör om {{ $daysLeft }} dagar.
                            Använd dem snart!
                        </p>
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- Admin Information -->
            @if($loyaltyPoint->createdBy)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Administratör</h3>
                <div class="space-y-2">
                    <p class="text-sm text-gray-600">Skapad av:</p>
                    <p class="font-medium">{{ $loyaltyPoint->createdBy->name }}</p>
                    <p class="text-xs text-gray-500">{{ $loyaltyPoint->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            @endif

            <!-- Help -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-4">Behöver du hjälp?</h3>
                <div class="text-blue-700 text-sm space-y-2">
                    <p>• Lojalitetspoäng upphör efter 1 år</p>
                    <p>• 1 poäng = 1 SEK värde</p>
                    <p>• Använd poäng på nya bokningar</p>
                    <p>• Kontakta support om du har frågor</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
