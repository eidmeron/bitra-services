<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Service;
use App\Models\Company;
use App\Models\PageContent;
use Illuminate\View\View;

final class CityServiceLandingController extends Controller
{
    public function show(string $citySlug, string $serviceSlug): View
    {
        $city = City::where('slug', $citySlug)->where('status', 'active')->firstOrFail();
        $service = Service::where('slug', $serviceSlug)->where('status', 'active')->with('category')->firstOrFail();

        // Pricing snapshot (for SEO and context)
        $basePrice = (float) ($service->base_price ?? 500);
        $hourlyRate = (float) ($service->hourly_rate ?? 400);
        $multiplier = (float) ($city->price_multiplier ?? 1.0);
        $priceFrom = (int) round($basePrice * $multiplier);
        $priceRangeMax = (int) round($priceFrom * 2.5);

        // Page content (CMS override if exists)
        $pageContent = PageContent::getByKey("landing-city-service-{$city->id}-{$service->id}");

        $seoTitle = $pageContent?->meta_title ?? "$service->name i $city->name – Pris från $priceFrom SEK";
        $seoDescription = $pageContent?->meta_description ?? "Boka $service->name i $city->name. Transparenta priser från $priceFrom SEK. Professionella utförare, garanti och snabb service.";
        $heroTitle = $pageContent?->hero_title ?? "$service->name i $city->name";
        $heroSubtitle = $pageContent?->hero_subtitle ?? "Pris från $priceFrom SEK • Snabb bokning • Verifierade utförare";

        // Get companies that offer this service in this city
        $companies = Company::where('status', 'active')
            ->whereHas('services', function ($query) use ($service) {
                $query->where('service_id', $service->id);
            })
            ->whereHas('cities', function ($query) use ($city) {
                $query->where('city_id', $city->id);
            })
            ->with(['user', 'services', 'cities'])
            ->get();

        // CTA target: Prefer active form if available, else service pricing page
        $form = $service->active_form;
        $formToken = $form->public_token ?? ($form->token ?? null);
        $ctaUrl = $formToken
            ? route('public.form', ['token' => $formToken])
            : route('public.pricing.service', $service->slug);

        return view('public.city-service.show', compact(
            'city',
            'service',
            'companies',
            'seoTitle',
            'seoDescription',
            'heroTitle',
            'heroSubtitle',
            'priceFrom',
            'priceRangeMax',
            'hourlyRate',
            'multiplier',
            'ctaUrl'
        ));
    }
}


