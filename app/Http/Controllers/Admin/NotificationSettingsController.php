<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationSettingsController extends Controller
{
    /**
     * Display notification settings
     */
    public function index(): View
    {
        $settings = NotificationSetting::orderBy('category')
            ->orderBy('event')
            ->get()
            ->groupBy('category');

        return view('admin.notification-settings.index', compact('settings'));
    }

    /**
     * Show the form for editing a notification setting
     */
    public function edit(NotificationSetting $notificationSetting): View
    {
        return view('admin.notification-settings.edit', compact('notificationSetting'));
    }

    /**
     * Update the specified notification setting
     */
    public function update(Request $request, NotificationSetting $notificationSetting): RedirectResponse
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'template' => 'required|string',
            'enabled' => 'boolean',
            'auto_send' => 'boolean',
            'priority' => 'integer|min:1|max:5',
        ]);

        $notificationSetting->update($request->only([
            'subject',
            'template',
            'enabled',
            'auto_send',
            'priority'
        ]));

        return redirect()->route('admin.notification-settings.index')
            ->with('success', 'Notifieringsinställning uppdaterad framgångsrikt.');
    }

    /**
     * Toggle notification setting enabled status
     */
    public function toggle(NotificationSetting $notificationSetting): RedirectResponse
    {
        $notificationSetting->update([
            'enabled' => !$notificationSetting->enabled
        ]);

        $status = $notificationSetting->enabled ? 'aktiverad' : 'inaktiverad';
        
        return redirect()->route('admin.notification-settings.index')
            ->with('success', "Notifiering {$status} framgångsrikt.");
    }

    /**
     * Create a new notification setting
     */
    public function create(): View
    {
        return view('admin.notification-settings.create');
    }

    /**
     * Store a new notification setting
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|string|in:email,sms,push,in_app',
            'category' => 'required|string|in:booking,user,company,admin,system',
            'event' => 'required|string|max:100',
            'subject' => 'nullable|string|max:255',
            'template' => 'required|string',
            'variables' => 'nullable|array',
            'enabled' => 'boolean',
            'auto_send' => 'boolean',
            'recipients' => 'nullable|array',
            'conditions' => 'nullable|array',
            'priority' => 'integer|min:1|max:5',
        ]);

        NotificationSetting::create($request->all());

        return redirect()->route('admin.notification-settings.index')
            ->with('success', 'Notifieringsinställning skapad framgångsrikt.');
    }

    /**
     * Delete a notification setting
     */
    public function destroy(NotificationSetting $notificationSetting): RedirectResponse
    {
        $notificationSetting->delete();

        return redirect()->route('admin.notification-settings.index')
            ->with('success', 'Notifieringsinställning raderad framgångsrikt.');
    }

    /**
     * Preview notification template
     */
    public function preview(Request $request): View
    {
        $template = $request->get('template', '');
        $variables = $request->get('variables', []);
        
        // Sample variables for preview
        $sampleVariables = [
            'user_name' => 'Anna Andersson',
            'company_name' => 'Städfirman AB',
            'service_name' => 'Hemstädning',
            'booking_number' => 'BOK-2025-001',
            'booking_date' => '2025-01-15',
            'booking_time' => '14:00',
            'total_amount' => '1,250 kr',
            'loyalty_points' => '125',
            'invoice_number' => 'INV-2025-000001',
        ];

        $processedTemplate = $template;
        foreach ($sampleVariables as $key => $value) {
            $processedTemplate = str_replace("{{$key}}", $value, $processedTemplate);
        }

        return view('admin.notification-settings.preview', compact('processedTemplate', 'template'));
    }
}
