<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\URL;

final class NotificationHelper
{
    /**
     * Generate a proper URL for notifications
     * This ensures URLs use the correct domain instead of localhost
     */
    public static function generateUrl(string $route, array $parameters = []): string
    {
        // Use the current request's host if available, otherwise use APP_URL
        $host = request()->getHost();
        $scheme = request()->getScheme();
        
        // If we're in a queue job or command, use the configured APP_URL
        if (!$host || $host === 'localhost' || $host === '127.0.0.1') {
            $appUrl = config('app.url');
            $parsedUrl = parse_url($appUrl);
            $host = $parsedUrl['host'] ?? '127.0.0.1:8000';
            $scheme = $parsedUrl['scheme'] ?? 'http';
        }
        
        // Generate the route URL
        $url = route($route, $parameters);
        
        // Replace the host if needed
        if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            $url = str_replace(['http://localhost', 'https://localhost', 'http://127.0.0.1', 'https://127.0.0.1'], "{$scheme}://{$host}", $url);
        }
        
        return $url;
    }
    
    /**
     * Generate a relative URL for notifications (safer for different environments)
     */
    public static function generateRelativeUrl(string $route, array $parameters = []): string
    {
        return route($route, $parameters, false);
    }
}

