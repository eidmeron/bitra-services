<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\EmailSubscriber;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

final class EmailMarketingController extends Controller
{
    public function index(): View
    {
        $campaigns = EmailCampaign::orderBy('created_at', 'desc')->paginate(20);
        $subscribers = EmailSubscriber::active()->count();
        $totalUsers = User::count();
        $totalCompanies = Company::where('status', 'active')->count();
        
        return view('admin.email-marketing.index', compact(
            'campaigns',
            'subscribers',
            'totalUsers',
            'totalCompanies'
        ));
    }

    public function create(): View
    {
        return view('admin.email-marketing.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:newsletter,promotional,transactional,welcome,reminder',
            'target_audience' => 'required|array',
            'target_audience.*' => 'in:users,companies,guests,all',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $validated['status'] = $validated['scheduled_at'] ? 'scheduled' : 'draft';
        $validated['target_audience'] = $validated['target_audience'];

        $campaign = EmailCampaign::create($validated);

        return redirect()
            ->route('admin.email-marketing.index')
            ->with('success', 'Email campaign created successfully!');
    }

    public function show(EmailCampaign $campaign): View
    {
        $campaign->load('stats');
        return view('admin.email-marketing.show', compact('campaign'));
    }

    public function edit(EmailCampaign $campaign): View
    {
        return view('admin.email-marketing.edit', compact('campaign'));
    }

    public function update(Request $request, EmailCampaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:newsletter,promotional,transactional,welcome,reminder',
            'target_audience' => 'required|array',
            'target_audience.*' => 'in:users,companies,guests,all',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $validated['target_audience'] = $validated['target_audience'];

        if ($validated['scheduled_at'] && $campaign->status === 'draft') {
            $validated['status'] = 'scheduled';
        }

        $campaign->update($validated);

        return redirect()
            ->route('admin.email-marketing.index')
            ->with('success', 'Email campaign updated successfully!');
    }

    public function destroy(EmailCampaign $campaign): RedirectResponse
    {
        if ($campaign->status === 'sending' || $campaign->status === 'sent') {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete a campaign that has been sent or is currently sending.');
        }

        $campaign->delete();

        return redirect()
            ->route('admin.email-marketing.index')
            ->with('success', 'Email campaign deleted successfully!');
    }

    public function send(EmailCampaign $campaign): RedirectResponse
    {
        if (!$campaign->canBeSent()) {
            return redirect()
                ->back()
                ->with('error', 'This campaign cannot be sent.');
        }

        // Get recipients based on target audience
        $recipients = $this->getRecipients($campaign->target_audience);
        
        $campaign->update([
            'status' => 'sending',
            'total_recipients' => $recipients->count(),
        ]);

        // Queue the email sending job
        foreach ($recipients as $recipient) {
            Queue::push(new \App\Jobs\SendEmailCampaign($campaign, $recipient));
        }

        return redirect()
            ->back()
            ->with('success', 'Email campaign is being sent to ' . $recipients->count() . ' recipients.');
    }

    public function subscribers(): View
    {
        $subscribers = EmailSubscriber::with(['user', 'company'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $stats = [
            'total' => EmailSubscriber::count(),
            'active' => EmailSubscriber::active()->count(),
            'users' => EmailSubscriber::users()->active()->count(),
            'companies' => EmailSubscriber::companies()->active()->count(),
            'guests' => EmailSubscriber::guests()->active()->count(),
        ];

        return view('admin.email-marketing.subscribers', compact('subscribers', 'stats'));
    }

    public function unsubscribe(Request $request, string $token): View
    {
        $subscriber = EmailSubscriber::where('unsubscribe_token', $token)->firstOrFail();
        
        if ($request->isMethod('post')) {
            $subscriber->unsubscribe();
            return view('admin.email-marketing.unsubscribed', compact('subscriber'));
        }

        return view('admin.email-marketing.unsubscribe', compact('subscriber'));
    }

    private function getRecipients(array $targetAudience)
    {
        $recipients = collect();

        if (in_array('all', $targetAudience)) {
            $recipients = $recipients->merge(EmailSubscriber::active()->get());
        } else {
            if (in_array('users', $targetAudience)) {
                $recipients = $recipients->merge(EmailSubscriber::users()->active()->get());
            }
            if (in_array('companies', $targetAudience)) {
                $recipients = $recipients->merge(EmailSubscriber::companies()->active()->get());
            }
            if (in_array('guests', $targetAudience)) {
                $recipients = $recipients->merge(EmailSubscriber::guests()->active()->get());
            }
        }

        return $recipients->unique('email');
    }
}