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
     * Total = ((Base + Variables) × Subscription_Multiplier × City_Multiplier) - ROT - Discount + Extra Fees
     * VAT is calculated on the final amount after all deductions and additions
     */
    public function calculate(array $data): array
    {
        $service = Service::findOrFail($data['service_id']);
        $city = City::findOrFail($data['city_id']);
        $formId = isset($data['form_id']) ? (int) $data['form_id'] : null;

        $basePrice = $service->base_price;
        $variableAdditions = $this->calculateVariableAdditions($data['form_data'] ?? [], $formId);
        
        // Get subscription multiplier (if applicable)
        $subscriptionMultiplier = $this->getSubscriptionMultiplier($service, $data);
        
        $cityMultiplier = $city->city_multiplier;
        
        // Get slot time price multiplier (if applicable)
        $slotTimeMultiplier = 1.00;
        if (isset($data['slot_time_id']) && $data['slot_time_id']) {
            $slotTime = \App\Models\SlotTime::find($data['slot_time_id']);
            if ($slotTime && $slotTime->price_multiplier) {
                $slotTimeMultiplier = $slotTime->price_multiplier;
            }
        }

        // Calculate subtotal: (Base + Variables) × Subscription × City × Slot Time
        $beforeMultipliers = $basePrice + $variableAdditions;
        $afterSubscription = $beforeMultipliers * $subscriptionMultiplier;
        $afterCity = $afterSubscription * $cityMultiplier;
        $subtotal = $afterCity * $slotTimeMultiplier;

        // ROT deduction (applied to base + variables, before multipliers)
        $rotDeduction = 0;
        if ($service->rot_eligible && ($data['apply_rot'] ?? false)) {
            $rotDeduction = $beforeMultipliers * ($service->rot_percent / 100);
        }

        // Discount (applied to base + variables, before multipliers)
        $discountAmount = 0;
        if ($service->discount_percent > 0) {
            $discountAmount = $beforeMultipliers * ($service->discount_percent / 100);
        }

        // Loyalty Points Discount
        $loyaltyPointsUsed = (float) ($data['loyalty_points_used'] ?? 0);
        $loyaltyPointsDiscount = $loyaltyPointsUsed; // 1 point = 1 SEK

        // Apply all deductions first (ROT, discount, loyalty points)
        $subtotalAfterDeductions = $subtotal - $rotDeduction - $discountAmount - $loyaltyPointsDiscount;
        
        // Swedish VAT calculation: Total includes VAT, so we need to extract VAT from total
        $taxRate = (float) ($service->tax_rate ?? 25.00);
        
        // If subtotal after deductions is the final amount that should include VAT:
        // VAT = Total / (1 + VAT_rate) * VAT_rate
        // Base amount = Total / (1 + VAT_rate)
        $finalPrice = $subtotalAfterDeductions; // This is the total that includes VAT
        $baseAmount = $finalPrice / (1 + ($taxRate / 100)); // Extract base amount
        $taxAmount = $finalPrice - $baseAmount; // Calculate VAT amount

        return [
            'base_price' => (float) $basePrice,
            'variable_additions' => $variableAdditions,
            'subscription_multiplier' => (float) $subscriptionMultiplier,
            'subscription_type' => $data['subscription_frequency'] ?? null,
            'city_multiplier' => (float) $cityMultiplier,
            'slot_time_multiplier' => (float) $slotTimeMultiplier,
            'subtotal' => $subtotal,
            'subtotal_after_deductions' => $subtotalAfterDeductions,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'rot_deduction' => $rotDeduction,
            'discount_amount' => $discountAmount,
            'loyalty_points_used' => $loyaltyPointsUsed,
            'loyalty_points_value' => $loyaltyPointsUsed, // Value in SEK
            'loyalty_points_discount' => $loyaltyPointsDiscount,
            'final_price' => max(0, $finalPrice), // Never negative
            'breakdown' => $this->getBreakdown($data['form_data'] ?? [], $formId),
        ];
    }

    private function getSubscriptionMultiplier(Service $service, array $data): float
    {
        // Check if it's a subscription booking
        if (($data['booking_type'] ?? 'one_time') !== 'subscription') {
            return 1.00; // No subscription multiplier for one-time bookings
        }

        // Get the subscription frequency
        $frequency = $data['subscription_frequency'] ?? 'weekly';

        // Return the appropriate multiplier
        return match($frequency) {
            'daily' => (float) $service->daily_multiplier,
            'weekly' => (float) $service->weekly_multiplier,
            'biweekly' => (float) $service->biweekly_multiplier,
            'monthly' => (float) $service->monthly_multiplier,
            default => 1.00,
        };
    }

    private function calculateVariableAdditions(array $formData, ?int $formId = null): float
    {
        $total = 0;

        foreach ($formData as $fieldName => $value) {
            // Look up field by form_id and field_name for accuracy
            $query = FormField::where('field_name', $fieldName);
            
            if ($formId) {
                $query->where('form_id', $formId);
            }
            
            $field = $query->first();

            if (!$field || !$field->pricing_rules) {
                continue;
            }

            $pricingRules = $field->pricing_rules;

            switch ($field->field_type) {
                case 'number':
                case 'slider':
                    // Price per unit
                    $pricePerUnit = $pricingRules['pricePerUnit'] ?? $pricingRules['price_per_unit'] ?? 0;
                    $total += (float) $value * (float) $pricePerUnit;
                    break;

                case 'select':
                case 'radio':
                    // Find option price
                    $options = $field->field_options ?? $pricingRules['options'] ?? [];
                    $option = collect($options)->firstWhere('value', $value);
                    $total += (float) ($option['price'] ?? 0);
                    break;

                case 'checkbox':
                    // Multiple selections
                    $values = is_array($value) ? $value : [$value];
                    $options = $field->field_options ?? $pricingRules['options'] ?? [];
                    foreach ($values as $val) {
                        $option = collect($options)->firstWhere('value', $val);
                        $total += (float) ($option['price'] ?? 0);
                    }
                    break;
            }
        }

        return $total;
    }

    private function getBreakdown(array $formData, ?int $formId = null): array
    {
        $breakdown = [];

        foreach ($formData as $fieldName => $value) {
            // Look up field by form_id and field_name for accuracy
            $query = FormField::where('field_name', $fieldName);
            
            if ($formId) {
                $query->where('form_id', $formId);
            }
            
            $field = $query->first();

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
            case 'slider':
                $pricePerUnit = $pricingRules['pricePerUnit'] ?? $pricingRules['price_per_unit'] ?? 0;
                return (float) $value * (float) $pricePerUnit;

            case 'select':
            case 'radio':
                $options = $field->field_options ?? $pricingRules['options'] ?? [];
                $option = collect($options)->firstWhere('value', $value);
                return (float) ($option['price'] ?? 0);

            case 'checkbox':
                $total = 0;
                $values = is_array($value) ? $value : [$value];
                $options = $field->field_options ?? $pricingRules['options'] ?? [];
                foreach ($values as $val) {
                    $option = collect($options)->firstWhere('value', $val);
                    $total += (float) ($option['price'] ?? 0);
                }
                return $total;

            default:
                return 0;
        }
    }

    /**
     * Apply loyalty points and return the actual number of points to use
     */
    private function applyLoyaltyPoints(int $userId, float $pointsToUse, float $maxAmount): float
    {
        // Check if loyalty points are enabled
        if (!\App\Models\SiteSetting::get('loyalty_points_enabled', true)) {
            return 0;
        }

        // Get user's available points
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return 0;
        }

        $availablePoints = $user->loyalty_points_balance;
        
        // Can't use more points than available
        $pointsToUse = min($pointsToUse, $availablePoints);

        // Get point value
        $pointValue = (float) \App\Models\SiteSetting::get('loyalty_points_value', 1);
        $maxDiscountFromPoints = $maxAmount; // Can't discount more than the remaining price

        // Calculate maximum points that can be used based on remaining price
        $maxPointsUsable = $maxDiscountFromPoints / $pointValue;

        // Use the minimum of requested points and maximum usable points
        return min($pointsToUse, $maxPointsUsable);
    }
}

