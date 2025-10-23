@extends('layouts.admin')

@section('title', 'Provisionsinställningar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">💰 Provisionsinställningar</h2>
                <p class="text-gray-600 mt-1">Hantera provisioner för alla företag</p>
            </div>
            <a href="{{ route('admin.commissions.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ny Provision
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    @php
        $totalCommissions = $commissions->sum(function($commission) {
            return $commission->company->payouts->sum('commission_amount');
        });
        $activeCommissions = $commissions->where('is_active', true)->count();
        $avgCommissionRate = $commissions->where('commission_type', 'percentage')->avg('commission_rate');
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-purple-100 text-sm font-medium uppercase">Aktiva Provisioner</p>
            <p class="text-3xl font-bold mt-2">{{ $activeCommissions }} / {{ $commissions->count() }}</p>
            <p class="text-xs text-purple-100 mt-1">företag</p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-green-100 text-sm font-medium uppercase">Total Provision</p>
            <p class="text-3xl font-bold mt-2">{{ number_format($totalCommissions, 0, ',', ' ') }} kr</p>
            <p class="text-xs text-green-100 mt-1">alla tider</p>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-blue-100 text-sm font-medium uppercase">Genomsnittlig Procent</p>
            <p class="text-3xl font-bold mt-2">{{ number_format($avgCommissionRate, 1) }}%</p>
            <p class="text-xs text-blue-100 mt-1">av procent-baserade</p>
        </div>
    </div>

    <!-- Commissions Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-purple-50 to-pink-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Företag</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Typ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Provision</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total Provision</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Skapad</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Åtgärd</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($commissions as $commission)
                    @php
                        $totalCommissionAmount = $commission->company->payouts->sum('commission_amount');
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if($commission->company->logo)
                                        <img src="{{ Storage::url($commission->company->logo) }}" alt="{{ $commission->company->company_name }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                                            {{ substr($commission->company->company_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('admin.companies.show', $commission->company) }}" class="text-sm font-medium text-gray-900 hover:text-purple-600">
                                        {{ $commission->company->company_name }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $commission->company->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($commission->commission_type === 'percentage')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    📊 Procent
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    💵 Fast
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($commission->commission_type === 'percentage')
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($commission->commission_rate, 2) }}%</span>
                            @else
                                <span class="text-sm font-semibold text-gray-900">{{ number_format($commission->fixed_amount, 2) }} kr</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-green-600">{{ number_format($totalCommissionAmount, 0, ',', ' ') }} kr</span>
                            <div class="text-xs text-gray-500">{{ $commission->company->payouts->count() }} utbetalningar</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($commission->is_active)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ✓ Aktiv
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    ✗ Inaktiv
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $commission->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.commissions.edit', $commission) }}" class="text-purple-600 hover:text-purple-900 mr-3">
                                Redigera
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-6xl mb-4">💰</div>
                            <p class="text-lg font-semibold text-gray-600">Inga provisionsinställningar ännu</p>
                            <p class="text-sm text-gray-500 mt-2">Klicka på "Ny Provision" för att skapa en</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($commissions->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
