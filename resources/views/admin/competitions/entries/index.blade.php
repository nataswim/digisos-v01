@extends($layout)

@section('title', 'Convocations — ' . $competition->title)

@php
    $statuts = \App\Models\CompetitionAttendance::STATUSES;
    $ouvert = $competition->isOpenForResponses();
@endphp

@section('content')
<div class="competitions-admin">
    {{-- En-tête --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-users text-primary me-2" aria-hidden="true"></i>Convocations et engagements</h1>
            <p class="mb-0 text-muted">
                <strong class="text-body">{{ $competition->title }}</strong> — {{ $competition->date_range }}, {{ $competition->city }}
                <span class="badge {{ $competition->state_badge }} ms-1">{{ $competition->state_label }}</span>
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route($area . '.competitions.edit', $competition) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-edit me-1" aria-hidden="true"></i>Fiche compétition
            </a>
            <a href="{{ route('competitions.show', $competition) }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
                <i class="fas fa-eye me-1" aria-hidden="true"></i>Page publique
            </a>
            @if ($stats['total'])
                <a href="{{ route($area . '.competitions.entries.export', $competition) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-csv me-1" aria-hidden="true"></i>Export Excel (CSV)
                </a>
            @endif
            <a href="{{ route($area . '.competitions.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Compétitions
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Chiffres clés --}}
    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-2 mb-3">
        @foreach ([
            ['Convoqués', $stats['total'], 'fa-user-friends', 'text-primary'],
            ['Confirmés', $stats['confirme'], 'fa-check', 'text-success'],
            ['Sans réponse', $stats['convoque'], 'fa-hourglass-half', 'text-warning'],
            ['Absents / forfaits', $stats['absent'] + $stats['forfait'], 'fa-times', 'text-secondary'],
            ['Engagements', $stats['entries'], 'fa-swimmer', 'text-primary'],
            ['Covoiturage', $stats['seats_offered'] . ' / ' . $stats['seats_needed'], 'fa-car', 'text-primary'],
        ] as [$libelle, $valeur, $icone, $couleur])
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body py-2 px-3">
                        <div class="small text-muted text-truncate">
                            <i class="fas {{ $icone }} {{ $couleur }} me-1" aria-hidden="true"></i>{{ $libelle }}
                        </div>
                        <div class="fs-4 fw-bold">{{ $valeur }}</div>
                        @if ($libelle === 'Covoiturage')
                            <div class="small text-muted">places offertes / cherchées</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Date limite et e-mails --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <i class="fas fa-calendar-check {{ $ouvert ? 'text-success' : 'text-secondary' }} me-1" aria-hidden="true"></i>
                @if ($competition->response_deadline)
                    Réponse des familles jusqu'au <strong>{{ $competition->response_deadline->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</strong>
                @else
                    Pas de date limite de réponse
                @endif
                — <span class="{{ $ouvert ? 'text-success' : 'text-secondary' }} fw-semibold">{{ $ouvert ? 'réponses en ligne ouvertes' : 'réponses en ligne closes' }}</span>
                @if ($withoutAccount)
                    <div class="small text-muted mt-1">
                        <i class="fas fa-exclamation-triangle text-warning me-1" aria-hidden="true"></i>
                        {{ $withoutAccount }} nageur{{ $withoutAccount > 1 ? 's' : '' }} sans compte du site lié : à prévenir par un autre moyen
                        (ou liez un compte dans <a href="{{ route($area . '.planning.swimmers.index') }}">Planning › Nageurs</a>).
                    </div>
                @endif
            </div>
            @if ($ouvert && $stats['convoque'])
                <div class="d-flex flex-wrap gap-2">
                    @if ($unnotified)
                        <form method="POST" action="{{ route($area . '.competitions.entries.notify', $competition) }}"
                              onsubmit="return confirm('Envoyer la convocation par e-mail aux familles concernées ?');">
                            @csrf
                            <input type="hidden" name="scope" value="new">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane me-1" aria-hidden="true"></i>Envoyer les convocations ({{ $unnotified }})
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route($area . '.competitions.entries.notify', $competition) }}"
                          onsubmit="return confirm('Relancer par e-mail toutes les familles qui n\'ont pas encore répondu ?');">
                        @csrf
                        <input type="hidden" name="scope" value="pending">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-bell me-1" aria-hidden="true"></i>Relancer les familles sans réponse
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Liste des convoqués --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h2 class="h6 fw-bold mb-0">Nageurs convoqués</h2>
            @if ($attendances->isNotEmpty())
                <button type="submit" form="form-statuts" class="btn btn-success btn-sm">
                    <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer les statuts
                </button>
            @endif
        </div>

        @if ($attendances->isEmpty())
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-user-plus fa-2x mb-2 d-block" aria-hidden="true"></i>
                Aucun nageur convoqué pour l'instant : cochez les nageurs ci-dessous.
            </div>
        @else
            <form id="form-statuts" method="POST" action="{{ route($area . '.competitions.entries.statuses', $competition) }}">
                @csrf
                @method('PATCH')
                <div class="table-responsive position-relative">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-3">Nageur</th>
                                <th scope="col" style="min-width: 190px;">Statut</th>
                                <th scope="col">Réponse de la famille</th>
                                <th scope="col">Épreuves</th>
                                <th scope="col" class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                @php $nageur = $attendance->swimmer; @endphp
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route($area . '.competitions.entries.edit', [$competition, $attendance]) }}" class="fw-semibold text-decoration-none">{{ $nageur->full_name }}</a>
                                        <small class="text-muted d-block">
                                            {{ $nageur->birth_year ?: 'année ?' }}{{ $nageur->gender ? ' · ' . $nageur->gender : '' }}
                                            @foreach ($nageur->groups as $groupe)
                                                · {{ $groupe->name }}
                                            @endforeach
                                        </small>
                                        @if (! $nageur->user)
                                            <small class="text-warning-emphasis"><i class="fas fa-user-slash me-1" aria-hidden="true"></i>sans compte lié</small>
                                        @elseif ($attendance->notified_at)
                                            <small class="text-muted"><i class="fas fa-envelope me-1" aria-hidden="true"></i>prévenu le {{ $attendance->notified_at->format('d/m') }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <label for="statut-{{ $attendance->id }}" class="visually-hidden">Statut de {{ $nageur->full_name }}</label>
                                        <select id="statut-{{ $attendance->id }}" name="statuses[{{ $attendance->id }}]" class="form-select form-select-sm">
                                            @foreach ($statuts as $cle => [$libelle])
                                                <option value="{{ $cle }}" @selected($attendance->status === $cle)>{{ $libelle }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="small">
                                        @if ($attendance->responded_at)
                                            <span class="text-muted">Le {{ $attendance->responded_at->format('d/m à H\hi') }}{{ $attendance->responder ? ' par ' . $attendance->responder->name : '' }}</span>
                                        @else
                                            <span class="text-muted fst-italic">Pas de réponse</span>
                                        @endif
                                        @if ($attendance->needs_seat)
                                            <span class="badge bg-info text-dark d-inline-block mt-1"><i class="fas fa-car me-1" aria-hidden="true"></i>cherche une place</span>
                                        @endif
                                        @if ($attendance->seats_offered)
                                            <span class="badge bg-light text-dark border d-inline-block mt-1"><i class="fas fa-car-side me-1" aria-hidden="true"></i>{{ $attendance->seats_offered }} place{{ $attendance->seats_offered > 1 ? 's' : '' }} offerte{{ $attendance->seats_offered > 1 ? 's' : '' }}</span>
                                        @endif
                                        @if ($attendance->family_comment)
                                            <div class="mt-1"><i class="fas fa-comment text-muted me-1" aria-hidden="true"></i>{{ $attendance->family_comment }}</div>
                                        @endif
                                        @if ($attendance->staff_note)
                                            <div class="mt-1 text-muted"><i class="fas fa-lock me-1" aria-hidden="true"></i>{{ $attendance->staff_note }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse ($attendance->sorted_entries as $entry)
                                            <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle me-1 mb-1">
                                                {{ $entry->event->label }} <span class="fw-normal">{{ $entry->entry_time ?? 'NT' }}</span>
                                            </span>
                                        @empty
                                            <span class="text-muted small">—</span>
                                        @endforelse
                                    </td>
                                    <td class="text-end pe-3 text-nowrap">
                                        <a href="{{ route($area . '.competitions.entries.edit', [$competition, $attendance]) }}" class="btn btn-sm btn-outline-primary" title="Épreuves et détails">
                                            <i class="fas fa-swimmer" aria-hidden="true"></i><span class="visually-hidden">Épreuves et détails de {{ $nageur->full_name }}</span>
                                        </a>
                                        <button type="submit" form="retirer-{{ $attendance->id }}" class="btn btn-sm btn-outline-danger" title="Retirer des convoqués">
                                            <i class="fas fa-user-minus" aria-hidden="true"></i><span class="visually-hidden">Retirer {{ $nageur->full_name }}</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

            {{-- Formulaires de retrait (hors du formulaire des statuts) --}}
            @foreach ($attendances as $attendance)
                <form id="retirer-{{ $attendance->id }}" method="POST" class="d-none"
                      action="{{ route($area . '.competitions.entries.destroy', [$competition, $attendance]) }}"
                      onsubmit="return confirm('Retirer ce nageur des convoqués ? Ses épreuves seront supprimées.');">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </div>

    {{-- Convoquer des nageurs --}}
    <div class="card border-0 shadow-sm" id="convoquer">
        <div class="card-header bg-white py-3">
            <h2 class="h6 fw-bold mb-0"><i class="fas fa-user-plus text-primary me-2" aria-hidden="true"></i>Convoquer des nageurs</h2>
        </div>
        <div class="card-body">
            @if ($groups->isEmpty() && $ungrouped->isEmpty())
                <p class="text-muted mb-0">
                    Tous les nageurs actifs sont déjà convoqués, ou aucun nageur n'est encore saisi.
                    <a href="{{ route($area . '.planning.swimmers.create') }}">Ajouter un nageur</a>
                </p>
            @else
                <form method="POST" action="{{ route($area . '.competitions.entries.store', $competition) }}">
                    @csrf
                    <div class="row g-3">
                        @foreach ($groups as $groupe)
                            @php $concerne = in_array($groupe->id, $competitionGroupIds, true); @endphp
                            <div class="col-md-6 col-xl-4">
                                <fieldset class="border rounded p-3 h-100 {{ $concerne ? 'border-primary' : '' }}" data-groupe>
                                    <legend class="float-none w-auto px-1 mb-1 fs-6 fw-semibold">
                                        <span class="badge me-1" style="background: {{ $groupe->background_color }}; color: {{ $groupe->text_color }};">{{ $groupe->name }}</span>
                                        @if ($concerne)
                                            <small class="text-primary">concerné</small>
                                        @endif
                                    </legend>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="tout-{{ $groupe->id }}" data-tout>
                                        <label class="form-check-label small text-muted" for="tout-{{ $groupe->id }}">Tout le groupe ({{ $groupe->swimmers->count() }})</label>
                                    </div>
                                    @foreach ($groupe->swimmers as $nageur)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="swimmers[]" value="{{ $nageur->id }}"
                                                   id="nageur-{{ $groupe->id }}-{{ $nageur->id }}" data-nageur="{{ $nageur->id }}">
                                            <label class="form-check-label" for="nageur-{{ $groupe->id }}-{{ $nageur->id }}">
                                                {{ $nageur->full_name }} <small class="text-muted">{{ $nageur->birth_year }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </fieldset>
                            </div>
                        @endforeach

                        @if ($ungrouped->isNotEmpty())
                            <div class="col-md-6 col-xl-4">
                                <fieldset class="border rounded p-3 h-100" data-groupe>
                                    <legend class="float-none w-auto px-1 mb-1 fs-6 fw-semibold">Sans groupe</legend>
                                    @foreach ($ungrouped as $nageur)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="swimmers[]" value="{{ $nageur->id }}"
                                                   id="nageur-0-{{ $nageur->id }}" data-nageur="{{ $nageur->id }}">
                                            <label class="form-check-label" for="nageur-0-{{ $nageur->id }}">
                                                {{ $nageur->full_name }} <small class="text-muted">{{ $nageur->birth_year }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </fieldset>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-user-plus me-1" aria-hidden="true"></i>Convoquer les nageurs cochés
                    </button>
                    <span class="small text-muted ms-2">Les familles ne sont prévenues qu'au clic sur « Envoyer les convocations ».</span>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var cases = document.querySelectorAll('#convoquer [data-nageur]');

    // « Tout le groupe »
    document.querySelectorAll('#convoquer [data-tout]').forEach(function (tout) {
        tout.addEventListener('change', function () {
            tout.closest('[data-groupe]').querySelectorAll('[data-nageur]').forEach(function (c) {
                c.checked = tout.checked;
                c.dispatchEvent(new Event('change'));
            });
        });
    });

    // Un nageur présent dans plusieurs groupes : ses cases restent synchronisées (envoyé une seule fois)
    cases.forEach(function (c) {
        c.addEventListener('change', function () {
            cases.forEach(function (autre) {
                if (autre !== c && autre.dataset.nageur === c.dataset.nageur) {
                    autre.checked = c.checked;
                    autre.disabled = c.checked;
                }
            });
        });
    });
});
</script>
@endpush
