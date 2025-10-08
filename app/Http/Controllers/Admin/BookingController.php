<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Company;
use App\Services\BookingWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private BookingWorkflowService $bookingWorkflow
    ) {
    }

    public function index(Request $request): View
    {
        $bookings = Booking::with(['service', 'city', 'user', 'company'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->service_id, function ($query, $serviceId) {
                $query->where('service_id', $serviceId);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('booking_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['service', 'city', 'user', 'company', 'form', 'review']);

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

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Bokning raderad framgångsrikt.');
    }
}
