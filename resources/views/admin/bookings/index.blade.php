@extends('layouts.admin')

@section('title', 'Bokningar')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Alla bokningar</h2>
    
    <!-- Filters -->
    <div class="flex space-x-4">
        <form method="GET" class="flex space-x-2">
            <select name="status" class="form-input" onchange="this.form.submit()">
                <option value="">Alla statusar</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Väntande</option>
                <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Tilldelad</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Pågående</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Slutförd</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Avbruten</option>
            </select>
            
            <input 
                type="text" 
                name="search" 
                placeholder="Sök bokningsnummer, kund..." 
                value="{{ request('search') }}"
                class="form-input"
            >
            <button type="submit" class="btn btn-primary">Sök</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Bokningsnr</th>
                    <th>Kund</th>
                    <th>Tjänst</th>
                    <th>Stad</th>
                    <th>Företag</th>
                    <th>Pris</th>
                    <th>Status</th>
                    <th>Datum</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="font-medium">{{ $booking->booking_number }}</td>
                        <td>
                            <div>{{ $booking->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->customer_email }}</div>
                        </td>
                        <td>{{ $booking->service->name }}</td>
                        <td>{{ $booking->city->name }}</td>
                        <td>
                            @if($booking->company)
                                {{ $booking->company->user->email }}
                            @else
                                <span class="text-gray-400">Ej tilldelad</span>
                            @endif
                        </td>
                        <td class="font-semibold">{{ number_format($booking->final_price, 2, ',', ' ') }} kr</td>
                        <td>{!! bookingStatusBadge($booking->status) !!}</td>
                        <td class="text-sm text-gray-600">{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:underline">
                                Visa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-8 text-gray-500">
                            Inga bokningar hittades
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
@endsection

