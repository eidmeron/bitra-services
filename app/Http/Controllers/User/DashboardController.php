<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Helper function to query user's bookings (includes guest bookings by email)
        $userBookingsQuery = function () use ($user) {
            return Booking::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereNull('user_id')
                          ->where('customer_email', $user->email);
                    });
            });
        };

        // Main stats
        $stats = [
            'total_bookings' => $userBookingsQuery()->count(),
            'pending_bookings' => $userBookingsQuery()->where('status', 'pending')->count(),
            'in_progress_bookings' => $userBookingsQuery()->where('status', 'in_progress')->count(),
            'completed_bookings' => $userBookingsQuery()->where('status', 'completed')->count(),
            'total_spent' => $userBookingsQuery()
                ->whereIn('status', ['completed', 'confirmed'])
                ->sum('final_price'),
            'rot_savings' => $userBookingsQuery()
                ->whereIn('status', ['completed', 'confirmed'])
                ->sum('rot_deduction'),
        ];

        // Recent bookings (includes guest bookings) - paginated
        $recentBookings = $userBookingsQuery()
            ->with(['service', 'city', 'company'])
            ->latest()
            ->paginate(5, ['*'], 'recent_page');

        // Upcoming bookings (includes guest bookings) - paginated
        $upcomingBookings = $userBookingsQuery()
            ->whereIn('status', ['pending', 'assigned', 'in_progress'])
            ->with(['service', 'city', 'company.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'upcoming_page');

        // Recent activity/notifications - paginated
        $recentActivity = $user->notifications()
            ->latest()
            ->paginate(5, ['*'], 'activity_page');

        // Recent loyalty points transactions - paginated
        $recentLoyaltyPoints = $user->loyaltyPoints()
            ->with('booking')
            ->latest()
            ->paginate(10, ['*'], 'loyalty_page');

        // Recent complaints
        $recentComplaints = $user->complaints()
            ->with(['booking.service'])
            ->latest()
            ->limit(3)
            ->get();

        return view('user.dashboard', compact('stats', 'recentBookings', 'upcomingBookings', 'recentActivity', 'recentLoyaltyPoints', 'recentComplaints'));
    }
}

