<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
        $serviceId = $this->route('service')?->id;

        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services', 'slug')->ignore($serviceId),
            ],
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'base_price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'one_time_booking' => 'nullable|boolean',
            'subscription_booking' => 'nullable|boolean',
            'rot_eligible' => 'nullable|boolean',
            'rot_percent' => 'nullable|numeric|min:0|max:100',
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
            'category_id.required' => 'Kategori är obligatoriskt.',
            'category_id.exists' => 'Vald kategori är ogiltig.',
            'name.required' => 'Namn är obligatoriskt.',
            'slug.required' => 'Slug är obligatoriskt.',
            'slug.unique' => 'Slug används redan.',
            'status.required' => 'Status är obligatoriskt.',
            'base_price.required' => 'Grundpris är obligatoriskt.',
            'base_price.numeric' => 'Grundpriset måste vara ett nummer.',
            'base_price.min' => 'Grundpriset kan inte vara negativt.',
            'image.image' => 'Bilden måste vara en giltig bild.',
            'image.max' => 'Bilden får inte vara större än 2MB.',
        ];
    }
}

