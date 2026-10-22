{{--
    Moyens de paiement d'un don (chèque, virement). Données dans config/club.php (.env).
    Variable : $who ('entreprise' ou 'particulier') pour le texte du reçu.
--}}
@php
    $don = config('club.dons');
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <div class="support-payment h-100 p-4">
            <h3 class="h5 fw-bold mb-3"><i class="fas fa-money-check me-2 text-primary" aria-hidden="true"></i>Par chèque</h3>
            <p class="mb-2">À l'ordre de :</p>
            <p class="fw-bold mb-3">{{ $don['ordre'] }}</p>
            <p class="mb-2">À envoyer ou à remettre à :</p>
            <p class="fw-bold mb-3">{{ $don['adresse'] }}</p>
            <p class="small text-muted mb-0">
                Joignez vos coordonnées
                {{ $who === 'entreprise' ? '(raison sociale, adresse, SIREN, e-mail)' : '(nom, adresse, e-mail)' }}
                pour recevoir votre reçu fiscal.
            </p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="support-payment h-100 p-4">
            <h3 class="h5 fw-bold mb-3"><i class="fas fa-university me-2 text-primary" aria-hidden="true"></i>Par virement</h3>
            @if (filled($don['iban']))
                <p class="mb-1">Titulaire du compte :</p>
                <p class="fw-bold mb-3">{{ $don['titulaire'] }}</p>
                <p class="mb-1">IBAN :</p>
                <p class="mb-3"><span class="support-iban">{{ $don['iban'] }}</span></p>
                @if (filled($don['bic']))
                    <p class="mb-1">BIC :</p>
                    <p class="mb-3"><span class="support-iban">{{ $don['bic'] }}</span></p>
                @endif
                <p class="small text-muted mb-0">
                    Indiquez « DON » et votre nom dans le libellé du virement, puis
                    <a href="{{ route('contact') }}">envoyez-nous vos coordonnées</a> pour recevoir votre reçu fiscal.
                </p>
            @else
                <p class="mb-3">Le RIB du club vous est envoyé sur simple demande.</p>
                <a href="{{ route('contact') }}" class="btn btn-primary text-white">
                    <i class="fas fa-envelope me-2" aria-hidden="true"></i>Demander le RIB
                </a>
                <p class="small text-muted mt-3 mb-0">Précisez dans votre message qu'il s'agit d'un don{{ $who === 'entreprise' ? ' d\'entreprise (mécénat)' : '' }}.</p>
            @endif
        </div>
    </div>
</div>
