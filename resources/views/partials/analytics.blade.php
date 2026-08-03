@php
    $analyticsId = config('services.google_analytics.id');
    $consentCookie = config('cookie_consent.cookie');
    $consentVersion = config('cookie_consent.version');
@endphp

@if ($analyticsId)
    {{-- Google consent mode v2.

         The tag loads on every page, which is what lets Google's own check
         find it, but it starts with every consent signal denied: no cookies,
         no identifiers, no measurement. Accepting sends an update and only
         then does measurement begin.

         Order is the safeguard here. These defaults must reach the dataLayer
         before gtag.js runs, otherwise the tag measures first and reads the
         defaults second - which is the failure this whole block exists to
         prevent. Keep the inline script above the async one. --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }

        gtag('consent', 'default', {
            ad_storage: 'denied',
            ad_user_data: 'denied',
            ad_personalization: 'denied',
            analytics_storage: 'denied',
            // Hold measurement briefly so a returning visitor's stored consent
            // is applied before the first ping rather than after it.
            wait_for_update: 500
        });

        // A visitor who already accepted should not have to accept again on
        // every page, so the stored choice is replayed before the tag starts.
        (function () {
            var match = document.cookie.match(/(?:^|;\s*){{ $consentCookie }}=([^;]*)/);

            if (!match) {
                return;
            }

            try {
                var saved = JSON.parse(decodeURIComponent(match[1]));

                if (saved.v === {{ $consentVersion }} && saved.analytics === true) {
                    gtag('consent', 'update', { analytics_storage: 'granted' });
                }
            } catch (e) {
                // A malformed cookie stays denied.
            }
        })();

        gtag('js', new Date());
        gtag('config', @json($analyticsId), { anonymize_ip: true });
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analyticsId }}"></script>
@endif
