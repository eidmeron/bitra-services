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

            <form 
                class="space-y-6" 
                x-data="{ 
                    ...priceCalculator({{ $form->service_id }}, null, {{ $form->id }}),
                    formData: {},
                    conditionalFields: @js($form->fields->mapWithKeys(function($field) {
                        return [$field->field_name . '_' . $field->id => $field->conditional_logic];
                    })->toArray()),
                    fieldNameMap: @js($form->fields->pluck('id', 'field_name')->toArray()),
                    
                    isFieldVisible(fieldName, fieldId) {
                        const logic = this.conditionalFields[fieldName + '_' + fieldId];
                        if (!logic || !logic.rules || logic.rules.length === 0) {
                            return true;
                        }
                        
                        const operator = logic.operator || 'and';
                        let results = [];
                        
                        logic.rules.forEach(rule => {
                            const fieldValue = this.formData[rule.field] || '';
                            const compareValue = rule.value || '';
                            const condition = rule.condition || rule.operator || 'equals';
                            let result = false;
                            
                            console.log('🔍 Evaluating:', {
                                field: rule.field, 
                                fieldValue, 
                                condition, 
                                compareValue,
                                formData: this.formData
                            });
                            
                            switch(condition) {
                                case 'equals':
                                    result = fieldValue == compareValue;
                                    break;
                                case 'not_equals':
                                    result = fieldValue != compareValue;
                                    break;
                                case 'contains':
                                    // Check both VALUE and if it might match an option LABEL
                                    const valueContains = String(fieldValue).toLowerCase().includes(compareValue.toLowerCase());
                                    result = valueContains;
                                    console.log('  Contains check:', {fieldValue, compareValue, valueContains, result});
                                    break;
                                case 'not_contains':
                                    result = !String(fieldValue).toLowerCase().includes(compareValue.toLowerCase());
                                    break;
                                case 'greater_than':
                                    result = Number(fieldValue) > Number(compareValue);
                                    break;
                                case 'less_than':
                                    result = Number(fieldValue) < Number(compareValue);
                                    break;
                                case 'is_empty':
                                    result = !fieldValue || fieldValue === '';
                                    break;
                                case 'is_not_empty':
                                    result = fieldValue && fieldValue !== '';
                                    break;
                            }
                            
                            console.log('  → Result:', result ? '✅ TRUE' : '❌ FALSE');
                            results.push(result);
                        });
                        
                        const finalResult = operator === 'and' ? results.every(r => r) : results.some(r => r);
                        console.log('👁️ Field visible:', fieldName + '_' + fieldId, '→', finalResult ? '✅ SHOW' : '❌ HIDE');
                        return finalResult;
                    },
                    
                    updateFieldValue(fieldName, value) {
                        this.formData[fieldName] = value;
                        this.updateField(fieldName, value);
                    },
                    
                    updateBookingType(type, frequency) {
                        this.updateSubscriptionType(type, frequency);
                    }
                }"
                @submit.prevent
            >
                <!-- 1. City Selection (FIRST) -->
                <div class="mb-6">
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

                <!-- 2. Booking Type (SECOND) -->
                @php
                    $defaultBookingType = $form->service->one_time_booking ? 'one_time' : 'subscription';
                    $defaultSubscriptionFreq = $form->service->subscription_types[0] ?? 'weekly';
                    $bookingTypesCount = ($form->service->one_time_booking ? 1 : 0) + ($form->service->subscription_booking ? 1 : 0);
                @endphp
                <div class="mb-6" x-data="{ bookingType: '{{ $defaultBookingType }}', subscriptionFrequency: '{{ $defaultSubscriptionFreq }}' }">
                    <label class="form-label">Bokningstyp <span class="text-red-500">*</span></label>
                    
                    <!-- Booking Type Icons -->
                    <div class="grid grid-cols-{{ $bookingTypesCount }} gap-4 mb-4">
                        @if($form->service->one_time_booking)
                            <label 
                                class="relative cursor-pointer"
                                :class="bookingType === 'one_time' ? 'ring-2 ring-blue-500' : ''"
                            >
                                <input 
                                    type="radio" 
                                    name="booking_type" 
                                    value="one_time" 
                                    {{ $form->service->one_time_booking ? 'checked' : '' }}
                                    class="hidden"
                                    x-model="bookingType"
                                    @change="updateBookingType('one_time', null)"
                                >
                                <div class="border-2 rounded-lg p-6 text-center hover:border-blue-400 transition" :class="bookingType === 'one_time' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">
                                    <div class="text-4xl mb-2">📅</div>
                                    <div class="font-semibold text-gray-900">Engångsbokning</div>
                                    <div class="text-sm text-gray-600 mt-1">Boka en gång</div>
                                </div>
                            </label>
                        @endif

                        @if($form->service->subscription_booking)
                            <label 
                                class="relative cursor-pointer"
                                :class="bookingType === 'subscription' ? 'ring-2 ring-blue-500' : ''"
                            >
                                <input 
                                    type="radio" 
                                    name="booking_type" 
                                    value="subscription" 
                                    {{ !$form->service->one_time_booking && $form->service->subscription_booking ? 'checked' : '' }}
                                    class="hidden"
                                    x-model="bookingType"
                                    @change="updateBookingType('subscription', subscriptionFrequency)"
                                >
                                <div class="border-2 rounded-lg p-6 text-center hover:border-blue-400 transition" :class="bookingType === 'subscription' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">
                                    <div class="text-4xl mb-2">🔄</div>
                                    <div class="font-semibold text-gray-900">Prenumeration</div>
                                    <div class="text-sm text-gray-600 mt-1">Återkommande bokning</div>
                                </div>
                            </label>
                        @endif
                    </div>

                    <!-- Subscription Frequency (show when subscription is selected) -->
                    @if($form->service->subscription_booking && $form->service->subscription_types && count($form->service->subscription_types) > 0)
                        @php
                            $subscriptionTypes = $form->service->subscription_types;
                            $availableTypes = [
                                'daily' => ['icon' => '⏰', 'label' => 'Dagligen', 'multiplier' => $form->service->daily_multiplier],
                                'weekly' => ['icon' => '📆', 'label' => 'Veckovis', 'multiplier' => $form->service->weekly_multiplier],
                                'biweekly' => ['icon' => '📅', 'label' => 'Varannan vecka', 'multiplier' => $form->service->biweekly_multiplier],
                                'monthly' => ['icon' => '🗓️', 'label' => 'Månadsvis', 'multiplier' => $form->service->monthly_multiplier],
                            ];
                        @endphp

                        <div x-show="bookingType === 'subscription'" x-transition class="mt-4">
                            <label class="form-label">Prenumerationsfrekvens <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-{{ min(count($subscriptionTypes), 4) }} gap-3">
                                @foreach($subscriptionTypes as $type)
                                    @if(isset($availableTypes[$type]))
                                        <label class="cursor-pointer">
                                            <input 
                                                type="radio" 
                                                name="subscription_frequency" 
                                                value="{{ $type }}" 
                                                class="hidden"
                                                x-model="subscriptionFrequency"
                                                @change="updateBookingType('subscription', '{{ $type }}')"
                                                {{ $loop->first ? 'checked' : '' }}
                                            >
                                            <div class="border-2 rounded-lg p-4 text-center hover:border-blue-400 transition" :class="subscriptionFrequency === '{{ $type }}' ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">
                                                <div class="text-2xl mb-1">{{ $availableTypes[$type]['icon'] }}</div>
                                                <div class="font-medium text-sm">{{ $availableTypes[$type]['label'] }}</div>
                                                @if($availableTypes[$type]['multiplier'] < 1)
                                                    <div class="text-xs text-green-600 mt-1">{{ round((1 - $availableTypes[$type]['multiplier']) * 100) }}% rabatt</div>
                                                @elseif($availableTypes[$type]['multiplier'] > 1)
                                                    <div class="text-xs text-red-600 mt-1">+{{ round(($availableTypes[$type]['multiplier'] - 1) * 100) }}% påslag</div>
                                                @endif
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 3. Dynamic Form Fields (THIRD) -->
                <div class="space-y-6 mb-8">
                    @foreach($form->fields->sortBy('sort_order') as $field)
                        <div 
                            x-show="isFieldVisible('{{ $field->field_name }}', {{ $field->id }})"
                            x-transition
                            style="width: {{ $field->field_width }}%" 
                            class="inline-block align-top px-2"
                        >
                            <label class="form-label">
                                {{ $field->field_label }}
                                @if($field->required)
                                    <span class="text-red-500">*</span>
                                @endif
                                @if($field->field_type === 'number' && $field->pricing_rules && isset($field->pricing_rules['pricePerUnit']) && $field->pricing_rules['pricePerUnit'] > 0)
                                    @php
                                        $unitLabel = $field->pricing_rules['unitLabel'] ?? 'kr/st';
                                    @endphp
                                    <span class="text-sm text-gray-500">({{ number_format($field->pricing_rules['pricePerUnit'], 0) }} {{ $unitLabel }})</span>
                                @endif
                            </label>

                            @if($field->field_type === 'text')
                                <input 
                                    type="text" 
                                    placeholder="{{ $field->placeholder_text }}"
                                    class="form-input"
                                    {{ $field->required ? 'required' : '' }}
                                    @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                >
                            @elseif($field->field_type === 'email')
                                <input 
                                    type="email" 
                                    placeholder="{{ $field->placeholder_text }}"
                                    class="form-input"
                                    {{ $field->required ? 'required' : '' }}
                                    @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                >
                            @elseif($field->field_type === 'tel')
                                <input 
                                    type="tel" 
                                    placeholder="{{ $field->placeholder_text }}"
                                    class="form-input"
                                    {{ $field->required ? 'required' : '' }}
                                    @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                >
                            @elseif($field->field_type === 'textarea')
                                <textarea 
                                    rows="4"
                                    placeholder="{{ $field->placeholder_text }}"
                                    class="form-input"
                                    {{ $field->required ? 'required' : '' }}
                                    @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                ></textarea>
                            @elseif($field->field_type === 'number')
                                <input 
                                    type="number" 
                                    placeholder="{{ $field->placeholder_text }}"
                                    class="form-input"
                                    {{ $field->required ? 'required' : '' }}
                                    @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                >
                            @elseif($field->field_type === 'select' && $field->field_options && is_array($field->field_options) && count($field->field_options) > 0)
                                <select 
                                    class="form-input"
                                    {{ $field->required ? 'required' : '' }}
                                    @change="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                >
                                    <option value="">Välj...</option>
                                    @foreach($field->field_options as $option)
                                        <option value="{{ $option['value'] ?? $option['label'] }}">
                                            {{ $option['label'] ?? $option }}
                                            @if(isset($option['price']) && $option['price'] > 0)
                                                (+{{ number_format($option['price'], 0) }} kr)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            @elseif($field->field_type === 'radio' && $field->field_options && is_array($field->field_options) && count($field->field_options) > 0)
                                <div class="space-y-2">
                                    @foreach($field->field_options as $option)
                                        <label class="flex items-center">
                                            <input 
                                                type="radio" 
                                                name="{{ $field->field_name }}"
                                                value="{{ $option['value'] ?? $option['label'] }}"
                                                class="mr-2"
                                                {{ $field->required ? 'required' : '' }}
                                                @change="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                            >
                                            <span>
                                                {{ $option['label'] ?? $option }}
                                                @if(isset($option['price']) && $option['price'] > 0)
                                                    <span class="text-sm text-gray-500">(+{{ number_format($option['price'], 0) }} kr)</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($field->field_type === 'checkbox' && $field->field_options && is_array($field->field_options) && count($field->field_options) > 0)
                                <div class="space-y-2">
                                    @foreach($field->field_options as $option)
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                name="{{ $field->field_name }}[]"
                                                value="{{ $option['value'] ?? $option['label'] }}"
                                                class="mr-2"
                                                @change="updateFieldValue('{{ $field->field_name }}', Array.from(document.querySelectorAll('input[name=\'{{ $field->field_name }}[]\']:checked')).map(el => el.value))"
                                            >
                                            <span>
                                                {{ $option['label'] ?? $option }}
                                                @if(isset($option['price']) && $option['price'] > 0)
                                                    <span class="text-sm text-gray-500">(+{{ number_format($option['price'], 0) }} kr)</span>
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($field->field_type === 'date')
                                <input 
                                    type="date" 
                                    class="form-input"
                                    {{ $field->required ? 'required' : '' }}
                                    @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                >
                            @elseif($field->field_type === 'time')
                                <input 
                                    type="time" 
                                    class="form-input"
                                    {{ $field->required ? 'required' : '' }}
                                    @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                >
                            @elseif($field->field_type === 'slider')
                                <input 
                                    type="range" 
                                    min="0"
                                    max="100"
                                    value="50"
                                    class="w-full"
                                    @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                    x-model="formData['{{ $field->field_name }}']"
                                >
                                <div class="text-sm text-gray-600 mt-1">
                                    Värde: <span x-text="formData['{{ $field->field_name }}'] || 50"></span>
                                    @if($field->pricing_rules && isset($field->pricing_rules['pricePerUnit']) && $field->pricing_rules['pricePerUnit'] > 0)
                                        <span class="text-gray-500">({{ number_format($field->pricing_rules['pricePerUnit'], 0) }} kr/st)</span>
                                    @endif
                                </div>
                            @elseif($field->field_type === 'divider')
                                <hr class="my-4">
                            @elseif($field->field_type === 'heading')
                                <h3 class="text-xl font-semibold text-gray-900">{{ $field->field_label }}</h3>
                            @elseif($field->field_type === 'paragraph')
                                <p class="text-gray-700">{{ $field->help_text ?? $field->field_label }}</p>
                            @endif

                            @if($field->help_text && !in_array($field->field_type, ['divider', 'heading', 'paragraph']))
                                <p class="text-sm text-gray-500 mt-1">{{ $field->help_text }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="clear-both"></div>

                <!-- ROT-avdrag -->
                @if($form->service->rot_eligible)
                    <div class="mb-6">
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
                    <h3 class="text-lg font-semibold mb-4">💰 Prisberäkning</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Grundpris:</span>
                            <span class="font-medium" x-text="formatPrice(priceBreakdown.base_price)"></span>
                        </div>

                        <div x-show="priceBreakdown.variable_additions > 0" class="flex justify-between">
                            <span>Tillägg (fält):</span>
                            <span class="font-medium text-blue-600" x-text="'+' + formatPrice(priceBreakdown.variable_additions)"></span>
                        </div>

                        <!-- Subscription Multiplier -->
                        <div x-show="priceBreakdown.subscription_multiplier && priceBreakdown.subscription_multiplier !== 1" class="flex justify-between bg-purple-50 -mx-2 px-2 py-1 rounded">
                            <span class="flex items-center">
                                <span class="text-purple-600 mr-1">🔄</span>
                                <span>Prenumerationsrabatt:</span>
                            </span>
                            <span class="font-semibold text-purple-600" x-text="'×' + priceBreakdown.subscription_multiplier + ' (' + Math.round((1 - priceBreakdown.subscription_multiplier) * 100) + '% rabatt)'"></span>
                        </div>

                        <div x-show="priceBreakdown.city_multiplier !== 1" class="flex justify-between">
                            <span>Stadsmultiplikator:</span>
                            <span class="font-medium" x-text="'×' + priceBreakdown.city_multiplier"></span>
                        </div>

                        <!-- Service Discount -->
                        <div x-show="priceBreakdown.discount_amount > 0" class="flex justify-between bg-green-50 -mx-2 px-2 py-1 rounded">
                            <span class="flex items-center">
                                <span class="text-green-600 mr-1">🎁</span>
                                <span>Tjänsterabatt:</span>
                            </span>
                            <span class="font-semibold text-green-600" x-text="'-' + formatPrice(priceBreakdown.discount_amount)"></span>
                        </div>

                        <!-- ROT Deduction -->
                        <div x-show="priceBreakdown.rot_deduction > 0" class="flex justify-between bg-green-50 -mx-2 px-2 py-1 rounded">
                            <span class="flex items-center">
                                <span class="text-green-600 mr-1">💚</span>
                                <span>ROT-avdrag ({{ $form->service->rot_percent }}%):</span>
                            </span>
                            <span class="font-semibold text-green-600" x-text="'-' + formatPrice(priceBreakdown.rot_deduction)"></span>
                        </div>

                        <div class="border-t-2 border-blue-300 pt-3 mt-3">
                            <div class="flex justify-between font-bold text-xl text-blue-900">
                                <span>Totalt pris:</span>
                                <span x-text="formatPrice(priceBreakdown.final_price)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Contact Information (LAST) -->
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

