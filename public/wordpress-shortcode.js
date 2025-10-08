(function() {
    'use strict';

    const script = document.currentScript;
    const formToken = script.getAttribute('data-form-token');
    const container = document.getElementById('bitra-form-' + formToken);

    if (!container || !formToken) {
        console.error('Bitra Forms: Container or form token not found');
        return;
    }

    // Fetch and inject form HTML
    fetch(`/api/public/form/${formToken}/html`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load form');
            }
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            initializeForm(container);
        })
        .catch(error => {
            console.error('Bitra Forms error:', error);
            container.innerHTML = '<p style="color: red;">Kunde inte ladda formuläret. Försök igen senare.</p>';
        });

    function initializeForm(container) {
        // Initialize Alpine.js if available
        if (window.Alpine) {
            window.Alpine.initTree(container);
        }

        // Add form submission handler
        const form = container.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Add loading state
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span>Skickar...</span>';
                }
            });
        }
    }
})();

