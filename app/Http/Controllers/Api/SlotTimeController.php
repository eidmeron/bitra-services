<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SlotTime;
use App\Models\Company;
use App\Models\Service;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

final class SlotTimeController extends Controller
{
    public function getAvailableSlots(Request $request): JsonResponse
    {
        $companyId = $request->input('company_id');
        $serviceId = $request->input('service_id');
        $cityId = $request->input('city_id');
        $date = $request->input('date');
        $days = (int) $request->input('days', 30); // Default 30 days ahead

        if (!$companyId || !$serviceId || !$cityId) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        // Get company
        $company = Company::find($companyId);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        // Get service
        $service = Service::find($serviceId);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        // Get city
        $city = City::find($cityId);
        if (!$city) {
            return response()->json(['error' => 'City not found'], 404);
        }

        // Check if company offers this service in this city
        if (!$company->services()->where('services.id', $serviceId)->exists()) {
            return response()->json(['error' => 'Company does not offer this service'], 400);
        }

        if (!$company->cities()->where('cities.id', $cityId)->exists()) {
            return response()->json(['error' => 'Company does not operate in this city'], 400);
        }

        // Get available slots (only future dates)
        $query = SlotTime::where('city_id', $cityId)
            ->where('service_id', $serviceId)
            ->future()
            ->available();

        if ($date) {
            // Get slots for specific date
            $query->whereDate('date', $date);
        } else {
            // Get slots for next N days
            $startDate = Carbon::today();
            $endDate = Carbon::today()->addDays($days);
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $slots = $query->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($slot) {
                return $slot->date->format('Y-m-d');
            })
            ->map(function ($daySlots, $date) {
                return [
                    'date' => $date,
                    'date_formatted' => Carbon::parse($date)->format('d/m/Y'),
                    'day_name' => Carbon::parse($date)->format('l'),
                    'slots' => $daySlots->map(function ($slot) {
                        return [
                            'id' => $slot->id,
                            'start_time' => $slot->start_time,
                            'end_time' => $slot->end_time,
                            'time_formatted' => Carbon::parse($slot->start_time)->format('H:i') . ' - ' . Carbon::parse($slot->end_time)->format('H:i'),
                            'capacity' => $slot->capacity,
                            'booked_count' => $slot->booked_count,
                            'available_spots' => $slot->capacity - $slot->booked_count,
                            'is_available' => $slot->hasAvailableSlots()
                        ];
                    })
                ];
            });

        return response()->json([
            'company_id' => $companyId,
            'service_id' => $serviceId,
            'city_id' => $cityId,
            'slots' => $slots,
            'total_days' => $slots->count(),
            'total_slots' => $slots->sum(function ($day) {
                return $day['slots']->count();
            })
        ]);
    }

    public function getCompanySlotTimes(Request $request): JsonResponse
    {
        $companyId = $request->input('company_id');
        $cityId = $request->input('city_id');
        $serviceId = $request->input('service_id');

        if (!$companyId) {
            return response()->json(['error' => 'Company ID is required'], 400);
        }

        $company = Company::find($companyId);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        // Get all slot times for the company
        $query = SlotTime::whereHas('city', function ($q) use ($companyId) {
            $q->whereHas('companies', function ($cq) use ($companyId) {
                $cq->where('companies.id', $companyId);
            });
        });

        if ($cityId) {
            $query->where('city_id', $cityId);
        }

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }

        $slots = $query->with(['city', 'service'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($slot) {
                return $slot->date->format('Y-m-d');
            })
            ->map(function ($daySlots, $date) {
                return [
                    'date' => $date,
                    'date_formatted' => Carbon::parse($date)->format('d/m/Y'),
                    'day_name' => Carbon::parse($date)->format('l'),
                    'slots' => $daySlots->map(function ($slot) {
                        return [
                            'id' => $slot->id,
                            'city_name' => $slot->city->name,
                            'service_name' => $slot->service->name ?? 'Alla tjänster',
                            'start_time' => $slot->start_time,
                            'end_time' => $slot->end_time,
                            'time_formatted' => Carbon::parse($slot->start_time)->format('H:i') . ' - ' . Carbon::parse($slot->end_time)->format('H:i'),
                            'capacity' => $slot->capacity,
                            'booked_count' => $slot->booked_count,
                            'available_spots' => $slot->capacity - $slot->booked_count,
                            'is_available' => $slot->hasAvailableSlots()
                        ];
                    })
                ];
            });

        return response()->json([
            'company_id' => $companyId,
            'slots' => $slots,
            'total_days' => $slots->count(),
            'total_slots' => $slots->sum(function ($day) {
                return $day['slots']->count();
            })
        ]);
    }

    public function getAllAvailableSlots(Request $request): JsonResponse
    {
        $serviceId = $request->input('service_id');
        $cityId = $request->input('city_id');
        $days = (int) $request->input('days', 30);

        if (!$serviceId || !$cityId) {
            return response()->json(['error' => 'Service ID and City ID are required'], 400);
        }

        // Get all available slots for any company offering this service in this city (only future dates)
        $query = SlotTime::where('city_id', $cityId)
            ->where('service_id', $serviceId)
            ->future()
            ->available();

        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays($days);
        $query->whereBetween('date', [$startDate, $endDate]);

        $slots = $query->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($slot) {
                return $slot->date->format('Y-m-d');
            })
            ->map(function ($daySlots, $date) {
                return [
                    'date' => $date,
                    'date_formatted' => Carbon::parse($date)->format('d/m/Y'),
                    'day_name' => Carbon::parse($date)->format('l'),
                    'slots' => $daySlots->map(function ($slot) {
                        return [
                            'id' => $slot->id,
                            'start_time' => $slot->start_time,
                            'end_time' => $slot->end_time,
                            'time_formatted' => Carbon::parse($slot->start_time)->format('H:i') . ' - ' . Carbon::parse($slot->end_time)->format('H:i'),
                            'capacity' => $slot->capacity,
                            'booked_count' => $slot->booked_count,
                            'available_spots' => $slot->capacity - $slot->booked_count,
                            'is_available' => $slot->hasAvailableSlots()
                        ];
                    })
                ];
            });

        return response()->json([
            'service_id' => $serviceId,
            'city_id' => $cityId,
            'slots' => $slots,
            'total_days' => $slots->count(),
            'total_slots' => $slots->sum(function ($day) {
                return $day['slots']->count();
            })
        ]);
    }
}
