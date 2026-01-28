@extends('layouts.public')

@section('title', 'Plateforme Sportive pour tous - nataswim')
@section('meta_description', 'Decouvrez notre plateforme dediee A la natation et au triathlon avec articles, plans d\'entrainement, fiches techniques et videos. Rejoignez notre communaute de nageurs, triathletes et coachs.')

@section('content')
<!--  Section -->

<section class="text-white py-5 nataswim-titre">
    <div class="container-lg py-4">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-swimmer me-3 fs-1"></i>
                    <h1 class="display-4 fw-bold mb-0">nataswim</h1>
                </div>
                
                <p class="lead mb-4">
                    Optimisez vos entraînements, développez vos connaissances et formez-vous en continu grâce à cette plateforme  dédiée aux sportifs, techniciens, préparateurs physiques, entraîneurs et coachs — du débutant au professionnel.
                </p>
            </div>
            <div class="col-lg-5">
                
    <div class="text-center">
        <div class="position-relative d-inline-block bg-white rounded-circle">
           <a href="{{ route('public.index') }}"> <img src="{{ asset('assets/images/team/nataswim_app_logo_2.png') }}" 
                 alt="nataswim application pour tous" 
                 class="img-fluid" 
                 style="max-width: 200px;height: auto;box-shadow: 0 0 40px rgba(255,255,255,.8),0 0 10px #fff;border-radius: 100%;"></a>
        </div>
    </div>
</div>
        </div>
    </div>
</section>

<!-- Section recherche -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center mb-4">
                    <h2 class="h3 mb-2">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </h2>
                </div>
                @include('public.partials.search-form')
            </div>
        </div>
    </div>
</section>






<!-- Dernieres Publications -->
<section class="py-5">    

<div class="container-lg">

        
        @php
            $latestPosts = App\Models\Post::with('category')
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();
        @endphp
        
        @if($latestPosts->count() > 0)
            <div class="row g-4">
                @foreach($latestPosts as $post)
                    <div class="col-md-4">
                        <div class="card h-100 nataswim-ombre hover-lift border-0">
                            
                            <div class="card-body">
                                @if($post->category)
                                    <div class="mb-2">
                                        <span class="badge bg-primary">{{ $post->category->name }}</span>
                                    </div>
                                @endif
                                <h3 class="card-title h5 mb-3">{{ $post->name }}</h3>
                                @if($post->intro)
    <p class="card-text text-muted small">
        {!! Str::limit(strip_tags($post->intro), 100) !!}
    </p>
@endif
                            </div>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $post->published_at->format('d/m/Y') }}
                                </small>
                                <a href="{{ route('public.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                    Lire la suite
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info" role="alert">
                <i class="fas fa-water me-2"></i>Aucun article n'est disponible actuellement.
            </div>
        @endif
    </div>
</section>




<!-- Section Pour qui avec vidéo background -->
<section class="py-5 position-relative" >
    <!-- Vidéo d'arrière-plan -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1; overflow: hidden;">
        <iframe 
            src="https://www.youtube.com/embed/AhBaSV8psGA?autoplay=1&mute=1&loop=1&playlist=AhBaSV8psGA&controls=0&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3" 
            frameborder="0" 
            allow="autoplay; encrypted-media" 
            allowfullscreen
            style="width: 100vw; height: 100vh; min-width: 100%; min-height: 100%; object-fit: cover; pointer-events: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"
            title="Entrainement natation professionnel"
            loading="lazy">
        </iframe>
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark" style="opacity: 0.3;"></div>
    </div>

    <!-- Contenu -->
    <div class="container-lg position-relative" style="z-index: 2;">
        <div class="text-center mb-5 text-white">
            <h2 class="fw-bold display-6">Espace adapté à tous les profils </h2>
            <p class="lead">
                De l'amateur passionné au professionnel, en passant par les entraineurs et techniciens du sport
            </p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <article class="card h-100 nataswim-ombre border-0 bg-white bg-opacity-95">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary-subtle p-3 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-swimmer text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 mb-3">Sportives & Sportifs</h3>
                        <p class="card-text text-muted small mb-0">
                            Plans d'entrainement pour tous personnalisés pour tous niveaux. Techniques, seances, préparation physique et suivi de progression.
                        </p>
                    </div>
                </article>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <article class="card h-100 nataswim-ombre border-0 bg-white bg-opacity-95">
                    <div class="card-body text-center p-4">
                        <div class="bg-success-subtle p-3 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-medal text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 mb-3">Nageurs Triathlètes</h3>
                        <p class="card-text text-muted small mb-0">
                            Programmes triathlon complets. Optimisez votre segment natation, vélo et CAP avec nos plans d'entrainement spécialisés.
                        </p>
                    </div>
                </article>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <article class="card h-100 nataswim-ombre border-0 bg-white bg-opacity-95">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning-subtle p-3 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-users text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 mb-3">Entraineurs & Coachs</h3>
                        <p class="card-text text-muted small mb-0">
                            Outils professionnels pour créer et gérer vos programmes d'entrainement, plans et préparation physique.
                        </p>
                    </div>
                </article>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <article class="card h-100 nataswim-ombre border-0 bg-white bg-opacity-95">
                    <div class="card-body text-center p-4">
                        <div class="bg-info-subtle p-3 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-graduation-cap text-info" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 mb-3">Sportifs & Étudiants </h3>
                        <p class="card-text text-muted small mb-0">
                            Ressources pédagogiques, fiches techniques et outils pour votre formation en sciences du sport.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>







<section class="py-5">    
<div class="container-lg">
            

<div class="row mb-4">

            </div>

            <div class="row g-4">
                @php
                $recentFiches = App\Models\Fiche::where('is_published', true)
                ->where('visibility', 'public')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                    ->orderBy('created_at', 'desc')
                    ->limit(4)
                    ->get();
                    @endphp

                    @forelse($recentFiches as $fiche)
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 nataswim-ombre h-100 hover-lift">


                            <div class="card-body p-3">
                                @if($fiche->category)
                                <span class="badge bg-primary-subtle text-primary mb-2">
                                    {{ $fiche->category->name }}
                                </span>
                                @endif
                                <h6 class="card-title mb-2">
                                    <a href="{{ route('public.fiches.show', [$fiche->category, $fiche]) }}"
                                        class="text-decoration-none text-dark">
                                        {!! Str::limit($fiche->title, 50) !!}
                                    </a>
                                </h6>
                                @if($fiche->short_description)
                                <p class="card-text text-muted small mb-3">
                                    {!! Str::limit(strip_tags($fiche->short_description), 80) !!}
                                </p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>{{ $fiche->views_count ?? 0 }}
                                    </small>
                                    <a href="{{ route('public.fiches.show', [$fiche->category, $fiche]) }}"
                                        class="btn btn-light d-flex align-items-center px-4">
                                        Lire cette fiche
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-file-alt fa-3x mb-3 opacity-25"></i>
                            <p>Aucune fiche disponible</p>
                        </div>
                    </div>
                    @endforelse
            </div>
        </div>
</section>
















<!-- Fonctionnalités clés -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="fw-bold display-6">Outils & contenus </h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                Tout ce dont vous avez besoin pour progresser, comprendre et améliorer vos performances
            </p>
        </header>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <!-- 1. Séances & Plans -->
            <div class="col">
                <a href="{{ route('public.workouts.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header text-white nataswim-titre6">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">Séances & Plans</h4>
                                    @php
                                        $workoutSectionsCount = \App\Models\WorkoutSection::where('is_active', true)->count();
                                        $workoutsCount = \App\Models\Workout::count();
                                    @endphp
                                    <p class="mb-0 opacity-75">{{ $workoutSectionsCount }} sections • {{ $workoutsCount }} workouts</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Programmes structurés pour tous niveaux : technique, endurance, sprint. Plans hebdomadaires et cycles d'entraînement pour les sportifs.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- 2. Exercices spécialisés -->
            <div class="col">
                <a href="{{ route('exercices.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                         <div class="card-header text-white nataswim-titre1">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">Exercices spécialisés</h4>
                                    @php
                                        $exercicesCount = \App\Models\Exercice::where('is_active', true)->count();
                                    @endphp
                                    <p class="mb-0 opacity-75">{{ $exercicesCount }} {{ $exercicesCount > 1 ? 'exercices disponibles' : 'exercice disponible' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Bibliothèque d'exercices musculation, natation et préparation physique. Techniques détaillées avec vidéos et conseils professionnels.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- 3. Fiches techniques -->
            <div class="col">
                <a href="{{ route('public.fiches.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                         <div class="card-header text-white nataswim-titre2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">Fiches techniques</h4>
                                    @php
                                        $fichesCount = \App\Models\Fiche::where('is_published', true)->where('visibility', 'public')->count();
                                        $fichesCategoriesCount = \App\Models\FichesCategory::where('is_active', true)->count();
                                    @endphp
                                    <p class="mb-0 opacity-75">{{ $fichesCategoriesCount }} catégories • {{ $fichesCount }} fiches</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Des guides complets sur les techniques, préparation physique, entraînement, sciences, stratégies et plus.
                            </p>

                        </div>
                    </div>
                </a>
            </div>
            
            <!-- 4. Calculateurs & Outils -->
            <div class="col">
                <a href="{{ route('tools.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                         <div class="card-header text-white nataswim-titre3">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">Calculateurs & Outils</h4>
                                    <p class="mb-0 opacity-75">18 outils spécialisés disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Outils de calcul spécialisés : VNC, prédicteur de temps natation, zones cardiaques, planification triathlon.
                            </p>

                        </div>
                    </div>
                </a>
            </div>
            
            <!-- 5. Suivi de progression -->
            <div class="col">
                <div class="card h-100 shadow-lg border-0 bg-white category-card">
                    <div class="card-header text-white nataswim-titre4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h4 class="mb-1">Suivi de progression</h4>
                                <p class="mb-0 opacity-75">Disponible</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="card-text text-muted mb-3">
                            Enregistrez vos performances, analysez votre évolution avec graphiques et statistiques détaillés.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- 6. Ressources téléchargeables -->
            <div class="col">
                <a href="{{ route('ebook.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header text-white nataswim-titre5">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-1">Ressources téléchargeables</h4>
                                    @php
                                        $totalDownloads = \App\Models\Downloadable::where('status', 'active')->count();
                                        $downloadCategoriesCount = \App\Models\DownloadCategory::where('status', 'active')->count();
                                    @endphp
                                    <p class="mb-0 opacity-75">{{ $downloadCategoriesCount }} catégories • {{ $totalDownloads }} ressources</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Documents, guides techniques et supports pédagogiques pour techniciens, sportifs et entraîneurs.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>








<!-- Temoignages -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row g-4">
            @php
                $testimonials = [
                    [
                        'name' => 'Marie L. Nageuse competition',
                        'quote' => 'Les plans d entrainement natation m ont permis d améliorer mon 200m crawl de 4 secondes en 2 mois. Programmes structurés et progressifs parfaits.',
                        'role' => 'Nageuse competition'
                    ],
                    [
                        'name' => 'Thomas D. Coach natation',
                        'quote' => 'Outil indispensable pour gérer mes groupes d entrainement natation. Je crée facilement des programmes adaptés à chaque nageur.',
                        'role' => 'Entraineur'
                    ],
                    [
                        'name' => 'Sophie. Triathlete amateur',
                        'quote' => 'Parfait pour intégrer mes séances natation dans mon plan triathlon global. Calculateurs et outils très pratiques pour la préparation physique.',
                        'role' => 'Triathlete'
                    ]
                ];
            @endphp
            
            @foreach($testimonials as $testimonial)
                <div class="col-md-4">
                    <div class="card h-100 bg-white">
                        <div class="card-body p-4">
                            <p class="card-text mb-4 text-dark">"{{ $testimonial['quote'] }}"</p>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-subtle p-2 me-3">
                                    <i class="fas fa-swimmer text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold text-dark">{{ $testimonial['name'] }}</p>
                                    <small class="text-muted">{{ $testimonial['role'] }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>





<section class="py-5 text-white nataswim-titre3">    

       <div class="container-lg">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ route('public.workouts.index') }}" class="btn btn-lg btn-light d-block align-items-center px-4" style="width: 100%;text-align: left;">
                        <i class="fas fa-life-ring me-2"></i> Séances
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @php
                $recentWorkouts = App\Models\Workout::with(['categories.section'])
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
                @endphp

                @forelse($recentWorkouts as $workout)
                @php
                $firstCategory = $workout->categories->first();
                $section = $firstCategory?->section;
                @endphp
                @if($firstCategory && $section)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 nataswim-ombre h-100 hover-lift">


                        <div class="card-body p-3">
                            <span class="badge bg-info-subtle text-info mb-2">
                                {{ $firstCategory->name }}
                            </span>
                            <h6 class="card-title mb-2">
                                <a href="{{ route('public.workouts.show', [$section, $firstCategory, $workout]) }}"
                                    class="text-decoration-none text-dark">
                                    {!! Str::limit($workout->title, 50) !!}
                                </a>
                            </h6>
                            @if($workout->short_description)
                            <p class="card-text text-muted small mb-3">
                                {!! Str::limit(strip_tags($workout->short_description), 80) !!}
                            </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-ruler me-1"></i>{{ $workout->formatted_total ?? 'N/A' }}
                                </small>
                                <a href="{{ route('public.workouts.show', [$section, $firstCategory, $workout]) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-heartbeat fa-3x mb-3 opacity-25"></i>
                        <p>Aucune séance disponible</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
</section>


<section class="py-5 text-white nataswim-titre4">    

<div class="container-lg">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ route('ebook.index') }}" class="btn btn-lg btn-light d-block align-items-center px-4" style="width: 100%;text-align: left;">
                        <i class="fas fa-life-ring me-2"></i> Documents
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @php
                $recentDownloads = App\Models\Downloadable::with('category')
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
                @endphp

                @forelse($recentDownloads as $download)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 nataswim-ombre h-100 hover-lift">
  

                        <div class="card-body p-3">
                            @if($download->category)
                            <span class="badge bg-primary-subtle text-primary mb-2">
                                {{ $download->category->name }}
                            </span>
                            @endif
                            <h6 class="card-title mb-2">
                                <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}"
                                    class="text-decoration-none text-dark">
                                    {!! Str::limit($download->title, 50) !!}
                                </a>
                            </h6>
                            @if($download->short_description)
                            <p class="card-text text-muted small mb-3">
                                {!! Str::limit(strip_tags($download->short_description), 80) !!}
                            </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-download me-1"></i>{{ $download->download_count ?? 0 }}
                                </small>
                                <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-book fa-3x mb-3 opacity-25"></i>
                        <p>Aucun document disponible</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
</section>

<section class="py-5 text-white nataswim-titre5">    
<!-- Derniers Exercices -->
        <div class="container-lg">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ route('exercices.index') }}" class="btn btn-lg btn-light d-block align-items-center px-4" style="width: 100%;text-align: left;">
                           <i class="fas fa-life-ring me-2"></i> Exercices
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @php
                $recentExercices = App\Models\Exercice::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
                @endphp

                @forelse($recentExercices as $exercice)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 nataswim-ombre h-100 hover-lift">

                        <div class="card-body p-3">
                            <h6 class="card-title mb-2">
                                <a href="{{ route('exercices.show', $exercice) }}"
                                    class="text-decoration-none text-dark">
                                    {!! Str::limit($exercice->titre, 50) !!}
                                </a>
                            </h6>
                            @if($exercice->description)
                            <p class="card-text text-muted small mb-3">
                                {!! Str::limit(strip_tags($exercice->description), 80) !!}
                            </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                @if($exercice->muscles_cibles && count($exercice->muscles_cibles) > 0)
                                <small class="text-muted">
                                    <i class="fas fa-crosshairs me-1"></i>{{ count($exercice->muscles_cibles) }} muscle(s)
                                </small>
                                @else
                                <small class="text-muted">&nbsp;</small>
                                @endif
                                <a href="{{ route('exercices.show', $exercice) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-running fa-3x mb-3 opacity-25"></i>
                        <p>Aucun exercice disponible</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
</section>












@endsection

@push('styles')
<style>





.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

/* Cards catégories - Design amélioré */
.category-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 12px;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important;
}

.category-card .card-header {
    padding: 1.25rem;
}

.category-card .card-header h4 {
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
}

/* Effets hover spécifiques par couleur */
.category-card:hover .bg-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%) !important;
}

.category-card:hover .bg-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%) !important;
}

.category-card:hover .bg-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
}

.category-card:hover .bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #cc9a06 100%) !important;
}

.category-card:hover .bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #bb0a31 100%) !important;
}

.category-card:hover .bg-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #565e64 100%) !important;
}

/* Amélioration des badges */
.category-card .badge {
    font-size: 0.7rem;
    padding: 0.35rem 0.65rem;
    font-weight: 600;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1);
}
.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .display-4 {
        font-size: 1.75rem !important;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
        align-items: stretch !important;
    }
    
    .category-card .card-header h4 {
        font-size: 1.1rem;
    }
    
    .category-card .card-header i {
        font-size: 1.5rem !important;
    }
    
    .category-card .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Animation au chargement */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.category-card {
    animation: fadeInUp 0.6s ease-out;
}

.category-card:nth-child(1) { animation-delay: 0.1s; }
.category-card:nth-child(2) { animation-delay: 0.2s; }
.category-card:nth-child(3) { animation-delay: 0.3s; }
.category-card:nth-child(4) { animation-delay: 0.4s; }
.category-card:nth-child(5) { animation-delay: 0.5s; }
.category-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush