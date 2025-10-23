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
                'redirect_after_submit' => isset($data['redirect_after_submit']) ? (bool)$data['redirect_after_submit'] : $form->redirect_after_submit,
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
                'field_type' => $fieldData['type'] ?? $fieldData['field_type'] ?? 'text',
                'field_label' => $fieldData['label'] ?? $fieldData['field_label'] ?? '',
                'field_name' => $fieldData['name'] ?? $fieldData['field_name'] ?? '',
                'placeholder_text' => $fieldData['placeholder'] ?? $fieldData['placeholder_text'] ?? null,
                'help_text' => $fieldData['helpText'] ?? $fieldData['help_text'] ?? null,
                'field_width' => $this->normalizeFieldWidth($fieldData['width'] ?? $fieldData['field_width'] ?? '100'),
                'required' => $fieldData['required'] ?? false,
                'sort_order' => $index,
                'pricing_rules' => $fieldData['pricingRules'] ?? $fieldData['pricing_rules'] ?? null,
                'conditional_logic' => $fieldData['conditionalLogic'] ?? $fieldData['conditional_logic'] ?? null,
                'validation_rules' => $fieldData['validationRules'] ?? $fieldData['validation_rules'] ?? null,
                'field_options' => $fieldData['options'] ?? $fieldData['field_options'] ?? null,
                'parent_id' => $fieldData['parent_id'] ?? null,
                'step_number' => $fieldData['step_number'] ?? null,
            ]);
        }
    }

    /**
     * Normalize field width values
     */
    private function normalizeFieldWidth(string|int $width): string
    {
        // Convert text values to numeric
        $widthMap = [
            'full' => '100',
            'half' => '50',
            'third' => '33',
            'quarter' => '25',
        ];

        if (is_string($width) && isset($widthMap[$width])) {
            return $widthMap[$width];
        }

        return (string)$width;
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

    /**
     * Duplicate an existing form with all its fields
     */
    public function duplicateForm(Form $form): Form
    {
        return DB::transaction(function () use ($form) {
            // Generate unique slug for duplicate
            $baseSlug = $form->form_slug . '-kopia';
            $slug = $baseSlug;
            $counter = 1;
            
            while (Form::where('form_slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // Create duplicate form
            $duplicateForm = Form::create([
                'service_id' => $form->service_id,
                'form_name' => $form->form_name . ' (Kopia)',
                'form_slug' => $slug,
                'form_schema' => $form->form_schema,
                'success_message' => $form->success_message,
                'redirect_after_submit' => $form->redirect_after_submit,
                'redirect_url' => $form->redirect_url,
                'custom_styles' => $form->custom_styles,
                'status' => 'draft', // Always set to draft for duplicates
            ]);

            // Duplicate all fields
            foreach ($form->fields as $field) {
                FormField::create([
                    'form_id' => $duplicateForm->id,
                    'field_type' => $field->field_type,
                    'field_label' => $field->field_label,
                    'field_name' => $field->field_name,
                    'placeholder_text' => $field->placeholder_text,
                    'help_text' => $field->help_text,
                    'field_width' => $field->field_width,
                    'required' => $field->required,
                    'sort_order' => $field->sort_order,
                    'pricing_rules' => $field->pricing_rules,
                    'conditional_logic' => $field->conditional_logic,
                    'field_options' => $field->field_options,
                    'validation_rules' => $field->validation_rules,
                    'parent_id' => $field->parent_id,
                    'step_number' => $field->step_number,
                ]);
            }

            return $duplicateForm->fresh('fields');
        });
    }

    /**
     * Add a new field to the form
     */
    public function addField(Form $form, array $data): FormField
    {
        $sortOrder = $data['sort_order'] ?? $form->fields()->max('sort_order') + 1;

        return FormField::create([
            'form_id' => $form->id,
            'field_type' => $data['field_type'],
            'field_label' => $data['field_label'],
            'field_name' => $data['field_name'],
            'placeholder_text' => $data['placeholder_text'] ?? null,
            'help_text' => $data['help_text'] ?? null,
            'field_width' => $this->normalizeFieldWidth($data['field_width'] ?? 'full'),
            'required' => $data['required'] ?? false,
            'sort_order' => $sortOrder,
            'pricing_rules' => $data['pricing_rules'] ?? null,
            'conditional_logic' => $data['conditional_logic'] ?? null,
            'field_options' => $data['field_options'] ?? null,
            'validation_rules' => $data['validation_rules'] ?? null,
        ]);
    }

    /**
     * Apply conditional logic to form fields based on user input
     */
    public function applyConditionalLogic(Form $form, array $formData): array
    {
        $visibleFields = [];

        foreach ($form->fields as $field) {
            $isVisible = true;

            if ($field->conditional_logic) {
                $logic = is_string($field->conditional_logic) 
                    ? json_decode($field->conditional_logic, true) 
                    : $field->conditional_logic;

                if ($logic && isset($logic['rules'])) {
                    $isVisible = $this->evaluateConditionalRules($logic['rules'], $formData, $logic['operator'] ?? 'and');
                }
            }

            if ($isVisible) {
                $visibleFields[] = $field->id;
            }
        }

        return $visibleFields;
    }

    /**
     * Evaluate conditional rules
     */
    private function evaluateConditionalRules(array $rules, array $formData, string $operator = 'and'): bool
    {
        $results = [];

        foreach ($rules as $rule) {
            $fieldName = $rule['field'] ?? '';
            $condition = $rule['condition'] ?? '';
            $value = $rule['value'] ?? '';
            $userValue = $formData[$fieldName] ?? null;

            $result = match($condition) {
                'equals' => $userValue == $value,
                'not_equals' => $userValue != $value,
                'contains' => str_contains((string)$userValue, (string)$value),
                'not_contains' => !str_contains((string)$userValue, (string)$value),
                'greater_than' => (float)$userValue > (float)$value,
                'less_than' => (float)$userValue < (float)$value,
                'is_empty' => empty($userValue),
                'is_not_empty' => !empty($userValue),
                default => true,
            };

            $results[] = $result;
        }

        return $operator === 'and' 
            ? !in_array(false, $results, true)
            : in_array(true, $results, true);
    }
}

