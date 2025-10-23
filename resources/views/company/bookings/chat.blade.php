@extends('layouts.company')

@section('title', 'Chat - ' . $booking->booking_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('company.bookings.show', $booking) }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Tillbaka till bokning
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Chat Area -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col" style="height: 70vh;">
            <!-- Chat Header -->
            <div class="bg-gradient-to-r from-green-600 to-blue-600 px-6 py-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">💬 Chat med {{ $booking->customer_name }}</h3>
                        <p class="text-sm text-green-100">Bokning: {{ $booking->booking_number }}</p>
                    </div>
                    <div class="text-right">
                        @if($canSendMessages)
                            <span class="inline-flex items-center px-3 py-1 bg-green-500 text-white rounded-full text-sm">
                                <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                Aktiv
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-gray-500 text-white rounded-full text-sm">
                                🔒 Stängd
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="chatMessages" class="flex-1 overflow-y-auto p-6 bg-gray-50 space-y-4">
                @forelse($chats as $chat)
                    <div class="flex {{ $chat->sender_type === 'company' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%]">
                            <div class="flex items-end space-x-2 {{ $chat->sender_type === 'company' ? 'flex-row-reverse space-x-reverse' : '' }}">
                                <!-- Avatar -->
                                <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $chat->sender_type === 'company' ? 'bg-green-500' : 'bg-blue-500' }} flex items-center justify-center text-white font-semibold text-sm">
                                    {{ $chat->sender_type === 'company' ? '🏢' : '👤' }}
                                </div>
                                
                                <!-- Message Bubble -->
                                <div>
                                    <div class="px-4 py-2 rounded-lg {{ $chat->sender_type === 'company' ? 'bg-green-600 text-white' : 'bg-white text-gray-900 shadow' }}">
                                        <p class="text-sm whitespace-pre-wrap">{{ $chat->message }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 {{ $chat->sender_type === 'company' ? 'text-right' : 'text-left' }}">
                                        {{ $chat->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-4xl">💬</span>
                        </div>
                        <p class="text-gray-500">Inga meddelanden än. Starta en konversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            @if($canSendMessages)
                <div class="bg-white border-t border-gray-200 p-4">
                    <form id="chatForm" class="flex space-x-3">
                        @csrf
                        <input 
                            type="text" 
                            id="messageInput"
                            name="message"
                            placeholder="Skriv ditt meddelande..."
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required
                            maxlength="1000"
                        >
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-blue-700 transition-all shadow-md flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Skicka
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-yellow-50 border-t border-yellow-200 p-4 text-center">
                    <p class="text-sm text-yellow-800">
                        🔒 Chatten är stängd. Bokningen är {{ $booking->status }}.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Booking Info -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3 border-b">
                <h4 class="font-semibold text-gray-900">📋 Bokningsinformation</h4>
            </div>
            <div class="p-4 space-y-3 text-sm">
                <div>
                    <span class="text-gray-600">Bokning:</span>
                    <p class="font-semibold">{{ $booking->booking_number }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Tjänst:</span>
                    <p class="font-semibold">{{ $booking->service->name }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Kund:</span>
                    <p class="font-semibold">{{ $booking->customer_name }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Telefon:</span>
                    <p class="font-semibold">
                        <a href="tel:{{ $booking->customer_phone }}" class="text-blue-600 hover:underline">
                            {{ $booking->customer_phone }}
                        </a>
                    </p>
                </div>
                <div>
                    <span class="text-gray-600">E-post:</span>
                    <p class="font-semibold">
                        <a href="mailto:{{ $booking->customer_email }}" class="text-blue-600 hover:underline">
                            {{ $booking->customer_email }}
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Help -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <h4 class="font-semibold text-blue-900 mb-2">💡 Tips</h4>
            <ul class="text-sm text-blue-800 space-y-1">
                <li>• Chatten uppdateras automatiskt</li>
                <li>• Max 1000 tecken per meddelande</li>
                <li>• Chatten stängs när bokningen är slutförd</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
let lastMessageId = {{ $chats->last()?->id ?? 0 }};

// Auto-scroll to bottom
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Add message to chat
function addMessage(chat, isOwn = false) {
    const chatMessages = document.getElementById('chatMessages');
    
    // Remove empty state if exists
    const emptyState = chatMessages.querySelector('.text-center.py-12');
    if (emptyState) {
        emptyState.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'}`;
    
    messageDiv.innerHTML = `
        <div class="max-w-[70%]">
            <div class="flex items-end space-x-2 ${isOwn ? 'flex-row-reverse space-x-reverse' : ''}">
                <div class="flex-shrink-0 w-8 h-8 rounded-full ${isOwn ? 'bg-green-500' : 'bg-blue-500'} flex items-center justify-center text-white font-semibold text-sm">
                    ${isOwn ? '🏢' : '👤'}
                </div>
                <div>
                    <div class="px-4 py-2 rounded-lg ${isOwn ? 'bg-green-600 text-white' : 'bg-white text-gray-900 shadow'}">
                        <p class="text-sm whitespace-pre-wrap">${escapeHtml(chat.message)}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ${isOwn ? 'text-right' : 'text-left'}">
                        ${chat.time_ago}
                    </p>
                </div>
            </div>
        </div>
    `;
    
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Send message
document.getElementById('chatForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    try {
        const response = await fetch('{{ route('company.bookings.chat.send', $booking) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        
        if (data.success) {
            addMessage(data.chat, true);
            messageInput.value = '';
            lastMessageId = data.chat.id;
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Kunde inte skicka meddelande. Försök igen.');
    }
});

// Fetch new messages
async function fetchNewMessages() {
    try {
        const response = await fetch('{{ route('company.bookings.chat.fetch', $booking) }}');
        const data = await response.json();
        
        data.chats.forEach(chat => {
            if (chat.id > lastMessageId) {
                addMessage(chat, chat.sender_type === 'company');
                lastMessageId = chat.id;
            }
        });
    } catch (error) {
        console.error('Error fetching messages:', error);
    }
}

// Auto-refresh every 3 seconds if chat is active
@if($canSendMessages)
    setInterval(fetchNewMessages, 3000);
@endif

// Initial scroll
scrollToBottom();
</script>
@endpush
@endsection

