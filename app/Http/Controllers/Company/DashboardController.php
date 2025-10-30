<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $company = auth()->user()->company;

        // Main stats
        $stats = [
            'total_bookings' => Booking::where('company_id', $company->id)->count(),
            'assigned_bookings' => Booking::where('company_id', $company->id)->where('status', 'assigned')->count(),
            'in_progress' => Booking::where('company_id', $company->id)->where('status', 'in_progress')->count(),
            'completed_bookings' => Booking::where('company_id', $company->id)->where('status', 'completed')->count(),
            'completed_today' => Booking::where('company_id', $company->id)
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'total_revenue' => Booking::where('company_id', $company->id)
                ->whereIn('status', ['completed', 'confirmed'])
                ->sum('final_price'),
            'monthly_revenue' => Booking::where('company_id', $company->id)
                ->whereIn('status', ['completed', 'confirmed'])
                ->whereMonth('created_at', now()->month)
                ->sum('final_price'),
            'average_rating' => $company->reviews_avg_company_rating ?? 0,
            'total_reviews' => $company->reviews_count ?? 0,
        ];

        // Recent bookings - paginated
        $recentBookings = Booking::where('company_id', $company->id)
            ->with(['service', 'city', 'user'])
            ->latest()
            ->paginate(5, ['*'], 'recent_page');

        // Upcoming bookings - paginated
        $upcomingBookings = Booking::where('company_id', $company->id)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->with(['service', 'city', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'upcoming_page');

        // Recent messages - paginated
        $recentMessages = $company->messages()
            ->where('status', 'new')
            ->latest()
            ->paginate(5, ['*'], 'messages_page');

        // Monthly revenue chart (last 6 months)
        $monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenueData[] = [
                'month' => $month->format('M'),
                'revenue' => Booking::where('company_id', $company->id)
                    ->whereIn('status', ['completed', 'confirmed'])
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('final_price'),
            ];
        }

        // Recent activity/notifications - limited to 5
        $recentActivity = auth()->user()->notifications()
            ->latest()
            ->limit(5)
            ->get();

        // Recent complaints
        $recentComplaints = $company->complaints()
            ->with(['booking.service'])
            ->latest()
            ->limit(3)
            ->get();

        // Deposit stats (replaces payout stats)
        $depositStats = [
            'pending' => \App\Models\Deposit::where('company_id', $company->id)->where('status', 'pending')->sum('deposit_amount'),
            'pending_count' => \App\Models\Deposit::where('company_id', $company->id)->where('status', 'pending')->count(),
            'sent' => \App\Models\Deposit::where('company_id', $company->id)->where('status', 'sent')->sum('deposit_amount'),
            'sent_count' => \App\Models\Deposit::where('company_id', $company->id)->where('status', 'sent')->count(),
            'paid' => \App\Models\Deposit::where('company_id', $company->id)->where('status', 'paid')->sum('deposit_amount'),
            'paid_count' => \App\Models\Deposit::where('company_id', $company->id)->where('status', 'paid')->count(),
            'total_commission' => \App\Models\Deposit::where('company_id', $company->id)->sum('commission_amount'),
            'total_loyalty_deduction' => \App\Models\Deposit::where('company_id', $company->id)->sum('loyalty_points_value'),
        ];

        // Recent deposits
        $recentDeposits = \App\Models\Deposit::where('company_id', $company->id)
            ->with('booking.service')
            ->latest()
            ->limit(5)
            ->get();

        return view('company.dashboard', compact(
            'stats',
            'recentBookings',
            'upcomingBookings',
            'recentMessages',
            'monthlyRevenueData',
            'recentActivity',
            'company',
            'depositStats',
            'recentDeposits',
            'recentComplaints'
        ));
    }
}
