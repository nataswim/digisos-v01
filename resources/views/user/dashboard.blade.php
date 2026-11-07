@extends('layouts.public')

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

    {{-- Nageurs rattachés au compte : photo, groupe, licence --}}
    @if ($swimmers->isNotEmpty())
        <section class="mb-4" aria-labelledby="mes-nageurs">
            <h2 id="mes-nageurs" class="visually-hidden">{{ $swimmers->count() > 1 ? 'Mes nageurs' : 'Ma fiche nageur' }}</h2>
            <div class="row g-3">
                @foreach ($swimmers as $nageur)
                    <div class="{{ $swimmers->count() > 1 ? 'col-md-6' : 'col-12' }}">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4 member-card">
                                <x-member.avatar :swimmer="$nageur" size="lg" />
                                <div>
                                    <p class="h4 fw-bold mb-2">{{ $nageur->full_name }}</p>
                                    <dl>
                                        <dt>Groupe</dt>
                                        <dd>
                                            @forelse ($nageur->groups as $groupe)
                                                <span class="member-group" style="background: {{ $groupe->background_color }}; color: {{ $groupe->text_color }}">{{ $groupe->name }}</span>
                                            @empty
                                                <span class="text-muted">non renseigné</span>
                                            @endforelse
                                        </dd>
                                        <dt>Licence FFN</dt>
                                        <dd>{{ $nageur->license_label ?: 'non renseignée' }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
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
