<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlotTime;
use App\Models\City;
use App\Models\Service;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SlotTimeController extends Controller
{
    public function index(Request $request): View
    {
        $query = SlotTime::with(['service', 'city', 'company'])
            ->future()
            ->orderBy('date')
            ->orderBy('start_time');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('service', function($serviceQuery) use ($request) {
                    $serviceQuery->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('city', function($cityQuery) use ($request) {
                    $cityQuery->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('company', function($companyQuery) use ($request) {
                    $companyQuery->where('company_name', 'like', '%' . $request->search . '%');
                });
            });
        }

        // Service filter
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // City filter
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Availability filter
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->available();
            } elseif ($request->availability === 'full') {
                $query->whereColumn('booked_count', '>=', 'capacity');
            } elseif ($request->availability === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $slotTimes = $query->paginate(20);

        // Get filter options
        $services = Service::where('status', 'active')->orderBy('name')->get();
        $cities = City::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where('status', 'active')->orderBy('company_name')->get();

        // Statistics
        $stats = [
            'total_slots' => SlotTime::future()->count(),
            'available_slots' => SlotTime::future()->available()->count(),
            'full_slots' => SlotTime::future()->whereColumn('booked_count', '>=', 'capacity')->count(),
            'unavailable_slots' => SlotTime::future()->where('is_available', false)->count(),
            'total_capacity' => SlotTime::future()->sum('capacity'),
            'total_booked' => SlotTime::future()->sum('booked_count'),
        ];

        return view('admin.slot-times.index', compact(
            'slotTimes', 
            'services', 
            'cities', 
            'companies', 
            'stats'
        ));
    }

    public function create(): View
    {
        $services = Service::where('status', 'active')->orderBy('name')->get();
        $cities = City::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.slot-times.create', compact('services', 'cities', 'companies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'company_id' => 'nullable|exists:companies,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:100',
            'price_multiplier' => 'nullable|numeric|min:0.01|max:10.00',
            'is_available' => 'boolean',
        ]);

        $data = $request->only([
            'service_id', 'city_id', 'company_id', 'date', 'start_time', 
            'end_time', 'capacity', 'price_multiplier', 'is_available'
        ]);
        
        $data['is_available'] = $request->has('is_available');
        $data['booked_count'] = 0;
        $data['price_multiplier'] = $data['price_multiplier'] ?? 1.00;

        SlotTime::create($data);

        return redirect()
            ->route('admin.slot-times.index')
            ->with('success', 'Tidslucka skapad framgångsrikt.');
    }

    public function show(SlotTime $slotTime): View
    {
        $slotTime->load(['service', 'city', 'company']);

        return view('admin.slot-times.show', compact('slotTime'));
    }

    public function edit(SlotTime $slotTime): View
    {
        $services = Service::where('status', 'active')->orderBy('name')->get();
        $cities = City::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.slot-times.edit', compact('slotTime', 'services', 'cities', 'companies'));
    }

    public function update(Request $request, SlotTime $slotTime): RedirectResponse
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'company_id' => 'nullable|exists:companies,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:100',
            'price_multiplier' => 'nullable|numeric|min:0.01|max:10.00',
            'is_available' => 'boolean',
        ]);

        $data = $request->only([
            'service_id', 'city_id', 'company_id', 'date', 'start_time', 
            'end_time', 'capacity', 'price_multiplier', 'is_available'
        ]);
        
        $data['is_available'] = $request->has('is_available');
        $data['price_multiplier'] = $data['price_multiplier'] ?? 1.00;

        // Debug: Log the data being updated
        \Log::info('Updating slot time', [
            'slot_time_id' => $slotTime->id,
            'data' => $data,
            'request_data' => $request->all()
        ]);

        $updated = $slotTime->update($data);

        if (!$updated) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Kunde inte uppdatera tidsluckan. Kontrollera att alla fält är korrekt ifyllda.');
        }

        return redirect()
            ->route('admin.slot-times.index')
            ->with('success', 'Tidslucka uppdaterad framgångsrikt.');
    }

    public function destroy(SlotTime $slotTime): RedirectResponse
    {
        // Check if slot has bookings
        if ($slotTime->booked_count > 0) {
            return redirect()
                ->route('admin.slot-times.index')
                ->with('error', 'Kan inte radera tidslucka som har bokningar. Antal bokningar: ' . $slotTime->booked_count);
        }

        $slotTime->delete();

        return redirect()
            ->route('admin.slot-times.index')
            ->with('success', 'Tidslucka raderad framgångsrikt.');
    }

    public function bulkCreate(): View
    {
        $services = Service::where('status', 'active')->orderBy('name')->get();
        $cities = City::where('status', 'active')->orderBy('name')->get();
        $companies = Company::where('status', 'active')->orderBy('company_name')->get();

        return view('admin.slot-times.bulk-create', compact('services', 'cities', 'companies'));
    }

    public function bulkStore(Request $request): RedirectResponse
    {
        // Debug: Log the request data
        \Log::info('Bulk store request', [
            'request_data' => $request->all()
        ]);

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'company_id' => 'nullable|exists:companies,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:100',
            'price_multiplier' => 'nullable|numeric|min:0.01|max:10.00',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'integer|between:0,6',
            'is_available' => 'boolean',
        ]);

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $daysOfWeek = $request->days_of_week;
        $createdCount = 0;

        DB::transaction(function() use ($startDate, $endDate, $daysOfWeek, $request, &$createdCount) {
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                if (in_array($currentDate->dayOfWeek, $daysOfWeek)) {
                    // Check if slot already exists
                    $existingSlot = SlotTime::where('service_id', $request->service_id)
                        ->where('city_id', $request->city_id)
                        ->where('company_id', $request->company_id)
                        ->where('date', $currentDate->format('Y-m-d'))
                        ->where('start_time', $request->start_time)
                        ->where('end_time', $request->end_time)
                        ->first();

                    if (!$existingSlot) {
                        SlotTime::create([
                            'service_id' => $request->service_id,
                            'city_id' => $request->city_id,
                            'company_id' => $request->company_id,
                            'date' => $currentDate->format('Y-m-d'),
                            'start_time' => $request->start_time,
                            'end_time' => $request->end_time,
                            'capacity' => $request->capacity,
                            'price_multiplier' => $request->price_multiplier ?? 1.00,
                            'booked_count' => 0,
                            'is_available' => $request->has('is_available'),
                        ]);
                        $createdCount++;
                    }
                }
                $currentDate->addDay();
            }
        });

        \Log::info('Bulk store completed', [
            'created_count' => $createdCount,
            'request_data' => $request->all()
        ]);

        return redirect()
            ->route('admin.slot-times.index')
            ->with('success', "{$createdCount} tidsluckor skapade framgångsrikt.");
    }

    public function toggleAvailability(SlotTime $slotTime): RedirectResponse
    {
        $slotTime->update(['is_available' => !$slotTime->is_available]);
        
        $status = $slotTime->is_available ? 'tillgänglig' : 'otillgänglig';
        
        return redirect()
            ->route('admin.slot-times.index')
            ->with('success', "Tidslucka markerad som {$status}.");
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        // Debug: Log the request data
        \Log::info('Bulk delete request', [
            'slot_ids' => $request->slot_ids,
            'all_data' => $request->all()
        ]);

        $request->validate([
            'slot_ids' => 'required|array|min:1',
            'slot_ids.*' => 'exists:slot_times,id',
        ]);

        $slots = SlotTime::whereIn('id', $request->slot_ids);
        
        // Check if any slots have bookings
        $slotsWithBookings = $slots->where('booked_count', '>', 0)->count();
        
        if ($slotsWithBookings > 0) {
            return redirect()
                ->route('admin.slot-times.index')
                ->with('error', "Kan inte radera {$slotsWithBookings} tidsluckor som har bokningar.");
        }

        $deletedCount = $slots->delete();

        \Log::info('Bulk delete completed', [
            'deleted_count' => $deletedCount,
            'slot_ids' => $request->slot_ids
        ]);

        return redirect()
            ->route('admin.slot-times.index')
            ->with('success', "{$deletedCount} tidsluckor raderade framgångsrikt.");
    }
}
