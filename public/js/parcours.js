/* Page « Le parcours du nageur » : choix de la vue, choix de l'étape, outil « Trouver mon groupe ».
   Adresse partageable : #vue=etapes&etape=3 ou #vue=disciplines */
(function () {
    'use strict';

    var racine = document.querySelector('[data-parcours]');
    if (!racine) return;
    racine.classList.add('js-actif');

    var boutonsVue = racine.querySelectorAll('[data-vue]');
    var panneauxVue = racine.querySelectorAll('[data-vue-panneau]');
    var boutonsEtape = racine.querySelectorAll('[data-etape]');
    var panneauxEtape = racine.querySelectorAll('[data-etape-panneau]');
    var etat = { vue: 'etapes', etape: '2' };

    function lireAdresse() {
        var h = window.location.hash.replace(/^#/, '');
        h.split('&').forEach(function (morceau) {
            var kv = morceau.split('=');
            if (kv[0] === 'vue' && (kv[1] === 'etapes' || kv[1] === 'disciplines')) etat.vue = kv[1];
            if (kv[0] === 'etape' && /^[1-5]$/.test(kv[1])) { etat.etape = kv[1]; etat.vue = 'etapes'; }
            var m = /^etape-([1-5])$/.exec(morceau);
            if (m) { etat.etape = m[1]; etat.vue = 'etapes'; }
        });
    }

    function ecrireAdresse() {
        var h = '#vue=' + etat.vue + (etat.vue === 'etapes' ? '&etape=' + etat.etape : '');
        if (window.location.hash !== h) history.replaceState(null, '', h);
    }

    function afficher() {
        boutonsVue.forEach(function (b) { b.setAttribute('aria-selected', String(b.dataset.vue === etat.vue)); });
        panneauxVue.forEach(function (p) { p.hidden = p.dataset.vuePanneau !== etat.vue; });
        boutonsEtape.forEach(function (b) {
            var actif = b.dataset.etape === etat.etape;
            b.setAttribute('aria-selected', String(actif));
            b.tabIndex = actif ? 0 : -1;
        });
        panneauxEtape.forEach(function (p) { p.hidden = p.dataset.etapePanneau !== etat.etape; });
    }

    boutonsVue.forEach(function (b) {
        b.addEventListener('click', function () { etat.vue = b.dataset.vue; afficher(); ecrireAdresse(); });
    });

    boutonsEtape.forEach(function (b, i) {
        b.addEventListener('click', function () { etat.etape = b.dataset.etape; afficher(); ecrireAdresse(); });
        // Flèches gauche / droite entre les étapes
        b.addEventListener('keydown', function (ev) {
            if (ev.key !== 'ArrowRight' && ev.key !== 'ArrowLeft') return;
            ev.preventDefault();
            var n = (i + (ev.key === 'ArrowRight' ? 1 : boutonsEtape.length - 1)) % boutonsEtape.length;
            boutonsEtape[n].focus();
            boutonsEtape[n].click();
        });
    });

    // Liens « aller à l'étape » (vue d'ensemble, carte P1)
    racine.querySelectorAll('[data-aller-etape]').forEach(function (a) {
        a.addEventListener('click', function (ev) {
            ev.preventDefault();
            etat.vue = 'etapes';
            etat.etape = a.dataset.allerEtape;
            afficher();
            ecrireAdresse();
            var cible = document.getElementById('vue-etapes');
            if (cible) cible.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    lireAdresse();
    afficher();

    // ----- Trouver mon groupe -----
    var form = racine.querySelector('[data-trouver-form]');
    if (!form) return;
    var curseur = form.querySelector('#trouver-age');
    var sortie = form.querySelector('#trouver-age-val');
    var cartes = racine.querySelectorAll('[data-groupe-min]');
    var aucun = racine.querySelector('[data-trouver-aucun]');

    function trouver() {
        var age = parseInt(curseur.value, 10);
        var pratique = (form.querySelector('input[name="pratique"]:checked') || {}).value || 'tous';
        sortie.textContent = age >= 80 ? '80 et plus' : age;
        var visibles = 0;

        cartes.forEach(function (c) {
            var min = parseInt(c.dataset.groupeMin, 10);
            var max = parseInt(c.dataset.groupeMax, 10);
            var type = c.dataset.groupeType;
            var parNatation = c.dataset.groupeCle === 'par-natation';
            var ok = age >= min && age <= max
                && (pratique === 'tous' || type === 'tous' || type === pratique)
                && !parNatation;
            c.hidden = !ok;
            if (ok) visibles++;
        });

        // Par-Natation : toujours proposé en complément
        cartes.forEach(function (c) { if (c.dataset.groupeCle === 'par-natation') c.hidden = false; });

        var message = '';
        if (age < 6) {
            message = 'Avant 6 ans, le club ne propose pas de groupe. L\'école de natation commence à 6 ans, quand l\'enfant nage 25 m sans aide.';
        } else if (visibles === 0 && pratique === 'loisir' && age >= 19 && age <= 20) {
            message = 'Les groupes Promotion vont jusqu\'à 18 ans et la section Maîtres commence à 21 ans. Contactez le club : une solution est étudiée avec l\'entraîneur.';
        } else if (visibles === 0) {
            message = 'Aucun groupe ne correspond exactement. Contactez le club : l\'évaluation du premier cours permettra de trouver le bon groupe.';
        }
        aucun.textContent = message;
        aucun.hidden = message === '';
    }

    curseur.addEventListener('input', trouver);
    form.addEventListener('change', trouver);
    trouver();
})();
