<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(Request $request): View
    {
        $cities = City::with('zone')
            ->when($request->zone_id, function ($query, $zoneId) {
                $query->where('zone_id', $zoneId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        $zones = Zone::all();

        return view('admin.cities.index', compact('cities', 'zones'));
    }

    public function create(): View
    {
        $zones = Zone::where('status', 'active')->get();

        return view('admin.cities.create', compact('zones'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city_multiplier' => 'required|numeric|min:0|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        City::create($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', 'Stad skapad framgångsrikt.');
    }

    public function edit(City $city): View
    {
        $zones = Zone::where('status', 'active')->get();

        return view('admin.cities.edit', compact('city', 'zones'));
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city_multiplier' => 'required|numeric|min:0|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $city->update($validated);

        return redirect()->route('admin.cities.index')
            ->with('success', 'Stad uppdaterad framgångsrikt.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'Stad raderad framgångsrikt.');
    }
}
