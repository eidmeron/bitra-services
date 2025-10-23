@extends('layouts.admin')

@section('title', 'Plattformsrecensioner')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">⭐ Plattformsrecensioner</h1>
            <p class="text-gray-600 mt-1">Hantera och moderera kundrecensioner för plattformen</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 text-2xl">📊</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Totalt</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-yellow-600 text-2xl">⏳</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Väntande</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-green-600 text-2xl">✅</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Godkända</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <span class="text-purple-600 text-2xl">⭐</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Genomsnitt</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['average_rating'] }}/5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">Utvalda</p>
            <p class="text-xl font-bold text-yellow-600">{{ $stats['featured'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">Idag</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['today'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">Denna vecka</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['this_week'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <p class="text-gray-600 text-sm">4-5 Stjärnor</p>
            <p class="text-xl font-bold text-purple-600">{{ $stats['high_rated'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Filtrera recensioner</h3>
        <form method="GET" action="{{ route('admin.platform-reviews.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sök</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Namn, e-post, kommentar..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Alla</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Väntande</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Godkänd</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Avvisad</option>
                </select>
            </div>

            <!-- Rating -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Min. Betyg</label>
                <select name="rating" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Alla</option>
                    <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5⭐</option>
                    <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4+⭐</option>
                    <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3+⭐</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sortera</label>
                <select name="sort" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Senaste</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Äldsta</option>
                    <option value="highest_rating" {{ request('sort') == 'highest_rating' ? 'selected' : '' }}>Högsta betyg</option>
                    <option value="lowest_rating" {{ request('sort') == 'lowest_rating' ? 'selected' : '' }}>Lägsta betyg</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Namn</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Filtrera
                </button>
                <a href="{{ route('admin.platform-reviews.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Rensa
                </a>
            </div>
        </form>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Recensioner ({{ $reviews->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kund</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Betyg</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kommentar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($review->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="font-semibold text-gray-900">{{ $review->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $review->email }}</div>
                                    @if($review->user_id)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Registrerad</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold text-gray-900 mr-2">{{ $review->overall_rating }}</span>
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            </div>
                            <div class="mt-1 space-x-1 text-xs">
                                @if($review->service_quality_rating)
                                    <span class="text-blue-600">Q:{{ $review->service_quality_rating }}</span>
                                @endif
                                @if($review->price_rating)
                                    <span class="text-green-600">P:{{ $review->price_rating }}</span>
                                @endif
                                @if($review->speed_rating)
                                    <span class="text-purple-600">S:{{ $review->speed_rating }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-700 text-sm">{{ Str::limit($review->comment, 80) }}</p>
                            @if($review->is_featured)
                                <span class="inline-block mt-1 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">
                                    ⭐ Utvald
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($review->status == 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Väntande</span>
                            @elseif($review->status == 'approved')
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Godkänd</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Avvisad</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $review->created_at->format('Y-m-d') }}</div>
                            <div class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.platform-reviews.show', $review) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Visa">
                                    👁️
                                </a>

                                @if($review->status == 'pending')
                                    <form action="{{ route('admin.platform-reviews.approve', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Godkänn">
                                            ✅
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.platform-reviews.reject', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Avvisa">
                                            ❌
                                        </button>
                                    </form>
                                @endif

                                @if($review->status == 'approved')
                                    <form action="{{ route('admin.platform-reviews.toggle-featured', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="hover:scale-110 transition" title="Växla utvald">
                                            {{ $review->is_featured ? '⭐' : '☆' }}
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.platform-reviews.destroy', $review) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Är du säker på att du vill radera denna recension?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Radera">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-6xl mb-4">📭</div>
                            <p class="text-xl font-semibold">Inga recensioner hittades</p>
                            <p class="text-sm mt-2">Recensioner från kunder visas här när de skickas in.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
        <div class="bg-gray-50 px-6 py-4">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>

    <!-- Company Reviews Section -->
    <div class="bg-white rounded-lg shadow-md mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Företagsrecensioner</h2>
                <a href="{{ route('admin.reviews.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Visa alla →
                </a>
            </div>
        </div>

        <!-- Company Review Stats -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $companyStats['total'] }}</div>
                    <div class="text-sm text-gray-600">Totalt</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $companyStats['pending'] }}</div>
                    <div class="text-sm text-gray-600">Väntande</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $companyStats['approved'] }}</div>
                    <div class="text-sm text-gray-600">Godkända</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $companyStats['rejected'] }}</div>
                    <div class="text-sm text-gray-600">Avvisade</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $companyStats['today'] }}</div>
                    <div class="text-sm text-gray-600">Idag</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $companyStats['this_week'] }}</div>
                    <div class="text-sm text-gray-600">Denna vecka</div>
                </div>
            </div>
        </div>

        <!-- Company Reviews List -->
        <div class="overflow-x-auto">
            @if($companyReviews->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recension</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kund</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Företag</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($companyReviews as $review)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex items-center mr-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $review->rating }}/5</div>
                                            @if($review->review_text)
                                                <div class="text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($review->review_text, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $review->booking->user->name ?? 'Anonym' }}</div>
                                    <div class="text-sm text-gray-500">{{ $review->booking->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $review->company->company_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $review->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $review->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $review->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $review->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.reviews.show', $review) }}" class="text-blue-600 hover:text-blue-900">Visa</a>
                                        @if($review->status === 'pending')
                                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Godkänn</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Avvisa</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Inga företagsrecensioner</h3>
                    <p class="mt-1 text-sm text-gray-500">Det finns inga företagsrecensioner ännu.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

