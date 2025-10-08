@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stats Cards -->
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Totala bokningar</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="text-4xl">📅</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Väntande bokningar</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_bookings'] }}</p>
            </div>
            <div class="text-4xl">⏳</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Aktiva företag</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['active_companies'] }}</p>
            </div>
            <div class="text-4xl">🏢</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total omsättning</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} kr</p>
            </div>
            <div class="text-4xl">💰</div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card mb-8">
    <h3 class="text-xl font-semibold mb-4">Senaste bokningar</h3>
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
                        <td>{{ $booking->customer_name }}</td>
                        <td>{{ $booking->service->name }}</td>
                        <td>{{ $booking->city->name }}</td>
                        <td>{{ number_format($booking->final_price, 2, ',', ' ') }} kr</td>
                        <td>{!! bookingStatusBadge($booking->status) !!}</td>
                        <td>
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:underline">Visa</a>
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

<!-- Pending Companies -->
@if($pendingCompanies->count() > 0)
<div class="card">
    <h3 class="text-xl font-semibold mb-4">Väntande företag</h3>
    <div class="space-y-4">
        @foreach($pendingCompanies as $company)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded">
                <div>
                    <p class="font-medium">{{ $company->user->email }}</p>
                    <p class="text-sm text-gray-600">{{ $company->company_org_number }}</p>
                </div>
                <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-primary btn-sm">
                    Granska
                </a>
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection

