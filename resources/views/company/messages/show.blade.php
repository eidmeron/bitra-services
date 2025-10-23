@extends('layouts.company')

@section('title', 'Meddelandedetaljer')

@section('content')
<div class="mb-6">
    <a href="{{ route('company.messages.index') }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Tillbaka till meddelanden
    </a>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
        <div class="flex items-center">
            <span class="mr-2">✅</span>
            <p>{{ session('success') }}</p>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Message Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">💬 {{ $message->subject }}</h3>
                    @php
                        $statusColors = [
                            'new' => 'bg-red-100 text-red-800',
                            'read' => 'bg-blue-100 text-blue-800',
                            'replied' => 'bg-green-100 text-green-800',
                        ];
                        $statusLabels = [
                            'new' => 'Nytt',
                            'read' => 'Läst',
                            'replied' => 'Besvarad',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$message->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$message->status] ?? ucfirst($message->status) }}
                    </span>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Message Content -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <p class="text-gray-800 whitespace-pre-line leading-relaxed">{{ $message->message }}</p>
                </div>

                <!-- Reply Section -->
                @if($message->reply)
                    <div class="border-t pt-6">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="text-xl mr-2">↩️</span>
                            Ditt Svar
                        </h4>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-gray-800 whitespace-pre-line">{{ $message->reply }}</p>
                            <p class="text-xs text-gray-500 mt-3">
                                Skickat: {{ $message->replied_at?->format('Y-m-d H:i') }}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="border-t pt-6">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="text-xl mr-2">✍️</span>
                            Svara på Meddelandet
                        </h4>
                        <form action="{{ route('company.messages.reply', $message) }}" method="POST">
                            @csrf
                            <textarea 
                                name="reply" 
                                rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Skriv ditt svar här..."
                                required
                            ></textarea>
                            <p class="text-sm text-gray-500 mt-2 mb-4">
                                Ditt svar kommer att skickas till kundens e-post: <strong>{{ $message->guest_email }}</strong>
                            </p>
                            <button type="submit" class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-blue-700 transition-all shadow-md">
                                📧 Skicka Svar
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Sender Info -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">👤 Avsändarinformation</h3>
            </div>
            <div class="p-6 space-y-3">
                <div>
                    <label class="text-sm text-gray-600">Namn</label>
                    <p class="font-semibold text-gray-900">{{ $message->guest_name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">E-post</label>
                    <p class="font-semibold text-gray-900">
                        <a href="mailto:{{ $message->guest_email }}" class="text-blue-600 hover:underline">
                            {{ $message->guest_email }}
                        </a>
                    </p>
                </div>
                @if($message->guest_phone)
                    <div>
                        <label class="text-sm text-gray-600">Telefon</label>
                        <p class="font-semibold text-gray-900">
                            <a href="tel:{{ $message->guest_phone }}" class="text-blue-600 hover:underline">
                                {{ $message->guest_phone }}
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">📅 Tidslinje</h3>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-start">
                    <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 text-sm">✓</div>
                    <div>
                        <p class="font-medium text-sm">Mottaget</p>
                        <p class="text-xs text-gray-600">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if($message->read_at)
                    <div class="flex items-start">
                        <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 text-sm">✓</div>
                        <div>
                            <p class="font-medium text-sm">Läst</p>
                            <p class="text-xs text-gray-600">{{ $message->read_at->format('Y-m-d H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $message->read_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endif
                @if($message->replied_at)
                    <div class="flex items-start">
                        <div class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 text-sm">✓</div>
                        <div>
                            <p class="font-medium text-sm">Besvarad</p>
                            <p class="text-xs text-gray-600">{{ $message->replied_at->format('Y-m-d H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $message->replied_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Booking -->
        @if($message->booking)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Relaterad Bokning</h3>
                </div>
                <div class="p-6">
                    <a href="{{ route('company.bookings.show', $message->booking) }}" class="block hover:bg-gray-50 rounded-lg p-4 border-2 border-gray-200 hover:border-blue-300 transition-all">
                        <p class="font-semibold text-blue-600">{{ $message->booking->booking_number }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $message->booking->service->name }}</p>
                        <p class="text-xs text-gray-500 mt-2">Klicka för att visa bokning →</p>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

