<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPointTransaction;
use App\Models\User;
use App\Services\LoyaltyPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoyaltyPointController extends Controller
{
    public function __construct(
        private LoyaltyPointService $loyaltyPointService
    ) {}

    /**
     * Display a listing of loyalty point transactions
     */
    public function index(Request $request): View
    {
        $query = LoyaltyPointTransaction::with(['user', 'booking']);

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

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

        $transactions = $query->latest()->paginate(20);

        $statistics = [
            'total_earned' => LoyaltyPointTransaction::where('type', 'earned')->sum('points'),
            'total_spent' => abs(LoyaltyPointTransaction::whereIn('type', ['spent', 'expired'])->sum('points')),
            'total_refunded' => LoyaltyPointTransaction::where('type', 'refunded')->sum('points'),
            'total_expired' => abs(LoyaltyPointTransaction::where('type', 'expired')->sum('points')),
            'active_users' => User::where('type', 'user')->whereHas('loyaltyPointTransactions')->count(),
        ];

        return view('admin.loyalty-points.index', compact('transactions', 'statistics'));
    }

    /**
     * Display the specified loyalty point transaction
     */
    public function show(LoyaltyPointTransaction $loyaltyPoint): View
    {
        $loyaltyPoint->load(['user', 'booking.service', 'createdBy']);
        
        return view('admin.loyalty-points.show', compact('loyaltyPoint'));
    }

    /**
     * Show the form for creating a new loyalty point transaction
     */
    public function create(): View
    {
        $users = User::where('type', 'user')->orderBy('name')->get();
        
        return view('admin.loyalty-points.create', compact('users'));
    }

    /**
     * Store a newly created loyalty point transaction
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:earned,spent,adjusted',
            'points' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'expires_at' => 'nullable|date|after:now',
        ]);

        try {
            LoyaltyPointTransaction::create([
                'user_id' => $request->user_id,
                'type' => $request->type,
                'points' => $request->type === 'spent' ? -$request->points : $request->points,
                'value' => $request->points, // 1 point = 1 SEK
                'description' => $request->description,
                'notes' => $request->notes,
                'expires_at' => $request->expires_at,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.loyalty-points.index')
                ->with('success', 'Lojalitetspoäng transaktion skapad.');
        } catch (\Exception $e) {
            return redirect()->route('admin.loyalty-points.create')
                ->with('error', 'Kunde inte skapa transaktion: ' . $e->getMessage());
        }
    }

    /**
     * Expire old loyalty points
     */
    public function expireOldPoints(): RedirectResponse
    {
        try {
            $expiredCount = $this->loyaltyPointService->expireOldPoints();
            
            return redirect()->route('admin.loyalty-points.index')
                ->with('success', "Expirerade {$expiredCount} gamla lojalitetspoäng.");
        } catch (\Exception $e) {
            return redirect()->route('admin.loyalty-points.index')
                ->with('error', 'Kunde inte expirera gamla poäng: ' . $e->getMessage());
        }
    }

    /**
     * Get user loyalty points summary
     */
    public function userSummary(User $user): View
    {
        $summary = $this->loyaltyPointService->getUserLoyaltySummary($user->id);
        
        return view('admin.loyalty-points.user-summary', compact('user', 'summary'));
    }
}