Develop a comprehensive Swedish service booking platform using Laravel with Blade templates and Breeze authentication. The platform connects users with service providers across Sweden, featuring dynamic form-based booking with real-time price calculation, ROT-avdrag (Swedish tax deduction) integration, and advanced form builder with WordPress shortcode export.

Tech Stack
Backend: Laravel 11.x with Breeze authentication
Frontend: Blade templates, TailwindCSS 3.x, Alpine.js 3.x
Database: MySQL 8.0+
Default Language: Swedish (sv_SE)
Real-time Features: Alpine.js for client-side reactivity, Livewire optional for complex interactions
Core Features
Multi-user system (Admin, Company, User)
Zone-based service management with city multipliers
Advanced drag-and-drop form builder with 15+ field types
Real-time price calculation with ROT-avdrag
One-time and subscription booking support
WordPress shortcode generation for external embedding
Booking workflow (User → Admin → Company → Completion → Review)
Public shareable form links
Custom form styling capabilities
Database Structure
1. Users Table (users)
php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->enum('type', ['admin', 'company', 'user'])->default('user');
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->string('password');
    $table->string('photo')->nullable();
    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
    $table->timestamps();
    $table->softDeletes();
});
2. Companies Table (companies)
php
Schema::create('companies', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('company_logo')->nullable();
    $table->string('company_email');
    $table->string('company_number');
    $table->string('company_org_number')->unique();
    $table->string('site')->nullable();
    $table->decimal('review_average', 3, 2)->default(0);
    $table->integer('review_count')->default(0);
    $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
    $table->timestamps();
});
3. Zones Table (zones)
php
Schema::create('zones', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->json('area_map_selection')->nullable(); // GeoJSON polygon
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
});
4. Cities Table (cities)
php
Schema::create('cities', function (Blueprint $table) {
    $table->id();
    $table->foreignId('zone_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->json('area_map_selection')->nullable();
    $table->decimal('city_multiplier', 5, 2)->default(1.00); // Price multiplier
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
});
5. Categories Table (categories)
php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('image')->nullable();
    $table->string('icon')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
6. Services Table (services)
php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('image')->nullable();
    $table->string('icon')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    
    // Pricing
    $table->decimal('base_price', 10, 2)->default(0);
    $table->decimal('discount_percent', 5, 2)->default(0);
    
    // Booking types
    $table->boolean('one_time_booking')->default(true);
    $table->boolean('subscription_booking')->default(false);
    
    // ROT-avdrag
    $table->boolean('rot_eligible')->default(false);
    $table->decimal('rot_percent', 5, 2)->default(30.00);
    
    $table->timestamps();
});
7. Service-City Pivot Table (city_service)
php
Schema::create('city_service', function (Blueprint $table) {
    $table->id();
    $table->foreignId('city_id')->constrained()->onDelete('cascade');
    $table->foreignId('service_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
8. Company-Service Pivot Table (company_service)
php
Schema::create('company_service', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained()->onDelete('cascade');
    $table->foreignId('service_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
9. Company-City Pivot Table (city_company)
php
Schema::create('city_company', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained()->onDelete('cascade');
    $table->foreignId('city_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
10. Forms Table (forms)
php
Schema::create('forms', function (Blueprint $table) {
    $table->id();
    $table->foreignId('service_id')->constrained()->onDelete('cascade');
    $table->string('form_name');
    $table->string('form_slug')->unique();
    $table->json('form_schema'); // Stores form fields configuration
    $table->text('success_message')->default('Tack för din bokning!');
    $table->boolean('redirect_after_submit')->default(false);
    $table->string('redirect_url')->nullable();
    $table->json('custom_styles')->nullable(); // Custom CSS/styling
    $table->string('shortcode')->unique(); // WordPress shortcode
    $table->string('public_token')->unique(); // For public link access
    $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
    $table->timestamps();
});
11. Form Fields Table (form_fields)
php
Schema::create('form_fields', function (Blueprint $table) {
    $table->id();
    $table->foreignId('form_id')->constrained()->onDelete('cascade');
    $table->string('field_type'); // text, email, phone, textarea, file, date, time, url, number, select, radio, checkbox, slider, step, divider, container
    $table->string('field_label');
    $table->string('field_name');
    $table->string('placeholder_text')->nullable();
    $table->text('help_text')->nullable();
    $table->enum('field_width', ['25', '33', '50', '100'])->default('100');
    $table->boolean('required')->default(false);
    $table->integer('sort_order')->default(0);
    
    // Pricing for field values
    $table->json('pricing_rules')->nullable(); // For number fields, select options, etc.
    
    // Conditional logic
    $table->json('conditional_logic')->nullable(); // {show_if: {field: 'id', value: 'x'}}
    
    // Field options (for select, radio, checkbox)
    $table->json('field_options')->nullable();
    
    // Step/Container config
    $table->string('parent_id')->nullable(); // For nested fields in containers
    $table->integer('step_number')->nullable();
    
    $table->timestamps();
});
12. Bookings Table (bookings)
php
Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->string('booking_number')->unique();
    
    // Relations
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('service_id')->constrained()->onDelete('cascade');
    $table->foreignId('form_id')->constrained()->onDelete('cascade');
    $table->foreignId('city_id')->constrained()->onDelete('cascade');
    
    // Booking type
    $table->enum('booking_type', ['one_time', 'subscription'])->default('one_time');
    $table->enum('subscription_frequency', ['daily', 'weekly', 'biweekly', 'monthly'])->nullable();
    
    // Contact info
    $table->string('customer_name');
    $table->string('customer_email');
    $table->string('customer_phone');
    $table->text('customer_message')->nullable();
    
    // Form data
    $table->json('form_data'); // All form field responses
    
    // Pricing breakdown
    $table->decimal('base_price', 10, 2);
    $table->decimal('variable_additions', 10, 2)->default(0);
    $table->decimal('city_multiplier', 5, 2);
    $table->decimal('rot_deduction', 10, 2)->default(0);
    $table->decimal('discount_amount', 10, 2)->default(0);
    $table->decimal('final_price', 10, 2);
    
    // Status workflow
    $table->enum('status', [
        'pending',           // Awaiting admin review
        'assigned',          // Assigned to company
        'in_progress',       // Company working on it
        'completed',         // Service completed
        'cancelled',         // Cancelled by user/admin
        'rejected'           // Rejected by company
    ])->default('pending');
    
    // Dates
    $table->dateTime('preferred_date')->nullable();
    $table->dateTime('assigned_at')->nullable();
    $table->dateTime('completed_at')->nullable();
    
    $table->timestamps();
    $table->softDeletes();
});
13. Reviews Table (reviews)
php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('booking_id')->constrained()->onDelete('cascade');
    $table->foreignId('company_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('service_id')->constrained()->onDelete('cascade');
    $table->integer('rating'); // 1-5
    $table->text('review_text')->nullable();
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamps();
});
14. Slot Times Table (slot_times)
php
Schema::create('slot_times', function (Blueprint $table) {
    $table->id();
    $table->foreignId('city_id')->constrained()->onDelete('cascade');
    $table->foreignId('service_id')->nullable()->constrained()->onDelete('cascade');
    $table->date('date');
    $table->time('start_time');
    $table->time('end_time');
    $table->integer('capacity')->default(1);
    $table->integer('booked_count')->default(0);
    $table->boolean('is_available')->default(true);
    $table->timestamps();
});
File Structure
bitra-tjanster/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── CompanyController.php
│   │   │   │   ├── ZoneController.php
│   │   │   │   ├── CityController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── ServiceController.php
│   │   │   │   ├── FormBuilderController.php
│   │   │   │   ├── BookingController.php
│   │   │   │   └── ReviewController.php
│   │   │   ├── Company/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── BookingController.php
│   │   │   │   └── ProfileController.php
│   │   │   ├── User/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── BookingController.php
│   │   │   │   └── ProfileController.php
│   │   │   ├── PublicFormController.php
│   │   │   └── BookingSubmissionController.php
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php
│   │   │   ├── CompanyMiddleware.php
│   │   │   └── UserMiddleware.php
│   │   └── Requests/
│   │       ├── BookingRequest.php
│   │       └── FormBuilderRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Company.php
│   │   ├── Zone.php
│   │   ├── City.php
│   │   ├── Category.php
│   │   ├── Service.php
│   │   ├── Form.php
│   │   ├── FormField.php
│   │   ├── Booking.php
│   │   ├── Review.php
│   │   └── SlotTime.php
│   ├── Services/
│   │   ├── PriceCalculatorService.php
│   │   ├── FormBuilderService.php
│   │   ├── BookingWorkflowService.php
│   │   ├── ShortcodeGeneratorService.php
│   │   └── NotificationService.php
│   └── Helpers/
│       └── helpers.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── admin.blade.php
│   │   │   ├── company.blade.php
│   │   │   ├── user.blade.php
│   │   │   └── public.blade.php
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── users/
│   │   │   ├── companies/
│   │   │   ├── zones/
│   │   │   ├── cities/
│   │   │   ├── categories/
│   │   │   ├── services/
│   │   │   ├── forms/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── builder.blade.php
│   │   │   ├── bookings/
│   │   │   └── reviews/
│   │   ├── company/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── bookings/
│   │   │   └── profile/
│   │   ├── user/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── bookings/
│   │   │   └── profile/
│   │   ├── public/
│   │   │   ├── form.blade.php
│   │   │   └── success.blade.php
│   │   └── components/
│   │       ├── form-builder/
│   │       │   ├── field-text.blade.php
│   │       │   ├── field-select.blade.php
│   │       │   ├── field-number.blade.php
│   │       │   ├── field-date.blade.php
│   │       │   └── ...
│   │       ├── price-calculator.blade.php
│   │       └── booking-status.blade.php
│   ├── js/
│   │   ├── app.js
│   │   ├── alpine/
│   │   │   ├── form-builder.js
│   │   │   ├── price-calculator.js
│   │   │   └── drag-drop.js
│   │   └── components/
│   ├── css/
│   │   └── app.css
│   └── lang/
│       └── sv/
│           ├── auth.php
│           ├── validation.php
│           └── messages.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── ZoneCitySeeder.php
│   │   ├── CategorySeeder.php
│   │   └── ServiceSeeder.php
│   └── factories/
├── routes/
│   ├── web.php
│   ├── admin.php
│   ├── company.php
│   └── user.php
├── public/
│   ├── uploads/
│   │   ├── companies/
│   │   ├── services/
│   │   └── bookings/
│   └── wordpress-shortcode.js
├── config/
│   └── bitra.php
└── tests/
Key Functionalities & Implementation
1. Price Calculator Service (app/Services/PriceCalculatorService.php)
php
<?php

namespace App\Services;

use App\Models\Service;
use App\Models\City;

class PriceCalculatorService
{
    /**
     * Calculate total price based on formula:
     * Total = ((Base + Variables) × City_Multiplier) - ((Base + Variables) × ROT%) - Discount
     */
    public function calculate(array $data): array
    {
        $service = Service::findOrFail($data['service_id']);
        $city = City::findOrFail($data['city_id']);
        
        $basePrice = $service->base_price;
        $variableAdditions = $this->calculateVariableAdditions($data['form_data'] ?? []);
        $cityMultiplier = $city->city_multiplier;
        
        // Subtotal before deductions
        $subtotal = ($basePrice + $variableAdditions) * $cityMultiplier;
        
        // ROT deduction (if eligible)
        $rotDeduction = 0;
        if ($service->rot_eligible && ($data['apply_rot'] ?? false)) {
            $rotDeduction = ($basePrice + $variableAdditions) * ($service->rot_percent / 100);
        }
        
        // Discount
        $discountAmount = 0;
        if ($service->discount_percent > 0) {
            $discountAmount = ($basePrice + $variableAdditions) * ($service->discount_percent / 100);
        }
        
        $finalPrice = $subtotal - $rotDeduction - $discountAmount;
        
        return [
            'base_price' => $basePrice,
            'variable_additions' => $variableAdditions,
            'city_multiplier' => $cityMultiplier,
            'subtotal' => $subtotal,
            'rot_deduction' => $rotDeduction,
            'discount_amount' => $discountAmount,
            'final_price' => max(0, $finalPrice), // Never negative
            'breakdown' => $this->getBreakdown($data['form_data'] ?? [])
        ];
    }
    
    private function calculateVariableAdditions(array $formData): float
    {
        $total = 0;
        
        foreach ($formData as $fieldName => $value) {
            $field = FormField::where('field_name', $fieldName)->first();
            
            if (!$field || !$field->pricing_rules) continue;
            
            $pricingRules = json_decode($field->pricing_rules, true);
            
            switch ($field->field_type) {
                case 'number':
                    // Example: price per unit
                    $total += $value * ($pricingRules['price_per_unit'] ?? 0);
                    break;
                    
                case 'select':
                case 'radio':
                    // Example: {options: [{value: 'small', price: 100}, ...]}
                    $option = collect($pricingRules['options'] ?? [])
                        ->firstWhere('value', $value);
                    $total += $option['price'] ?? 0;
                    break;
                    
                case 'checkbox':
                    // Multiple selections
                    $values = is_array($value) ? $value : [$value];
                    foreach ($values as $val) {
                        $option = collect($pricingRules['options'] ?? [])
                            ->firstWhere('value', $val);
                        $total += $option['price'] ?? 0;
                    }
                    break;
                    
                case 'slider':
                    $total += $value * ($pricingRules['price_per_unit'] ?? 0);
                    break;
            }
        }
        
        return $total;
    }
    
    private function getBreakdown(array $formData): array
    {
        $breakdown = [];
        
        foreach ($formData as $fieldName => $value) {
            $field = FormField::where('field_name', $fieldName)->first();
            
            if (!$field || !$field->pricing_rules) continue;
            
            $pricingRules = json_decode($field->pricing_rules, true);
            
            // Add to breakdown for display
            $breakdown[] = [
                'field_label' => $field->field_label,
                'value' => $value,
                'price' => $this->calculateFieldPrice($field, $value, $pricingRules)
            ];
        }
        
        return $breakdown;
    }
    
    private function calculateFieldPrice($field, $value, $pricingRules): float
    {
        // Similar logic to calculateVariableAdditions but for single field
        // Implementation details...
        return 0;
    }
}
2. Form Builder Alpine.js Component (resources/js/alpine/form-builder.js)
javascript
export default () => ({
    fields: [],
    selectedField: null,
    draggedField: null,
    
    init() {
        // Load existing form schema if editing
        if (window.formSchema) {
            this.fields = JSON.parse(window.formSchema);
        }
        
        // Initialize Sortable.js for drag-drop
        this.initDragDrop();
    },
    
    addField(type) {
        const field = {
            id: 'field_' + Date.now(),
            type: type,
            label: this.getDefaultLabel(type),
            name: 'field_' + this.fields.length,
            placeholder: '',
            helpText: '',
            width: '100',
            required: false,
            options: type === 'select' || type === 'radio' || type === 'checkbox' ? [] : null,
            pricingRules: null,
            conditionalLogic: null
        };
        
        this.fields.push(field);
        this.selectedField = field;
    },
    
    removeField(field) {
        this.fields = this.fields.filter(f => f.id !== field.id);
        if (this.selectedField?.id === field.id) {
            this.selectedField = null;
        }
    },
    
    duplicateField(field) {
        const clone = JSON.parse(JSON.stringify(field));
        clone.id = 'field_' + Date.now();
        clone.name = clone.name + '_copy';
        this.fields.push(clone);
    },
    
    getDefaultLabel(type) {
        const labels = {
            text: 'Textfält',
            email: 'E-post',
            phone: 'Telefon',
            textarea: 'Textområde',
            number: 'Nummer',
            select: 'Rullgardinsmeny',
            radio: 'Radioknappar',
            checkbox: 'Kryssrutor',
            date: 'Datum',
            time: 'Tid',
            file: 'Fil',
            slider: 'Skjutreglage',
            divider: 'Avdelare',
            container: 'Container'
        };
        return labels[type] || type;
    },
    
    saveForm() {
        // Submit form schema
        document.getElementById('form_schema_input').value = JSON.stringify(this.fields);
        document.getElementById('form_builder_form').submit();
    }
});
3. Real-time Price Calculator (resources/js/alpine/price-calculator.js)
javascript
export default (serviceId, cityId) => ({
    formData: {},
    priceBreakdown: {
        base_price: 0,
        variable_additions: 0,
        city_multiplier: 1,
        subtotal: 0,
        rot_deduction: 0,
        discount_amount: 0,
        final_price: 0,
        breakdown: []
    },
    loading: false,
    applyRot: false,
    
    async calculatePrice() {
        this.loading = true;
        
        try {
            const response = await fetch('/api/calculate-price', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    service_id: serviceId,
                    city_id: cityId,
                    form_data: this.formData,
                    apply_rot: this.applyRot
                })
            });
            
            const data = await response.json();
            this.priceBreakdown = data;
        } catch (error) {
            console.error('Price calculation error:', error);
        } finally {
            this.loading = false;
        }
    },
    
    updateField(fieldName, value) {
        this.formData[fieldName] = value;
        this.calculatePrice();
    },
    
    toggleRot() {
        this.applyRot = !this.applyRot;
        this.calculatePrice();
    }
});
4. WordPress Shortcode Generator
php
// app/Services/ShortcodeGeneratorService.php
class ShortcodeGeneratorService
{
    public function generate(Form $form): string
    {
        return "[bitra_form id=\"{$form->shortcode}\"]";
    }
    
    public function generatePublicUrl(Form $form): string
    {
        return route('public.form', ['token' => $form->public_token]);
    }
    
    public function generateEmbedCode(Form $form): string
    {
        $url = $this->generatePublicUrl($form);
        
        return <<<HTML
<div id="bitra-form-{$form->id}"></div>
<script src="{$this->getScriptUrl()}" data-form-token="{$form->public_token}"></script>
HTML;
    }
    
    private function getScriptUrl(): string
    {
        return asset('wordpress-shortcode.js');
    }
}
javascript
// public/wordpress-shortcode.js
(function() {
    'use strict';
    
    const script = document.currentScript;
    const formToken = script.getAttribute('data-form-token');
    const container = document.getElementById('bitra-form-' + formToken);
    
    if (!container || !formToken) return;
    
    // Fetch and inject form HTML
    fetch(`/api/public/form/${formToken}/html`)
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html;
            initializeForm(container);
        });
    
    function initializeForm(container) {
        // Initialize Alpine.js components
        // Add event listeners
        // Setup price calculator
    }
})();
Routes Structure
php
// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/form/{token}', [PublicFormController::class, 'show'])->name('public.form');
Route::post('/form/{token}', [BookingSubmissionController::class, 'store'])->name('booking.submit');

// API routes for AJAX
Route::post('/api/calculate-price', [BookingSubmissionController::class, 'calculatePrice']);
Route::get('/api/public/form/{token}/html', [PublicFormController::class, 'html']);

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('users', Admin\UserController::class);
    Route::resource('companies', Admin\CompanyController::class);
    Route::resource('zones', Admin\ZoneController::class);
    Route::resource('cities', Admin\CityController::class);
    Route::resource('categories', Admin\CategoryController::class);
    Route::resource('services', Admin\ServiceController::class);
    
    // Form builder
    Route::resource('forms', Admin\FormBuilderController::class);
    Route::get('forms/{form}/preview', [Admin\FormBuilderController::class, 'preview'])->name('forms.preview');
    Route::get('forms/{form}/shortcode', [Admin\FormBuilderController::class, 'shortcode'])->name('forms.shortcode');
    
    // Bookings
    Route::resource('bookings', Admin\BookingController::class);
    Route::post('bookings/{booking}/assign', [Admin\BookingController::class, 'assign'])->name('bookings.assign');
    
    Route::resource('reviews', Admin\ReviewController::class);
});

// Company routes
Route::middleware(['auth', 'company'])->prefix('company')->name('company.')->group(function() {
    Route::get('/dashboard', [Company\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('bookings', Company\BookingController::class)->only(['index', 'show', 'update']);
    Route::post('bookings/{booking}/accept', [Company\BookingController::class, 'accept'])->name('bookings.accept');
    Route::post('bookings/{booking}/reject', [Company\BookingController::class, 'reject'])->name('bookings.reject');
    Route::post('bookings/{booking}/complete', [Company\BookingController::class, 'complete'])->name('bookings.complete');
});

// User routes
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function() {
    Route::get('/dashboard', [User\DashboardController::

Continue

php
// User routes (continued)
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function() {
    Route::get('/dashboard', [User\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('bookings', User\BookingController::class)->only(['index', 'show']);
    Route::post('bookings/{booking}/cancel', [User\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/review', [User\BookingController::class, 'review'])->name('bookings.review');
});
Key Models with Relationships
User Model
php
// app/Models/User.php
class User extends Authenticatable
{
    protected $fillable = ['type', 'email', 'phone', 'password', 'photo', 'status'];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function company()
    {
        return $this->hasOne(Company::class);
    }
    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }
    
    public function isCompany(): bool
    {
        return $this->type === 'company';
    }
    
    public function isUser(): bool
    {
        return $this->type === 'user';
    }
}
Service Model
php
// app/Models/Service.php
class Service extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'image', 'icon', 
        'status', 'base_price', 'discount_percent', 'one_time_booking', 
        'subscription_booking', 'rot_eligible', 'rot_percent'
    ];
    
    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'rot_percent' => 'decimal:2',
        'one_time_booking' => 'boolean',
        'subscription_booking' => 'boolean',
        'rot_eligible' => 'boolean',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function cities()
    {
        return $this->belongsToMany(City::class, 'city_service');
    }
    
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_service');
    }
    
    public function forms()
    {
        return $this->hasMany(Form::class);
    }
    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    public function getActiveFormAttribute()
    {
        return $this->forms()->where('status', 'active')->first();
    }
}
Form Model
php
// app/Models/Form.php
class Form extends Model
{
    protected $fillable = [
        'service_id', 'form_name', 'form_slug', 'form_schema', 
        'success_message', 'redirect_after_submit', 'redirect_url', 
        'custom_styles', 'shortcode', 'public_token', 'status'
    ];
    
    protected $casts = [
        'form_schema' => 'array',
        'custom_styles' => 'array',
        'redirect_after_submit' => 'boolean',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($form) {
            if (!$form->shortcode) {
                $form->shortcode = 'bitra_' . Str::random(12);
            }
            if (!$form->public_token) {
                $form->public_token = Str::random(32);
            }
        });
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }
    
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    public function getPublicUrlAttribute()
    {
        return route('public.form', ['token' => $this->public_token]);
    }
    
    public function getShortcodeTextAttribute()
    {
        return "[bitra_form id=\"{$this->shortcode}\"]";
    }
}
Booking Model
php
// app/Models/Booking.php
class Booking extends Model
{
    protected $fillable = [
        'booking_number', 'user_id', 'company_id', 'service_id', 'form_id', 
        'city_id', 'booking_type', 'subscription_frequency', 'customer_name', 
        'customer_email', 'customer_phone', 'customer_message', 'form_data', 
        'base_price', 'variable_additions', 'city_multiplier', 'rot_deduction', 
        'discount_amount', 'final_price', 'status', 'preferred_date', 
        'assigned_at', 'completed_at'
    ];
    
    protected $casts = [
        'form_data' => 'array',
        'base_price' => 'decimal:2',
        'variable_additions' => 'decimal:2',
        'city_multiplier' => 'decimal:2',
        'rot_deduction' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'preferred_date' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = 'BK' . date('Ymd') . strtoupper(Str::random(6));
            }
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    
    public function review()
    {
        return $this->hasOne(Review::class);
    }
    
    public function canBeReviewed(): bool
    {
        return $this->status === 'completed' && !$this->review;
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
Booking Workflow Service
php
// app/Services/BookingWorkflowService.php
<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Company;
use App\Notifications\BookingCreatedNotification;
use App\Notifications\BookingAssignedNotification;
use App\Notifications\BookingCompletedNotification;
use Illuminate\Support\Facades\DB;

class BookingWorkflowService
{
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // Calculate pricing
            $priceCalculator = new PriceCalculatorService();
            $pricing = $priceCalculator->calculate($data);
            
            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'service_id' => $data['service_id'],
                'form_id' => $data['form_id'],
                'city_id' => $data['city_id'],
                'booking_type' => $data['booking_type'],
                'subscription_frequency' => $data['subscription_frequency'] ?? null,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'customer_message' => $data['customer_message'] ?? null,
                'form_data' => $data['form_data'],
                'preferred_date' => $data['preferred_date'] ?? null,
                'base_price' => $pricing['base_price'],
                'variable_additions' => $pricing['variable_additions'],
                'city_multiplier' => $pricing['city_multiplier'],
                'rot_deduction' => $pricing['rot_deduction'],
                'discount_amount' => $pricing['discount_amount'],
                'final_price' => $pricing['final_price'],
                'status' => 'pending',
            ]);
            
            // Notify admin
            $this->notifyAdmin($booking);
            
            return $booking;
        });
    }
    
    public function assignToCompany(Booking $booking, Company $company): bool
    {
        return DB::transaction(function () use ($booking, $company) {
            $booking->update([
                'company_id' => $company->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);
            
            // Notify company
            $company->user->notify(new BookingAssignedNotification($booking));
            
            // Notify user
            if ($booking->user) {
                $booking->user->notify(new BookingAssignedNotification($booking));
            }
            
            return true;
        });
    }
    
    public function acceptBooking(Booking $booking): bool
    {
        $booking->update([
            'status' => 'in_progress',
        ]);
        
        return true;
    }
    
    public function rejectBooking(Booking $booking, string $reason): bool
    {
        $booking->update([
            'status' => 'rejected',
            'company_id' => null,
            'assigned_at' => null,
        ]);
        
        // Notify admin to reassign
        $this->notifyAdmin($booking, 'rejected', $reason);
        
        return true;
    }
    
    public function completeBooking(Booking $booking): bool
    {
        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        
        // Notify user to leave review
        if ($booking->user) {
            $booking->user->notify(new BookingCompletedNotification($booking));
        }
        
        return true;
    }
    
    public function cancelBooking(Booking $booking): bool
    {
        $booking->update([
            'status' => 'cancelled',
        ]);
        
        // Notify company if assigned
        if ($booking->company) {
            $booking->company->user->notify(new BookingCancelledNotification($booking));
        }
        
        return true;
    }
    
    private function notifyAdmin(Booking $booking, string $type = 'created', string $message = null)
    {
        $admins = User::where('type', 'admin')->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new BookingCreatedNotification($booking, $type, $message));
        }
    }
    
    public function findAvailableCompanies(Booking $booking)
    {
        return Company::where('status', 'active')
            ->whereHas('services', function ($query) use ($booking) {
                $query->where('services.id', $booking->service_id);
            })
            ->whereHas('cities', function ($query) use ($booking) {
                $query->where('cities.id', $booking->city_id);
            })
            ->with(['user', 'reviews'])
            ->get();
    }
}
View Examples
Form Builder View (resources/views/admin/forms/builder.blade.php)
blade
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="formBuilder()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Formulärbyggare</h1>
        <button @click="saveForm()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Spara formulär
        </button>
    </div>
    
    <div class="grid grid-cols-12 gap-6">
        <!-- Field Palette -->
        <div class="col-span-3 bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold mb-4">Fälttyper</h3>
            
            <div class="space-y-2">
                <button @click="addField('text')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    📝 Textfält
                </button>
                <button @click="addField('email')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    ✉️ E-post
                </button>
                <button @click="addField('phone')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    📞 Telefon
                </button>
                <button @click="addField('textarea')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    📄 Textområde
                </button>
                <button @click="addField('number')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    🔢 Nummer
                </button>
                <button @click="addField('select')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    📋 Rullgardinsmeny
                </button>
                <button @click="addField('radio')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    🔘 Radioknappar
                </button>
                <button @click="addField('checkbox')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    ☑️ Kryssrutor
                </button>
                <button @click="addField('date')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    📅 Datum
                </button>
                <button @click="addField('time')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    🕐 Tid
                </button>
                <button @click="addField('file')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    📎 Fil
                </button>
                <button @click="addField('slider')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    🎚️ Skjutreglage
                </button>
                <button @click="addField('divider')" class="w-full text-left px-4 py-2 border rounded hover:bg-gray-50">
                    ➖ Avdelare
                </button>
            </div>
        </div>
        
        <!-- Form Canvas -->
        <div class="col-span-6 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Formulärförhandsvisning</h3>
            
            <div id="form-canvas" class="space-y-4 min-h-[400px]">
                <template x-for="(field, index) in fields" :key="field.id">
                    <div 
                        class="border rounded-lg p-4 hover:border-blue-500 cursor-pointer"
                        :class="{'border-blue-500 bg-blue-50': selectedField?.id === field.id}"
                        @click="selectedField = field"
                    >
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1" x-text="field.label"></label>
                                
                                <!-- Field Preview -->
                                <template x-if="field.type === 'text' || field.type === 'email' || field.type === 'phone' || field.type === 'url'">
                                    <input 
                                        type="text" 
                                        :placeholder="field.placeholder"
                                        class="w-full px-3 py-2 border rounded"
                                        disabled
                                    >
                                </template>
                                
                                <template x-if="field.type === 'textarea'">
                                    <textarea 
                                        :placeholder="field.placeholder"
                                        class="w-full px-3 py-2 border rounded"
                                        rows="3"
                                        disabled
                                    ></textarea>
                                </template>
                                
                                <template x-if="field.type === 'number' || field.type === 'slider'">
                                    <input 
                                        type="number" 
                                        :placeholder="field.placeholder"
                                        class="w-full px-3 py-2 border rounded"
                                        disabled
                                    >
                                </template>
                                
                                <template x-if="field.type === 'select'">
                                    <select class="w-full px-3 py-2 border rounded" disabled>
                                        <option>Välj ett alternativ</option>
                                    </select>
                                </template>
                                
                                <template x-if="field.helpText">
                                    <p class="text-sm text-gray-500 mt-1" x-text="field.helpText"></p>
                                </template>
                            </div>
                            
                            <div class="flex space-x-2 ml-4">
                                <button @click.stop="duplicateField(field)" class="text-gray-400 hover:text-gray-600">
                                    📋
                                </button>
                                <button @click.stop="removeField(field)" class="text-red-400 hover:text-red-600">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                
                <div x-show="fields.length === 0" class="text-center text-gray-400 py-12">
                    Dra och släpp fält här eller klicka på fälttyper till vänster
                </div>
            </div>
        </div>
        
        <!-- Field Settings -->
        <div class="col-span-3 bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold mb-4">Fältinställningar</h3>
            
            <div x-show="selectedField" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etikett</label>
                    <input 
                        type="text" 
                        x-model="selectedField.label"
                        class="w-full px-3 py-2 border rounded"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fältnamn</label>
                    <input 
                        type="text" 
                        x-model="selectedField.name"
                        class="w-full px-3 py-2 border rounded"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Platshållare</label>
                    <input 
                        type="text" 
                        x-model="selectedField.placeholder"
                        class="w-full px-3 py-2 border rounded"
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hjälptext</label>
                    <textarea 
                        x-model="selectedField.helpText"
                        class="w-full px-3 py-2 border rounded"
                        rows="2"
                    ></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fältbredd</label>
                    <select x-model="selectedField.width" class="w-full px-3 py-2 border rounded">
                        <option value="100">100%</option>
                        <option value="50">50%</option>
                        <option value="33">33%</option>
                        <option value="25">25%</option>
                    </select>
                </div>
                
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        x-model="selectedField.required"
                        class="mr-2"
                    >
                    <label class="text-sm font-medium text-gray-700">Obligatoriskt fält</label>
                </div>
                
                <!-- Pricing Rules -->
                <div x-show="['number', 'select', 'radio', 'checkbox', 'slider'].includes(selectedField?.type)">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prisregler</label>
                    <button class="text-sm text-blue-600 hover:underline">+ Lägg till prisregel</button>
                </div>
            </div>
            
            <div x-show="!selectedField" class="text-center text-gray-400 py-12">
                Välj ett fält för att redigera
            </div>
        </div>
    </div>
    
    <!-- Hidden form for submission -->
    <form id="form_builder_form" method="POST" action="{{ route('admin.forms.store') }}">
        @csrf
        <input type="hidden" name="service_id" value="{{ $service->id }}">
        <input type="hidden" name="form_name" value="{{ $form->form_name ?? '' }}">
        <input type="hidden" name="form_schema" id="form_schema_input">
    </form>
</div>

@push('scripts')
<script>
    window.formSchema = @json($form->form_schema ?? []);
</script>
<script src="{{ asset('js/alpine/form-builder.js') }}"></script>
@endpush
@endsection
Public Form View with Price Calculator (resources/views/public/form.blade.php)
blade
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $form->form_name }} - Bitra Tjänster</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $form->form_name }}</h1>
                <p class="text-gray-600 mb-8">{{ $form->service->name }}</p>
                
                <form 
                    method="POST" 
                    action="{{ route('booking.submit', $form->public_token) }}"
                    x-data="priceCalculator({{ $form->service_id }}, null)"
                    @submit="loading = true"
                >
                    @csrf
                    
                    <input type="hidden" name="service_id" value="{{ $form->service_id }}">
                    <input type="hidden" name="form_id" value="{{ $form->id }}">
                    
                    <!-- Dynamic Form Fields -->
                    @foreach($form->fields as $field)
                        <div class="mb-6" style="width: {{ $field->field_width }}%">
                            @include('components.form-builder.field-' . $field->field_type, ['field' => $field])
                        </div>
                    @endforeach
                    
                    <!-- City Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stad *</label>
                        <select 
                            name="city_id" 
                            required
                            @change="cityId = $event.target.value; calculatePrice()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Välj stad</option>
                            @foreach($form->service->cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Booking Type -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Välj städfrekvens *</label>
                        <div class="space-y-2">
                            @if($form->service->one_time_booking)
                                <label class="flex items-center">
                                    <input type="radio" name="booking_type" value="one_time" checked class="mr-2">
                                    <span>En gång</span>
                                </label>
                            @endif
                            
                            @if($form->service->subscription_booking)
                                <label class="flex items-center">
                                    <input type="radio" name="booking_type" value="subscription" class="mr-2">
                                    <span>Prenumeration</span>
                                </label>
                                
                                <div x-show="$el.closest('form').querySelector('[name=booking_type]:checked')?.value === 'subscription'" class="ml-6 space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="subscription_frequency" value="daily" class="mr-2">
                                        <span>Varje dag</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="subscription_frequency" value="weekly" class="mr-2">
                                        <span>Varje vecka</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="subscription_frequency" value="biweekly" class="mr-2">
                                        <span>Varannan vecka</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- ROT-avdrag -->
                    @if($form->service->rot_eligible)
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="apply_rot" 
                                    value="1"
                                    @change="toggleRot()"
                                    class="mr-2"
                                >
                                <span class="text-sm font-medium text-gray-700">Använd ROT-avdrag ({{ $form->service->rot_percent }}%)</span>
                            </label>
                        </div>
                    @endif
                    
                    <!-- Price Breakdown -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4">Prisberäkning</h3>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Grundpris:</span>
                                <span x-text="priceBreakdown.base_price.toFixed(2) + ' kr'"></span>
                            </div>
                            
                            <div x-show="priceBreakdown.variable_additions > 0" class="flex justify-between">
                                <span>Tillägg:</span>
                                <span x-text="priceBreakdown.variable_additions.toFixed(2) + ' kr'"></span>
                            </div>
                            
                            <div x-show="priceBreakdown.city_multiplier !== 1" class="flex justify-between">
                                <span>Stadsmultiplikator (×<span x-text="priceBreakdown.city_multiplier"></span>):</span>
                                <span x-text="priceBreakdown.subtotal.toFixed(2) + ' kr'"></span>
                            </div>
                            
                            <div x-show="priceBreakdown.rot_deduction > 0" class="flex justify-between text-green-600">
                                <span>ROT-avdrag:</span>
                                <span x-text="'-' + priceBreakdown.rot_deduction.toFixed(2) + ' kr'"></span>
                            </div>
                            
                            <div x-show="priceBreakdown.discount_amount > 0" class="flex justify-between text-green-600">
                                <span>Rabbatt:</span>
                                <span x-text="'-' + priceBreakdown.discount_amount.toFixed(2) + ' kr'"></span>
                            </div>
                            
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between font-bold text-lg">
                                    <span>Totalt pris:</span>
                                    <span x-text="priceBreakdown.final_price.toFixed(2) + ' kr'"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div x-show="loading" class="text-center mt-4">
                            <span class="

Continue

blade
                            <span class="text-gray-500">Beräknar pris...</span>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="border-t pt-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4">Kontaktinformation</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Namn *</label>
                                <input 
                                    type="text" 
                                    name="customer_name" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telefon *</label>
                                <input 
                                    type="tel" 
                                    name="customer_phone" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">E-post *</label>
                                <input 
                                    type="email" 
                                    name="customer_email" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meddelande (valfritt)</label>
                                <textarea 
                                    name="customer_message" 
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms and Conditions -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" name="accept_terms" required class="mr-2 mt-1">
                            <span class="text-sm text-gray-700">
                                Jag accepterar <a href="#" class="text-blue-600 hover:underline">användarvillk



