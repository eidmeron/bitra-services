

@extends('layouts.company')

@section('title', 'Bokningsdetaljer')

@section('content')
<div class="mb-6">
    <a href="{{ route('company.bookings.index') }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Tillbaka till bokningar
    </a>
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
                        <label class="text-sm text-gray-600">Kundtyp</label>
                        <p class="font-medium">
                            @if($booking->customer_type === 'company')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    🏢 Företag
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    🏠 Privatperson
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Telefon</label>
                        <p class="font-medium">
                            <a href="tel:{{ $booking->customer_phone }}" class="text-blue-600 hover:underline">
                                📞 {{ $booking->customer_phone }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">E-post</label>
                        <p class="font-medium">
                            <a href="mailto:{{ $booking->customer_email }}" class="text-blue-600 hover:underline">
                                ✉️ {{ $booking->customer_email }}
                            </a>
                        </p>
                    </div>
                    @if($booking->customer_type === 'company' && $booking->org_number)
                        <div class="col-span-2">
                            <label class="text-sm text-gray-600">Organisationsnummer</label>
                            <p class="font-mono font-medium">{{ $booking->org_number }}</p>
                        </div>
                    @endif
                    @if($booking->customer_type === 'private' && $booking->personnummer)
                        <div class="col-span-2">
                            <label class="text-sm text-gray-600">Personnummer (ROT-avdrag)</label>
                            <p class="font-mono font-medium">{{ $booking->personnummer }}</p>
                        </div>
                    @endif
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
            @if($booking->form_data && is_array($booking->form_data) && count($booking->form_data) > 0)
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

            <!-- Chat Section -->
            @if(in_array($booking->status, ['assigned', 'in_progress']))
                <div class="border-t pt-4 mt-4" id="chat">
                    <h4 class="font-semibold mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chatta med {{ $booking->customer_name }}
                    </h4>
                    
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 border border-green-200 rounded-lg p-6">
                        <!-- Chat Messages -->
                        <div class="bg-white rounded-lg p-4 mb-4 max-h-96 overflow-y-auto" id="chatMessages">
                            @forelse($booking->chats()->orderBy('created_at', 'asc')->get() as $chat)
                                <div class="mb-4 {{ $chat->sender_type === 'company' ? 'text-right' : 'text-left' }}">
                                    <div class="inline-block max-w-xs lg:max-w-md">
                                        <div class="text-xs text-gray-500 mb-1">
                                            {{ $chat->sender_type === 'company' ? 'Du' : $booking->customer_name }}
                                            <span class="ml-1">{{ $chat->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="rounded-lg px-4 py-2 {{ $chat->sender_type === 'company' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                                            {{ $chat->message }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <p class="text-sm">Inga meddelanden ännu. Starta konversationen!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Send Message Form -->
                        <form method="POST" action="{{ route('company.bookings.chat.send', $booking) }}" class="flex space-x-2">
                            @csrf
                            <input 
                                type="text" 
                                name="message" 
                                class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                placeholder="Skriv ditt meddelande..."
                                required
                            >
                            <button 
                                type="submit" 
                                class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition flex items-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Skicka
                            </button>
                        </form>
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
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Delsumma:</span>
                    <span>{{ number_format($booking->subtotal ?? ($booking->final_price - ($booking->tax_amount ?? 0)), 2, ',', ' ') }} kr</span>
                </div>
                <div class="flex justify-between">
                    <span>Moms ({{ number_format($booking->tax_rate ?? ($booking->service->tax_rate ?? 25), 2, ',', ' ') }}%):</span>
                    <span>{{ number_format($booking->tax_amount ?? 0, 2, ',', ' ') }} kr</span>
                </div>
                @if($booking->rot_deduction > 0)
                    <div class="flex justify-between text-green-600">
                        <span>ROT-avdrag:</span>
                        <span>-{{ number_format($booking->rot_deduction, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                @if($booking->total_extra_fees > 0)
                    <div class="flex justify-between text-blue-600">
                        <span>Extra avgifter:</span>
                        <span>+{{ number_format($booking->total_extra_fees, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                <div class="border-t pt-2 mt-2 flex justify-between font-bold text-lg">
                    <span>Totalt (inkl. moms):</span>
                    <span>{{ number_format($booking->total_with_extra_fees, 2, ',', ' ') }} kr</span>                                                                                             
                </div>
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

        <!-- PDF Download -->
        <div class="card">
            <h4 class="font-semibold mb-3">📄 Dokument</h4>
            <a href="{{ route('booking.pdf.download', $booking) }}" class="w-full btn btn-primary flex items-center justify-center">
                📄 Ladda ner PDF-sammanfattning
            </a>
        </div>

        <!-- Extra Fees -->
        @if(in_array($booking->status, ['assigned', 'in_progress', 'completed']))
            <div class="card">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-semibold">💰 Extra Avgifter</h4>
                    @if(in_array($booking->status, ['assigned', 'in_progress']))
                        <a href="{{ route('company.extra-fees.create', $booking) }}" 
                           class="text-sm bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition">
                            + Lägg till
                        </a>
                    @endif
                </div>
                
                @if($booking->extraFees->count() > 0)
                    <div class="space-y-3">
                        @foreach($booking->extraFees as $extraFee)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h5 class="font-medium text-sm">{{ $extraFee->title }}</h5>
                                        @if($extraFee->description)
                                            <p class="text-xs text-gray-600 mt-1">{{ $extraFee->description }}</p>
                                        @endif
                                        <p class="text-sm font-semibold text-green-600 mt-1">{{ number_format($extraFee->amount, 0, ',', ' ') }} kr</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @switch($extraFee->status)
                                            @case('pending')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⏳ Väntande
                                                </span>
                                                @break
                                            @case('approved')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Godkänd
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    ❌ Avvisad
                                                </span>
                                                @break
                                        @endswitch
                                        
                                        @if($extraFee->status === 'rejected' && in_array($booking->status, ['assigned', 'in_progress']))
                                            <a href="{{ route('company.extra-fees.edit', $extraFee) }}" 
                                               class="text-xs text-blue-600 hover:text-blue-800">
                                                Redigera
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($extraFee->rejection_reason)
                                    <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-700">
                                        <strong>Anledning till avvisning:</strong> {{ $extraFee->rejection_reason }}
                                    </div>
                                @endif
                                
                                @if($extraFee->photo_path)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($extraFee->photo_path) }}" 
                                             alt="Extra avgift foto" 
                                             class="w-20 h-20 object-cover rounded">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Inga extra avgifter ännu</p>
                @endif
            </div>
        @endif

        <!-- Complaints -->
        @if($booking->status === 'completed')
            <div class="card">
                <h4 class="font-semibold mb-3">📝 Reklamationer</h4>
                @if($booking->complaints->count() > 0)
                    <div class="space-y-2">
                        @foreach($booking->complaints as $complaint)
                            <a href="{{ route('company.complaints.show', $complaint) }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-sm">{{ $complaint->subject }}</p>
                                        <p class="text-xs text-gray-500">{{ $complaint->created_at->format('Y-m-d') }}</p>
                                    </div>
                                    {!! $complaint->status_badge !!}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Inga reklamationer ännu</p>
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

@if(in_array($booking->status, ['assigned', 'in_progress']))
// Chat Auto-Refresh and Sound Notification
let lastMessageCount = {{ $booking->chats()->count() }};
let chatContainer = document.getElementById('chatMessages');

// Play notification sound
function playNotificationSound() {
    // Create a simple notification beep using Web Audio API
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.value = 800; // Frequency in Hz
    oscillator.type = 'sine';
    
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
}

// Check for new messages every 10 seconds
setInterval(function() {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newChatContainer = doc.getElementById('chatMessages');
        
        if (newChatContainer) {
            const currentMessageCount = newChatContainer.querySelectorAll('.mb-4').length;
            
            if (currentMessageCount > lastMessageCount) {
                // New message received
                chatContainer.innerHTML = newChatContainer.innerHTML;
                chatContainer.scrollTop = chatContainer.scrollHeight;
                
                // Play notification sound
                playNotificationSound();
                
                // Show browser notification
                if ("Notification" in window && Notification.permission === "granted") {
                    new Notification("💬 Nytt meddelande", {
                        body: "Du har fått ett nytt meddelande från kunden",
                        icon: "/favicon.ico"
                    });
                }
                
                lastMessageCount = currentMessageCount;
            }
        }
    })
    .catch(error => console.error('Error checking for new messages:', error));
}, 10000); // Check every 10 seconds

// Request notification permission on page load
if ("Notification" in window && Notification.permission === "default") {
    Notification.requestPermission();
}

// Auto-scroll to bottom of chat on page load
if (chatContainer) {
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

// Auto-scroll to chat section if hash is present
if (window.location.hash === '#chat') {
    document.getElementById('chat')?.scrollIntoView({ behavior: 'smooth' });
}
@endif
</script>
@endsection
