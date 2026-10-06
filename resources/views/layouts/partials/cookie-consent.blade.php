{{--
    Bandeau de consentement pour la mesure d'audience (Google Analytics).

    Inclus par layouts/public.blade.php uniquement quand un identifiant est configuré
    (config('services.google_analytics.id')). Google Analytics n'est chargé qu'après accord.

    - Le choix est mémorisé 6 mois dans le navigateur (localStorage), puis redemandé.
    - Tout élément portant l'attribut data-cookie-settings rouvre le bandeau (lien du pied de page).

    Styles : resources/scss/layout/_public.scss (.cookie-consent)
--}}
<div id="cookie-consent" class="cookie-consent" role="dialog" aria-modal="false"
     aria-labelledby="cookie-consent-titre" aria-describedby="cookie-consent-texte" hidden>
    <h2 id="cookie-consent-titre" class="h6 fw-bold mb-2">
        <i class="fas fa-cookie-bite me-2" aria-hidden="true"></i>Mesure d'audience
    </h2>
    <p id="cookie-consent-texte" class="small mb-3">
        Le club souhaite compter les visites pour améliorer le site, avec Google Analytics.
        Cet outil dépose des cookies : il n'est activé que si vous l'acceptez. Le site fonctionne de la même façon si vous refusez.
        <a href="{{ route('cookies') }}">En savoir plus</a>
    </p>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-primary btn-sm text-white" data-cookie-choice="yes">Accepter</button>
        <button type="button" class="btn btn-outline-dark btn-sm" data-cookie-choice="no">Refuser</button>
    </div>
</div>

<script>
(function () {
    var GA_ID = {!! json_encode($gaId, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!};
    var KEY = 'cnbb_audience_consent';
    var SIX_MONTHS = 1000 * 60 * 60 * 24 * 182;
    var banner = document.getElementById('cookie-consent');

    function readChoice() {
        try {
            var saved = JSON.parse(localStorage.getItem(KEY) || 'null');
            if (saved && saved.value && (Date.now() - saved.date) < SIX_MONTHS) {
                return saved.value;
            }
        } catch (e) {}
        return null;
    }

    function saveChoice(value) {
        try {
            localStorage.setItem(KEY, JSON.stringify({ value: value, date: Date.now() }));
        } catch (e) {}
    }

    function loadAnalytics() {
        if (window.cnbbAnalyticsLoaded) {
            return;
        }
        window.cnbbAnalyticsLoaded = true;
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () { window.dataLayer.push(arguments); };
        window.gtag('js', new Date());
        // Cookies limités à 13 mois, adresse IP anonymisée
        window.gtag('config', GA_ID, { anonymize_ip: true, cookie_expires: 60 * 60 * 24 * 395 });

        var script = document.createElement('script');
        script.async = true;
        script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(GA_ID);
        document.head.appendChild(script);
    }

    // Efface les cookies de mesure d'audience déjà déposés (en cas de refus après un accord)
    function clearAnalyticsCookies() {
        var host = window.location.hostname;
        var domains = ['', '; domain=' + host, '; domain=.' + host.split('.').slice(-2).join('.')];
        document.cookie.split(';').forEach(function (cookie) {
            var name = cookie.split('=')[0].trim();
            if (name.indexOf('_ga') === 0) {
                domains.forEach(function (domain) {
                    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/' + domain;
                });
            }
        });
    }

    var choice = readChoice();
    if (choice === 'yes') {
        loadAnalytics();
    } else if (choice === null) {
        banner.hidden = false;
    }

    banner.addEventListener('click', function (event) {
        var button = event.target.closest('[data-cookie-choice]');
        if (!button) {
            return;
        }
        var value = button.getAttribute('data-cookie-choice');
        saveChoice(value);
        banner.hidden = true;
        if (value === 'yes') {
            loadAnalytics();
        } else {
            clearAnalyticsCookies();
            if (window.cnbbAnalyticsLoaded) {
                // Refus après un accord : il prend effet au rechargement de la page
                window.location.reload();
            }
        }
    });

    // Lien « Gérer les cookies » du pied de page
    document.addEventListener('click', function (event) {
        var opener = event.target.closest('[data-cookie-settings]');
        if (!opener) {
            return;
        }
        event.preventDefault();
        banner.hidden = false;
        banner.querySelector('button').focus();
    });
})();
</script>
