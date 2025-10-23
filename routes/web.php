<?php

declare(strict_types=1);

use App\Http\Controllers\BookingChatController;
use App\Http\Controllers\BookingSubmissionController;
use App\Http\Controllers\PublicFormController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Public form routes
Route::get('/form/{token}', [PublicFormController::class, 'show'])->name('public.form');
Route::post('/form/{token}', [BookingSubmissionController::class, 'store'])->name('booking.submit');
Route::get('/booking-success/{booking}', [\App\Http\Controllers\Public\BookingSuccessController::class, 'show'])->name('public.form.success');
Route::post('/booking-success/{booking}/create-account', [\App\Http\Controllers\Public\BookingSuccessController::class, 'createAccount'])->name('public.booking.create-account');

// Guest booking check
Route::get('/check-booking', [App\Http\Controllers\Public\BookingCheckController::class, 'showForm'])->name('public.booking.check.form');
Route::post('/check-booking', [App\Http\Controllers\Public\BookingCheckController::class, 'check'])->name('public.booking.check');

// Guest chat and reviews
Route::post('/booking/{booking}/chat', [\App\Http\Controllers\Public\GuestChatController::class, 'send'])->name('public.booking.chat.send');
Route::post('/booking/{booking}/review', [\App\Http\Controllers\Public\GuestReviewController::class, 'submit'])->name('public.booking.review.submit');
Route::get('/booking/{publicToken}/review', [\App\Http\Controllers\Public\GuestReviewController::class, 'show'])->name('public.booking.review.show');
Route::post('/booking/{publicToken}/review', [\App\Http\Controllers\Public\GuestReviewController::class, 'submitByToken'])->name('public.booking.review.submit.token');

// Booking Chat (accessible by users and guests)
Route::get('/booking/{bookingNumber}/chat', [BookingChatController::class, 'show'])->name('booking.chat');
Route::post('/booking/{bookingNumber}/chat', [BookingChatController::class, 'store'])->name('booking.chat.send');
Route::get('/booking/{bookingNumber}/chat/fetch', [BookingChatController::class, 'fetch'])->name('booking.chat.fetch');

// API routes for AJAX
Route::post('/api/calculate-price', [BookingSubmissionController::class, 'calculatePrice'])->name('api.calculate-price');
Route::get('/api/public/form/{token}/html', [PublicFormController::class, 'html'])->name('api.public.form.html');
Route::get('/api/companies/available', [\App\Http\Controllers\Api\CompanyController::class, 'getAvailableCompanies'])->name('api.companies.available');

// ⚠️ IMPORTANT: Auth, Admin, User, and Company routes MUST be loaded BEFORE any catch-all routes!
// This prevents catch-all slug routes from intercepting authenticated routes.
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
require __DIR__ . '/company.php';

// Global profile route that redirects based on user type
Route::middleware('auth')->get('/profile', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isCompany()) {
        return redirect()->route('company.profile');
    } else {
        return redirect()->route('user.profile');
    }
})->name('profile');

// Public Pages - Placeholder routes (will be implemented)

// Categories
Route::get('/categories', function () {
    $categories = \App\Models\Category::where('status', 'active')
        ->withCount('services')
        ->orderBy('name')
        ->get();
    return view('public.categories.index', compact('categories'));
})->name('public.categories');

Route::get('/category/{category:slug}', function (\App\Models\Category $category) {
    $category->load(['services' => function($query) {
        $query->active()->with(['cities', 'forms']);
    }]);
    
    // Get companies that offer services in this category
    $companies = \App\Models\Company::whereHas('services', function($query) use ($category) {
        $query->where('category_id', $category->id);
    })
    ->where('status', 'active')
    ->withAvg('reviews', 'rating')
    ->withCount('reviews')
    ->take(6)
    ->get();
    
    return view('public.categories.show', compact('category', 'companies'));
})->name('public.category.show');

// Services
Route::get('/services', function (Illuminate\Http\Request $request) {
    $query = \App\Models\Service::active();
    
    // Filter by category
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }
    
    // Filter by city
    if ($request->filled('city')) {
        $query->whereHas('cities', function($q) use ($request) {
            $q->where('cities.id', $request->city);
        });
    }
    
    $services = $query->with(['category', 'cities', 'forms'])->paginate(12);
    
    // Get all options for filters
    $allCategories = \App\Models\Category::where('status', 'active')->withCount('services')->orderBy('name')->get();
    $allCities = \App\Models\City::orderBy('name')->get();
    
    return view('public.services.index', compact('services', 'allCategories', 'allCities'));
})->name('public.services');

// Cities
Route::get('/cities', function () {
    $cities = \App\Models\City::with('zone')
        ->withCount(['services', 'companies'])
        ->orderBy('name')
        ->get();
    
    $citiesByZone = $cities->groupBy(function($city) {
        return $city->zone->name ?? 'Övriga Sverige';
    });
    
    return view('public.cities.index', compact('cities', 'citiesByZone'));
})->name('public.cities');

Route::get('/zones', function () {
    $zones = \App\Models\Zone::with('cities')
        ->withCount('cities')
        ->orderBy('name')
        ->get();
    
    return view('public.zones.index', compact('zones'));
})->name('public.zones');

// Companies
Route::get('/companies', function (Illuminate\Http\Request $request) {
    $query = \App\Models\Company::where('status', 'active');
    
    // Filter by city
    if ($request->filled('city')) {
        $query->whereHas('cities', function($q) use ($request) {
            $q->where('cities.id', $request->city);
        });
    }
    
    // Filter by service
    if ($request->filled('service')) {
        $query->whereHas('services', function($q) use ($request) {
            $q->where('services.id', $request->service);
        });
    }
    
    // Filter by minimum rating
    if ($request->filled('rating')) {
        $query->whereHas('reviews', function($q) use ($request) {
            $q->select(\DB::raw('AVG(rating)'))
              ->groupBy('company_id')
              ->havingRaw('AVG(rating) >= ?', [$request->rating]);
        });
    }
    
    // Sorting
    $sortBy = $request->get('sort', 'rating'); // Default: best rated first
    
    $query->with(['services', 'cities', 'user'])
          ->withAvg('reviews', 'rating')
          ->withCount('reviews');
    
    switch ($sortBy) {
        case 'rating':
            $query->orderByDesc('reviews_avg_rating');
            break;
        case 'reviews':
            $query->orderByDesc('reviews_count');
            break;
        case 'name':
            $query->orderBy('company_name');
            break;
        default:
            $query->orderByDesc('reviews_avg_rating');
    }
    
    $companies = $query->paginate(12);
    
    // Get all options for filters
    $allCities = \App\Models\City::orderBy('name')->get();
    $allServices = \App\Models\Service::active()->orderBy('name')->get();
    
    return view('public.companies.index', compact('companies', 'allCities', 'allServices'));
})->name('public.companies');

Route::get('/company/{company}', function (\App\Models\Company $company) {
    $company->load([
        'services', 
        'cities', 
        'user', 
        'reviews' => function($query) {
            $query->where('status', 'approved')
                ->with('booking.user')
                ->orderBy('created_at', 'desc');
        }
    ])
    ->loadAvg('reviews', 'rating')
    ->loadCount('reviews');
    
    // Get similar companies
    $similarCompanies = \App\Models\Company::where('status', 'active')
        ->where('id', '!=', $company->id)
        ->with(['services', 'cities', 'user'])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->limit(4)
        ->get();
    
    return view('public.companies.show', compact('company', 'similarCompanies'));
})->name('public.company.show');

// Company message submission
Route::post('/company/{company}/message', [\App\Http\Controllers\Public\CompanyMessageController::class, 'send'])->name('company.message.send');

// Platform reviews
Route::get('/reviews', [\App\Http\Controllers\Public\PlatformReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [\App\Http\Controllers\Public\PlatformReviewController::class, 'store'])->name('reviews.store');

Route::get('/search', [\App\Http\Controllers\Public\SearchController::class, 'index'])->name('public.search');

// XML Sitemaps
Route::get('/sitemap.xml', [\App\Http\Controllers\Public\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-main.xml', [\App\Http\Controllers\Public\SitemapController::class, 'main'])->name('sitemap.main');
Route::get('/sitemap-services.xml', [\App\Http\Controllers\Public\SitemapController::class, 'services'])->name('sitemap.services');
Route::get('/sitemap-cities.xml', [\App\Http\Controllers\Public\SitemapController::class, 'cities'])->name('sitemap.cities');
Route::get('/sitemap-companies.xml', [\App\Http\Controllers\Public\SitemapController::class, 'companies'])->name('sitemap.companies');
Route::get('/sitemap-search.xml', [\App\Http\Controllers\Public\SitemapController::class, 'search'])->name('sitemap.search');
Route::get('/sitemap-pricing.xml', [\App\Http\Controllers\Public\SitemapController::class, 'pricing'])->name('sitemap.pricing');

// API Routes for autocomplete
Route::get('/api/cities', function () {
    return \App\Models\City::with('zone')->orderBy('name')->get();
});

// Pricing routes
Route::get('/priser', [\App\Http\Controllers\Public\PricingController::class, 'index'])->name('public.pricing.index');
Route::get('/priser/{service}', [\App\Http\Controllers\Public\PricingController::class, 'service'])->name('public.pricing.service');
Route::get('/priser/{city}/{service}', [\App\Http\Controllers\Public\PricingController::class, 'cityService'])->name('public.pricing.city-service');

// PDF routes
Route::get('/booking/{booking}/pdf', [\App\Http\Controllers\BookingPdfController::class, 'generate'])->name('booking.pdf.download');
Route::get('/booking/{booking}/pdf/stream', [\App\Http\Controllers\BookingPdfController::class, 'stream'])->name('booking.pdf.stream');

// Complaint routes
Route::get('/booking/{booking}/complaint', [\App\Http\Controllers\ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/booking/{booking}/complaint', [\App\Http\Controllers\ComplaintController::class, 'store'])->name('complaints.store');
Route::get('/complaint/{complaint}/guest', [\App\Http\Controllers\ComplaintController::class, 'guestShow'])->name('complaints.guest.show');
Route::get('/complaint/{complaint}/download/{attachment}', [\App\Http\Controllers\ComplaintController::class, 'downloadAttachment'])->name('complaints.download-attachment');

// API routes for search functionality
Route::get('/api/cities', [\App\Http\Controllers\Api\CityController::class, 'index'])->name('api.cities');
Route::get('/api/services', [\App\Http\Controllers\Api\ServiceController::class, 'index'])->name('api.services');
Route::get('/api/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index'])->name('api.categories');


// Explicit service page route to avoid catch-all conflicts (must be BEFORE /{city}/{service})
Route::get('/service/{service:slug}', function (\App\Models\Service $service) {
    $service->load(['category', 'cities', 'forms']);
    $companies = \App\Models\Company::where('status', 'active')
        ->whereHas('services', function($q) use ($service) {
            $q->where('services.id', $service->id);
        })
        ->with('user')
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->take(6)
        ->get();
    return view('public.services.show', compact('service', 'companies'));
})->name('public.service.show');

// City + Service landing (SEO friendly): /{city}/{service}
Route::get('/{city}/{service}', [\App\Http\Controllers\Public\CityServiceLandingController::class, 'show'])
    ->where([
        'city' => '^[a-z0-9\-]+$',
        'service' => '^[a-z0-9\-]+$'
    ])
    ->name('public.city-service.landing');

// CMS Pages: Serve active pages by page_key at /{slug} if not matched earlier (placed near end)

// Email Marketing
Route::get('/email/unsubscribe/{token}', [\App\Http\Controllers\Admin\EmailMarketingController::class, 'unsubscribe'])->name('email.unsubscribe');
Route::post('/email/unsubscribe/{token}', [\App\Http\Controllers\Admin\EmailMarketingController::class, 'unsubscribe']);

// (moved fallback to the bottom; CMS dynamic route is declared later)

// Info Pages
Route::get('/about', function () {
    return view('public.pages.about');
})->name('about');

Route::get('/how-it-works', function () {
    return view('public.pages.how-it-works');
})->name('how-it-works');

// Contact Page
Route::get('/contact', [\App\Http\Controllers\Public\ContactController::class, 'show'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\Public\ContactController::class, 'send'])->name('contact.send');

Route::get('/why-us', function () {
    return view('public.pages.why-us');
})->name('why-us');

// City page (named route used on homepage): redirect to company listing filtered by city
Route::get('/city/{city:slug}', function (\App\Models\City $city) {
    return redirect()->route('public.companies', ['city' => $city->id]);
})->name('public.city.show');

// Policy Pages
Route::prefix('policies')->name('policy.')->group(function() {
    Route::get('/privacy', function () {
        return view('public.policies.privacy');
    })->name('privacy');
    
    Route::get('/terms', function () {
        return view('public.policies.terms');
    })->name('terms');
    
    Route::get('/cookies', function () {
        return view('public.policies.cookies');
    })->name('cookies');
    
    Route::get('/gdpr', function () {
        return view('public.policies.gdpr');
    })->name('gdpr');
    
    Route::get('/booking-terms', function () {
        return view('public.policies.booking-terms');
    })->name('booking-terms');
});

// (service route declared above)

// CMS Pages: Serve active pages by page_key at /{slug} (placed before fallback and after specific routes)
Route::get('/{slug}', function (string $slug) {
    $page = \App\Models\PageContent::active()->byKey($slug)->first();
    abort_unless($page, 404);
    $seoTitle = $page->meta_title ?: ($page->page_name ?: ucfirst(str_replace('-', ' ', $page->page_key)));
    $seoDescription = $page->meta_description ?: setting('seo_default_description');
    return view('public.pages.dynamic', compact('page', 'seoTitle', 'seoDescription'));
})->where('slug', '^[a-z0-9\-]+$')->name('public.page');

// Fallback route for 404 errors (must be last)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Diagnostic route
Route::get('/debug-form-1', function () {
    $form = App\Models\Form::with('fields')->find(1);
    dd([
        'form_name' => $form->form_name,
        'fields_count' => $form->fields->count(),
        'fields' => $form->fields->map(function($field) {
            return [
                'id' => $field->id,
                'type' => $field->field_type,
                'label' => $field->field_label,
                'name' => $field->field_name,
                'options' => $field->field_options,
                'options_type' => gettype($field->field_options),
                'options_is_array' => is_array($field->field_options),
                'pricing' => $field->pricing_rules,
            ];
        })->toArray()
    ]);
});
