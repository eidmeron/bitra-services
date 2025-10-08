@extends('layouts.public')

@section('title', $form->form_name . ' - Förhandsvisning')

@section('content')
<div class="min-h-screen py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Preview Header -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <span class="text-2xl mr-3">👁️</span>
                <div>
                    <h2 class="font-semibold text-yellow-900">Förhandsvisning - Detta formulär är inte aktivt</h2>
                    <p class="text-sm text-yellow-800">Detta är hur formuläret kommer att se ut för dina kunder.</p>
                </div>
            </div>
        </div>

        <!-- Form Preview -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $form->form_name }}</h1>
            <p class="text-gray-600 mb-8">{{ $form->service->name }}</p>

            <form class="space-y-6" x-data="priceCalculator({{ $form->service_id }}, null)" @submit.prevent>
                <!-- Dynamic Form Fields -->
                @foreach($form->fields as $field)
                    <div style="width: {{ $field->field_width }}%" class="inline-block align-top px-2">
                        <label class="form-label">
                            {{ $field->field_label }}
                            @if($field->required)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if($field->field_type === 'text')
                            <input 
                                type="text" 
                                placeholder="{{ $field->placeholder_text }}"
                                class="form-input"
                                {{ $field->required ? 'required' : '' }}
                            >
                        @elseif($field->field_type === 'email')
                            <input 
                                type="email" 
                                placeholder="{{ $field->placeholder_text }}"
                                class="form-input"
                                {{ $field->required ? 'required' : '' }}
                            >
                        @elseif($field->field_type === 'phone')
                            <input 
                                type="tel" 
                                placeholder="{{ $field->placeholder_text }}"
                                class="form-input"
                                {{ $field->required ? 'required' : '' }}
                            >
                        @elseif($field->field_type === 'textarea')
                            <textarea 
                                rows="4"
                                placeholder="{{ $field->placeholder_text }}"
                                class="form-input"
                                {{ $field->required ? 'required' : '' }}
                            ></textarea>
                        @elseif($field->field_type === 'number')
                            <input 
                                type="number" 
                                placeholder="{{ $field->placeholder_text }}"
                                class="form-input"
                                {{ $field->required ? 'required' : '' }}
                                @change="updateField('{{ $field->field_name }}', $event.target.value)"
                            >
                        @elseif($field->field_type === 'select' && $field->field_options)
                            <select 
                                class="form-input"
                                {{ $field->required ? 'required' : '' }}
                                @change="updateField('{{ $field->field_name }}', $event.target.value)"
                            >
                                <option value="">Välj...</option>
                                @foreach($field->field_options as $option)
                                    <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                        @elseif($field->field_type === 'radio' && $field->field_options)
                            <div class="space-y-2">
                                @foreach($field->field_options as $option)
                                    <label class="flex items-center">
                                        <input 
                                            type="radio" 
                                            name="{{ $field->field_name }}"
                                            value="{{ $option['value'] }}"
                                            class="mr-2"
                                            {{ $field->required ? 'required' : '' }}
                                        >
                                        <span>{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($field->field_type === 'checkbox' && $field->field_options)
                            <div class="space-y-2">
                                @foreach($field->field_options as $option)
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            name="{{ $field->field_name }}[]"
                                            value="{{ $option['value'] }}"
                                            class="mr-2"
                                        >
                                        <span>{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($field->field_type === 'date')
                            <input 
                                type="date" 
                                class="form-input"
                                {{ $field->required ? 'required' : '' }}
                            >
                        @elseif($field->field_type === 'time')
                            <input 
                                type="time" 
                                class="form-input"
                                {{ $field->required ? 'required' : '' }}
                            >
                        @elseif($field->field_type === 'slider')
                            <input 
                                type="range" 
                                min="0"
                                max="100"
                                class="w-full"
                                @change="updateField('{{ $field->field_name }}', $event.target.value)"
                            >
                        @elseif($field->field_type === 'divider')
                            <hr class="my-4">
                        @endif

                        @if($field->help_text)
                            <p class="text-sm text-gray-500 mt-1">{{ $field->help_text }}</p>
                        @endif
                    </div>
                @endforeach

                <div class="clear-both"></div>

                <!-- City Selection -->
                <div class="mt-8 pt-6 border-t">
                    <label class="form-label">Stad <span class="text-red-500">*</span></label>
                    <select 
                        class="form-input"
                        required
                        @change="updateCity($event.target.value)"
                    >
                        <option value="">Välj stad</option>
                        @foreach($form->service->cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- ROT-avdrag -->
                @if($form->service->rot_eligible)
                    <div>
                        <label class="flex items-center">
                            <input 
                                type="checkbox"
                                @change="toggleRot()"
                                class="mr-2"
                            >
                            <span class="text-sm font-medium text-gray-700">Använd ROT-avdrag ({{ $form->service->rot_percent }}%)</span>
                        </label>
                    </div>
                @endif

                <!-- Price Breakdown -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
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
                </div>

                <!-- Contact Information -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Kontaktinformation</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Namn <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input" required>
                        </div>

                        <div>
                            <label class="form-label">Telefon <span class="text-red-500">*</span></label>
                            <input type="tel" class="form-input" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="form-label">E-post <span class="text-red-500">*</span></label>
                            <input type="email" class="form-input" required>
                        </div>
                    </div>
                </div>

                <!-- Preview Notice -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <strong>OBS:</strong> Detta är en förhandsvisning. Formuläret kan inte skickas in härifrån.
                    </p>
                </div>

                <!-- Submit Button (Disabled) -->
                <button type="button" class="w-full btn btn-primary opacity-50 cursor-not-allowed" disabled>
                    Boka tjänst (inaktiverad i förhandsvisning)
                </button>
            </form>
        </div>

        <!-- Back to Edit -->
        <div class="mt-6 text-center">
            <a href="{{ route('admin.forms.edit', $form) }}" class="btn btn-secondary">
                ← Tillbaka till redigering
            </a>
        </div>
    </div>
</div>
@endsection

