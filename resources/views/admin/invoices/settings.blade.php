@extends('layouts.admin')

@section('title', 'Fakturainställningar')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">⚙️</span>
                    Fakturainställningar
                </h1>
                <p class="text-gray-600 mt-2">Konfigurera svenska fakturainställningar och betalningsinformation</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.invoices.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ← Tillbaka till Fakturor
                </a>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">🏢</span>
                Företagsinformation & Betalningsinställningar
            </h3>
        </div>

        <form method="POST" action="{{ route('admin.invoices.settings.update') }}" class="p-6 space-y-6">
            @csrf

            <!-- Company Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Företagsnamn <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="company_name" 
                           name="company_name" 
                           value="{{ old('company_name', $settings->company_name ?? 'Bitra Services AB') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('company_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company_org_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Organisationsnummer <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="company_org_number" 
                           name="company_org_number" 
                           value="{{ old('company_org_number', $settings->company_org_number ?? '556123-4567') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('company_org_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="company_address" class="block text-sm font-medium text-gray-700 mb-2">
                    Företagsadress <span class="text-red-500">*</span>
                </label>
                <textarea id="company_address" 
                          name="company_address" 
                          rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          required>{{ old('company_address', $settings->company_address ?? 'Storgatan 123, 123 45 Stockholm') }}</textarea>
                @error('company_address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Telefonnummer <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="company_phone" 
                           name="company_phone" 
                           value="{{ old('company_phone', $settings->company_phone ?? '+46 8 123 456 78') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('company_phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-postadress <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="company_email" 
                           name="company_email" 
                           value="{{ old('company_email', $settings->company_email ?? 'faktura@bitra.se') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('company_email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Payment Information -->
            <div class="border-t pt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="text-xl mr-2">💳</span>
                    Betalningsinformation
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bankgiro_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Bankgiro-nummer <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="bankgiro_number" 
                               name="bankgiro_number" 
                               value="{{ old('bankgiro_number', $settings->bankgiro_number ?? '123-4567') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('bankgiro_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_due_days" class="block text-sm font-medium text-gray-700 mb-2">
                            Betalningstid (dagar) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="payment_due_days" 
                               name="payment_due_days" 
                               value="{{ old('payment_due_days', $settings->payment_due_days ?? 30) }}"
                               min="1" 
                               max="365"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('payment_due_days')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Invoice Settings -->
            <div class="border-t pt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="text-xl mr-2">📋</span>
                    Fakturainställningar
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="invoice_prefix" class="block text-sm font-medium text-gray-700 mb-2">
                            Fakturaprefix <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="invoice_prefix" 
                               name="invoice_prefix" 
                               value="{{ old('invoice_prefix', $settings->invoice_prefix ?? 'INV') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                        @error('invoice_prefix')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Exempel: INV-2025-000001</p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="auto_send_invoices" 
                                   name="auto_send_invoices" 
                                   value="1"
                                   {{ old('auto_send_invoices', $settings->auto_send_invoices ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="auto_send_invoices" class="ml-2 text-sm text-gray-700">
                                Skicka fakturor automatiskt
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="include_booking_details" 
                                   name="include_booking_details" 
                                   value="1"
                                   {{ old('include_booking_details', $settings->include_booking_details ?? true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="include_booking_details" class="ml-2 text-sm text-gray-700">
                                Inkludera bokningsdetaljer
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="border-t pt-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <span class="text-xl mr-2">📝</span>
                    Ytterligare Information
                </h4>

                <div class="space-y-4">
                    <div>
                        <label for="payment_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Betalningsinstruktioner
                        </label>
                        <textarea id="payment_instructions" 
                                  name="payment_instructions" 
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Exempel: Betala via bankgiro eller Swish. Ange fakturanummer som meddelande.">{{ old('payment_instructions', $settings->payment_instructions ?? 'Betala via bankgiro eller Swish. Ange fakturanummer som meddelande.') }}</textarea>
                        @error('payment_instructions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="invoice_footer_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Fakturatext (sidfot)
                        </label>
                        <textarea id="invoice_footer_text" 
                                  name="invoice_footer_text" 
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Exempel: Tack för ditt förtroende! Kontakta oss om du har frågor.">{{ old('invoice_footer_text', $settings->invoice_footer_text ?? 'Tack för ditt förtroende! Kontakta oss om du har frågor.') }}</textarea>
                        @error('invoice_footer_text')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t pt-6">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.invoices.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Avbryt
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        Spara Inställningar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Svenska Fakturastandarder</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• Bankgiro-nummer: Format 123-4567 (svenska standard)</p>
                    <p>• Betalningstid: 30 dagar är standard i Sverige</p>
                    <p>• Moms: 25% inkluderad i alla fakturor</p>
                    <p>• Fakturanummer: Automatisk numrering med prefix</p>
                    <p>• E-postutskick: Automatisk leverans till företag</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
