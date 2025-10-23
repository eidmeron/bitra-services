<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Company;
use App\Models\Review;
use App\Models\User;
use App\Models\PlatformReview;
use App\Models\BookingChat;
use Illuminate\Support\Facades\Cache;

final class NotificationService
{
    public function getNotificationCounts(): array
    {
        return Cache::remember('admin_notification_counts', 300, function () {
            return [
                'new_bookings' => Booking::whereDate('created_at', today())->count(),
                'new_users' => User::whereDate('created_at', today())->count(),
                'new_companies' => Company::whereDate('created_at', today())->count(),
                'new_company_reviews' => Review::whereDate('created_at', today())->count(),
                'new_platform_reviews' => PlatformReview::whereDate('created_at', today())->count(),
                'pending_company_reviews' => Review::where('status', 'pending')->count(),
                'pending_platform_reviews' => PlatformReview::where('status', 'pending')->count(),
                'pending_companies' => Company::where('status', 'pending')->count(),
            ];
        });
    }

    public function getTotalNotifications(): int
    {
        $counts = $this->getNotificationCounts();
        return $counts['new_bookings'] + 
               $counts['new_users'] + 
               $counts['new_companies'] + 
               $counts['new_company_reviews'] + 
               $counts['new_platform_reviews'] + 
               $counts['pending_company_reviews'] + 
               $counts['pending_platform_reviews'] + 
               $counts['pending_companies'];
    }

    public function clearCache(): void
    {
        Cache::forget('admin_notification_counts');
    }

    public function notifyNewChatMessage(BookingChat $chat, Booking $booking): void
    {
        // Send notification to company
        if ($booking->company && $booking->company->user) {
            $booking->company->user->notify(new \App\Notifications\NewChatMessageNotification($chat, $booking));
        }
    }
}