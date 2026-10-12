@extends('layouts.public')

@section('title', 'Club de natation à Bressuire')
@section('meta_description', 'Site officiel du Cercle des Nageurs du Bocage Bressuirais (CNBB), club de natation de Bressuire affilié à la Fédération Française de Natation, à la Fédération Française Handisport, à la Fédération Française du Sport Adapté et à la Fédération Française de Sauvetage et de Secourisme : école de natation, natation course, actualités et informations pratiques.')

@section('content')

@php
    $ageClub = now()->year - 1954;

    // Articles publiés (pas d'article programmé pour plus tard)
    $publies = fn () => App\Models\Post::with('category')
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now());

    // 2 articles mis en avant : d'abord ceux marqués « À la une », complétés par les plus récents
    $articlesUne = $publies()->where('is_featured', true)->latest('published_at')->limit(2)->get();
    if ($articlesUne->count() < 2) {
        $articlesUne = $articlesUne->concat(
            $publies()->whereNotIn('id', $articlesUne->pluck('id'))
                ->latest('published_at')->limit(2 - $articlesUne->count())->get()
        );
    }

    // 4 articles suivants, sans répéter ceux mis en avant
    $recentArticles = $publies()->whereNotIn('id', $articlesUne->pluck('id'))
        ->latest('published_at')->limit(4)->get();

    // Extrait de 200 mots en texte brut.
    // Article réservé aux adhérents et visiteur non autorisé : on n'affiche que l'introduction.
    $extrait = function ($post) {
        $reserve = $post->visibility === 'authenticated'
            && (auth()->guest() || auth()->user()->hasRole('visitor'));
        $source = $reserve ? $post->intro : ($post->content ?: $post->intro);
        // Une balise devient un espace (sinon « Titre</h2><p>Texte » donne « TitreTexte »)
        $texte  = preg_replace('/<[^>]*>/', ' ', (string) $source);
        $texte  = html_entity_decode($texte, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $texte  = trim(preg_replace('/\s+([,.)])/u', '$1', preg_replace('/\s+/u', ' ', $texte)));

        return Str::words($texte, 200, ' […]');
    };


    // Les groupes du club
    $groupes = [
        [
            'icon'    => 'fa-child',
            'titre'   => 'École de natation',
            'texte'   => 'Pour les plus jeunes : les bases des quatre nages et les tests de la Fédération (Sauv\'nage, Pass\'sports de l\'eau, Pass\'compétition).',
        ],
        [
            'icon'    => 'fa-swimmer',
            'titre'   => 'Natation course',
            'texte'   => 'Le cœur du club : l\'entraînement régulier et la compétition, du niveau départemental au niveau régional.',
        ],
        [
            'icon'    => 'fa-user-graduate',
            'titre'   => 'Jeunes et étudiants',
            'texte'   => 'Un tarif dédié pour continuer à nager pendant ses études, et la possibilité de se former à l\'encadrement des plus petits.',
        ],
        [
            'icon'    => 'fa-users',
            'titre'   => 'Adultes',
            'texte'   => 'Pour les adultes sachant nager qui veulent s\'entretenir et progresser dans une ambiance associative.',
        ],
    ];

    // Accès rapides
    $raccourcis = [
        ['icon' => 'fa-clipboard-list', 'titre' => 'Infos pratiques',   'url' => route('public.fiches.index')],
        ['icon' => 'fa-play-circle',    'titre' => 'Au fil de l\'eau',  'url' => route('public.videos.index')],
        ['icon' => 'fa-images',         'titre' => 'Galeries photo',    'url' => route('galleries.index')],
        ['icon' => 'fa-file-download',  'titre' => 'Ressources',        'url' => route('ebook.index')],
        ['icon' => 'fa-swimming-pool',  'titre' => 'Installations',     'url' => route('public.installations.index')],
        ['icon' => 'fa-life-ring',      'titre' => 'Guide du site',     'url' => route('guide')],
    ];
@endphp


<x-public.hero
    class="home-hero"
    title="Plongez à votre rythme !"
    eyebrow="Cercle des Nageurs du Bocage Bressuirais"
    icon="fa-swimmer"
    video="assets/images/team/CNBB-natation-1.mp4"
    lead="Bienvenue sur le site officiel du CNBB, le club de natation de Bressuire. De l'école de natation à la compétition, le club est un lieu d'apprentissage, de dépassement de soi et de convivialité.">
    <a href="#le-club" class="btn btn-primary btn-lg home-btn">
        <i class="fas fa-arrow-down me-2" aria-hidden="true"></i>Découvrir le club
    </a>
    <a href="{{ route('posts.public.index') }}" class="btn btn-outline-light btn-lg">
        <i class="fas fa-newspaper me-2" aria-hidden="true"></i>Vie du club
    </a>
</x-public.hero>


<!-- Le club en bref -->
<section id="le-club" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Un club de natation à Bressuire depuis 1954</h2>
                <p class="lead text-muted">
                    Association sportive affiliée à la Fédération Française de Natation,
                    à la Fédération Française Handisport, à la Fédération Française du Sport Adapté
                    et à la Fédération Française de Sauvetage et de Secourisme,
                    le CNBB est essentiellement spécialisé dans la natation course.
                </p>

                <p class="text-muted mb-4">
                    Nos nageurs s'entraînent au centre aquatique Cœur d'O. Enfants, jeunes, étudiants et adultes
                    y trouvent un groupe adapté à leur âge et à leur niveau, encadré par les entraîneurs
                    et porté par une équipe de bénévoles.
                </p>
                <a href="{{ route('about') }}" class="btn btn-outline-primary">
                    <i class="fas fa-water me-2" aria-hidden="true"></i>Présentation et historique du club
                </a>
            </div>

            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="display-5 fw-bold text-primary mb-2">1954</div>
                            <small class="text-muted">Année de création</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="display-5 fw-bold text-success mb-2">{{ $ageClub }} ans</div>
                            <small class="text-muted">D'histoire au bord des bassins</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="display-5 fw-bold text-warning mb-2">4</div>
                            <small class="text-muted">Fédérations : natation, handisport, sport adapté, sauvetage et secourisme</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="display-5 fw-bold text-info mb-2">Cœur d'O</div>
                            <small class="text-muted">Notre lieu d'entraînement à Bressuire</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Nager au CNBB -->
<section id="nager" class="anchor-section py-5 bg-light">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Nager au CNBB</h2>
            <p class="lead text-muted">Un groupe pour chaque âge, dès que l'on sait nager 25 mètres</p>
        </header>

        <div class="row g-4">
            @foreach ($groupes as $groupe)
                <div class="col-md-6 col-lg-3">
                    <article class="card border-0 shadow-sm h-100 text-center">
                        <div class="card-body p-4">
                            <div class="home-groupe-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-4">
                                <i class="fas {{ $groupe['icon'] }} fa-2x" aria-hidden="true"></i>
                            </div>
                            <h3 class="h5 fw-bold mb-3">{{ $groupe['titre'] }}</h3>
                            <p class="text-muted mb-0">{{ $groupe['texte'] }}</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('pricing') }}" class="btn btn-primary btn-lg home-btn">
                <i class="fas fa-euro-sign me-2" aria-hidden="true"></i>Tarifs et inscription
            </a>
        </div>
    </div>
</section>


<!-- Actualités -->
<section id="actualites" class="anchor-section py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="text-center mb-5">
            <h2 class="title-aqua-secondary home-banner-title py-4">
                <i class="fas fa-newspaper me-2" aria-hidden="true"></i>La passion de l'eau, l'esprit d'équipe.
            </h2>
            <p class="text-muted">Résultats, événements et informations pratiques : restez informés de la vie du club.</p>
        </div>

        {{-- Deux articles mis en avant, avec un extrait de 200 mots --}}
        @if ($articlesUne->isNotEmpty())
            <div class="row g-4 mb-5">
                @foreach ($articlesUne as $article)
                    @php $date = $article->published_at ?? $article->created_at; @endphp
                    <div class="col-lg-6">
                        <article class="card border-0 shadow-sm h-100 home-article-une">
                            @if ($article->image)
                                <img src="{{ $article->image }}" class="card-img-top home-article-une-img" alt="" loading="lazy">
                            @endif
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                    <span class="badge badge-primary">{{ $article->category->name ?? 'Actualité' }}</span>
                                    @if ($article->is_featured)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-star me-1" aria-hidden="true"></i>À la une
                                        </span>
                                    @endif
                                    @if ($date)
                                        <small class="text-muted ms-auto">
                                            <time datetime="{{ $date->format('Y-m-d') }}">{{ $date->format('d/m/Y') }}</time>
                                        </small>
                                    @endif
                                </div>

                                <h3 class="h4 fw-bold mb-3">
                                    <a href="{{ route('posts.public.show', $article) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ $article->name }}
                                    </a>
                                </h3>

                                <p class="text-muted mb-4">{{ $extrait($article) }}</p>

                                <div class="mt-auto">
                                    <a href="{{ route('posts.public.show', $article) }}" class="btn btn-primary home-btn">
                                        Lire l'article<span class="visually-hidden"> : {{ $article->name }}</span>
                                        <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Les 4 articles suivants --}}
        <div class="row g-4 mb-4">
            @forelse ($recentArticles as $article)
                <div class="col-md-6 col-lg-3">
                    <x-public.post-card :post="$article" :intro-limit="80" />
                </div>
            @empty
                @if ($articlesUne->isEmpty())
                    <div class="col-12">
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-newspaper fa-3x mb-3 opacity-25" aria-hidden="true"></i>
                            <p class="mb-0">Les premières actualités arrivent bientôt.</p>
                        </div>
                    </div>
                @endif
            @endforelse
        </div>

        <div class="text-center">
            <a href="{{ route('posts.public.index') }}" class="btn btn-primary btn-lg home-btn">
                <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Toute la vie du club
            </a>
        </div>
    </div>
</section>


<!-- Accès rapides -->
<section id="acces-rapides" class="anchor-section py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Accès rapides</h2>
            <p class="lead text-muted">Toutes les rubriques du site en un clic</p>
        </header>

        <div class="row g-3 justify-content-center">
            @foreach ($raccourcis as $raccourci)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ $raccourci['url'] }}" class="home-quick-link card border-0 shadow-sm h-100 text-center text-decoration-none p-3">
                        <i class="fas {{ $raccourci['icon'] }} fa-2x text-primary mb-2" aria-hidden="true"></i>
                        <span class="fw-semibold text-dark">{{ $raccourci['titre'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Nous rejoindre -->
<section class="py-5 bg-primary text-white home-join">
    <div class="container-lg text-center">
        <h2 class="display-6 fw-bold mb-3 text-white">Envie de nager avec nous ?</h2>
        <p class="lead mb-2">Les inscriptions se font sur dossier, à déposer dans la boîte aux lettres du club.</p>
        <p class="mb-4">
            <a href="mailto:cnbb079@gmail.com" class="text-white">cnbb079@gmail.com</a>
            <span class="mx-2" aria-hidden="true">·</span>
            <a href="tel:+33602350843" class="text-white">06 02 35 08 43</a>
            <span class="mx-2" aria-hidden="true">·</span>
            40 boulevard de la République, 79300 Bressuire
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="{{ route('pricing') }}" class="btn btn-light btn-lg">
                <i class="fas fa-clipboard-check me-2" aria-hidden="true"></i>S'inscrire au club
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-envelope me-2" aria-hidden="true"></i>Nous contacter
            </a>
        </div>
    </div>
</section>

@endsection


@push('styles')
<style>
    /* Boutons bleus de l'accueil : texte et icône toujours blancs,
       quelle que soit la couleur de lien définie par le thème */
    .home-btn,
    .home-btn:visited,
    .home-btn i {
        background-color: #2f80b8;
        border-color: #2f80b8;
        color: #fff !important;
    }

    .home-btn:hover,
    .home-btn:focus {
        background-color: #24668f;
        border-color: #24668f;
        color: #fff !important;
    }

    .home-btn i {
        background-color: transparent;
    }

    /* Bandeau « Envie de nager avec nous ? » : titres en blanc */
    .home-join h2,
    .home-join .lead {
        color: #fff !important;
    }

    /* En-tête vidéo plus haut sur l'accueil */
    .home-hero .min-vh-50 {
        min-height: 80vh;
    }

    @media (max-width: 768px) {
        .home-hero .min-vh-50 {
            min-height: 65vh;
        }
    }

    /* Pastilles des groupes : fond bleu, icône blanche */
    .home-groupe-icon {
        width: 80px;
        height: 80px;
        background-color: #2f80b8 !important;
    }

    .home-groupe-icon i {
        color: #fff !important;
    }

    /* Articles mis en avant */
    .home-article-une-img {
        height: 220px;
        object-fit: cover;
    }

    .home-article-une p {
        line-height: 1.7;
    }

    /* Bandeau de titre des actualités */
    .home-banner-title {
        background-image: linear-gradient(129deg, #f9be38 85%, #2f80b8 0);
        color: #1c2111;
        box-shadow: 0 5px 6px 4px rgba(0, 0, 0, 0.05);
        border-radius: 15px 0 15px 0;
    }

    /* Tuiles d'accès rapide */
    .home-quick-link {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .home-quick-link:hover,
    .home-quick-link:focus {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(47, 128, 184, 0.18) !important;
    }

    @media (prefers-reduced-motion: reduce) {
        .home-quick-link {
            transition: none;
        }

        .home-quick-link:hover,
        .home-quick-link:focus {
            transform: none;
        }
    }
</style>
@endpush