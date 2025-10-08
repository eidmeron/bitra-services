<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormBuilderService
{
    public function createForm(array $data): Form
    {
        return DB::transaction(function () use ($data) {
            // Generate unique slug
            $baseSlug = Str::slug($data['form_name']);
            $slug = $baseSlug;
            $counter = 1;
            
            while (Form::where('form_slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $form = Form::create([
                'service_id' => $data['service_id'],
                'form_name' => $data['form_name'],
                'form_slug' => $slug,
                'form_schema' => $data['form_schema'] ?? [],
                'success_message' => $data['success_message'] ?? 'Tack för din bokning!',
                'redirect_after_submit' => $data['redirect_after_submit'] ?? false,
                'redirect_url' => $data['redirect_url'] ?? null,
                'custom_styles' => $data['custom_styles'] ?? null,
                'status' => $data['status'] ?? 'draft',
            ]);

            // Create form fields if provided
            if (!empty($data['fields'])) {
                $this->createFormFields($form, $data['fields']);
            }

            return $form;
        });
    }

    public function updateForm(Form $form, array $data): Form
    {
        return DB::transaction(function () use ($form, $data) {
            // Generate unique slug if name changed
            $slug = $form->form_slug;
            if (isset($data['form_name']) && $data['form_name'] !== $form->form_name) {
                $baseSlug = Str::slug($data['form_name']);
                $slug = $baseSlug;
                $counter = 1;
                
                while (Form::where('form_slug', $slug)->where('id', '!=', $form->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
            }
            
            $form->update([
                'form_name' => $data['form_name'] ?? $form->form_name,
                'form_slug' => $slug,
                'form_schema' => $data['form_schema'] ?? $form->form_schema,
                'success_message' => $data['success_message'] ?? $form->success_message,
                'redirect_after_submit' => $data['redirect_after_submit'] ?? $form->redirect_after_submit,
                'redirect_url' => $data['redirect_url'] ?? $form->redirect_url,
                'custom_styles' => $data['custom_styles'] ?? $form->custom_styles,
                'status' => $data['status'] ?? $form->status,
            ]);

            // Update form fields if provided
            if (isset($data['fields'])) {
                // Delete existing fields
                $form->fields()->delete();
                // Create new fields
                $this->createFormFields($form, $data['fields']);
            }

            return $form->fresh();
        });
    }

    private function createFormFields(Form $form, array $fields): void
    {
        foreach ($fields as $index => $fieldData) {
            FormField::create([
                'form_id' => $form->id,
                'field_type' => $fieldData['field_type'] ?? 'text',
                'field_label' => $fieldData['field_label'],
                'field_name' => $fieldData['field_name'],
                'placeholder_text' => $fieldData['placeholder_text'] ?? null,
                'help_text' => $fieldData['help_text'] ?? null,
                'field_width' => $fieldData['field_width'] ?? '100',
                'required' => $fieldData['required'] ?? false,
                'sort_order' => $index,
                'pricing_rules' => $fieldData['pricing_rules'] ?? null,
                'conditional_logic' => $fieldData['conditional_logic'] ?? null,
                'field_options' => $fieldData['field_options'] ?? null,
                'parent_id' => $fieldData['parent_id'] ?? null,
                'step_number' => $fieldData['step_number'] ?? null,
            ]);
        }
    }

    public function validateFormData(Form $form, array $data): array
    {
        $rules = [];
        $messages = [];

        foreach ($form->fields as $field) {
            if ($field->required) {
                $rules[$field->field_name] = 'required';
                $messages[$field->field_name . '.required'] = "{$field->field_label} är obligatoriskt.";
            }

            // Add field-specific validation rules
            switch ($field->field_type) {
                case 'email':
                    $rules[$field->field_name] = ($rules[$field->field_name] ?? '') . '|email';
                    break;
                case 'phone':
                    $rules[$field->field_name] = ($rules[$field->field_name] ?? '') . '|regex:/^[0-9+\-\s()]+$/';
                    break;
                case 'number':
                case 'slider':
                    $rules[$field->field_name] = ($rules[$field->field_name] ?? '') . '|numeric';
                    break;
                case 'file':
                    $rules[$field->field_name] = ($rules[$field->field_name] ?? '') . '|file|max:10240'; // 10MB
                    break;
            }
        }

        return ['rules' => $rules, 'messages' => $messages];
    }
}

