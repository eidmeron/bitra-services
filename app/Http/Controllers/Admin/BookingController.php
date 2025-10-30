<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Company;
use App\Models\Service;
use App\Services\BookingWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private BookingWorkflowService $bookingWorkflow
    ) {
    }

    public function index(Request $request): View
    {
        $query = Booking::with(['service', 'city', 'user', 'company.user']);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('booking_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Service filter
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting - Default to latest first
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_high':
                $query->orderBy('final_price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('final_price', 'asc');
                break;
            case 'booking_number':
                $query->orderBy('booking_number', 'asc');
                break;
            case 'customer_name':
                $query->orderBy('customer_name', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc'); // Latest first
                break;
        }

        $bookings = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'assigned' => Booking::where('status', 'assigned')->count(),
            'in_progress' => Booking::where('status', 'in_progress')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'today' => Booking::whereDate('created_at', today())->count(),
            'this_week' => Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Booking::whereMonth('created_at', now()->month)->count(),
            'total_revenue' => Booking::whereIn('status', ['completed', 'in_progress', 'assigned'])->sum('final_price'),
            'monthly_revenue' => Booking::whereIn('status', ['completed', 'in_progress', 'assigned'])
                ->whereMonth('created_at', now()->month)
                ->sum('final_price'),
        ];

        // Filter options
        $services = Service::active()->orderBy('name')->get();
        $companies = Company::where('status', 'active')->with('user')->get();

        return view('admin.bookings.index', compact('bookings', 'stats', 'services', 'companies'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['service', 'city', 'user', 'company.user', 'form', 'review', 'slotTime']);

        $availableCompanies = $booking->status === 'pending'
            ? $this->bookingWorkflow->findAvailableCompanies($booking)
            : collect();

        return view('admin.bookings.show', compact('booking', 'availableCompanies'));
    }

    public function assign(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $company = Company::findOrFail($request->company_id);

        $this->bookingWorkflow->assignToCompany($booking, $company);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Bokning tilldelad företaget framgångsrikt.');
    }

    public function changeStatus(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $booking->status = $request->status;
        $booking->save();

        // Send email to customer if status changed
        if ($oldStatus !== $request->status) {
            $this->sendStatusChangeEmail($booking, $oldStatus, $request->status);
        }

        return redirect()->back()
            ->with('success', 'Bokningsstatus uppdaterad framgångsrikt.');
    }

    public function sendEmail(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::send('emails.booking-custom', [
                'booking' => $booking,
                'messageContent' => $request->message,
            ], function ($mail) use ($booking, $request) {
                $mail->to($booking->customer_email, $booking->customer_name)
                    ->subject($request->subject);
            });

            return redirect()->back()
                ->with('success', 'E-post skickad till kund framgångsrikt.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Det gick inte att skicka e-post: ' . $e->getMessage());
        }
    }

    public function reassignCompany(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $oldCompany = $booking->company;
        $newCompany = $request->company_id ? Company::find($request->company_id) : null;

        $booking->update([
            'company_id' => $request->company_id,
        ]);

        // Send notification to new company if assigned
        if ($newCompany) {
            $newCompany->user->notify(new \App\Notifications\BookingAssignedNotification($booking));
        }

        // Send notification to old company if unassigned
        if ($oldCompany && !$newCompany) {
            $oldCompany->user->notify(new \App\Notifications\BookingUnassignedNotification($booking));
        }

        $message = $newCompany 
            ? "Bokning tilldelad till {$newCompany->company_name}."
            : "Bokning avtilldelad från företag.";

        return redirect()->back()
            ->with('success', $message);
    }

    public function sendMessage(Request $request, Booking $booking): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'send_email' => 'boolean',
        ]);

        try {
            if ($request->boolean('send_email')) {
                // Send email notification
                Mail::send('emails.booking-custom', [
                    'booking' => $booking,
                    'messageContent' => $request->message,
                ], function ($mail) use ($booking, $request) {
                    $mail->to($booking->customer_email, $booking->customer_name)
                        ->subject($request->subject . ' - Bokning #' . $booking->booking_number);
                });
            }

            // Create chat message if user is registered
            if ($booking->user_id) {
                $booking->chats()->create([
                    'sender_type' => 'admin',
                    'sender_id' => auth()->id(),
                    'message' => $request->message,
                ]);
            }

            return redirect()->back()
                ->with('success', 'Meddelande skickat framgångsrikt.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Kunde inte skicka meddelande: ' . $e->getMessage());
        }
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $bookingNumber = $booking->booking_number;
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', "Bokning #{$bookingNumber} har raderats.");
    }

    private function sendStatusChangeEmail(Booking $booking, string $oldStatus, string $newStatus): void
    {
        try {
            Mail::send('emails.booking-status-change', [
                'booking' => $booking,
                'oldStatus' => $oldStatus,
                'newStatus' => $newStatus,
            ], function ($mail) use ($booking) {
                $mail->to($booking->customer_email, $booking->customer_name)
                    ->subject('Uppdatering av din bokning #' . $booking->booking_number);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send status change email: ' . $e->getMessage());
        }
    }
}
