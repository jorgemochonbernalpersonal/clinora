{{-- Google Consent Mode v2 Configuration --}}
<script>
    // Initialize Consent Mode BEFORE gtag loads
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    
    // Default consent state (denied until user accepts)
    gtag('consent', 'default', {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied',
        'functionality_storage': 'granted', // Essential cookies
        'personalization_storage': 'denied',
        'security_storage': 'granted' // Essential for security
    });
    
    // Check if user has already made a choice
    const cookieConsent = localStorage.getItem('cookie_consent');
    if (cookieConsent === 'accepted') {
        gtag('consent', 'update', {
            'analytics_storage': 'granted',
            'ad_storage': 'denied', // Still deny ads (not using them)
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'personalization_storage': 'granted'
        });
    }
</script>

{{-- Load Google Analytics AFTER Consent Mode is configured --}}
<script async src="https://www.googletagmanager.com/gtag/js?id=G-TJ20C7QSTH"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-TJ20C7QSTH', {
        'anonymize_ip': true, // Anonymize IPs for privacy
        'cookie_flags': 'SameSite=None;Secure' // Secure cookies
    });
</script>

<div x-data="{ 
        cookieBanner: !localStorage.getItem('cookie_consent'),
        acceptCookies() {
            // Update consent
            gtag('consent', 'update', {
                'analytics_storage': 'granted',
                'personalization_storage': 'granted'
            });
            
            // Save preference
            localStorage.setItem('cookie_consent', 'accepted');
            this.cookieBanner = false;
            
            // Send event to Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'cookie_consent_granted', {
                    'event_category': 'consent',
                    'event_label': 'accepted'
                });
            }
        },
        rejectCookies() {
            // Keep consent denied (already set in default)
            gtag('consent', 'update', {
                'analytics_storage': 'denied',
                'personalization_storage': 'denied'
            });
            
            // Save preference
            localStorage.setItem('cookie_consent', 'rejected');
            this.cookieBanner = false;
            
            // Send event (will be anonymized)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'cookie_consent_rejected', {
                    'event_category': 'consent',
                    'event_label': 'rejected'
                });
            }
        }
    }" 
    x-show="cookieBanner" 
    x-cloak
    class="fixed bottom-0 inset-x-0 pb-4 px-4 z-50"
    style="display: none;">
    <div class="max-w-7xl mx-auto">
        <div class="bg-gray-900 backdrop-blur-xl bg-opacity-95 rounded-lg shadow-2xl p-6 border border-gray-700">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-primary-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-white text-sm font-medium mb-1">
                                🍪 Utilizamos cookies para mejorar tu experiencia
                            </p>
                            <p class="text-gray-300 text-xs">
                                Utilizamos cookies propias y de terceros para analíticas y mejorar tu experiencia. 
                                Al aceptar, permites el uso de cookies de análisis. Al rechazar, solo usaremos cookies esenciales.
                                Lee nuestra 
                                <a href="{{ route('legal.cookies') }}" class="underline text-primary-400 hover:text-primary-300">Política de Cookies</a> y 
                                <a href="{{ route('legal.privacy') }}" class="underline text-primary-400 hover:text-primary-300">Política de Privacidad</a>.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 flex-shrink-0">
                    <button @click="rejectCookies()"
                            class="px-4 py-2 text-sm text-gray-300 hover:text-white border border-gray-600 hover:border-gray-500 rounded-lg transition-colors">
                        Rechazar
                    </button>
                    <button @click="acceptCookies()"
                            class="px-6 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-sm font-medium transition-colors shadow-lg">
                        Aceptar Todas
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

