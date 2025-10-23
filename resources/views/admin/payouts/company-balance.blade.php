@extends('layouts.admin')

@section('title', 'Företagsbalans - ' . $balance['company']->company_name)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">💰 Företagsbalans</h2>
            <p class="text-gray-600">{{ $balance['company']->company_name ?? $balance['company']->user->email }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.companies.show', $balance['company']) }}" class="btn btn-secondary">
                👁️ Visa Företag
            </a>
            <a href="{{ route('admin.payouts.index') }}" class="btn btn-primary">
                ← Tillbaka till Utbetalningar
            </a>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Omsättning</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($balance['total_revenue'], 0, ',', ' ') }} kr</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">📊</span>
            </div>
        </div>
    </div>

    <!-- Commission -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Admin Provision</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($balance['total_commission'], 0, ',', ' ') }} kr</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">💰</span>
            </div>
        </div>
    </div>

    <!-- Pending Payouts -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Väntande Utbetalningar</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($balance['pending_payouts'], 0, ',', ' ') }} kr</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">⏳</span>
            </div>
        </div>
    </div>

    <!-- Current Balance -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Aktuell Balans</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($balance['current_balance'], 0, ',', ' ') }} kr</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">💳</span>
            </div>
        </div>
    </div>
</div>

<!-- Company Information -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">🏢 Företagsinformation</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Företagsnamn</label>
            <p class="text-gray-900">{{ $balance['company']->company_name ?? 'Ej angivet' }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">E-post</label>
            <p class="text-gray-900">{{ $balance['company']->user->email }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
            <p class="text-gray-900">{{ $balance['company']->phone ?? 'Ej angivet' }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            @if($balance['company']->status === 'active')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✅ Aktiv</span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">❌ Inaktiv</span>
            @endif
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Registrerad</label>
            <p class="text-gray-900">{{ $balance['company']->created_at->format('Y-m-d') }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Provision</label>
            <p class="text-gray-900">{{ $balance['commission_rate'] }}%</p>
        </div>
    </div>
</div>

<!-- Payouts Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
        <h3 class="text-lg font-semibold text-gray-900">Utbetalningshistorik</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokning</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Belopp</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provision</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ROT-avdrag</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utbetalning</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($balance['payouts'] as $payout)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-sm">#{{ $payout->booking->booking_number ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $payout->booking->service->name ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $payout->booking->city->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($payout->booking_amount, 0, ',', ' ') }} kr</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-red-600 font-medium">-{{ number_format($payout->commission_amount, 0, ',', ' ') }} kr</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-red-600 font-medium">-{{ number_format($payout->rot_deduction, 0, ',', ' ') }} kr</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-green-600">{{ number_format($payout->payout_amount, 0, ',', ' ') }} kr</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($payout->status)
                            @case('pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⏳ Väntande
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ✅ Godkänd
                                </span>
                                @break
                            @case('paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    💰 Betald
                                </span>
                                @break
                            @case('cancelled')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    ❌ Avbruten
                                </span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payout->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.payouts.show', $payout) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Visa →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <div class="text-5xl mb-4">📭</div>
                        <p class="text-lg mb-2">Inga utbetalningar ännu</p>
                        <p class="text-sm text-gray-400">Utbetalningar kommer att visas här när bokningar slutförs</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if(isset($balance['payouts']) && $balance['payouts']->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $balance['payouts']->links() }}
        </div>
    @endif
</div>

<!-- Weekly Reports -->
@if(isset($balance['weekly_reports']) && $balance['weekly_reports']->count() > 0)
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b">
        <h3 class="text-lg font-semibold text-gray-900">📄 Veckorapporter</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vecka</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokningar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Omsättning</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provision</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utbetalning</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($balance['weekly_reports'] as $report)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            Vecka {{ $report->week_number }}, {{ $report->year }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $report->start_date->format('M d') }} - {{ $report->end_date->format('M d') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $report->total_bookings }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ number_format($report->total_revenue, 0, ',', ' ') }} kr</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-red-600 font-medium">-{{ number_format($report->total_commission, 0, ',', ' ') }} kr</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-green-600">{{ number_format($report->total_payout, 0, ',', ' ') }} kr</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($report->is_paid)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                💰 Betald
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                ⏳ Väntande
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection