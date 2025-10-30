@extends('layouts.company')

@section('title', 'Veckorapporter')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">📈</span>
                    Veckorapporter
                </h1>
                <p class="text-gray-600 mt-2">Veckovis sammanfattning av dina kommissioner och deposits</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('company.deposits.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ← Tillbaka till Deposits
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Reports -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Totala Rapporter</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['total_reports'] }}</p>
                </div>
                <div class="text-4xl opacity-75">📊</div>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Väntande</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['pending_reports'] }}</p>
                </div>
                <div class="text-4xl opacity-75">⏳</div>
            </div>
        </div>

        <!-- Sent Reports -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Skickade</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['sent_reports'] }}</p>
                </div>
                <div class="text-4xl opacity-75">📤</div>
            </div>
        </div>

        <!-- Paid Reports -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Betalda</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['paid_reports'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($statistics['total_commission'], 0, ',', ' ') }} kr</p>
                </div>
                <div class="text-4xl opacity-75">✅</div>
            </div>
        </div>
    </div>

    <!-- Weekly Reports Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">📅</span>
                Veckorapporter Historik
            </h3>
        </div>

        @if($reports->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vecka</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokningar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intäkter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kommission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lojalitetspoäng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Netto Deposit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skickad</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reports as $report)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Vecka {{ $report->week_number }}</div>
                            <div class="text-sm text-gray-500">{{ $report->year }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($report->period_start)->format('d/m') }} - 
                                {{ \Carbon\Carbon::parse($report->period_end)->format('d/m') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 font-medium">{{ $report->total_bookings }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 font-medium">{{ number_format($report->total_revenue, 0, ',', ' ') }} kr</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-red-600 font-medium">{{ number_format($report->total_commission, 0, ',', ' ') }} kr</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($report->total_loyalty_points_deduction > 0)
                                <span class="text-sm text-blue-600 font-medium">-{{ number_format($report->total_loyalty_points_deduction, 0, ',', ' ') }} kr</span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-green-600 font-bold">{{ number_format($report->net_commission_due, 0, ',', ' ') }} kr</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Väntande'],
                                    'sent' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Skickad'],
                                    'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Betald'],
                                ];
                                $config = $statusConfig[$report->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($report->status)];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($report->sent_at)
                                {{ \Carbon\Carbon::parse($report->sent_at)->format('d/m/Y H:i') }}
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
            {{ $reports->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <div class="text-5xl mb-4">📈</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga veckorapporter ännu</h3>
            <p class="text-gray-500">Veckorapporter genereras automatiskt varje måndag kl 09:00 för företag med slutförda bokningar.</p>
        </div>
        @endif
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Hur fungerar veckorapporter?</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• Rapporter genereras automatiskt varje måndag kl 09:00</p>
                    <p>• Inkluderar alla slutförda bokningar från föregående vecka</p>
                    <p>• Visar totala intäkter, kommissioner och lojalitetspoäng</p>
                    <p>• Skickas automatiskt via e-post med faktura</p>
                    <p>• Betalning sker via bankgiro enligt svenska standarder</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
