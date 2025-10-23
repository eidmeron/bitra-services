@extends('layouts.company')

@section('title', 'Meddelanden')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">💬 Meddelanden</h2>
            <p class="text-gray-600 mt-1">Hantera meddelanden från kunder och administratörer</p>
        </div>
        @if($newCount > 0)
            <span class="px-4 py-2 bg-red-100 text-red-800 font-semibold rounded-full">
                {{ $newCount }} nya
            </span>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
        <div class="flex items-center">
            <span class="mr-2">✅</span>
            <p>{{ session('success') }}</p>
        </div>
    </div>
@endif

<!-- Messages List -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    @if($messages->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($messages as $message)
                <a href="{{ route('company.messages.show', $message) }}" 
                   class="block hover:bg-gray-50 transition-colors">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- Status Icon -->
                                <div class="flex-shrink-0">
                                    @if($message->status === 'new')
                                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                            <span class="text-2xl">📩</span>
                                        </div>
                                    @elseif($message->status === 'read')
                                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-2xl">👀</span>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-2xl">✅</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Message Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h3 class="text-lg font-semibold text-gray-900 {{ $message->status === 'new' ? 'font-bold' : '' }}">
                                            {{ $message->subject }}
                                        </h3>
                                        @if($message->status === 'new')
                                            <span class="px-2 py-1 text-xs font-semibold bg-red-500 text-white rounded-full">
                                                NY
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        👤 {{ $message->guest_name }} | 📧 {{ $message->guest_email }} 
                                        @if($message->guest_phone)
                                            | 📱 {{ $message->guest_phone }}
                                        @endif
                                    </p>
                                    <p class="text-gray-700 line-clamp-2 {{ $message->status === 'new' ? 'font-medium' : '' }}">
                                        {{ $message->message }}
                                    </p>
                                    
                                    @if($message->booking)
                                        <div class="mt-2 inline-flex items-center text-sm text-blue-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Relaterat till bokning: {{ $message->booking->booking_number }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Timestamp & Status -->
                            <div class="flex-shrink-0 ml-4 text-right">
                                <p class="text-sm text-gray-500">
                                    {{ $message->created_at->diffForHumans() }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $message->created_at->format('Y-m-d H:i') }}
                                </p>
                                @if($message->status === 'replied')
                                    <span class="mt-2 inline-flex items-center px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                        ✓ Besvarad
                                    </span>
                                @elseif($message->status === 'read')
                                    <span class="mt-2 inline-flex items-center px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                        👁️ Läst
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $messages->links() }}
        </div>
    @else
        <div class="p-12 text-center">
            <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <span class="text-5xl">📭</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga meddelanden</h3>
            <p class="text-gray-600">Du har inga meddelanden än. Meddelanden från kunder kommer att visas här.</p>
        </div>
    @endif
</div>

<!-- Help Info -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <h4 class="font-semibold text-blue-900 mb-2">💡 Om Meddelanden</h4>
    <ul class="text-sm text-blue-800 space-y-1">
        <li>• <strong>Nya</strong> meddelanden markerade med röd ikon och "NY" badge</li>
        <li>• Klicka på ett meddelande för att läsa och svara</li>
        <li>• Meddelanden kan vara relaterade till specifika bokningar</li>
        <li>• Dina svar skickas via e-post till kunden</li>
    </ul>
</div>
@endsection

