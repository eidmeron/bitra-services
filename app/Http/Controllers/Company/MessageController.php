<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyMessage;
use Illuminate\Http\Request;

final class MessageController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        
        $messages = CompanyMessage::where('company_id', $company->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $newCount = CompanyMessage::where('company_id', $company->id)
            ->where('status', 'new')
            ->count();
        
        return view('company.messages.index', compact('messages', 'newCount'));
    }

    public function show(CompanyMessage $message)
    {
        // Ensure the message belongs to the authenticated company
        if ($message->company_id !== auth()->user()->company->id) {
            abort(403);
        }
        
        // Mark as read if it's new
        if ($message->status === 'new') {
            $message->markAsRead();
        }
        
        return view('company.messages.show', compact('message'));
    }

    public function reply(Request $request, CompanyMessage $message)
    {
        // Ensure the message belongs to the authenticated company
        if ($message->company_id !== auth()->user()->company->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'reply' => 'required|string|max:2000',
        ]);
        
        $message->markAsReplied($validated['reply']);
        
        // Send email notification to the customer
        try {
            // Create a temporary notifiable for the guest email
            $guestNotifiable = new class($message->guest_email, $message->guest_name) {
                public function __construct(
                    public string $email,
                    public string $name
                ) {}
                
                public function routeNotificationForMail(): string
                {
                    return $this->email;
                }
            };
            
            $guestNotifiable->notify(new \App\Notifications\CompanyReplyNotification($message));
        } catch (\Exception $e) {
            \Log::error('Failed to send company reply notification', [
                'message_id' => $message->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return back()->with('success', 'Ditt svar har skickats till ' . $message->guest_email . '!');
    }
}

