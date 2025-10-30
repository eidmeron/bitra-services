<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->form_name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Select2 for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- International Telephone Input -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    
    <style>
        /* Custom styles for embedded form */
        .bitra-embed-form {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .bitra-embed-form .form-container {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .bitra-embed-form .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .bitra-embed-form .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }
        
        .bitra-embed-form .form-group {
            margin-bottom: 24px;
        }
        
        .bitra-embed-form .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }
        
        .bitra-embed-form .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .bitra-embed-form .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .bitra-embed-form .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            min-height: 120px;
            resize: vertical;
        }
        
        .bitra-embed-form .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            background: white;
        }
        
        .bitra-embed-form .pricing-summary {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        
        .bitra-embed-form .pricing-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .bitra-embed-form .pricing-total {
            font-weight: 700;
            font-size: 18px;
            color: #1e40af;
            border-top: 2px solid #cbd5e1;
            padding-top: 12px;
            margin-top: 12px;
        }
        
        .bitra-embed-form .error-message {
            color: #dc2626;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .bitra-embed-form .success-message {
            color: #059669;
            font-size: 14px;
            margin-top: 4px;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .bitra-embed-form {
                padding: 10px;
            }
            
            .bitra-embed-form .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="bitra-embed-form">
        <div class="form-container">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $form->form_name }}</h1>
                @if($form->description)
                    <p class="text-gray-600">{{ $form->description }}</p>
                @endif
            </div>
            
            <form id="booking-form" method="POST" action="{{ route('booking.submit', $form->public_token) }}">
                @csrf
                
                <!-- Service Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-2">📋 Tjänstinformation</h3>
                    <p class="text-blue-800"><strong>{{ $form->service->name }}</strong></p>
                    @if($form->service->description)
                        <p class="text-blue-700 text-sm mt-1">{{ $form->service->description }}</p>
                    @endif
                </div>
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="form-group">
                        <label class="form-label" for="customer_name">Namn *</label>
                        <input type="text" id="customer_name" name="customer_name" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="customer_email">E-post *</label>
                        <input type="email" id="customer_email" name="customer_email" class="form-input" required>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="form-group">
                        <label class="form-label" for="customer_phone">Telefon *</label>
                        <input type="tel" id="customer_phone" name="customer_phone" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="city_id">Stad *</label>
                        <select id="city_id" name="city_id" class="form-select" required>
                            <option value="">Välj stad</option>
                            @foreach(\App\Models\City::where('status', 'active')->orderBy('name')->get() as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Message -->
                <div class="form-group mb-6">
                    <label class="form-label" for="customer_message">Meddelande (valfritt)</label>
                    <textarea id="customer_message" name="customer_message" class="form-textarea" placeholder="Beskriv dina behov eller frågor..."></textarea>
                </div>
                
                <!-- Pricing Summary -->
                <div class="pricing-summary" id="pricing-summary" style="display: none;">
                    <h3 class="font-semibold text-gray-900 mb-4">💰 Prisuppställning</h3>
                    <div id="pricing-details"></div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-primary">
                    <span id="submit-text">🚀 Skicka förfrågan</span>
                    <span id="loading-text" style="display: none;">⏳ Skickar...</span>
                </button>
                
                <!-- Error/Success Messages -->
                <div id="form-messages" class="mt-4"></div>
            </form>
        </div>
    </div>
    
    <script>
        // Form submission handling
        document.getElementById('booking-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.querySelector('.btn-primary');
            const submitText = document.getElementById('submit-text');
            const loadingText = document.getElementById('loading-text');
            const messagesDiv = document.getElementById('form-messages');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.style.display = 'none';
            loadingText.style.display = 'inline';
            
            // Clear previous messages
            messagesDiv.innerHTML = '';
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Redirect to success page
                    window.location.href = data.redirect_url;
                } else {
                    // Show error messages
                    let errorHtml = '<div class="error-message">❌ ' + (data.message || 'Ett fel uppstod. Försök igen.') + '</div>';
                    
                    if (data.errors) {
                        errorHtml += '<ul class="mt-2">';
                        for (const [field, errors] of Object.entries(data.errors)) {
                            errors.forEach(error => {
                                errorHtml += '<li class="text-sm text-red-600">• ' + error + '</li>';
                            });
                        }
                        errorHtml += '</ul>';
                    }
                    
                    messagesDiv.innerHTML = errorHtml;
                }
            } catch (error) {
                console.error('Form submission error:', error);
                messagesDiv.innerHTML = '<div class="error-message">❌ Ett fel uppstod. Kontrollera din internetanslutning och försök igen.</div>';
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                submitText.style.display = 'inline';
                loadingText.style.display = 'none';
            }
        });
        
        // Price calculation on city change
        document.getElementById('city_id').addEventListener('change', async function() {
            const cityId = this.value;
            const pricingSummary = document.getElementById('pricing-summary');
            const pricingDetails = document.getElementById('pricing-details');
            
            if (!cityId) {
                pricingSummary.style.display = 'none';
                return;
            }
            
            try {
                const response = await fetch('/api/calculate-price', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        service_id: {{ $form->service->id }},
                        city_id: cityId,
                        booking_type: 'one_time'
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    pricingDetails.innerHTML = `
                        <div class="pricing-item">
                            <span>Grundpris:</span>
                            <span>${data.pricing.base_price.toLocaleString('sv-SE')} kr</span>
                        </div>
                        <div class="pricing-item">
                            <span>Stadstillägg (${data.pricing.city_multiplier}x):</span>
                            <span>${data.pricing.city_addition.toLocaleString('sv-SE')} kr</span>
                        </div>
                        <div class="pricing-item">
                            <span>Delsumma (exkl. moms):</span>
                            <span>${data.pricing.subtotal_after_deductions.toLocaleString('sv-SE')} kr</span>
                        </div>
                        <div class="pricing-item">
                            <span>Moms (${data.pricing.tax_rate}%):</span>
                            <span>${data.pricing.tax_amount.toLocaleString('sv-SE')} kr</span>
                        </div>
                        <div class="pricing-total">
                            <span>Totalt (inkl. moms):</span>
                            <span>${data.pricing.final_price.toLocaleString('sv-SE')} kr</span>
                        </div>
                    `;
                    pricingSummary.style.display = 'block';
                }
            } catch (error) {
                console.error('Price calculation error:', error);
            }
        });
    </script>
</body>
</html>
