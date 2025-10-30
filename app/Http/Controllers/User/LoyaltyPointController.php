<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPointTransaction;
use App\Services\LoyaltyPointService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoyaltyPointController extends Controller
{
    public function __construct(
        private LoyaltyPointService $loyaltyPointService
    ) {}

    /**
     * Display user's loyalty points
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $query = $user->loyaltyPointTransactions();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $transactions = $query->with(['booking.service'])->latest()->paginate(20);

        $summary = $this->loyaltyPointService->getUserLoyaltySummary($user->id);

        return view('user.loyalty-points.index', compact('transactions', 'summary'));
    }

    /**
     * Display the specified loyalty point transaction
     */
    public function show(LoyaltyPointTransaction $loyaltyPoint): View
    {
        // Ensure the transaction belongs to the user
        if ($loyaltyPoint->user_id !== auth()->id()) {
            abort(403, 'Du har inte behörighet att visa denna transaktion.');
        }

        $loyaltyPoint->load(['booking.service', 'createdBy']);
        
        return view('user.loyalty-points.show', compact('loyaltyPoint'));
    }
}