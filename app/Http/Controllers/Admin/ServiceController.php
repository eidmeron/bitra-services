<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->category_id, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        $categories = Category::all();

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::where('status', 'active')->get();
        $cities = City::where('status', 'active')->get();

        return view('admin.services.create', compact('categories', 'cities'));
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $data['slug'] = Str::slug($data['name']);

        $service = Service::create($data);

        // Attach cities
        if ($request->has('cities')) {
            $service->cities()->sync($request->cities);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Tjänst skapad framgångsrikt.');
    }

    public function edit(Service $service): View
    {
        $categories = Category::where('status', 'active')->get();
        $cities = City::where('status', 'active')->get();
        $service->load('cities');

        return view('admin.services.edit', compact('service', 'categories', 'cities'));
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $data['slug'] = Str::slug($data['name']);

        $service->update($data);

        // Sync cities
        if ($request->has('cities')) {
            $service->cities()->sync($request->cities);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Tjänst uppdaterad framgångsrikt.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Tjänst raderad framgångsrikt.');
    }
}
