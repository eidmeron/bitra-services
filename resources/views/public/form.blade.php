@extends('layouts.public')

@section('title', $form->form_name)

@push('head')
<!-- Select2 for searchable dropdowns -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- International Telephone Input -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<!-- Modern intl-tel-input styling -->
<style>
    /* Container */
    .iti {
        width: 100%;
        display: block;
        position: relative;
    }
    
    /* Modern phone input wrapper */
    .iti input[type="tel"] {
        width: 100%;
        height: 48px;
        padding: 12px 16px 12px 120px !important;
        border: 2px solid #d1d5db;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
    }
    
    .iti input[type="tel"]:focus {
        border-color: #3b82f6;
        ring: 2px;
        ring-color: rgba(59, 130, 246, 0.3);
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .iti input[type="tel"]:hover {
        border-color: #9ca3af;
    }
    
    /* Flag container - integrated design */
    .iti__flag-container {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 0;
        z-index: 1;
    }
    
    /* Selected flag button - modern design */
    .iti__selected-flag {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 100%;
        padding: 0 12px 0 16px;
        min-width: 105px;
        cursor: pointer;
        border-radius: 12px 0 0 12px;
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        border-right: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .iti__selected-flag:hover {
        background: linear-gradient(135deg, #f3f4f6 0%, #f9fafb 100%);
    }
    
    .iti--show-flags .iti__selected-flag:focus {
        background: linear-gradient(135deg, #eff6ff 0%, #f3f4f6 100%);
    }
    
    /* Flag with modern styling */
    .iti__flag {
        margin-right: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 3px;
    }
    
    /* Dial code styling */
    .iti__selected-dial-code {
        margin-left: 4px;
        font-weight: 600;
        font-size: 15px;
        color: #374151;
        font-family: 'SF Mono', Monaco, 'Cascadia Code', monospace;
    }
    
    /* Arrow indicator */
    .iti__arrow {
        margin-left: 8px;
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #6b7280;
        transition: transform 0.3s ease;
    }
    
    .iti--container-open .iti__arrow {
        transform: rotate(180deg);
    }
    
    /* Modern dropdown */
    .iti__country-list {
        max-height: 350px;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        border: 1px solid #e5e7eb;
        margin-top: 8px;
        background: white;
        z-index: 1000;
        overflow: hidden;
    }
    
    /* Search input in dropdown */
    .iti__search-input {
        width: calc(100% - 20px);
        margin: 10px;
        padding: 8px 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .iti__search-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    /* Country items */
    .iti__country {
        padding: 10px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: background 0.2s ease;
    }
    
    .iti__country:hover,
    .iti__country.iti__highlight {
        background: linear-gradient(90deg, #eff6ff 0%, #f0f9ff 100%);
    }
    
    .iti__country.iti__active {
        background: linear-gradient(90deg, #dbeafe 0%, #e0f2fe 100%);
        font-weight: 600;
    }
    
    .iti__flag-box {
        margin-right: 12px;
    }
    
    .iti__country-name {
        flex: 1;
        color: #374151;
        font-size: 14px;
    }
    
    .iti__dial-code {
        color: #6b7280;
        font-weight: 500;
        font-size: 13px;
        font-family: 'SF Mono', Monaco, monospace;
    }
    
    /* Divider for preferred countries */
    .iti__divider {
        border-bottom: 2px solid #e5e7eb;
        margin: 8px 0;
    }
    
    /* Animation for opening */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .iti__country-list {
        animation: slideDown 0.3s ease;
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .iti input[type="tel"] {
            padding-left: 110px !important;
        }
        
        .iti__selected-flag {
            min-width: 95px;
        }
    }
</style>
<!-- Custom animations -->
<style>
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-slide-in-right {
        animation: slideInRight 0.5s ease-out;
    }
    
    .animate-slide-in-left {
        animation: slideInLeft 0.5s ease-out;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    .step-indicator {
        transition: all 0.3s ease;
    }
    
    .step-indicator.active {
        transform: scale(1.1);
    }
    
    .step-indicator.completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .price-impact {
        animation: pulse 0.5s ease-in-out;
    }
    
    .select2-container--default .select2-selection--single {
        height: 42px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
        padding-left: 12px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
    
    .iti {
        width: 100%;
    }
</style>
@endpush

@section('content')
<!-- Toast Notification Component -->
<x-toast />

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-12">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $form->form_name }}</h1>
            <p class="text-xl text-gray-600">{{ $form->service->name }}</p>
            <p class="text-sm text-gray-500 mt-2">Fyll i formuläret steg för steg för att få din offert</p>
        </div>

        <!-- Error Messages from Laravel Validation -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg animate-fade-in">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-red-800">
                            Det uppstod {{ $errors->count() }} {{ $errors->count() === 1 ? 'fel' : 'fel' }} vid bokningen:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg animate-fade-in">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pass booking settings to JavaScript -->
        <script>
            // Booking settings for company selection
            window.bookingSettings = {
                show_companies: {{ $bookingSettings['show_companies'] ? 'true' : 'false' }},
                allow_company_selection: {{ $bookingSettings['allow_company_selection'] ? 'true' : 'false' }},
                auto_assign: {{ $bookingSettings['auto_assign'] ? 'true' : 'false' }}
            };
        </script>

        <!-- Define Alpine component BEFORE using it -->
        <script>
        document.addEventListener('alpine:init', () => {
            window.multiStepForm = (serviceId, formId) => ({
                // Steps
                currentStep: 0,
                steps: [
                    { title: 'Stad', icon: '📍' },
                    { title: 'Kundtyp', icon: '👤' },
                    { title: 'Typ', icon: '📅' },
                    { title: 'Detaljer', icon: '📝' },
                    { title: 'Kontakt', icon: '👤' }
                ],
                
                // Toast helper
                showToast(message, type = 'error') {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message, type }
                    }));
                },
                
                // Form data
                serviceId: serviceId,
                formId: formId,
                cityId: null,
                customerType: null,
                orgNumber: '',
                personnummer: '',
                bookingType: '{{ $defaultBookingType }}',
                subscriptionFrequency: '{{ $defaultSubscriptionFreq }}',
                formData: {},
                applyRot: false,
                
                // Loyalty Points
                userLoyaltyPoints: {{ auth()->check() ? (auth()->user()->loyalty_points_balance ?? 0) : 0 }},
                useLoyaltyPoints: false,
                loyaltyPointsToUse: 0,
                
                // Slot time data
                availableSlots: {},
                selectedDate: null,
                selectedTimeSlot: null,
                loadingSlots: false,
                
                // Swedish Calendar data
                currentDate: new Date(),
                currentMonth: new Date().getMonth(),
                currentYear: new Date().getFullYear(),
                swedishWeekdays: ['Mån', 'Tis', 'Ons', 'Tor', 'Fre', 'Lör', 'Sön'],
                swedishMonths: [
                    'Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni',
                    'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December'
                ],
                calendarDays: [],
                availableTimesForSelectedDate: [],
                selectedSubscriptionDays: [],
                
                // Computed properties
                get currentMonthName() {
                    return this.swedishMonths[this.currentMonth];
                },
                
                get selectedDateFormatted() {
                    if (!this.selectedDate) return '';
                    const date = new Date(this.selectedDate);
                    return date.toLocaleDateString('sv-SE', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    });
                },
                
                get selectedDateInfo() {
                    if (!this.selectedDate) return '';
                    const slots = this.availableSlots[this.selectedDate];
                    if (slots && slots.slots) {
                        return `${slots.slots.length} tillgängliga tider`;
                    }
                    return 'Inga tider tillgängliga';
                },
                
                get isSubscriptionBooking() {
                    return this.bookingType === 'subscription';
                },
                
                // Price
                priceBreakdown: {
                    base_price: 0,
                    variable_additions: 0,
                    subscription_multiplier: 1,
                    city_multiplier: 1,
                    discount_amount: 0,
                    rot_deduction: 0,
                    final_price: 0
                },
                showPriceDetails: false,
                isSubmitting: false,
                
                
                // Conditional logic
                conditionalFields: @json($form->fields->mapWithKeys(function($field) {
                    return [$field->field_name . '_' . $field->id => $field->conditional_logic];
                })->toArray()),
                
                init() {
                    // Restore old input if validation failed
                    @if(old('city_id'))
                        this.cityId = '{{ old('city_id') }}';
                    @endif
                    
                    @if(old('booking_type'))
                        this.bookingType = '{{ old('booking_type') }}';
                    @endif
                    
                    @if(old('subscription_frequency'))
                        this.subscriptionFrequency = '{{ old('subscription_frequency') }}';
                    @endif
                    
                    @if(old('apply_rot'))
                        this.applyRot = {{ old('apply_rot') === 'true' || old('apply_rot') === '1' ? 'true' : 'false' }};
                    @endif
                    
                    // If there are validation errors or session error, stay on the last step
                    @if($errors->any() || session('error'))
                        this.currentStep = 3; // Contact step where the submit happens
                        this.showToast('Vänligen kontrollera formuläret och försök igen', 'error');
                        
                        // Scroll to top to show error messages
                        setTimeout(() => {
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }, 100);
                    @endif
                    
                    // Initialize Select2 for city select
                    const self = this;
                    setTimeout(() => {
                        $('#city-select').select2({
                            placeholder: '🔍 Sök efter din stad...',
                            allowClear: false
                        }).on('change', (e) => {
                            self.cityId = e.target.value;
                            self.cityChanged();
                        });
                        
                        // Set city value if restored from old input
                        @if(old('city_id'))
                            $('#city-select').val('{{ old('city_id') }}').trigger('change');
                        @endif
                    }, 100);
                    
                    // Initialize phone input if on contact step
                    @if($errors->any() || session('error'))
                        setTimeout(() => this.initPhoneInput(), 300);
                    @endif
                },
                
                // Initialize phone input when reaching step 4
                initPhoneInput() {
                    if (!window.iti) {
                        const phoneInput = document.querySelector("#phone");
                        if (phoneInput) {
                            console.log('Initializing intl-tel-input...');
                            window.iti = window.intlTelInput(phoneInput, {
                                initialCountry: "se", // Sweden as default
                                preferredCountries: ["se", "no", "dk", "fi", "de", "gb", "us"], // Nordic + common countries
                                separateDialCode: true, // Show country code separately
                                formatOnDisplay: true, // Auto-format as user types
                                autoPlaceholder: "aggressive", // Show example placeholder
                                nationalMode: false, // Include country code
                                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js"
                            });
                            console.log('intl-tel-input initialized:', window.iti);
                        }
                    }
                },
                
                // Step navigation
                nextStep() {
                    if (this.validateCurrentStep()) {
                        if (this.currentStep < this.steps.length - 1) {
                            this.currentStep++;
                            
                            // Load available slots when reaching step 3 (Typ - index 2)
                            if (this.currentStep === 2) {
                                this.loadAvailableSlots();
                            }
                            
                            
                            // Initialize phone input when reaching step 5 (index 4)
                            if (this.currentStep === 4) {
                                setTimeout(() => this.initPhoneInput(), 200);
                            }
                        }
                    }
                },
                
                previousStep() {
                    if (this.currentStep > 0) {
                        this.currentStep--;
                        
                        // Initialize phone input if going back to step 5 (index 4)
                        if (this.currentStep === 4) {
                            setTimeout(() => this.initPhoneInput(), 200);
                        }
                    }
                },
                
                validateCurrentStep() {
                    switch(this.currentStep) {
                        case 0: // City
                            if (!this.cityId) {
                                this.showToast('Vänligen välj en stad', 'warning');
                                return false;
                            }
                            return true;
                        case 1: // Customer Type
                            if (!this.customerType) {
                                this.showToast('Vänligen välj kundtyp', 'warning');
                                return false;
                            }
                            if (this.customerType === 'company' && !this.orgNumber) {
                                this.showToast('Vänligen ange organisationsnummer', 'warning');
                                return false;
                            }
                            if (this.customerType === 'private' && this.applyRot && !this.personnummer) {
                                this.showToast('Vänligen ange personnummer för ROT-avdrag', 'warning');
                                return false;
                            }
                            return true;
                        case 2: // Booking type
                            // Validate date and time selection
                            if (!this.selectedDate) {
                                this.showToast('Välj ett datum för din bokning', 'error');
                                return false;
                            }
                            if (!this.selectedTimeSlot) {
                                this.showToast('Välj en tid för din bokning', 'error');
                                return false;
                            }
                            return true;
                        case 3: // Form fields
                            // Validate required form fields
                            const formFields = @json($form->fields->where('required', true)->values());
                            for (let field of formFields) {
                                // Skip if field is not visible due to conditional logic
                                if (!this.isFieldVisible(field.field_name, field.id)) {
                                    continue;
                                }
                                
                                // Check based on field type
                                if (field.field_type === 'checkbox') {
                                    // For checkbox, check if at least one is checked
                                    const checkboxes = document.querySelectorAll(`[name="form_data[${field.field_name}][]"]`);
                                    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                                    if (checkboxes.length > 0 && !anyChecked) {
                                        this.showToast(`${field.field_label} är obligatoriskt`, 'warning');
                                        checkboxes[0]?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        return false;
                                    }
                                } else if (field.field_type === 'radio') {
                                    // For radio, check if any option is selected
                                    const checked = document.querySelector(`[name="form_data[${field.field_name}]"]:checked`);
                                    if (!checked) {
                                        const firstRadio = document.querySelector(`[name="form_data[${field.field_name}]"]`);
                                        this.showToast(`${field.field_label} är obligatoriskt`, 'warning');
                                        firstRadio?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        return false;
                                    }
                                } else {
                                    // text, number, email, tel, textarea, address, select, etc.
                                    const fieldElement = document.querySelector(`[name="form_data[${field.field_name}]"]`);
                                    if (!fieldElement) {
                                        console.warn(`Field not found: ${field.field_name}`);
                                        continue;
                                    }
                                    
                                    if (!fieldElement.value || fieldElement.value.trim() === '') {
                                        this.showToast(`${field.field_label} är obligatoriskt`, 'warning');
                                        fieldElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        fieldElement.focus();
                                        return false;
                                    }
                                    
                                    // Additional validation for number fields
                                    if (field.field_type === 'number') {
                                        const numValue = parseFloat(fieldElement.value);
                                        if (isNaN(numValue)) {
                                            this.showToast(`${field.field_label} måste vara ett giltigt nummer`, 'warning');
                                            fieldElement.focus();
                                            return false;
                                        }
                                    }
                                }
                            }
                            return true;
                        case 3: // Contact
                            // Validate name
                            const name = document.querySelector('input[name="name"]');
                            if (!name || !name.value.trim()) {
                                this.showToast('Vänligen ange ditt namn', 'warning');
                                name?.focus();
                                return false;
                            }
                            
                            // Validate email
                            const email = document.querySelector('input[name="email"]');
                            if (!email || !email.value.trim()) {
                                this.showToast('Vänligen ange din e-postadress', 'warning');
                                email?.focus();
                                return false;
                            }
                            // Validate email format
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailRegex.test(email.value.trim())) {
                                this.showToast('Vänligen ange en giltig e-postadress', 'warning');
                                email?.focus();
                                return false;
                            }
                            
                            // Validate phone
                            const phone = document.querySelector('input[name="customer_phone"]');
                            if (!phone || !phone.value.trim()) {
                                this.showToast('Vänligen ange ditt telefonnummer', 'warning');
                                phone?.focus();
                                return false;
                            }
                            // Validate phone (minimum 6 digits)
                            const phoneDigits = phone.value.replace(/\D/g, '');
                            if (phoneDigits.length < 6) {
                                this.showToast('Vänligen ange ett giltigt telefonnummer (minst 6 siffror)', 'warning');
                                phone?.focus();
                                return false;
                            }
                            
                            // Message is optional, no validation needed
                            
                            // Validate terms & conditions
                            const acceptTerms = document.querySelector('input[name="accept_terms"]');
                            if (!acceptTerms || !acceptTerms.checked) {
                                this.showToast('Du måste acceptera användarvillkoren för att fortsätta', 'warning');
                                acceptTerms?.focus();
                                return false;
                            }
                            
                            return true;
                        default:
                            return true;
                    }
                },
                
                // City changed
                cityChanged() {
                    if (this.cityId) {
                        this.calculatePrice();
                        
                        // Always load available slots after city selection
                        this.loadAvailableSlots();
                    }
                },
                
                
                // Load available slots
                async loadAvailableSlots() {
                    if (!this.cityId || !this.serviceId) return;
                    
                    this.loadingSlots = true;
                    this.availableSlots = {};
                    this.selectedDate = null;
                    this.selectedTimeSlot = null;
                    
                    try {
                        let url = '/api/slot-times/all-available';
                        let params = {
                            service_id: this.serviceId,
                            city_id: this.cityId,
                            days: 30
                        };
                        
                        const response = await window.axios.get(url, { params });
                        
                        if (response.data.slots) {
                            this.availableSlots = response.data.slots;
                            console.log('Loaded available slots:', this.availableSlots);
                            console.log('Total days:', response.data.total_days);
                            console.log('Total slots:', response.data.total_slots);
                            
                            // Generate calendar after loading slots
                            this.generateCalendarDays();
                        }
                    } catch (error) {
                        console.error('Error loading available slots:', error);
                        this.availableSlots = {};
                    } finally {
                        this.loadingSlots = false;
                    }
                },
                
                // Select a date
                selectDate(date) {
                    this.selectedDate = date;
                    this.selectedTimeSlot = null; // Reset time selection
                },
                
                // Select a time slot
                selectTimeSlot(slotId) {
                    this.selectedTimeSlot = slotId;
                    // Recalculate price when time slot is selected
                    this.calculatePrice();
                },
                
                // Swedish Calendar methods
                generateCalendarDays() {
                    const year = this.currentYear;
                    const month = this.currentMonth;
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const startDate = new Date(firstDay);
                    startDate.setDate(startDate.getDate() - firstDay.getDay() + 1); // Start from Monday
                    
                    const days = [];
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    for (let i = 0; i < 42; i++) { // 6 weeks
                        const date = new Date(startDate);
                        date.setDate(startDate.getDate() + i);
                        
                        const dateString = date.toISOString().split('T')[0];
                        const isCurrentMonth = date.getMonth() === month;
                        const isToday = date.getTime() === today.getTime();
                        const isSelected = this.selectedDate === dateString;
                        const isAvailable = this.isDateAvailable(date);
                        const hasSlots = this.availableSlots[dateString] && this.availableSlots[dateString].slots.length > 0;
                        
                        days.push({
                            date: dateString,
                            dayNumber: date.getDate(),
                            isCurrentMonth,
                            isToday,
                            isSelected,
                            isAvailable,
                            hasSlots
                        });
                    }
                    
                    this.calendarDays = days;
                },
                
                isDateAvailable(date) {
                    // Check if date is in the future and has available slots
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (date < today) return false;
                    
                    const dateString = date.toISOString().split('T')[0];
                    return this.availableSlots[dateString] && this.availableSlots[dateString].slots.length > 0;
                },
                
                selectCalendarDate(day) {
                    if (!day.isAvailable) return;
                    
                    this.selectedDate = day.date;
                    this.selectedTimeSlot = null;
                    
                    // Load times for selected date
                    if (this.availableSlots[day.date]) {
                        this.availableTimesForSelectedDate = this.availableSlots[day.date].slots;
                    }
                },
                
                clearDateSelection() {
                    this.selectedDate = null;
                    this.selectedTimeSlot = null;
                    this.availableTimesForSelectedDate = [];
                },
                
                previousMonth() {
                    if (this.currentMonth === 0) {
                        this.currentMonth = 11;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                    this.generateCalendarDays();
                },
                
                nextMonth() {
                    if (this.currentMonth === 11) {
                        this.currentMonth = 0;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                    this.generateCalendarDays();
                },
                
                toggleSubscriptionDay(dayIndex) {
                    const index = this.selectedSubscriptionDays.indexOf(dayIndex);
                    if (index > -1) {
                        this.selectedSubscriptionDays.splice(index, 1);
                    } else {
                        this.selectedSubscriptionDays.push(dayIndex);
                    }
                },
                
                getDayNumber(dayIndex) {
                    // Get the day number for the selected date's week
                    if (!this.selectedDate) return '';
                    
                    const selectedDate = new Date(this.selectedDate);
                    const startOfWeek = new Date(selectedDate);
                    startOfWeek.setDate(selectedDate.getDate() - selectedDate.getDay() + 1); // Monday
                    
                    const targetDate = new Date(startOfWeek);
                    targetDate.setDate(startOfWeek.getDate() + dayIndex);
                    
                    return targetDate.getDate();
                },
                
                getSelectedTimeFormatted() {
                    if (!this.selectedTimeSlot || !this.availableTimesForSelectedDate) return '';
                    
                    const selectedTime = this.availableTimesForSelectedDate.find(time => time.id == this.selectedTimeSlot);
                    return selectedTime ? selectedTime.time_formatted : '';
                },
                
                
                
                // Booking type changed
                updateBookingType(type, frequency) {
                    this.bookingType = type;
                    if (frequency) {
                        this.subscriptionFrequency = frequency;
                    }
                    this.calculatePrice();
                },
                
                // Field value updated
                updateFieldValue(fieldName, value) {
                    this.formData[fieldName] = value;
                    this.calculatePrice();
                },
                
                // Update loyalty points usage
                updateLoyaltyPointsUsage() {
                    if (!this.useLoyaltyPoints) {
                        this.loyaltyPointsToUse = 0;
                    } else {
                        // Ensure we don't use more points than available or more than the booking amount
                        const maxPoints = Math.min(this.userLoyaltyPoints, Math.floor(this.priceBreakdown.final_price));
                        this.loyaltyPointsToUse = Math.min(this.loyaltyPointsToUse, maxPoints);
                    }
                    this.calculatePrice();
                },
                
                // Check if field is visible (conditional logic)
                isFieldVisible(fieldName, fieldId) {
                    const logic = this.conditionalFields[fieldName + '_' + fieldId];
                    if (!logic || !logic.rules || logic.rules.length === 0) {
                        return true;
                    }
                    
                    const operator = logic.operator || 'and';
                    let results = [];
                    
                    logic.rules.forEach(rule => {
                        const fieldValue = this.formData[rule.field] || '';
                        const compareValue = rule.value || '';
                        const condition = rule.condition || rule.operator || 'equals';
                        let result = false;
                        
                        switch(condition) {
                            case 'equals':
                                result = fieldValue == compareValue;
                                break;
                            case 'not_equals':
                                result = fieldValue != compareValue;
                                break;
                            case 'contains':
                                result = String(fieldValue).toLowerCase().includes(compareValue.toLowerCase());
                                break;
                        }
                        
                        results.push(result);
                    });
                    
                    return operator === 'and' ? results.every(r => r) : results.some(r => r);
                },
                
                // Calculate price
                async calculatePrice() {
                    if (!this.cityId || !this.serviceId) return;
                    
                    try {
                        const response = await window.axios.post('/api/calculate-price', {
                            service_id: this.serviceId,
                            city_id: this.cityId,
                            form_id: this.formId,
                            form_data: this.formData,
                            apply_rot: this.applyRot,
                            booking_type: this.bookingType,
                            subscription_frequency: this.subscriptionFrequency,
                            loyalty_points_used: this.useLoyaltyPoints ? this.loyaltyPointsToUse : 0,
                            slot_time_id: this.selectedTimeSlot,
                            user_id: {{ auth()->check() ? auth()->id() : 'null' }}
                        });
                        
                        this.priceBreakdown = response.data;
                    } catch (error) {
                        console.error('Price calculation error:', error);
                    }
                },
                
                
                
                // Format org number (123456-7890)
                formatOrgNumber(event) {
                    let value = event.target.value.replace(/[^0-9]/g, ''); // Remove non-digits
                    
                    if (value.length > 6) {
                        value = value.substring(0, 6) + '-' + value.substring(6, 10);
                    }
                    
                    this.orgNumber = value;
                    this.updateFieldValue('org_number', value);
                },
                
                // Format personnummer (YYYYMMDD-XXXX)
                formatPersonnummer(event) {
                    let value = event.target.value.replace(/[^0-9]/g, ''); // Remove non-digits
                    
                    if (value.length > 8) {
                        value = value.substring(0, 8) + '-' + value.substring(8, 12);
                    }
                    
                    this.personnummer = value;
                    this.updateFieldValue('personnummer', value);
                },
                
                // Format price
                formatPrice(price) {
                    return new Intl.NumberFormat('sv-SE', {
                        style: 'currency',
                        currency: 'SEK',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(price);
                },
                
                // Submit form
                async submitForm(event) {
                    // Prevent default form submission
                    event.preventDefault();
                    
                    if (!this.validateCurrentStep()) {
                        return;
                    }
                    
                    this.isSubmitting = true;
                    
                    // Get the actual phone number with country code
                    if (window.iti) {
                        const phoneInput = document.querySelector("#phone");
                        if (phoneInput) {
                            phoneInput.value = window.iti.getNumber();
                        }
                    }
                    
                    // Get the form element
                    const form = event.target;
                    
                    // Add company selection data to form
                    // Make sure all data is collected before submission
                    console.log('Submitting form with data:', {
                        service_id: this.serviceId,
                        form_id: this.formId,
                        city_id: this.cityId,
                        booking_type: this.bookingType,
                        subscription_frequency: this.subscriptionFrequency
                    });
                    
                    // Submit the form
                    form.submit();
                }
            });
        });
        </script>

        <form 
            method="POST" 
            action="{{ route('booking.submit', $form->public_token) }}"
            x-data="multiStepForm({{ $form->service_id }}, {{ $form->id }})"
            x-init="init()"
            @submit="submitForm"
            novalidate
        >
            @csrf
            
            <!-- Hidden required fields -->
            <input type="hidden" name="service_id" value="{{ $form->service_id }}">
            <input type="hidden" name="form_id" value="{{ $form->id }}">
            <input type="hidden" name="selected_date" x-model="selectedDate">
            <input type="hidden" name="selected_time_slot" x-model="selectedTimeSlot">
            <input type="hidden" name="selected_subscription_days" :value="JSON.stringify(selectedSubscriptionDays)">
            <input type="hidden" name="loyalty_points_used" x-model="loyaltyPointsToUse">
            <input type="hidden" name="use_loyalty_points" x-model="useLoyaltyPoints">

            <!-- Progress Bar -->
            <div class="mb-8 animate-fade-in">
                <div class="flex justify-between items-center mb-4">
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="flex items-center flex-1">
                            <div class="flex flex-col items-center flex-1">
                                <div 
                                    class="step-indicator w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300"
                                    :class="{
                                        'bg-blue-600 text-white scale-110 shadow-lg': currentStep === index,
                                        'bg-green-500 text-white': currentStep > index,
                                        'bg-gray-200 text-gray-500': currentStep < index
                                    }"
                                >
                                    <span x-show="currentStep <= index" x-text="index + 1"></span>
                                    <span x-show="currentStep > index">✓</span>
                                </div>
                                <span 
                                    class="text-xs mt-2 font-medium transition-colors duration-300"
                                    :class="{
                                        'text-blue-600': currentStep === index,
                                        'text-green-600': currentStep > index,
                                        'text-gray-500': currentStep < index
                                    }"
                                    x-text="step.title"
                                ></span>
                            </div>
                            <div 
                                x-show="index < steps.length - 1" 
                                class="flex-1 h-1 mx-2 transition-all duration-300"
                                :class="currentStep > index ? 'bg-green-500' : 'bg-gray-200'"
                            ></div>
                        </div>
                    </template>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div 
                        class="h-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-500 ease-out"
                        :style="`width: ${(currentStep / (steps.length - 1)) * 100}%`"
                    ></div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 lg:p-12">
                    
                    <!-- Step 1: City Selection -->
                    <div x-show="currentStep === 0" x-transition:enter="animate-slide-in-right" x-transition:leave="animate-slide-in-left">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
                                <span class="text-4xl">📍</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Välj din stad</h2>
                            <p class="text-gray-600">Var ska tjänsten utföras?</p>
                        </div>

                        <div class="max-w-md mx-auto">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stad</label>
                            <select 
                                id="city-select"
                                name="city_id" 
                                x-model="cityId"
                                @change="cityChanged()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required
                            >
                                <option value="">🔍 Sök efter din stad...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }} ({{ $city->zone->name }})</option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-2">💡 Priset kan variera beroende på stad</p>
                        </div>
                        
                        <!-- Company Selector (shown after city is selected) -->
                        <div x-show="cityId" x-transition class="mt-8">
                            <x-company-selector />
                        </div>
                    </div>

                    <!-- Step 2: Customer Type -->
                    <div x-show="currentStep === 1" x-transition:enter="animate-slide-in-right" x-transition:leave="animate-slide-in-left">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-100 rounded-full mb-4">
                                <span class="text-4xl">👤</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Vem bokar tjänsten?</h2>
                            <p class="text-gray-600">Välj om du bokar som privatperson eller företag</p>
                        </div>

                        <div class="max-w-md mx-auto space-y-4">
                            <!-- Customer Type Selection -->
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition-all">
                                    <input 
                                        type="radio" 
                                        name="customer_type"
                                        value="private"
                                        x-model="customerType"
                                        @change="updateFieldValue('customer_type', $event.target.value)"
                                        class="mr-3 text-purple-600 focus:ring-purple-500"
                                        required
                                    >
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900">🏠 Privatperson</div>
                                        <div class="text-sm text-gray-600">Jag bokar för mig själv eller min familj</div>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 hover:bg-purple-50 transition-all">
                                    <input 
                                        type="radio" 
                                        name="customer_type"
                                        value="company"
                                        x-model="customerType"
                                        @change="updateFieldValue('customer_type', $event.target.value)"
                                        class="mr-3 text-purple-600 focus:ring-purple-500"
                                        required
                                    >
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900">🏢 Företag</div>
                                        <div class="text-sm text-gray-600">Jag bokar för mitt företag eller organisation</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Company Org Number (shown when company is selected) -->
                            <div x-show="customerType === 'company'" x-transition class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Organisationsnummer <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="org_number"
                                    x-model="orgNumber"
                                    @input="formatOrgNumber($event)"
                                    placeholder="123456-7890"
                                    maxlength="11"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                                >
                                <p class="text-xs text-gray-500 mt-1">Format: 123456-7890</p>
                            </div>

                            <!-- ROT Avdrag (only for private customers and if service supports it) -->
                            @if($form->service->rot_eligible)
                                <div x-show="customerType === 'private'" x-transition class="mt-6">
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-300 hover:bg-green-50 transition-all">
                                        <input 
                                            type="checkbox" 
                                            name="apply_rot"
                                            x-model="applyRot"
                                            @change="updateFieldValue('apply_rot', $event.target.checked)"
                                            class="mr-3 text-green-600 focus:ring-green-500 rounded"
                                        >
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900">💚 Jag vill använda ROT-avdrag</div>
                                            <div class="text-sm text-gray-600">Spara upp till {{ $form->service->rot_percent }}% på din faktura</div>
                                        </div>
                                    </label>

                                    <!-- Personnummer (shown when ROT is selected) -->
                                    <div x-show="applyRot" x-transition class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Personnummer <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            name="personnummer"
                                            x-model="personnummer"
                                            @input="formatPersonnummer($event)"
                                            placeholder="19900101-1234"
                                            maxlength="13"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                        >
                                        <p class="text-xs text-gray-500 mt-1">Format: YYYYMMDD-XXXX (för ROT-avdrag)</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Step 3: Booking Type -->
                    <div x-show="currentStep === 2" x-transition:enter="animate-slide-in-right" x-transition:leave="animate-slide-in-left">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-100 rounded-full mb-4">
                                <span class="text-4xl">📅</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Välj bokningstyp</h2>
                            <p class="text-gray-600">Hur ofta behöver du tjänsten?</p>
                        </div>

                        @php
                            $defaultBookingType = $form->service->one_time_booking ? 'one_time' : 'subscription';
                            $defaultSubscriptionFreq = $form->service->subscription_types[0] ?? 'weekly';
                            $bookingTypesCount = ($form->service->one_time_booking ? 1 : 0) + ($form->service->subscription_booking ? 1 : 0);
                        @endphp

                        <div class="max-w-2xl mx-auto space-y-6">
                            <!-- Booking Type Selection -->
                            <div class="grid grid-cols-{{ $bookingTypesCount }} gap-4">
                                @if($form->service->one_time_booking)
                                    <label class="cursor-pointer group">
                                        <input 
                                            type="radio" 
                                            name="booking_type" 
                                            value="one_time" 
                                            x-model="bookingType"
                                            @change="updateBookingType('one_time', null)"
                                            class="hidden"
                                            {{ $form->service->one_time_booking ? 'checked' : '' }}
                                        >
                                        <div 
                                            class="border-2 rounded-xl p-6 text-center transition-all duration-300 hover:shadow-lg"
                                            :class="bookingType === 'one_time' ? 'border-blue-500 bg-blue-50 shadow-lg scale-105' : 'border-gray-300 hover:border-blue-300'"
                                        >
                                            <div class="text-5xl mb-3">📅</div>
                                            <div class="font-bold text-lg text-gray-900">Engångsbokning</div>
                                            <div class="text-sm text-gray-600 mt-1">Boka en gång</div>
                                        </div>
                                    </label>
                                @endif

                                @if($form->service->subscription_booking)
                                    <label class="cursor-pointer group">
                                        <input 
                                            type="radio" 
                                            name="booking_type" 
                                            value="subscription" 
                                            x-model="bookingType"
                                            @change="updateBookingType('subscription', subscriptionFrequency)"
                                            class="hidden"
                                            {{ !$form->service->one_time_booking && $form->service->subscription_booking ? 'checked' : '' }}
                                        >
                                        <div 
                                            class="border-2 rounded-xl p-6 text-center transition-all duration-300 hover:shadow-lg"
                                            :class="bookingType === 'subscription' ? 'border-purple-500 bg-purple-50 shadow-lg scale-105' : 'border-gray-300 hover:border-purple-300'"
                                        >
                                            <div class="text-5xl mb-3">🔄</div>
                                            <div class="font-bold text-lg text-gray-900">Prenumeration</div>
                                            <div class="text-sm text-gray-600 mt-1">Återkommande bokning</div>
                                        </div>
                                    </label>
                                @endif
                            </div>

                            <!-- Subscription Frequency -->
                            @if($form->service->subscription_booking && $form->service->subscription_types && count($form->service->subscription_types) > 0)
                                @php
                                    $subscriptionTypes = $form->service->subscription_types;
                                    $availableTypes = [
                                        'daily' => ['icon' => '⏰', 'label' => 'Dagligen', 'multiplier' => $form->service->daily_multiplier],
                                        'weekly' => ['icon' => '📆', 'label' => 'Veckovis', 'multiplier' => $form->service->weekly_multiplier],
                                        'biweekly' => ['icon' => '📅', 'label' => 'Varannan vecka', 'multiplier' => $form->service->biweekly_multiplier],
                                        'monthly' => ['icon' => '🗓️', 'label' => 'Månadsvis', 'multiplier' => $form->service->monthly_multiplier],
                                    ];
                                @endphp

                                <div x-show="bookingType === 'subscription'" x-transition class="mt-6">
                                    <h3 class="text-lg font-semibold text-center mb-4">Välj frekvens</h3>
                                    <div class="grid grid-cols-{{ min(count($subscriptionTypes), 4) }} gap-4">
                                        @foreach($subscriptionTypes as $type)
                                            @if(isset($availableTypes[$type]))
                                                <label class="cursor-pointer">
                                                    <input 
                                                        type="radio" 
                                                        name="subscription_frequency" 
                                                        value="{{ $type }}" 
                                                        x-model="subscriptionFrequency"
                                                        @change="updateBookingType('subscription', '{{ $type }}')"
                                                        class="hidden"
                                                        {{ $loop->first ? 'checked' : '' }}
                                                    >
                                                    <div 
                                                        class="border-2 rounded-lg p-4 text-center transition-all duration-300 hover:shadow-md"
                                                        :class="subscriptionFrequency === '{{ $type }}' ? 'border-purple-500 bg-purple-50' : 'border-gray-300'"
                                                    >
                                                        <div class="text-3xl mb-2">{{ $availableTypes[$type]['icon'] }}</div>
                                                        <div class="font-medium text-sm">{{ $availableTypes[$type]['label'] }}</div>
                                                        @if($availableTypes[$type]['multiplier'] < 1)
                                                            <div class="text-xs text-green-600 mt-1 font-semibold">{{ round((1 - $availableTypes[$type]['multiplier']) * 100) }}% rabatt</div>
                                                        @elseif($availableTypes[$type]['multiplier'] > 1)
                                                            <div class="text-xs text-red-600 mt-1 font-semibold">+{{ round(($availableTypes[$type]['multiplier'] - 1) * 100) }}%</div>
                                                        @endif
                                                    </div>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Swedish Calendar System -->
                            <div class="mt-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                    <span class="mr-2">📅</span>
                                    Välj datum och tid
                                </h3>
                                
                                <!-- Loading State -->
                                <div x-show="loadingSlots" class="text-center py-8">
                                    <div class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-gray-600">Laddar tillgängliga tider...</span>
                                    </div>
                                </div>

                                <!-- Swedish Calendar -->
                                <div x-show="!loadingSlots" class="space-y-6">
                                    <!-- Calendar Header -->
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-semibold text-gray-900">Välj datum</h4>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" @click="previousMonth()" class="p-2 hover:bg-gray-100 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            <span class="font-semibold text-gray-900" x-text="currentMonthName + ' ' + currentYear"></span>
                                            <button type="button" @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Calendar Grid -->
                                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                        <!-- Weekday Headers -->
                                        <div class="grid grid-cols-7 bg-gray-50 border-b">
                                            <template x-for="day in swedishWeekdays" :key="day">
                                                <div class="p-3 text-center text-sm font-semibold text-gray-600" x-text="day"></div>
                                            </template>
                                        </div>
                                        
                                        <!-- Calendar Days -->
                                        <div class="grid grid-cols-7">
                                            <template x-for="day in calendarDays" :key="day.date">
                                                <button type="button"
                                                        class="p-3 text-center border-r border-b border-gray-100 hover:bg-gray-50 transition-all relative"
                                                        :class="{
                                                            'bg-blue-50 text-blue-600 font-semibold': day.isSelected,
                                                            'text-gray-400': !day.isCurrentMonth,
                                                            'text-gray-900': day.isCurrentMonth && !day.isSelected,
                                                            'bg-red-50 text-red-600': day.isToday,
                                                            'cursor-not-allowed opacity-50': !day.isAvailable
                                                        }"
                                                        @click="selectCalendarDate(day)"
                                                        :disabled="!day.isAvailable">
                                                    <span x-text="day.dayNumber"></span>
                                                    <div x-show="day.hasSlots" class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-green-500 rounded-full"></div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Selection Summary -->
                                    <div x-show="selectedDate" class="bg-white rounded-lg border border-gray-200 p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex items-center">
                                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                                        <span class="text-sm font-medium text-gray-700">Datum:</span>
                                                        <span class="text-sm text-gray-900 ml-1" x-text="selectedDateFormatted"></span>
                                                    </div>
                                                    <div x-show="selectedTimeSlot" class="flex items-center">
                                                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                                        <span class="text-sm font-medium text-gray-700">Tid:</span>
                                                        <span class="text-sm text-gray-900 ml-1" x-text="getSelectedTimeFormatted()"></span>
                                                    </div>
                                                </div>
                                                <div x-show="!selectedTimeSlot" class="mt-2">
                                                    <p class="text-sm text-amber-600">⚠️ Välj en tid för att fortsätta</p>
                                                </div>
                                            </div>
                                            <button type="button" @click="clearDateSelection()" class="text-gray-400 hover:text-gray-600 ml-4">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Time Selection -->
                                    <div x-show="selectedDate && availableTimesForSelectedDate.length > 0" 
                                         x-transition
                                         class="space-y-4">
                                        <h4 class="font-semibold text-gray-900">Välj tid</h4>
                                        <p class="text-sm text-gray-600 mb-4">Välj en specifik tid för din bokning</p>
                                        
                                        <!-- Available Times -->
                                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                            <template x-for="time in availableTimesForSelectedDate" :key="time.id">
                                                <button type="button"
                                                        class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all text-left group"
                                                        :class="{ 'border-blue-500 bg-blue-50 shadow-md': selectedTimeSlot === time.id, 'border-gray-200': selectedTimeSlot !== time.id }"
                                                        @click="selectTimeSlot(time.id)"
                                                        :disabled="!time.is_available">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <div class="font-semibold text-gray-900 text-lg" x-text="time.time_formatted"></div>
                                                            <div class="text-sm text-gray-600 mt-1" x-text="time.available_spots + ' platser kvar'"></div>
                                                        </div>
                                                        <div class="ml-3">
                                                            <div x-show="selectedTimeSlot === time.id" class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </div>
                                                            <div x-show="selectedTimeSlot !== time.id" class="w-6 h-6 border-2 border-gray-300 rounded-full group-hover:border-blue-500 transition-colors"></div>
                                                        </div>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                        
                                        <!-- No Times Available Message -->
                                        <div x-show="selectedDate && availableTimesForSelectedDate.length === 0" class="text-center py-8 text-gray-500">
                                            <div class="text-4xl mb-2">⏰</div>
                                            <p class="font-semibold">Inga tider tillgängliga</p>
                                            <p class="text-sm mt-1">För detta datum finns inga tillgängliga tider. Välj ett annat datum.</p>
                                        </div>
                                    </div>

                                    <!-- Subscription Day Selection (for weekly/monthly) -->
                                    <div x-show="selectedDate && isSubscriptionBooking && (bookingType === 'weekly' || bookingType === 'monthly')" 
                                         x-transition
                                         class="space-y-4">
                                        <h4 class="font-semibold text-gray-900">Välj dagar för prenumeration</h4>
                                        
                                        <div class="bg-white rounded-lg border border-gray-200 p-4">
                                            <div class="grid grid-cols-7 gap-2">
                                                <template x-for="(day, index) in swedishWeekdays" :key="index">
                                                    <button type="button"
                                                            class="p-2 text-center border border-gray-200 rounded-lg hover:border-blue-500 transition-all"
                                                            :class="{ 'border-blue-500 bg-blue-50 text-blue-600': selectedSubscriptionDays.includes(index) }"
                                                            @click="toggleSubscriptionDay(index)">
                                                        <div class="text-xs font-semibold" x-text="day"></div>
                                                        <div class="text-xs text-gray-500" x-text="getDayNumber(index)"></div>
                                                    </button>
                                                </template>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-3">
                                                Välj vilka dagar i veckan/månaden du vill ha tjänsten
                                            </p>
                                        </div>
                                    </div>

                                    <!-- No Available Slots -->
                                    <div x-show="!loadingSlots && Object.keys(availableSlots).length === 0" class="text-center py-8 text-gray-500">
                                        <div class="text-4xl mb-2">📅</div>
                                        <p class="font-semibold">Inga tillgängliga tider hittades</p>
                                        <p class="text-sm mt-1">För denna tjänst finns inga tillgängliga tider just nu.</p>
                                        <p class="text-sm mt-1">Kontakta oss för att hitta en lämplig tid.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Step 4: Dynamic Form Fields -->
                    <div x-show="currentStep === 3" x-transition:enter="animate-slide-in-right" x-transition:leave="animate-slide-in-left">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                                <span class="text-4xl">📝</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Anpassa din tjänst</h2>
                            <p class="text-gray-600">Fyll i detaljer för att få exakt pris</p>
                        </div>

                        <!-- Service Description Info Box -->
                        @if($form->service->full_content)
                        <div class="max-w-2xl mx-auto mb-8">
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Tjänstinformation</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <p>{{ $form->service->full_content }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif


                        <div class="max-w-2xl mx-auto flex flex-wrap gap-6">
                            @foreach($form->fields->sortBy('sort_order') as $field)
                                @php
                                    $widthClass = match($field->field_width ?? '100') {
                                        '100', 'full' => 'w-full',
                                        '50', 'half' => 'w-full md:w-[calc(50%-0.75rem)]',
                                        '33', 'third' => 'w-full md:w-[calc(33.333%-1rem)]',
                                        '25', 'quarter' => 'w-full md:w-[calc(25%-1.125rem)]',
                                        default => 'w-full'
                                    };
                                @endphp
                                <div 
                                    x-show="isFieldVisible('{{ $field->field_name }}', {{ $field->id }})"
                                    x-transition
                                    class="bg-gray-50 rounded-lg p-6 hover:shadow-md transition-shadow duration-300 {{ $widthClass }}"
                                >
                                    @if($field->field_type !== 'divider')
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="inline-flex items-center">
                                                @switch($field->field_type)
                                                    @case('text')
                                                    @case('email')
                                                    @case('tel')
                                                        <span class="mr-2">✍️</span>
                                                        @break
                                                    @case('address')
                                                        <span class="mr-2">📍</span>
                                                        @break
                                                    @case('number')
                                                        <span class="mr-2">🔢</span>
                                                        @break
                                                    @case('select')
                                                        <span class="mr-2">📋</span>
                                                        @break
                                                    @case('radio')
                                                        <span class="mr-2">⭕</span>
                                                        @break
                                                    @case('checkbox')
                                                        <span class="mr-2">☑️</span>
                                                        @break
                                                    @case('textarea')
                                                        <span class="mr-2">📄</span>
                                                        @break
                                                    @case('date')
                                                        <span class="mr-2">📅</span>
                                                        @break
                                                @endswitch
                                                {{ $field->field_label }}
                                                @if($field->required)<span class="text-red-500 ml-1">*</span>@endif
                                            </span>
                                            @if($field->field_type === 'number' && $field->pricing_rules && isset($field->pricing_rules['pricePerUnit']) && $field->pricing_rules['pricePerUnit'] > 0)
                                                @php
                                                    $unitLabel = $field->pricing_rules['unitLabel'] ?? 'kr/st';
                                                @endphp
                                                <span class="ml-2 text-green-600 text-xs font-medium">({{ number_format($field->pricing_rules['pricePerUnit'], 0) }} {{ $unitLabel }})</span>
                                            @endif
                                        </label>
                                    @endif

                                    @if($field->field_type === 'text')
                                        <input 
                                            type="text" 
                                            name="form_data[{{ $field->field_name }}]"
                                            placeholder="{{ $field->placeholder_text ?? '' }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            @if($field->required) required @endif
                                            @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                        >

                                    @elseif($field->field_type === 'address')
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-gray-400 text-xl">📍</span>
                                            </div>
                                            <input 
                                                type="text" 
                                                id="address_{{ $field->id }}"
                                                name="form_data[{{ $field->field_name }}]"
                                                placeholder="{{ $field->placeholder_text ?? 'Börja skriva din adress...' }}"
                                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                                @if($field->required) required @endif
                                                @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                                autocomplete="off"
                                            >
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                                            <span class="mr-1">🔍</span>
                                            Börja skriva för att söka adress
                                        </p>

                                    @elseif($field->field_type === 'number')
                                        <input 
                                            type="number" 
                                            name="form_data[{{ $field->field_name }}]"
                                            placeholder="{{ $field->placeholder_text ?? '' }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            @if($field->required) required @endif
                                            @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                        >

                                    @elseif($field->field_type === 'select' && $field->field_options && is_array($field->field_options) && count($field->field_options) > 0)
                                        <select 
                                            name="form_data[{{ $field->field_name }}]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            @if($field->required) required @endif
                                            @change="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                        >
                                            <option value="">Välj...</option>
                                            @foreach($field->field_options as $option)
                                                <option value="{{ $option['value'] ?? $option['label'] }}">
                                                    {{ $option['label'] }}
                                                    @if(isset($option['price']) && $option['price'] > 0)
                                                        (+{{ number_format($option['price'], 0) }} kr)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    @elseif($field->field_type === 'radio' && $field->field_options && is_array($field->field_options) && count($field->field_options) > 0)
                                        <div class="space-y-3">
                                            @foreach($field->field_options as $option)
                                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all">
                                                    <input 
                                                        type="radio" 
                                                        name="form_data[{{ $field->field_name }}]"
                                                        value="{{ $option['value'] ?? $option['label'] }}"
                                                        class="mr-3 text-blue-600 focus:ring-blue-500"
                                                        @if($field->required && $loop->first) required @endif
                                                        @change="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                                    >
                                                    <span class="flex-1 font-medium">{{ $option['label'] }}</span>
                                                    @if(isset($option['price']) && $option['price'] > 0)
                                                        <span class="text-green-600 font-semibold">+{{ number_format($option['price'], 0) }} kr</span>
                                                    @endif
                                                </label>
                                            @endforeach
                                        </div>

                                    @elseif($field->field_type === 'checkbox' && $field->field_options && is_array($field->field_options) && count($field->field_options) > 0)
                                        <div class="space-y-3">
                                            @foreach($field->field_options as $option)
                                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all">
                                                    <input 
                                                        type="checkbox" 
                                                        name="form_data[{{ $field->field_name }}][]"
                                                        value="{{ $option['value'] ?? $option['label'] }}"
                                                        class="mr-3 text-blue-600 focus:ring-blue-500 rounded"
                                                        @change="updateFieldValue('{{ $field->field_name }}', $event.target.checked ? $event.target.value : null)"
                                                    >
                                                    <span class="flex-1 font-medium">{{ $option['label'] }}</span>
                                                    @if(isset($option['price']) && $option['price'] > 0)
                                                        <span class="text-green-600 font-semibold">+{{ number_format($option['price'], 0) }} kr</span>
                                                    @endif
                                                </label>
                                            @endforeach
                                        </div>

                                    @elseif($field->field_type === 'textarea')
                                        <textarea 
                                            name="form_data[{{ $field->field_name }}]"
                                            rows="4"
                                            placeholder="{{ $field->placeholder_text ?? '' }}"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            @if($field->required) required @endif
                                            @input="updateFieldValue('{{ $field->field_name }}', $event.target.value)"
                                        ></textarea>

                                    @elseif($field->field_type === 'date')
                                        <input 
                                            type="date" 
                                            name="form_data[{{ $field->field_name }}]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            @if($field->required) required @endif
                                        >

                                    @elseif($field->field_type === 'divider')
                                        <hr class="my-6 border-t-2 border-gray-200">
                                    @endif

                                    @if($field->help_text)
                                        <p class="text-sm text-gray-500 mt-2">💡 {{ $field->help_text }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 5: Contact Information -->
                    <div x-show="currentStep === 4" x-transition:enter="animate-slide-in-right" x-transition:leave="animate-slide-in-left">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-4">
                                <span class="text-4xl">👤</span>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Dina uppgifter</h2>
                            <p class="text-gray-600">Så att vi kan kontakta dig</p>
                        </div>

                        <div class="max-w-2xl mx-auto space-y-6">
                            <!-- Name -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="inline-flex items-center">
                                        <span class="mr-2">👤</span>
                                        Namn <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                @auth
                                    @if(auth()->user()->name)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">✓ Automatiskt ifylld</span>
                                        </div>
                                    @endif
                                @endauth
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name', auth()->user()->name ?? '') }}"
                                    placeholder="Ditt fullständiga namn"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required
                                    @if(auth()->check() && auth()->user()->name) readonly @endif
                                >
                                @auth
                                    <p class="text-xs text-gray-500 mt-2">💡 Inloggad som {{ auth()->user()->email }}</p>
                                @endauth
                            </div>

                            <!-- Email -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="inline-flex items-center">
                                        <span class="mr-2">📧</span>
                                        E-post <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                @auth
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">✓ Automatiskt ifylld</span>
                                    </div>
                                @endauth
                                <input 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email', auth()->user()->email ?? '') }}"
                                    placeholder="din@email.se"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @auth bg-gray-100 @endauth"
                                    required
                                    @auth readonly @endauth
                                >
                            </div>

                            <!-- Phone with International Flags -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="inline-flex items-center">
                                        <span class="mr-2">📱</span>
                                        Telefon <span class="text-red-500 ml-1">*</span>
                                    </span>
                                </label>
                                @auth
                                    @if(auth()->user()->phone)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">✓ Automatiskt ifylld</span>
                                        </div>
                                    @endif
                                @endauth
                                <p class="text-xs text-gray-500 mb-2">🇸🇪 Välj land och ange ditt telefonnummer</p>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="customer_phone" 
                                    value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                                    placeholder="070 123 45 67"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    required
                                >
                                <p class="text-xs text-gray-500 mt-2 italic">Klicka på flaggan för att välja annat land</p>
                            </div>


                            <!-- Message -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <span class="inline-flex items-center">
                                        <span class="mr-2">💬</span>
                                        Meddelande (valfritt)
                                    </span>
                                </label>
                                <textarea 
                                    name="customer_message" 
                                    rows="4"
                                    placeholder="Berätta mer om vad du behöver hjälp med..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                >{{ old('customer_message') }}</textarea>
                                <p class="text-xs text-gray-500 mt-2">Om du vill ge mer information om din bokning</p>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                                <label class="flex items-start cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="accept_terms" 
                                        value="1"
                                        class="mr-3 mt-1 text-blue-600 focus:ring-blue-500 rounded"
                                        required
                                    >
                                    <div class="text-sm text-gray-700">
                                        Jag godkänner <a href="#" class="text-blue-600 hover:underline">användarvillkoren</a> 
                                        och <a href="#" class="text-blue-600 hover:underline">integritetspolicyn</a>
                                        <span class="text-red-500 ml-1">*</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Navigation Footer -->
                <div class="bg-gray-50 px-8 lg:px-12 py-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <button 
                            type="button"
                            @click="previousStep()"
                            x-show="currentStep > 0"
                            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all duration-300 flex items-center"
                        >
                            <span class="mr-2">←</span>
                            Tillbaka
                        </button>

                        <div x-show="currentStep < steps.length - 1">
                            <button 
                                type="button"
                                @click="nextStep()"
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center"
                            >
                                Nästa
                                <span class="ml-2">→</span>
                            </button>
                        </div>

                        <div x-show="currentStep === steps.length - 1">
                            <button 
                                type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center"
                                :disabled="isSubmitting"
                            >
                                <span x-show="!isSubmitting">
                                    <span class="mr-2">✓</span>
                                    Skicka bokning
                                </span>
                                <span x-show="isSubmitting">
                                    <span class="animate-spin mr-2">⏳</span>
                                    Skickar...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Price Card - Top Right -->
            <div 
                x-show="cityId && priceBreakdown"
                x-transition
                class="fixed top-24 right-6 bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-2xl max-w-sm z-50 border-2 border-blue-300 animate-fade-in overflow-hidden"
                style="max-height: calc(100vh - 200px); overflow-y: auto;"
            >
                <!-- Compact Header -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase font-semibold tracking-wide opacity-90">Din offert</div>
                            <div class="text-2xl font-bold price-impact" x-text="formatPrice(priceBreakdown.final_price)"></div>
                        </div>
                        <button 
                            type="button"
                            @click="showPriceDetails = !showPriceDetails"
                            class="bg-white/20 hover:bg-white/30 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200"
                        >
                            <span x-show="!showPriceDetails">▼</span>
                            <span x-show="showPriceDetails">▲</span>
                        </button>
                    </div>
                </div>

                <!-- Expandable Price Details -->
                <div x-show="showPriceDetails" x-transition class="p-4 space-y-3">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600 font-medium">📦 Grundpris</span>
                            <span class="font-semibold text-gray-900" x-text="formatPrice(priceBreakdown.base_price)"></span>
                        </div>
                        
                        <div x-show="priceBreakdown.variable_additions > 0" class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600 font-medium">➕ Tillägg</span>
                            <span class="font-semibold text-blue-600" x-text="'+' + formatPrice(priceBreakdown.variable_additions)"></span>
                        </div>
                        
                        <div x-show="priceBreakdown.subscription_multiplier && priceBreakdown.subscription_multiplier != 1" class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600 font-medium">🔄 Prenumeration</span>
                            <span class="font-semibold text-purple-600" x-text="'×' + priceBreakdown.subscription_multiplier"></span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600 font-medium">📍 Stad</span>
                            <span class="font-semibold text-gray-900" x-text="'×' + priceBreakdown.city_multiplier"></span>
                        </div>
                        
                        <div x-show="priceBreakdown.discount_amount > 0" class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600 font-medium">🎁 Rabatt</span>
                            <span class="font-semibold text-green-600" x-text="'-' + formatPrice(priceBreakdown.discount_amount)"></span>
                        </div>
                        
                        <!-- Loyalty Points Section -->
                        <div x-show="userLoyaltyPoints > 0" class="py-3 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-700 font-medium flex items-center">
                                    <span class="mr-2">⭐</span>
                                    Lojalitetspoäng
                                </span>
                                <span class="text-sm text-gray-500" x-text="'Tillgängliga: ' + userLoyaltyPoints + ' poäng'"></span>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        x-model="useLoyaltyPoints"
                                        @change="updateLoyaltyPointsUsage()"
                                        class="mr-2 text-blue-600 focus:ring-blue-500 rounded"
                                    >
                                    <span class="text-sm font-medium text-gray-700">Använd lojalitetspoäng</span>
                                </label>
                                
                                <div x-show="useLoyaltyPoints" class="ml-6 space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <input 
                                            type="range" 
                                            min="0" 
                                            :max="Math.min(userLoyaltyPoints, Math.floor(priceBreakdown.final_price))"
                                            x-model="loyaltyPointsToUse"
                                            @input="updateLoyaltyPointsUsage()"
                                            class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                        >
                                        <span class="text-sm font-medium text-gray-700 min-w-[60px]" x-text="loyaltyPointsToUse + ' poäng'"></span>
                                    </div>
                                    
                                    <div class="text-xs text-gray-500">
                                        <span x-text="'Värde: ' + formatPrice(loyaltyPointsToUse) + ' SEK'"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div x-show="useLoyaltyPoints && loyaltyPointsToUse > 0" class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
                                <span class="text-gray-600 font-medium">⭐ Lojalitetspoäng</span>
                                <span class="font-semibold text-blue-600" x-text="'-' + formatPrice(loyaltyPointsToUse)"></span>
                            </div>
                        </div>

                        <div x-show="priceBreakdown.rot_deduction > 0" class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-600 font-medium">💚 ROT-avdrag</span>
                            <span class="font-semibold text-green-600" x-text="'-' + formatPrice(priceBreakdown.rot_deduction)"></span>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-gradient-to-r from-green-100 to-emerald-100 rounded-xl p-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-green-900">💰 Totalt</span>
                        <span class="text-2xl font-bold text-green-900" x-text="formatPrice(priceBreakdown.final_price)"></span>
                        </div>
                    <div class="text-xs text-green-700 mt-1">inkl. alla tillägg, rabatter och moms</div>
                    </div>

                <div class="mt-3 text-sm text-gray-700 space-y-1">
                    <div class="flex justify-between">
                        <span>Delsumma (exkl. moms)</span>
                        <span x-text="formatPrice(priceBreakdown.final_price - priceBreakdown.tax_amount)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Moms (<span x-text="priceBreakdown.tax_rate"></span>%)</span>
                        <span x-text="formatPrice(priceBreakdown.tax_amount)"></span>
                    </div>
                </div>

                    <p class="text-xs text-gray-500 text-center mt-3 italic">💡 Uppdateras automatiskt</p>
                </div>

                <!-- Collapsed View (when showPriceDetails is false) -->
                <div x-show="!showPriceDetails" class="p-4">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-1">Klicka ▼ för detaljer</div>
                        <div class="inline-flex items-center gap-2 text-xs text-gray-500">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            <span>Priset uppdateras live</span>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- International Telephone Input -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<!-- Google Maps Places API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHcc3WXJfdZiTiZNQcmoPQZexWScR_aLo&libraries=places&callback=initGoogleMaps&loading=async" async defer></script>

<script>
// Global callback for Google Maps
window.initGoogleMaps = function() {
    console.log('✅ Google Maps API loaded successfully');
    
    // Wait a bit for DOM to be ready
    setTimeout(() => {
        // Initialize all address fields
        const addressFields = document.querySelectorAll('input[id^="address_"]');
        
        if (addressFields.length === 0) {
            console.log('ℹ️ No address fields found yet (will initialize when step 2 is reached)');
        }
        
        addressFields.forEach(field => {
            if (field.dataset.autocompleteInitialized) {
                return; // Already initialized
            }
            
            try {
                const autocomplete = new google.maps.places.Autocomplete(field, {
                    types: ['address'],
                    componentRestrictions: { country: 'se' }, // Restrict to Sweden
                    fields: ['formatted_address', 'address_components']
                });
                
                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (place.formatted_address) {
                        field.value = place.formatted_address;
                        // Trigger Alpine.js update
                        field.dispatchEvent(new Event('input', { bubbles: true }));
                        console.log('📍 Address selected:', place.formatted_address);
                    }
                });
                
                field.dataset.autocompleteInitialized = 'true';
                console.log('✅ Google autocomplete initialized for:', field.id);
            } catch (error) {
                console.error('❌ Error initializing autocomplete for', field.id, error);
            }
        });
    }, 500);
};

// Initialize address fields when stepping to form fields (Step 2)
document.addEventListener('alpine:init', () => {
    // Add observer for dynamically loaded address fields
    const observer = new MutationObserver((mutations) => {
        if (typeof google !== 'undefined' && google.maps && google.maps.places) {
            const addressFields = document.querySelectorAll('input[id^="address_"]:not([data-autocomplete-initialized])');
            if (addressFields.length > 0) {
                console.log('🔄 Initializing address autocomplete for new fields');
                window.initGoogleMaps();
            }
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

// Fallback if Google Maps is already loaded
if (typeof google !== 'undefined' && google.maps && google.maps.places) {
    console.log('Google Maps already loaded, initializing...');
    initGoogleMaps();
}
</script>

<!-- multiStepForm function now defined inline above the form for earlier availability -->
@endpush
