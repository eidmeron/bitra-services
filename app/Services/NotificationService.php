<?php

namespace App\Services;

use App\Models\NotificationSetting;
use App\Models\User;
use App\Models\Booking;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification based on event
     */
    public function sendNotification(string $event, array $data = []): void
    {
        $settings = NotificationSetting::enabled()
            ->byEvent($event)
            ->autoSend()
            ->get();

        foreach ($settings as $setting) {
            try {
                $this->processNotification($setting, $data);
            } catch (\Exception $e) {
                Log::error("Failed to send notification {$event}: " . $e->getMessage());
            }
        }
    }

    /**
     * Process individual notification
     */
    private function processNotification(NotificationSetting $setting, array $data): void
    {
        $recipients = $this->getRecipients($setting, $data);
        $template = $setting->getProcessedTemplate($data);
        $subject = $setting->getProcessedSubject($data);

        foreach ($recipients as $recipient) {
            switch ($setting->type) {
                case 'email':
                    $this->sendEmail($recipient, $subject, $template, $data);
                    break;
                case 'sms':
                    $this->sendSms($recipient, $template, $data);
                    break;
                case 'push':
                    $this->sendPush($recipient, $template, $data);
                    break;
                case 'in_app':
                    $this->sendInApp($recipient, $template, $data);
                    break;
            }
        }
    }

    /**
     * Get recipients for notification
     */
    private function getRecipients(NotificationSetting $setting, array $data): array
    {
        $recipients = [];

        if ($setting->recipients) {
            foreach ($setting->recipients as $recipientType) {
                switch ($recipientType) {
                    case 'user':
                        if (isset($data['user_id'])) {
                            $user = User::find($data['user_id']);
                            if ($user) $recipients[] = $user;
                        }
                        break;
                    case 'company':
                        if (isset($data['company_id'])) {
                            $company = Company::find($data['company_id']);
                            if ($company && $company->user) {
                                $recipients[] = $company->user;
                            }
                        }
                        break;
                    case 'admin':
                        $admins = User::where('role', 'admin')->get();
                        $recipients = array_merge($recipients, $admins->toArray());
                        break;
                }
            }
        }

        return $recipients;
    }

    /**
     * Send email notification
     */
    private function sendEmail($recipient, string $subject, string $template, array $data): void
    {
        if (method_exists($recipient, 'email')) {
            Mail::raw($template, function ($message) use ($recipient, $subject) {
                $message->to($recipient->email)
                        ->subject($subject);
            });
        }
    }

    /**
     * Send SMS notification (placeholder)
     */
    private function sendSms($recipient, string $template, array $data): void
    {
        // Implement SMS sending logic here
        Log::info("SMS notification sent to {$recipient->phone}: {$template}");
    }

    /**
     * Send push notification (placeholder)
     */
    private function sendPush($recipient, string $template, array $data): void
    {
        // Implement push notification logic here
        Log::info("Push notification sent to user {$recipient->id}: {$template}");
    }

    /**
     * Send in-app notification
     */
    private function sendInApp($recipient, string $template, array $data): void
    {
        if (method_exists($recipient, 'notify')) {
            $recipient->notify(new \App\Notifications\InAppNotification($template, $data));
        }
    }

    /**
     * Send booking completion notification
     */
    public function sendBookingCompletionNotification(Booking $booking): void
    {
        $data = [
            'user_id' => $booking->user_id,
            'company_id' => $booking->company_id,
            'booking_id' => $booking->id,
            'user_name' => $booking->user->name,
            'company_name' => $booking->company->company_name,
            'service_name' => $booking->service->name,
            'booking_number' => $booking->booking_number,
            'booking_date' => $booking->preferred_date ? $booking->preferred_date->format('Y-m-d') : 'N/A',
            'booking_time' => $booking->preferred_time ?? 'N/A',
            'total_amount' => number_format($booking->total_with_extra_fees, 0, ',', ' ') . ' kr',
            'loyalty_points' => $booking->loyalty_points_used ?? 0,
        ];

        $this->sendNotification('booking_completed', $data);
    }

    /**
     * Send new user welcome notification
     */
    public function sendNewUserWelcomeNotification(User $user): void
    {
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'loyalty_points' => 100, // Welcome bonus
        ];

        $this->sendNotification('user_registered', $data);
    }

    /**
     * Send loyalty points earned notification
     */
    public function sendLoyaltyPointsEarnedNotification(User $user, int $points, Booking $booking): void
    {
        $data = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'loyalty_points' => $points,
            'booking_number' => $booking->booking_number,
            'service_name' => $booking->service->name,
            'total_points' => $user->loyalty_points_balance + $points,
        ];

        $this->sendNotification('loyalty_points_earned', $data);
    }

    /**
     * Send invoice notification
     */
    public function sendInvoiceNotification(Company $company, array $invoiceData): void
    {
        $data = [
            'company_id' => $company->id,
            'company_name' => $company->company_name,
            'invoice_number' => $invoiceData['invoice_number'],
            'invoice_amount' => number_format($invoiceData['total_amount'], 0, ',', ' ') . ' kr',
            'due_date' => $invoiceData['due_date'],
        ];

        $this->sendNotification('invoice_sent', $data);
    }

    /**
     * Get notification counts for admin dashboard
     */
    public function getNotificationCounts(): array
    {
        return [
            'total_notifications' => \App\Models\NotificationSetting::count(),
            'enabled_notifications' => \App\Models\NotificationSetting::enabled()->count(),
            'disabled_notifications' => \App\Models\NotificationSetting::where('enabled', false)->count(),
            'auto_send_notifications' => \App\Models\NotificationSetting::autoSend()->count(),
            'new_bookings' => \App\Models\Booking::where('status', 'new')->count(),
            'pending_bookings' => \App\Models\Booking::where('status', 'pending')->count(),
            'completed_bookings' => \App\Models\Booking::where('status', 'completed')->count(),
            'total_companies' => \App\Models\Company::count(),
            'active_companies' => \App\Models\Company::where('status', 'active')->count(),
            'pending_companies' => \App\Models\Company::where('status', 'pending')->count(),
            'new_companies' => \App\Models\Company::where('status', 'new')->count(),
            'total_users' => \App\Models\User::count(),
            'new_users' => \App\Models\User::whereDate('created_at', today())->count(),
            'new_users_today' => \App\Models\User::whereDate('created_at', today())->count(),
            'new_platform_reviews' => \App\Models\Review::where('bitra_status', 'pending')->count(),
            'pending_platform_reviews' => \App\Models\Review::where('bitra_status', 'pending')->count(),
            'new_company_reviews' => \App\Models\Review::where('company_status', 'pending')->count(),
            'pending_company_reviews' => \App\Models\Review::where('company_status', 'pending')->count(),
        ];
    }
}