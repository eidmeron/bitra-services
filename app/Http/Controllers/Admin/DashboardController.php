<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Company;
use App\Models\Service;
use App\Models\SlotTime;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // Main Statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'total_companies' => Company::count(),
            'total_users' => User::where('type', 'user')->count(),
            'total_services' => Service::where('status', 'active')->count(),
            'total_revenue' => Booking::whereIn('status', ['completed', 'confirmed'])->sum('final_price'),
            'monthly_revenue' => Booking::whereIn('status', ['completed', 'confirmed'])
                ->whereMonth('created_at', now()->month)
                ->sum('final_price'),
        ];

        // Status breakdown
        $bookingsByStatus = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'in_progress' => Booking::where('status', 'in_progress')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        // Recent data - paginated
        $recentBookings = Booking::with(['service', 'city', 'user', 'company'])
            ->latest()
            ->paginate(5, ['*'], 'recent_page');

        $pendingCompanies = Company::where('status', 'pending')
            ->with('user')
            ->latest()
            ->paginate(5, ['*'], 'companies_page');

        $topServices = Service::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        $recentUsers = User::where('type', 'user')
            ->latest()
            ->limit(5)
            ->get();

        // Recent complaints
        $recentComplaints = \App\Models\Complaint::with(['booking.service'])
            ->latest()
            ->limit(3)
            ->get();

        // Revenue chart data based on filter
        $filter = $request->get('revenue_filter', 'monthly');
        $revenueData = [];
        
        switch ($filter) {
            case 'weekly':
                // Last 8 weeks
                for ($i = 7; $i >= 0; $i--) {
                    $week = now()->subWeeks($i);
                    $weekStart = $week->startOfWeek();
                    $weekEnd = $week->endOfWeek();
                    $revenueData[] = [
                        'period' => 'Vecka ' . $week->weekOfYear,
                        'revenue' => Booking::whereIn('status', ['completed', 'confirmed'])
                            ->whereBetween('created_at', [$weekStart, $weekEnd])
                            ->sum('final_price'),
                    ];
                }
                break;
            case 'yearly':
                // Last 5 years
                for ($i = 4; $i >= 0; $i--) {
                    $year = now()->subYears($i);
                    $revenueData[] = [
                        'period' => $year->format('Y'),
                        'revenue' => Booking::whereIn('status', ['completed', 'confirmed'])
                            ->whereYear('created_at', $year->year)
                            ->sum('final_price'),
                    ];
                }
                break;
            case 'monthly':
            default:
                // Last 6 months
                for ($i = 5; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $revenueData[] = [
                        'period' => $month->format('M'),
                        'revenue' => Booking::whereIn('status', ['completed', 'confirmed'])
                            ->whereYear('created_at', $month->year)
                            ->whereMonth('created_at', $month->month)
                            ->sum('final_price'),
                    ];
                }
                break;
        }

        // Recent activity (notifications)
        $recentActivity = auth()->user()->notifications()
            ->latest()
            ->limit(5)
            ->get();

        // Recent chat activity (removed - chatbot functionality disabled)
        $recentChats = collect([]);

        // Chat statistics (removed - chatbot functionality disabled)
        $chatStats = [
            'total_messages' => 0,
            'messages_today' => 0,
            'active_conversations' => 0,
        ];

        // Loyalty points statistics
        $loyaltyStats = [
            'total_points_balance' => \App\Models\User::where('type', 'user')->sum('loyalty_points_balance'),
            'points_earned_month' => \App\Models\LoyaltyPoint::where('type', 'earned')
                ->whereMonth('created_at', now()->month)
                ->sum('points'),
            'points_redeemed_month' => abs(\App\Models\LoyaltyPoint::where('type', 'redeemed')
                ->whereMonth('created_at', now()->month)
                ->sum('points')),
            'users_with_points' => \App\Models\User::where('type', 'user')
                ->where('loyalty_points_balance', '>', 0)
                ->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'bookingsByStatus',
            'recentBookings',
            'pendingCompanies',
            'topServices',
            'recentUsers',
            'revenueData',
            'filter',
            'recentActivity',
            'recentChats',
            'chatStats',
            'loyaltyStats',
            'recentComplaints'
        ));
    }

    /**
     * Clean up past slot times
     */
    public function cleanupPastSlots(Request $request): RedirectResponse
    {
        $daysToKeep = (int) $request->input('days_to_keep', 0);
        
        // Count past slots before deletion
        $pastSlotsCount = SlotTime::where('date', '<', now()->format('Y-m-d'))->count();
        
        if ($pastSlotsCount === 0) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Inga gamla tidsluckor hittades att rensa.');
        }
        
        // Delete past slots
        $deletedCount = SlotTime::cleanupPast($daysToKeep);
        
        return redirect()->route('admin.dashboard')
            ->with('success', "Rensade {$deletedCount} gamla tidsluckor från schemat.");
    }
}
