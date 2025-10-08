<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
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
        $companyId = $this->route('company')?->id;

        return [
            'email' => 'required|email|unique:users,email,' . ($this->route('company')?->user_id ?? 'NULL'),
            'phone' => 'nullable|string|max:20',
            'password' => $this->isMethod('post') ? 'required|min:8' : 'nullable|min:8',
            'company_email' => 'required|email',
            'company_number' => 'required|string|max:50',
            'company_org_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('companies', 'company_org_number')->ignore($companyId),
            ],
            'site' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive,pending',
            'company_logo' => 'nullable|image|max:2048',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'cities' => 'nullable|array',
            'cities.*' => 'exists:cities,id',
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
            'email.required' => 'E-post är obligatoriskt.',
            'email.unique' => 'E-postadressen används redan.',
            'password.required' => 'Lösenord är obligatoriskt.',
            'password.min' => 'Lösenordet måste vara minst 8 tecken.',
            'company_email.required' => 'Företagets e-post är obligatoriskt.',
            'company_number.required' => 'Företagsnummer är obligatoriskt.',
            'company_org_number.required' => 'Organisationsnummer är obligatoriskt.',
            'company_org_number.unique' => 'Organisationsnumret används redan.',
            'status.required' => 'Status är obligatoriskt.',
            'company_logo.image' => 'Logotypen måste vara en bild.',
            'company_logo.max' => 'Logotypen får inte vara större än 2MB.',
        ];
    }
}

