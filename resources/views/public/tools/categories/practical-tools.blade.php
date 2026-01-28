@extends('layouts.public')

@section('title', 'Outils Pratiques & Chronometrage - Utilitaires Sportifs Professionnels')
@section('meta_description', 'Outils pratiques pour l\'entraînement sportif : chronometrage professionnel multi-athletes, carte interactive parcours. Interface optimisee pour coaches et sportifs.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('tools.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-home me-1"></i>Outils
                    </a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">
                    Outils Pratiques & Chronometrage
                </li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-tools me-3"></i>
                    Outils Pratiques & Chronometrage
                </h1>
                <p class="lead mb-4">
                    Utilitaires pratiques pour l'entraînement et le suivi sportif. 
                    Outils optimises pour coaches, educateurs sportifs et pratiquants autonomes recherchant efficacite et simplicite d'usage.
                </p>
                <div class="alert alert-info border-0 bg-white bg-opacity-25">
                    <small>
                        <i class="fas fa-cogs me-2"></i>
                        <strong>2 outils disponibles</strong> - Interface intuitive et fonctionnalites avancees
                    </small>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle p-4 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px;">
                    <i class="fas fa-tools text-white" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Note utilisation responsable -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="alert alert-info border-0 mb-0">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <i class="fas fa-water fa-2x text-info"></i>
                </div>
                <div class="col-md-11">
                    <h6 class="fw-bold mb-2 text-info">Utilisation Responsable des Outils</h6>
                    <p class="mb-0 small">
                        <strong>Ces outils sont conçus pour faciliter l'entraînement et le suivi sportif.</strong> 
                        Respectez la confidentialite des donnees personnelles si vous chronometrez d'autres personnes. 
                        Pour la carte interactive, verifiez toujours les conditions de securite sur le terrain 
                        avant de planifier ou realiser un parcours sportif.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Outils de la categorie -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            
            <!-- 1. Chronometre Pro Groupe -->
            <div class="col-lg-6">
                <a href="{{ route('tools.chronometre-pro') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-stopwatch text-warning" style="font-size: 1.8rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Chronometre Pro Groupe</h5>
                                        <span class="badge bg-warning ms-2">Pro</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Chronometrage multi-athletes avance pour coaching professionnel. 
                                        Gestion simultanee de plusieurs coureurs avec fonctionnalites avancees : 
                                        tours automatiques, export donnees, analyse comparative.
                                    </p>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <small class="text-success d-flex align-items-center">
                                                <i class="fas fa-users me-1"></i>Multi-athletes
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-info d-flex align-items-center">
                                                <i class="fas fa-water me-1"></i>Export donnees
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-primary d-flex align-items-center">
                                                <i class="fas fa-chart-bar me-1"></i>Analyse temps
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-warning d-flex align-items-center">
                                                <i class="fas fa-redo me-1"></i>Tours auto
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-primary fw-semibold">Acceder A l'outil →</small>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>Temps reel</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 2. Carte Interactive -->
            <div class="col-lg-6">
                <a href="{{ route('tools.carte-interactive') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-map text-success" style="font-size: 1.8rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Carte Interactive</h5>
                                        <span class="badge bg-secondary ms-2">Pratique</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Planification parcours et geolocalisation sportive interactive. 
                                        Creation d'itineraires personnalises, calcul distances, 
                                        deniveles et partage de parcours pour course, velo, randonnee.
                                    </p>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <small class="text-primary d-flex align-items-center">
                                                <i class="fas fa-route me-1"></i>Trace parcours
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-warning d-flex align-items-center">
                                                <i class="fas fa-mountain me-1"></i>Deniveles
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-info d-flex align-items-center">
                                                <i class="fas fa-share-alt me-1"></i>Partage facile
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-success d-flex align-items-center">
                                                <i class="fas fa-mobile-alt me-1"></i>Mobile friendly
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-primary fw-semibold">Acceder A l'outil →</small>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>5-15 min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <!-- Suggestions d'usage -->
        <div class="text-center mt-5">
            <div class="card border-secondary">
                <div class="card-body py-3">
                    <h6 class="text-secondary mb-2">
                        <i class="fas fa-lightbulb me-2"></i>Suggestions d'Usage Optimal
                    </h6>
                    <p class="small text-muted mb-3">
                        Combinez ces outils avec nos calculateurs specialises pour une approche complete :
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('tools.category.swimming') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-swimmer me-1"></i>Chronometrage + VNC
                        </a>
                        <a href="{{ route('tools.category.running') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-running me-1"></i>Parcours + Planificateur
                        </a>
                        <a href="{{ route('tools.category.cardiac') }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-heart me-1"></i>Chronometrage + Zones FC
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="row g-3 mt-5">
            <div class="col-md-6">
                <a href="{{ route('tools.category.strength') }}" class="btn btn-outline-secondary btn-lg w-100">
                    <i class="fas fa-arrow-left me-2"></i>Force & Musculation
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('tools.category.development') }}" class="btn btn-secondary btn-lg w-100">
                    Outils en Developpement <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        
        <!-- Utilisation optimale des outils -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-chart-line me-2"></i>
                    Optimisation de l'Usage des Outils Pratiques
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-secondary">Chronometrage Efficace</h6>
                        <p class="small">
                            Le chronometrage precis est essentiel pour l'evaluation objective des performances 
                            et la planification d'entraînement. L'usage d'outils numeriques permet une 
                            collecte de donnees fiable et une analyse comparative dans le temps.
                        </p>
                        
                        <h6 class="text-primary mt-3">Bonnes Pratiques Chronometrage</h6>
                        <ul class="small">
                            <li><strong>Standardisation :</strong> Même protocole A chaque test</li>
                            <li><strong>Conditions similaires :</strong> Environnement, echauffement, moment</li>
                            <li><strong>Precision :</strong> Declenchement et arrêt nets</li>
                            <li><strong>Documentation :</strong> Contexte et conditions de mesure</li>
                            <li><strong>Repetabilite :</strong> Plusieurs mesures si possible</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Planification Parcours Securisee</h6>
                        <p class="small">
                            La planification numerique de parcours facilite la preparation mais ne remplace pas 
                            la reconnaissance terrain. La securite, la faisabilite technique et les autorisations 
                            necessaires doivent toujours être verifiees sur site.
                        </p>
                        
                        <h6 class="text-warning mt-3">Verifications Essentielles</h6>
                        <ul class="small">
                            <li><strong>Securite terrain :</strong> etat, obstacles, dangers potentiels</li>
                            <li><strong>Autorisations :</strong> Acces public, propriete privee</li>
                            <li><strong>Conditions meteo :</strong> Praticabilite selon saison</li>
                            <li><strong>Niveau technique :</strong> Adaptation capacites du groupe</li>
                            <li><strong>Points de sortie :</strong> Secours et evacuation possibles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confidentialite et ethique -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-shield-alt me-2"></i>
                    Confidentialite et Utilisation ethique
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-info">Protection des Donnees Personnelles</h6>
                        <p class="small">
                            Lors du chronometrage d'autres personnes ou du partage de parcours, 
                            respectez la confidentialite des donnees personnelles et sportives. 
                            Obtenez le consentement explicite pour l'enregistrement et le partage d'informations.
                        </p>
                        
                        <h6 class="text-warning mt-3">Bonnes Pratiques RGPD</h6>
                        <ul class="small">
                            <li><strong>Consentement explicite :</strong> Accord ecrit si necessaire</li>
                            <li><strong>Minimisation donnees :</strong> Collecter uniquement le necessaire</li>
                            <li><strong>Securisation :</strong> Protection contre acces non autorise</li>
                            <li><strong>Droit d'effacement :</strong> Possibilite de supprimer les donnees</li>
                            <li><strong>Finalite claire :</strong> Usage defini et limite</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Usage Pedagogique et Motivationnel</h6>
                        <p class="small">
                            Ces outils doivent servir l'amelioration de la performance et le plaisir de la pratique. 
                            evitez les comparaisons blessantes ou la pression excessive. 
                            Privilegiez l'encouragement et la progression individuelle.
                        </p>
                        
                        <h6 class="text-primary mt-3">Approche Constructive</h6>
                        <ul class="small">
                            <li><strong>Feedback positif :</strong> Souligner les progres realises</li>
                            <li><strong>Objectifs individualises :</strong> Respecter les capacites de chacun</li>
                            <li><strong>Apprentissage :</strong> Expliquer l'interêt des mesures</li>
                            <li><strong>Motivation intrinseque :</strong> Plaisir de l'effort et progression</li>
                            <li><strong>Bienveillance :</strong> Atmosphere d'entraide et respect mutuel</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h6><i class="fas fa-users me-2"></i>Responsabilite educative</h6>
                    <p class="mb-0 small">
                        Si vous utilisez ces outils dans un cadre educatif ou d'encadrement, 
                        <strong>votre responsabilite est de creer un environnement positif et securisant.</strong> 
                        Les donnees de performance ne doivent jamais servir A humilier ou exclure, 
                        mais toujours A encourager et personnaliser l'accompagnement.
                    </p>
                </div>
            </div>
        </div>

        <!-- Integration avec autres outils -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-link me-2"></i>
                    Integration avec l'ecosysteme d'Outils
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Synergies Recommandees</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Outil Pratique</th>
                                        <th>Combinaison Efficace</th>
                                        <th>Benefice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Chronometre Pro</td>
                                        <td>+ Zones Cardiaques</td>
                                        <td>Analyse intensite effort</td>
                                    </tr>
                                    <tr>
                                        <td>Chronometre Pro</td>
                                        <td>+ VNC Natation</td>
                                        <td>Tests seuils precis</td>
                                    </tr>
                                    <tr>
                                        <td>Carte Interactive</td>
                                        <td>+ Planificateur Course</td>
                                        <td>Parcours personnalises</td>
                                    </tr>
                                    <tr>
                                        <td>Carte Interactive</td>
                                        <td>+ Calculateur Hydratation</td>
                                        <td>Preparation conditions</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Workflow Recommande</h6>
                        <div class="timeline-container">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="small fw-bold">1. Planification</h6>
                                    <p class="small">Carte interactive pour parcours, calculateurs pour objectifs</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="small fw-bold">2. Preparation</h6>
                                    <p class="small">Zones cardiaques, hydratation, echauffement</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="small fw-bold">3. Execution</h6>
                                    <p class="small">Chronometrage precis, suivi temps reel</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="small fw-bold">4. Analyse</h6>
                                    <p class="small">Comparaison normes, planification progression</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-success alert-sm mt-3">
                            <h6 class="small">Approche Globale</h6>
                            <p class="small mb-0">
                                L'efficacite maximale provient de l'usage coordonne des outils, 
                                chacun apportant sa valeur ajoutee A l'ensemble du processus.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Section Credit et Contact -->
     <div class="card mb-4">
            <a href="{{ route('tools.index') }}" class="btn btn-success btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Essayer d'autres outils
            </a>
        </div>
<section class="py-5 bg-primary text-white">

    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">A Propos de nos Outils</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-info mb-2">Developpement & Expertise</h6>
                        <p class="mb-3">
                            Contenus et outils developpes par 
                            <a href="https://www.facebook.com/Sports.Ressources/" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="text-warning fw-bold text-decoration-none">
                                Med H El Haouat
                                <i class="fas fa-external-link-alt ms-1 small"></i>
                            </a>
                        </p>
                        <p class="small text-light opacity-75">
                            Expert en sciences du sport, physiologie de l'exercice et developpement 
                            d'outils d'aide A la performance sportive evidence-based.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success mb-2">Collaboration & Amelioration</h6>
                        <p class="mb-3 small">
                            Si vous constatez une erreur dans nos calculateurs ou souhaitez suggerer 
                            de nouveaux outils, n'hesitez pas A nous contacter.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-envelope me-2"></i>Nous Contacter
                            </a>
                            <a href="https://www.facebook.com/Sports.Ressources/" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fab fa-linkedin me-2"></i>LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="bg-white bg-opacity-10 rounded-circle p-2 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px; overflow: hidden;">
                    <img src="{{ asset('assets/images/team/med_Hassan_EL_HAOUAT.png') }}" 
                         alt="Med H El Haouat - Expert en sciences du sport" 
                         class="w-100 h-100 rounded-circle"
                         style="object-fit: cover;">
                </div>
                <div class="mt-3">
                    <h6 class="text-warning mb-1">Evidence-Based</h6>
                    <small class="text-light opacity-75">Recherches 2024 integrees</small>
                </div>
            </div>
        </div>
    </div>
</section>





<!-- Dernieres Publications -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-water text-primary me-2"></i>Dernieres Publications
            </h2>
            <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary">
                Tous les articles <i class="fas fa-angle-right ms-1"></i>
            </a>
        </div>
        
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
                        <div class="card h-100 shadow-sm hover-lift border-0">
                            <div style="height: 180px; overflow: hidden;">
                                @if($post->image)
                                    <img src="{{ $post->image }}" 
                                         alt="{{ $post->name }}"
                                         class="card-img-top"
                                         style="height: 100%; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                        <i class="fas fa-swimmer text-muted" style="font-size: 2.5rem;"></i>
                                    </div>
                                @endif
                            </div>
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
                                <a href="{{ route('posts.public.show', $post) }}" class="btn btn-sm btn-outline-primary">
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




@endsection

@push('styles')
<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.7);
}

.breadcrumb-item.active {
    color: rgba(255,255,255,0.9);
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.table th {
    border-top: none;
}

/* Timeline styles */
.timeline-container {
    position: relative;
    padding-left: 30px;
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 2px solid white;
    z-index: 1;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px 15px;
    border-radius: 5px;
    border-left: 3px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entree pour les cards
    const cards = document.querySelectorAll('.hover-lift');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush