<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyMessage;
use App\Notifications\NewCompanyMessageNotification;
use Illuminate\Http\Request;

final class CompanyMessageController extends Controller
{
    public function send(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $companyMessage = CompanyMessage::create([
            'company_id' => $company->id,
            'guest_name' => $validated['name'],
            'guest_email' => $validated['email'],
            'guest_phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        // Notify company user
        if ($company->user) {
            $company->user->notify(new NewCompanyMessageNotification($companyMessage));
        }

        return back()->with('success', 'Ditt meddelande har skickats! Företaget kommer att kontakta dig snart.');
    }
}

