@extends('layouts.admin')

@section('title', 'Företagsdetaljer')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.companies.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till företag</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center">
                    @if($company->company_logo)
                        <img src="{{ Storage::url($company->company_logo) }}" alt="Logo" class="w-20 h-20 object-cover rounded mr-4">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded mr-4 flex items-center justify-center">
                            <span class="text-4xl">🏢</span>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold">{{ $company->user->email }}</h2>
                        <p class="text-gray-600">Org.nr: {{ $company->company_org_number }}</p>
                    </div>
                </div>
                {!! companyStatusBadge($company->status) !!}
            </div>

            <div class="grid grid-cols-2 gap-4 border-t pt-4">
                <div>
                    <label class="text-sm text-gray-600">Företagets e-post</label>
                    <p class="font-medium">{{ $company->company_email }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Telefon</label>
                    <p class="font-medium">{{ $company->company_number }}</p>
                </div>
                @if($company->site)
                    <div class="col-span-2">
                        <label class="text-sm text-gray-600">Webbplats</label>
                        <p><a href="{{ $company->site }}" target="_blank" class="text-blue-600 hover:underline">{{ $company->site }}</a></p>
                    </div>
                @endif
                <div>
                    <label class="text-sm text-gray-600">Medlem sedan</label>
                    <p class="font-medium">{{ $company->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="card">
            <h3 class="text-xl font-semibold mb-4">Tjänster ({{ $company->services->count() }})</h3>
            @if($company->services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($company->services as $service)
                        <div class="flex items-center p-3 bg-gray-50 rounded">
                            @if($service->icon)
                                <span class="text-2xl mr-2">{{ $service->icon }}</span>
                            @endif
                            <div>
                                <p class="font-medium">{{ $service->name }}</p>
                                <p class="text-xs text-gray-600">{{ $service->category->name }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Inga tjänster tilldelade ännu</p>
            @endif
        </div>

        <!-- Cities -->
        <div class="card">
            <h3 class="text-xl font-semibold mb-4">Verksamhetsområden ({{ $company->cities->count() }})</h3>
            @if($company->cities->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($company->cities as $city)
                        <span class="badge badge-info">{{ $city->name }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Inga städer tilldelade ännu</p>
            @endif
        </div>

        <!-- Recent Bookings -->
        <div class="card">
            <h3 class="text-xl font-semibold mb-4">Senaste bokningar</h3>
            @if($company->bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th>Bokningsnr</th>
                                <th>Tjänst</th>
                                <th>Pris</th>
                                <th>Status</th>
                                <th>Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($company->bookings->take(10) as $booking)
                                <tr class="border-t">
                                    <td>{{ $booking->booking_number }}</td>
                                    <td>{{ $booking->service->name }}</td>
                                    <td>{{ number_format($booking->final_price, 2, ',', ' ') }} kr</td>
                                    <td>{!! bookingStatusBadge($booking->status) !!}</td>
                                    <td class="text-sm text-gray-600">{{ $booking->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Inga bokningar ännu</p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Statistics -->
        <div class="card">
            <h3 class="text-xl font-semibold mb-4">📊 Statistik</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Genomsnittligt betyg:</span>
                    <span class="font-bold text-2xl text-yellow-600">{{ number_format($company->review_average, 1) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Antal recensioner:</span>
                    <span class="font-bold text-lg">{{ $company->review_count }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Totala bokningar:</span>
                    <span class="font-bold text-lg">{{ $company->bookings->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Slutförda:</span>
                    <span class="font-bold text-lg text-green-600">{{ $company->bookings->where('status', 'completed')->count() }}</span>
                </div>
                @if($company->review_average > 0)
                    <div class="pt-3 border-t">
                        <div class="text-center">
                            {!! reviewStars((int)round($company->review_average)) !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <h3 class="text-xl font-semibold mb-4">Åtgärder</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-primary w-full">
                    ✏️ Redigera företag
                </a>
                
                @if($company->status === 'pending')
                    <form method="POST" action="{{ route('admin.companies.update', $company) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="active">
                        <input type="hidden" name="email" value="{{ $company->user->email }}">
                        <input type="hidden" name="company_email" value="{{ $company->company_email }}">
                        <input type="hidden" name="company_number" value="{{ $company->company_number }}">
                        <input type="hidden" name="company_org_number" value="{{ $company->company_org_number }}">
                        <button type="submit" class="btn btn-success w-full">
                            ✓ Godkänn företag
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card">
            <h3 class="text-xl font-semibold mb-4">Kontakt</h3>
            <div class="space-y-2 text-sm">
                <div>
                    <p class="text-gray-600">Inloggning:</p>
                    <p class="font-medium">{{ $company->user->email }}</p>
                </div>
                @if($company->user->phone)
                    <div>
                        <p class="text-gray-600">Telefon:</p>
                        <p class="font-medium">{{ $company->user->phone }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-gray-600">Skapad:</p>
                    <p class="font-medium">{{ $company->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

