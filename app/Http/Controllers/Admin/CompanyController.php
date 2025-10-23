<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\City;
use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::with(['user', 'services', 'cities']);

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('company_org_number', 'like', "%{$search}%")
                    ->orWhere('company_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Date filters
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
                $query->orderBy('company_name');
                break;
            default:
                $query->latest();
        }

        $companies = $query->paginate(20);

        // Statistics
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'pending_companies' => Company::where('status', 'pending')->count(),
            'inactive_companies' => Company::where('status', 'inactive')->count(),
            'new_today' => Company::whereDate('created_at', today())->count(),
            'new_this_week' => Company::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_this_month' => Company::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.companies.index', compact('companies', 'stats'));
    }

    public function create(): View
    {
        $services = Service::where('status', 'active')->get();
        $cities = City::where('status', 'active')->get();

        return view('admin.companies.create', compact('services', 'cities'));
    }

    public function store(CompanyRequest $request): RedirectResponse
    {
        return DB::transaction(function () use ($request) {
            // Create user
            $user = User::create([
                'type' => 'company',
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'status' => 'active',
            ]);

            // Create company
            $companyData = $request->validated();

            if ($request->hasFile('company_logo')) {
                $companyData['company_logo'] = $request->file('company_logo')->store('companies', 'public');
            }

            $company = Company::create([
                'user_id' => $user->id,
                'company_name' => $companyData['company_name'],
                'description' => $companyData['description'] ?? null,
                'address' => $companyData['address'] ?? null,
                'city' => $companyData['city'] ?? null,
                'postal_code' => $companyData['postal_code'] ?? null,
                'company_logo' => $companyData['company_logo'] ?? null,
                'company_email' => $companyData['company_email'],
                'company_number' => $companyData['company_number'],
                'company_org_number' => $companyData['company_org_number'],
                'site' => $companyData['site'] ?? null,
                'status' => $companyData['status'],
            ]);

            // Attach services
            if ($request->has('services')) {
                $company->services()->sync($request->services);
            }

            // Attach cities
            if ($request->has('cities')) {
                $company->cities()->sync($request->cities);
            }

            return redirect()->route('admin.companies.index')
                ->with('success', 'Företag skapat framgångsrikt.');
        });
    }

    public function show(Company $company): View
    {
        $company->load([
            'user', 
            'services', 
            'cities', 
            'bookings.user',
            'reviews' => function($query) {
                $query->with('booking.user')->latest();
            },
            'commissionSetting',
            'payouts' => function($query) {
                $query->latest()->limit(10);
            }
        ]);

        // Get recent chat messages for this company (disabled - chatbot functionality removed)
        $recentChats = collect([]);

        return view('admin.companies.show', compact('company', 'recentChats'));
    }

    public function edit(Company $company): View
    {
        $company->load(['user', 'services', 'cities']);
        $services = Service::where('status', 'active')->get();
        $cities = City::where('status', 'active')->get();

        return view('admin.companies.edit', compact('company', 'services', 'cities'));
    }

    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {
        return DB::transaction(function () use ($request, $company) {
            // Update user
            $userData = [
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $company->user->update($userData);

            // Update company
            $companyData = $request->validated();

            if ($request->hasFile('company_logo')) {
                $companyData['company_logo'] = $request->file('company_logo')->store('companies', 'public');
            }

            $company->update([
                'company_name' => $companyData['company_name'],
                'description' => $companyData['description'] ?? null,
                'address' => $companyData['address'] ?? null,
                'city' => $companyData['city'] ?? null,
                'postal_code' => $companyData['postal_code'] ?? null,
                'company_logo' => $companyData['company_logo'] ?? $company->company_logo,
                'company_email' => $companyData['company_email'],
                'company_number' => $companyData['company_number'],
                'company_org_number' => $companyData['company_org_number'],
                'site' => $companyData['site'] ?? null,
                'status' => $companyData['status'],
            ]);

            // Sync services
            if ($request->has('services')) {
                $company->services()->sync($request->services);
            }

            // Sync cities
            if ($request->has('cities')) {
                $company->cities()->sync($request->cities);
            }

            return redirect()->route('admin.companies.index')
                ->with('success', 'Företag uppdaterat framgångsrikt.');
        });
    }

    public function destroy(Company $company): RedirectResponse
    {
        DB::transaction(function () use ($company) {
            $company->user->delete();
            $company->delete();
        });

        return redirect()->route('admin.companies.index')
            ->with('success', 'Företag raderat framgångsrikt.');
    }
}
