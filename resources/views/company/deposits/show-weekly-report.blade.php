@extends('layouts.company')

@section('title', 'Veckorapport Detaljer')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">📈</span>
                    Veckorapport Detaljer
                </h1>
                <p class="text-gray-600 mt-2">Vecka {{ $report->week_number }}, {{ $report->year }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('company.deposits.weekly-reports') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ← Tillbaka
                </a>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if($report->status === 'sent')
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
        <div class="flex items-center">
            <div class="text-blue-500 text-2xl mr-3">📤</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Rapport Skickad</h3>
                <p class="text-blue-700 mt-1">
                    Denna veckorapport skickades {{ $report->sent_at ? \Carbon\Carbon::parse($report->sent_at)->format('d/m/Y H:i') : 'N/A' }}.
                    Faktura och betalningsinstruktioner har skickats via e-post.
                </p>
            </div>
        </div>
    </div>
    @elseif($report->status === 'paid')
    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
        <div class="flex items-center">
            <div class="text-green-500 text-2xl mr-3">✅</div>
            <div>
                <h3 class="text-lg font-semibold text-green-800">Betalning Mottagen</h3>
                <p class="text-green-700 mt-1">
                    Betalningen för denna veckorapport har mottagits. Tack för din betalning!
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Period Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">📅</span>
                    Periodinformation
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Vecka</p>
                        <p class="font-medium">Vecka {{ $report->week_number }}, {{ $report->year }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Period</p>
                        <p class="font-medium">
                            {{ \Carbon\Carbon::parse($report->period_start)->format('d/m/Y') }} - 
                            {{ \Carbon\Carbon::parse($report->period_end)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Antal dagar</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($report->period_start)->diffInDays(\Carbon\Carbon::parse($report->period_end)) + 1 }} dagar</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
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
                    </div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">💰</span>
                    Ekonomisk Sammanfattning
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-gray-600">Antal bokningar</span>
                        <span class="font-semibold">{{ $report->total_bookings }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-gray-600">Totala intäkter</span>
                        <span class="font-semibold">{{ number_format($report->total_revenue, 0, ',', ' ') }} kr</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-gray-600">Total kommission</span>
                        <span class="font-semibold text-red-600">-{{ number_format($report->total_commission, 0, ',', ' ') }} kr</span>
                    </div>
                    
                    @if($report->total_loyalty_points_deduction > 0)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="text-gray-600">Lojalitetspoäng avdrag</span>
                        <span class="font-semibold text-blue-600">-{{ number_format($report->total_loyalty_points_deduction, 0, ',', ' ') }} kr</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center py-4 bg-gray-50 rounded-lg px-4">
                        <span class="text-lg font-bold text-gray-900">Netto deposit att betala</span>
                        <span class="text-lg font-bold text-green-600">{{ number_format($report->net_commission_due, 0, ',', ' ') }} kr</span>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">📊</span>
                    Prestanda
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $report->total_bookings }}</div>
                        <div class="text-sm text-blue-600">Bokningar</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($report->total_revenue / max($report->total_bookings, 1), 0, ',', ' ') }} kr</div>
                        <div class="text-sm text-green-600">Genomsnitt per bokning</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ number_format(($report->total_commission / max($report->total_revenue, 1)) * 100, 1) }}%</div>
                        <div class="text-sm text-purple-600">Kommissionssats</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
                
                <div class="text-center">
                    <div class="text-4xl mb-2">
                        @if($report->status === 'pending') ⏳
                        @elseif($report->status === 'sent') 📤
                        @elseif($report->status === 'paid') ✅
                        @else ❓
                        @endif
                    </div>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                        {{ $config['label'] }}
                    </span>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tidslinje</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium">Rapport genererad</p>
                            <p class="text-xs text-gray-500">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($report->sent_at)
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium">Rapport skickad</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($report->sent_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($report->status === 'paid')
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium">Betalning mottagen</p>
                            <p class="text-xs text-gray-500">Bekräftat</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Instructions -->
            @if($report->status === 'sent' || $report->status === 'paid')
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-lg font-bold text-blue-800 mb-4">Betalningsinstruktioner</h3>
                <div class="space-y-3 text-sm text-blue-700">
                    <p><strong>Bankgiro:</strong> 123-4567</p>
                    <p><strong>Belopp:</strong> {{ number_format($report->net_commission_due, 0, ',', ' ') }} kr</p>
                    <p><strong>Meddelande:</strong> Vecka {{ $report->week_number }}/{{ $report->year }}</p>
                </div>
                <p class="text-xs text-blue-600 mt-3">
                    Använd veckan som betalningsreferens för att säkerställa korrekt registrering.
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
