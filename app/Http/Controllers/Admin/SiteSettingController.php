<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

final class SiteSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $settings = SiteSetting::orderBy('group')->orderBy('order')->get()->groupBy('group');
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(): View
    {
        $settings = SiteSetting::orderBy('group')->orderBy('order')->get()->groupBy('group');
        
        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();
            
            if (!$setting) {
                continue;
            }

            // Handle file uploads
            if ($setting->type === 'image' && $request->hasFile("settings.{$key}")) {
                // Delete old image
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
                
                // Upload new image
                $path = $request->file("settings.{$key}")->store('settings', 'public');
                $value = $path;
            }

            SiteSetting::set($key, $value);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Inställningar uppdaterade framgångsrikt!');
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            SiteSetting::set($key, $value ?? '');
        }

        return redirect()
            ->back()
            ->with('success', 'Inställningar uppdaterade!');
    }

    /**
     * Reset to default settings
     */
    public function reset(): RedirectResponse
    {
        // This will be implemented with seeder
        \Artisan::call('db:seed', ['--class' => 'SiteSettingsSeeder', '--force' => true]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Inställningar återställda till standard!');
    }
}
