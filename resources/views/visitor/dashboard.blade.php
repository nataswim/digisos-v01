@extends('layouts.public')

@section('title', 'Mon espace visiteur')

@section('content')

<div class="container-lg py-5">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Bonjour {{ $user->first_name ?: $user->name }}</h1>
            <p class="text-muted mb-0">Bienvenue dans votre espace sur le site du club.</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
            <i class="fas fa-user-edit me-2" aria-hidden="true"></i>Mon profil
        </a>
    </div>

    {{-- Statut du compte --}}
    @include('layouts.partials.espace-accueil-statut', [
        'icon' => 'fa-user-clock',
        'titre' => 'Vous avez le statut « visiteur ».',
        'texte' => "Un administrateur du club validera votre compte après vérification, pour vous permettre de voir plus de contenu et d'accéder à l'espace membre du club.",
    ])

    {{-- Accès rapides du club --}}
    @include('layouts.partials.espace-accueil-tuiles', ['titre' => 'Accès rapides', 'masquerTitre' => true, 'tuiles' => [
        ['icon' => 'fa-handshake', 'titre' => 'Nos partenaires', 'texte' => 'Les entreprises et collectivités qui soutiennent le club.', 'route' => 'partners.index'],
        ['icon' => 'fa-hand-holding-heart', 'titre' => 'Devenir mécène', 'texte' => "Soutenez le club et bénéficiez d'une réduction d'impôt.", 'route' => 'support.mecenat'],
        ['icon' => 'fa-heart', 'titre' => 'Faire un don', 'texte' => 'Un don par chèque ou par virement, avec reçu fiscal.', 'route' => 'support.donation'],
        ['icon' => 'fa-calendar-alt', 'titre' => 'Calendrier', 'texte' => 'Les compétitions et événements à venir.', 'route' => 'competitions.index'],
    ]])

    {{-- 4 derniers articles, fiches et pages --}}
    @include('layouts.partials.espace-accueil-contenus')
</div>
@endsection
