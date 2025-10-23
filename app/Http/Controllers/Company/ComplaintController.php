<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ComplaintController extends Controller
{
    public function index(): View
    {
        $company = auth()->user()->company;
        
        $complaints = Complaint::where('company_id', $company->id)
            ->with(['booking.service', 'booking.city', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('company.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint): View
    {
        $company = auth()->user()->company;

        // Check if complaint belongs to this company
        if ($complaint->company_id !== $company->id) {
            abort(403);
        }

        $complaint->load(['booking.service', 'booking.city', 'user', 'messages']);

        // Mark messages as read
        $complaint->messages()->where('sender_type', '!=', 'company')->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return view('company.complaints.show', compact('complaint'));
    }

    public function sendMessage(Request $request, Complaint $complaint): RedirectResponse
    {
        $company = auth()->user()->company;

        // Check if complaint belongs to this company
        if ($complaint->company_id !== $company->id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        ComplaintMessage::create([
            'complaint_id' => $complaint->id,
            'sender_type' => 'company',
            'sender_id' => $company->id,
            'message' => $request->message,
        ]);

        return redirect()->route('company.complaints.show', $complaint)
            ->with('success', 'Meddelande skickat.');
    }
}