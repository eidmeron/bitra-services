@extends('layouts.admin')

@section('title', 'Recensionsdetaljer')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">⭐ Recensionsdetaljer</h1>
            <p class="text-gray-600 mt-2">Recension #{{ $review->id }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Tillbaka
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-6 h-6 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $review->rating }}/5 stjärnor</h3>
                            <p class="text-gray-600">{{ $review->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $review->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $review->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $review->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($review->status) }}
                    </span>
                </div>

                @if($review->review_text)
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Recensionstext</h4>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $review->review_text }}</p>
                    </div>
                @endif

                <!-- Actions -->
                @if($review->status === 'pending')
                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">
                                ✓ Godkänn recension
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                                ✗ Avvisa recension
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Booking Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Bokningsdetaljer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Boknings-ID</label>
                        <p class="text-gray-900">{{ $review->booking->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Tjänst</label>
                        <p class="text-gray-900">{{ $review->booking->service_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Datum</label>
                        <p class="text-gray-900">{{ $review->booking->booking_date }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Status</label>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $review->booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $review->booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $review->booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $review->booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($review->booking->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">👤 Kundinformation</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Namn</label>
                        <p class="text-gray-900">{{ $review->booking->user->name ?? 'Anonym' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">E-post</label>
                        <p class="text-gray-900">{{ $review->booking->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Telefon</label>
                        <p class="text-gray-900">{{ $review->booking->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Company Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🏢 Företagsinformation</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Företagsnamn</label>
                        <p class="text-gray-900">{{ $review->company->company_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">E-post</label>
                        <p class="text-gray-900">{{ $review->company->user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Telefon</label>
                        <p class="text-gray-900">{{ $review->company->phone ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.companies.show', $review->company) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Visa företagsprofil →
                    </a>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Åtgärder</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Är du säker på att du vill ta bort denna recension?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                            🗑️ Ta bort recension
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
