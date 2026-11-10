@extends('layouts.member')

@section('title', 'Mon espace adhérent')

@section('content')

<div class="container-lg py-5">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Bonjour {{ $user->first_name ?: $user->name }}</h1>
            <p class="text-muted mb-0">Bienvenue dans votre espace adhérent.</p>
        </div>
        <a href="{{ route('user.profile.edit') }}" class="btn btn-outline-primary">
            <i class="fas fa-user-edit me-2" aria-hidden="true"></i>Mon profil
        </a>
    </div>

    <x-member.styles />

    {{-- Messages non lus (module Messagerie) --}}
    @if (! $user->hasRole('visitor'))
        @include('messages._dashboard', ['index' => 'user.messages.index', 'show' => 'user.messages.show'])
    @endif

    {{-- Nageurs rattachés au compte : photo, groupe et créneaux, licence, fiche adhérent --}}
    @if ($swimmers->isNotEmpty() || $otherMembers->isNotEmpty())
        <section class="mb-4" aria-labelledby="mes-nageurs">
            <h2 id="mes-nageurs" class="visually-hidden">{{ $swimmers->count() > 1 ? 'Mes nageurs' : 'Ma fiche nageur' }}</h2>
            @foreach ($swimmers as $nageur)
                @php $adherent = $memberFor[$nageur->id] ?? null; @endphp
                <article class="card border-0 shadow-sm mb-3" aria-labelledby="nageur-{{ $nageur->id }}">
                    <div class="card-body p-4">
                        <div class="member-card mb-3">
                            <x-member.avatar :swimmer="$nageur" size="lg" />
                            <div>
                                <h3 id="nageur-{{ $nageur->id }}" class="h4 fw-bold mb-2">{{ $nageur->full_name }}</h3>
                                <dl>
                                    <dt>Groupe</dt>
                                    <dd>
                                        @forelse ($nageur->groups as $groupe)
                                            <a href="{{ route('schedule') }}#groupe-{{ $groupe->slug }}" class="member-group text-decoration-none" style="background: {{ $groupe->background_color }}; color: {{ $groupe->text_color }}" title="Voir les horaires du groupe {{ $groupe->name }}">{{ $groupe->name }}</a>
                                        @empty
                                            <span class="text-muted">non renseigné</span>
                                        @endforelse
                                    </dd>
                                    @if ($nageur->birth_year)
                                        <dt>Année de naissance</dt>
                                        <dd>{{ $nageur->birth_year }}</dd>
                                    @endif
                                    <dt>Licence FFN</dt>
                                    <dd>{{ $nageur->license_label ?: 'non renseignée' }}</dd>
                                </dl>
                            </div>
                        </div>

                        <div class="row g-4">
                            {{-- Créneaux d'entraînement du ou des groupes --}}
                            <div class="{{ $adherent ? 'col-lg-6' : 'col-12' }}">
                                <h3 class="h6 fw-bold mb-2"><i class="fas fa-calendar-week text-primary me-2" aria-hidden="true"></i>Entraînements</h3>
                                @forelse ($nageur->groups as $groupe)
                                    @php
                                        $scolaire = $groupe->slots->where('period', 'scolaire');
                                        $vacances = $groupe->slots->where('period', 'vacances');
                                    @endphp
                                    <div class="member-slots mb-3">
                                        <p class="small fw-semibold mb-1">{{ $groupe->name }} <span class="text-muted fw-normal">· période scolaire</span></p>
                                        @if ($scolaire->isNotEmpty())
                                            <ul class="list-unstyled mb-1">
                                                @foreach ($scolaire as $creneau)
                                                    <li><strong>{{ $creneau->day_name }}</strong> {{ $creneau->time_range }}@if ($creneau->location) <span class="text-muted">· {{ $creneau->location }}</span>@endif</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="small text-muted mb-1">Pas de créneau publié pour l'instant.</p>
                                        @endif
                                        <p class="small mb-0">
                                            <a href="{{ route('schedule') }}#groupe-{{ $groupe->slug }}">Horaires du groupe</a>
                                            @if ($vacances->isNotEmpty()) · <a href="{{ route('schedule', ['periode' => 'vacances']) }}#groupe-{{ $groupe->slug }}">vacances scolaires ({{ $vacances->count() }} créneau{{ $vacances->count() > 1 ? 'x' : '' }})</a>@endif
                                        </p>
                                    </div>
                                @empty
                                    <p class="small text-muted mb-1">Aucun groupe pour l'instant. <a href="{{ route('schedule') }}">Voir tous les groupes et horaires</a>.</p>
                                @endforelse
                            </div>

                            {{-- Fiche adhérent --}}
                            @if ($adherent)
                                <div class="col-lg-6">
                                    @include('user.partials.member-file', ['member' => $adherent, 'season' => $season])
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach

            {{-- Fiches adhérent du compte sans nageur rattaché (adulte, bénévole, encadrant…) --}}
            @foreach ($otherMembers as $adherent)
                <article class="card border-0 shadow-sm mb-3" aria-labelledby="adherent-{{ $adherent->id }}">
                    <div class="card-body p-4">
                        <h3 id="adherent-{{ $adherent->id }}" class="h5 fw-bold mb-3">{{ $adherent->full_name }}</h3>
                        @include('user.partials.member-file', ['member' => $adherent, 'season' => $season])
                    </div>
                </article>
            @endforeach
        </section>
    @endif

    {{-- Compétitions auxquelles les nageurs du compte sont convoqués --}}
    @if ($swimmers->isNotEmpty())
        <section class="card border-0 shadow-sm mb-4" aria-labelledby="mes-convocations">
            <div class="card-header bg-white p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h2 id="mes-convocations" class="h5 fw-bold mb-0"><i class="fas fa-trophy text-primary me-2" aria-hidden="true"></i>Mes convocations</h2>
                <a href="{{ route('user.competitions.index') }}" class="small">Toutes mes compétitions</a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($convocations as $convocation)
                    @php $competition = $convocation->competition; @endphp
                    <li class="list-group-item p-3 p-md-4">
                        <div class="member-convoc">
                            <div class="member-convoc-main">
                                <span class="member-convoc-date" aria-hidden="true">
                                    <strong>{{ $competition->starts_on->format('d') }}</strong>{{ $competition->starts_on->locale('fr')->isoFormat('MMM') }}
                                </span>
                                <div class="min-w-0">
                                    <p class="fw-bold mb-1">{{ $competition->title }}</p>
                                    <p class="small text-muted mb-1">
                                        {{ $competition->date_range }} · {{ $competition->city }}
                                        @if ($swimmers->count() > 1) · <strong class="text-body">{{ $convocation->swimmer->first_name }}</strong>@endif
                                    </p>
                                    @if ($convocation->entries->isNotEmpty())
                                        <p class="small mb-0">{{ $convocation->sorted_entries->map(fn ($e) => $e->event?->label)->filter()->implode(' · ') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-md-end">
                                <span class="badge {{ $convocation->status_badge }}"><i class="fas {{ $convocation->status_icon }} me-1" aria-hidden="true"></i>{{ $convocation->status_label }}</span>
                                @if ($convocation->canBeAnsweredByFamily())
                                    <a href="{{ route('user.competitions.index') }}#convocation-{{ $convocation->id }}" class="btn btn-sm btn-primary ms-2">
                                        {{ $convocation->status === 'convoque' ? 'Répondre' : 'Modifier ma réponse' }}
                                    </a>
                                @endif
                                @if ($convocation->status === 'convoque' && $competition->response_deadline)
                                    <p class="small text-muted mb-0 mt-1">avant le {{ $competition->response_deadline->locale('fr')->isoFormat('D MMMM') }}</p>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item p-4 text-muted">Aucune convocation en cours. Les prochaines compétitions sont dans le <a href="{{ route('competitions.index') }}">calendrier</a>.</li>
                @endforelse
            </ul>
        </section>
    @endif

    {{-- Statut du compte --}}
    @include('layouts.partials.espace-accueil-statut', [
        'icon' => 'fa-user-check',
        'variante' => 'membre',
        'titre' => 'Vous êtes membre du club.',
        'texte' => 'Votre compte est validé : vous avez accès aux contenus réservés aux adhérents, à votre fiche et aux convocations de vos nageurs.',
    ])

    {{-- Mon espace --}}
    @include('layouts.partials.espace-accueil-tuiles', ['titre' => 'Mon espace', 'tuiles' => [
        ['icon' => 'fa-id-card', 'titre' => 'Ma fiche', 'texte' => 'Votre fiche adhérent.', 'route' => 'user.user-profile.show'],
        ['icon' => 'fa-person-swimming', 'titre' => 'Mes entraînements', 'texte' => 'Les séances préparées par les entraîneurs.', 'route' => 'user.training.index'],
        ['icon' => 'fa-stopwatch', 'titre' => 'Mes performances', 'texte' => 'Meilleurs temps et progression.', 'route' => 'user.performances.index'],
        ['icon' => 'fa-trophy', 'titre' => 'Mes compétitions', 'texte' => 'Les convocations de vos nageurs et vos réponses.', 'route' => 'user.competitions.index'],
        ['icon' => 'fa-clipboard-check', 'titre' => 'Assiduité', 'texte' => 'Les présences de vos nageurs aux entraînements.', 'route' => 'user.presence.index'],
        ['icon' => 'fa-user-edit', 'titre' => 'Mon profil', 'texte' => 'Vos coordonnées et votre mot de passe.', 'route' => 'user.profile.edit'],
        ['icon' => 'fa-download', 'titre' => 'Documents', 'texte' => 'Les documents mis à disposition par le club.', 'route' => 'ebook.index'],
    ]])

    {{-- Pages de l'entraîneur (sites externes, nouvel onglet) --}}
    @include('layouts.partials.espace-accueil-tuiles', ['titre' => 'Pages de votre entraîneur', 'variante' => 'entraineur',
        'intro' => "Articles, conseils, vidéos, séances et outils proposés par votre entraîneur sur nataswim.fr.", 'tuiles' => [
        ['icon' => 'fa-newspaper', 'titre' => 'Articles', 'texte' => 'Actualités et articles sur la natation.', 'url' => 'https://nataswim.fr/articles'],
        ['icon' => 'fa-lightbulb', 'titre' => 'Fiches', 'texte' => 'Expertise et conseils.', 'url' => 'https://nataswim.fr/fiches'],
        ['icon' => 'fa-video', 'titre' => 'Vidéos', 'texte' => 'Techniques et exercices en vidéo.', 'url' => 'https://nataswim.fr/videos'],
        ['icon' => 'fa-person-swimming', 'titre' => 'Séances et plans', 'texte' => "Séances et plans d'entraînement.", 'url' => 'https://nataswim.fr/workouts'],
        ['icon' => 'fa-dumbbell', 'titre' => 'Musculation', 'texte' => 'Exercices de préparation physique.', 'url' => 'https://nataswim.fr/exercices'],
        ['icon' => 'fa-toolbox', 'titre' => 'Outils et applis', 'texte' => 'Calculateurs et outils pour nageurs.', 'url' => 'https://nataswim.fr/outils'],
    ]])

    {{-- Le club --}}
    @include('layouts.partials.espace-accueil-tuiles', ['titre' => 'Le club', 'tuiles' => [
        ['icon' => 'fa-handshake', 'titre' => 'Nos partenaires', 'texte' => 'Les entreprises et collectivités qui soutiennent le club.', 'route' => 'partners.index'],
        ['icon' => 'fa-hand-holding-heart', 'titre' => 'Devenir mécène', 'texte' => "Soutenez le club et bénéficiez d'une réduction d'impôt.", 'route' => 'support.mecenat'],
        ['icon' => 'fa-heart', 'titre' => 'Faire un don', 'texte' => 'Un don par chèque ou par virement, avec reçu fiscal.', 'route' => 'support.donation'],
        ['icon' => 'fa-calendar-alt', 'titre' => 'Calendrier', 'texte' => 'Les compétitions et événements à venir.', 'route' => 'competitions.index'],
    ]])

    {{-- 4 derniers articles, fiches et pages (y compris ceux réservés aux adhérents) --}}
    @include('layouts.partials.espace-accueil-contenus')
</div>
@endsection
