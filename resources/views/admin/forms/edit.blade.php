@extends('layouts.admin')

@section('title', 'Redigera formulär')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.forms.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till formulär</a>
</div>

<div class="max-w-7xl mx-auto">
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

    <div x-data="formBuilder()" x-init="init()">
        <!-- Main Form Settings -->
        <div class="card mb-6">
            <h3 class="text-xl font-semibold mb-4">Formulärinställningar</h3>
            
            <form method="POST" action="{{ route('admin.forms.update', $form) }}" id="form_builder_form">
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
                    
                    <div id="form-canvas" class="space-y-4 min-h-[400px]">
                        <template x-for="(field, index) in fields" :key="field.id">
                            <div 
                                class="border rounded-lg p-4 hover:border-blue-500 cursor-pointer transition"
                                :class="{'border-blue-500 bg-blue-50': selectedField?.id === field.id}"
                                @click="selectedField = field"
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
                                        
                                        <template x-if="field.type === 'textarea'">
                                            <textarea 
                                                :placeholder="field.placeholder"
                                                class="w-full px-3 py-2 border rounded bg-gray-50"
                                                rows="3"
                                                disabled
                                            ></textarea>
                                        </template>
                                        
                                        <template x-if="field.type === 'number' || field.type === 'slider'">
                                            <input 
                                                type="number" 
                                                :placeholder="field.placeholder"
                                                class="w-full px-3 py-2 border rounded bg-gray-50"
                                                disabled
                                            >
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
                            </div>
                        </div>

                        <!-- Conditional Logic -->
                        <div class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">🔀 Villkorsstyrd logik</label>
                            <p class="text-xs text-gray-500 mb-2">Visa detta fält endast om:</p>
                            
                            <div class="space-y-2">
                                <select 
                                    x-model="selectedField.conditionalLogic.operator"
                                    class="w-full px-2 py-1 border rounded text-sm"
                                >
                                    <option value="and">Alla villkor måste uppfyllas (OCH)</option>
                                    <option value="or">Ett villkor måste uppfyllas (ELLER)</option>
                                </select>
                                
                                <template x-if="selectedField?.conditionalLogic?.rules">
                                    <div class="space-y-2">
                                        <template x-for="(rule, ruleIdx) in selectedField.conditionalLogic.rules" :key="ruleIdx">
                                            <div class="border p-2 rounded text-sm space-y-1">
                                                <select x-model="rule.field" class="w-full px-2 py-1 border rounded text-xs">
                                                    <option value="">Välj fält...</option>
                                                    <template x-for="field in fields" :key="field.id">
                                                        <option x-bind:value="field.name" x-text="field.label"></option>
                                                    </template>
                                                </select>
                                                
                                                <select x-model="rule.condition" class="w-full px-2 py-1 border rounded text-xs">
                                                    <option value="equals">Är lika med</option>
                                                    <option value="not_equals">Är inte lika med</option>
                                                    <option value="contains">Innehåller</option>
                                                    <option value="not_contains">Innehåller inte</option>
                                                    <option value="greater_than">Större än</option>
                                                    <option value="less_than">Mindre än</option>
                                                    <option value="is_empty">Är tom</option>
                                                    <option value="is_not_empty">Är inte tom</option>
                                                </select>
                                                
                                                <div class="flex gap-2">
                                                    <input 
                                                        type="text" 
                                                        x-model="rule.value"
                                                        placeholder="Värde"
                                                        class="flex-1 px-2 py-1 border rounded text-xs"
                                                    >
                                                    <button @click="removeConditionalRule(selectedField, ruleIdx)" class="text-red-500 hover:text-red-700 text-xs">
                                                        ✕
                                                    </button>
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
                <button @click="saveForm()" class="btn btn-primary">
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
    
    // Debug: Log the schema
    console.log('✅ window.formSchema loaded:', window.formSchema);
    console.log('📊 Number of fields in schema:', window.formSchema ? window.formSchema.length : 0);
    
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
        } else {
            console.error('❌ Alpine component not found or not initialized');
        }
    }, 1000);
</script>
@endpush
@endsection

