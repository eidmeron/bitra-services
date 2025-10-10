@extends('layouts.admin')

@section('title', 'Bokningsdetaljer')

@section('content')
<div class="mb-6">
    <a href="{{ route('company.bookings.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till bokningar</a>
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

            <!-- Customer Details -->
            <div class="border-t pt-4 mt-4">
                <h4 class="font-semibold mb-3 flex items-center">
                    <span class="text-xl mr-2">👤</span>
                    Kundinformation
                </h4>
                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded">
                    <div>
                        <label class="text-sm text-gray-600">Namn</label>
                        <p class="font-medium">{{ $booking->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Telefon</label>
                        <p class="font-medium">
                            <a href="tel:{{ $booking->customer_phone }}" class="text-blue-600 hover:underline">
                                📞 {{ $booking->customer_phone }}
                            </a>
                        </p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-sm text-gray-600">E-post</label>
                        <p class="font-medium">
                            <a href="mailto:{{ $booking->customer_email }}" class="text-blue-600 hover:underline">
                                ✉️ {{ $booking->customer_email }}
                            </a>
                        </p>
                    </div>
                </div>

                @if($booking->customer_message)
                    <div class="mt-4">
                        <label class="text-sm text-gray-600">Kundens meddelande</label>
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded mt-2">
                            <p class="text-gray-800">{{ $booking->customer_message }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Form Data -->
            @if(count($booking->form_data) > 0)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-3">📝 Bokningsdetaljer</h4>
                    <div class="bg-gray-50 p-4 rounded space-y-2">
                        @foreach($booking->form_data as $key => $value)
                            <div class="flex justify-between py-2 border-b last:border-b-0">
                                <span class="text-gray-600 font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                <span class="font-semibold">
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

            <!-- Actions Based on Status -->
            @if($booking->status === 'assigned')
                <div class="border-t pt-4 mt-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-yellow-800">
                            <strong>⏳ Åtgärd krävs:</strong> Denna bokning väntar på din bekräftelse. Vänligen acceptera eller avvisa.
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <form method="POST" action="{{ route('company.bookings.accept', $booking) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="btn btn-success w-full text-lg py-3">
                                ✓ Acceptera bokning
                            </button>
                        </form>

                        <form method="POST" action="{{ route('company.bookings.reject', $booking) }}" class="flex-1" onsubmit="return handleReject(event)">
                            @csrf
                            <input type="hidden" name="reason" id="reject_reason">
                            <button type="submit" class="btn btn-danger w-full text-lg py-3">
                                ✕ Avvisa bokning
                            </button>
                        </form>
                    </div>
                </div>
            @elseif($booking->status === 'in_progress')
                <div class="border-t pt-4 mt-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-800">
                            <strong>🔄 Pågående:</strong> Markera som slutförd när arbetet är klart.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('company.bookings.complete', $booking) }}">
                        @csrf
                        <button type="submit" class="btn btn-success w-full text-lg py-3" onclick="return confirm('Är du säker på att arbetet är helt slutfört?')">
                            ✓ Markera som slutförd
                        </button>
                    </form>
                </div>
            @elseif($booking->status === 'completed')
                <div class="border-t pt-4 mt-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-green-800">
                            <strong>✓ Slutförd:</strong> Bra jobbat! Denna bokning är slutförd.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Price -->
        <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
            <h4 class="font-semibold mb-4">💰 Pris</h4>
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-900">{{ number_format($booking->final_price, 2, ',', ' ') }} kr</p>
                @if($booking->rot_deduction > 0)
                    <p class="text-sm text-green-600 mt-2">
                        Inkl. ROT-avdrag: {{ number_format($booking->rot_deduction, 0, ',', ' ') }} kr
                    </p>
                @endif
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <h4 class="font-semibold mb-4">📅 Tidslinje</h4>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 text-sm">✓</div>
                    <div>
                        <p class="font-medium text-sm">Skapad</p>
                        <p class="text-xs text-gray-600">{{ $booking->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @if($booking->assigned_at)
                    <div class="flex items-start">
                        <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 text-sm">✓</div>
                        <div>
                            <p class="font-medium text-sm">Tilldelad</p>
                            <p class="text-xs text-gray-600">{{ $booking->assigned_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                @endif
                @if($booking->completed_at)
                    <div class="flex items-start">
                        <div class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 text-sm">✓</div>
                        <div>
                            <p class="font-medium text-sm">Slutförd</p>
                            <p class="text-xs text-gray-600">{{ $booking->completed_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Review -->
        @if($booking->review)
            <div class="card bg-yellow-50 border border-yellow-200">
                <h4 class="font-semibold mb-3">⭐ Kundrecension</h4>
                <div class="text-center mb-2">
                    {!! reviewStars($booking->review->rating) !!}
                </div>
                @if($booking->review->review_text)
                    <p class="text-sm text-gray-700 italic">"{{ $booking->review->review_text }}"</p>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
function handleReject(event) {
    event.preventDefault();
    
    const reason = prompt('Varför avvisar du denna bokning?\n\nAnge en tydlig anledning (skickas till admin):');
    
    if (reason && reason.trim() !== '') {
        document.getElementById('reject_reason').value = reason;
        event.target.submit();
    } else {
        alert('Du måste ange en anledning för att avvisa bokningen.');
    }
    
    return false;
}
</script>
@endsection
