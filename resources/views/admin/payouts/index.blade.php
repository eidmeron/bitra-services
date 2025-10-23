@extends('layouts.admin')

@section('title', 'Utbetalningar')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">💰 Utbetalningar</h2>
    <p class="text-gray-600">Hantera företagsutbetalningar och provisioner</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Väntande</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalPending, 0, ',', ' ') }} kr</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">⏳</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Godkända</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalApproved, 0, ',', ' ') }} kr</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">✅</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-400 to-green-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Betalda</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalPaid, 0, ',', ' ') }} kr</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">💰</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-400 to-red-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Avbrutna</p>
                <p class="text-3xl font-bold mt-2">{{ number_format($totalCancelled, 0, ',', ' ') }} kr</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">❌</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-md p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-center">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Alla statusar</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Väntande</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Godkända</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>💰 Betalda</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Avbrutna</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Företag</label>
            <select name="company_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Alla företag</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name ?? $company->user->email }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Från datum</label>
            <input type="date" name="date_from" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date_from') }}">
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Till datum</label>
            <input type="date" name="date_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date_to') }}">
        </div>
        @if(request()->hasAny(['status', 'company_id', 'date_from', 'date_to']))
            <div class="flex items-end">
                <a href="{{ route('admin.payouts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
                    🔄 Rensa filter
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Payouts Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">💰 Utbetalningar</h3>
            <div class="flex gap-2">
                <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" onclick="bulkApprove()">
                    ✅ Godkänn valda
                </button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" onclick="bulkMarkAsPaid()">
                    💰 Markera som betalda
                </button>
            </div>
        </div>
    </div>

    <form id="bulkForm" method="POST">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Företag</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bokning</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Belopp</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Provision</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Utbetalning</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payouts as $payout)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="payout_ids[]" value="{{ $payout->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 payout-checkbox">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $payout->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.companies.show', $payout->company) }}" class="text-blue-600 hover:underline">
                                    {{ $payout->company->company_name ?? $payout->company->user->email }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payout->booking)
                                    <a href="{{ route('admin.bookings.show', $payout->booking) }}" class="text-blue-600 hover:underline">
                                        {{ $payout->booking->booking_number }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($payout->booking_amount, 0, ',', ' ') }} kr</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($payout->commission_amount, 0, ',', ' ') }} kr</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ number_format($payout->payout_amount, 0, ',', ' ') }} kr</td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payout->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.payouts.show', $payout) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs">
                                        👁️ Visa
                                    </a>
                                    @if($payout->status === 'pending')
                                        <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all text-xs" onclick="return confirm('Godkänn denna utbetalning?')">
                                                ✅ Godkänn
                                            </button>
                                        </form>
                                    @endif
                                    @if($payout->status === 'approved')
                                        <form method="POST" action="{{ route('admin.payouts.mark-as-paid', $payout) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs" onclick="return confirm('Markera som betald?')">
                                                💰 Betald
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <span class="text-5xl">💰</span>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900 mb-2">Inga utbetalningar hittades</p>
                                    <p class="text-sm text-gray-500">Utbetalningar kommer att dyka upp här när bokningar slutförs</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    @if($payouts->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $payouts->links() }}
        </div>
    @endif
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.payout-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

function bulkApprove() {
    const form = document.getElementById('bulkForm');
    form.action = '{{ route("admin.payouts.bulk-approve") }}';
    form.method = 'POST';
    
    const checkedBoxes = document.querySelectorAll('.payout-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Välj minst en utbetalning att godkänna.');
        return;
    }
    
    if (confirm(`Godkänn ${checkedBoxes.length} utbetalningar?`)) {
        form.submit();
    }
}

function bulkMarkAsPaid() {
    const form = document.getElementById('bulkForm');
    form.action = '{{ route("admin.payouts.bulk-mark-as-paid") }}';
    form.method = 'POST';
    
    const checkedBoxes = document.querySelectorAll('.payout-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Välj minst en utbetalning att markera som betald.');
        return;
    }
    
    if (confirm(`Markera ${checkedBoxes.length} utbetalningar som betalda?`)) {
        form.submit();
    }
}
</script>
@endsection