<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class ProfileController extends Controller
{
    public function edit(): View
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            abort(404, 'Företagsprofil hittades inte');
        }
        
        return view('company.profile.edit', compact('company'));
    }
    
    public function update(Request $request): RedirectResponse
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            abort(404, 'Företagsprofil hittades inte');
        }
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'org_number' => ['required', 'string', 'regex:/^\d{6}-\d{4}$/'],
            'company_email' => 'required|email|max:255',
            'company_number' => 'required|string|max:20',
            'site' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'logo' => 'nullable|image|max:2048', // 2MB max
            'payout_method' => 'nullable|in:swish,bank_account',
            'swish_number' => 'nullable|required_if:payout_method,swish|string|max:20',
            'bank_name' => 'nullable|required_if:payout_method,bank_account|string|max:100',
            'clearing_number' => 'nullable|required_if:payout_method,bank_account|string|max:10',
            'account_number' => 'nullable|required_if:payout_method,bank_account|string|max:20',
            'payout_notes' => 'nullable|string|max:500',
        ], [
            'org_number.regex' => 'Organisationsnummer måste vara i formatet XXXXXX-XXXX'
        ]);
        
        // Map form fields to database column names
        $updateData = [
            'company_name' => $validated['company_name'],
            'description' => $validated['description'],
            'company_org_number' => $validated['org_number'],  // Map to correct column
            'company_email' => $validated['company_email'],
            'company_number' => $validated['company_number'],
            'site' => $validated['site'],
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'city' => $validated['city'],
            'payout_method' => $validated['payout_method'] ?? null,
            'swish_number' => $validated['swish_number'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'clearing_number' => $validated['clearing_number'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'payout_notes' => $validated['payout_notes'] ?? null,
        ];
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->company_logo) {
                Storage::disk('public')->delete($company->company_logo);
            }
            
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $updateData['company_logo'] = $logoPath;  // Map to correct column
        }
        
        $company->update($updateData);
        
        return redirect()
            ->route('company.profile')
            ->with('success', 'Företagsprofil uppdaterad framgångsrikt!');
    }
}

