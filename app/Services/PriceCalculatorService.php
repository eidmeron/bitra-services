<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\City;
use App\Models\FormField;
use App\Models\Service;

class PriceCalculatorService
{
    /**
     * Calculate total price based on formula:
     * Total = ((Base + Variables) × City_Multiplier) - ((Base + Variables) × ROT%) - Discount
     */
    public function calculate(array $data): array
    {
        $service = Service::findOrFail($data['service_id']);
        $city = City::findOrFail($data['city_id']);

        $basePrice = $service->base_price;
        $variableAdditions = $this->calculateVariableAdditions($data['form_data'] ?? []);
        $cityMultiplier = $city->city_multiplier;

        // Subtotal before deductions
        $subtotal = ($basePrice + $variableAdditions) * $cityMultiplier;

        // ROT deduction (if eligible)
        $rotDeduction = 0;
        if ($service->rot_eligible && ($data['apply_rot'] ?? false)) {
            $rotDeduction = ($basePrice + $variableAdditions) * ($service->rot_percent / 100);
        }

        // Discount
        $discountAmount = 0;
        if ($service->discount_percent > 0) {
            $discountAmount = ($basePrice + $variableAdditions) * ($service->discount_percent / 100);
        }

        $finalPrice = $subtotal - $rotDeduction - $discountAmount;

        return [
            'base_price' => (float) $basePrice,
            'variable_additions' => $variableAdditions,
            'city_multiplier' => (float) $cityMultiplier,
            'subtotal' => $subtotal,
            'rot_deduction' => $rotDeduction,
            'discount_amount' => $discountAmount,
            'final_price' => max(0, $finalPrice), // Never negative
            'breakdown' => $this->getBreakdown($data['form_data'] ?? []),
        ];
    }

    private function calculateVariableAdditions(array $formData): float
    {
        $total = 0;

        foreach ($formData as $fieldName => $value) {
            $field = FormField::where('field_name', $fieldName)->first();

            if (!$field || !$field->pricing_rules) {
                continue;
            }

            $pricingRules = $field->pricing_rules;

            switch ($field->field_type) {
                case 'number':
                    // Example: price per unit
                    $total += (float) $value * ($pricingRules['price_per_unit'] ?? 0);
                    break;

                case 'select':
                case 'radio':
                    // Example: {options: [{value: 'small', price: 100}, ...]}
                    $option = collect($pricingRules['options'] ?? [])
                        ->firstWhere('value', $value);
                    $total += $option['price'] ?? 0;
                    break;

                case 'checkbox':
                    // Multiple selections
                    $values = is_array($value) ? $value : [$value];
                    foreach ($values as $val) {
                        $option = collect($pricingRules['options'] ?? [])
                            ->firstWhere('value', $val);
                        $total += $option['price'] ?? 0;
                    }
                    break;

                case 'slider':
                    $total += (float) $value * ($pricingRules['price_per_unit'] ?? 0);
                    break;
            }
        }

        return $total;
    }

    private function getBreakdown(array $formData): array
    {
        $breakdown = [];

        foreach ($formData as $fieldName => $value) {
            $field = FormField::where('field_name', $fieldName)->first();

            if (!$field || !$field->pricing_rules) {
                continue;
            }

            $pricingRules = $field->pricing_rules;

            // Add to breakdown for display
            $breakdown[] = [
                'field_label' => $field->field_label,
                'value' => $value,
                'price' => $this->calculateFieldPrice($field, $value, $pricingRules),
            ];
        }

        return $breakdown;
    }

    private function calculateFieldPrice(FormField $field, $value, array $pricingRules): float
    {
        switch ($field->field_type) {
            case 'number':
                return (float) $value * ($pricingRules['price_per_unit'] ?? 0);

            case 'select':
            case 'radio':
                $option = collect($pricingRules['options'] ?? [])
                    ->firstWhere('value', $value);
                return $option['price'] ?? 0;

            case 'checkbox':
                $total = 0;
                $values = is_array($value) ? $value : [$value];
                foreach ($values as $val) {
                    $option = collect($pricingRules['options'] ?? [])
                        ->firstWhere('value', $val);
                    $total += $option['price'] ?? 0;
                }
                return $total;

            case 'slider':
                return (float) $value * ($pricingRules['price_per_unit'] ?? 0);

            default:
                return 0;
        }
    }
}

