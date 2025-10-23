@extends('layouts.user')

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
                        <label class="text-sm text-gray-600">Bokningstyp</label>
                        <p class="font-medium">
                            @if($booking->booking_type === 'one_time')
                                En gång
                            @else
                                Prenumeration ({{ getSubscriptionFrequencyLabel($booking->subscription_frequency) }})
                            @endif
                        </p>
                    </div>
                    @if($booking->customer_type === 'company' && $booking->org_number)
                        <div>
                            <label class="text-sm text-gray-600">Organisationsnummer</label>
                            <p class="font-mono font-medium">{{ $booking->org_number }}</p>
                        </div>
                    @endif
                    @if($booking->customer_type === 'private' && $booking->personnummer)
                        <div>
                            <label class="text-sm text-gray-600">Personnummer (ROT)</label>
                            <p class="font-mono font-medium">{{ $booking->personnummer }}</p>
                        </div>
                    @endif
                    @if($booking->preferred_date)
                        <div>
                            <label class="text-sm text-gray-600">Önskat datum</label>
                            <p class="font-medium">{{ $booking->preferred_date->format('Y-m-d') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Form Data -->
            @if($booking->form_data && is_array($booking->form_data) && count($booking->form_data) > 0)
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

            <!-- Chat Section -->
            @if(in_array($booking->status, ['assigned', 'in_progress']) && $booking->company)
                <div class="border-t pt-4 mt-4" id="chat">
                    <h4 class="font-semibold mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chatta med {{ $booking->company->company_name ?? 'företaget' }}
                    </h4>
                    
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                        <!-- Chat Messages -->
                        <div class="bg-white rounded-lg p-4 mb-4 max-h-96 overflow-y-auto" id="chatMessages">
                            @forelse($booking->chats()->orderBy('created_at', 'asc')->get() as $chat)
                                <div class="mb-4 {{ $chat->sender_type === 'user' ? 'text-right' : 'text-left' }}">
                                    <div class="inline-block max-w-xs lg:max-w-md">
                                        <div class="text-xs text-gray-500 mb-1">
                                            {{ $chat->sender_type === 'user' ? 'Du' : $booking->company->company_name }}
                                            <span class="ml-1">{{ $chat->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="rounded-lg px-4 py-2 {{ $chat->sender_type === 'user' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900' }}">
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
                        <form method="POST" action="{{ route('user.bookings.chat.send', $booking) }}" class="flex space-x-2">
                            @csrf
                            <input type="hidden" name="sender_type" value="user">
                            <input 
                                type="text" 
                                name="message" 
                                class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Skriv ditt meddelande..."
                                required
                            >
                            <button 
                                type="submit" 
                                class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center"
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

            <!-- Review Section -->
            @if($booking->canBeReviewed())
                <div class="border-t pt-4 mt-4" id="review">
                    <h4 class="font-semibold mb-3">⭐ Lämna recension</h4>
                    <div class="bg-green-50 border border-green-200 p-4 rounded mb-4">
                        <p class="text-sm text-green-800">
                            Tjänsten är slutförd! Dela din upplevelse med andra kunder.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('user.bookings.review', $booking) }}" x-data="{ rating: 0 }">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Betyg *</label>
                            <div class="flex space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer" @click="rating = {{ $i }}">
                                        <input type="radio" name="rating" value="{{ $i }}" required class="hidden" x-model="rating">
                                        <span 
                                            class="text-4xl transition-colors duration-150"
                                            :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-300'"
                                        >★</span>
                                    </label>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-500 mt-2">
                                <span x-show="rating === 1">⭐ Dålig</span>
                                <span x-show="rating === 2">⭐⭐ Mindre bra</span>
                                <span x-show="rating === 3">⭐⭐⭐ Okej</span>
                                <span x-show="rating === 4">⭐⭐⭐⭐ Bra</span>
                                <span x-show="rating === 5">⭐⭐⭐⭐⭐ Utmärkt!</span>
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Din recension</label>
                            <textarea name="review_text" rows="4" class="form-input" placeholder="Berätta om din upplevelse..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            📝 Skicka recension
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

            <!-- Complaint Section -->
            @if($booking->status === 'completed')
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-3">📝 Reklamation</h4>
                    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded">
                        <p class="text-sm text-yellow-800 mb-3">
                            Om du inte är nöjd med tjänsten kan du skapa en reklamation.
                        </p>
                        <a href="{{ route('complaints.create', $booking) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                            📝 Skapa reklamation
                        </a>
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

    <!-- Chat Auto-Refresh and Sound Notification -->
    @if(in_array($booking->status, ['assigned', 'in_progress']) && $booking->company)
    <script>
        let lastMessageCount = {{ $booking->chats->count() }};
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
                                body: "Du har fått ett nytt meddelande från företaget",
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
    </script>
    @endif

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
                <div class="flex justify-between">
                    <span>Delsumma:</span>
                    <span>{{ number_format($booking->subtotal ?? ($booking->final_price - ($booking->tax_amount ?? 0)), 2, ',', ' ') }} kr</span>
                </div>
                <div class="flex justify-between">
                    <span>Moms ({{ number_format($booking->tax_rate ?? ($booking->service->tax_rate ?? 25), 2, ',', ' ') }}%):</span>
                    <span>{{ number_format($booking->tax_amount ?? 0, 2, ',', ' ') }} kr</span>
                </div>
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

        <!-- PDF Download -->
        <div class="card">
            <h4 class="font-semibold mb-3">📄 Dokument</h4>
            <a href="{{ route('booking.pdf.download', $booking) }}" class="w-full btn btn-primary flex items-center justify-center">
                📄 Ladda ner PDF-sammanfattning
            </a>
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

