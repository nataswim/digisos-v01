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
        ['icon' => 'fa-trophy', 'titre' => 'Mes compétitions', 'texte' => 'Les convocations de vos nageurs et vos réponses.', 'route' => 'user.competitions.index'],
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
