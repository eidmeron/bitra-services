@extends('layouts.public')

@section('title', $form->form_name)

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $form->form_name }}</h1>
            <p class="text-gray-600 mb-8">{{ $form->service->name }}</p>

            <form 
                method="POST" 
                action="{{ route('booking.submit', $form->public_token) }}"
                x-data="priceCalculator({{ $form->service_id }}, null)"
                @submit="loading = true"
            >
                @csrf

                <input type="hidden" name="service_id" value="{{ $form->service_id }}">
                <input type="hidden" name="form_id" value="{{ $form->id }}">

                <!-- Dynamic Form Fields from Schema -->
                <div class="space-y-6 mb-8">
                    @if(is_array($form->form_schema))
                        @foreach($form->form_schema as $field)
                            <div style="width: {{ $field['width'] ?? '100' }}%">
                                @if($field['type'] === 'text')
                                    <label class="form-label">{{ $field['label'] }}@if($field['required'] ?? false)<span class="text-red-500">*</span>@endif</label>
                                    <input 
                                        type="text" 
                                        name="form_data[{{ $field['name'] }}]"
                                        placeholder="{{ $field['placeholder'] ?? '' }}"
                                        class="form-input"
                                        @if($field['required'] ?? false) required @endif
                                        @change="updateField('{{ $field['name'] }}', $event.target.value)"
                                    >
                                    @if($field['helpText'] ?? false)
                                        <p class="text-sm text-gray-500 mt-1">{{ $field['helpText'] }}</p>
                                    @endif
                                @elseif($field['type'] === 'number')
                                    <label class="form-label">{{ $field['label'] }}@if($field['required'] ?? false)<span class="text-red-500">*</span>@endif</label>
                                    <input 
                                        type="number" 
                                        name="form_data[{{ $field['name'] }}]"
                                        placeholder="{{ $field['placeholder'] ?? '' }}"
                                        class="form-input"
                                        @if($field['required'] ?? false) required @endif
                                        @change="updateField('{{ $field['name'] }}', $event.target.value)"
                                    >
                                    @if($field['helpText'] ?? false)
                                        <p class="text-sm text-gray-500 mt-1">{{ $field['helpText'] }}</p>
                                    @endif
                                @elseif($field['type'] === 'select' && isset($field['options']))
                                    <label class="form-label">{{ $field['label'] }}@if($field['required'] ?? false)<span class="text-red-500">*</span>@endif</label>
                                    <select 
                                        name="form_data[{{ $field['name'] }}]"
                                        class="form-input"
                                        @if($field['required'] ?? false) required @endif
                                        @change="updateField('{{ $field['name'] }}', $event.target.value)"
                                    >
                                        <option value="">Välj...</option>
                                        @foreach($field['options'] as $option)
                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- City Selection -->
                <div class="mb-6">
                    <label class="form-label">Stad <span class="text-red-500">*</span></label>
                    <select 
                        name="city_id" 
                        required
                        @change="updateCity($event.target.value)"
                        class="form-input"
                    >
                        <option value="">Välj stad</option>
                        @foreach($form->service->cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Booking Type -->
                <div class="mb-6">
                    <label class="form-label">Bokningstyp <span class="text-red-500">*</span></label>
                    <div class="space-y-2">
                        @if($form->service->one_time_booking)
                            <label class="flex items-center">
                                <input type="radio" name="booking_type" value="one_time" checked class="mr-2">
                                <span>En gång</span>
                            </label>
                        @endif

                        @if($form->service->subscription_booking)
                            <label class="flex items-center">
                                <input type="radio" name="booking_type" value="subscription" class="mr-2">
                                <span>Prenumeration</span>
                            </label>
                        @endif
                    </div>
                </div>

                <!-- ROT-avdrag -->
                @if($form->service->rot_eligible)
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="apply_rot" 
                                value="1"
                                @change="toggleRot()"
                                class="mr-2"
                            >
                            <span class="text-sm font-medium text-gray-700">Använd ROT-avdrag ({{ $form->service->rot_percent }}%)</span>
                        </label>
                    </div>
                @endif

                <!-- Price Breakdown -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Prisberäkning</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Grundpris:</span>
                            <span x-text="formatPrice(priceBreakdown.base_price)"></span>
                        </div>

                        <div x-show="priceBreakdown.variable_additions > 0" class="flex justify-between">
                            <span>Tillägg:</span>
                            <span x-text="formatPrice(priceBreakdown.variable_additions)"></span>
                        </div>

                        <div x-show="priceBreakdown.city_multiplier !== 1" class="flex justify-between">
                            <span>Stadsmultiplikator:</span>
                            <span x-text="'×' + priceBreakdown.city_multiplier"></span>
                        </div>

                        <div x-show="priceBreakdown.rot_deduction > 0" class="flex justify-between text-green-600">
                            <span>ROT-avdrag:</span>
                            <span x-text="'-' + formatPrice(priceBreakdown.rot_deduction)"></span>
                        </div>

                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Totalt pris:</span>
                                <span x-text="formatPrice(priceBreakdown.final_price)"></span>
                            </div>
                        </div>
                    </div>

                    <div x-show="loading" class="text-center mt-4">
                        <span class="text-gray-500">Beräknar pris...</span>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Kontaktinformation</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Namn <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" required class="form-input">
                        </div>

                        <div>
                            <label class="form-label">Telefon <span class="text-red-500">*</span></label>
                            <input type="tel" name="customer_phone" required class="form-input">
                        </div>

                        <div class="md:col-span-2">
                            <label class="form-label">E-post <span class="text-red-500">*</span></label>
                            <input type="email" name="customer_email" required class="form-input">
                        </div>

                        <div class="md:col-span-2">
                            <label class="form-label">Meddelande (valfritt)</label>
                            <textarea name="customer_message" rows="4" class="form-input"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" name="accept_terms" required class="mr-2 mt-1">
                        <span class="text-sm text-gray-700">
                            Jag accepterar användarvillkoren och integritetspolicyn <span class="text-red-500">*</span>
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full btn btn-primary text-lg py-3">
                    Boka tjänst
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

