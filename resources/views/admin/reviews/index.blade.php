@extends('layouts.admin')

@section('title', 'Alla Recensioner')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">⭐ Alla Recensioner</h1>
            <p class="text-gray-600 mt-2">Hantera både företags- och plattformsrecensioner från kunder</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 text-2xl">📊</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Totalt</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-600">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-red-600 text-2xl">❌</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-semibold text-gray-600">Avvisade</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Recensionstyp</label>
                <select name="review_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Alla typer</option>
                    <option value="company" {{ request('review_type') === 'company' ? 'selected' : '' }}>Företag</option>
                    <option value="bitra" {{ request('review_type') === 'bitra' ? 'selected' : '' }}>Bitra</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Företagsstatus</label>
                <select name="company_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Alla statusar</option>
                    <option value="pending" {{ request('company_status') === 'pending' ? 'selected' : '' }}>Väntande</option>
                    <option value="approved" {{ request('company_status') === 'approved' ? 'selected' : '' }}>Godkända</option>
                    <option value="rejected" {{ request('company_status') === 'rejected' ? 'selected' : '' }}>Avvisade</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bitra-status</label>
                <select name="bitra_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Alla statusar</option>
                    <option value="pending" {{ request('bitra_status') === 'pending' ? 'selected' : '' }}>Väntande</option>
                    <option value="approved" {{ request('bitra_status') === 'approved' ? 'selected' : '' }}>Godkända</option>
                    <option value="rejected" {{ request('bitra_status') === 'rejected' ? 'selected' : '' }}>Avvisade</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Företag</label>
                <select name="company" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Alla företag</option>
                    @foreach(\App\Models\Company::orderBy('company_name')->get() as $company)
                        <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                    🔍 Filtrera
                </button>
            </div>
        </form>
    </div>

    <!-- Reviews List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($reviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Företagsrecension</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bitra-recension</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kund</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Företag</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tjänst</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-gray-50">
                                <!-- Company Review -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($review->hasCompanyReview())
                                        <div class="flex items-center">
                                            <div class="flex items-center mr-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->company_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $review->company_rating }}/5</div>
                                                <div class="text-xs text-gray-500">
                                                    @if($review->company_status === 'approved')
                                                        <span class="text-green-600">✓ Godkänd</span>
                                                    @elseif($review->company_status === 'pending')
                                                        <span class="text-yellow-600">⏳ Väntande</span>
                                                    @else
                                                        <span class="text-red-600">✗ Avvisad</span>
                                                    @endif
                                                </div>
                                                @if($review->company_review_text)
                                                    <div class="text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($review->company_review_text, 30) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Ingen recension</span>
                                    @endif
                                </td>

                                <!-- Bitra Review -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($review->hasBitraReview())
                                        <div class="flex items-center">
                                            <div class="flex items-center mr-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->bitra_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $review->bitra_rating }}/5</div>
                                                <div class="text-xs text-gray-500">
                                                    @if($review->bitra_status === 'approved')
                                                        <span class="text-green-600">✓ Godkänd</span>
                                                    @elseif($review->bitra_status === 'pending')
                                                        <span class="text-yellow-600">⏳ Väntande</span>
                                                    @else
                                                        <span class="text-red-600">✗ Avvisad</span>
                                                    @endif
                                                </div>
                                                @if($review->bitra_review_text)
                                                    <div class="text-sm text-gray-500 max-w-xs truncate">{{ Str::limit($review->bitra_review_text, 30) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Ingen recension</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $review->booking->user->name ?? 'Anonym' }}</div>
                                    <div class="text-sm text-gray-500">{{ $review->booking->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $review->company->company_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $review->booking->service_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $review->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col space-y-1">
                                        <a href="{{ route('admin.reviews.show', $review) }}" class="text-blue-600 hover:text-blue-900">Visa</a>
                                        
                                        <!-- Company Review Actions -->
                                        @if($review->hasCompanyReview() && $review->company_status === 'pending')
                                            <div class="flex space-x-1">
                                                <form method="POST" action="{{ route('admin.reviews.approve-company', $review) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 text-xs">Godkänn företag</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.reviews.reject-company', $review) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Avvisa företag</button>
                                                </form>
                                            </div>
                                        @endif

                                        <!-- Bitra Review Actions -->
                                        @if($review->hasBitraReview() && $review->bitra_status === 'pending')
                                            <div class="flex space-x-1">
                                                <form method="POST" action="{{ route('admin.reviews.approve-bitra', $review) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 text-xs">Godkänn Bitra</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.reviews.reject-bitra', $review) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Avvisa Bitra</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Inga recensioner</h3>
                <p class="mt-1 text-sm text-gray-500">Det finns inga recensioner som matchar dina filter.</p>
            </div>
        @endif
    </div>
</div>
@endsection
