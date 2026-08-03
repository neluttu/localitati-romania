<script>
    window.cookieConsent = function () {
        return {
            version: {{ config('cookie_consent.version') }},
            storageKey: @json(config('cookie_consent.cookie')),
            lifetimeDays: {{ config('cookie_consent.lifetime_days') }},

            open: false,
            preferencesOpen: false,
            analytics: false,

            init() {
                const saved = this.read();

                if (saved === null) {
                    this.open = true;
                    return;
                }

                // The stored choice was already replayed to the tag in the head,
                // so there is nothing to signal here - only the banner state.
                this.analytics = saved.analytics === true;
            },

            read() {
                const match = document.cookie.match(/(?:^|;\s*)cookie_consent=([^;]*)/);

                if (!match) {
                    return null;
                }

                try {
                    const parsed = JSON.parse(decodeURIComponent(match[1]));

                    // A choice made against a different set of categories is
                    // not a choice about this one.
                    return parsed.v === this.version ? parsed : null;
                } catch (e) {
                    return null;
                }
            },

            save(analytics) {
                const value = {
                    v: this.version,
                    necessary: true,
                    analytics: analytics,
                    at: new Date().toISOString(),
                };

                document.cookie = this.storageKey + '=' + encodeURIComponent(JSON.stringify(value))
                    + ';path=/;max-age=' + (60 * 60 * 24 * this.lifetimeDays)
                    + ';SameSite=Lax'
                    + (location.protocol === 'https:' ? ';Secure' : '');

                this.analytics = analytics;
                this.open = false;
                this.preferencesOpen = false;

                this.signalConsent(analytics);
            },

            /**
             * Tell the tag what was decided. Refusing is signalled just as
             * explicitly as accepting: a visitor who turns analytics back off
             * must stop being measured within the same page view, not on the
             * next reload.
             */
            signalConsent(analytics) {
                if (typeof window.gtag !== 'function') {
                    return;
                }

                window.gtag('consent', 'update', {
                    analytics_storage: analytics ? 'granted' : 'denied',
                });
            },

            acceptAll() { this.save(true); },
            rejectAll() { this.save(false); },
            savePreferences() { this.save(this.analytics); },

            openPreferences() {
                const saved = this.read();
                this.analytics = saved ? saved.analytics === true : false;
                this.preferencesOpen = true;
                this.open = false;
            },

        };
    };
</script>

<div x-data="cookieConsent()" x-init="init()" x-cloak
    x-on:open-cookie-preferences.window="openPreferences()">

    {{-- Banner --}}
    <div x-show="open" x-transition.opacity
        role="dialog" aria-modal="false" aria-label="Preferințe cookies"
        class="fixed bottom-4 left-4 right-4 sm:left-6 sm:right-6 lg:left-auto lg:right-8 lg:max-w-lg z-50">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-5 sm:p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-2">Cookie-uri</h2>
            <p class="text-sm text-gray-600 leading-relaxed mb-4">
                Folosim cookie-uri strict necesare ca autentificarea și formularele să funcționeze. Ne-ar ajuta
                și unele de analiză, ca să înțelegem ce pagini sunt utile - dar numai dacă ești de acord.
                Detalii în <a href="{{ url('/politica-de-cookies') }}" class="underline text-purple-600 hover:text-purple-700">politica de cookies</a>.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <button type="button" x-on:click="acceptAll()"
                    class="px-4 py-2.5 rounded-xl bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 transition-colors">
                    Accept toate
                </button>
                <button type="button" x-on:click="rejectAll()"
                    class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-800 text-sm font-medium hover:bg-gray-200 transition-colors">
                    Refuz toate
                </button>
                <button type="button" x-on:click="openPreferences()"
                    class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">
                    Preferințe
                </button>
            </div>
        </div>
    </div>

    {{-- Preferences --}}
    <div x-show="preferencesOpen" x-transition.opacity
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
        role="dialog" aria-modal="true" aria-label="Setări cookies">

        <div class="absolute inset-0 bg-gray-900/50" x-on:click="preferencesOpen = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg max-h-[85vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Setări cookies</h2>
                <p class="text-sm text-gray-500 mt-1">Alege ce accepți. Poți reveni oricând.</p>
            </div>

            <div class="p-6 space-y-5">
                {{-- Necessary --}}
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Strict necesare</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Sesiunea de autentificare și protecția formularelor. Fără ele site-ul nu funcționează,
                            de aceea nu pot fi dezactivate.
                        </p>
                    </div>
                    <span class="shrink-0 mt-1 inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                        Mereu active
                    </span>
                </div>

                {{-- Analytics --}}
                <div class="flex items-start justify-between gap-4 pt-5 border-t border-gray-100">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Analiză</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Google Analytics, cu adresa IP anonimizată. Ne arată ce pagini sunt folosite,
                            ca să știm ce merită îmbunătățit.
                        </p>
                    </div>
                    <button type="button" role="switch" x-bind:aria-checked="analytics ? 'true' : 'false'"
                        aria-label="Cookie-uri de analiză"
                        x-on:click="analytics = !analytics"
                        x-bind:class="analytics ? 'bg-purple-600' : 'bg-gray-300'"
                        class="shrink-0 mt-1 relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                        <span x-bind:class="analytics ? 'translate-x-6' : 'translate-x-1'"
                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                    </button>
                </div>
            </div>

            <div class="p-6 bg-gray-50 flex flex-col sm:flex-row gap-2">
                <button type="button" x-on:click="savePreferences()"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-purple-600 text-white text-sm font-medium hover:bg-purple-700 transition-colors">
                    Salvează alegerea
                </button>
                <button type="button" x-on:click="acceptAll()"
                    class="flex-1 px-4 py-2.5 rounded-xl bg-gray-100 text-gray-800 text-sm font-medium hover:bg-gray-200 transition-colors">
                    Accept toate
                </button>
            </div>
        </div>
    </div>
</div>
