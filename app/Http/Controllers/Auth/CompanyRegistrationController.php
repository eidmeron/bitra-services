<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

final class CompanyRegistrationController extends Controller
{
    /**
     * Show company registration form
     */
    public function showRegistrationForm(): View
    {
        $services = Service::active()->orderBy('name')->get();
        $cities = City::active()->orderBy('name')->get();
        
        return view('auth.company-register', compact('services', 'cities'));
    }
    
    /**
     * Validate specific step
     */
    public function validateStep(Request $request): \Illuminate\Http\JsonResponse
    {
        $step = $request->input('step', 1);
        $rules = [];
        $messages = [];
        
        switch ($step) {
            case 1:
                $rules = [
                    'company_name' => 'required|string|max:255',
                    'org_number' => 'required|string|max:50|unique:companies,company_org_number',
                    'company_email' => 'required|email|max:255',
                    'company_phone' => 'required|string|max:20',
                    'company_address' => 'required|string|max:255',
                    'company_city' => 'required|string|max:100',
                    'company_zip' => 'required|string|max:10',
                    'company_website' => 'nullable|url|max:255',
                ];
                $messages = [
                    'company_name.required' => 'Företagsnamn är obligatoriskt.',
                    'org_number.required' => 'Organisationsnummer är obligatoriskt.',
                    'org_number.unique' => 'Detta organisationsnummer är redan registrerat.',
                    'company_email.required' => 'Företagsepost är obligatoriskt.',
                    'company_email.email' => 'Ange en giltig e-postadress.',
                    'company_phone.required' => 'Företagstelefon är obligatoriskt.',
                    'company_address.required' => 'Adress är obligatoriskt.',
                    'company_city.required' => 'Stad är obligatoriskt.',
                    'company_zip.required' => 'Postnummer är obligatoriskt.',
                    'company_website.url' => 'Ange en giltig webbadress.',
                ];
                break;
                
            case 2:
                $rules = [
                    'services' => 'required|array|min:1',
                    'services.*' => 'exists:services,id',
                    'cities' => 'required|array|min:1',
                    'cities.*' => 'exists:cities,id',
                    'description' => 'required|string|min:50',
                ];
                $messages = [
                    'services.required' => 'Välj minst en tjänst.',
                    'services.min' => 'Välj minst en tjänst.',
                    'cities.required' => 'Välj minst en stad.',
                    'cities.min' => 'Välj minst en stad.',
                    'description.required' => 'Företagsbeskrivning är obligatoriskt.',
                    'description.min' => 'Beskrivningen måste vara minst 50 tecken.',
                ];
                break;
                
            case 3:
                $rules = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users,email',
                    'password' => 'required|string|min:8|confirmed',
                    'terms' => 'accepted',
                ];
                $messages = [
                    'name.required' => 'Ditt namn är obligatoriskt.',
                    'email.required' => 'Din e-post är obligatoriskt.',
                    'email.email' => 'Ange en giltig e-postadress.',
                    'email.unique' => 'Denna e-postadress är redan registrerad.',
                    'password.required' => 'Lösenord är obligatoriskt.',
                    'password.min' => 'Lösenordet måste vara minst 8 tecken.',
                    'password.confirmed' => 'Lösenorden matchar inte.',
                    'terms.accepted' => 'Du måste acceptera användarvillkoren.',
                ];
                break;
        }
        
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'step' => $step
            ], 422);
        }
        
        return response()->json([
            'success' => true,
            'step' => $step
        ]);
    }

    /**
     * Handle company registration
     */
    public function register(Request $request): RedirectResponse
    {
        // Validate all steps
        $validated = $request->validate([
            // Step 1: Company Info
            'company_name' => 'required|string|max:255',
            'org_number' => 'required|string|max:50|unique:companies,company_org_number',
            'company_email' => 'required|email|max:255',
            'company_phone' => 'required|string|max:20',
            'company_address' => 'required|string|max:255',
            'company_city' => 'required|string|max:100',
            'company_zip' => 'required|string|max:10',
            'company_website' => 'nullable|url|max:255',
            
            // Step 2: Services & Coverage
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'cities' => 'required|array|min:1',
            'cities.*' => 'exists:cities,id',
            'description' => 'required|string|min:50',
            
            // Step 3: Account
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            
            // Optional
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048000',
            'terms' => 'accepted',
        ], [
            // Swedish error messages
            'company_name.required' => 'Företagsnamn är obligatoriskt.',
            'org_number.required' => 'Organisationsnummer är obligatoriskt.',
            'org_number.unique' => 'Detta organisationsnummer är redan registrerat.',
            'company_email.required' => 'Företagsepost är obligatoriskt.',
            'company_email.email' => 'Ange en giltig e-postadress.',
            'company_phone.required' => 'Företagstelefon är obligatoriskt.',
            'company_address.required' => 'Adress är obligatoriskt.',
            'company_city.required' => 'Stad är obligatoriskt.',
            'company_zip.required' => 'Postnummer är obligatoriskt.',
            'company_website.url' => 'Ange en giltig webbadress.',
            'services.required' => 'Välj minst en tjänst.',
            'services.min' => 'Välj minst en tjänst.',
            'cities.required' => 'Välj minst en stad.',
            'cities.min' => 'Välj minst en stad.',
            'description.required' => 'Företagsbeskrivning är obligatoriskt.',
            'description.min' => 'Beskrivningen måste vara minst 50 tecken.',
            'name.required' => 'Ditt namn är obligatoriskt.',
            'email.required' => 'Din e-post är obligatoriskt.',
            'email.email' => 'Ange en giltig e-postadress.',
            'email.unique' => 'Denna e-postadress är redan registrerad.',
            'password.required' => 'Lösenord är obligatoriskt.',
            'password.min' => 'Lösenordet måste vara minst 8 tecken.',
            'password.confirmed' => 'Lösenorden matchar inte.',
            'logo.image' => 'Logotypen måste vara en bild.',
            'logo.mimes' => 'Logotypen måste vara i formatet: jpeg, png, jpg eller gif.',
            'logo.max' => 'Logotypen får inte vara större än 2MB.',
            'terms.accepted' => 'Du måste acceptera användarvillkoren.',
        ]);

        try {
            DB::beginTransaction();
            
            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'type' => 'company',
                'email_verified_at' => null, // Require email verification
            ]);
            
            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('companies/logos', 'public');
            }
            
            // Create company (map form fields to database columns)
            $company = Company::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'company_org_number' => $validated['org_number'],
                'company_email' => $validated['company_email'],
                'company_number' => $validated['company_phone'],
                'address' => $validated['company_address'],
                'city' => $validated['company_city'],
                'postal_code' => $validated['company_zip'],
                'site' => $validated['company_website'] ?? null,
                'description' => $validated['description'],
                'company_logo' => $logoPath,
                'status' => 'pending', // Requires admin approval
            ]);
            
            // Attach services
            $company->services()->attach($validated['services']);
            
            // Attach cities
            $company->cities()->attach($validated['cities']);
            
            DB::commit();
            
            // Fire registered event
            event(new Registered($user));
            
            // Log the user in
            Auth::login($user);
            
            Log::info("New company registered: {$company->company_name} (ID: {$company->id})");
            
            return redirect()->route('company.dashboard')->with('success', '🎉 Välkommen! Ditt konto har skapats och väntar på godkännande från vår admin. Du kommer att få ett e-postmeddelande när ditt konto har godkänts.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Company registration failed: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->withErrors([
                'error' => 'Ett fel uppstod vid registrering: ' . $e->getMessage() . '. Vänligen kontakta support om problemet kvarstår.'
            ]);
        }
    }
}
