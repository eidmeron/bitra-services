<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormBuilderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Decode form_schema if it's a JSON string
        if ($this->has('form_schema') && is_string($this->form_schema)) {
            $decoded = json_decode($this->form_schema, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Map camelCase to snake_case for each field
                $mappedFields = array_map(function ($field) {
                    // Skip if field is null or not an array
                    if (!is_array($field)) {
                        return null;
                    }

                    return [
                        'field_type' => $field['type'] ?? $field['field_type'] ?? 'text',
                        'field_label' => $field['label'] ?? $field['field_label'] ?? 'Field',
                        'field_name' => $field['name'] ?? $field['field_name'] ?? 'field_' . uniqid(),
                        'placeholder_text' => $field['placeholder'] ?? $field['placeholder_text'] ?? null,
                        'help_text' => $field['helpText'] ?? $field['help_text'] ?? null,
                        'field_width' => $field['width'] ?? $field['field_width'] ?? '100',
                        'required' => $field['required'] ?? false,
                        'pricing_rules' => $field['pricingRules'] ?? $field['pricing_rules'] ?? null,
                        'conditional_logic' => $field['conditionalLogic'] ?? $field['conditional_logic'] ?? null,
                        'validation_rules' => $field['validationRules'] ?? $field['validation_rules'] ?? null,
                        'field_options' => $field['options'] ?? $field['field_options'] ?? null,
                    ];
                }, $decoded);

                // Filter out null values
                $mappedFields = array_filter($mappedFields);

                $this->merge([
                    'form_schema' => $decoded,
                    'fields' => $mappedFields,
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'form_name' => 'required|string|max:255',
            'form_schema' => 'nullable|array',
            'success_message' => 'nullable|string|max:1000',
            'redirect_after_submit' => 'nullable|boolean',
            'redirect_url' => 'nullable|url|max:500',
            'custom_styles' => 'nullable|array',
            'status' => 'required|in:active,inactive,draft',
            'fields' => 'nullable|array',
            'fields.*.field_type' => 'required_with:fields.*|string',
            'fields.*.field_label' => 'nullable|string|max:255',
            'fields.*.field_name' => 'nullable|string|max:255',
            'fields.*.placeholder_text' => 'nullable|string|max:255',
            'fields.*.help_text' => 'nullable|string|max:500',
            'fields.*.field_width' => 'nullable|in:25,33,50,100,full,half,third,quarter',
            'fields.*.required' => 'nullable|boolean',
            'fields.*.pricing_rules' => 'nullable|array',
            'fields.*.conditional_logic' => 'nullable|array',
            'fields.*.validation_rules' => 'nullable|array',
            'fields.*.field_options' => 'nullable|array',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service_id.required' => 'Tjänst är obligatoriskt.',
            'service_id.exists' => 'Vald tjänst är ogiltig.',
            'form_name.required' => 'Formulärnamn är obligatoriskt.',
            'status.required' => 'Status är obligatoriskt.',
            'status.in' => 'Ogiltig status.',
            'fields.*.field_type.required' => 'Fälttyp är obligatoriskt.',
            'fields.*.field_label.required' => 'Fältetikett är obligatoriskt.',
            'fields.*.field_name.required' => 'Fältnamn är obligatoriskt.',
        ];
    }
}

