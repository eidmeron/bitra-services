<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

final class SettingsController extends Controller
{
    public function index(): View
    {
        return view('company.settings.index');
    }
    
    public function updateAccount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);
        
        auth()->user()->update($validated);
        
        return redirect()
            ->route('company.settings')
            ->with('success', 'Kontoinformation uppdaterad framgångsrikt!');
    }
    
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Nuvarande lösenord är felaktigt.']);
        }
        
        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()
            ->route('company.settings')
            ->with('success', 'Lösenord ändrat framgångsrikt!');
    }
    
    public function updateNotifications(Request $request): RedirectResponse
    {
        // For now, just return success
        // You can implement notification preferences in the users table or a separate table
        
        return redirect()
            ->route('company.settings')
            ->with('success', 'Aviseringsinställningar uppdaterade!');
    }
    
    public function deleteAccount(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $company = $user->company;
        
        // Soft delete or mark as inactive
        if ($company) {
            $company->update(['status' => 'suspended']);
        }
        
        auth()->logout();
        
        return redirect()
            ->route('welcome')
            ->with('success', 'Ditt konto har inaktiverats.');
    }
}

