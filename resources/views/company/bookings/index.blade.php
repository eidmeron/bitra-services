@extends('layouts.company')

@section('title', 'Mina bokningar')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">📋 Tilldelade Bokningar</h2>
    <p class="text-gray-600">Hantera bokningar som tilldelats ditt företag</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    @php
        $assignedCount = $bookings->where('status', 'assigned')->count();
        $inProgressCount = $bookings->where('status', 'in_progress')->count();
        $completedCount = $bookings->where('status', 'completed')->count();
        $rejectedCount = $bookings->where('status', 'rejected')->count();
    @endphp

    <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Tilldelade</p>
                <p class="text-3xl font-bold mt-2">{{ $assignedCount }}</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">⏳</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Pågående</p>
                <p class="text-3xl font-bold mt-2">{{ $inProgressCount }}</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">🔄</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-teal-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Slutförda</p>
                <p class="text-3xl font-bold mt-2">{{ $completedCount }}</p>
            </div>
            <div class="bg-white/20 rounded-full p-3">
                <span class="text-4xl">✅</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Avvisade</p>
                <p class="text-3xl font-bold mt-2">{{ $rejectedCount }}</p>
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
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="this.form.submit()">
                <option value="">Alla statusar</option>
                <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>⏳ Tilldelad</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>🔄 Pågående</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Slutförd</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Avvisad</option>
            </select>
        </div>
        @if(request('status'))
            <div class="flex items-end">
                <a href="{{ route('company.bookings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
                    🔄 Rensa filter
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-green-50 to-blue-50 border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bokningsnr</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kund</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tjänst</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stad</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pris</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Önskat datum</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-900">{{ $booking->booking_number }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->created_at->format('Y-m-d') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->customer_phone }}</div>
                            <div class="mt-1">
                                @if($booking->customer_type === 'company')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        🏢 Företag
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        🏠 Privat
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-xl">{{ $booking->service->icon ?? '🛠️' }}</span>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->service->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->service->category->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $booking->city->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ number_format($booking->total_with_tax ?? $booking->final_price, 0, ',', ' ') }} kr</div>
                            @if($booking->tax_amount > 0)
                                <div class="text-xs text-gray-500">inkl. moms</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($booking->preferred_date)
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->preferred_date)->format('Y-m-d') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->preferred_date)->format('H:i') }}</div>
                            @else
                                <span class="text-sm text-gray-400">Ej angivet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'assigned' => 'bg-yellow-100 text-yellow-800',
                                    'in_progress' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                                $statusIcons = [
                                    'assigned' => '⏳',
                                    'in_progress' => '🔄',
                                    'completed' => '✅',
                                    'rejected' => '❌',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                <span class="mr-1">{{ $statusIcons[$booking->status] ?? '⚪' }}</span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('company.bookings.show', $booking) }}" class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg hover:from-green-700 hover:to-blue-700 transition-all font-medium shadow-sm">
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
                                <p class="text-lg font-semibold text-gray-900 mb-2">Inga bokningar tilldelade ännu</p>
                                <p class="text-sm text-gray-500">Bokningar kommer att dyka upp här när admin tilldelar dem till ditt företag</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

<!-- Help -->
<div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6 shadow-md">
    <h4 class="font-bold text-blue-900 mb-3 flex items-center text-lg">
        <span class="text-2xl mr-2">💡</span>
        Bokningsflöde
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-4 border-l-4 border-yellow-500">
            <div class="flex items-center mb-2">
                <span class="text-2xl mr-2">⏳</span>
                <span class="font-semibold text-gray-900">Tilldelad</span>
            </div>
            <p class="text-sm text-gray-600">Bokning har tilldelats till ditt företag. Acceptera eller avvisa den.</p>
        </div>
        <div class="bg-white rounded-lg p-4 border-l-4 border-purple-500">
            <div class="flex items-center mb-2">
                <span class="text-2xl mr-2">🔄</span>
                <span class="font-semibold text-gray-900">Pågående</span>
            </div>
            <p class="text-sm text-gray-600">Du har accepterat och arbetar med bokningen.</p>
        </div>
        <div class="bg-white rounded-lg p-4 border-l-4 border-green-500">
            <div class="flex items-center mb-2">
                <span class="text-2xl mr-2">✅</span>
                <span class="font-semibold text-gray-900">Slutförd</span>
            </div>
            <p class="text-sm text-gray-600">Markera som slutförd när arbetet är klart.</p>
        </div>
    </div>
</div>
@endsection
