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

        $stats = [
            'assigned_bookings' => Booking::where('company_id', $company->id)->where('status', 'assigned')->count(),
            'in_progress' => Booking::where('company_id', $company->id)->where('status', 'in_progress')->count(),
            'completed_today' => Booking::where('company_id', $company->id)
                ->where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'total_revenue' => Booking::where('company_id', $company->id)
                ->where('status', 'completed')
                ->sum('final_price'),
            'average_rating' => $company->review_average,
        ];

        $recentBookings = Booking::where('company_id', $company->id)
            ->with(['service', 'city', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('company.dashboard', compact('stats', 'recentBookings', 'company'));
    }
}
