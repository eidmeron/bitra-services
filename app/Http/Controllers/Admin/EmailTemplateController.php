<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    public function index(): View
    {
        $templates = NotificationSetting::where('type', 'email')
            ->orderBy('category')
            ->orderBy('event')
            ->get()
            ->groupBy('category');

        return view('admin.email-templates.index', compact('templates'));
    }

    public function edit(NotificationSetting $notificationSetting): View
    {
        if ($notificationSetting->type !== 'email') {
            abort(404);
        }

        return view('admin.email-templates.edit', compact('notificationSetting'));
    }

    public function update(Request $request, NotificationSetting $notificationSetting): RedirectResponse
    {
        if ($notificationSetting->type !== 'email') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'enabled' => 'boolean',
            'auto_send' => 'boolean',
            'delay_minutes' => 'required|integer|min:0|max:10080', // Max 1 week
            'priority' => 'required|in:low,normal,high',
        ]);

        $data = $request->only([
            'name', 'description', 'subject', 'body', 
            'enabled', 'auto_send', 'delay_minutes', 'priority'
        ]);
        
        $data['enabled'] = $request->has('enabled');
        $data['auto_send'] = $request->has('auto_send');

        $notificationSetting->update($data);

        return redirect()
            ->route('admin.email-templates.index')
            ->with('success', 'E-postmall uppdaterad framgångsrikt.');
    }

    public function preview(NotificationSetting $notificationSetting): View
    {
        if ($notificationSetting->type !== 'email') {
            abort(404);
        }

        // Sample data for preview
        $sampleData = [
            'user_name' => 'Anna Andersson',
            'company_name' => 'Städservice AB',
            'service_name' => 'Hemstädning',
            'booking_number' => 'BKT-2025-001',
            'booking_date' => '2025-10-30',
            'booking_time' => '14:00 - 15:00',
            'total_amount' => '450',
            'loyalty_points_earned' => '45',
            'total_loyalty_points' => '145',
            'cancellation_reason' => 'Kund avbokade',
            'customer_name' => 'Erik Eriksson',
            'customer_email' => 'erik@example.com',
            'customer_phone' => '+46 70 123 45 67',
            'booking_url' => 'https://bitra.se/bookings/BKT-2025-001',
            'review_url' => 'https://bitra.se/reviews/create/BKT-2025-001',
            'dashboard_url' => 'https://bitra.se/dashboard',
            'invoice_number' => 'INV-2025-001',
            'invoice_period' => '2025-10-01 - 2025-10-31',
            'due_date' => '2025-11-30',
            'bankgiro_number' => '123-4567',
            'new_status' => 'Bekräftad',
        ];

        // Replace placeholders in subject and body
        $previewSubject = $this->replacePlaceholders($notificationSetting->subject ?? '', $sampleData);
        $previewBody = $this->replacePlaceholders($notificationSetting->body ?? '', $sampleData);

        return view('admin.email-templates.preview', compact(
            'notificationSetting', 
            'previewSubject', 
            'previewBody', 
            'sampleData'
        ));
    }

    public function test(NotificationSetting $notificationSetting): RedirectResponse
    {
        if ($notificationSetting->type !== 'email') {
            abort(404);
        }

        // Here you would implement actual email sending
        // For now, just return a success message
        return redirect()
            ->route('admin.email-templates.index')
            ->with('success', 'Test-e-post skickat framgångsrikt.');
    }

    private function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        return $content;
    }
}
