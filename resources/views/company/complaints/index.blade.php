@extends('layouts.company')

@section('title', 'Reklamationer')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                <span class="text-4xl mr-3">📝</span>
                Reklamationer
            </h2>
            <p class="text-gray-600 mt-2">Hantera alla reklamationer från kunder</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('company.dashboard') }}" class="flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all">
                <span class="mr-2">←</span>
                Tillbaka till Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
    <div class="flex items-center mb-4">
        <span class="text-xl mr-2">🔍</span>
        <h3 class="text-lg font-semibold text-gray-900">Filtrera reklamationer</h3>
    </div>
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sök</label>
            <input
                type="text"
                name="search"
                placeholder="Sök på ämne eller beskrivning..."
                value="{{ request('search') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                <option value="">Alla statusar</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Öppen</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Pågående</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Löst</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Stängd</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Prioritet</label>
            <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                <option value="">Alla prioriteringar</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Låg</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Hög</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Akut</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all">
                <span class="mr-2">🔍</span>
                Sök
            </button>
        </div>
    </form>
</div>

<!-- Complaints Table -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <span class="text-xl mr-2">📋</span>
            Alla Reklamationer ({{ $complaints->total() }})
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reklamation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kund</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tjänst</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meddelanden</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skapad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($complaints as $complaint)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $complaint->subject }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($complaint->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($complaint->user)
                                <div class="text-sm font-medium text-gray-900">{{ $complaint->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $complaint->user->email }}</div>
                            @else
                                <div class="text-sm font-medium text-gray-900">{{ $complaint->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $complaint->customer_email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $complaint->booking->service->name }}</div>
                            <div class="text-sm text-gray-500">{{ $complaint->booking->city->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'urgent' => 'bg-red-100 text-red-800',
                                ];
                                $priorityLabels = [
                                    'low' => 'Låg',
                                    'medium' => 'Medium',
                                    'high' => 'Hög',
                                    'urgent' => 'Akut',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$complaint->priority] }}">
                                {{ $priorityLabels[$complaint->priority] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'open' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'closed' => 'bg-gray-100 text-gray-800',
                                ];
                                $statusLabels = [
                                    'open' => 'Öppen',
                                    'in_progress' => 'Pågående',
                                    'resolved' => 'Löst',
                                    'closed' => 'Stängd',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$complaint->status] }}">
                                {{ $statusLabels[$complaint->status] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $complaint->messages->count() }} meddelanden
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $complaint->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('company.complaints.show', $complaint) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-all">
                                    <span class="mr-1">👁️</span>
                                    Visa
                                </a>
                                @if($complaint->status === 'open')
                                    <form action="{{ route('company.complaints.update-status', $complaint) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="in_progress">
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-semibold rounded-lg transition-all">
                                            <span class="mr-1">🔄</span>
                                            Starta
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <div class="text-4xl mb-4">📝</div>
                                <p class="text-lg font-medium">Inga reklamationer hittades</p>
                                <p class="text-sm">Prova att ändra dina sökfilter</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200">
        {{ $complaints->links() }}
    </div>
</div>
@endsection
