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
            'fields.*.field_type' => 'required|string',
            'fields.*.field_label' => 'required|string|max:255',
            'fields.*.field_name' => 'required|string|max:255',
            'fields.*.placeholder_text' => 'nullable|string|max:255',
            'fields.*.help_text' => 'nullable|string|max:500',
            'fields.*.field_width' => 'nullable|in:25,33,50,100',
            'fields.*.required' => 'nullable|boolean',
            'fields.*.pricing_rules' => 'nullable|array',
            'fields.*.conditional_logic' => 'nullable|array',
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

