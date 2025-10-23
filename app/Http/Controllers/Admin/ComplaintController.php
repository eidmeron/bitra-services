<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $query = Complaint::with(['booking.service', 'booking.city', 'company.user', 'user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('complaint_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get companies for filter dropdown
        $companies = \App\Models\Company::with('user')->get();

        return view('admin.complaints.index', compact('complaints', 'companies'));
    }

    public function show(Complaint $complaint): View
    {
        $complaint->load(['booking.service', 'booking.city', 'company.user', 'user', 'messages']);

        // Mark messages as read
        $complaint->messages()->where('sender_type', '!=', 'admin')->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
        } elseif ($request->status === 'closed') {
            $updateData['closed_at'] = now();
        }

        $complaint->update($updateData);

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Status uppdaterad.');
    }

    public function sendMessage(Request $request, Complaint $complaint): RedirectResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        ComplaintMessage::create([
            'complaint_id' => $complaint->id,
            'sender_type' => 'admin',
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Update status to in_progress if it was open
        if ($complaint->status === 'open') {
            $complaint->update(['status' => 'in_progress']);
        }

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Meddelande skickat.');
    }

    public function resolve(Request $request, Complaint $complaint): RedirectResponse
    {
        $request->validate([
            'resolution' => 'required|string|max:2000',
        ]);

        $complaint->update([
            'status' => 'resolved',
            'resolution' => $request->resolution,
            'resolved_at' => now(),
        ]);

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Reklamation löst.');
    }

    public function close(Complaint $complaint): RedirectResponse
    {
        $complaint->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Reklamation stängd.');
    }
}