<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\SlotTime;
use App\Models\City;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SlotTimeController extends Controller
{
    public function index(): View
    {
        $company = auth()->user()->company;
        
        // Get slot times for the company's services
        $slotTimes = SlotTime::whereHas('service', function($query) use ($company) {
            $query->whereHas('companies', function($q) use ($company) {
                $q->where('companies.id', $company->id);
            });
        })
        ->with(['service', 'city'])
        ->orderBy('date')
        ->orderBy('start_time')
        ->paginate(20);

        // Get available cities and services for the company
        $cities = City::where('status', 'active')->orderBy('name')->get();
        $services = $company->services()->where('status', 'active')->orderBy('name')->get();

        return view('company.slot-times.index', compact('slotTimes', 'cities', 'services'));
    }

    public function create(): View
    {
        $company = auth()->user()->company;
        
        $cities = City::where('status', 'active')->orderBy('name')->get();
        $services = $company->services()->where('status', 'active')->orderBy('name')->get();

        return view('company.slot-times.create', compact('cities', 'services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $company = auth()->user()->company;
        
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:100',
            'is_available' => 'boolean',
        ]);

        // Verify the service belongs to the company
        $service = Service::where('id', $request->service_id)
            ->whereHas('companies', function($query) use ($company) {
                $query->where('companies.id', $company->id);
            })
            ->firstOrFail();

        $data = $request->only([
            'service_id', 'city_id', 'date', 'start_time', 
            'end_time', 'capacity', 'is_available'
        ]);
        
        $data['is_available'] = $request->has('is_available');
        $data['booked_count'] = 0;

        SlotTime::create($data);

        return redirect()
            ->route('company.slot-times.index')
            ->with('success', 'Tidslucka skapad framgångsrikt.');
    }

    public function show(SlotTime $slotTime): View
    {
        $company = auth()->user()->company;
        
        // Verify the slot time belongs to the company
        $this->verifyCompanyOwnership($slotTime, $company);

        $slotTime->load(['service', 'city']);

        return view('company.slot-times.show', compact('slotTime'));
    }

    public function edit(SlotTime $slotTime): View
    {
        $company = auth()->user()->company;
        
        // Verify the slot time belongs to the company
        $this->verifyCompanyOwnership($slotTime, $company);

        $cities = City::where('status', 'active')->orderBy('name')->get();
        $services = $company->services()->where('status', 'active')->orderBy('name')->get();

        return view('company.slot-times.edit', compact('slotTime', 'cities', 'services'));
    }

    public function update(Request $request, SlotTime $slotTime): RedirectResponse
    {
        $company = auth()->user()->company;
        
        // Verify the slot time belongs to the company
        $this->verifyCompanyOwnership($slotTime, $company);

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:100',
            'is_available' => 'boolean',
        ]);

        // Verify the service belongs to the company
        $service = Service::where('id', $request->service_id)
            ->whereHas('companies', function($query) use ($company) {
                $query->where('companies.id', $company->id);
            })
            ->firstOrFail();

        $data = $request->only([
            'service_id', 'city_id', 'date', 'start_time', 
            'end_time', 'capacity', 'is_available'
        ]);
        
        $data['is_available'] = $request->has('is_available');

        $slotTime->update($data);

        return redirect()
            ->route('company.slot-times.index')
            ->with('success', 'Tidslucka uppdaterad framgångsrikt.');
    }

    public function destroy(SlotTime $slotTime): RedirectResponse
    {
        $company = auth()->user()->company;
        
        // Verify the slot time belongs to the company
        $this->verifyCompanyOwnership($slotTime, $company);

        // Check if there are any bookings for this slot
        if ($slotTime->booked_count > 0) {
            return redirect()
                ->route('company.slot-times.index')
                ->with('error', 'Kan inte ta bort tidslucka med befintliga bokningar.');
        }

        $slotTime->delete();

        return redirect()
            ->route('company.slot-times.index')
            ->with('success', 'Tidslucka borttagen framgångsrikt.');
    }

    public function bulkCreate(): View
    {
        $company = auth()->user()->company;
        
        $cities = City::where('status', 'active')->orderBy('name')->get();
        $services = $company->services()->where('status', 'active')->orderBy('name')->get();

        return view('company.slot-times.bulk-create', compact('cities', 'services'));
    }

    public function bulkStore(Request $request): RedirectResponse
    {
        $company = auth()->user()->company;
        
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1|max:100',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'in:0,1,2,3,4,5,6', // 0=Sunday, 6=Saturday
            'is_available' => 'boolean',
        ]);

        // Verify the service belongs to the company
        $service = Service::where('id', $request->service_id)
            ->whereHas('companies', function($query) use ($company) {
                $query->where('companies.id', $company->id);
            })
            ->firstOrFail();

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
                        ->where('date', $currentDate->format('Y-m-d'))
                        ->where('start_time', $request->start_time)
                        ->where('end_time', $request->end_time)
                        ->first();

                    if (!$existingSlot) {
                        SlotTime::create([
                            'service_id' => $request->service_id,
                            'city_id' => $request->city_id,
                            'date' => $currentDate->format('Y-m-d'),
                            'start_time' => $request->start_time,
                            'end_time' => $request->end_time,
                            'capacity' => $request->capacity,
                            'booked_count' => 0,
                            'is_available' => $request->has('is_available'),
                        ]);
                        $createdCount++;
                    }
                }
                $currentDate->addDay();
            }
        });

        return redirect()
            ->route('company.slot-times.index')
            ->with('success', "{$createdCount} tidsluckor skapade framgångsrikt.");
    }

    private function verifyCompanyOwnership(SlotTime $slotTime, $company): void
    {
        $service = $slotTime->service;
        if (!$service || !$service->companies->contains($company->id)) {
            abort(403, 'Du har inte behörighet att hantera denna tidslucka.');
        }
    }
}