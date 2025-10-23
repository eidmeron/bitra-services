<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        // Redirect to notification URL if available
        if (isset($notification->data['url'])) {
            $url = $notification->data['url'];
            
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

        return redirect()->back();
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return redirect()->back()->with('success', 'Alla notifikationer markerade som lästa.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notifikation raderad.');
    }
}

