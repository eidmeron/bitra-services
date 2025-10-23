@extends('layouts.user')

@section('title', 'Mina bokningar')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Mina bokningar</h2>
    <a href="{{ route('welcome') }}" class="btn btn-primary">
        + Ny bokning
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" class="flex space-x-4">
        <select name="status" class="form-input" onchange="this.form.submit()">
            <option value="">Alla statusar</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Väntande</option>
            <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Tilldelad</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Pågående</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Slutförd</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Avbruten</option>
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
                    <th>Tjänst</th>
                    <th>Stad</th>
                    <th>Kundtyp</th>
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
                            <div>{{ $booking->service->name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->service->category->name }}</div>
                        </td>
                        <td>{{ $booking->city->name }}</td>
                        <td>
                            @if($booking->customer_type === 'company')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    🏢 Företag
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    🏠 Privat
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($booking->company)
                                <div>{{ $booking->company->user->email }}</div>
                                @if($booking->company->review_average > 0)
                                    <div class="text-xs">
                                        {!! reviewStars((int)round($booking->company->review_average)) !!}
                                    </div>
                                @endif
                            @else
                                <span class="text-gray-400">Väntar på tilldelning</span>
                            @endif
                        </td>
                        <td>
                            <div class="font-semibold">{{ number_format($booking->total_with_tax ?? $booking->final_price, 2, ',', ' ') }} kr</div>
                            @if($booking->tax_amount > 0)
                                <div class="text-xs text-gray-500">inkl. moms</div>
                            @endif
                            @if($booking->rot_deduction > 0)
                                <div class="text-xs text-green-600">ROT: -{{ number_format($booking->rot_deduction, 0) }} kr</div>
                            @endif
                        </td>
                        <td>{!! bookingStatusBadge($booking->status) !!}</td>
                        <td class="text-sm text-gray-600">{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('user.bookings.show', $booking) }}" class="text-blue-600 hover:underline text-sm">
                                    Visa
                                </a>
                                @if($booking->canBeReviewed())
                                    <a href="{{ route('user.bookings.show', $booking) }}#review" class="text-green-600 hover:underline text-sm">
                                        Recensera
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-12">
                            <p class="text-gray-500 mb-4">Du har inga bokningar ännu</p>
                            <a href="{{ route('welcome') }}" class="btn btn-primary">
                                Boka din första tjänst
                            </a>
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

<!-- Help Section -->
@if($bookings->count() > 0)
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h4 class="font-semibold text-blue-900 mb-2">Behöver du hjälp?</h4>
    <p class="text-sm text-blue-800 mb-4">
        Kontakta oss om du har frågor om din bokning eller om något inte stämmer.
    </p>
    <div class="flex space-x-4">
        <a href="mailto:support@bitratjanster.se" class="text-sm text-blue-600 hover:underline">
            📧 support@bitratjanster.se
        </a>
        <a href="tel:+46123456789" class="text-sm text-blue-600 hover:underline">
            📞 012-345 67 89
        </a>
    </div>
</div>
@endif
@endsection

