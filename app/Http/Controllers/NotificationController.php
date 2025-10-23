<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class NotificationController extends Controller
{
    /**
     * Mark a notification as read and redirect to URL
     */
    public function markAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        $notification->markAsRead();
        
        $url = $notification->data['url'] ?? '#';
        
        // Handle relative URLs properly
        if (str_starts_with($url, '/')) {
            return redirect($url);
        }
        
        // Handle absolute URLs that might have localhost
        if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            $url = str_replace(['http://localhost', 'https://localhost', 'http://127.0.0.1', 'https://127.0.0.1'], '', $url);
            return redirect($url);
        }
        
        return redirect($url);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return back()->with('success', 'Alla notifikationer markerade som lästa');
    }
}

