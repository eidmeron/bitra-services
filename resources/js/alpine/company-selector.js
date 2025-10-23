/**
 * Company Selector Component for Alpine.js
 * Fetches and displays available companies based on service and city selection
 */
export default (serviceId, cityId) => ({
    // State
    loading: false,
    companies: [],
    selectedCompanyId: null,
    autoSelect: false,
    showCompanies: false,
    allowSelection: true,
    
    /**
     * Initialize the component
     */
    async init() {
        // Get settings from window or data attributes
        this.showCompanies = window.bookingSettings?.show_companies ?? true;
        this.allowSelection = window.bookingSettings?.allow_company_selection ?? true;
        
        // Watch for changes in service or city
        this.$watch('serviceId', () => this.fetchCompanies());
        this.$watch('cityId', () => this.fetchCompanies());
        
        // If both service and city are set, fetch companies
        if (serviceId && cityId) {
            await this.fetchCompanies();
        }
    },
    
    /**
     * Fetch available companies
     */
    async fetchCompanies() {
        if (!this.showCompanies || !serviceId || !cityId) {
            this.companies = [];
            return;
        }
        
        this.loading = true;
        
        try {
            const response = await fetch(`/api/companies/available?service_id=${serviceId}&city_id=${cityId}`);
            const data = await response.json();
            
            if (data.success) {
                this.companies = data.companies;
                
                // Auto-select first company if only one available
                if (this.companies.length === 1) {
                    this.selectedCompanyId = this.companies[0].id;
                    this.autoSelect = false;
                }
            }
        } catch (error) {
            console.error('Error fetching companies:', error);
            this.companies = [];
        } finally {
            this.loading = false;
        }
    },
    
    /**
     * Select a company
     */
    selectCompany(companyId) {
        if (!this.allowSelection && !this.autoSelect) return;
        
        this.selectedCompanyId = companyId;
        this.autoSelect = false;
        
        // Dispatch event for other components
        this.$dispatch('company-selected', { companyId });
    },
    
    /**
     * Toggle auto-select (Let Bitra choose)
     */
    toggleAutoSelect() {
        this.autoSelect = !this.autoSelect;
        if (this.autoSelect) {
            this.selectedCompanyId = null;
        }
        
        // Dispatch event
        this.$dispatch('auto-select-changed', { autoSelect: this.autoSelect });
    },
    
    /**
     * Get selected company data for form submission
     */
    getSelectionData() {
        return {
            company_id: this.selectedCompanyId,
            auto_select: this.autoSelect,
        };
    },
    
    /**
     * Check if selection is valid
     */
    isSelectionValid() {
        return this.autoSelect || this.selectedCompanyId !== null || this.companies.length === 0;
    },
});

