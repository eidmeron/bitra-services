@extends('layouts.admin')

@section('title', 'Bokningsdetaljer')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till bokningar</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Booking Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold">{{ $booking->booking_number }}</h3>
                    <p class="text-gray-600">Skapad {{ $booking->created_at->format('Y-m-d H:i') }}</p>
                </div>
                {!! bookingStatusBadge($booking->status) !!}
            </div>

            <div class="border-t pt-4">
                <h4 class="font-semibold mb-2">Tjänst</h4>
                <p class="text-lg">{{ $booking->service->name }}</p>
                <p class="text-sm text-gray-600">{{ $booking->city->name }}</p>
            </div>

            <!-- Date and Time Information -->
            @if($booking->slotTime)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-2">📅 Datum och Tid</h4>
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-semibold">Datum</label>
                                <p class="text-lg font-bold text-blue-900">
                                    {{ $booking->slotTime->date->format('Y-m-d') }} 
                                    ({{ $booking->slotTime->date->format('l') }})
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-semibold">Tid</label>
                                <p class="text-lg font-bold text-blue-900">
                                    {{ \Carbon\Carbon::parse($booking->slotTime->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($booking->slotTime->end_time)->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($booking->preferred_date)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-2">📅 Önskat Datum</h4>
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 p-4 rounded-lg">
                        <p class="text-lg font-bold text-orange-900">
                            {{ $booking->preferred_date->format('Y-m-d H:i') }}
                        </p>
                        <p class="text-sm text-orange-700 mt-1">
                            Ingen specifik tidslucka vald
                        </p>
                    </div>
                </div>
            @endif

            <div class="border-t pt-4 mt-4">
                <h4 class="font-semibold mb-2">Kunduppgifter</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Namn</label>
                        <p>{{ $booking->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">E-post</label>
                        <p>{{ $booking->customer_email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Telefon</label>
                        <p>{{ $booking->customer_phone }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Kundtyp</label>
                        <p>
                            @if($booking->customer_type === 'company')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    🏢 Företag
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    🏠 Privatperson
                                </span>
                            @endif
                        </p>
                    </div>
                    @if($booking->customer_type === 'company' && $booking->org_number)
                        <div>
                            <label class="text-sm text-gray-600">Organisationsnummer</label>
                            <p class="font-mono">{{ $booking->org_number }}</p>
                        </div>
                    @endif
                    @if($booking->customer_type === 'private' && $booking->personnummer)
                        <div>
                            <label class="text-sm text-gray-600">Personnummer (ROT)</label>
                            <p class="font-mono">{{ $booking->personnummer }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-sm text-gray-600">Bokningstyp</label>
                        <p>{{ $booking->booking_type === 'one_time' ? 'En gång' : 'Prenumeration' }}</p>
                    </div>
                </div>

                @if($booking->customer_message)
                    <div class="mt-4">
                        <label class="text-sm text-gray-600">Meddelande</label>
                        <p class="bg-gray-50 p-3 rounded mt-1">{{ $booking->customer_message }}</p>
                    </div>
                @endif
            </div>

            @if($booking->form_data && is_array($booking->form_data) && count($booking->form_data) > 0)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold mb-4 text-lg">📋 Bokningsdetaljer</h4>
                    
                    <!-- Booking Type & Subscription -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 p-4 rounded-lg mb-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-semibold">Bokningstyp</label>
                                <p class="text-lg font-bold text-blue-900">
                                    @if($booking->booking_type === 'one_time')
                                        📅 Engångsbokning
                                    @else
                                        🔄 Prenumeration
                                    @endif
                                </p>
                            </div>
                            
                            @if($booking->booking_type === 'subscription' && $booking->subscription_frequency)
                                <div>
                                    <label class="text-xs text-gray-600 uppercase font-semibold">Frekvens</label>
                                    <p class="text-lg font-bold text-purple-900">
                                        {{ getSubscriptionFrequencyLabel($booking->subscription_frequency) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-4 rounded-lg mb-4">
                        <h5 class="font-semibold text-green-900 mb-3">💰 Prissammanfattning</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">Grundpris:</span>
                                <span class="font-semibold">{{ number_format($booking->base_price, 2, ',', ' ') }} kr</span>
                            </div>
                            
                            @if($booking->variable_additions > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">Tillägg från formulär:</span>
                                    <span class="font-semibold text-blue-600">+{{ number_format($booking->variable_additions, 2, ',', ' ') }} kr</span>
                                </div>
                            @endif

                            @if($booking->subscription_multiplier && $booking->subscription_multiplier != 1.00)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">Prenumerationsmultiplikator:</span>
                                    <span class="font-semibold text-purple-600">×{{ $booking->subscription_multiplier }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">Stadsmultiplikator ({{ $booking->city->name }}):</span>
                                <span class="font-semibold">×{{ $booking->city_multiplier }}</span>
                            </div>
                            
                            @if($booking->discount_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">🎁 Tjänsterabatt:</span>
                                    <span class="font-semibold text-green-600">-{{ number_format($booking->discount_amount, 2, ',', ' ') }} kr</span>
                                </div>
                            @endif
                            
                            @if($booking->rot_deduction > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">💚 ROT-avdrag:</span>
                                    <span class="font-semibold text-green-600">-{{ number_format($booking->rot_deduction, 2, ',', ' ') }} kr</span>
                                </div>
                            @endif
                            
                            @php
                                $taxRate = $booking->tax_rate ?? $booking->service->tax_rate ?? 25;
                                $totalWithVAT = $booking->final_price;
                                $baseAmount = $totalWithVAT / (1 + ($taxRate / 100));
                                $vatAmount = $totalWithVAT - $baseAmount;
                            @endphp
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">Delsumma (exkl. moms):</span>
                                <span class="font-semibold">{{ number_format($baseAmount, 2, ',', ' ') }} kr</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700">Moms ({{ number_format($taxRate, 2, ',', ' ') }}%):</span>
                                <span class="font-semibold">{{ number_format($vatAmount, 2, ',', ' ') }} kr</span>
                            </div>

                            @if($booking->total_extra_fees > 0)
                                <div class="flex justify-between text-sm text-blue-600">
                                    <span>Extra avgifter:</span>
                                    <span class="font-semibold">+{{ number_format($booking->total_extra_fees, 2, ',', ' ') }} kr</span>
                                </div>
                            @endif

                            <div class="border-t-2 border-green-300 pt-2 mt-2">
                                <div class="flex justify-between">
                                    <span class="text-lg font-bold text-green-900">Totalt (inkl. moms):</span>
                                    <span class="text-xl font-bold text-green-900">{{ number_format($booking->total_with_extra_fees, 2, ',', ' ') }} kr</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields Data -->
                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg">
                        <h5 class="font-semibold text-gray-900 mb-3">📝 Formulärfält</h5>
                        <div class="space-y-3">
                            @foreach($booking->form_data as $key => $value)
                                @php
                                    // Try to find the field label from the form
                                    $field = $booking->form?->fields->where('field_name', $key)->first();
                                    $label = $field?->field_label ?? ucfirst(str_replace('_', ' ', $key));
                                @endphp
                                <div class="flex flex-col sm:flex-row sm:justify-between py-2 border-b last:border-b-0">
                                    <span class="text-gray-600 font-medium mb-1 sm:mb-0">{{ $label }}:</span>
                                    <span class="font-semibold text-gray-900">
                                        @if(is_array($value))
                                            <span class="inline-flex flex-wrap gap-1">
                                                @foreach($value as $item)
                                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $item }}</span>
                                                @endforeach
                                            </span>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($booking->preferred_date || $booking->preferred_time)
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mt-4">
                            <h5 class="font-semibold text-yellow-900 mb-3">📅 Önskat datum & tid</h5>
                            <div class="space-y-2">
                                @if($booking->preferred_date)
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">Datum:</span>
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->preferred_date)->format('Y-m-d') }}</span>
                                    </div>
                                @endif
                                @if($booking->preferred_time)
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">Tid:</span>
                                        <span class="font-semibold">{{ $booking->preferred_time }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Assign to Company -->
        @if($booking->status === 'pending' && $availableCompanies->count() > 0)
            <div class="card bg-yellow-50">
                <h4 class="font-semibold mb-4">Tilldela till företag</h4>
                <form method="POST" action="{{ route('admin.bookings.assign', $booking) }}">
                    @csrf
                    <div class="mb-4">
                        <select name="company_id" class="form-input" required>
                            <option value="">Välj företag...</option>
                            @foreach($availableCompanies as $company)
                                <option value="{{ $company->id }}">
                                    {{ $company->user->email }} - ⭐ {{ number_format($company->review_average, 1) }} ({{ $company->review_count }} recensioner)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tilldela bokning</button>
                </form>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Price Breakdown -->
        <div class="card bg-blue-50">
            <h4 class="font-semibold mb-4">Prisberäkning</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span>Grundpris:</span>
                    <span>{{ number_format($booking->base_price, 2, ',', ' ') }} kr</span>
                </div>
                @if($booking->variable_additions > 0)
                    <div class="flex justify-between">
                        <span>Tillägg:</span>
                        <span>{{ number_format($booking->variable_additions, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Stadsmultiplikator:</span>
                    <span>×{{ $booking->city_multiplier }}</span>
                </div>
                @if($booking->rot_deduction > 0)
                    <div class="flex justify-between text-green-600">
                        <span>ROT-avdrag:</span>
                        <span>-{{ number_format($booking->rot_deduction, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                @if($booking->discount_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Rabatt:</span>
                        <span>-{{ number_format($booking->discount_amount, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                @php
                    $taxRate = $booking->tax_rate ?? $booking->service->tax_rate ?? 25;
                    $totalWithVAT = $booking->final_price;
                    $baseAmount = $totalWithVAT / (1 + ($taxRate / 100));
                    $vatAmount = $totalWithVAT - $baseAmount;
                @endphp
                
                <div class="flex justify-between">
                    <span>Delsumma (exkl. moms):</span>
                    <span>{{ number_format($baseAmount, 2, ',', ' ') }} kr</span>
                </div>
                <div class="flex justify-between">
                    <span>Moms ({{ number_format($taxRate, 2, ',', ' ') }}%):</span>
                    <span>{{ number_format($vatAmount, 2, ',', ' ') }} kr</span>
                </div>
                @if($booking->total_extra_fees > 0)
                    <div class="flex justify-between text-blue-600">
                        <span>Extra avgifter:</span>
                        <span>+{{ number_format($booking->total_extra_fees, 2, ',', ' ') }} kr</span>
                    </div>
                @endif
                <div class="border-t pt-2 mt-2 flex justify-between font-bold text-lg">
                    <span>Totalt (inkl. moms):</span>
                    <span>{{ number_format($booking->total_with_extra_fees, 2, ',', ' ') }} kr</span>
                </div>
            </div>
        </div>

        <!-- Company Info -->
        @if($booking->company)
            <div class="card">
                <h4 class="font-semibold mb-2">Tilldelat företag</h4>
                <p class="font-medium">{{ $booking->company->user->email }}</p>
                <p class="text-sm text-gray-600">Org.nr: {{ $booking->company->company_org_number }}</p>
                @if($booking->company->review_average > 0)
                    <p class="text-sm mt-2">
                        {!! reviewStars((int)round($booking->company->review_average)) !!}
                        <span class="text-gray-600">({{ $booking->company->review_count }} recensioner)</span>
                    </p>
                @endif
            </div>
        @endif

        <!-- Timeline -->
        <div class="card">
            <h4 class="font-semibold mb-4">Tidslinje</h4>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">✓</div>
                    <div>
                        <p class="font-medium">Bokning skapad</p>
                        <p class="text-sm text-gray-600">{{ $booking->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @if($booking->assigned_at)
                    <div class="flex items-start">
                        <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">✓</div>
                        <div>
                            <p class="font-medium">Tilldelad till företag</p>
                            <p class="text-sm text-gray-600">{{ $booking->assigned_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                @endif
                @if($booking->completed_at)
                    <div class="flex items-start">
                        <div class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3">✓</div>
                        <div>
                            <p class="font-medium">Slutförd</p>
                            <p class="text-sm text-gray-600">{{ $booking->completed_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Send Message -->
        <div class="card">
            <h4 class="font-semibold mb-3">💬 Skicka meddelande</h4>
            <form method="POST" action="{{ route('admin.bookings.send-message', $booking) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ämne</label>
                    <input type="text" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Meddelande om bokning #{{ $booking->booking_number }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meddelande</label>
                    <textarea name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Skriv ditt meddelande till kunden..." required></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="send_email" id="send_email" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                    <label for="send_email" class="ml-2 text-sm text-gray-700">Skicka även som e-post</label>
                </div>
                <button type="submit" class="w-full btn btn-primary">
                    📧 Skicka meddelande
                </button>
            </form>
        </div>

        <!-- Actions -->
        <div class="card">
            <h4 class="font-semibold mb-4">Åtgärder</h4>
            <div class="space-y-2">
                <a href="{{ route('booking.pdf.download', $booking) }}" class="w-full btn btn-primary flex items-center justify-center">
                    📄 Ladda ner PDF
                </a>
                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Är du säker på att du vill radera denna bokning?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full btn btn-danger">
                        Radera bokning
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

