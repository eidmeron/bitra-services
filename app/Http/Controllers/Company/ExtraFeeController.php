<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\ExtraFee;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ExtraFeeController extends Controller
{
    public function create(Booking $booking): View
    {
        // Ensure the booking belongs to the company
        if ($booking->company_id !== auth()->user()->company->id) {
            abort(403, 'Du har inte behörighet att hantera denna bokning.');
        }

        return view('company.extra-fees.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        // Ensure the booking belongs to the company
        if ($booking->company_id !== auth()->user()->company->id) {
            abort(403, 'Du har inte behörighet att hantera denna bokning.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048000',
        ]);

        $data = [
            'booking_id' => $booking->id,
            'company_id' => auth()->user()->company->id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('extra-fees', 'public');
        }

        ExtraFee::create($data);

        return redirect()->route('company.bookings.show', $booking)
            ->with('success', 'Extra avgift skapad och väntar på godkännande.');
    }

    public function edit(ExtraFee $extraFee): View
    {
        // Ensure the extra fee belongs to the company
        if ($extraFee->company_id !== auth()->user()->company->id) {
            abort(403, 'Du har inte behörighet att redigera denna extra avgift.');
        }

        // Only allow editing if status is pending
        if ($extraFee->status !== 'pending') {
            abort(403, 'Du kan endast redigera extra avgifter med status "väntande".');
        }

        return view('company.extra-fees.edit', compact('extraFee'));
    }

    public function update(Request $request, ExtraFee $extraFee): RedirectResponse
    {
        // Ensure the extra fee belongs to the company
        if ($extraFee->company_id !== auth()->user()->company->id) {
            abort(403, 'Du har inte behörighet att redigera denna extra avgift.');
        }

        // Only allow editing if status is pending
        if ($extraFee->status !== 'pending') {
            abort(403, 'Du kan endast redigera extra avgifter med status "väntande".');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048000',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($extraFee->photo_path) {
                \Storage::disk('public')->delete($extraFee->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('extra-fees', 'public');
        }

        $extraFee->update($data);

        return redirect()->route('company.bookings.show', $extraFee->booking)
            ->with('success', 'Extra avgift uppdaterad.');
    }

    public function destroy(ExtraFee $extraFee): RedirectResponse
    {
        // Ensure the extra fee belongs to the company
        if ($extraFee->company_id !== auth()->user()->company->id) {
            abort(403, 'Du har inte behörighet att ta bort denna extra avgift.');
        }

        // Only allow deletion if status is pending
        if ($extraFee->status !== 'pending') {
            abort(403, 'Du kan endast ta bort extra avgifter med status "väntande".');
        }

        // Delete photo if exists
        if ($extraFee->photo_path) {
            \Storage::disk('public')->delete($extraFee->photo_path);
        }

        $booking = $extraFee->booking;
        $extraFee->delete();

        return redirect()->route('company.bookings.show', $booking)
            ->with('success', 'Extra avgift borttagen.');
    }
}
