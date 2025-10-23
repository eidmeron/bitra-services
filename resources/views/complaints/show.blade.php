@extends('layouts.user')

@section('title', 'Reklamation - ' . $complaint->complaint_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('user.bookings.show', $complaint->booking) }}" class="text-blue-600 hover:underline">
        &larr; Tillbaka till bokning
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold">{{ $complaint->complaint_number }}</h3>
                    <p class="text-gray-600">{{ $complaint->subject }}</p>
                </div>
                <div class="text-right">
                    {!! $complaint->status_badge !!}
                    {!! $complaint->priority_badge !!}
                </div>
            </div>

            <!-- Complaint Details -->
            <div class="border-t pt-4 mt-4">
                <h4 class="font-semibold mb-3">📋 Reklamationsdetaljer</h4>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-800">{{ $complaint->description }}</p>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="border-t pt-4 mt-4" id="chat">
                <h4 class="font-semibold mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Meddelanden
                </h4>
                
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                    <!-- Messages -->
                    <div class="bg-white rounded-lg p-4 mb-4 max-h-96 overflow-y-auto" id="chatMessages">
                        @forelse($complaint->messages as $message)
                            <div class="mb-4 {{ $message->sender_type === 'customer' ? 'text-right' : 'text-left' }}">
                                <div class="inline-block max-w-xs lg:max-w-md">
                                    <div class="text-xs text-gray-500 mb-1">
                                        {{ $message->sender_avatar }} {{ $message->sender_name }}
                                        <span class="ml-1">{{ $message->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="rounded-lg px-4 py-2 {{ $message->sender_type === 'customer' ? 'bg-blue-600 text-white' : ($message->sender_type === 'company' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-900') }}">
                                        {{ $message->message }}
                                    </div>
                                    
                                    <!-- Attachments -->
                                    @if($message->attachments && count($message->attachments) > 0)
                                        <div class="mt-2 space-y-1">
                                            @foreach($message->attachments as $index => $attachment)
                                                <a href="{{ route('complaints.download-attachment', ['complaint' => $complaint, 'attachment' => $index]) }}" 
                                                   class="inline-block text-xs bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded">
                                                    📎 {{ $attachment['name'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="text-sm">Inga meddelanden ännu.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Send Message Form -->
                    @if($complaint->status !== 'closed')
                        <form method="POST" action="{{ route('user.complaints.send-message', $complaint) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <textarea 
                                    name="message" 
                                    rows="3"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Skriv ditt meddelande..."
                                    required
                                ></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bifoga filer (valfritt)
                                </label>
                                <input 
                                    type="file" 
                                    name="attachments[]" 
                                    multiple
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <p class="text-xs text-gray-500 mt-1">Max 5 filer, 10MB per fil. Tillåtna format: JPG, PNG, PDF, DOC, DOCX</p>
                            </div>
                            
                            <button 
                                type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Skicka meddelande
                            </button>
                        </form>
                    @else
                        <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-center text-gray-600">
                            <p>Denna reklamation är stängd och kan inte längre kommenteras.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Info -->
        <div class="card">
            <h4 class="font-semibold mb-3">📊 Status</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Status:</span>
                    {!! $complaint->status_badge !!}
                </div>
                <div class="flex justify-between">
                    <span>Prioritet:</span>
                    {!! $complaint->priority_badge !!}
                </div>
                <div class="flex justify-between">
                    <span>Skapad:</span>
                    <span>{{ $complaint->created_at->format('Y-m-d H:i') }}</span>
                </div>
                @if($complaint->resolved_at)
                    <div class="flex justify-between">
                        <span>Löst:</span>
                        <span>{{ $complaint->resolved_at->format('Y-m-d H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Booking Info -->
        <div class="card">
            <h4 class="font-semibold mb-3">📋 Bokningsinformation</h4>
            <div class="space-y-2 text-sm">
                <div>
                    <span class="font-medium">Bokningsnummer:</span>
                    <p>{{ $complaint->booking->booking_number }}</p>
                </div>
                <div>
                    <span class="font-medium">Tjänst:</span>
                    <p>{{ $complaint->booking->service->name }}</p>
                </div>
                <div>
                    <span class="font-medium">Stad:</span>
                    <p>{{ $complaint->booking->city->name }}</p>
                </div>
                <div>
                    <span class="font-medium">Företag:</span>
                    <p>{{ $complaint->company->company_name ?? $complaint->company->user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Resolution -->
        @if($complaint->resolution)
            <div class="card bg-green-50 border border-green-200">
                <h4 class="font-semibold mb-3 text-green-900">✅ Lösning</h4>
                <p class="text-sm text-green-800">{{ $complaint->resolution }}</p>
            </div>
        @endif

        <!-- Support -->
        <div class="card bg-gray-50">
            <h4 class="font-semibold mb-3">Behöver du hjälp?</h4>
            <p class="text-sm text-gray-700 mb-3">Kontakta oss om du har frågor om din reklamation.</p>
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
@endsection

