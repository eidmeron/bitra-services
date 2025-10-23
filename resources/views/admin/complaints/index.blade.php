@extends('layouts.admin')

@section('title', 'Reklamationer')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">📝 Reklamationer</h2>
    <p class="text-gray-600">Hantera kundreklamationer och supportärenden</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    @php
        $openCount = $complaints->where('status', 'open')->count();
        $inProgressCount = $complaints->where('status', 'in_progress')->count();
        $resolvedCount = $complaints->where('status', 'resolved')->count();
        $closedCount = $complaints->where('status', 'closed')->count();
    @endphp

    <div class="bg-gradient-to-br from-red-400 to-red-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Öppna</p>
                <p class="text-3xl font-bold mt-2">{{ $openCount }}</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">🔴</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Pågående</p>
                <p class="text-3xl font-bold mt-2">{{ $inProgressCount }}</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">🟡</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-400 to-green-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Lösta</p>
                <p class="text-3xl font-bold mt-2">{{ $resolvedCount }}</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">🟢</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-100 text-sm font-medium">Stängda</p>
                <p class="text-3xl font-bold mt-2">{{ $closedCount }}</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">⚫</span>
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
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>🔴 Öppen</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>🟡 Pågående</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>🟢 Löst</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>⚫ Stängd</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Prioritet</label>
            <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">Alla prioriteringar</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>🟢 Låg</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>🟠 Hög</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>🔴 Akut</option>
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
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Sök</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Sök efter nummer, ämne, kund..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        @if(request()->hasAny(['status', 'priority', 'company_id', 'search']))
            <div class="flex items-end">
                <a href="{{ route('admin.complaints.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
                    🔄 Rensa filter
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Complaints Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Reklamationsnr</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kund</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Företag</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ämne</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Prioritet</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Skapad</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($complaints as $complaint)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-900">{{ $complaint->complaint_number }}</div>
                            <div class="text-xs text-gray-500">{{ $complaint->booking->booking_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $complaint->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $complaint->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $complaint->company->company_name ?? $complaint->company->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($complaint->subject, 50) }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($complaint->description, 80) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {!! $complaint->priority_badge !!}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {!! $complaint->status_badge !!}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $complaint->created_at->format('Y-m-d') }}</div>
                            <div class="text-xs text-gray-500">{{ $complaint->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.complaints.show', $complaint) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all font-medium shadow-sm">
                                👁️ Visa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <span class="text-5xl">📭</span>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mb-2">Inga reklamationer hittades</p>
                                <p class="text-sm text-gray-500">Reklamationer kommer att dyka upp här när kunder skapar dem</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($complaints->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $complaints->links() }}
        </div>
    @endif
</div>
@endsection

