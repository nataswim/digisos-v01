@extends('layouts.public')

@section('title', 'Mes compétitions')

@section('content')

<x-public.hero
    title="Mes compétitions"
    eyebrow="Espace adhérent"
    icon="fa-trophy"
    lead="Convocations de vos nageurs : confirmez la présence, indiquez vos besoins de transport et proposez des places en voiture.">
    <a href="{{ route('competitions.index') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-calendar-alt me-2" aria-hidden="true"></i>Calendrier des compétitions
    </a>
</x-public.hero>

<section class="py-5 bg-light">
    <div class="container-lg member-competitions">

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $erreur)
                        <li>{{ $erreur }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (! $hasSwimmers)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-user-friends fa-2x text-primary mb-3" aria-hidden="true"></i>
                    <h2 class="h5 fw-bold">Aucun nageur rattaché à votre compte</h2>
                    <p class="text-muted mb-3">
                        Pour recevoir et répondre aux convocations en ligne, demandez à l'entraîneur ou au secrétariat
                        de rattacher votre enfant (ou vous-même) à ce compte.
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-primary competitions-btn">Nous contacter</a>
                </div>
            </div>
        @elseif ($upcoming->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center text-muted">
                    <i class="fas fa-calendar-check fa-2x text-primary mb-3" aria-hidden="true"></i>
                    <p class="mb-0">Aucune convocation en cours. Les prochaines compétitions sont dans le <a href="{{ route('competitions.index') }}">calendrier</a>.</p>
                </div>
            </div>
        @endif

        {{-- Convocations à venir, regroupées par compétition --}}
        @foreach ($upcoming as $convocations)
            @php $competition = $convocations->first()->competition; @endphp
            <article class="card border-0 shadow-sm mb-4" aria-labelledby="competition-{{ $competition->id }}">
                <div class="card-header bg-white p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                        <div>
                            <h2 id="competition-{{ $competition->id }}" class="h5 fw-bold mb-1">
                                <a href="{{ route('competitions.show', $competition) }}" class="text-decoration-none">{{ $competition->title }}</a>
                            </h2>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-calendar-alt me-1" aria-hidden="true"></i>{{ $competition->date_range }}
                                · <i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>{{ $competition->venue }}, {{ $competition->city }}
                            </p>
                        </div>
                        <span class="badge {{ $competition->state_badge }}">{{ $competition->state_label }}</span>
                    </div>
                    @if ($competition->response_deadline)
                        <p class="small mb-0 mt-2 {{ $competition->isOpenForResponses() ? 'text-body' : 'text-muted' }}">
                            <i class="fas fa-hourglass-half me-1 text-warning" aria-hidden="true"></i>
                            Réponse attendue avant le <strong>{{ $competition->response_deadline->locale('fr')->isoFormat('dddd D MMMM') }}</strong>
                            @if (! $competition->isOpenForResponses())
                                — réponses en ligne closes, contactez l'entraîneur pour tout changement.
                            @endif
                        </p>
                    @endif
                </div>

                <div class="card-body p-4">
                    @foreach ($convocations as $convocation)
                        <div id="convocation-{{ $convocation->id }}" class="member-convocation {{ $loop->last ? '' : 'border-bottom pb-4 mb-4' }}">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <h3 class="h6 fw-bold mb-0">{{ $convocation->swimmer->first_name }}</h3>
                                <span class="badge {{ $convocation->status_badge }}"><i class="fas {{ $convocation->status_icon }} me-1" aria-hidden="true"></i>{{ $convocation->status_label }}</span>
                                @if ($convocation->responded_at)
                                    <small class="text-muted">réponse du {{ $convocation->responded_at->format('d/m/Y') }}</small>
                                @endif
                            </div>

                            @if ((int) session('answered') === $convocation->id)
                                <div class="alert alert-success py-2 small" role="status">
                                    <i class="fas fa-check-circle me-1" aria-hidden="true"></i>Réponse enregistrée, merci !
                                </div>
                            @endif

                            @if ($convocation->sorted_entries->isNotEmpty())
                                <p class="small mb-3">
                                    <span class="text-muted">Épreuves :</span>
                                    @foreach ($convocation->sorted_entries as $entry)
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">{{ $entry->event->label }} · {{ $entry->entry_time ?? 'NT' }}</span>
                                    @endforeach
                                </p>
                            @else
                                <p class="small text-muted mb-3">Les épreuves seront précisées par l'entraîneur.</p>
                            @endif

                            @if ($convocation->canBeAnsweredByFamily())
                                @php
                                    $id = $convocation->id;
                                    $estCetteFiche = (int) old('attendance_id') === $id;
                                    $statut = $estCetteFiche ? old('status') : ($convocation->status === 'convoque' ? null : $convocation->status);
                                @endphp
                                <form method="POST" action="{{ route('user.competitions.respond', $convocation) }}" class="member-response" data-reponse>
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="attendance_id" value="{{ $id }}">

                                    <fieldset class="mb-3">
                                        <legend class="fs-6 fw-semibold mb-2">{{ $convocation->swimmer->first_name }} sera :</legend>
                                        <div class="d-flex flex-wrap gap-2">
                                            <input type="radio" class="btn-check" name="status" value="confirme" id="present-{{ $id }}" autocomplete="off" required @checked($statut === 'confirme')>
                                            <label class="btn btn-outline-success member-choice" for="present-{{ $id }}"><i class="fas fa-check me-1" aria-hidden="true"></i>Présent(e)</label>

                                            <input type="radio" class="btn-check" name="status" value="absent" id="absent-{{ $id }}" autocomplete="off" @checked($statut === 'absent')>
                                            <label class="btn btn-outline-secondary member-choice" for="absent-{{ $id }}"><i class="fas fa-times me-1" aria-hidden="true"></i>Absent(e)</label>
                                        </div>
                                    </fieldset>

                                    <div class="row g-3 mb-3" data-transport>
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <input type="hidden" name="needs_seat" value="0">
                                                <input class="form-check-input" type="checkbox" name="needs_seat" value="1" id="place-{{ $id }}"
                                                       @checked($estCetteFiche ? old('needs_seat') : $convocation->needs_seat)>
                                                <label class="form-check-label" for="place-{{ $id }}">Nous cherchons une place en voiture</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="places-{{ $id }}" class="form-label small mb-1">Places que nous pouvons proposer</label>
                                            <input type="number" class="form-control form-control-sm" style="max-width: 110px;" min="0" max="8"
                                                   name="seats_offered" id="places-{{ $id }}"
                                                   value="{{ $estCetteFiche ? old('seats_offered') : $convocation->seats_offered }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="commentaire-{{ $id }}" class="form-label small mb-1">Commentaire pour l'entraîneur <span class="text-muted">(facultatif)</span></label>
                                        <textarea class="form-control" rows="2" maxlength="500" name="family_comment" id="commentaire-{{ $id }}"
                                                  placeholder="ex. arrivée tardive le samedi, allergie…">{{ $estCetteFiche ? old('family_comment') : $convocation->family_comment }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary competitions-btn">
                                        <i class="fas fa-paper-plane me-1" aria-hidden="true"></i>{{ $convocation->status === 'convoque' ? 'Envoyer ma réponse' : 'Modifier ma réponse' }}
                                    </button>
                                </form>
                            @else
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-lock me-1" aria-hidden="true"></i>
                                    @if ($convocation->status === 'forfait')
                                        Forfait enregistré par l'encadrement.
                                    @else
                                        Les réponses en ligne sont closes : pour tout changement, contactez l'entraîneur.
                                    @endif
                                    @if ($convocation->needs_seat)
                                        <br><i class="fas fa-car me-1" aria-hidden="true"></i>Place en voiture demandée.
                                    @endif
                                    @if ($convocation->seats_offered)
                                        <br><i class="fas fa-car-side me-1" aria-hidden="true"></i>{{ $convocation->seats_offered }} place(s) proposée(s).
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </article>
        @endforeach

        {{-- Historique --}}
        @if ($past->isNotEmpty())
            <section class="card border-0 shadow-sm" aria-labelledby="titre-historique">
                <div class="card-body p-4">
                    <h2 id="titre-historique" class="h5 fw-bold mb-3"><i class="fas fa-history text-primary me-2" aria-hidden="true"></i>Compétitions passées</h2>
                    <div class="table-responsive position-relative">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Compétition</th>
                                    <th scope="col">Nageur</th>
                                    <th scope="col">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($past as $convocation)
                                    <tr>
                                        <td class="text-nowrap">{{ $convocation->competition->starts_on->format('d/m/Y') }}</td>
                                        <td><a href="{{ route('competitions.show', $convocation->competition) }}">{{ $convocation->competition->title }}</a></td>
                                        <td>{{ $convocation->swimmer->first_name }}</td>
                                        <td><span class="badge {{ $convocation->status_badge }}">{{ $convocation->status_label }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    </div>
</section>
@endsection

@push('styles')
<meta name="robots" content="noindex, nofollow">
<style>
    .competitions-btn,
    .competitions-btn i {
        background-color: #2f80b8;
        border-color: #2f80b8;
        color: #fff !important;
    }
    .competitions-btn i { background-color: transparent; }
    .member-competitions .member-choice { min-width: 140px; }
    .member-competitions [data-transport].is-hidden { display: none; }
    .member-competitions .member-convocation { scroll-margin-top: 110px; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Le bloc transport n'a de sens que si le nageur est présent
    document.querySelectorAll('[data-reponse]').forEach(function (form) {
        var bloc = form.querySelector('[data-transport]');
        var maj = function () {
            var absent = form.querySelector('input[name="status"][value="absent"]');
            bloc.classList.toggle('is-hidden', absent && absent.checked);
        };
        form.querySelectorAll('input[name="status"]').forEach(function (r) { r.addEventListener('change', maj); });
        maj();
    });
});
</script>
@endpush
