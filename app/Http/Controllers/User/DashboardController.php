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

        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'pending_bookings' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'total_spent' => Booking::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('final_price'),
        ];

        $recentBookings = Booking::where('user_id', $user->id)
            ->with(['service', 'city', 'company'])
            ->latest()
            ->limit(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentBookings'));
    }
}

