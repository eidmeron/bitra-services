<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

final class ComplaintController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        if ($user->type === 'user') {
            $complaints = Complaint::where('user_id', $user->id)
                ->with(['booking.service', 'booking.city', 'company.user'])
                ->latest()
                ->paginate(10);
        } elseif ($user->type === 'company') {
            $complaints = Complaint::where('company_id', $user->company->id)
                ->with(['booking.service', 'booking.city', 'user'])
                ->latest()
                ->paginate(10);
        } else {
            abort(403);
        }

        return view('complaints.index', compact('complaints'));
    }

    public function create(Booking $booking): View
    {
        // Only allow complaints for completed bookings
        if ($booking->status !== 'completed') {
            abort(403, 'Reklamationer kan endast skapas för slutförda bokningar.');
        }

        // Check if user has permission to create complaint for this booking
        if (auth()->check()) {
            if (auth()->user()->type === 'user' && $booking->user_id !== auth()->id()) {
                abort(403);
            }
        } else {
            // For guests, check if they have access via email
            if (request()->get('email') !== $booking->customer_email) {
                abort(403);
            }
        }

        return view('complaints.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        // Only allow complaints for completed bookings
        if ($booking->status !== 'completed') {
            abort(403, 'Reklamationer kan endast skapas för slutförda bokningar.');
        }

        $complaint = DB::transaction(function () use ($request, $booking) {
            $complaint = Complaint::create([
                'booking_id' => $booking->id,
                'user_id' => auth()->check() ? auth()->id() : null,
                'customer_email' => $booking->customer_email,
                'customer_name' => $booking->customer_name,
                'company_id' => $booking->company_id,
                'subject' => $request->subject,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => 'open',
            ]);

            // Create initial message
            ComplaintMessage::create([
                'complaint_id' => $complaint->id,
                'sender_type' => 'customer',
                'sender_id' => auth()->check() ? auth()->id() : null,
                'message' => $request->description,
            ]);

            return $complaint;
        });

        if (auth()->check()) {
            return redirect()->route('user.complaints.show', $complaint)
                ->with('success', 'Reklamation skapad framgångsrikt.');
        } else {
            return redirect()->route('complaints.guest.show', $complaint)
                ->with('success', 'Reklamation skapad framgångsrikt.');
        }
    }

    public function show(Complaint $complaint): View
    {
        // Check permissions
        if (auth()->check()) {
            if (auth()->user()->type === 'user' && $complaint->user_id !== auth()->id()) {
                abort(403);
            }
            if (auth()->user()->type === 'company' && $complaint->company_id !== auth()->user()->company->id) {
                abort(403);
            }
        }

        $complaint->load(['booking.service', 'booking.city', 'company.user', 'messages']);

        return view('complaints.show', compact('complaint'));
    }

    public function guestShow(Complaint $complaint): View
    {
        // For guest access, require email parameter
        if (request()->get('email') !== $complaint->customer_email) {
            abort(403);
        }

        $complaint->load(['booking.service', 'booking.city', 'company.user', 'messages']);

        return view('complaints.guest-show', compact('complaint'));
    }

    public function sendMessage(Request $request, Complaint $complaint): RedirectResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB max
        ]);

        // Check permissions
        if (auth()->check()) {
            if (auth()->user()->type === 'user' && $complaint->user_id !== auth()->id()) {
                abort(403);
            }
            if (auth()->user()->type === 'company' && $complaint->company_id !== auth()->user()->company->id) {
                abort(403);
            }
        } else {
            // For guests, check email
            if (request()->get('email') !== $complaint->customer_email) {
                abort(403);
            }
        }

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('complaint-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ];
            }
        }

        $senderType = 'customer';
        $senderId = null;

        if (auth()->check()) {
            if (auth()->user()->type === 'company') {
                $senderType = 'company';
                $senderId = auth()->user()->company->id;
            } elseif (auth()->user()->type === 'user') {
                $senderType = 'customer';
                $senderId = auth()->id();
            }
        }

        ComplaintMessage::create([
            'complaint_id' => $complaint->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $request->message,
            'attachments' => $attachments,
        ]);

        // Update complaint status if it was closed
        if ($complaint->status === 'closed') {
            $complaint->update(['status' => 'open']);
        }

        $redirectRoute = auth()->check() 
            ? 'user.complaints.show' 
            : 'complaints.guest.show';

        return redirect()->route($redirectRoute, $complaint)
            ->with('success', 'Meddelande skickat.');
    }

    public function downloadAttachment(ComplaintMessage $message, int $attachmentIndex): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $attachments = $message->attachments ?? [];
        
        if (!isset($attachments[$attachmentIndex])) {
            abort(404);
        }

        $attachment = $attachments[$attachmentIndex];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment['name']);
    }
}