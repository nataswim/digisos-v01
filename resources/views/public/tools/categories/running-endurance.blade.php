@extends('layouts.public')

@section('title', 'Outils Course A Pied & Endurance - Planification Securisee Evidence-Based')
@section('meta_description', 'Outils scientifiques pour course A pied et endurance : planification progressive, prevention blessures, approche securisee. Biomecanique et physiologie de l\'endurance evidence-based.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('tools.index') }}" class="text-dark text-decoration-none">
                        <i class="fas fa-home me-1"></i>Outils
                    </a>
                </li>
                <li class="breadcrumb-item active text-dark" aria-current="page">
                    Course A Pied & Endurance
                </li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-running me-3"></i>
                    Course A Pied & Endurance
                </h1>
                <p class="lead mb-4">
                    Planification intelligente et securisee pour la course A pied. 
                    Approche progressive basee sur la biomecanique, la physiologie de l'endurance et la prevention des blessures.
                </p>
                <div class="alert alert-info border-0 bg-white bg-opacity-75">
                    <small>
                        <i class="fas fa-route me-2"></i>
                        <strong>1 outil disponible</strong> - Planification progressive et securisee
                    </small>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-25 rounded-circle p-4 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px;">
                    <i class="fas fa-running text-dark" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avertissement progression graduelle -->
<section class="py-4 bg-danger text-white">
    <div class="container">
        <div class="alert alert-light border-0 mb-0">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                </div>
                <div class="col-md-11">
                    <h6 class="fw-bold mb-2 text-danger">Progression Graduelle Obligatoire</h6>
                    <p class="mb-0 small text-dark">
                        <strong>La course A pied presente un risque eleve de blessures sans progression appropriee.</strong> 
                        Augmentation maximum 10% du volume/semaine, respect des signaux corporels, equipement adapte et 
                        consultation medicale recommandee avant tout programme d'entraînement, particulierement apres 40 ans 
                        ou en cas d'antecedents cardiovasculaires, articulaires ou de sedentarite prolongee.
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
            
            <!-- 1. Planificateur Course A Pied -->
            <div class="col-lg-8 mx-auto">
                <a href="{{ route('tools.running-planner') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-route text-primary" style="font-size: 2rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h4 class="card-title mb-0 text-dark fw-bold">Planificateur Course A Pied</h4>
                                        <span class="badge bg-primary ms-2">Avance</span>
                                    </div>
                                    <p class="card-text text-muted mb-4">
                                        Plans d'entraînement personnalises avec progression graduelle securisee. 
                                        Planification intelligente respectant les principes physiologiques, 
                                        la prevention des blessures et l'adaptation individuelle pour un developpement durable.
                                    </p>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-shield-alt text-success me-2"></i>
                                                <small class="text-success fw-semibold">Progression securisee</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-md text-info me-2"></i>
                                                <small class="text-info fw-semibold">Approche medicale</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-heart text-danger me-2"></i>
                                                <small class="text-danger fw-semibold">Bien-être prioritaire</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="text-primary fw-bold fs-5">Acceder A l'outil →</span>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>10-15 min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <!-- Message outils complementaires -->
        <div class="text-center mt-5">
            <div class="card border-warning">
                <div class="card-body py-3">
                    <h6 class="text-warning mb-2">
                        <i class="fas fa-tools me-2"></i>Outils Complementaires Recommandes
                    </h6>
                    <p class="small text-muted mb-3">
                        Pour une approche complete de la course A pied, combinez avec nos autres outils :
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('tools.category.cardiac') }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-heart me-1"></i>Zones Cardiaques
                        </a>
                        <a href="{{ route('tools.category.nutrition') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-apple-alt me-1"></i>Hydratation
                        </a>
                        <a href="{{ route('tools.category.health') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-heartbeat me-1"></i>Composition Corporelle
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="row g-3 mt-5">
            <div class="col-md-6">
                <a href="{{ route('tools.category.swimming') }}" class="btn btn-outline-warning btn-lg w-100">
                    <i class="fas fa-arrow-left me-2"></i>Sports Aquatiques
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('tools.category.strength') }}" class="btn btn-warning btn-lg w-100">
                    Force & Musculation <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        
        <!-- Biomecanique de la course -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-cogs me-2"></i>
                    Biomecanique et Technique de Course
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-warning">Cycle de Course et Efficacite</h6>
                        <p class="small">
                            La course implique des impacts repetes (2-3x le poids du corps) et une coordination complexe. 
                            L'efficacite resulte de l'equilibre entre economie gestuelle, absorption des chocs 
                            et propulsion. La technique naturelle est generalement la plus efficace pour chaque individu.
                        </p>
                        
                        <h6 class="text-primary mt-3">Parametres Techniques Cles</h6>
                        <ul class="small">
                            <li><strong>Cadence :</strong> 170-190 pas/minute optimal pour la plupart</li>
                            <li><strong>Longueur foulee :</strong> Adaptation naturelle selon vitesse</li>
                            <li><strong>Attaque pied :</strong> Talon, medio-pied ou avant-pied selon morphologie</li>
                            <li><strong>Posture :</strong> Legere inclinaison avant, core engage</li>
                            <li><strong>Balancement bras :</strong> Mouvement naturel, decontracte</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Progression Technique Saine</h6>
                        <div class="alert alert-success alert-sm">
                            <h6 class="small">Principe Fondamental</h6>
                            <p class="small mb-0">
                                <strong>Ne pas forcer de changements techniques majeurs.</strong> 
                                La technique de course est largement determinee par la morphologie individuelle. 
                                Les modifications doivent être progressives et accompagnees.
                            </p>
                        </div>
                        
                        <h6 class="text-info mt-3">Facteurs Individuels</h6>
                        <ul class="small">
                            <li>Morphologie (taille, proportions membres)</li>
                            <li>Flexibilite articulaire (cheville, hanche, thorax)</li>
                            <li>Historique blessures et adaptations</li>
                            <li>Condition physique et experience</li>
                            <li>Type de terrain habituel</li>
                        </ul>
                        
                        <div class="alert alert-warning alert-sm">
                            <h6 class="small">Changements Techniques</h6>
                            <p class="small mb-0">
                                Toute modification technique peut initialement augmenter le risque de blessure. 
                                Progression tres graduelle et surveillance necessaires.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prevention des blessures -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-2">
                    <i class="fas fa-shield-alt me-2"></i>
                    Prevention des Blessures en Course A Pied
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-danger">Blessures Courantes et Causes</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Blessure</th>
                                        <th>Frequence</th>
                                        <th>Cause Principale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tendinopathie d'Achille</td>
                                        <td>15-20%</td>
                                        <td>Progression trop rapide</td>
                                    </tr>
                                    <tr>
                                        <td>Syndrome rotulien</td>
                                        <td>10-15%</td>
                                        <td>Desequilibres musculaires</td>
                                    </tr>
                                    <tr>
                                        <td>Periostite tibiale</td>
                                        <td>8-12%</td>
                                        <td>Augmentation brutale volume</td>
                                    </tr>
                                    <tr>
                                        <td>Syndrome IT Band</td>
                                        <td>5-8%</td>
                                        <td>Faiblesse muscles stabilisateurs</td>
                                    </tr>
                                    <tr>
                                        <td>Fascite plantaire</td>
                                        <td>5-10%</td>
                                        <td>Raideur mollet/pied</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Strategies Preventives Evidence-Based</h6>
                        <ul class="small">
                            <li><strong>Regle des 10% :</strong> Augmentation hebdomadaire maximum</li>
                            <li><strong>Renforcement cible :</strong> Muscles stabilisateurs (hanches, core)</li>
                            <li><strong>Mobilite reguliere :</strong> etirements dynamiques pre-course</li>
                            <li><strong>Recuperation programmee :</strong> Jours de repos obligatoires</li>
                            <li><strong>ecoute corporelle :</strong> Arrêt si douleur persistante</li>
                            <li><strong>Surfaces variees :</strong> eviter monotonie (asphalte uniquement)</li>
                        </ul>
                        
                        <h6 class="text-warning mt-3">equipement Preventif</h6>
                        <div class="alert alert-info alert-sm">
                            <h6 class="small">Chaussures de Course</h6>
                            <p class="small mb-0">
                                element preventif crucial : changement tous les 500-800km, 
                                choix selon type de foulee et morphologie. 
                                <strong>Conseil specialise en magasin recommande.</strong>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger mt-4">
                    <h6><i class="fas fa-stop me-2"></i>Signaux d'Arrêt Immediat</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <ul class="small mb-0">
                                <li>Douleur aiguë pendant la course</li>
                                <li>Douleur persistante plus de 48h</li>
                                <li>Gonflement ou inflammation visible</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="small mb-0">
                                <li>Boiterie ou compensation gestuelle</li>
                                <li>Douleur nocturne ou au repos</li>
                                <li>Symptômes systemiques (fievre, malaise)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Physiologie de l'endurance -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-heartbeat me-2"></i>
                    Physiologie de l'Endurance et Adaptations
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Systemes energetiques</h6>
                        <p class="small">
                            L'endurance sollicite principalement le systeme aerobie, avec contribution 
                            anaerobie selon l'intensite. Les adaptations cardiovasculaires, 
                            respiratoires et musculaires s'installent progressivement (6-12 semaines minimum).
                        </p>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Distance</th>
                                        <th>Systeme Principal</th>
                                        <th>Duree Effort</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>5km</td>
                                        <td>Aerobie + Anaerobie</td>
                                        <td>15-30 min</td>
                                    </tr>
                                    <tr>
                                        <td>10km</td>
                                        <td>Aerobie dominant</td>
                                        <td>30-60 min</td>
                                    </tr>
                                    <tr>
                                        <td>Semi-marathon</td>
                                        <td>Aerobie (95%)</td>
                                        <td>1h-2h30</td>
                                    </tr>
                                    <tr>
                                        <td>Marathon</td>
                                        <td>Aerobie (98%)</td>
                                        <td>2h30-6h</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Adaptations Chronologiques</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Periode</th>
                                        <th>Adaptations</th>
                                        <th>Manifestations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2-4 semaines</td>
                                        <td>Neuromusculaires</td>
                                        <td>Coordination, efficacite</td>
                                    </tr>
                                    <tr>
                                        <td>4-8 semaines</td>
                                        <td>Cardiovasculaires</td>
                                        <td>FC repos, debit cardiaque</td>
                                    </tr>
                                    <tr>
                                        <td>8-16 semaines</td>
                                        <td>Metaboliques</td>
                                        <td>Enzymes, mitochondries</td>
                                    </tr>
                                    <tr>
                                        <td>3-6 mois</td>
                                        <td>Structurelles</td>
                                        <td>Densite capillaire, VO2 max</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-success alert-sm">
                            <h6 class="small">Patience Necessaire</h6>
                            <p class="small mb-0">
                                Les adaptations physiologiques demandent du temps. 
                                Forcer la progression peut causer blessures sans benefice supplementaire.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approche equilibree -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-balance-scale me-2"></i>
                    Approche equilibree de la Course A Pied
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-success">Benefices Sante Demontres</h6>
                        <ul class="small">
                            <li><strong>Cardiovasculaire :</strong> Reduction risque maladies cardiaques (-30-40%)</li>
                            <li><strong>Metabolique :</strong> Amelioration sensibilite insuline, contrôle poids</li>
                            <li><strong>Osseux :</strong> Prevention osteoporose, renforcement squelette</li>
                            <li><strong>Mental :</strong> Reduction stress, anxiete, amelioration humeur</li>
                            <li><strong>Cognitif :</strong> Neuroplasticite, memoire, concentration</li>
                            <li><strong>Longevite :</strong> Augmentation esperance de vie en bonne sante</li>
                        </ul>
                        
                        <div class="alert alert-info alert-sm">
                            <h6 class="small">Dose Optimale</h6>
                            <p class="small mb-0">
                                150min/semaine d'activite moderee ou 75min d'activite intense 
                                pour benefices sante maximaux selon l'OMS.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Prevention Comportements Dysfonctionnels</h6>
                        <p class="small">
                            La course peut devenir compulsive ou excessive. Signaux d'alerte : 
                            impossibilite de repos, course malgre blessures, negligence obligations sociales/professionnelles.
                        </p>
                        
                        <h6 class="text-info mt-3">equilibre Sain</h6>
                        <ul class="small">
                            <li>Plaisir de courir preserve</li>
                            <li>Flexibilite dans la planification</li>
                            <li>Repos accepte sans culpabilite</li>
                            <li>Objectifs realistes et adaptables</li>
                            <li>Vie sociale et familiale respectee</li>
                            <li>ecoute des signaux corporels</li>
                        </ul>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-heart me-2"></i>Priorite Bien-être</h6>
                            <p class="mb-0 small">
                                La course doit enrichir votre vie, non la contrôler. 
                                <strong>Si courir devient une obsession ou source d'anxiete, 
                                cherchez l'aide d'un professionnel.</strong> Sante mentale et physique 
                                sont indissociables pour un developpement harmonieux.
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
    color: rgba(0,0,0,0.7);
}

.breadcrumb-item.active {
    color: rgba(0,0,0,0.9);
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.table th {
    border-top: none;
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