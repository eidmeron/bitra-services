@extends('layouts.admin')

@section('title', 'Mina bokningar')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold">Tilldelade bokningar</h2>
    <p class="text-gray-600">Hantera bokningar som tilldelats ditt företag</p>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" class="flex space-x-4">
        <select name="status" class="form-input" onchange="this.form.submit()">
            <option value="">Alla statusar</option>
            <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Tilldelad</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Pågående</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Slutförd</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Avvisad</option>
        </select>
    </form>
</div>

<!-- Bookings Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Bokningsnr</th>
                    <th>Kund</th>
                    <th>Tjänst</th>
                    <th>Stad</th>
                    <th>Pris</th>
                    <th>Önskat datum</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="font-medium">{{ $booking->booking_number }}</td>
                        <td>
                            <div>{{ $booking->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->customer_phone }}</div>
                        </td>
                        <td>
                            <div>{{ $booking->service->name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->service->category->name }}</div>
                        </td>
                        <td>{{ $booking->city->name }}</td>
                        <td class="font-semibold">{{ number_format($booking->final_price, 2, ',', ' ') }} kr</td>
                        <td class="text-sm">
                            @if($booking->preferred_date)
                                {{ $booking->preferred_date->format('Y-m-d') }}
                            @else
                                <span class="text-gray-400">Ej angivet</span>
                            @endif
                        </td>
                        <td>{!! bookingStatusBadge($booking->status) !!}</td>
                        <td>
                            <a href="{{ route('company.bookings.show', $booking) }}" class="text-blue-600 hover:underline">
                                Visa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <p class="text-gray-500 mb-2">Inga bokningar tilldelade ännu</p>
                            <p class="text-sm text-gray-400">Bokningar kommer att dyka upp här när admin tilldelar dem till ditt företag</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
</div>

<!-- Help -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <h4 class="font-semibold text-blue-900 mb-2">💡 Bokningsflöde</h4>
    <ol class="text-sm text-blue-800 list-decimal ml-4 space-y-1">
        <li><strong>Tilldelad</strong> - Bokning har tilldelats till ditt företag, acceptera eller avvisa</li>
        <li><strong>Pågående</strong> - Du har accepterat och arbetar med bokningen</li>
        <li><strong>Slutförd</strong> - Markera som slutförd när arbetet är klart</li>
    </ol>
</div>
@endsection

