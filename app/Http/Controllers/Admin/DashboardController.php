<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::pending()->count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'total_users' => User::where('type', 'user')->count(),
            'total_revenue' => Booking::completed()->sum('final_price'),
        ];

        $recentBookings = Booking::with(['service', 'city', 'user', 'company'])
            ->latest()
            ->limit(10)
            ->get();

        $pendingCompanies = Company::where('status', 'pending')
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'pendingCompanies'));
    }
}
