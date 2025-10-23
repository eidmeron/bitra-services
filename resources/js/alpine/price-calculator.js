export default (serviceId, cityId, formId = null, userLoyaltyPoints = 0) => ({
    serviceId: serviceId,
    cityId: cityId,
    formId: formId,
    formData: {},
    bookingType: 'one_time',
    subscriptionFrequency: 'weekly',
    priceBreakdown: {
        base_price: 0,
        variable_additions: 0,
        subscription_multiplier: 1,
        city_multiplier: 1,
        subtotal: 0,
        rot_deduction: 0,
        discount_amount: 0,
        loyalty_points_discount: 0,
        final_price: 0,
        breakdown: []
    },
    loading: false,
    applyRot: false,
    loyaltyPoints: userLoyaltyPoints,
    loyaltyPointsToUse: 0,
    loyaltyPointsEnabled: false,
    
    // Company selection
    companies: [],
    selectedCompanyId: null,
    autoSelectCompany: false,
    loadingCompanies: false,
    showCompanies: window.bookingSettings?.show_companies ?? true,
    allowCompanySelection: window.bookingSettings?.allow_company_selection ?? true,

    async init() {
        // Initial price calculation
        this.calculatePrice();
        
        // Fetch companies if settings allow
        if (this.showCompanies && this.serviceId && this.cityId) {
            await this.fetchCompanies();
        }
        
        // Watch for city/service changes to update companies
        this.$watch('cityId', () => {
            if (this.showCompanies) {
                this.fetchCompanies();
            }
        });
    },
    
    async fetchCompanies() {
        if (!this.showCompanies || !this.serviceId || !this.cityId) {
            this.companies = [];
            return;
        }
        
        this.loadingCompanies = true;
        
        try {
            const response = await window.axios.get('/api/companies/available', {
                params: {
                    service_id: this.serviceId,
                    city_id: this.cityId
                }
            });
            
            if (response.data.success) {
                this.companies = response.data.companies;
                
                // Auto-select if only one company available
                if (this.companies.length === 1) {
                    this.selectedCompanyId = this.companies[0].id;
                    this.autoSelectCompany = false;
                }
            }
        } catch (error) {
            console.error('Error fetching companies:', error);
            this.companies = [];
        } finally {
            this.loadingCompanies = false;
        }
    },

    async calculatePrice() {
        if (!this.serviceId || !this.cityId) {
            return;
        }

        this.loading = true;

        try {
            const response = await window.axios.post('/api/calculate-price', {
                service_id: this.serviceId,
                city_id: this.cityId,
                form_id: this.formId,
                form_data: this.formData,
                apply_rot: this.applyRot,
                booking_type: this.bookingType,
                subscription_frequency: this.subscriptionFrequency,
                loyalty_points_to_use: this.loyaltyPointsToUse
            });

            this.priceBreakdown = response.data;
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

    updateCity(cityId) {
        this.cityId = cityId;
        this.calculatePrice();
    },

    updateSubscriptionType(type, frequency) {
        this.bookingType = type;
        if (frequency) {
            this.subscriptionFrequency = frequency;
        }
        this.calculatePrice();
    },

    toggleRot() {
        this.applyRot = !this.applyRot;
        this.calculatePrice();
    },

    toggleLoyaltyPoints() {
        this.loyaltyPointsEnabled = !this.loyaltyPointsEnabled;
        if (this.loyaltyPointsEnabled) {
            // Use all available points by default
            this.loyaltyPointsToUse = this.loyaltyPoints;
        } else {
            this.loyaltyPointsToUse = 0;
        }
        this.calculatePrice();
    },

    updateLoyaltyPointsToUse(points) {
        this.loyaltyPointsToUse = Math.min(Math.max(0, points), this.loyaltyPoints);
        this.calculatePrice();
    },

    formatPrice(price) {
        return new Intl.NumberFormat('sv-SE', {
            style: 'currency',
            currency: 'SEK',
            minimumFractionDigits: 2
        }).format(price);
    },
    
    // Company selection methods
    selectCompany(companyId) {
        if (!this.allowCompanySelection && !this.autoSelectCompany) return;
        
        this.selectedCompanyId = companyId;
        this.autoSelectCompany = false;
    },
    
    toggleAutoSelect() {
        this.autoSelectCompany = !this.autoSelectCompany;
        if (this.autoSelectCompany) {
            this.selectedCompanyId = null;
        }
    },
    
    // Get form data for submission including company selection
    getFormData() {
        const data = {
            service_id: this.serviceId,
            city_id: this.cityId,
            form_id: this.formId,
            form_data: this.formData,
            apply_rot: this.applyRot,
            booking_type: this.bookingType,
            subscription_frequency: this.subscriptionFrequency,
            loyalty_points_to_use: this.loyaltyPointsToUse,
        };
        
        // Add company selection
        if (this.showCompanies) {
            data.company_id = this.selectedCompanyId;
            data.auto_select_company = this.autoSelectCompany;
        }
        
        return data;
    }
});

