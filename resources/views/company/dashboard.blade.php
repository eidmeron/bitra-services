@extends('layouts.admin')

@section('title', 'Företagspanel')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold">Välkommen, {{ $company->user->email }}</h2>
    <p class="text-gray-600">Org.nr: {{ $company->company_org_number }}</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Tilldelade bokningar</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['assigned_bookings'] }}</p>
            </div>
            <div class="text-4xl">📋</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Pågående</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['in_progress'] }}</p>
            </div>
            <div class="text-4xl">🔄</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Slutförda idag</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['completed_today'] }}</p>
            </div>
            <div class="text-4xl">✓</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Genomsnittligt betyg</p>
                <p class="text-3xl font-bold text-yellow-600">{{ number_format($stats['average_rating'], 1) }}</p>
            </div>
            <div class="text-4xl">⭐</div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">Senaste bokningar</h3>
        <a href="{{ route('company.bookings.index') }}" class="text-blue-600 hover:underline">
            Se alla &rarr;
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Bokningsnr</th>
                    <th>Kund</th>
                    <th>Tjänst</th>
                    <th>Stad</th>
                    <th>Pris</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                    <tr class="border-t">
                        <td>{{ $booking->booking_number }}</td>
                        <td>
                            <div>{{ $booking->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->customer_phone }}</div>
                        </td>
                        <td>{{ $booking->service->name }}</td>
                        <td>{{ $booking->city->name }}</td>
                        <td class="font-semibold">{{ number_format($booking->final_price, 2, ',', ' ') }} kr</td>
                        <td>{!! bookingStatusBadge($booking->status) !!}</td>
                        <td>
                            <a href="{{ route('company.bookings.show', $booking) }}" class="text-blue-600 hover:underline">
                                Visa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Inga bokningar ännu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

