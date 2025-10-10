export default () => ({
    fields: [],
    selectedField: null,
    draggedField: null,

    init() {
        console.log('🚀 Form Builder init() called');
        console.log('📋 window.formSchema exists?', !!window.formSchema);
        console.log('📊 window.formSchema:', window.formSchema);
        
        // Load existing form schema if editing
        if (window.formSchema) {
            console.log('✅ Loading', window.formSchema.length, 'fields from window.formSchema');
            this.fields = window.formSchema;
            console.log('✅ this.fields now has', this.fields.length, 'fields');
            console.log('📝 Fields:', this.fields);
        } else {
            console.warn('⚠️ No window.formSchema found - starting with empty fields array');
        }
    },

    addField(type) {
        const field = {
            id: 'field_' + Date.now(),
            type: type,
            label: this.getDefaultLabel(type),
            name: 'field_' + this.fields.length,
            placeholder: '',
            helpText: '',
            width: '100',
            required: false,
            options: ['select', 'radio', 'checkbox'].includes(type) ? [] : null,
            pricingRules: {
                pricePerUnit: 0
            },
            validationRules: {
                minLength: null,
                maxLength: null,
                min: null,
                max: null,
                pattern: null
            },
            conditionalLogic: {
                operator: 'and',
                rules: []
            }
        };

        this.fields.push(field);
        this.selectedField = field;
    },

    removeField(field) {
        this.fields = this.fields.filter(f => f.id !== field.id);
        if (this.selectedField?.id === field.id) {
            this.selectedField = null;
        }
    },

    duplicateField(field) {
        const clone = JSON.parse(JSON.stringify(field));
        clone.id = 'field_' + Date.now();
        clone.name = clone.name + '_copy';
        this.fields.push(clone);
    },

    moveFieldUp(index) {
        if (index > 0) {
            [this.fields[index], this.fields[index - 1]] = [this.fields[index - 1], this.fields[index]];
        }
    },

    moveFieldDown(index) {
        if (index < this.fields.length - 1) {
            [this.fields[index], this.fields[index + 1]] = [this.fields[index + 1], this.fields[index]];
        }
    },

    getDefaultLabel(type) {
        const labels = {
            text: 'Textfält',
            email: 'E-post',
            phone: 'Telefon',
            textarea: 'Textområde',
            number: 'Nummer',
            select: 'Rullgardinsmeny',
            radio: 'Radioknappar',
            checkbox: 'Kryssrutor',
            date: 'Datum',
            time: 'Tid',
            file: 'Fil',
            url: 'URL',
            slider: 'Skjutreglage',
            divider: 'Avdelare',
            container: 'Container'
        };
        return labels[type] || type;
    },

    saveForm() {
        // Submit form schema
        const formSchemaInput = document.getElementById('form_schema_input');
        if (formSchemaInput) {
            formSchemaInput.value = JSON.stringify(this.fields);
            document.getElementById('form_builder_form').submit();
        }
    },

    addOption(field) {
        if (!field.options) {
            field.options = [];
        }
        field.options.push({
            label: 'Alternativ ' + (field.options.length + 1),
            value: 'option_' + (field.options.length + 1),
            price: 0
        });
    },

    removeOption(field, index) {
        field.options.splice(index, 1);
    },

    addConditionalRule(field) {
        if (!field.conditionalLogic) {
            field.conditionalLogic = {
                operator: 'and',
                rules: []
            };
        }
        field.conditionalLogic.rules.push({
            field: '',
            condition: 'equals',
            value: ''
        });
    },

    removeConditionalRule(field, index) {
        if (field.conditionalLogic && field.conditionalLogic.rules) {
            field.conditionalLogic.rules.splice(index, 1);
        }
    }
});

