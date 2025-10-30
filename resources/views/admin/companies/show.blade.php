@extends('layouts.admin')

@section('title', 'Företagsdetaljer - ' . $company->company_name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $company->company_name }}</h2>
            <p class="text-gray-600 mt-1">Fullständig företagsinformation och statistik</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.deposits.index', ['company_id' => $company->id]) }}" class="btn btn-success">
                💰 Deposits & Kommissioner
            </a>
            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-primary">
                ✏️ Redigera
            </a>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
                ← Tillbaka
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content (2/3) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Company Profile -->
        <div class="card">
            <div class="card-header bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <h3 class="text-lg font-semibold">🏢 Företagsprofil</h3>
            </div>
            <div class="card-body">
                <div class="flex items-start space-x-6 mb-6">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->company_name }}" class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200">
                        @else
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white text-3xl font-bold">
                                {{ strtoupper(substr($company->company_name, 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Company Info -->
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $company->company_name }}</h3>
                        <div class="flex items-center space-x-4 mb-4">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $company->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $company->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $company->status === 'inactive' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($company->status) }}
                            </span>
                            @if($company->review_count > 0)
                                <div class="flex items-center text-yellow-500">
                                    <span class="text-lg">⭐</span>
                                    <span class="ml-1 font-semibold">{{ number_format($company->review_average, 1) }}</span>
                                    <span class="text-gray-500 ml-1">({{ $company->review_count }} recensioner)</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($company->description)
                            <p class="text-gray-700 leading-relaxed">{{ $company->description }}</p>
                        @else
                            <p class="text-gray-400 italic">Ingen beskrivning tillgänglig</p>
                        @endif
                    </div>
                </div>

                <!-- Key Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mr-3">
                            📧
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase">Företags-epost</div>
                            <a href="mailto:{{ $company->company_email }}" class="text-blue-600 hover:underline">
                                {{ $company->company_email }}
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600 mr-3">
                            📱
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase">Telefon</div>
                            <a href="tel:{{ $company->company_number }}" class="text-gray-900">
                                {{ $company->company_number }}
                            </a>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 mr-3">
                            🔢
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase">Organisationsnummer</div>
                            <div class="font-mono text-gray-900">{{ $company->company_org_number }}</div>
                        </div>
                    </div>

                    @if($company->site)
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 mr-3">
                                🌐
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 uppercase">Webbplats</div>
                                <a href="{{ $company->site }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $company->site }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Address Information -->
        @if($company->address || $company->city || $company->postal_code)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold">📍 Adress</h3>
                </div>
                <div class="card-body">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600 mr-3">
                            🏠
                        </div>
                        <div>
                            @if($company->address)
                                <div class="text-gray-900">{{ $company->address }}</div>
                            @endif
                            @if($company->postal_code || $company->city)
                                <div class="text-gray-600">
                                    {{ $company->postal_code }} {{ $company->city }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Services -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold">🛠️ Tjänster ({{ $company->services->count() }})</h3>
            </div>
            <div class="card-body">
                @if($company->services->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($company->services as $service)
                            <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                {{ $service->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Inga tjänster tillagda än</p>
                @endif
            </div>
        </div>

        <!-- Cities -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold">🏙️ Verksamhetsområden ({{ $company->cities->count() }})</h3>
            </div>
            <div class="card-body">
                @if($company->cities->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($company->cities as $city)
                            <span class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                {{ $city->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Inga städer tillagda än</p>
                @endif
            </div>
        </div>

        <!-- Slot Times -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold">⏰ Tidsluckor</h3>
            </div>
            <div class="card-body">
                @php
                    $companySlotTimes = \App\Models\SlotTime::where('company_id', $company->id)
                        ->with(['service', 'city'])
                        ->future()
                        ->orderBy('date')
                        ->orderBy('start_time')
                        ->limit(10)
                        ->get();
                @endphp
                
                @if($companySlotTimes->count() > 0)
                    <div class="space-y-3">
                        @foreach($companySlotTimes as $slotTime)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium">{{ $slotTime->service->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $slotTime->city->name }} | 
                                        {{ $slotTime->date->format('Y-m-d') }} | 
                                        {{ \Carbon\Carbon::parse($slotTime->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($slotTime->end_time)->format('H:i') }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium">
                                        {{ $slotTime->booked_count }}/{{ $slotTime->capacity }}
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $slotTime->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $slotTime->is_available ? 'Tillgänglig' : 'Otillgänglig' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($companySlotTimes->count() >= 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.slot-times.index', ['company_id' => $company->id]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Visa alla tidsluckor →
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500">Inga tidsluckor skapade än</p>
                    <div class="mt-3">
                        <a href="{{ route('admin.slot-times.create', ['company_id' => $company->id]) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Skapa första tidsluckan →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold">📅 Senaste Bokningar</h3>
            </div>
            <div class="card-body">
                @if($company->bookings->count() > 0)
                    <div class="space-y-3">
                        @foreach($company->bookings->take(5) as $booking)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium">{{ $booking->service_name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $booking->booking_date }} | {{ $booking->service_area }}
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if($company->bookings->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.bookings.index', ['company' => $company->id]) }}" class="text-blue-600 hover:underline text-sm">
                                Visa alla {{ $company->bookings->count() }} bokningar →
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 text-center py-6">Inga bokningar ännu</p>
                @endif
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="card">
            <div class="card-header bg-gradient-to-r from-yellow-600 to-orange-600 text-white">
                <h3 class="text-lg font-semibold">⭐ Recensioner ({{ $company->reviews->count() }})</h3>
            </div>
            <div class="card-body">
                @if($company->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($company->reviews->take(5) as $review)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">
                                                {{ $review->booking->user->name ?? 'Anonym' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $review->created_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $review->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $review->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $review->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </div>
                                
                                @if($review->review_text)
                                    <p class="text-gray-700 mb-3">{{ $review->review_text }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <div>
                                        <span class="font-medium">Bokning:</span> {{ $review->booking->service_name }}
                                    </div>
                                    <div class="flex space-x-2">
                                        @if($review->status === 'pending')
                                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 font-medium">
                                                    ✓ Godkänn
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                    ✗ Avvisa
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.reviews.show', $review) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                            Visa detaljer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($company->reviews->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.reviews.index', ['company' => $company->id]) }}" class="text-blue-600 hover:underline text-sm">
                                Visa alla {{ $company->reviews->count() }} recensioner →
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 text-center py-6">Inga recensioner ännu</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar (1/3) -->
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="card bg-gradient-to-br from-blue-50 to-purple-50">
            <div class="card-header">
                <h3 class="text-lg font-semibold">📊 Statistik</h3>
            </div>
            <div class="card-body space-y-4">
                <div class="text-center p-4 bg-white rounded-lg">
                    <div class="text-3xl font-bold text-blue-600">{{ $company->bookings->count() }}</div>
                    <div class="text-sm text-gray-600">Totala Bokningar</div>
                </div>
                <div class="text-center p-4 bg-white rounded-lg">
                    <div class="text-3xl font-bold text-green-600">{{ $company->review_count }}</div>
                    <div class="text-sm text-gray-600">Recensioner</div>
                </div>
                @if($company->review_count > 0)
                    <div class="text-center p-4 bg-white rounded-lg">
                        <div class="text-3xl font-bold text-yellow-500">⭐ {{ number_format($company->review_average, 1) }}</div>
                        <div class="text-sm text-gray-600">Genomsnittsbetyg</div>
                    </div>
                @endif
                <div class="text-center p-4 bg-white rounded-lg">
                    <div class="text-3xl font-bold text-purple-600">{{ $company->services->count() }}</div>
                    <div class="text-sm text-gray-600">Tjänster</div>
                </div>
                <div class="text-center p-4 bg-white rounded-lg">
                    <div class="text-3xl font-bold text-indigo-600">{{ $company->cities->count() }}</div>
                    <div class="text-sm text-gray-600">Städer</div>
                </div>
            </div>
        </div>

        <!-- Login Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold">🔐 Inloggningsinfo</h3>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <div class="text-xs text-gray-500 uppercase mb-1">Email</div>
                    <div class="text-sm">{{ $company->user->email }}</div>
                </div>
                @if($company->user->phone)
                    <div>
                        <div class="text-xs text-gray-500 uppercase mb-1">Telefon</div>
                        <div class="text-sm">{{ $company->user->phone }}</div>
                    </div>
                @endif
                <div>
                    <div class="text-xs text-gray-500 uppercase mb-1">Status</div>
                    <div class="text-sm">{{ ucfirst($company->user->status) }}</div>
                </div>
                <div class="pt-3 border-t">
                    <div class="text-xs text-gray-500 uppercase mb-1">Registrerad</div>
                    <div class="text-sm">{{ $company->created_at->format('Y-m-d H:i') }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase mb-1">Senast uppdaterad</div>
                    <div class="text-sm">{{ $company->updated_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Commission Settings -->
        <div class="card bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200">
            <div class="card-header bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg">
                <h3 class="text-lg font-semibold flex items-center">
                    <span class="mr-2">💰</span>
                    Provisionsinställningar
                </h3>
            </div>
            <div class="card-body">
                @if($company->commissionSetting)
                    <div class="space-y-4">
                        <div class="bg-white p-4 rounded-lg shadow-md border border-purple-100">
                            <div class="text-xs text-gray-500 uppercase mb-2 font-semibold tracking-wide">Typ</div>
                            <div class="text-sm font-semibold">
                                @if($company->commissionSetting->commission_type === 'percentage')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">📊 Procent</span>
                                @else
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-bold">💵 Fast belopp</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-md border border-purple-100">
                            <div class="text-xs text-gray-500 uppercase mb-2 font-semibold tracking-wide">Provision</div>
                            <div class="text-3xl font-bold text-purple-600">
                                @if($company->commissionSetting->commission_type === 'percentage')
                                    {{ number_format($company->commissionSetting->commission_rate, 2) }}%
                                @else
                                    {{ number_format($company->commissionSetting->fixed_amount, 2) }} kr
                                @endif
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-md border border-purple-100">
                            <div class="text-xs text-gray-500 uppercase mb-2 font-semibold tracking-wide">Status</div>
                            <div class="text-sm">
                                @if($company->commissionSetting->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">✅ Aktiv</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold">⏸️ Inaktiv</span>
                                @endif
                            </div>
                        </div>
                        @if($company->commissionSetting->notes)
                            <div class="bg-white p-4 rounded-lg shadow-md border border-purple-100">
                                <div class="text-xs text-gray-500 uppercase mb-2 font-semibold tracking-wide">📝 Anteckningar</div>
                                <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded border-l-4 border-purple-300">{{ $company->commissionSetting->notes }}</div>
                            </div>
                        @endif
                        <a href="{{ route('admin.commissions.edit', $company->commissionSetting) }}" class="flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <span class="mr-2">✏️</span>
                            Redigera Provision
                        </a>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="text-4xl mb-3">💰</div>
                        <p class="text-gray-600 mb-4">Ingen provisionsinställning</p>
                        <a href="{{ route('admin.commissions.create') }}?company_id={{ $company->id }}" class="btn btn-primary">
                            ➕ Lägg till Provision
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Deposit Info -->
        @if($company->deposit_method)
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold">💰 Depositsinfo</h3>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <div class="text-xs text-gray-500 uppercase mb-1">Metod</div>
                    <div class="text-sm font-semibold">
                        @if($company->deposit_method === 'swish')
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">Swish</span>
                        @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Bankkonto</span>
                        @endif
                    </div>
                </div>
                @if($company->deposit_method === 'swish' && $company->swish_number)
                    <div>
                        <div class="text-xs text-gray-500 uppercase mb-1">Swish-nummer</div>
                        <div class="text-sm">{{ $company->swish_number }}</div>
                    </div>
                @endif
                @if($company->deposit_method === 'bank_account')
                    @if($company->bank_name)
                        <div>
                            <div class="text-xs text-gray-500 uppercase mb-1">Bank</div>
                            <div class="text-sm">{{ $company->bank_name }}</div>
                        </div>
                    @endif
                    @if($company->clearing_number)
                        <div>
                            <div class="text-xs text-gray-500 uppercase mb-1">Clearingnummer</div>
                            <div class="text-sm font-mono">{{ $company->clearing_number }}</div>
                        </div>
                    @endif
                    @if($company->account_number)
                        <div>
                            <div class="text-xs text-gray-500 uppercase mb-1">Kontonummer</div>
                            <div class="text-sm font-mono">{{ $company->account_number }}</div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card bg-gradient-to-br from-green-50 to-blue-50">
            <div class="card-header bg-gradient-to-r from-green-600 to-blue-600 text-white">
                <h3 class="text-lg font-semibold">⚡ Snabbåtgärder</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.companies.edit', $company) }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <span class="mr-2">✏️</span>
                        Redigera Företag
                    </a>
                    <a href="{{ route('public.company.show', $company) }}" target="_blank" class="flex items-center justify-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <span class="mr-2">👁️</span>
                        Visa Publikt
                    </a>
                    <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Är du säker på att du vill radera detta företag?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <span class="mr-2">🗑️</span>
                            Radera Företag
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Chat Messages (Disabled) -->
<div class="mt-6">
    <div class="card">
        <div class="card-header bg-gradient-to-r from-gray-500 to-gray-600 text-white">
            <h3 class="text-lg font-semibold">💬 Chattmeddelanden (Inaktiverat)</h3>
        </div>
        <div class="card-body">
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-lg font-medium text-gray-700 mb-2">Chattfunktionen har inaktiverats</p>
                <p class="text-sm text-gray-500">Chattfunktionaliteten har tagits bort från systemet. Använd bokningshantering och meddelanden istället.</p>
            </div>
        </div>
    </div>
</div>
@endsection
