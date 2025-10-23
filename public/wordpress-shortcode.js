/**
 * Bitra Booking Forms - WordPress Shortcode Integration
 * Version: 1.0.0
 * 
 * This script handles the embedding of Bitra booking forms into external websites
 * Usage: <script src="https://bitra.se/wordpress-shortcode.js" data-form-token="YOUR_TOKEN"></script>
 */

(function() {
    'use strict';
    
    // Configuration
    const BITRA_API_URL = 'https://bitra.se';
    const DEFAULT_HEIGHT = '1200px';
    
    // Get all script tags with data-form-token
    const scripts = document.querySelectorAll('script[data-form-token]');
    
    scripts.forEach(function(script) {
        const token = script.getAttribute('data-form-token');
        const height = script.getAttribute('data-height') || DEFAULT_HEIGHT;
        const type = script.getAttribute('data-type') || 'iframe';
        
        if (!token) {
            console.error('Bitra Form Error: data-form-token attribute is required');
            return;
        }
        
        // Find the target container
        const containerId = script.getAttribute('data-container') || 'bitra-form-' + token;
        let container = document.getElementById(containerId);
        
        // If no container found, create one after the script tag
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.className = 'bitra-booking-form-container';
            script.parentNode.insertBefore(container, script.nextSibling);
        }
        
        // Show loading state
        container.innerHTML = '<div class="bitra-loading" style="text-align: center; padding: 40px; color: #666;">⏳ Laddar formulär...</div>';
        
        if (type === 'ajax') {
            loadAjaxForm(container, token);
        } else {
            loadIframeForm(container, token, height);
        }
    });
    
    /**
     * Load form via AJAX (embedded directly)
     */
    function loadAjaxForm(container, token) {
        const url = BITRA_API_URL + '/api/public/form/' + encodeURIComponent(token) + '/html';
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.text();
            })
            .then(html => {
                container.innerHTML = html;
                
                // Add some basic styling for embedded forms
                const style = document.createElement('style');
                style.textContent = `
                    .bitra-booking-form-container {
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                        max-width: 100%;
                        margin: 0 auto;
                    }
                    
                    .bitra-booking-form-container .bitra-embed-form {
                        background: #f8fafc;
                        border-radius: 12px;
                        padding: 20px;
                    }
                    
                    .bitra-booking-form-container .form-container {
                        background: white;
                        border-radius: 16px;
                        padding: 32px;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                    }
                    
                    .bitra-booking-form-container .btn-primary {
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
                    
                    .bitra-booking-form-container .btn-primary:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
                    }
                    
                    .bitra-booking-form-container .form-group {
                        margin-bottom: 24px;
                    }
                    
                    .bitra-booking-form-container .form-label {
                        display: block;
                        font-weight: 600;
                        margin-bottom: 8px;
                        color: #374151;
                    }
                    
                    .bitra-booking-form-container .form-input,
                    .bitra-booking-form-container .form-select,
                    .bitra-booking-form-container .form-textarea {
                        width: 100%;
                        padding: 12px 16px;
                        border: 2px solid #d1d5db;
                        border-radius: 8px;
                        font-size: 16px;
                        transition: border-color 0.3s ease;
                        box-sizing: border-box;
                    }
                    
                    .bitra-booking-form-container .form-input:focus,
                    .bitra-booking-form-container .form-select:focus,
                    .bitra-booking-form-container .form-textarea:focus {
                        outline: none;
                        border-color: #3b82f6;
                        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
                    }
                    
                    .bitra-booking-form-container .form-textarea {
                        min-height: 120px;
                        resize: vertical;
                    }
                    
                    .bitra-booking-form-container .pricing-summary {
                        background: #f1f5f9;
                        border-radius: 12px;
                        padding: 24px;
                        margin: 24px 0;
                    }
                    
                    .bitra-booking-form-container .pricing-item {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 8px;
                    }
                    
                    .bitra-booking-form-container .pricing-total {
                        font-weight: 700;
                        font-size: 18px;
                        color: #1e40af;
                        border-top: 2px solid #cbd5e1;
                        padding-top: 12px;
                        margin-top: 12px;
                    }
                    
                    .bitra-booking-form-container .error-message {
                        color: #dc2626;
                        font-size: 14px;
                        margin-top: 4px;
                    }
                    
                    .bitra-booking-form-container .success-message {
                        color: #059669;
                        font-size: 14px;
                        margin-top: 4px;
                    }
                    
                    @media (max-width: 768px) {
                        .bitra-booking-form-container .bitra-embed-form {
                            padding: 10px;
                        }
                        
                        .bitra-booking-form-container .form-container {
                            padding: 20px;
                        }
                    }
                `;
                document.head.appendChild(style);
            })
            .catch(error => {
                console.error('Bitra Form Error:', error);
                container.innerHTML = `
                    <div class="bitra-error" style="text-align: center; padding: 40px; color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;">
                        <h3>❌ Formulär kunde inte laddas</h3>
                        <p>Ett fel uppstod vid laddning av formuläret. Kontrollera att:</p>
                        <ul style="text-align: left; margin: 20px 0;">
                            <li>Formuläret är aktivt</li>
                            <li>Token är korrekt</li>
                            <li>Internetanslutningen fungerar</li>
                        </ul>
                        <p><strong>Fel:</strong> ${error.message}</p>
                    </div>
                `;
            });
    }
    
    /**
     * Load form via iframe
     */
    function loadIframeForm(container, token, height) {
        const url = BITRA_API_URL + '/form/' + encodeURIComponent(token);
        
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.width = '100%';
        iframe.height = height;
        iframe.frameBorder = '0';
        iframe.scrolling = 'auto';
        iframe.className = 'bitra-booking-iframe';
        iframe.allowFullscreen = true;
        iframe.style.border = 'none';
        iframe.style.borderRadius = '8px';
        
        // Handle iframe load events
        iframe.onload = function() {
            console.log('Bitra form iframe loaded successfully');
        };
        
        iframe.onerror = function() {
            container.innerHTML = `
                <div class="bitra-error" style="text-align: center; padding: 40px; color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;">
                    <h3>❌ Formulär kunde inte laddas</h3>
                    <p>Ett fel uppstod vid laddning av formuläret. Kontrollera att formuläret är aktivt och token är korrekt.</p>
                </div>
            `;
        };
        
        container.innerHTML = '';
        container.appendChild(iframe);
    }
    
    // Expose global functions for manual integration
    window.BitraForms = {
        loadForm: function(containerId, token, options = {}) {
            const container = document.getElementById(containerId);
            if (!container) {
                console.error('Bitra Form Error: Container with ID "' + containerId + '" not found');
                return;
            }
            
            const type = options.type || 'iframe';
            const height = options.height || DEFAULT_HEIGHT;
            
            if (type === 'ajax') {
                loadAjaxForm(container, token);
            } else {
                loadIframeForm(container, token, height);
            }
        }
    };
    
})();