/**
 * UTM Tracker - Tracks UTM parameters in checkout
 */
(function() {
    const utmTracker = {
        /**
         * Initialize the tracker
         */
        init: function() {
            this.collectUTMParams();
            this.trackFormSubmissions();
        },
        
        /**
         * Collect UTM parameters from URL
         */
        collectUTMParams: function() {
            const params = new URLSearchParams(window.location.search);
            this.utmParams = {
                utm_source: params.get('utm_source') || this.getCookie('utm_source') || 'direct',
                utm_medium: params.get('utm_medium') || this.getCookie('utm_medium') || 'none',
                utm_campaign: params.get('utm_campaign') || this.getCookie('utm_campaign') || 'none',
                utm_term: params.get('utm_term') || this.getCookie('utm_term') || '',
                utm_content: params.get('utm_content') || this.getCookie('utm_content') || '',
                referrer: document.referrer
            };
            
            // Save as cookies for later reference
            this.setCookie('utm_source', this.utmParams.utm_source, 30);
            this.setCookie('utm_medium', this.utmParams.utm_medium, 30);
            this.setCookie('utm_campaign', this.utmParams.utm_campaign, 30);
            this.setCookie('utm_term', this.utmParams.utm_term, 30);
            this.setCookie('utm_content', this.utmParams.utm_content, 30);
            
            // Log for debugging
            console.log('UTM Tracker initialized with params:', this.utmParams);
        },
        
        /**
         * Track form submissions
         */
        trackFormSubmissions: function() {
            const forms = document.querySelectorAll('form[data-utm-track="true"]');
            
            forms.forEach(form => {
                form.addEventListener('submit', e => {
                    // Don't add fields if they already exist
                    if (!form.querySelector('input[name="utm_source"]')) {
                        // Add UTM fields to the form
                        Object.keys(this.utmParams).forEach(key => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = this.utmParams[key];
                            form.appendChild(input);
                        });
                    }
                });
            });
        },
        
        /**
         * Set a cookie
         */
        setCookie: function(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + "=" + value + "; expires=" + date.toUTCString() + "; path=/";
        },
        
        /**
         * Get a cookie
         */
        getCookie: function(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
    };
    
    // Expose to global scope
    window.utmTracker = utmTracker;
    
    // Auto initialize if the page has forms with data-utm-track="true"
    if (document.querySelector('form[data-utm-track="true"]')) {
        document.addEventListener('DOMContentLoaded', function() {
            utmTracker.init();
        });
    }
})();
