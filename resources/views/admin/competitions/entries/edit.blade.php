@extends($layout)

@php $nageur = $attendance->swimmer; @endphp

@section('title', $nageur->full_name . ' — ' . $competition->title)

@section('content')
<div class="competitions-admin">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-swimmer text-primary me-2" aria-hidden="true"></i>{{ $nageur->full_name }}</h1>
            <p class="mb-0 text-muted">
                {{ $competition->title }} — {{ $competition->date_range }}
                @if ($competition->pool_length)
                    · bassin {{ $competition->pool_length }} m
                @endif
            </p>
        </div>
        <a href="{{ route($area . '.competitions.entries.index', $competition) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Tous les convoqués
        </a>
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

    <form method="POST" action="{{ route($area . '.competitions.entries.update', [$competition, $attendance]) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            {{-- Colonne gauche : statut, famille --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <dl class="row small mb-3">
                            <dt class="col-5 text-muted fw-normal">Année</dt>
                            <dd class="col-7 mb-1">{{ $nageur->birth_year ?: '—' }}</dd>
                            <dt class="col-5 text-muted fw-normal">Sexe</dt>
                            <dd class="col-7 mb-1">{{ $nageur->gender ?: '—' }}</dd>
                            <dt class="col-5 text-muted fw-normal">Licence</dt>
                            <dd class="col-7 mb-1">{{ $nageur->license_number ?: '—' }}</dd>
                            <dt class="col-5 text-muted fw-normal">Groupe(s)</dt>
                            <dd class="col-7 mb-1">{{ $nageur->groups->pluck('name')->implode(', ') ?: '—' }}</dd>
                            <dt class="col-5 text-muted fw-normal">Compte lié</dt>
                            <dd class="col-7 mb-0">{{ $nageur->user ? $nageur->user->name : 'aucun' }}</dd>
                        </dl>

                        <label for="status" class="form-label fw-semibold">Statut</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                            @foreach (\App\Models\CompetitionAttendance::STATUSES as $cle => [$libelle])
                                <option value="{{ $cle }}" @selected(old('status', $attendance->status) === $cle)>{{ $libelle }}</option>
                            @endforeach
                        </select>
                        @if ($attendance->responded_at)
                            <div class="form-text">
                                Réponse en ligne le {{ $attendance->responded_at->format('d/m/Y à H\hi') }}{{ $attendance->responder ? ' par ' . $attendance->responder->name : '' }}.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h6 fw-bold mb-3"><i class="fas fa-car text-primary me-2" aria-hidden="true"></i>Covoiturage et remarques</h2>
                        <div class="form-check mb-2">
                            <input type="hidden" name="needs_seat" value="0">
                            <input class="form-check-input" type="checkbox" id="needs_seat" name="needs_seat" value="1"
                                   @checked(old('needs_seat', $attendance->needs_seat))>
                            <label class="form-check-label" for="needs_seat">Cherche une place en voiture</label>
                        </div>
                        <label for="seats_offered" class="form-label">Places offertes par la famille</label>
                        <input type="number" id="seats_offered" name="seats_offered" min="0" max="8"
                               class="form-control mb-3 @error('seats_offered') is-invalid @enderror" style="max-width: 120px;"
                               value="{{ old('seats_offered', $attendance->seats_offered) }}">

                        <label for="family_comment" class="form-label">Commentaire de la famille</label>
                        <textarea id="family_comment" name="family_comment" rows="2" maxlength="500"
                                  class="form-control mb-3 @error('family_comment') is-invalid @enderror">{{ old('family_comment', $attendance->family_comment) }}</textarea>

                        <label for="staff_note" class="form-label">Note interne <span class="text-muted small">(jamais visible par la famille)</span></label>
                        <textarea id="staff_note" name="staff_note" rows="2" maxlength="500"
                                  class="form-control @error('staff_note') is-invalid @enderror">{{ old('staff_note', $attendance->staff_note) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Colonne droite : épreuves --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h6 fw-bold mb-1"><i class="fas fa-stopwatch text-primary me-2" aria-hidden="true"></i>Épreuves et temps d'engagement</h2>
                        @if ($fromProgram)
                            <p class="small mb-1"><i class="fas fa-filter text-primary me-1" aria-hidden="true"></i>Épreuves du programme{{ $nageur->gender ? ' (' . ($nageur->gender === 'F' ? 'Dames' : 'Messieurs') . ')' : '' }}.</p>
                        @else
                            <p class="small mb-1 text-muted"><i class="fas fa-info-circle me-1" aria-hidden="true"></i>Programme non saisi : toutes les épreuves individuelles sont proposées
                                (<a href="{{ route($area . '.competitions.program.edit', $competition) }}">saisir la grille des épreuves</a>).</p>
                        @endif
                        <p class="small text-muted mb-3">Cochez les épreuves nagées. Temps au format <code>1:02.34</code> ou <code>32.50</code> ; laissez vide pour « NT » (sans temps).</p>

                        <div class="row g-3">
                            @foreach ($events as $nage => $epreuves)
                                <div class="col-md-6">
                                    <fieldset class="border rounded p-2 h-100">
                                        <legend class="float-none w-auto px-1 mb-0 fs-6 fw-semibold">{{ \App\Models\SwimEvent::STROKES[$nage] ?? $nage }}</legend>
                                        @foreach ($epreuves as $epreuve)
                                            @php
                                                $engagement = $entries->get($epreuve->id);
                                                $coche = old('events.' . $epreuve->id . '.selected', $engagement ? '1' : '');
                                                $temps = old('events.' . $epreuve->id . '.time', $engagement ? $engagement->entry_time : '');
                                            @endphp
                                            <div class="d-flex align-items-center gap-2 py-1 epreuve-ligne">
                                                <div class="form-check mb-0 flex-grow-1">
                                                    <input type="hidden" name="events[{{ $epreuve->id }}][selected]" value="0">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           name="events[{{ $epreuve->id }}][selected]" id="epreuve-{{ $epreuve->id }}"
                                                           @checked($coche)>
                                                    <label class="form-check-label" for="epreuve-{{ $epreuve->id }}">{{ $epreuve->label }}</label>
                                                </div>
                                                <label for="temps-{{ $epreuve->id }}" class="visually-hidden">Temps d'engagement {{ $epreuve->label }}</label>
                                                <input type="text" id="temps-{{ $epreuve->id }}" name="events[{{ $epreuve->id }}][time]"
                                                       inputmode="decimal" autocomplete="off" placeholder="NT" maxlength="12"
                                                       class="form-control form-control-sm text-end @error('events.' . $epreuve->id . '.time') is-invalid @enderror"
                                                       style="width: 96px;" value="{{ $temps }}">
                                            </div>
                                        @endforeach
                                    </fieldset>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1" aria-hidden="true"></i>Enregistrer
            </button>
            <button type="submit" name="next" value="1" class="btn btn-outline-primary">
                <i class="fas fa-forward me-1" aria-hidden="true"></i>Enregistrer et nageur suivant
            </button>
            <a href="{{ route($area . '.competitions.entries.index', $competition) }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Saisir un temps coche l'épreuve
    document.querySelectorAll('.epreuve-ligne').forEach(function (ligne) {
        var temps = ligne.querySelector('input[type="text"]');
        var coche = ligne.querySelector('input[type="checkbox"]');
        temps.addEventListener('input', function () {
            if (temps.value.trim() !== '') {
                coche.checked = true;
            }
        });
    });
});
</script>
@endpush
