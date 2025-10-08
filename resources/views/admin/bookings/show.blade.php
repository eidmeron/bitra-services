@extends('layouts.admin')

@section('title', 'Bokningsdetaljer')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till bokningar</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Booking Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold">{{ $booking->booking_number }}</h3>
                    <p class="text-gray-600">Skapad {{ $booking->created_at->format('Y-m-d H:i') }}</p>
                </div>
                {!! bookingStatusBadge($booking->status) !!}
            </div>

            <div class="border-t pt-4">
                <h4 class="font-semibold mb-2">Tjänst</h4>
                <p class="text-lg">{{ $booking->service->name }}</p>
                <p class="text-sm text-gray-600">{{ $booking->city->name }}</p>
            </div>

            <div class="border-t pt-4 mt-4">
                <h4 class="font-semibold mb-2">Kunduppgifter</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Namn</label>
                        <p>{{ $booking->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">E-post</label>
                        <p>{{ $booking->customer_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Telefon</label>
                        <p>{{ $booking->customer_phone }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Bokningstyp</label>
                        <p>{{ $booking->booking_type === 'one_time' ? 'En gång' : 'Prenumeration' }}</p>
                    </div>
                </div>

                @if($booking->customer_message)
                    <div class="mt-4">
                        <label class="text-sm text-gray-600">Meddelande</label>
                        <p class="bg-gray-50 p-3 rounded mt-1">{{ $booking->customer_message }}</p>
                    </div>
                @endif
            </div>

            @if(count($booking->form_data) > 0)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-2">Formulärdata</h4>
                    <div class="bg-gray-50 p-4 rounded">
                        @foreach($booking->form_data as $key => $value)
                            <div class="flex justify-between py-2 border-b last:border-b-0">
                                <span class="text-gray-600">{{ $key }}</span>
                                <span class="font-medium">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Assign to Company -->
        @if($booking->status === 'pending' && $availableCompanies->count() > 0)
            <div class="card bg-yellow-50">
                <h4 class="font-semibold mb-4">Tilldela till företag</h4>
                <form method="POST" action="{{ route('admin.bookings.assign', $booking) }}">
                    @csrf
                    <div class="mb-4">
                        <select name="company_id" class="form-input" required>
                            <option value="">Välj företag...</option>
                            @foreach($availableCompanies as $company)
                                <option value="{{ $company->id }}">
                                    {{ $company->user->email }} - ⭐ {{ number_format($company->review_average, 1) }} ({{ $company->review_count }} recensioner)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tilldela bokning</button>
                </form>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Price Breakdown -->
        <div class="card bg-blue-50">
            <h4 class="font-semibold mb-4">Prisberäkning</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Grundpris:</span>
                    <span>{{ number_format($booking->base_price, 2, ',', ' ') }} kr</span>
                </div>
                @if($booking->variable_additions > 0)
                    <div class="flex justify-between">
                        <span>Tillägg:</span>
                        <span>{{ number_format($booking->variable_additions, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Stadsmultiplikator:</span>
                    <span>×{{ $booking->city_multiplier }}</span>
                </div>
                @if($booking->rot_deduction > 0)
                    <div class="flex justify-between text-green-600">
                        <span>ROT-avdrag:</span>
                        <span>-{{ number_format($booking->rot_deduction, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                @if($booking->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Rabatt:</span>
                        <span>-{{ number_format($booking->discount_amount, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                <div class="border-t pt-2 mt-2 flex justify-between font-bold text-lg">
                    <span>Totalt:</span>
                    <span>{{ number_format($booking->final_price, 2, ',', ' ') }} kr</span>
                </div>
            </div>
        </div>

        <!-- Company Info -->
        @if($booking->company)
            <div class="card">
                <h4 class="font-semibold mb-2">Tilldelat företag</h4>
                <p class="font-medium">{{ $booking->company->user->email }}</p>
                <p class="text-sm text-gray-600">Org.nr: {{ $booking->company->company_org_number }}</p>
                @if($booking->company->review_average > 0)
                    <p class="text-sm mt-2">
                        {!! reviewStars((int)round($booking->company->review_average)) !!}
                        <span class="text-gray-600">({{ $booking->company->review_count }} recensioner)</span>
                    </p>
                @endif
            </div>
        @endif

        <!-- Timeline -->
        <div class="card">
            <h4 class="font-semibold mb-4">Tidslinje</h4>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">✓</div>
                    <div>
                        <p class="font-medium">Bokning skapad</p>
                        <p class="text-sm text-gray-600">{{ $booking->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @if($booking->assigned_at)
                    <div class="flex items-start">
                        <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">✓</div>
                        <div>
                            <p class="font-medium">Tilldelad till företag</p>
                            <p class="text-sm text-gray-600">{{ $booking->assigned_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                @endif
                @if($booking->completed_at)
                    <div class="flex items-start">
                        <div class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">✓</div>
                        <div>
                            <p class="font-medium">Slutförd</p>
                            <p class="text-sm text-gray-600">{{ $booking->completed_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <h4 class="font-semibold mb-4">Åtgärder</h4>
            <div class="space-y-2">
                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Är du säker på att du vill radera denna bokning?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full btn btn-danger">
                        Radera bokning
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

