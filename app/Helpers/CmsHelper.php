<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\PageContent;
use App\Models\SiteSetting;

class CmsHelper
{
    /**
     * Get a site setting value
     */
    public static function setting(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }

    /**
     * Get page content by key
     */
    public static function page(string $key): ?PageContent
    {
        return PageContent::getByKey($key);
    }

    /**
     * Get all settings as array
     */
    public static function settings(): array
    {
        return SiteSetting::getAllSettings();
    }

    /**
     * Check if setting exists and is truthy
     */
    public static function isEnabled(string $key): bool
    {
        return (bool) self::setting($key, false);
    }

    /**
     * Get social media links
     */
    public static function socialMedia(): array
    {
        return [
            'facebook' => self::setting('social_facebook'),
            'instagram' => self::setting('social_instagram'),
            'linkedin' => self::setting('social_linkedin'),
            'twitter' => self::setting('social_twitter'),
        ];
    }

    /**
     * Get contact information
     */
    public static function contact(): array
    {
        return [
            'address' => self::setting('contact_address'),
            'phone' => self::setting('contact_phone'),
            'email' => self::setting('contact_email'),
            'support_email' => self::setting('contact_support_email'),
            'hours' => self::setting('contact_hours'),
        ];
    }
}

