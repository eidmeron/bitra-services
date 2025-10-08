<?php

declare(strict_types=1);

if (!function_exists('formatPrice')) {
    /**
     * Format price with Swedish currency
     */
    function formatPrice(float $price): string
    {
        return number_format($price, 2, ',', ' ') . ' kr';
    }
}

if (!function_exists('bookingStatusBadge')) {
    /**
     * Get HTML badge for booking status
     */
    function bookingStatusBadge(string $status): string
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Väntande</span>',
            'assigned' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Tilldelad</span>',
            'in_progress' => '<span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Pågående</span>',
            'completed' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Slutförd</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Avbruten</span>',
            'rejected' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Avvisad</span>',
        ];

        return $badges[$status] ?? $status;
    }
}

if (!function_exists('companyStatusBadge')) {
    /**
     * Get HTML badge for company status
     */
    function companyStatusBadge(string $status): string
    {
        $badges = [
            'active' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktiv</span>',
            'inactive' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inaktiv</span>',
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Väntande</span>',
        ];

        return $badges[$status] ?? $status;
    }
}

if (!function_exists('reviewStars')) {
    /**
     * Generate star rating HTML
     */
    function reviewStars(int $rating, int $maxRating = 5): string
    {
        $stars = '';
        for ($i = 1; $i <= $maxRating; $i++) {
            if ($i <= $rating) {
                $stars .= '<span class="text-yellow-400">★</span>';
            } else {
                $stars .= '<span class="text-gray-300">★</span>';
            }
        }
        return $stars;
    }
}

if (!function_exists('getSubscriptionFrequencyLabel')) {
    /**
     * Get Swedish label for subscription frequency
     */
    function getSubscriptionFrequencyLabel(?string $frequency): string
    {
        $labels = [
            'daily' => 'Dagligen',
            'weekly' => 'Veckovis',
            'biweekly' => 'Varannan vecka',
            'monthly' => 'Månadsvis',
        ];

        return $labels[$frequency] ?? 'Engångstjänst';
    }
}

