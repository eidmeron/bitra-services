<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Service;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

final class PricingController extends Controller
{
    public function index(): View
    {
        $services = Service::where('status', 'active')
            ->with(['category'])
            ->orderBy('name')
            ->get();

        $cities = City::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get SEO content from PageContent if available
        $pageContent = PageContent::getByKey('pricing-index');
        
        $seoTitle = $pageContent?->meta_title ?? 'Priser - Professionella tjänster i hela Sverige';
        $seoDescription = $pageContent?->meta_description ?? 'Se priser för alla våra professionella tjänster. Transparenta priser för städning, hantverk, flytt och mer.';
        $heroTitle = $pageContent?->hero_title ?? 'Transparenta priser för alla tjänster';
        $heroDescription = $pageContent?->hero_subtitle ?? 'Se exakta priser för alla våra professionella tjänster. Inga dolda avgifter, alltid transparenta priser.';

        return view('public.pricing.index', compact(
            'services',
            'cities',
            'seoTitle',
            'seoDescription',
            'heroTitle',
            'heroDescription'
        ));
    }

    public function service(string $serviceSlug): View
    {
        $service = Service::where('slug', $serviceSlug)
            ->where('status', 'active')
            ->with(['category'])
            ->firstOrFail();

        $cities = City::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Calculate pricing for this service
        $pricing = $this->calculateServicePricing($service);

        // Get SEO content
        $pageContent = PageContent::getByKey("pricing-service-{$service->id}");
        
        $seoTitle = $pageContent?->meta_title ?? "{$service->name} priser - Transparenta priser från Bitra";
        $seoDescription = $pageContent?->meta_description ?? "Se exakta priser för {$service->name}. Professionell service med transparenta priser. Boka enkelt online.";
        $heroTitle = $pageContent?->hero_title ?? "{$service->name} priser";
        $heroDescription = $pageContent?->hero_subtitle ?? "Se exakta priser för {$service->name} i hela Sverige. Transparenta priser, professionell service.";

        return view('public.pricing.service', compact(
            'service',
            'cities',
            'pricing',
            'seoTitle',
            'seoDescription',
            'heroTitle',
            'heroDescription'
        ));
    }

    public function cityService(string $citySlug, string $serviceSlug): View
    {
        $city = City::where('slug', $citySlug)
            ->where('status', 'active')
            ->firstOrFail();

        $service = Service::where('slug', $serviceSlug)
            ->where('status', 'active')
            ->with(['category'])
            ->firstOrFail();

        // Calculate pricing for this service in this city
        $pricing = $this->calculateCityServicePricing($service, $city);

        // Get SEO content
        $pageContent = PageContent::getByKey("pricing-city-service-{$city->id}-{$service->id}");
        
        $seoTitle = $pageContent?->meta_title ?? "{$service->name} priser i {$city->name} - Transparenta priser";
        $seoDescription = $pageContent?->meta_description ?? "Se exakta priser för {$service->name} i {$city->name}. Professionell service med transparenta priser. Boka enkelt online.";
        $heroTitle = $pageContent?->hero_title ?? "{$service->name} priser i {$city->name}";
        $heroDescription = $pageContent?->hero_subtitle ?? "Se exakta priser för {$service->name} i {$city->name}. Transparenta priser, professionell service.";

        return view('public.pricing.city-service', compact(
            'city',
            'service',
            'pricing',
            'seoTitle',
            'seoDescription',
            'heroTitle',
            'heroDescription'
        ));
    }

    private function calculateServicePricing(Service $service): array
    {
        // Base pricing calculation
        $basePrice = $service->base_price ?? 500; // Default base price
        $hourlyRate = $service->hourly_rate ?? 400; // Default hourly rate

        return [
            'base_price' => $basePrice,
            'hourly_rate' => $hourlyRate,
            'price_range' => [
                'min' => $basePrice,
                'max' => $basePrice * 2.5, // Estimated max based on complexity
            ],
            'includes' => $this->getServiceIncludes($service),
            'excludes' => $this->getServiceExcludes($service),
            'add_ons' => $this->getServiceAddOns($service),
            'faqs' => $this->getServiceFaqs($service),
        ];
    }

    private function calculateCityServicePricing(Service $service, City $city): array
    {
        $basePricing = $this->calculateServicePricing($service);
        
        // Apply city multiplier if available
        $cityMultiplier = $city->price_multiplier ?? 1.0;
        
        $adjustedBasePrice = $basePricing['base_price'] * $cityMultiplier;
        $adjustedHourlyRate = $basePricing['hourly_rate'] * $cityMultiplier;

        return [
            'base_price' => $adjustedBasePrice,
            'hourly_rate' => $adjustedHourlyRate,
            'city_multiplier' => $cityMultiplier,
            'price_range' => [
                'min' => $adjustedBasePrice,
                'max' => $adjustedBasePrice * 2.5,
            ],
            'includes' => $basePricing['includes'],
            'excludes' => $basePricing['excludes'],
            'add_ons' => $basePricing['add_ons'],
            'faqs' => $basePricing['faqs'],
        ];
    }

    private function getServiceIncludes(Service $service): array
    {
        $defaultIncludes = [
            'Professionell personal med erfarenhet',
            'Alla nödvändiga material och verktyg',
            'Försäkring och garanti',
            'Gratis offert',
            'Flexibla betalningsalternativ',
        ];

        // Service-specific includes
        $serviceIncludes = match($service->name) {
            'Städning', 'Hemstädning' => [
                'Alla städmaterial inkluderade',
                'Miljövänliga produkter',
                'Städning av alla rum',
                'Fönsterputsning',
                'Städning av kök och badrum',
            ],
            'Hantverk', 'VVS', 'El' => [
                'Licensierad personal',
                'Alla verktyg och material',
                'Garanti på utfört arbete',
                'Professionell rådgivning',
                'Snabb service',
            ],
            'Flytt', 'Flytthjälp' => [
                'Flyttkartonger och emballage',
                'Transport och lastning',
                'Försäkring av gods',
                'Montering/demontering av möbler',
                'Städning av gamla lägenheten',
            ],
            default => $defaultIncludes,
        };

        return array_merge($defaultIncludes, $serviceIncludes);
    }

    private function getServiceExcludes(Service $service): array
    {
        return [
            'Specialutrustning som kräver extra kostnad',
            'Material som kräver särskild beställning',
            'Arbete som kräver bygglov',
            'Transport av farliga ämnen',
        ];
    }

    private function getServiceAddOns(Service $service): array
    {
        return [
            [
                'name' => 'Express service (samma dag)',
                'price' => '+ 200 SEK',
                'description' => 'Få din tjänst utförd samma dag',
            ],
            [
                'name' => 'Helger och kvällar',
                'price' => '+ 25%',
                'description' => 'Service på helger och efter 18:00',
            ],
            [
                'name' => 'Extra material',
                'price' => 'Kostnad + 15%',
                'description' => 'Specialmaterial som inte ingår i standardpriset',
            ],
        ];
    }

    private function getServiceFaqs(Service $service): array
    {
        return [
            [
                'question' => 'Hur beräknas priset?',
                'answer' => 'Priset baseras på tjänstens omfattning, tidsåtgång och eventuella specialkrav. Vi ger alltid en transparent offert innan arbetet påbörjas.',
            ],
            [
                'question' => 'Finns det några dolda avgifter?',
                'answer' => 'Nej, alla priser är transparenta. Det enda som kan tillkomma är specialmaterial som diskuteras i förväg.',
            ],
            [
                'question' => 'Kan jag få ROT-avdrag?',
                'answer' => 'Ja, många av våra tjänster är ROT-berättigade. Vi hjälper dig med all nödvändig dokumentation.',
            ],
            [
                'question' => 'Vad händer om jag inte är nöjd?',
                'answer' => 'Vi garanterar din tillfredsställelse. Om du inte är nöjd kommer vi att åtgärda problemet utan extra kostnad.',
            ],
        ];
    }
}
