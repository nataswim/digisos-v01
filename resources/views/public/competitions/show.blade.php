@extends('layouts.public')

@section('title', $competition->title)
@section('meta_description', \Illuminate\Support\Str::limit($competition->excerpt ?: $competition->title . ' — ' . $competition->date_range . ', ' . $competition->venue . ', ' . $competition->city . '. Programme et informations pratiques pour les nageurs du CNBB.', 160))
@if ($competition->image_url)
    @section('og_image', $competition->image_url)
    @section('twitter_image', $competition->image_url)
    @section('og_image_alt', $competition->title)
@endif
@if ($competition->excerpt)
    @section('og_description', $competition->excerpt)
@endif
@section('og_title', $competition->title)

@section('content')

<x-public.hero
    :title="$competition->title"
    eyebrow="{{ $competition->level_label }} · {{ $competition->kind_label }}"
    icon="fa-trophy"
    :lead="$competition->date_range . ' — ' . $competition->venue . ', ' . $competition->city">
    <a href="{{ route('competitions.calendar.one', $competition) }}" class="btn btn-primary btn-lg competitions-btn">
        <i class="fas fa-calendar-plus me-2" aria-hidden="true"></i>Ajouter à mon agenda
    </a>
    <a href="{{ route('competitions.index') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Tout le calendrier
    </a>
</x-public.hero>


<section class="py-5 bg-light">
    <div class="container-lg">

        @if (! $competition->is_published)
            <div class="alert alert-warning" role="status">
                <i class="fas fa-eye-slash me-1" aria-hidden="true"></i>
                Aperçu : cette compétition n'est pas encore publiée. Seuls les administrateurs et éditeurs la voient.
            </div>
        @endif

        @if (in_array($competition->state, ['annulee', 'reportee'], true))
            <div class="alert alert-danger fw-semibold" role="alert">
                <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Compétition {{ mb_strtolower($competition->state_label) }}.
            </div>
        @endif

        @foreach ($myAttendances as $convocation)
            <div class="alert {{ $convocation->status === 'convoque' ? 'alert-warning' : 'alert-info' }} d-flex flex-wrap align-items-center justify-content-between gap-2" role="status">
                <span>
                    <i class="fas {{ $convocation->status_icon }} me-1" aria-hidden="true"></i>
                    <strong>{{ $convocation->swimmer->first_name }}</strong> est convoqué(e) à cette compétition —
                    {{ mb_strtolower($convocation->status_label) }}.
                </span>
                <a href="{{ route('user.competitions.index') }}#convocation-{{ $convocation->id }}" class="btn btn-sm btn-primary competitions-btn">
                    {{ $convocation->status === 'convoque' ? 'Répondre à la convocation' : 'Voir ou modifier ma réponse' }}
                </a>
            </div>
        @endforeach

        <div class="row g-4">
            {{-- ================================================== colonne principale --}}
            <div class="col-lg-8">

                {{-- En-tête --}}
                <article class="card border-0 shadow-sm mb-4 overflow-hidden">
                    @if ($competition->image)
                        <img src="{{ $competition->image }}" alt="" class="competition-cover" loading="eager">
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <span class="badge {{ $competition->state_badge }}">{{ $competition->state_label }}</span>
                            <span class="badge bg-light text-dark border">{{ $competition->level_label }}</span>
                            <span class="badge bg-light text-dark border">{{ $competition->kind_label }}</span>
                            @foreach ($competition->groups as $groupe)
                                <span class="badge" style="background: {{ $groupe->background_color }}; color: {{ $groupe->text_color }};">{{ $groupe->name }}</span>
                            @endforeach
                        </div>

                        <dl class="row mb-0 competition-facts">
                            <dt class="col-sm-4"><i class="fas fa-calendar-alt me-2 text-primary" aria-hidden="true"></i>Dates</dt>
                            <dd class="col-sm-8">{{ $competition->date_range }}
                                @if ($competition->duration_days > 1)<span class="text-muted">({{ $competition->duration_days }} jours)</span>@endif
                            </dd>

                            <dt class="col-sm-4"><i class="fas fa-map-marker-alt me-2 text-primary" aria-hidden="true"></i>Lieu</dt>
                            <dd class="col-sm-8">
                                {{ $competition->venue }}<br>
                                <span class="text-muted">{{ $competition->address ? $competition->address . ', ' : '' }}{{ $competition->city }}</span>
                            </dd>

                            @if ($competition->pool_label)
                                <dt class="col-sm-4"><i class="fas fa-swimming-pool me-2 text-primary" aria-hidden="true"></i>Bassin</dt>
                                <dd class="col-sm-8">{{ $competition->pool_label }}</dd>
                            @endif

                            @if ($competition->organizer)
                                <dt class="col-sm-4"><i class="fas fa-flag me-2 text-primary" aria-hidden="true"></i>Organisateur</dt>
                                <dd class="col-sm-8">{{ $competition->organizer }}</dd>
                            @endif
                        </dl>

                        @if ($competition->description_html)
                            <hr>
                            <div class="competition-description competition-content">{!! $competition->description_html !!}</div>
                        @endif
                    </div>
                </article>

                {{-- Programme --}}
                <section class="card border-0 shadow-sm mb-4" aria-labelledby="titre-programme">
                    <div class="card-body p-4">
                        <h2 id="titre-programme" class="h4 fw-bold mb-3"><i class="fas fa-list-ol text-primary me-2" aria-hidden="true"></i>Programme</h2>

                        @if ($sessionsByDay->isEmpty())
                            <p class="text-muted mb-0">Le programme sera publié prochainement.</p>
                        @else
                            @foreach ($sessionsByDay as $jour => $reunions)
                                <h3 class="h6 fw-bold text-uppercase competition-day mt-3">
                                    {{ ucfirst(\Illuminate\Support\Carbon::parse($jour)->locale('fr')->isoFormat('dddd D MMMM')) }}
                                </h3>
                                <div class="row g-3">
                                    @foreach ($reunions as $reunion)
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100">
                                                <p class="fw-semibold mb-2">{{ $reunion->label }}</p>
                                                <ul class="list-unstyled small mb-2">
                                                    @if ($reunion->doors_open_at)
                                                        <li><i class="fas fa-door-open fa-fw me-1 text-muted" aria-hidden="true"></i>Ouverture des portes : <strong>{{ \App\Models\CompetitionSession::formatTime($reunion->doors_open_at) }}</strong></li>
                                                    @endif
                                                    @if ($reunion->warmup_at)
                                                        <li><i class="fas fa-water fa-fw me-1 text-muted" aria-hidden="true"></i>Échauffement : <strong>{{ \App\Models\CompetitionSession::formatTime($reunion->warmup_at) }}</strong></li>
                                                    @endif
                                                    @if ($reunion->starts_at)
                                                        <li><i class="fas fa-stopwatch fa-fw me-1 text-muted" aria-hidden="true"></i>Début des épreuves : <strong>{{ \App\Models\CompetitionSession::formatTime($reunion->starts_at) }}</strong></li>
                                                    @endif
                                                </ul>
                                                @if ($reunion->programEvents->isNotEmpty())
                                                    <ol class="small mb-0 ps-3 competition-events">
                                                        @foreach ($reunion->programEvents as $epreuve)
                                                            <li>
                                                                {{ $epreuve->title }}
                                                                <span class="{{ $epreuve->round === 'series' ? 'text-muted' : 'fw-semibold competition-final' }}">— {{ $epreuve->round_label }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ol>
                                                    @if ($reunion->events)
                                                        <p class="small text-muted mt-2 mb-0">{!! nl2br(e($reunion->events)) !!}</p>
                                                    @endif
                                                @elseif ($reunion->event_list)
                                                    <ol class="small mb-0 ps-3 competition-events">
                                                        @foreach ($reunion->event_list as $epreuve)
                                                            <li>{{ $epreuve }}</li>
                                                        @endforeach
                                                    </ol>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </div>
                </section>

                {{-- Nageurs engagés (V2) --}}
                @if ($engaged->isNotEmpty())
                    <section class="card border-0 shadow-sm mb-4" aria-labelledby="titre-engages">
                        <div class="card-body p-4">
                            <h2 id="titre-engages" class="h4 fw-bold mb-1"><i class="fas fa-swimmer text-primary me-2" aria-hidden="true"></i>Nageurs du club engagés</h2>
                            <p class="text-muted small mb-3">{{ $engaged->count() }} nageur{{ $engaged->count() > 1 ? 's' : '' }} · temps d'engagement entre parenthèses (NT : sans temps)</p>
                            <ul class="list-unstyled row row-cols-1 row-cols-md-2 g-2 mb-0 competition-engages">
                                @foreach ($engaged as $engage)
                                    <li class="col">
                                        <div class="border rounded p-2 h-100">
                                            <span class="fw-semibold">{{ $isMember ? $engage->swimmer->full_name : $engage->swimmer->short_name }}</span>
                                            @if ($engage->sorted_entries->isNotEmpty())
                                                <div class="small text-muted">
                                                    @foreach ($engage->sorted_entries as $entry)
                                                        {{ $entry->event->label }} ({{ $entry->entry_time ?? 'NT' }}){{ $loop->last ? '' : ' · ' }}
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                @endif

                {{-- Logistique (adhérents) --}}
                <section class="card border-0 shadow-sm mb-4" aria-labelledby="titre-logistique">
                    <div class="card-body p-4">
                        <h2 id="titre-logistique" class="h4 fw-bold mb-3"><i class="fas fa-bus text-primary me-2" aria-hidden="true"></i>Informations pratiques</h2>

                        @if (! $isMember)
                            @include('public.competitions.partials.members-only', ['quoi' => 'Rendez-vous, transport, hébergement et repas'])
                        @elseif (! $competition->hasLogistics())
                            <p class="text-muted mb-0">Les informations de déplacement seront communiquées prochainement.</p>
                        @else
                            <dl class="row mb-0 competition-facts">
                                @if ($competition->meeting_point)
                                    <dt class="col-sm-4">Rendez-vous</dt>
                                    <dd class="col-sm-8">{{ $competition->meeting_point }}</dd>
                                @endif
                                @if ($competition->departure_at)
                                    <dt class="col-sm-4">Départ</dt>
                                    <dd class="col-sm-8">{{ ucfirst($competition->departure_at->locale('fr')->isoFormat('dddd D MMMM [à] H[ h ]mm')) }}</dd>
                                @endif
                                @if ($competition->return_at)
                                    <dt class="col-sm-4">Retour prévu</dt>
                                    <dd class="col-sm-8">{{ ucfirst($competition->return_at->locale('fr')->isoFormat('dddd D MMMM [vers] H[ h ]mm')) }}</dd>
                                @endif
                                @if ($competition->transport_label)
                                    <dt class="col-sm-4">Transport</dt>
                                    <dd class="col-sm-8">{{ $competition->transport_label }}</dd>
                                @endif
                                @if ($competition->accommodation)
                                    <dt class="col-sm-4">Hébergement</dt>
                                    <dd class="col-sm-8">{!! nl2br(e($competition->accommodation)) !!}</dd>
                                @endif
                                @if ($competition->meals)
                                    <dt class="col-sm-4">Repas</dt>
                                    <dd class="col-sm-8">{!! nl2br(e($competition->meals)) !!}</dd>
                                @endif
                                @if ($competition->logistics_notes)
                                    <dt class="col-sm-4">À savoir</dt>
                                    <dd class="col-sm-8">{!! nl2br(e($competition->logistics_notes)) !!}</dd>
                                @endif
                            </dl>
                        @endif

                        @if ($carpool)
                            <div class="border rounded p-3 mt-3 competition-carpool">
                                <h3 class="h6 fw-bold mb-2"><i class="fas fa-car text-primary me-2" aria-hidden="true"></i>Covoiturage</h3>
                                <p class="mb-2">
                                    <strong>{{ $carpool['offered'] }}</strong> place{{ $carpool['offered'] > 1 ? 's' : '' }} proposée{{ $carpool['offered'] > 1 ? 's' : '' }}
                                    · <strong>{{ $carpool['needed'] }}</strong> nageur{{ $carpool['needed'] > 1 ? 's' : '' }} cherche{{ $carpool['needed'] > 1 ? 'nt' : '' }} une place
                                </p>
                                @if ($carpool['offers']->isNotEmpty())
                                    <p class="small mb-1"><span class="text-muted">Places proposées par les familles de :</span>
                                        @foreach ($carpool['offers'] as [$nom, $places])
                                            {{ $nom }} ({{ $places }}){{ $loop->last ? '' : ', ' }}
                                        @endforeach
                                    </p>
                                @endif
                                @if ($carpool['seekers']->isNotEmpty())
                                    <p class="small mb-1"><span class="text-muted">Cherchent une place :</span> {{ $carpool['seekers']->implode(', ') }}</p>
                                @endif
                                <p class="small text-muted mb-0">L'encadrement organise la répartition ; indiquez vos besoins dans <a href="{{ route('user.competitions.index') }}">Mes compétitions</a>.</p>
                            </div>
                        @endif

                        <p class="small text-muted mt-3 mb-0">
                            <i class="fas fa-info-circle me-1" aria-hidden="true"></i>
                            Les nageurs confirment leur participation et leur moyen de transport
                            @if ($competition->response_deadline)
                                avant le <strong>{{ $competition->response_deadline->locale('fr')->isoFormat('dddd D MMMM') }}</strong>
                            @else
                                au plus tard 4 jours avant la compétition
                            @endif
                            (<a href="{{ url('/pages/regles-du-club/engagements-des-competiteurs') }}">engagements des compétiteurs</a>).
                        </p>
                    </div>
                </section>
            </div>

            {{-- ================================================== colonne latérale --}}
            <div class="col-lg-4">

                {{-- Liens officiels --}}
                @if ($competition->live_url || $competition->extranat_url)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h2 class="h6 fw-bold mb-3"><i class="fas fa-link text-primary me-2" aria-hidden="true"></i>Liens officiels</h2>
                            <div class="d-grid gap-2">
                                @if ($competition->live_url)
                                    <a href="{{ $competition->live_url }}" class="btn btn-outline-primary" target="_blank" rel="noopener">
                                        <i class="fas fa-satellite-dish me-1" aria-hidden="true"></i>Résultats en direct
                                    </a>
                                @endif
                                @if ($competition->extranat_url)
                                    <a href="{{ $competition->extranat_url }}" class="btn btn-outline-primary" target="_blank" rel="noopener">
                                        <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>Fiche extraNat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Encadrement --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3"><i class="fas fa-user-tie text-primary me-2" aria-hidden="true"></i>Encadrement du club</h2>
                        @if ($competition->staff->isEmpty())
                            <p class="text-muted small mb-0">À venir.</p>
                        @else
                            <ul class="list-unstyled mb-0">
                                @foreach ($competition->staff as $personne)
                                    <li class="mb-2">
                                        <strong>{{ $personne->name }}</strong>
                                        <span class="text-muted small d-block">{{ $personne->role_label }}</span>
                                        @if ($isMember && $personne->phone)
                                            <a href="tel:{{ $personne->phone_href }}" class="small"><i class="fas fa-phone me-1" aria-hidden="true"></i>{{ $personne->phone }}</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @if (! $isMember && $competition->staff->contains(fn ($p) => $p->phone))
                                <p class="small text-muted mt-2 mb-0"><i class="fas fa-lock me-1" aria-hidden="true"></i>Téléphones visibles par les adhérents connectés.</p>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Documents --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3"><i class="fas fa-file-pdf text-primary me-2" aria-hidden="true"></i>Documents</h2>
                        @if ($documents->isEmpty() && ! $hiddenDocuments)
                            <p class="text-muted small mb-0">Aucun document pour le moment.</p>
                        @endif
                        @if ($documents->isNotEmpty())
                            <ul class="list-unstyled mb-0">
                                @foreach ($documents as $document)
                                    <li class="mb-2">
                                        <a href="{{ route('competitions.document', [$competition, $document]) }}" target="_blank" rel="noopener">
                                            <i class="fas fa-file-pdf text-danger me-1" aria-hidden="true"></i>{{ $document->title }}
                                        </a>
                                        <small class="text-muted">(PDF, {{ $document->size_label }})</small>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($hiddenDocuments)
                            <p class="small text-muted mt-2 mb-0">
                                <i class="fas fa-lock me-1" aria-hidden="true"></i>
                                {{ $hiddenDocuments }} document{{ $hiddenDocuments > 1 ? 's' : '' }} réservé{{ $hiddenDocuments > 1 ? 's' : '' }} aux adhérents
                                @guest — <a href="{{ route('login') }}">se connecter</a>@endguest
                            </p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('schedule') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-calendar-week me-1" aria-hidden="true"></i>Planning des entraînements
                </a>
            </div>
        </div>
    </div>
</section>

@endsection


@push('styles')
<style>
    /* Image de couverture */
    .competition-cover { display: block; width: 100%; max-height: 360px; object-fit: cover; }

    /* Présentation saisie avec l'éditeur (classes produites par Quill) */
    .competition-content img { max-width: 100%; height: auto; border-radius: .375rem; }
    .competition-content iframe.ql-video,
    .competition-content iframe { display: block; width: 100%; max-width: 720px; aspect-ratio: 16 / 9; height: auto; border: 0; margin: 1rem auto; }
    .competition-content table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
    .competition-content th, .competition-content td { border: 1px solid #dee2e6; padding: .5rem; }
    .competition-content .ql-table-wrapper { overflow-x: auto; }
    .competition-content blockquote { border-left: 4px solid #2f80b8; padding-left: 1rem; color: #495057; }
    .competition-content .ql-align-center { text-align: center; }
    .competition-content .ql-align-right { text-align: right; }
    .competition-content .ql-align-justify { text-align: justify; }
    .competition-content .ql-size-small { font-size: .85em; }
    .competition-content .ql-size-large { font-size: 1.4em; }
    .competition-content .ql-size-huge { font-size: 2em; }
    .competition-content .ql-indent-1 { padding-left: 3em; }
    .competition-content .ql-indent-2 { padding-left: 6em; }
    .competition-content .ql-indent-3 { padding-left: 9em; }
    .competition-content p:last-child { margin-bottom: 0; }

    .competitions-btn,
    .competitions-btn i {
        background-color: #2f80b8;
        border-color: #2f80b8;
        color: #fff !important;
    }

    .competitions-btn i {
        background-color: transparent;
    }

    .competition-facts dt {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .competition-facts dd {
        margin-bottom: 0.75rem;
    }

    .competition-day {
        color: #2f80b8;
        border-bottom: 2px solid #f9be38;
        padding-bottom: 0.3rem;
        letter-spacing: 0.04em;
    }

    .competition-events li {
        padding: 0.1rem 0;
    }
</style>
@endpush
