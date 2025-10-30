/**
 * Bitra Analytics Tracking
 * Comprehensive analytics tracking for the Bitra platform
 */

class BitraAnalytics {
    constructor() {
        this.sessionId = this.getSessionId();
        this.startTime = Date.now();
        this.scrollDepth = 0;
        this.clickedElements = [];
        this.formInteractions = [];
        this.isTrackingEnabled = this.checkTrackingConsent();
        
        this.init();
    }
    
    init() {
        if (!this.isTrackingEnabled) {
            return;
        }
        
        this.trackPageView();
        this.trackScrollDepth();
        this.trackClicks();
        this.trackFormInteractions();
        this.trackTimeOnPage();
        this.trackConversions();
        
        // Track page unload
        window.addEventListener('beforeunload', () => {
            this.trackTimeOnPage();
        });
    }
    
    getSessionId() {
        let sessionId = sessionStorage.getItem('bitra_session_id');
        if (!sessionId) {
            sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('bitra_session_id', sessionId);
        }
        return sessionId;
    }
    
    checkTrackingConsent() {
        // Check if user has consented to tracking
        const consent = localStorage.getItem('bitra_analytics_consent');
        return consent === 'true' || consent === null; // Default to true if no preference set
    }
    
    trackPageView() {
        const data = {
            session_id: this.sessionId,
            page_url: window.location.href,
            page_title: document.title,
            referrer: document.referrer,
            user_agent: navigator.userAgent,
            screen_resolution: screen.width + 'x' + screen.height,
            device_type: this.getDeviceType(),
            browser: this.getBrowser(),
            os: this.getOS(),
            utm_source: this.getUrlParameter('utm_source'),
            utm_medium: this.getUrlParameter('utm_medium'),
            utm_campaign: this.getUrlParameter('utm_campaign'),
            utm_term: this.getUrlParameter('utm_term'),
            utm_content: this.getUrlParameter('utm_content'),
            custom_events: this.getCustomEvents()
        };
        
        this.sendData('/api/analytics/track-page-view', data);
    }
    
    trackScrollDepth() {
        let maxScroll = 0;
        
        const trackScroll = () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = Math.round((scrollTop / docHeight) * 100);
            
            if (scrollPercent > maxScroll) {
                maxScroll = scrollPercent;
                this.scrollDepth = maxScroll;
                
                // Send scroll depth updates at key milestones
                if ([25, 50, 75, 90, 100].includes(scrollPercent)) {
                    this.sendData('/api/analytics/track-scroll', {
                        session_id: this.sessionId,
                        scroll_depth: scrollPercent,
                        page_url: window.location.href
                    });
                }
            }
        };
        
        window.addEventListener('scroll', this.throttle(trackScroll, 1000));
    }
    
    trackClicks() {
        document.addEventListener('click', (event) => {
            const element = event.target;
            const elementData = {
                tag: element.tagName.toLowerCase(),
                id: element.id,
                class: element.className,
                text: element.textContent?.substring(0, 100),
                href: element.href,
                type: element.type,
                name: element.name,
                value: element.value
            };
            
            this.clickedElements.push({
                ...elementData,
                timestamp: Date.now(),
                page_url: window.location.href
            });
            
            // Send click data in batches
            if (this.clickedElements.length >= 5) {
                this.sendData('/api/analytics/track-clicks', {
                    session_id: this.sessionId,
                    clicked_elements: this.clickedElements
                });
                this.clickedElements = [];
            }
        });
    }
    
    trackFormInteractions() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Track form field interactions
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    this.trackFormInteraction(form, input, 'focus');
                });
                
                input.addEventListener('blur', () => {
                    this.trackFormInteraction(form, input, 'blur');
                });
                
                input.addEventListener('change', () => {
                    this.trackFormInteraction(form, input, 'change');
                });
            });
            
            // Track form submissions
            form.addEventListener('submit', (event) => {
                this.trackFormInteraction(form, null, 'submit');
            });
        });
    }
    
    trackFormInteraction(form, input, action) {
        const interaction = {
            form_id: form.id || 'unnamed',
            form_action: form.action,
            form_method: form.method,
            field_name: input?.name,
            field_type: input?.type,
            field_value: input?.value?.substring(0, 100),
            action: action,
            timestamp: Date.now(),
            page_url: window.location.href
        };
        
        this.formInteractions.push(interaction);
        
        // Send form interaction data
        this.sendData('/api/analytics/track-form-interaction', {
            session_id: this.sessionId,
            form_interaction: interaction
        });
    }
    
    trackTimeOnPage() {
        const timeOnPage = Math.round((Date.now() - this.startTime) / 1000);
        
        this.sendData('/api/analytics/track-time', {
            session_id: this.sessionId,
            time_on_page: timeOnPage,
            page_url: window.location.href
        });
    }
    
    trackConversions() {
        // Track booking conversions
        const bookingButtons = document.querySelectorAll('[data-conversion="booking"]');
        bookingButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.trackConversion('booking', this.getConversionValue());
            });
        });
        
        // Track contact conversions
        const contactButtons = document.querySelectorAll('[data-conversion="contact"]');
        contactButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.trackConversion('contact', 0);
            });
        });
        
        // Track form submissions as conversions
        const forms = document.querySelectorAll('form[data-conversion]');
        forms.forEach(form => {
            form.addEventListener('submit', () => {
                const conversionType = form.dataset.conversion;
                const conversionValue = form.dataset.conversionValue || 0;
                this.trackConversion(conversionType, parseFloat(conversionValue));
            });
        });
    }
    
    trackConversion(type, value) {
        this.sendData('/api/analytics/track-conversion', {
            session_id: this.sessionId,
            conversion_type: type,
            conversion_value: value,
            page_url: window.location.href
        });
    }
    
    trackKeyword(keyword, position = null) {
        this.sendData('/api/analytics/track-keyword', {
            session_id: this.sessionId,
            keyword: keyword,
            position: position,
            page_url: window.location.href
        });
    }
    
    getDeviceType() {
        const width = window.innerWidth;
        if (width < 768) return 'mobile';
        if (width < 1024) return 'tablet';
        return 'desktop';
    }
    
    getBrowser() {
        const ua = navigator.userAgent;
        if (ua.includes('Chrome')) return 'Chrome';
        if (ua.includes('Firefox')) return 'Firefox';
        if (ua.includes('Safari')) return 'Safari';
        if (ua.includes('Edge')) return 'Edge';
        return 'Unknown';
    }
    
    getOS() {
        const ua = navigator.userAgent;
        if (ua.includes('Windows')) return 'Windows';
        if (ua.includes('Mac')) return 'macOS';
        if (ua.includes('Linux')) return 'Linux';
        if (ua.includes('Android')) return 'Android';
        if (ua.includes('iOS')) return 'iOS';
        return 'Unknown';
    }
    
    getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
    
    getCustomEvents() {
        return {
            page_load_time: Date.now() - this.startTime,
            viewport_width: window.innerWidth,
            viewport_height: window.innerHeight,
            language: navigator.language,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
        };
    }
    
    getConversionValue() {
        // Try to extract conversion value from page
        const priceElement = document.querySelector('[data-price]');
        if (priceElement) {
            return parseFloat(priceElement.dataset.price) || 0;
        }
        
        // Try to extract from form
        const priceInput = document.querySelector('input[name*="price"], input[name*="cost"]');
        if (priceInput) {
            return parseFloat(priceInput.value) || 0;
        }
        
        return 0;
    }
    
    sendData(url, data) {
        if (!this.isTrackingEnabled) return;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        }).catch(error => {
            console.warn('Analytics tracking error:', error);
        });
    }
    
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    // Public methods for external use
    setConsent(consent) {
        localStorage.setItem('bitra_analytics_consent', consent.toString());
        this.isTrackingEnabled = consent;
    }
    
    trackCustomEvent(eventName, eventData = {}) {
        this.sendData('/api/analytics/track-custom-event', {
            session_id: this.sessionId,
            event_name: eventName,
            event_data: eventData,
            page_url: window.location.href
        });
    }
}

// Initialize analytics when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.bitraAnalytics = new BitraAnalytics();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BitraAnalytics;
}
