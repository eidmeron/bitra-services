<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

final class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index(): View
    {
        $settings = [
            // Brand & Logo
            'site_logo' => setting('site_logo', ''),
            'site_favicon' => setting('site_favicon', ''),
            'site_name' => setting('site_name', 'Bitra Services'),
            'site_tagline' => setting('site_tagline', 'Professionella Tjänster i Hela Sverige'),
            
            // Contact
            'site_email' => setting('site_email', 'info@bitraservices.se'),
            'site_phone' => setting('site_phone', ''),
            'site_address' => setting('site_address', ''),
            
            // SEO
            'seo_title' => setting('seo_title', 'Bitra Services - Professionella Tjänster'),
            'seo_description' => setting('seo_description', 'Boka professionella tjänster i hela Sverige'),
            'seo_keywords' => setting('seo_keywords', 'tjänster, boka, hemstädning, flyttstädning'),
            'seo_og_image' => setting('seo_og_image', ''),
            
            // Loyalty Points
            'loyalty_points_enabled' => (bool) setting('loyalty_points_enabled', true),
            'loyalty_points_value' => (float) setting('loyalty_points_value', 1.0),
            'loyalty_points_earn_rate' => (float) setting('loyalty_points_earn_rate', 1.0),
            'loyalty_points_min_redeem' => (int) setting('loyalty_points_min_redeem', 100),
            'loyalty_points_max_redeem_percent' => (int) setting('loyalty_points_max_redeem_percent', 50),
            'loyalty_points_expiry_days' => (int) setting('loyalty_points_expiry_days', 365),
            'new_user_loyalty_bonus' => (int) setting('new_user_loyalty_bonus', 100),
            
            // Booking
            'booking_cancellation_hours' => (int) setting('booking_cancellation_hours', 24),
            'booking_auto_assign' => (bool) setting('booking_auto_assign', false),
            'booking_show_companies' => (bool) setting('booking_show_companies', true),
            'booking_allow_company_selection' => (bool) setting('booking_allow_company_selection', true),
            
            // Commission
            'default_commission_rate' => (float) setting('default_commission_rate', 15.0),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Brand & Logo
            'site_logo' => 'nullable|image|max:2048000',
            'site_favicon' => 'nullable|image|max:1024',
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            
            // Contact
            'site_email' => 'required|email|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string|max:500',
            
            // SEO
            'seo_title' => 'required|string|max:60',
            'seo_description' => 'required|string|max:160',
            'seo_keywords' => 'nullable|string|max:255',
            'seo_og_image' => 'nullable|image|max:2048000',
            
            // Loyalty Points
            'loyalty_points_enabled' => 'boolean',
            'loyalty_points_value' => 'required|numeric|min:0.01|max:100',
            'loyalty_points_earn_rate' => 'required|numeric|min:0|max:100',
            'loyalty_points_min_redeem' => 'required|integer|min:1',
            'loyalty_points_max_redeem_percent' => 'required|integer|min:1|max:100',
            'loyalty_points_expiry_days' => 'required|integer|min:0',
            'new_user_loyalty_bonus' => 'required|integer|min:0',
            
            // Booking
            'booking_cancellation_hours' => 'required|integer|min:0',
            'booking_auto_assign' => 'boolean',
            'booking_show_companies' => 'boolean',
            'booking_allow_company_selection' => 'boolean',
            
            // Commission
            'default_commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        // Handle file uploads
        $fileFields = ['site_logo', 'site_favicon', 'seo_og_image'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file
                $oldValue = setting($field);
                if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                    Storage::disk('public')->delete($oldValue);
                }
                
                // Upload new file
                $path = $request->file($field)->store('settings', 'public');
                $validated[$field] = $path;
            } else {
                // Keep existing value if no new file uploaded
                unset($validated[$field]);
            }
        }

        // Convert checkboxes to boolean
        $validated['loyalty_points_enabled'] = $request->has('loyalty_points_enabled');
        $validated['booking_auto_assign'] = $request->has('booking_auto_assign');
        $validated['booking_show_companies'] = $request->has('booking_show_companies');
        $validated['booking_allow_company_selection'] = $request->has('booking_allow_company_selection');

        // Update all settings
        foreach ($validated as $key => $value) {
            $settingMeta = $this->getSettingMetadata($key);
            
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => (string) $value,
                    'type' => $settingMeta['type'],
                    'label' => $settingMeta['label'],
                    'group' => $settingMeta['group'],
                    'description' => $settingMeta['description'],
                    'order' => $settingMeta['order'],
                ]
            );
        }

        // Clear settings cache
        \Cache::forget('site_settings');

        return back()->with('success', 'Inställningar uppdaterade!');
    }

    /**
     * Get setting type based on value
     */
    private function getSettingType(mixed $value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }
        if (is_int($value)) {
            return 'integer';
        }
        if (is_float($value)) {
            return 'float';
        }
        return 'string';
    }
    
    /**
     * Get metadata for a setting key
     */
    private function getSettingMetadata(string $key): array
    {
        $metadata = [
            // Brand & Logo
            'site_logo' => ['label' => 'Webbplatslogotyp', 'group' => 'brand', 'description' => 'Huvudlogotyp för webbplatsen', 'type' => 'image', 'order' => 1],
            'site_favicon' => ['label' => 'Favicon', 'group' => 'brand', 'description' => 'Webbläsarikon', 'type' => 'image', 'order' => 2],
            'site_name' => ['label' => 'Webbplatsnamn', 'group' => 'brand', 'description' => 'Namnet på webbplatsen', 'type' => 'text', 'order' => 3],
            'site_tagline' => ['label' => 'Tagline', 'group' => 'brand', 'description' => 'Slogan', 'type' => 'text', 'order' => 4],
            
            // Contact
            'site_email' => ['label' => 'Support E-post', 'group' => 'contact', 'description' => 'Primär kontakt e-post', 'type' => 'email', 'order' => 1],
            'site_phone' => ['label' => 'Support Telefon', 'group' => 'contact', 'description' => 'Supporttelefonnummer', 'type' => 'text', 'order' => 2],
            'site_address' => ['label' => 'Företagsadress', 'group' => 'contact', 'description' => 'Fysisk adress', 'type' => 'text', 'order' => 3],
            
            // SEO
            'seo_title' => ['label' => 'SEO Titel', 'group' => 'seo', 'description' => 'Meta titel', 'type' => 'text', 'order' => 1],
            'seo_description' => ['label' => 'SEO Beskrivning', 'group' => 'seo', 'description' => 'Meta beskrivning', 'type' => 'textarea', 'order' => 2],
            'seo_keywords' => ['label' => 'SEO Nyckelord', 'group' => 'seo', 'description' => 'Nyckelord', 'type' => 'text', 'order' => 3],
            'seo_og_image' => ['label' => 'Open Graph Bild', 'group' => 'seo', 'description' => 'Social media bild', 'type' => 'image', 'order' => 4],
            
            // Loyalty Points
            'loyalty_points_enabled' => ['label' => 'Aktivera Lojalitetspoäng', 'group' => 'loyalty', 'description' => 'Slå på/av system', 'type' => 'boolean', 'order' => 1],
            'loyalty_points_value' => ['label' => 'Poäng Värde', 'group' => 'loyalty', 'description' => '1 poäng = X kr', 'type' => 'number', 'order' => 2],
            'loyalty_points_earn_rate' => ['label' => 'Intjäningsgrad', 'group' => 'loyalty', 'description' => 'Procent', 'type' => 'number', 'order' => 3],
            'loyalty_points_min_redeem' => ['label' => 'Minsta Inlösen', 'group' => 'loyalty', 'description' => 'Minsta poäng', 'type' => 'number', 'order' => 4],
            'loyalty_points_max_redeem_percent' => ['label' => 'Max Inlösen %', 'group' => 'loyalty', 'description' => 'Max procent', 'type' => 'number', 'order' => 5],
            'loyalty_points_expiry_days' => ['label' => 'Giltighetstid', 'group' => 'loyalty', 'description' => 'Antal dagar', 'type' => 'number', 'order' => 6],
            'new_user_loyalty_bonus' => ['label' => 'Välkomstbonus', 'group' => 'loyalty', 'description' => 'Poäng för nya användare', 'type' => 'number', 'order' => 7],
            
            // Booking
            'booking_cancellation_hours' => ['label' => 'Avbokningstid', 'group' => 'booking', 'description' => 'Timmar', 'type' => 'number', 'order' => 1],
            'booking_auto_assign' => ['label' => 'Automatisk Tilldelning', 'group' => 'booking', 'description' => 'Auto-tilldela', 'type' => 'boolean', 'order' => 2],
            'booking_show_companies' => ['label' => 'Visa Företagslista', 'group' => 'booking', 'description' => 'Visa företag', 'type' => 'boolean', 'order' => 3],
            'booking_allow_company_selection' => ['label' => 'Tillåt Företagsval', 'group' => 'booking', 'description' => 'Kunder välja', 'type' => 'boolean', 'order' => 4],
            
            // Commission
            'default_commission_rate' => ['label' => 'Standard Provision', 'group' => 'commission', 'description' => 'Provisionsprocent', 'type' => 'number', 'order' => 1],
        ];
        
        return $metadata[$key] ?? [
            'label' => ucfirst(str_replace('_', ' ', $key)),
            'group' => 'general',
            'description' => '',
            'type' => 'text',
            'order' => 999,
        ];
    }
}
