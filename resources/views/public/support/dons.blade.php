@extends('layouts.public')

@section('title', 'Faire un don au club')
@section('meta_description', 'Adhérents, parents, sympathisants : faites un don au CNBB. 66 % de votre don est déduit de votre impôt sur le revenu, avec reçu fiscal.')
@section('og_title', 'Faire un don au CNBB')
@section('og_description', 'Un don de 100 € ne vous coûte que 34 € : 66 % déduits de votre impôt sur le revenu, avec reçu fiscal.')

@section('content')

@include('public.support._styles')

<x-public.hero
    title="Faire un don"
    eyebrow="Soutenir le club"
    icon="fa-heart"
    lead="Adhérent, parent, ancien nageur ou simple sympathisant : votre don aide le CNBB à faire nager petits et grands. 66 % de votre don est déduit de votre impôt sur le revenu.">
    <a href="#donner" class="btn btn-light btn-lg">
        <i class="fas fa-arrow-down me-2" aria-hidden="true"></i>Comment donner
    </a>
    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-envelope me-2" aria-hidden="true"></i>Une question ?
    </a>
</x-public.hero>


{{-- L'essentiel --}}
<section class="py-5 bg-light" aria-labelledby="essentiel-title">
    <div class="container-lg">
        <h2 id="essentiel-title" class="visually-hidden">L'essentiel</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <p class="support-key mb-0">
                    <span class="support-key-value">66 %</span>
                    <span class="support-key-label">du don déduit de votre impôt sur le revenu</span>
                    <span class="support-key-note">dans la limite de 20 % de votre revenu imposable</span>
                </p>
            </div>
            <div class="col-md-4">
                <p class="support-key mb-0">
                    <span class="support-key-value">34 €</span>
                    <span class="support-key-label">coût réel d'un don de 100 €</span>
                    <span class="support-key-note">66 € de réduction d'impôt</span>
                </p>
            </div>
            <div class="col-md-4">
                <p class="support-key mb-0">
                    <span class="support-key-value"><i class="fas fa-file-invoice" aria-hidden="true"></i></span>
                    <span class="support-key-label">un reçu fiscal remis par le club</span>
                    <span class="support-key-note">à conserver, pas à envoyer aux impôts</span>
                </p>
            </div>
        </div>
    </div>
</section>


{{-- À quoi sert votre don --}}
<section class="py-5 bg-white" aria-labelledby="usage-title">
    <div class="container-lg">
        <h2 id="usage-title" class="h3 fw-bold mb-4 support-title">À quoi sert votre don ?</h2>
        <p class="support-lead mb-4">
            Le CNBB est une association à but non lucratif, animée par des bénévoles. Votre don nous aide à :
        </p>
        <div class="row g-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="support-icon mb-3" aria-hidden="true"><i class="fas fa-child"></i></span>
                        <h3 class="h6 fw-bold">Former les jeunes</h3>
                        <p class="mb-0 text-muted small">Apprendre à nager, progresser, découvrir la compétition.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="support-icon mb-3" aria-hidden="true"><i class="fas fa-swimmer"></i></span>
                        <h3 class="h6 fw-bold">S'équiper</h3>
                        <p class="mb-0 text-muted small">Planches, palmes, chronomètres et matériel d'entraînement.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="support-icon mb-3" aria-hidden="true"><i class="fas fa-bus"></i></span>
                        <h3 class="h6 fw-bold">Partir en compétition</h3>
                        <p class="mb-0 text-muted small">Engagements, déplacements et hébergement des nageurs.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="support-icon mb-3" aria-hidden="true"><i class="fas fa-hands-helping"></i></span>
                        <h3 class="h6 fw-bold">Ouvrir le club à tous</h3>
                        <p class="mb-0 text-muted small">Garder des tarifs accessibles et accueillir chacun.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- Avantage fiscal + simulateur --}}
<section class="py-5 bg-light" aria-labelledby="fiscal-title">
    <div class="container-lg">
        <h2 id="fiscal-title" class="h3 fw-bold mb-4 support-title">Votre réduction d'impôt</h2>

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <ul class="support-list mb-4">
                    <li>Vous déduisez de votre impôt sur le revenu <strong>66 % de la somme versée</strong>.</li>
                    <li>Limite : <strong>20 % de votre revenu imposable</strong>. Au-delà, l'excédent est <strong>reporté sur les 5 années suivantes</strong>.</li>
                    <li>Le club vous envoie un <strong>reçu fiscal</strong>. Conservez-le : il n'est pas à joindre à la déclaration, mais les impôts peuvent vous le demander.</li>
                    <li>Lors de votre déclaration de revenus, l'année suivante, indiquez le montant de vos dons en <strong>case 7UF</strong>.</li>
                    <li>Le don ne doit pas donner lieu à une <strong>contrepartie</strong> (hors petit souvenir symbolique).</li>
                </ul>
                <p class="support-note mb-0">
                    <i class="fas fa-info-circle me-1" aria-hidden="true"></i>
                    Informations générales, valables en {{ now()->year }}. En savoir plus :
                    <a href="https://www.impots.gouv.fr/url-stable-fiche-qr-chatbot-ndeg4-dons-une-association" target="_blank" rel="noopener">impots.gouv.fr</a>.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="support-simulator p-4 h-100">
                    <h3 class="h5 fw-bold mb-3 text-white"><i class="fas fa-calculator me-2" aria-hidden="true"></i>Combien vous coûte votre don ?</h3>
                    <label for="sim-don" class="form-label">Montant du don (€)</label>
                    <input type="number" id="sim-don" class="form-control form-control-lg mb-3" min="0" step="10" value="100" inputmode="numeric">

                    <div class="d-flex flex-wrap gap-2 mb-3" role="group" aria-label="Montants proposés">
                        @foreach ([30, 50, 100, 200] as $montant)
                            <button type="button" class="btn btn-sm sim-preset" data-amount="{{ $montant }}">{{ $montant }} €</button>
                        @endforeach
                    </div>

                    <div class="row g-3" aria-live="polite">
                        <div class="col-6">
                            <div class="support-result">
                                <span class="support-result-value" id="sim-reduction">66 €</span>
                                <span class="support-result-label">de réduction d'impôt</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="support-result">
                                <span class="support-result-value" id="sim-cout">34 €</span>
                                <span class="support-result-label">de coût réel pour vous</span>
                            </div>
                        </div>
                    </div>
                    <p class="small mt-3 mb-0" style="color: rgba(255,255,255,.85);">
                        Exemple valable si vous payez de l'impôt sur le revenu et dans la limite de 20 % de votre revenu imposable.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- Bon à savoir --}}
<section class="py-5 bg-white" aria-labelledby="savoir-title">
    <div class="container-lg">
        <h2 id="savoir-title" class="h3 fw-bold mb-4 support-title">Bon à savoir</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4 d-flex gap-3">
                        <span class="support-icon" aria-hidden="true"><i class="fas fa-id-card"></i></span>
                        <div>
                            <h3 class="h6 fw-bold">La cotisation n'est pas un don</h3>
                            <p class="mb-0 text-muted">
                                La cotisation annuelle et la licence paient votre activité au club : elles ne donnent pas droit à la réduction d'impôt.
                                Seul un versement en plus, sans contrepartie, est un don.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card support-card border-0 shadow-sm">
                    <div class="card-body p-4 d-flex gap-3">
                        <span class="support-icon" aria-hidden="true"><i class="fas fa-car"></i></span>
                        <div>
                            <h3 class="h6 fw-bold">Bénévoles : vos frais peuvent devenir un don</h3>
                            <p class="mb-0 text-muted">
                                Vous avancez des frais pour le club (déplacements en compétition, achats) et renoncez à leur remboursement ?
                                Ils peuvent être considérés comme un don, avec reçu fiscal. Parlez-en au trésorier avant la fin de l'année.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- Comment donner --}}
<section class="py-5 bg-light" id="donner" aria-labelledby="donner-title" style="scroll-margin-top: 100px;">
    <div class="container-lg">
        <h2 id="donner-title" class="h3 fw-bold mb-4 support-title">Comment faire un don ?</h2>

        <ol class="list-unstyled row g-4 mb-5">
            <li class="col-md-4 d-flex gap-3">
                <span class="support-step-number" aria-hidden="true">1</span>
                <div>
                    <h3 class="h6 fw-bold mb-1">Choisissez le montant</h3>
                    <p class="mb-0 text-muted">Chaque don compte, quel que soit son montant.</p>
                </div>
            </li>
            <li class="col-md-4 d-flex gap-3">
                <span class="support-step-number" aria-hidden="true">2</span>
                <div>
                    <h3 class="h6 fw-bold mb-1">Donnez par chèque ou virement</h3>
                    <p class="mb-0 text-muted">Avec vos nom, adresse et e-mail pour le reçu fiscal.</p>
                </div>
            </li>
            <li class="col-md-4 d-flex gap-3">
                <span class="support-step-number" aria-hidden="true">3</span>
                <div>
                    <h3 class="h6 fw-bold mb-1">Recevez votre reçu fiscal</h3>
                    <p class="mb-0 text-muted">Le club vous l'envoie ; vous déclarez votre don l'année suivante.</p>
                </div>
            </li>
        </ol>

        @include('public.support._payment', ['who' => 'particulier'])

        <div class="alert alert-info border-0 shadow-sm mt-5 mb-0 d-flex gap-3 align-items-start" role="note">
            <i class="fas fa-building fa-lg mt-1" aria-hidden="true"></i>
            <div>
                Vous représentez une <strong>entreprise</strong> ?
                <a href="{{ route('support.mecenat') }}" class="fw-semibold">Devenir mécène du club</a> (60 % déduits de l'impôt de l'entreprise).
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Simulateur : réduction de 66 % du don
    (function () {
        var don = document.getElementById('sim-don');
        if (!don) return;

        var euros = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 });

        function calculer() {
            var montant = Math.max(0, parseFloat(don.value) || 0);
            var reduction = Math.round(montant * 0.66);
            document.getElementById('sim-reduction').textContent = euros.format(reduction);
            document.getElementById('sim-cout').textContent = euros.format(montant - reduction);
        }

        don.addEventListener('input', calculer);
        document.querySelectorAll('.sim-preset').forEach(function (bouton) {
            bouton.addEventListener('click', function () {
                don.value = bouton.dataset.amount;
                calculer();
            });
        });
        don.addEventListener('input', function () { marquer(); });
        function marquer() {
            document.querySelectorAll('.sim-preset').forEach(function (b) {
                var actif = b.dataset.amount === String(parseFloat(don.value));
                b.classList.toggle('is-active', actif);
                b.setAttribute('aria-pressed', actif ? 'true' : 'false');
            });
        }
        document.querySelectorAll('.sim-preset').forEach(function (bouton) {
            bouton.addEventListener('click', marquer);
        });
        calculer();
        marquer();
    })();
</script>
@endpush
