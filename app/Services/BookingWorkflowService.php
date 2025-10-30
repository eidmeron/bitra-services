<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Company;
use App\Models\User;
use App\Notifications\BookingAssignedNotification;
use App\Notifications\BookingStatusChangedNotification;
use App\Notifications\NewBookingNotification;
use App\Notifications\BookingCompletedNotification;
use App\Notifications\CompanyWorkSummaryNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class BookingWorkflowService
{
    public function __construct(
        private PriceCalculatorService $priceCalculator
    ) {
    }

    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // Calculate pricing
            $pricing = $this->priceCalculator->calculate($data);

            // Determine company selection
            $companyId = null;
            $shouldAutoAssign = setting('booking_auto_assign', false);
            
            // Check if auto-select company is enabled
            if (!empty($data['auto_select_company']) || !empty($data['company_id'])) {
                if (!empty($data['auto_select_company']) && $data['auto_select_company'] === '1') {
                    // Find best company automatically (highest rated)
                    $companyId = $this->findBestCompany((int) $data['service_id'], (int) $data['city_id']);
                } elseif (!empty($data['company_id'])) {
                    // Use customer-selected company
                    $companyId = (int) $data['company_id'];
                }
            }

            // Determine initial status
            $status = 'pending';
            $assignedAt = null;
            
            if ($companyId && $shouldAutoAssign) {
                // Auto-assign if company is selected and setting is enabled
                $status = 'assigned';
                $assignedAt = now();
            } elseif ($companyId) {
                // Company selected but no auto-assign, set to assigned manually
                $assignedAt = now();
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'company_id' => $companyId,
                'service_id' => $data['service_id'],
                'form_id' => $data['form_id'],
                'city_id' => $data['city_id'],
                'customer_type' => $data['customer_type'],
                'org_number' => $data['org_number'] ?? null,
                'personnummer' => $data['personnummer'] ?? null,
                'booking_type' => $data['booking_type'],
                'subscription_frequency' => $data['subscription_frequency'] ?? null,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'customer_message' => $data['customer_message'] ?? null,
                'form_data' => $data['form_data'],
                'preferred_date' => $data['preferred_date'] ?? null,
                'base_price' => $pricing['base_price'],
                'variable_additions' => $pricing['variable_additions'],
                'city_multiplier' => $pricing['city_multiplier'],
                'subscription_multiplier' => $pricing['subscription_multiplier'],
                'subtotal' => $pricing['subtotal'],
                'tax_rate' => $pricing['tax_rate'],
                'tax_amount' => $pricing['tax_amount'],
                'rot_deduction' => $pricing['rot_deduction'],
                'discount_amount' => $pricing['discount_amount'],
                'loyalty_points_used' => $pricing['loyalty_points_used'] ?? 0,
                'loyalty_points_value' => $pricing['loyalty_points_value'] ?? 0,
                'final_price' => $pricing['final_price'],
                'total_with_tax' => $pricing['final_price'],
                'status' => $status,
                'assigned_at' => $assignedAt,
            ]);

            // Notify admin
            $this->notifyAdmin($booking);
            
            // If auto-assigned, notify the company
            if ($companyId && $status === 'assigned') {
                $company = Company::find($companyId);
                if ($company && $company->user) {
                    $company->user->notify(new BookingAssignedNotification($booking));
                    Log::info("Booking {$booking->booking_number} auto-assigned to company {$companyId}");
                }
            }

            return $booking;
        });
    }
    
    /**
     * Find the best company for a service in a city
     * (highest rated, most reviews, active status)
     */
    private function findBestCompany(int $serviceId, int $cityId): ?int
    {
        $company = Company::where('status', 'active')
            ->whereHas('services', function ($query) use ($serviceId) {
                $query->where('services.id', $serviceId);
            })
            ->whereHas('cities', function ($query) use ($cityId) {
                $query->where('cities.id', $cityId);
            })
            ->withAvg('reviews', 'company_rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_company_rating')
            ->orderByDesc('reviews_count')
            ->orderBy('created_at')
            ->first();
            
        return $company?->id;
    }

    public function assignToCompany(Booking $booking, Company $company): bool
    {
        return DB::transaction(function () use ($booking, $company) {
            $oldStatus = $booking->status;
            
            $booking->update([
                'company_id' => $company->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            // Notify company
            if ($company->user) {
                $company->user->notify(new BookingAssignedNotification($booking));
            }

            // Notify customer about status change
            if ($booking->user) {
                $booking->user->notify(new BookingStatusChangedNotification($booking, $oldStatus, 'assigned'));
            }

            // Log assignment
            Log::info("Booking {$booking->booking_number} assigned to company {$company->id}");

            return true;
        });
    }

    public function acceptBooking(Booking $booking): bool
    {
        $oldStatus = $booking->status;
        
        $booking->update([
            'status' => 'in_progress',
        ]);

        // Notify customer about status change
        if ($booking->user) {
            $booking->user->notify(new BookingStatusChangedNotification($booking, $oldStatus, 'in_progress'));
        }

        return true;
    }

    public function rejectBooking(Booking $booking, string $reason): bool
    {
        $booking->update([
            'status' => 'rejected',
            'company_id' => null,
            'assigned_at' => null,
        ]);

        // Notify admin to reassign
        $this->notifyAdmin($booking, 'rejected', $reason);

        return true;
    }

    public function completeBooking(Booking $booking): bool
    {
        return DB::transaction(function () use ($booking) {
            $oldStatus = $booking->status;
            
            $booking->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Create payout record for the company
            if ($booking->company_id && !$booking->payout) {
                \App\Models\Payout::createFromBooking($booking);
                Log::info("Payout created for booking {$booking->booking_number}");
            }

            // Award loyalty points to the user
            if ($booking->user_id && setting('loyalty_points_enabled', true)) {
                $this->awardLoyaltyPoints($booking);
            }

            // Notify customer about completion with PDF invoice
            if ($booking->user) {
                $booking->user->notify(new BookingCompletedNotification($booking));
            } else {
                // For guest bookings, send notification to customer email
                $notifiable = new class($booking->customer_email) {
                    public function __construct(public string $email) {}
                    public function routeNotificationForMail() { return $this->email; }
                };
                $notifiable->notify(new BookingCompletedNotification($booking));
            }

            // Notify company with work summary
            if ($booking->company && $booking->company->user) {
                $booking->company->user->notify(new CompanyWorkSummaryNotification($booking));
            }

            return true;
        });
    }

    /**
     * Award loyalty points to user based on booking amount
     */
    private function awardLoyaltyPoints(Booking $booking): void
    {
        try {
            $pointsPerHundred = (float) setting('loyalty_points_per_100kr', 10);
            $expiryDays = (int) setting('loyalty_points_expiry_days', 365);
            
            // Calculate points: (final_price / 100) * points_per_hundred
            $points = ($booking->final_price / 100) * $pointsPerHundred;
            
            if ($points > 0) {
                $expiresAt = $expiryDays > 0 ? now()->addDays($expiryDays) : null;
                
                \App\Models\LoyaltyPoint::addPoints(
                    $booking->user_id,
                    $points,
                    "Poäng från bokning #{$booking->booking_number}",
                    $booking->id,
                    $expiresAt
                );
                
                Log::info("Awarded {$points} loyalty points to user {$booking->user_id} for booking {$booking->booking_number}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to award loyalty points for booking {$booking->booking_number}: " . $e->getMessage());
        }
    }

    public function cancelBooking(Booking $booking): bool
    {
        $booking->update([
            'status' => 'cancelled',
        ]);

        return true;
    }

    private function notifyAdmin(Booking $booking, string $type = 'created', ?string $message = null): void
    {
        $admins = User::where('type', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewBookingNotification($booking));
        }

        Log::info("Notifying {$admins->count()} admins about booking {$booking->booking_number} ({$type})");
    }

    public function findAvailableCompanies(Booking $booking)
    {
        return Company::where('status', 'active')
            ->whereHas('services', function ($query) use ($booking) {
                $query->where('services.id', $booking->service_id);
            })
            ->whereHas('cities', function ($query) use ($booking) {
                $query->where('cities.id', $booking->city_id);
            })
            ->with(['user', 'reviews'])
            ->get();
    }
}

