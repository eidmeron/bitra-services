@extends('layouts.admin')

@section('title', 'Notifikationer')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Notifikationer</h2>
        <p class="text-gray-600 mt-1">Hantera dina notifikationer och meddelanden</p>
    </div>
    @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                ✅ Markera alla som lästa
            </button>
        </form>
    @endif
</div>

<!-- Notifications List -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    @forelse($notifications as $notification)
        <div class="border-b border-gray-200 hover:bg-gray-50 transition {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 {{ $notification->read_at ? 'bg-gray-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center text-2xl">
                                {{ $notification->data['icon'] ?? '🔔' }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ $notification->data['title'] ?? 'Notifikation' }}
                                        @if(!$notification->read_at)
                                            <span class="ml-2 px-2 py-0.5 bg-blue-600 text-white text-xs rounded-full">Ny</span>
                                        @endif
                                    </h3>
                                    <p class="text-gray-700 mb-2">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span>🕐 {{ $notification->created_at->diffForHumans() }}</span>
                                        <span>•</span>
                                        <span>{{ $notification->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2 ml-4">
                        @if(isset($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                Visa
                            </a>
                        @endif
                        
                        @if(!$notification->read_at)
                            <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition"
                                        title="Markera som läst">
                                    ✓
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" 
                              onsubmit="return confirm('Är du säker på att du vill radera denna notifikation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition"
                                    title="Radera">
                                🗑️
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Inga Notifikationer</h3>
            <p class="text-gray-600">Du har inga notifikationer för tillfället.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
@endif

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Totalt</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ auth()->user()->notifications->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl">
                🔔
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Lästa</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ auth()->user()->readNotifications->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl">
                ✅
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Olästa</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ auth()->user()->unreadNotifications->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-2xl">
                ⚠️
            </div>
        </div>
    </div>
</div>
@endsection

