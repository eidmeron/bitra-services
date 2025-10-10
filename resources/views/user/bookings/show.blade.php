@extends('layouts.admin')

@section('title', 'Bokningsdetaljer')

@section('content')
<div class="mb-6">
    <a href="{{ route('user.bookings.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till mina bokningar</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold">{{ $booking->booking_number }}</h3>
                    <p class="text-gray-600">{{ $booking->service->name }}</p>
                </div>
                {!! bookingStatusBadge($booking->status) !!}
            </div>

            <!-- Service Details -->
            <div class="border-t pt-4 mt-4">
                <h4 class="font-semibold mb-3">Tjänstedetaljer</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Tjänst</label>
                        <p class="font-medium">{{ $booking->service->name }}</p>
                        <p class="text-xs text-gray-500">{{ $booking->service->category->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Stad</label>
                        <p class="font-medium">{{ $booking->city->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Bokningstyp</label>
                        <p class="font-medium">
                            @if($booking->booking_type === 'one_time')
                                En gång
                            @else
                                Prenumeration ({{ getSubscriptionFrequencyLabel($booking->subscription_frequency) }})
                            @endif
                        </p>
                    </div>
                    @if($booking->preferred_date)
                        <div>
                            <label class="text-sm text-gray-600">Önskat datum</label>
                            <p class="font-medium">{{ $booking->preferred_date->format('Y-m-d') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Form Data -->
            @if(count($booking->form_data) > 0)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-3">Dina uppgifter</h4>
                    <div class="bg-gray-50 p-4 rounded">
                        @foreach($booking->form_data as $key => $value)
                            <div class="flex justify-between py-2 border-b last:border-b-0">
                                <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                <span class="font-medium">
                                    @if(is_array($value))
                                        {{ implode(', ', $value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Company Info -->
            @if($booking->company)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-3">Tilldelat företag</h4>
                    <div class="bg-blue-50 p-4 rounded">
                        <p class="font-medium text-lg">{{ $booking->company->user->email }}</p>
                        @if($booking->company->company_number)
                            <p class="text-sm text-gray-600">📞 {{ $booking->company->company_number }}</p>
                        @endif
                        @if($booking->company->site)
                            <p class="text-sm">
                                <a href="{{ $booking->company->site }}" target="_blank" class="text-blue-600 hover:underline">
                                    🌐 {{ $booking->company->site }}
                                </a>
                            </p>
                        @endif
                        @if($booking->company->review_average > 0)
                            <div class="mt-2">
                                {!! reviewStars((int)round($booking->company->review_average)) !!}
                                <span class="text-sm text-gray-600 ml-1">({{ $booking->company->review_count }} recensioner)</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="border-t pt-4 mt-4">
                    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded">
                        <p class="text-sm text-yellow-800">
                            <strong>⏳ Väntar på tilldelning:</strong> Din bokning granskas av admin och kommer snart att tilldelas ett lämpligt företag.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Review Section -->
            @if($booking->canBeReviewed())
                <div class="border-t pt-4 mt-4" id="review">
                    <h4 class="font-semibold mb-3">⭐ Lämna recension</h4>
                    <div class="bg-green-50 border border-green-200 p-4 rounded mb-4">
                        <p class="text-sm text-green-800">
                            Tjänsten är slutförd! Dela din upplevelse med andra kunder.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.bookings.review', $booking) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Betyg *</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" required class="hidden peer">
                                        <span class="text-3xl peer-checked:text-yellow-400 text-gray-300 hover:text-yellow-400">★</span>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Din recension</label>
                            <textarea name="review_text" rows="4" class="form-input" placeholder="Berätta om din upplevelse..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Skicka recension
                        </button>
                    </form>
                </div>
            @elseif($booking->review)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-3">Din recension</h4>
                    <div class="bg-gray-50 p-4 rounded">
                        <div class="text-center mb-2">
                            {!! reviewStars($booking->review->rating) !!}
                        </div>
                        @if($booking->review->review_text)
                            <p class="text-sm text-gray-700 italic text-center">"{{ $booking->review->review_text }}"</p>
                        @endif
                        <p class="text-xs text-gray-500 text-center mt-2">
                            Status: 
                            @if($booking->review->status === 'approved')
                                <span class="text-green-600">Godkänd</span>
                            @elseif($booking->review->status === 'pending')
                                <span class="text-yellow-600">Väntar på godkännande</span>
                            @else
                                <span class="text-red-600">Avvisad</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <!-- Cancel Option -->
            @if(in_array($booking->status, ['pending', 'assigned']))
                <div class="border-t pt-4 mt-4">
                    <form method="POST" action="{{ route('user.bookings.cancel', $booking) }}" onsubmit="return confirm('Är du säker på att du vill avbryta denna bokning?')">
                        @csrf
                        <button type="submit" class="btn btn-danger w-full">
                            Avbryt bokning
                        </button>
                    </form>
                </div>
            @endif
        </div>
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

        <!-- Status Info -->
        <div class="card">
            <h4 class="font-semibold mb-3">Status</h4>
            @if($booking->status === 'pending')
                <p class="text-sm text-gray-700">Din bokning väntar på att granskas av admin.</p>
            @elseif($booking->status === 'assigned')
                <p class="text-sm text-gray-700">Ett företag har tilldelats din bokning och kommer att kontakta dig snart.</p>
            @elseif($booking->status === 'in_progress')
                <p class="text-sm text-gray-700">Företaget arbetar med din bokning.</p>
            @elseif($booking->status === 'completed')
                <p class="text-sm text-green-700">✓ Tjänsten är slutförd!</p>
            @elseif($booking->status === 'cancelled')
                <p class="text-sm text-red-700">Denna bokning har avbrutits.</p>
            @endif
        </div>

        <!-- Support -->
        <div class="card bg-gray-50">
            <h4 class="font-semibold mb-3">Behöver du hjälp?</h4>
            <p class="text-sm text-gray-700 mb-3">Kontakta oss om du har frågor om din bokning.</p>
            <div class="space-y-2 text-sm">
                <a href="mailto:support@bitratjanster.se" class="text-blue-600 hover:underline block">
                    📧 support@bitratjanster.se
                </a>
                <a href="tel:+46123456789" class="text-blue-600 hover:underline block">
                    📞 012-345 67 89
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function handleReject(event) {
    event.preventDefault();
    const reason = prompt('Varför avvisar du denna bokning?');
    if (reason && reason.trim() !== '') {
        document.getElementById('reject_reason').value = reason;
        event.target.submit();
    }
    return false;
}
</script>
@endsection

