export default (serviceId, cityId) => ({
    serviceId: serviceId,
    cityId: cityId,
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

    init() {
        // Initial price calculation
        this.calculatePrice();
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
                form_data: this.formData,
                apply_rot: this.applyRot
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

    toggleRot() {
        this.applyRot = !this.applyRot;
        this.calculatePrice();
    },

    formatPrice(price) {
        return new Intl.NumberFormat('sv-SE', {
            style: 'currency',
            currency: 'SEK',
            minimumFractionDigits: 2
        }).format(price);
    }
});

