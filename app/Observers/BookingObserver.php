<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Booking;
use App\Services\DepositService;
use App\Services\LoyaltyPointService;
use App\Services\NotificationService;

class BookingObserver
{
    public function __construct(
        private DepositService $depositService,
        private LoyaltyPointService $loyaltyPointService,
        private NotificationService $notificationService
    ) {}

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Check if booking status changed to completed
        if ($booking->isDirty('status') && $booking->status === 'completed') {
            $this->handleBookingCompleted($booking);
        }
        
        // Check if booking status changed to cancelled
        if ($booking->isDirty('status') && $booking->status === 'cancelled') {
            $this->handleBookingCancelled($booking);
        }
    }

    /**
     * Handle booking completion
     */
    private function handleBookingCompleted(Booking $booking): void
    {
        try {
            // Create deposit for the company
            $this->depositService->createDepositForBooking($booking);
            
            // Award loyalty points to the user
            if ($booking->user_id) {
                $pointsToEarn = $this->loyaltyPointService->calculatePointsToEarn($booking->final_price);
                if ($pointsToEarn > 0) {
                    $this->loyaltyPointService->earnPointsForBooking($booking, $pointsToEarn);
                    
                    // Send loyalty points earned notification
                    $this->notificationService->sendLoyaltyPointsEarnedNotification(
                        $booking->user, 
                        $pointsToEarn, 
                        $booking
                    );
                }
            }
            
            // Send booking completion notifications
            $this->notificationService->sendBookingCompletionNotification($booking);
            
            \Log::info("Booking {$booking->id} completed - deposit created, loyalty points awarded, and notifications sent");
            
        } catch (\Exception $e) {
            \Log::error("Failed to process completed booking {$booking->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle booking cancellation
     */
    private function handleBookingCancelled(Booking $booking): void
    {
        try {
            // Refund loyalty points if they were used
            if ($booking->user_id && $booking->loyalty_points_used > 0) {
                $this->loyaltyPointService->refundPointsForBooking($booking);
            }
            
            \Log::info("Booking {$booking->id} cancelled - loyalty points refunded");
            
        } catch (\Exception $e) {
            \Log::error("Failed to process cancelled booking {$booking->id}: " . $e->getMessage());
        }
    }
}