{{-- Fiche adhérent (saison en cours) vue par la famille. Paramètres : $member, $season. --}}
@php
    use App\Models\Membership as M;
    $adhesion = $member->memberships->first();
    $mineur = $member->isMinor($season);
@endphp
<div class="member-file">
    <h3 class="h6 fw-bold mb-2"><i class="fas fa-id-card text-primary me-2" aria-hidden="true"></i>Fiche adhérent · saison {{ $season }}</h3>

    @if ($adhesion)
        @php [$statutLibelle, $statutCouleur] = M::STATUSES[$adhesion->status] ?? [$adhesion->status, 'secondary']; $manquantes = $adhesion->missingFileItems(); @endphp
        <dl class="member-dl">
            <dt>Catégorie</dt>
            <dd>{{ $adhesion->category?->label ?? '—' }}</dd>
            <dt>Statut</dt>
            <dd><span class="badge bg-{{ $statutCouleur }}{{ in_array($statutCouleur, ['warning', 'info']) ? ' text-dark' : '' }}">{{ $statutLibelle }}</span></dd>
            <dt>Dossier</dt>
            <dd>
                @if (! $manquantes)
                    <span class="text-success"><i class="fas fa-check me-1" aria-hidden="true"></i>complet</span>
                @else
                    <span class="text-warning-emphasis">à compléter :</span> {{ implode(', ', $manquantes) }}
                @endif
            </dd>
            <dt>{{ $mineur ? 'Santé' : 'Certificat médical' }}</dt>
            <dd>
                @if ($mineur)
                    questionnaire de santé (mineur)
                @elseif ($alerte = $adhesion->certificateAlert())
                    <span class="text-danger">{{ $alerte }}</span>
                @else
                    {{ $adhesion->medical_certificate_year }}
                @endif
            </dd>
            <dt>Droit à l'image</dt>
            <dd>{{ $member->image_rights === null ? 'non renseigné' : ($member->image_rights ? 'accordé' : 'refusé') }}</dd>
            <dt>Cotisation</dt>
            <dd>
                {{ M::money($adhesion->amount_cents) }}@if ($adhesion->discount_cents) <span class="text-muted small">(remise {{ M::money($adhesion->discount_cents) }})</span>@endif
                <br>
                <span class="small">
                    Réglé {{ M::money($adhesion->paid_cents) }}@if ($adhesion->pass_sport && $adhesion->pass_sport_cents) + Pass'Sport {{ M::money($adhesion->pass_sport_cents) }}@endif
                    ·
                    @if ($adhesion->due_cents > 0)
                        <strong class="text-danger">reste {{ M::money($adhesion->due_cents) }}</strong>
                    @else
                        <span class="text-success">soldé</span>
                    @endif
                </span>
            </dd>
            @if ($adhesion->payment_certificate)
                <dt>Attestation</dt>
                <dd>attestation de paiement remise{{ $adhesion->payment_certificate_on ? ' le ' . \Illuminate\Support\Carbon::parse($adhesion->payment_certificate_on)->format('d/m/Y') : '' }}</dd>
            @endif
        </dl>
    @else
        <p class="small text-muted mb-2">Pas encore d'adhésion enregistrée pour la saison {{ $season }}.</p>
    @endif

    <details class="mt-2">
        <summary class="small fw-semibold">Coordonnées enregistrées par le club</summary>
        <dl class="member-dl mt-2 small">
            <dt>Naissance</dt>
            <dd>{{ $member->birth_date?->format('d/m/Y') ?? '—' }}@if ($member->birth_place) à {{ $member->birth_place }}@endif</dd>
            <dt>Adresse</dt>
            <dd>{{ $member->address ?: '—' }}@if ($member->postal_code || $member->city)<br>{{ trim($member->postal_code . ' ' . $member->city) }}@endif</dd>
            <dt>Téléphone</dt>
            <dd>{{ collect([$member->phone1, $member->phone2])->filter()->implode(' · ') ?: '—' }}</dd>
            <dt>E-mail</dt>
            <dd class="text-break">{{ $member->email ?: '—' }}</dd>
            @foreach (collect($member->guardians)->filter(fn ($g) => ! empty($g['name'])) as $g)
                <dt>{{ ! empty($g['relation']) ? ucfirst($g['relation']) : 'Responsable légal' }}</dt>
                <dd>{{ $g['name'] }}@if (! empty($g['phone']))<br>{{ $g['phone'] }}@endif @if (! empty($g['email']))<br><span class="text-break">{{ $g['email'] }}</span>@endif</dd>
            @endforeach
        </dl>
        <p class="small text-muted mb-0">Une information à corriger ?
            @if (Route::has('user.messages.create'))<a href="{{ route('user.messages.create') }}">Écrivez au club</a>.@else Contactez le club.@endif
        </p>
    </details>
</div>
