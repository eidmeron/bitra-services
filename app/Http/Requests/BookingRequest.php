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
            'customer_type' => 'required|in:private,company',
            'org_number' => 'required_if:customer_type,company|nullable|string|regex:/^[0-9]{6}-[0-9]{4}$/',
            'personnummer' => 'required_if:apply_rot,true|nullable|string|regex:/^[0-9]{8}-[0-9]{4}$/',
            'booking_type' => 'required|in:one_time,subscription',
            'subscription_frequency' => 'required_if:booking_type,subscription|in:daily,weekly,biweekly,monthly',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'customer_name' => 'sometimes|string|max:255',
            'customer_email' => 'sometimes|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_message' => 'nullable|string|max:1000',
            'preferred_date' => 'nullable|date|after:now',
            'form_data' => 'required|array|min:1',
            'apply_rot' => 'nullable|boolean',
            'accept_terms' => 'required|accepted',
            
            // Company selection
            'company_id' => 'nullable|exists:companies,id',
            'auto_select_company' => 'nullable|in:0,1',
        ];
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure form_data is set as array
        if (!$this->has('form_data')) {
            $this->merge(['form_data' => []]);
        }
        
        // Convert apply_rot to boolean if it's a string
        if ($this->has('apply_rot') && is_string($this->apply_rot)) {
            $this->merge(['apply_rot' => filter_var($this->apply_rot, FILTER_VALIDATE_BOOLEAN)]);
        }
        
        // Map name and email to customer_name and customer_email
        if ($this->has('name')) {
            $this->merge(['customer_name' => $this->input('name')]);
        }
        
        if ($this->has('email')) {
            $this->merge(['customer_email' => $this->input('email')]);
        }
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
            'customer_type.required' => 'Kundtyp är obligatoriskt.',
            'customer_type.in' => 'Ogiltig kundtyp.',
            'org_number.required_if' => 'Organisationsnummer är obligatoriskt för företag.',
            'org_number.regex' => 'Organisationsnummer måste vara i formatet 123456-7890.',
            'personnummer.required_if' => 'Personnummer är obligatoriskt för ROT-avdrag.',
            'personnummer.regex' => 'Personnummer måste vara i formatet YYYYMMDD-XXXX.',
            'booking_type.required' => 'Bokningstyp är obligatoriskt.',
            'booking_type.in' => 'Ogiltig bokningstyp.',
            'subscription_frequency.required_if' => 'Frekvens är obligatoriskt för prenumeration.',
            'name.required' => 'Namn är obligatoriskt.',
            'email.required' => 'E-post är obligatoriskt.',
            'email.email' => 'E-post måste vara en giltig e-postadress.',
            'customer_phone.required' => 'Telefonnummer är obligatoriskt.',
            'customer_message.max' => 'Meddelandet får vara högst 1000 tecken.',
            'preferred_date.after' => 'Önskat datum måste vara i framtiden.',
            'form_data.required' => 'Formulärdata är obligatoriskt.',
            'accept_terms.accepted' => 'Du måste acceptera användarvillkoren.',
        ];
    }
}

