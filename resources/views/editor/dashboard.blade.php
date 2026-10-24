@extends('layouts.public')

@section('title', 'Mon espace éditeur')

@section('content')

<div class="container-lg py-5">

    {{-- En-tête --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Bonjour {{ $user->first_name ?: $user->name }}</h1>
            <p class="text-muted mb-0">Bienvenue dans votre espace éditeur.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if (Route::has('editor.posts.create'))
                <a href="{{ route('editor.posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2" aria-hidden="true"></i>Écrire un article
                </a>
            @endif
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                <i class="fas fa-user-edit me-2" aria-hidden="true"></i>Mon profil
            </a>
        </div>
    </div>

    {{-- Statut du compte --}}
    @include('layouts.partials.espace-accueil-statut', [
        'icon' => 'fa-pen-nib',
        'variante' => 'editeur',
        'titre' => 'Vous êtes éditeur du site.',
        'texte' => 'Vous pouvez rédiger et mettre à jour les contenus du club : articles, pages, fiches, vidéos, galeries, compétitions et planning.',
    ])

    {{-- Outils de l'éditeur --}}
    @include('layouts.partials.espace-accueil-tuiles', ['titre' => "Mes outils d'éditeur", 'tuiles' => [
        ['icon' => 'fa-newspaper', 'titre' => 'Articles', 'texte' => 'Rédiger et publier les actualités.', 'route' => 'editor.posts.index',
            'badge' => $drafts->count() ? $drafts->count() . ' brouillon' . ($drafts->count() > 1 ? 's' : '') : null],
        ['icon' => 'fa-copy', 'titre' => 'Pages', 'texte' => 'Les pages du club.', 'route' => 'editor.pages.index'],
        ['icon' => 'fa-file-alt', 'titre' => 'Fiches', 'texte' => 'Les fiches pratiques.', 'route' => 'editor.fiches.index'],
        ['icon' => 'fa-video', 'titre' => 'Vidéos', 'texte' => 'Les vidéos du club.', 'route' => 'editor.videos.index'],
        ['icon' => 'fa-images', 'titre' => 'Galeries photos', 'texte' => 'Les albums photos.', 'route' => 'editor.photo-galleries.index'],
        ['icon' => 'fa-photo-video', 'titre' => 'Médiathèque', 'texte' => 'Images et fichiers.', 'route' => 'editor.media.index'],
        ['icon' => 'fa-trophy', 'titre' => 'Compétitions', 'texte' => 'Le calendrier et les convocations.', 'route' => 'editor.competitions.index'],
        ['icon' => 'fa-clock', 'titre' => 'Planning', 'texte' => 'Créneaux, groupes et nageurs.', 'route' => 'editor.planning.slots.index'],
        ['icon' => 'fa-handshake', 'titre' => 'Partenaires', 'texte' => 'Les partenaires du club.', 'route' => 'editor.partners.index'],
        ['icon' => 'fa-chart-line', 'titre' => 'Statistiques', 'texte' => 'La fréquentation des contenus.', 'route' => 'editor.stats'],
    ]])

    {{-- Mes brouillons --}}
    @if ($drafts->isNotEmpty() && Route::has('editor.posts.edit'))
        <section class="mb-5" aria-labelledby="titre-brouillons">
            <h2 id="titre-brouillons" class="h4 fw-bold mb-3">
                <i class="fas fa-pencil-alt me-2 text-primary" aria-hidden="true"></i>Mes brouillons
            </h2>
            <ul class="list-group shadow-sm">
                @foreach ($drafts as $draft)
                    <li class="list-group-item d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <span>
                            <span class="fw-semibold">{{ $draft->name }}</span>
                            <small class="text-muted ms-2">modifié le {{ $draft->updated_at?->format('d/m/Y') }}</small>
                        </span>
                        <a href="{{ route('editor.posts.edit', $draft) }}" class="btn btn-sm btn-outline-primary">
                            Continuer<span class="visually-hidden"> : {{ $draft->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- Le club --}}
    @include('layouts.partials.espace-accueil-tuiles', ['titre' => 'Le club', 'tuiles' => [
        ['icon' => 'fa-handshake', 'titre' => 'Nos partenaires', 'texte' => 'Les entreprises et collectivités qui soutiennent le club.', 'route' => 'partners.index'],
        ['icon' => 'fa-hand-holding-heart', 'titre' => 'Devenir mécène', 'texte' => "Soutenez le club et bénéficiez d'une réduction d'impôt.", 'route' => 'support.mecenat'],
        ['icon' => 'fa-heart', 'titre' => 'Faire un don', 'texte' => 'Un don par chèque ou par virement, avec reçu fiscal.', 'route' => 'support.donation'],
        ['icon' => 'fa-calendar-alt', 'titre' => 'Calendrier', 'texte' => 'Les compétitions et événements à venir.', 'route' => 'competitions.index'],
    ]])

    {{-- 4 derniers articles, fiches et pages publiés --}}
    @include('layouts.partials.espace-accueil-contenus')
</div>
@endsection
