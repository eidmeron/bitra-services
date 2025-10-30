@extends('layouts.admin')

@section('title', 'Redigera formulär')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.forms.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till formulär</a>
</div>

<div class="max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-red-700 font-medium">Det finns fel i formuläret:</p>
                    <ul class="text-red-600 text-sm mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Header -->
    <div class="card mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $form->form_name }}</h2>
                <p class="text-gray-600">{{ $form->service->name }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.forms.preview', $form) }}" target="_blank" class="btn btn-secondary">
                    👁️ Förhandsgranska
                </a>
                <a href="{{ route('admin.forms.shortcode', $form) }}" class="btn btn-primary">
                    📝 Hämta kortkod
                </a>
            </div>
        </div>
    </div>

    <script>
        // Set form schema BEFORE Alpine initializes
        window.formSchema = {!! json_encode($form->fields->map(function($field) {
            return [
                'id' => (string)$field->id,
                'type' => $field->field_type,
                'label' => $field->field_label,
                'name' => $field->field_name,
                'placeholder' => $field->placeholder_text ?? '',
                'helpText' => $field->help_text ?? '',
                'width' => $field->field_width,
                'required' => (bool)$field->required,
                'options' => $field->field_options ?? [],
                'pricingRules' => $field->pricing_rules ?? ['pricePerUnit' => 0],
                'validationRules' => $field->validation_rules ?? [
                    'minLength' => null,
                    'maxLength' => null,
                    'min' => null,
                    'max' => null,
                    'pattern' => null
                ],
                'conditionalLogic' => $field->conditional_logic ?? [
                    'operator' => 'and',
                    'rules' => []
                ],
            ];
        })) !!};
        console.log('✅ window.formSchema set EARLY:', window.formSchema);
    </script>

    <div x-data="formBuilder()" x-init="init()">
        <!-- Main Form Settings -->
        <div class="card mb-6">
            <h3 class="text-xl font-semibold mb-4">Formulärinställningar</h3>
            
            <form 
                method="POST" 
                action="{{ route('admin.forms.update', $form) }}" 
                id="form_builder_form"
                @submit="document.getElementById('form_schema_input').value = JSON.stringify(fields); console.log('📤 Submitting form with fields:', fields)"
            >
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">Formulärnamn *</label>
                        <input type="text" name="form_name" value="{{ old('form_name', $form->form_name) }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Tjänst *</label>
                        <select name="service_id" required class="form-input">
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $form->service_id == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Meddelande vid lyckad bokning</label>
                    <textarea name="success_message" rows="2" class="form-input">{{ old('success_message', $form->success_message) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="redirect_after_submit" value="1" {{ old('redirect_after_submit', $form->redirect_after_submit) ? 'checked' : '' }} class="mr-2">
                        <span>Omdirigera efter bokning</span>
                    </label>
                    <input type="url" name="redirect_url" value="{{ old('redirect_url', $form->redirect_url) }}" class="form-input" placeholder="https://example.com/tack">
                </div>

                <div class="mb-4">
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="draft" {{ $form->status === 'draft' ? 'selected' : '' }}>Utkast</option>
                        <option value="active" {{ $form->status === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ $form->status === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <input type="hidden" name="form_schema" id="form_schema_input">
            </form>
        </div>

        <!-- Form Builder -->
        <div class="grid grid-cols-12 gap-6 mb-6">
            <!-- Field Palette -->
            <div class="col-span-3">
                <div class="card sticky top-4">
                    <h3 class="text-lg font-semibold mb-4">Fälttyper</h3>
                    
                    <div class="space-y-2">
                        <button @click="addField('text')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            📝 Textfält
                        </button>
                        <button @click="addField('email')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            ✉️ E-post
                        </button>
                        <button @click="addField('phone')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            📞 Telefon
                        </button>
                        <button @click="addField('address')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            📍 Adress (Google Autocomplete)
                        </button>
                        <button @click="addField('textarea')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            📄 Textområde
                        </button>
                        <button @click="addField('number')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            🔢 Nummer
                        </button>
                        <button @click="addField('select')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            📋 Rullgardinsmeny
                        </button>
                        <button @click="addField('radio')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            🔘 Radioknappar
                        </button>
                        <button @click="addField('checkbox')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            ☑️ Kryssrutor
                        </button>
                        <button @click="addField('date')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            📅 Datum
                        </button>
                        <button @click="addField('time')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            🕐 Tid
                        </button>
                        <button @click="addField('slider')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            🎚️ Skjutreglage
                        </button>
                        <button @click="addField('divider')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50 transition">
                            ➖ Avdelare
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Form Canvas -->
            <div class="col-span-6">
                <div class="card">
                    <h3 class="text-lg font-semibold mb-4">Formulärförhandsvisning</h3>
                    
                    <div id="form-canvas" class="flex flex-wrap gap-4 min-h-[400px]">
                        <template x-for="(field, index) in fields" :key="field.id">
                            <div 
                                class="border rounded-lg p-4 hover:border-blue-500 cursor-pointer transition"
                                :class="{
                                    'border-blue-500 bg-blue-50': selectedField?.id === field.id,
                                    'w-full': field.width == '100',
                                    'w-[calc(50%-0.5rem)]': field.width == '50',
                                    'w-[calc(33.333%-0.67rem)]': field.width == '33',
                                    'w-[calc(25%-0.75rem)]': field.width == '25'
                                }"
                                @click="selectedField = field; console.log('Selected field:', field.id, field.label, 'Conditional Logic:', field.conditionalLogic)"mmer
                            >
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            <span x-text="field.label"></span>
                                            <span x-show="field.required" class="text-red-500">*</span>
                                        </label>
                                        
                                        <!-- Field Preview -->
                                        <template x-if="field.type === 'text' || field.type === 'email' || field.type === 'phone'">
                                            <input 
                                                type="text" 
                                                :placeholder="field.placeholder"
                                                class="w-full px-3 py-2 border rounded bg-gray-50"
                                                disabled
                                            >
                                        </template>
                                        
                                        <template x-if="field.type === 'address'">
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">📍</span>
                                                <input 
                                                    type="text" 
                                                    :placeholder="field.placeholder || 'Börja skriva din adress...'"
                                                    class="w-full pl-10 pr-3 py-2 border rounded bg-gray-50"
                                                    disabled
                                                >
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">🔍 Google Maps autocomplete</p>
                                        </template>
                                        
                                        <template x-if="field.type === 'textarea'">
                                            <textarea 
                                                :placeholder="field.placeholder"
                                                class="w-full px-3 py-2 border rounded bg-gray-50"
                                                rows="3"
                                                disabled
                                            ></textarea>
                                        </template>
                                        
                                        <template x-if="field.type === 'number'">                                                                                                        
                                            <input 
                                                type="number" 
                                                :placeholder="field.placeholder"
                                                class="w-full px-3 py-2 border rounded bg-gray-50"
                                                disabled
                                            >
                                        </template>
                                        
                                        <template x-if="field.type === 'slider'">
                                            <div class="w-full">
                                                <div class="flex items-center space-x-4">
                                                    <input 
                                                        type="range" 
                                                        :min="field.validationRules?.min || 0"
                                                        :max="field.validationRules?.max || 100"
                                                        :step="field.validationRules?.step || 1"
                                                        class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                                                        disabled
                                                    >
                                                    <span class="text-sm text-gray-600 min-w-[3rem] text-center" x-text="field.validationRules?.min || 0"></span>
                                                </div>
                                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                                    <span x-text="field.validationRules?.min || 0"></span>
                                                    <span x-text="field.validationRules?.max || 100"></span>
                                                </div>
                                            </div>
                                        </template>
                                        
                                        <template x-if="field.type === 'select'">
                                            <select class="w-full px-3 py-2 border rounded bg-gray-50" disabled>
                                                <option>Välj ett alternativ</option>
                                            </select>
                                        </template>
                                        
                                        <template x-if="field.type === 'date'">
                                            <input type="date" class="w-full px-3 py-2 border rounded bg-gray-50" disabled>
                                        </template>
                                        
                                        <template x-if="field.helpText">
                                            <p class="text-sm text-gray-500 mt-1" x-text="field.helpText"></p>
                                        </template>
                                    </div>
                                    
                                    <div class="flex space-x-2 ml-4">
                                        <button @click.stop="moveFieldUp(index)" class="text-gray-400 hover:text-gray-600" title="Flytta upp">
                                            ⬆️
                                        </button>
                                        <button @click.stop="moveFieldDown(index)" class="text-gray-400 hover:text-gray-600" title="Flytta ner">
                                            ⬇️
                                        </button>
                                        <button @click.stop="duplicateField(field)" class="text-gray-400 hover:text-gray-600" title="Duplicera">
                                            📋
                                        </button>
                                        <button @click.stop="removeField(field)" class="text-red-400 hover:text-red-600" title="Radera">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="fields.length === 0" class="text-center text-gray-400 py-12 border-2 border-dashed rounded-lg">
                            <p class="text-lg mb-2">Inget fält än</p>
                            <p class="text-sm">Klicka på en fälttyp till vänster för att lägga till</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Field Settings -->
            <div class="col-span-3">
                <div class="card sticky top-4">
                    <h3 class="text-lg font-semibold mb-4">Fältinställningar</h3>
                    
                    <div x-show="selectedField" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Etikett</label>
                            <input 
                                type="text" 
                                x-model="selectedField.label"
                                class="w-full px-3 py-2 border rounded"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fältnamn</label>
                            <input 
                                type="text" 
                                x-model="selectedField.name"
                                class="w-full px-3 py-2 border rounded"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Platshållare</label>
                            <input 
                                type="text" 
                                x-model="selectedField.placeholder"
                                class="w-full px-3 py-2 border rounded"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hjälptext</label>
                            <textarea 
                                x-model="selectedField.helpText"
                                class="w-full px-3 py-2 border rounded"
                                rows="2"
                            ></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fältbredd</label>
                            <select x-model="selectedField.width" class="w-full px-3 py-2 border rounded">
                                <option value="100">100% (Full bredd)</option>
                                <option value="50">50% (Halv bredd)</option>
                                <option value="33">33% (En tredjedel)</option>
                                <option value="25">25% (En fjärdedel)</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                x-model="selectedField.required"
                                class="mr-2"
                            >
                            <label class="text-sm font-medium text-gray-700">Obligatoriskt fält</label>
                        </div>
                        
                        <!-- Options for select/radio/checkbox -->
                        <div x-show="['select', 'radio', 'checkbox'].includes(selectedField?.type)">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alternativ</label>
                            <template x-if="selectedField?.options">
                                <div class="space-y-2">
                                    <template x-for="(option, idx) in selectedField.options" :key="idx">
                                        <div class="flex gap-2">
                                            <input 
                                                type="text" 
                                                x-model="option.label"
                                                placeholder="Etikett"
                                                class="flex-1 px-2 py-1 border rounded text-sm"
                                            >
                                            <input 
                                                type="number" 
                                                x-model="option.price"
                                                placeholder="Pris (kr)"
                                                class="w-20 px-2 py-1 border rounded text-sm"
                                            >
                                            <button @click="removeOption(selectedField, idx)" class="text-red-500 hover:text-red-700">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <button @click="addOption(selectedField)" class="text-sm text-blue-600 hover:underline mt-2">
                                + Lägg till alternativ
                            </button>
                        </div>

                        <!-- Pricing Rules -->
                        <div x-show="['number', 'slider'].includes(selectedField?.type)" class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">💰 Prissättning</label>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-xs text-gray-600">Pris per enhet (kr)</label>
                                    <input 
                                        type="number" 
                                        x-model="selectedField.pricingRules.pricePerUnit"
                                        placeholder="0.00"
                                        step="0.01"
                                        class="w-full px-2 py-1 border rounded text-sm"
                                    >
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600">Enhetsetikett (visas efter pris)</label>
                                    <input 
                                        type="text" 
                                        x-model="selectedField.pricingRules.unitLabel"
                                        placeholder="t.ex. kr/st, kr/kvm, kr/fönster"
                                        class="w-full px-2 py-1 border rounded text-sm"
                                    >
                                    <p class="text-xs text-gray-500 mt-1">
                                        Exempel: "kr/st" → visas som "(10 kr/st)"
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Validation Rules -->
                        <div class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">✓ Valideringsregler</label>
                            <div class="space-y-2">
                                <template x-if="selectedField?.type === 'text' || selectedField?.type === 'textarea'">
                                    <div>
                                        <label class="text-xs text-gray-600">Min längd</label>
                                        <input 
                                            type="number" 
                                            x-model="selectedField.validationRules.minLength"
                                            placeholder="ex: 3"
                                            class="w-full px-2 py-1 border rounded text-sm"
                                        >
                                    </div>
                                </template>
                                <template x-if="selectedField?.type === 'text' || selectedField?.type === 'textarea'">
                                    <div>
                                        <label class="text-xs text-gray-600">Max längd</label>
                                        <input 
                                            type="number" 
                                            x-model="selectedField.validationRules.maxLength"
                                            placeholder="ex: 100"
                                            class="w-full px-2 py-1 border rounded text-sm"
                                        >
                                    </div>
                                </template>
                                <template x-if="selectedField?.type === 'number'">
                                    <div>
                                        <label class="text-xs text-gray-600">Minimivärde</label>
                                        <input 
                                            type="number" 
                                            x-model="selectedField.validationRules.min"
                                            placeholder="ex: 0"
                                            class="w-full px-2 py-1 border rounded text-sm"
                                        >
                                    </div>
                                </template>
                                <template x-if="selectedField?.type === 'number'">
                                    <div>
                                        <label class="text-xs text-gray-600">Maximivärde</label>
                                        <input 
                                            type="number" 
                                            x-model="selectedField.validationRules.max"
                                            placeholder="ex: 100"
                                            class="w-full px-2 py-1 border rounded text-sm"
                                        >
                                    </div>
                                </template>
                                
                                <!-- Slider Validation Rules -->
                                <template x-if="selectedField?.type === 'slider'">
                                    <div>
                                        <label class="text-xs text-gray-600">Minimivärde</label>
                                        <input 
                                            type="number" 
                                            x-model="selectedField.validationRules.min"
                                            placeholder="ex: 0"
                                            class="w-full px-2 py-1 border rounded text-sm"
                                        >
                                    </div>
                                </template>
                                <template x-if="selectedField?.type === 'slider'">
                                    <div>
                                        <label class="text-xs text-gray-600">Maximivärde</label>
                                        <input 
                                            type="number" 
                                            x-model="selectedField.validationRules.max"
                                            placeholder="ex: 100"
                                            class="w-full px-2 py-1 border rounded text-sm"
                                        >
                                    </div>
                                </template>
                                <template x-if="selectedField?.type === 'slider'">
                                    <div>
                                        <label class="text-xs text-gray-600">Steg</label>
                                        <input 
                                            type="number" 
                                            x-model="selectedField.validationRules.step"
                                            placeholder="ex: 1"
                                            class="w-full px-2 py-1 border rounded text-sm"
                                        >
                                    </div>
                                </template>
                                
                                <!-- Slider Points Configuration -->
                                <template x-if="selectedField?.type === 'slider'">
                                    <div class="border-t pt-3 mt-3">
                                        <label class="text-xs text-gray-700 font-semibold mb-2 block">🎚️ Skjutreglage-punkter</label>
                                        <p class="text-xs text-gray-500 mb-2">Definiera specifika värden med priser:</p>
                                        
                                        <!-- Points List -->
                                        <div class="space-y-2 mb-2">
                                            <template x-if="selectedField.options && selectedField.options.length > 0">
                                                <div class="space-y-2">
                                                    <template x-for="(point, idx) in selectedField.options" :key="idx">
                                                        <div class="flex gap-2 items-center p-2 bg-gray-50 rounded border">
                                                            <input 
                                                                type="number"
                                                                x-model="point.value"
                                                                placeholder="Värde (ex: 10)"
                                                                class="flex-1 px-2 py-1 border rounded text-xs"
                                                            >
                                                            <input 
                                                                type="text"
                                                                x-model="point.label"
                                                                placeholder="Etikett (ex: 10 rum)"
                                                                class="flex-1 px-2 py-1 border rounded text-xs"
                                                            >
                                                            <input 
                                                                type="number"
                                                                x-model="point.price"
                                                                placeholder="Pris"
                                                                class="w-20 px-2 py-1 border rounded text-xs"
                                                            >
                                                            <button 
                                                                @click="selectedField.options.splice(idx, 1)" 
                                                                type="button"
                                                                class="text-red-500 hover:text-red-700 px-2"
                                                            >
                                                                ✕
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="!selectedField.options || selectedField.options.length === 0">
                                                <p class="text-xs text-gray-400 italic text-center py-2">Inga punkter definierade ännu</p>
                                            </template>
                                        </div>
                                        
                                        <button 
                                            @click="if (!selectedField.options) selectedField.options = []; selectedField.options.push({value: '', label: '', price: 0})"
                                            type="button"
                                            class="text-xs text-blue-600 hover:underline"
                                        >
                                            + Lägg till punkt
                                        </button>
                                        
                                        <div class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded text-xs">
                                            <p class="font-semibold text-blue-800 mb-1">💡 Tips:</p>
                                            <ul class="list-disc list-inside text-blue-700 space-y-1">
                                                <li>Värde: Det numeriska värdet (10, 11, 12, osv.)</li>
                                                <li>Etikett: Vad som visas till användaren</li>
                                                <li>Pris: Tilläggspris för detta värde</li>
                                                <li>Standardvärde sätts till 0 om inga punkter definieras</li>
                                            </ul>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Conditional Logic -->
                        <div class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">🔀 Villkorsstyrd logik</label>
                            <p class="text-xs text-gray-500 mb-2">Visa detta fält endast om:</p>
                            
                            <!-- Debug Info -->
                            <div class="bg-yellow-50 border border-yellow-200 p-2 rounded text-xs mb-2">
                                <strong>🐛 Debug:</strong>
                                <div x-text="`Totalt ${fields.length} fält i formuläret`"></div>
                                <div x-show="selectedField" x-text="`Valt fält: ${selectedField?.label} (${selectedField?.name})`"></div>
                                <button type="button" @click="console.log('Current fields:', fields); console.log('Selected field:', selectedField)" class="text-blue-600 underline">
                                    Se i konsolen
                                </button>
                            </div>
                            
                            <div class="space-y-2">
                                <select 
                                    x-model="selectedField.conditionalLogic.operator"
                                    class="w-full px-2 py-1 border rounded text-sm"
                                    @change="console.log('Operator changed to:', selectedField.conditionalLogic.operator); console.log('Full conditional logic:', selectedField.conditionalLogic)"
                                >
                                    <option value="and">Alla villkor måste uppfyllas (OCH)</option>
                                    <option value="or">Ett villkor måste uppfyllas (ELLER)</option>
                                </select>
                                
                                <template x-if="selectedField?.conditionalLogic?.rules">
                                    <div class="space-y-2">
                                        <template x-for="(rule, ruleIdx) in selectedField.conditionalLogic.rules" :key="ruleIdx">
                                            <div class="border p-2 rounded text-sm space-y-1 bg-gray-50">
                                                <div>
                                                    <label class="text-xs text-gray-600 font-medium">Fält att kontrollera:</label>
                                                    <select 
                                                        x-model="rule.field" 
                                                        class="w-full px-2 py-1 border rounded text-xs mt-1"
                                                        :class="rule.field ? 'bg-green-50 border-green-300' : 'bg-white'"
                                                        @change="console.log('✅ Field selected:', rule.field, 'Full rule:', rule)"
                                                        @click="console.log('📋 Fields available:', fields.length, fields.map(f => ({id: f.id, name: f.name, label: f.label})))"
                                                    >
                                                        <option value="">Välj fält...</option>
                                                        <template x-for="otherField in fields.filter(f => f.id !== selectedField.id)" :key="otherField.id">
                                                            <option 
                                                                :value="otherField.name" 
                                                                x-text="`${otherField.label} (${otherField.name})`"
                                                            ></option>
                                                        </template>
                                                    </select>
                                                    <!-- Status indicators -->
                                                    <div class="mt-1 space-y-1">
                                                        <p class="text-xs text-red-500" x-show="fields.length === 0">⚠️ Inga fält tillgängliga. Lägg till fält först!</p>
                                                        <p class="text-xs text-gray-500" x-show="fields.length > 0 && !rule.field" x-text="`${fields.length} fält tillgängliga`"></p>
                                                        <p class="text-xs text-green-600 font-medium" x-show="rule.field">
                                                            ✅ Valt fält: <span x-text="rule.field"></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="text-xs text-gray-600 font-medium">Villkor:</label>
                                                    <select x-model="rule.condition" class="w-full px-2 py-1 border rounded text-xs mt-1">
                                                        <option value="equals">Är lika med</option>
                                                        <option value="not_equals">Är inte lika med</option>
                                                        <option value="contains">Innehåller</option>
                                                        <option value="not_contains">Innehåller inte</option>
                                                        <option value="greater_than">Större än</option>
                                                        <option value="less_than">Mindre än</option>
                                                        <option value="is_empty">Är tom</option>
                                                        <option value="is_not_empty">Är inte tom</option>
                                                    </select>
                                                </div>
                                                
                                                <div>
                                                    <label class="text-xs text-gray-600 font-medium">Värde:</label>
                                                    <div class="flex gap-2 mt-1">
                                                        <input 
                                                            type="text" 
                                                            x-model="rule.value"
                                                            placeholder="t.ex. option_1"
                                                            class="flex-1 px-2 py-1 border rounded text-xs"
                                                            @input="console.log('✍️ Rule value changed:', {field: rule.field, condition: rule.condition, value: rule.value})"
                                                        >
                                                        <button @click="removeConditionalRule(selectedField, ruleIdx)" type="button" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-xs">
                                                            ✕
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Show available options for select/radio/checkbox fields -->
                                                    <template x-if="rule.field">
                                                        <div>
                                                            <template x-for="targetField in fields.filter(f => f.name === rule.field)" :key="targetField.id">
                                                                <div>
                                                                    <template x-if="targetField.options && targetField.options.length > 0 && ['select', 'radio', 'checkbox'].includes(targetField.type)">
                                                                        <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded">
                                                                            <p class="text-xs font-semibold text-blue-800 mb-1">📋 Tillgängliga värden för "<span x-text="targetField.label"></span>":</p>
                                                                            <div class="space-y-1">
                                                                                <template x-for="opt in targetField.options" :key="opt.value">
                                                                                    <div class="flex items-center justify-between bg-white px-2 py-1 rounded border border-blue-100">
                                                                                        <div class="flex items-center gap-2">
                                                                                            <code class="text-xs font-mono bg-blue-100 text-blue-800 px-1 py-0.5 rounded" x-text="opt.value"></code>
                                                                                            <span class="text-xs text-gray-600">→</span>
                                                                                            <span class="text-xs text-gray-700" x-text="opt.label"></span>
                                                                                        </div>
                                                                                        <button 
                                                                                            type="button"
                                                                                            @click="rule.value = opt.value; console.log('✅ Quick-selected value:', opt.value)"
                                                                                            class="text-xs text-blue-600 hover:text-blue-800 hover:underline font-medium"
                                                                                        >
                                                                                            Använd
                                                                                        </button>
                                                                                    </div>
                                                                                </template>
                                                                            </div>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </template>
                                                    
                                                    <p class="text-xs text-gray-500 mt-1">💡 För radio/select: använd <strong>värdet</strong> (t.ex. "option_1") inte etiketten</p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                
                                <button @click="addConditionalRule(selectedField)" class="text-xs text-blue-600 hover:underline">
                                    + Lägg till villkor
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="!selectedField" class="text-center text-gray-400 py-12">
                        <p>Välj ett fält för att redigera</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.forms.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button @click="console.log('💾 SAVING FORM - All fields:', JSON.parse(JSON.stringify(fields))); saveForm()" class="btn btn-primary">
                    💾 Spara formulär
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Debug: Form load timestamp
    console.log('🔄 Form loaded at:', new Date().toISOString());
    console.log('📝 Form ID:', {{ $form->id }});
    console.log('📋 Fields from database:', {{ $form->fields->count() }});
    
    // Verify Alpine loads the data
    document.addEventListener('alpine:init', () => {
        console.log('🏔️ Alpine.js initialized');
    });
    
    // Check Alpine data after a short delay
    setTimeout(() => {
        const formBuilder = document.querySelector('[x-data="formBuilder()"]');
        if (formBuilder && formBuilder.__x) {
            console.log('🎯 Alpine component data:', formBuilder.__x.$data);
            console.log('📝 Fields in Alpine:', formBuilder.__x.$data.fields);
            console.log('📝 Field names:', formBuilder.__x.$data.fields.map(f => ({name: f.name, label: f.label})));
        } else {
            console.error('❌ Alpine component not found or not initialized');
        }
    }, 1000);
</script>
@endpush
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success/error messages after 5 seconds
    const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Service change notification
    const serviceSelect = document.querySelector('select[name="service_id"]');
    if (serviceSelect) {
        const originalValue = serviceSelect.value;
        
        serviceSelect.addEventListener('change', function() {
            if (this.value !== originalValue) {
                // Add visual feedback
                this.style.borderColor = '#10b981';
                this.style.backgroundColor = '#f0fdf4';
                
                // Show a small notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.textContent = 'Tjänst ändrad - kom ihåg att spara formuläret';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        });
    }
});
</script>
@endpush

