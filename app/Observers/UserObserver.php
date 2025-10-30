<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Services\NotificationService;
use App\Services\LoyaltyPointService;

class UserObserver
{
    public function __construct(
        private NotificationService $notificationService,
        private LoyaltyPointService $loyaltyPointService
    ) {}

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        try {
            // Award welcome loyalty points
            $welcomePoints = 100; // Welcome bonus
            $this->loyaltyPointService->awardWelcomePoints($user, $welcomePoints);
            
            // Send welcome notification
            $this->notificationService->sendNewUserWelcomeNotification($user);
            
            \Log::info("New user {$user->id} registered - welcome points awarded and notification sent");
            
        } catch (\Exception $e) {
            \Log::error("Failed to process new user registration {$user->id}: " . $e->getMessage());
        }
    }
}
