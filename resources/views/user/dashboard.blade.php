@extends('layouts.admin')

@section('title', 'Min sida')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold">Välkommen, {{ auth()->user()->email }}</h2>
    <p class="text-gray-600">Hantera dina bokningar och se din bokningshistorik</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Totala bokningar</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="text-4xl">📋</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Väntande</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_bookings'] }}</p>
            </div>
            <div class="text-4xl">⏳</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Slutförda</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['completed_bookings'] }}</p>
            </div>
            <div class="text-4xl">✓</div>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Totalt spenderat</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_spent'], 0, ',', ' ') }} kr</p>
            </div>
            <div class="text-4xl">💰</div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card mb-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">Senaste bokningar</h3>
        <a href="{{ route('user.bookings.index') }}" class="text-blue-600 hover:underline">
            Se alla &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Bokningsnr</th>
                    <th>Tjänst</th>
                    <th>Stad</th>
                    <th>Företag</th>
                    <th>Pris</th>
                    <th>Status</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                    <tr class="border-t">
                        <td class="font-medium">{{ $booking->booking_number }}</td>
                        <td>{{ $booking->service->name }}</td>
                        <td>{{ $booking->city->name }}</td>
                        <td>
                            @if($booking->company)
                                {{ $booking->company->user->email }}
                            @else
                                <span class="text-gray-400">Ej tilldelad ännu</span>
                            @endif
                        </td>
                        <td class="font-semibold">{{ number_format($booking->final_price, 2, ',', ' ') }} kr</td>
                        <td>{!! bookingStatusBadge($booking->status) !!}</td>
                        <td class="text-sm text-gray-600">{{ $booking->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <p class="text-gray-500 mb-4">Du har inga bokningar ännu</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                Boka en tjänst
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card text-center">
        <div class="text-4xl mb-4">📝</div>
        <h4 class="font-semibold mb-2">Boka ny tjänst</h4>
        <p class="text-sm text-gray-600 mb-4">Hitta och boka tjänster i din stad</p>
        <a href="{{ route('home') }}" class="btn btn-primary w-full">
            Utforska tjänster
        </a>
    </div>

    <div class="card text-center">
        <div class="text-4xl mb-4">📋</div>
        <h4 class="font-semibold mb-2">Mina bokningar</h4>
        <p class="text-sm text-gray-600 mb-4">Hantera och följ dina bokningar</p>
        <a href="{{ route('user.bookings.index') }}" class="btn btn-secondary w-full">
            Se bokningar
        </a>
    </div>

    <div class="card text-center">
        <div class="text-4xl mb-4">⭐</div>
        <h4 class="font-semibold mb-2">Lämna recension</h4>
        <p class="text-sm text-gray-600 mb-4">Dela din upplevelse med andra</p>
        <a href="{{ route('user.bookings.index') }}" class="btn btn-secondary w-full">
            Recensera
        </a>
    </div>
</div>

<!-- ROT-avdrag Info -->
<div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-6">
    <div class="flex items-start">
        <div class="text-3xl mr-4">💰</div>
        <div>
            <h4 class="font-semibold text-green-900 mb-2">ROT-avdrag - Spara pengar!</h4>
            <p class="text-sm text-green-800 mb-2">
                Många av våra tjänster är berättigade till ROT-avdrag. Det innebär att du kan få tillbaka upp till 30% av arbetskostnaden från Skatteverket.
            </p>
            <ul class="text-sm text-green-800 list-disc ml-4">
                <li>Automatisk beräkning vid bokning</li>
                <li>Upp till 30% avdrag på arbetskostnad</li>
                <li>Maxbelopp: 50 000 kr per år</li>
            </ul>
        </div>
    </div>
</div>
@endsection

