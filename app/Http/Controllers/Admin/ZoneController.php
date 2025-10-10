<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ZoneController extends Controller
{
    public function index(Request $request): View
    {
        $zones = Zone::withCount('cities')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('admin.zones.index', compact('zones'));
    }

    public function create(): View
    {
        return view('admin.zones.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Zone::create($validated);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone skapad framgångsrikt.');
    }

    public function edit(Zone $zone): View
    {
        $zone->loadCount('cities');

        return view('admin.zones.edit', compact('zone'));
    }

    public function update(Request $request, Zone $zone): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $zone->update($validated);

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone uppdaterad framgångsrikt.');
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        if ($zone->cities()->count() > 0) {
            return redirect()->route('admin.zones.index')
                ->with('error', 'Kan inte radera zone med tillhörande städer.');
        }

        $zone->delete();

        return redirect()->route('admin.zones.index')
            ->with('success', 'Zone raderad framgångsrikt.');
    }
}

