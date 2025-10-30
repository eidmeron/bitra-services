@extends('layouts.admin')

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
                <p class="text-gray-600 mt-2">Veckovis sammanfattning av kommissioner och deposits</p>
            </div>
            <div class="flex space-x-3">
                <form method="POST" action="{{ route('admin.deposits.generate-weekly-reports') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                            onclick="return confirm('Är du säker på att du vill generera veckorapporter för alla företag?')">
                        📊 Generera Rapporter
                    </button>
                </form>
                <a href="{{ route('admin.deposits.index') }}" 
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
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['total_reports'] ?? 0 }}</p>
                </div>
                <div class="text-4xl opacity-75">📊</div>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 text-sm font-medium">Väntande</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['pending_reports'] ?? 0 }}</p>
                </div>
                <div class="text-4xl opacity-75">⏳</div>
            </div>
        </div>

        <!-- Sent Reports -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Skickade</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['sent_reports'] ?? 0 }}</p>
                </div>
                <div class="text-4xl opacity-75">📤</div>
            </div>
        </div>

        <!-- Paid Reports -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Betalda</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ $statistics['paid_reports'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($statistics['total_commission'] ?? 0, 0, ',', ' ') }} kr</p>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">År</label>
                <select name="year" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Alla år</option>
                    @for($year = now()->year; $year >= now()->year - 2; $year--)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Filtrera
                </button>
            </div>
        </form>
    </div>

    <!-- Weekly Reports Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">📅</span>
                Veckorapporter Lista
            </h3>
        </div>

        @if(isset($reports) && $reports->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Företag</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vecka</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bokningar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intäkter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kommission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lojalitetspoäng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Netto Deposit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skickad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reports as $report)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $report->company->company_name }}</div>
                            <div class="text-sm text-gray-500">{{ $report->company->user->name }}</div>
                        </td>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.deposits.show', $report) }}" class="text-blue-600 hover:text-blue-900">Visa</a>
                                @if($report->status === 'pending')
                                    <form method="POST" action="{{ route('admin.deposits.weekly-reports.send', $report) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">Skicka</button>
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
