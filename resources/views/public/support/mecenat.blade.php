@extends('layouts.public')

@section('title', 'Devenir mécène du club')
@section('meta_description', 'Entreprises : soutenez le CNBB par un don financier, en nature ou en compétences, et bénéficiez d\'une réduction d\'impôt de 60 % du montant du don.')
@section('og_title', 'Devenir mécène du CNBB')
@section('og_description', 'Soutenez le club de natation de Bressuire : 60 % de votre don déduit de votre impôt, avec reçu fiscal.')

@section('content')

@include('public.support._styles')

<x-public.hero
    title="Devenir mécène"
    eyebrow="Soutenir le club"
    icon="fa-hand-holding-heart"
    lead="Entreprises, commerçants, artisans : soutenez le CNBB par un don d'argent, de matériel ou de compétences. 60 % de votre don est déduit de votre impôt.">
    <a href="#donner" class="btn btn-light btn-lg">
        <i class="fas fa-arrow-down me-2" aria-hidden="true"></i>Comment donner
    </a>
    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-envelope me-2" aria-hidden="true"></i>Nous contacter
    </a>
</x-public.hero>


{{-- L'essentiel en 3 chiffres --}}
<section class="py-5 bg-light" aria-labelledby="essentiel-title">
    <div class="container-lg">
        <h2 id="essentiel-title" class="visually-hidden">L'essentiel</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <p class="support-key mb-0">
                    <span class="support-key-value">60 %</span>
                    <span class="support-key-label">du don déduit de votre impôt</span>
                    <span class="support-key-note">impôt sur les sociétés ou sur le revenu</span>
                </p>
            </div>
            <div class="col-md-4">
                <p class="support-key mb-0">
                    <span class="support-key-value">400 €</span>
                    <span class="support-key-label">coût réel d'un don de 1 000 €</span>
                    <span class="support-key-note">600 € de réduction d'impôt</span>
                </p>
            </div>
            <div class="col-md-4">
                <p class="support-key mb-0">
                    <span class="support-key-value"><i class="fas fa-file-invoice" aria-hidden="true"></i></span>
                    <span class="support-key-label">un reçu fiscal remis par le club</span>
                    <span class="support-key-note">à conserver pour votre déclaration</span>
                </p>
            </div>
        </div>
    </div>
</section>


{{-- Principe --}}
<section class="py-5 bg-white" aria-labelledby="principe-title">
    <div class="container-lg">
        <h2 id="principe-title" class="h3 fw-bold mb-4 support-title">Le mécénat sportif, c'est quoi ?</h2>
        <p class="support-lead mb-4">
            C'est un <strong>soutien volontaire</strong> d'une entreprise à un club, sans recherche de retour commercial.
            Votre entreprise participe à la vie locale, au développement du sport et à la transmission de valeurs positives.
        </p>

        <h3 class="h5 fw-bold mb-3">Trois façons d'aider le club</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="support-icon mb-3" aria-hidden="true"><i class="fas fa-euro-sign"></i></span>
                        <h4 class="h6 fw-bold">Un don d'argent</h4>
                        <p class="mb-0 text-muted">Par chèque ou par virement, ponctuel ou chaque année.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="support-icon mb-3" aria-hidden="true"><i class="fas fa-box-open"></i></span>
                        <h4 class="h6 fw-bold">Un don en nature</h4>
                        <p class="mb-0 text-muted">Du matériel, des équipements, des lots pour nos compétitions, un véhicule prêté pour un déplacement…</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="support-icon mb-3" aria-hidden="true"><i class="fas fa-user-tie"></i></span>
                        <h4 class="h6 fw-bold">Un don de compétences</h4>
                        <p class="mb-0 text-muted">Un salarié aide le club sur son temps de travail : comptabilité, communication, travaux, informatique…</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- Pourquoi --}}
<section class="py-5 bg-light" aria-labelledby="pourquoi-title">
    <div class="container-lg">
        <div class="row g-5">
            <div class="col-lg-6">
                <h2 id="pourquoi-title" class="h3 fw-bold mb-4 support-title">Pourquoi soutenir le sport ?</h2>
                <p class="mb-3">Le sport rassemble, forme les jeunes et fait du bien. En soutenant le CNBB, vous :</p>
                <ul class="support-list">
                    <li>aidez à rendre la natation <strong>accessible à tous</strong> ;</li>
                    <li>contribuez à l'<strong>éducation et à l'épanouissement des jeunes</strong> ;</li>
                    <li>soutenez la <strong>vie associative locale</strong> ;</li>
                    <li>participez à la <strong>vitalité de votre territoire</strong>, le Bocage bressuirais.</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <h2 class="h3 fw-bold mb-4 support-title">Un engagement valorisant</h2>
                <p class="mb-3">Devenir mécène, c'est aussi :</p>
                <ul class="support-list">
                    <li>affirmer les <strong>valeurs de votre entreprise</strong> : solidarité, engagement, esprit d'équipe ;</li>
                    <li><strong>rassembler vos collaborateurs</strong> autour d'un projet commun ;</li>
                    <li>construire une <strong>image responsable et citoyenne</strong>.</li>
                </ul>
            </div>
        </div>
    </div>
</section>


{{-- Avantage fiscal + simulateur --}}
<section class="py-5 bg-white" aria-labelledby="fiscal-title">
    <div class="container-lg">
        <h2 id="fiscal-title" class="h3 fw-bold mb-4 support-title">L'avantage fiscal</h2>

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <ul class="support-list mb-4">
                    <li>Votre entreprise déduit de son impôt <strong>60 % du montant du don</strong>.</li>
                    <li>
                        Plafond par an : <strong>20 000 €</strong> ou <strong>0,5 % du chiffre d'affaires hors taxes</strong>
                        (le montant le plus élevé des deux).
                    </li>
                    <li>Au-delà du plafond, l'excédent peut être <strong>reporté sur les 5 années suivantes</strong>.</li>
                    <li>Un don en nature ou en compétences compte pour sa <strong>valeur au prix de revient</strong> (coût du matériel, salaire et charges du temps passé).</li>
                    <li>Le club vous remet un <strong>reçu fiscal</strong> : conservez-le, il justifie votre réduction d'impôt.</li>
                </ul>
                <p class="support-note mb-0">
                    <i class="fas fa-info-circle me-1" aria-hidden="true"></i>
                    Informations générales, valables en {{ now()->year }}. Pour votre situation précise, rapprochez-vous de votre expert-comptable
                    ou consultez <a href="https://www.economie.gouv.fr/entreprises/mecenat-dons-entreprise" target="_blank" rel="noopener">economie.gouv.fr</a>.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="support-simulator p-4 h-100" id="simulateur-entreprise">
                    <h3 class="h5 fw-bold mb-3 text-white"><i class="fas fa-calculator me-2" aria-hidden="true"></i>Combien coûte réellement votre don ?</h3>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="sim-don" class="form-label">Montant du don (€)</label>
                            <input type="number" id="sim-don" class="form-control form-control-lg" min="0" step="50" value="1000" inputmode="numeric">
                        </div>
                        <div class="col-sm-6">
                            <label for="sim-ca" class="form-label">Chiffre d'affaires HT (€) <span class="small">facultatif</span></label>
                            <input type="number" id="sim-ca" class="form-control form-control-lg" min="0" step="10000" placeholder="ex. 500000" inputmode="numeric">
                        </div>
                    </div>
                    <div class="row g-3" aria-live="polite">
                        <div class="col-6">
                            <div class="support-result">
                                <span class="support-result-value" id="sim-reduction">600 €</span>
                                <span class="support-result-label">de réduction d'impôt</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="support-result">
                                <span class="support-result-value" id="sim-cout">400 €</span>
                                <span class="support-result-label">de coût réel pour l'entreprise</span>
                            </div>
                        </div>
                    </div>
                    <p class="small mt-3 mb-0" id="sim-plafond" style="color: rgba(255,255,255,.85);"></p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- Mécénat ou partenariat --}}
<section class="py-5 bg-light" aria-labelledby="difference-title">
    <div class="container-lg">
        <h2 id="difference-title" class="h3 fw-bold mb-3 support-title">Mécénat ou partenariat : quelle différence ?</h2>
        <p class="support-lead mb-4">
            Le mécénat est un <strong>don</strong>. Les contreparties doivent rester <strong>très limitées</strong> :
            une reconnaissance discrète, sans publicité.
        </p>

        <div class="table-responsive">
            <table class="table support-compare bg-white shadow-sm mb-3">
                <caption class="visually-hidden">Comparaison entre mécénat et partenariat</caption>
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Mécénat</th>
                        <th scope="col">Partenariat (sponsoring)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Le but</th>
                        <td data-label="Mécénat">Soutenir le club, sans retour commercial</td>
                        <td data-label="Partenariat">Faire connaître votre entreprise</td>
                    </tr>
                    <tr>
                        <th scope="row">Ce que vous recevez</th>
                        <td data-label="Mécénat">
                            Une reconnaissance discrète : nom ou logo dans la plaquette du club,
                            remerciements lors d'événements, invitation aux rencontres partenaires
                        </td>
                        <td data-label="Partenariat">Une visibilité publicitaire (logo, banderole, présence sur le site…) en échange du versement</td>
                    </tr>
                    <tr>
                        <th scope="row">Pour vos impôts</th>
                        <td data-label="Mécénat"><strong>Réduction d'impôt de 60 %</strong> avec reçu fiscal</td>
                        <td data-label="Partenariat">Dépense déductible du résultat comme une charge de publicité, avec une facture</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mb-0">
            Vous cherchez plutôt de la visibilité ?
            <a href="{{ route('partners.index') }}" class="fw-semibold">Découvrez nos partenaires et devenez partenaire du club</a>.
        </p>
    </div>
</section>


{{-- Comment donner --}}
<section class="py-5 bg-white" id="donner" aria-labelledby="donner-title" style="scroll-margin-top: 100px;">
    <div class="container-lg">
        <h2 id="donner-title" class="h3 fw-bold mb-4 support-title">Comment devenir mécène ?</h2>

        <ol class="list-unstyled row g-4 mb-5">
            <li class="col-md-4 d-flex gap-3">
                <span class="support-step-number" aria-hidden="true">1</span>
                <div>
                    <h3 class="h6 fw-bold mb-1">Parlons-en</h3>
                    <p class="mb-0 text-muted">
                        <a href="{{ route('contact') }}">Contactez le club</a> : nous vous présentons nos projets et choisissons ensemble la forme de votre soutien.
                    </p>
                </div>
            </li>
            <li class="col-md-4 d-flex gap-3">
                <span class="support-step-number" aria-hidden="true">2</span>
                <div>
                    <h3 class="h6 fw-bold mb-1">Faites votre don</h3>
                    <p class="mb-0 text-muted">Par chèque ou par virement pour un don d'argent ; nous organisons ensemble un don en nature ou de compétences.</p>
                </div>
            </li>
            <li class="col-md-4 d-flex gap-3">
                <span class="support-step-number" aria-hidden="true">3</span>
                <div>
                    <h3 class="h6 fw-bold mb-1">Recevez votre reçu fiscal</h3>
                    <p class="mb-0 text-muted">Le club vous l'envoie : il vous permet de bénéficier de la réduction d'impôt.</p>
                </div>
            </li>
        </ol>

        @include('public.support._payment', ['who' => 'entreprise'])

        <div class="alert alert-info border-0 shadow-sm mt-5 mb-0 d-flex gap-3 align-items-start" role="note">
            <i class="fas fa-user fa-lg mt-1" aria-hidden="true"></i>
            <div>
                Vous souhaitez donner <strong>à titre personnel</strong> ?
                <a href="{{ route('support.donation') }}" class="fw-semibold">Faire un don en tant que particulier</a> (66 % déduits de votre impôt sur le revenu).
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Simulateur : réduction de 60 % dans la limite de 20 000 € ou 0,5 % du CA HT (le plus élevé)
    (function () {
        var don = document.getElementById('sim-don');
        var ca = document.getElementById('sim-ca');
        if (!don) return;

        var euros = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 });

        function calculer() {
            var montant = Math.max(0, parseFloat(don.value) || 0);
            var chiffre = Math.max(0, parseFloat(ca.value) || 0);
            var plafond = Math.max(20000, chiffre * 0.005);
            var retenu = Math.min(montant, plafond);
            var reduction = Math.round(retenu * 0.6);
            var excedent = montant - retenu;

            document.getElementById('sim-reduction').textContent = euros.format(reduction);
            document.getElementById('sim-cout').textContent = euros.format(montant - reduction);

            var note = 'Plafond pris en compte cette année : ' + euros.format(plafond) + '.';
            if (excedent > 0) {
                note += ' L\'excédent de ' + euros.format(excedent) + ' peut être reporté sur les 5 années suivantes (réduction affichée : cette année seulement).';
            }
            document.getElementById('sim-plafond').textContent = note;
        }

        don.addEventListener('input', calculer);
        ca.addEventListener('input', calculer);
        calculer();
    })();
</script>
@endpush
