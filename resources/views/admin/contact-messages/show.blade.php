@extends('layouts.admin')

@section('title', 'Visa Meddelande')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📧 Meddelande från {{ $contactMessage->name }}</h1>
        <a href="{{ route('admin.contact-messages.index') }}" 
           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
            Tillbaka
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Message Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Original Message -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-bold text-gray-900">{{ $contactMessage->subject }}</h2>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                        $contactMessage->status === 'new' ? 'bg-red-100 text-red-800' : 
                        ($contactMessage->status === 'replied' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')
                    }}">
                        {{ ucfirst($contactMessage->status) }}
                    </span>
                </div>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $contactMessage->message }}</p>
                </div>
            </div>

            <!-- Reply Section -->
            @if($contactMessage->status !== 'replied')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">💬 Svara på meddelandet</h3>
                    <form method="POST" action="{{ route('admin.contact-messages.reply', $contactMessage) }}">
                        @csrf
                        <textarea 
                            name="admin_reply" 
                            rows="6" 
                            required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Skriv ditt svar här..."></textarea>
                        @error('admin_reply')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="mt-4">
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                Skicka Svar
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Show Reply -->
                <div class="bg-green-50 border-l-4 border-green-500 rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-green-900 mb-2">✅ Ditt svar ({{ $contactMessage->replied_at->format('Y-m-d H:i') }})</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $contactMessage->admin_reply }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Contact Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Avsändarinfo</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Namn</p>
                        <p class="font-semibold text-gray-900">{{ $contactMessage->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">E-post</p>
                        <a href="mailto:{{ $contactMessage->email }}" class="font-semibold text-blue-600 hover:underline break-all">
                            {{ $contactMessage->email }}
                        </a>
                    </div>
                    @if($contactMessage->phone)
                        <div>
                            <p class="text-sm text-gray-600">Telefon</p>
                            <a href="tel:{{ $contactMessage->phone }}" class="font-semibold text-green-600 hover:underline">
                                {{ $contactMessage->phone }}
                            </a>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600">Mottaget</p>
                        <p class="font-semibold text-gray-900">{{ $contactMessage->created_at->format('Y-m-d H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $contactMessage->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚙️ Åtgärder</h3>
                <div class="space-y-3">
                    <a href="mailto:{{ $contactMessage->email }}" 
                       class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Öppna i e-postklient
                    </a>
                    <form method="POST" action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Är du säker på att du vill radera detta meddelande?')"
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Radera meddelande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
