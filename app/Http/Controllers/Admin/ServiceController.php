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

        // Handle checkboxes - when unchecked, they don't send data
        $data['one_time_booking'] = $request->has('one_time_booking') ? 1 : 0;
        $data['subscription_booking'] = $request->has('subscription_booking') ? 1 : 0;
        $data['rot_eligible'] = $request->has('rot_eligible') ? 1 : 0;
        
        // Handle subscription types - empty array if none selected
        $data['subscription_types'] = $request->has('subscription_types') ? $request->input('subscription_types') : [];
        
        // Handle content arrays - filter out empty values
        $data['includes'] = $request->has('includes') ? array_values(array_filter($request->input('includes', []))) : [];
        $data['features'] = $request->has('features') ? array_values(array_filter($request->input('features', []), function($item) {
            return !empty($item['title']);
        })) : [];
        $data['faq'] = $request->has('faq') ? array_values(array_filter($request->input('faq', []), function($item) {
            return !empty($item['question']);
        })) : [];
        
        // Set default multipliers if not provided
        $data['daily_multiplier'] = $request->input('daily_multiplier', 1.05);
        $data['weekly_multiplier'] = $request->input('weekly_multiplier', 1.00);
        $data['biweekly_multiplier'] = $request->input('biweekly_multiplier', 0.95);
        $data['monthly_multiplier'] = $request->input('monthly_multiplier', 0.90);

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

        // Handle checkboxes - when unchecked, they don't send data
        $data['one_time_booking'] = $request->has('one_time_booking') ? 1 : 0;
        $data['subscription_booking'] = $request->has('subscription_booking') ? 1 : 0;
        $data['rot_eligible'] = $request->has('rot_eligible') ? 1 : 0;
        
        // Handle subscription types - empty array if none selected
        $data['subscription_types'] = $request->has('subscription_types') ? $request->input('subscription_types') : [];
        
        // Handle content arrays - filter out empty values
        $data['includes'] = $request->has('includes') ? array_values(array_filter($request->input('includes', []))) : [];
        $data['features'] = $request->has('features') ? array_values(array_filter($request->input('features', []), function($item) {
            return !empty($item['title']);
        })) : [];
        $data['faq'] = $request->has('faq') ? array_values(array_filter($request->input('faq', []), function($item) {
            return !empty($item['question']);
        })) : [];
        
        // Set default multipliers if not provided
        $data['daily_multiplier'] = $request->input('daily_multiplier', 1.05);
        $data['weekly_multiplier'] = $request->input('weekly_multiplier', 1.00);
        $data['biweekly_multiplier'] = $request->input('biweekly_multiplier', 0.95);
        $data['monthly_multiplier'] = $request->input('monthly_multiplier', 0.90);

        $service->update($data);

        // Sync cities
        if ($request->has('cities')) {
            $service->cities()->sync($request->cities);
        } else {
            // If no cities selected, clear all
            $service->cities()->sync([]);
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
