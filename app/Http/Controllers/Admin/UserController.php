<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

final class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('type', 'user');

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by registration date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'name':
                $query->orderBy('email');
                break;
            default:
                $query->latest();
        }

        $users = $query->withCount('bookings')->paginate(20);

        // Statistics
        $stats = [
            'total_users' => User::where('type', 'user')->count(),
            'new_today' => User::where('type', 'user')->whereDate('created_at', today())->count(),
            'new_this_week' => User::where('type', 'user')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_this_month' => User::where('type', 'user')->whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user): View
    {
        $user->load(['bookings' => function($query) {
            $query->with(['service', 'city', 'company'])->latest();
        }]);

        $userStats = [
            'total_bookings' => $user->bookings()->count(),
            'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
            'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
            'total_spent' => $user->bookings()->whereIn('status', ['completed', 'confirmed'])->sum('final_price'),
            'rot_savings' => $user->bookings()->whereIn('status', ['completed', 'confirmed'])->sum('rot_deduction'),
        ];

        return view('admin.users.show', compact('user', 'userStats'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'name' => $validated['name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'type' => 'user',
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Användare skapad framgångsrikt!');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $updateData = [
            'email' => $validated['email'],
            'name' => $validated['name'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Användare uppdaterad framgångsrikt!');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Check if user has bookings
        if ($user->bookings()->count() > 0) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Kan inte ta bort användare med befintliga bokningar.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Användare borttagen framgångsrikt!');
    }
}

