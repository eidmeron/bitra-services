<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Company;
use App\Models\User;
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

            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'service_id' => $data['service_id'],
                'form_id' => $data['form_id'],
                'city_id' => $data['city_id'],
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
                'rot_deduction' => $pricing['rot_deduction'],
                'discount_amount' => $pricing['discount_amount'],
                'final_price' => $pricing['final_price'],
                'status' => 'pending',
            ]);

            // Notify admin
            $this->notifyAdmin($booking);

            return $booking;
        });
    }

    public function assignToCompany(Booking $booking, Company $company): bool
    {
        return DB::transaction(function () use ($booking, $company) {
            $booking->update([
                'company_id' => $company->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            // Log assignment
            Log::info("Booking {$booking->booking_number} assigned to company {$company->id}");

            return true;
        });
    }

    public function acceptBooking(Booking $booking): bool
    {
        $booking->update([
            'status' => 'in_progress',
        ]);

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
        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return true;
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

