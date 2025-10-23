{{-- Company Selector Component --}}
@props(['priceCalculator' => 'priceCalculator'])

<div x-show="showCompanies && companies.length > 0" 
     x-transition
     class="space-y-4">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border-2 border-purple-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Välj Tjänsteleverantör
                </h3>
                <p class="text-sm text-gray-600 mt-1">
                    <span x-text="companies.length"></span> tillgängliga företag sorterade efter betyg
                </p>
            </div>
            
            <div x-show="loadingCompanies" class="flex items-center">
                <svg class="animate-spin h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-2 text-sm text-gray-600">Laddar...</span>
            </div>
        </div>
    </div>

    <!-- Let Bitra Choose Option -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-300 rounded-lg p-4">
        <label class="flex items-start cursor-pointer group">
            <input 
                type="checkbox" 
                x-model="autoSelectCompany"
                @change="toggleAutoSelect()"
                class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <div class="ml-3">
                <span class="font-semibold text-gray-900 group-hover:text-blue-600 transition">
                    🎯 Låt Bitra välja åt mig
                </span>
                <p class="text-sm text-gray-600 mt-1">
                    Vi väljer automatiskt det högst betygsatta företaget för dig
                </p>
            </div>
        </label>
    </div>

    <!-- Company List -->
    <div x-show="!autoSelectCompany" x-transition class="space-y-3">
        <template x-for="company in companies" :key="company.id">
            <div 
                @click="selectCompany(company.id)"
                :class="{ 
                    'border-purple-600 bg-purple-50': selectedCompanyId === company.id,
                    'border-gray-200 hover:border-purple-300 hover:bg-gray-50': selectedCompanyId !== company.id 
                }"
                class="border-2 rounded-lg p-4 cursor-pointer transition-all duration-200">
                
                <div class="flex items-start space-x-4">
                    <!-- Company Logo -->
                    <div class="flex-shrink-0">
                        <template x-if="company.logo">
                            <img :src="company.logo" :alt="company.name" class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200">
                        </template>
                        <template x-if="!company.logo">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                <span class="text-2xl font-bold text-white" x-text="company.name.charAt(0)"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Company Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-bold text-gray-900" x-text="company.name"></h4>
                            <div 
                                x-show="selectedCompanyId === company.id"
                                class="flex-shrink-0 w-6 h-6 bg-purple-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="flex items-center mt-1">
                            <div class="flex items-center">
                                <template x-for="i in 5">
                                    <svg 
                                        :class="i <= company.rating ? 'text-yellow-400' : 'text-gray-300'"
                                        class="w-5 h-5" 
                                        fill="currentColor" 
                                        viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </template>
                            </div>
                            <span class="ml-2 text-sm font-semibold text-gray-700" x-text="company.rating"></span>
                            <span class="ml-1 text-sm text-gray-500" x-text="'(' + company.review_count + ' recensioner)'"></span>
                        </div>

                        <!-- Description -->
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2" x-text="company.description || 'Professionell tjänsteleverantör'"></p>

                        <!-- Recent Reviews -->
                        <div x-show="company.recent_reviews && company.recent_reviews.length > 0" class="mt-3 space-y-2">
                            <template x-for="review in company.recent_reviews">
                                <div class="bg-white p-2 rounded border border-gray-200 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-900" x-text="review.user_name"></span>
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            <span class="ml-1 text-gray-600" x-text="review.rating"></span>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 mt-1" x-text="review.comment"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="company_id" :value="selectedCompanyId">
    <input type="hidden" name="auto_select_company" :value="autoSelectCompany ? '1' : '0'">
</div>

<script>
    // Pass settings to window for Alpine components
    window.bookingSettings = {
        show_companies: {{ setting('booking_show_companies', true) ? 'true' : 'false' }},
        allow_company_selection: {{ setting('booking_allow_company_selection', true) ? 'true' : 'false' }},
        auto_assign: {{ setting('booking_auto_assign', false) ? 'true' : 'false' }}
    };
</script>

