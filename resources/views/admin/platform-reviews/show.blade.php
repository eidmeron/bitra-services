@extends('layouts.admin')

@section('title', 'Plattformsrecension - Detaljer')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Plattformsrecension - Detaljer</h1>
            <p class="text-gray-600 mt-2">Recension #{{ $platformReview->id }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.platform-reviews.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
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
                                <svg class="w-6 h-6 {{ $i <= $platformReview->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $platformReview->overall_rating }}/5 stjärnor</h3>
                            <p class="text-gray-600">{{ $platformReview->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $platformReview->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $platformReview->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $platformReview->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($platformReview->status) }}
                    </span>
                </div>

                @if($platformReview->review_text)
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Recensionstext</h4>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $platformReview->review_text }}</p>
                    </div>
                @endif

                <!-- Detailed Ratings -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Detaljerade betyg</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Tjänstekvalitet</span>
                                <span class="text-sm font-bold text-gray-900">{{ $platformReview->service_quality_rating }}/5</span>
                            </div>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $platformReview->service_quality_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Priser</span>
                                <span class="text-sm font-bold text-gray-900">{{ $platformReview->pricing_rating }}/5</span>
                            </div>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $platformReview->pricing_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Snabbhet</span>
                                <span class="text-sm font-bold text-gray-900">{{ $platformReview->speed_rating }}/5</span>
                            </div>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $platformReview->speed_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if($platformReview->status === 'pending')
                    <div class="flex space-x-3">
                        <form method="POST" action="{{ route('admin.platform-reviews.approve', $platformReview) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">
                                Godkänn recension
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.platform-reviews.reject', $platformReview) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                                Avvisa recension
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Kundinformation</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Namn</label>
                        <p class="text-gray-900">{{ $platformReview->user->name ?? 'Anonym' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">E-post</label>
                        <p class="text-gray-900">{{ $platformReview->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Telefon</label>
                        <p class="text-gray-900">{{ $platformReview->user->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Review Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recensionsstatistik</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Totalt betyg</label>
                        <p class="text-2xl font-bold text-yellow-500">{{ $platformReview->overall_rating }}/5</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Status</label>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $platformReview->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $platformReview->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $platformReview->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($platformReview->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Skapad</label>
                        <p class="text-gray-900">{{ $platformReview->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($platformReview->is_featured)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Utvald</label>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                Ja
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Åtgärder</h3>
                <div class="space-y-3">
                    @if($platformReview->status === 'approved')
                        <form method="POST" action="{{ route('admin.platform-reviews.toggle-featured', $platformReview) }}" class="inline">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg">
                                {{ $platformReview->is_featured ? 'Ta bort från utvalda' : 'Markera som utvald' }}
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.platform-reviews.destroy', $platformReview) }}" onsubmit="return confirm('Är du säker på att du vill ta bort denna recension?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                            Ta bort recension
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
