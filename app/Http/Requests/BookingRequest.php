<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'form_id' => 'required|exists:forms,id',
            'city_id' => 'required|exists:cities,id',
            'booking_type' => 'required|in:one_time,subscription',
            'subscription_frequency' => 'required_if:booking_type,subscription|in:daily,weekly,biweekly,monthly',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_message' => 'nullable|string|max:1000',
            'preferred_date' => 'nullable|date|after:now',
            'form_data' => 'required|array',
            'apply_rot' => 'nullable|boolean',
            'accept_terms' => 'required|accepted',
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
            'form_id.required' => 'Formulär är obligatoriskt.',
            'form_id.exists' => 'Valt formulär är ogiltigt.',
            'city_id.required' => 'Stad är obligatoriskt.',
            'city_id.exists' => 'Vald stad är ogiltig.',
            'booking_type.required' => 'Bokningstyp är obligatoriskt.',
            'booking_type.in' => 'Ogiltig bokningstyp.',
            'subscription_frequency.required_if' => 'Frekvens är obligatoriskt för prenumeration.',
            'customer_name.required' => 'Namn är obligatoriskt.',
            'customer_email.required' => 'E-post är obligatoriskt.',
            'customer_email.email' => 'E-post måste vara en giltig e-postadress.',
            'customer_phone.required' => 'Telefonnummer är obligatoriskt.',
            'preferred_date.after' => 'Önskat datum måste vara i framtiden.',
            'form_data.required' => 'Formulärdata är obligatoriskt.',
            'accept_terms.accepted' => 'Du måste acceptera användarvillkoren.',
        ];
    }
}

