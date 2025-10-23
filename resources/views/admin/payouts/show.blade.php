@extends('layouts.admin')

@section('title', 'Utbetalning #' . $payout->id)

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">💰 Utbetalning #{{ $payout->id }}</h2>
    <p class="text-gray-600">Detaljer för utbetalning till {{ $payout->company->company_name ?? $payout->company->user->email }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Payout Details -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Utbetalningsdetaljer</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Utbetalnings-ID</label>
                    <p class="text-gray-900 font-mono">#{{ $payout->id }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    @switch($payout->status)
                        @case('pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">⏳ Väntande</span>
                            @break
                        @case('approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">✅ Godkänd</span>
                            @break
                        @case('paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">💰 Betald</span>
                            @break
                        @case('cancelled')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">❌ Avbruten</span>
                            @break
                    @endswitch
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Skapad</label>
                    <p class="text-gray-900">{{ $payout->created_at->format('Y-m-d H:i') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Senast uppdaterad</label>
                    <p class="text-gray-900">{{ $payout->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Financial Details -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">💵 Finansiella detaljer</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                    <span class="text-gray-600">Bokningsbelopp</span>
                    <span class="font-semibold">{{ number_format($payout->booking_amount, 0, ',', ' ') }} kr</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                    <span class="text-gray-600">Admin provision</span>
                    <span class="font-semibold text-red-600">-{{ number_format($payout->commission_amount, 0, ',', ' ') }} kr</span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                    <span class="text-gray-600">ROT-avdrag</span>
                    <span class="font-semibold text-red-600">-{{ number_format($payout->rot_deduction, 0, ',', ' ') }} kr</span>
                </div>
                
                <div class="flex justify-between items-center py-2 bg-blue-50 rounded-lg px-4">
                    <span class="text-lg font-semibold text-gray-900">Utbetalningsbelopp</span>
                    <span class="text-xl font-bold text-blue-600">{{ number_format($payout->payout_amount, 0, ',', ' ') }} kr</span>
                </div>
            </div>
        </div>

        <!-- Booking Information -->
        @if($payout->booking)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📅 Bokningsinformation</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bokningsnummer</label>
                    <a href="{{ route('admin.bookings.show', $payout->booking) }}" class="text-blue-600 hover:underline">
                        {{ $payout->booking->booking_number }}
                    </a>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tjänst</label>
                    <p class="text-gray-900">{{ $payout->booking->service->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stad</label>
                    <p class="text-gray-900">{{ $payout->booking->city->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kund</label>
                    <p class="text-gray-900">{{ $payout->booking->customer_name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-post</label>
                    <p class="text-gray-900">{{ $payout->booking->customer_email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <p class="text-gray-900">{{ $payout->booking->customer_phone }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Company Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🏢 Företagsinformation</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Företagsnamn</label>
                    <p class="text-gray-900">{{ $payout->company->company_name ?? 'Ej angivet' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-post</label>
                    <p class="text-gray-900">{{ $payout->company->user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <p class="text-gray-900">{{ $payout->company->phone ?? 'Ej angivet' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    @if($payout->company->status === 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅ Aktiv</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">❌ Inaktiv</span>
                    @endif
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('admin.companies.show', $payout->company) }}" class="w-full btn btn-primary">
                    👁️ Visa företag
                </a>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Åtgärder</h3>
            
            <div class="space-y-3">
                @if($payout->status === 'pending')
                    <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full btn btn-success" onclick="return confirm('Godkänn denna utbetalning?')">
                            ✅ Godkänn utbetalning
                        </button>
                    </form>
                @endif
                
                @if($payout->status === 'approved')
                    <form method="POST" action="{{ route('admin.payouts.mark-as-paid', $payout) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full btn btn-primary" onclick="return confirm('Markera som betald?')">
                            💰 Markera som betald
                        </button>
                    </form>
                @endif
                
                @if($payout->status === 'pending')
                    <form method="POST" action="{{ route('admin.payouts.cancel', $payout) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full btn btn-danger" onclick="return confirm('Avbryt denna utbetalning?')">
                            ❌ Avbryt utbetalning
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.payouts.index') }}" class="w-full btn btn-secondary">
                    ← Tillbaka till lista
                </a>
            </div>
        </div>

        <!-- Approval History -->
        @if($payout->approved_at || $payout->paid_at)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Godkännandhistorik</h3>
            
            <div class="space-y-3">
                @if($payout->approved_at)
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Godkänd</p>
                        <p class="text-xs text-gray-500">{{ $payout->approved_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @endif
                
                @if($payout->paid_at)
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Betald</p>
                        <p class="text-xs text-gray-500">{{ $payout->paid_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

