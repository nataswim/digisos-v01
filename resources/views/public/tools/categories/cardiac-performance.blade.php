@extends('layouts.public')

@section('title', 'Outils Performance Cardiaque & Zones d\'Entraînement - Physiologie Evidence-Based')
@section('meta_description', 'Outils scientifiques pour optimiser votre entraînement cardiaque : zones d\'entraînement personnalisees et coherence cardiaque. Approche physiologique securisee et evidence-based.')

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
                    Performance Cardiaque & Zones d'Entraînement
                </li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-heart me-3"></i>
                    Performance Cardiaque & Zones d'Entraînement
                </h1>
                <p class="lead mb-4">
                    Optimisez votre entraînement cardiovasculaire avec une approche physiologique scientifique. 
                    Outils bases sur la recherche en cardiologie du sport et la variabilite cardiaque pour un entraînement securise et efficace.
                </p>
                <div class="alert alert-warning border-0 bg-white bg-opacity-25">
                    <small>
                        <i class="fas fa-stethoscope me-2"></i>
                        <strong>2 outils disponibles</strong> - Approche medicale securisee et personnalisee
                    </small>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle p-4 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px;">
                    <i class="fas fa-heart text-white" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avertissement medical important -->
<section class="py-4 bg-warning">
    <div class="container">
        <div class="alert alert-dark border-0 mb-0">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <i class="fas fa-user-md fa-2x text-dark"></i>
                </div>
                <div class="col-md-11">
                    <h6 class="fw-bold mb-2">Avertissement Medical Important</h6>
                    <p class="mb-0 small">
                        <strong>L'entraînement cardiaque necessite une approche prudente et personnalisee.</strong> 
                        Consultez un medecin du sport avant de debuter tout programme d'entraînement intensif, 
                        particulierement si vous avez des antecedents cardiovasculaires, ressentez des douleurs thoraciques, 
                        des palpitations ou tout symptôme inhabituel. Ces outils ne remplacent pas un suivi medical professionnel.
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
            
            <!-- 1. Zones Cardiaques Avancees -->
            <div class="col-lg-6">
                <a href="{{ route('tools.heart-rate-zones') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-bullseye text-danger" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Zones Cardiaques Avancees</h5>
                                        <span class="badge bg-warning ms-2">Pro</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Calcul personnalise des zones d'entraînement avec 6 formules FC max validees scientifiquement. 
                                        Integration FC repos, HRV et adaptation individuelle pour optimisation securisee de l'entraînement.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-primary fw-semibold">Acceder A l'outil →</small>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>8-12 min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 2. Coherence Cardiaque -->
            <div class="col-lg-6">
                <a href="{{ route('tools.coherence-cardiaque') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-info bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-brain text-info" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Coherence Cardiaque</h5>
                                        <span class="badge bg-info ms-2">Bien-être</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Simulateur et guide de pratique coherence cardiaque pour gestion du stress, 
                                        recuperation et optimisation du systeme nerveux autonome. Technique validee scientifiquement.
                                    </p>
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

        <!-- Navigation -->
        <div class="row g-3 mt-5">
            <div class="col-md-6">
                <a href="{{ route('tools.category.nutrition') }}" class="btn btn-outline-danger btn-lg w-100">
                    <i class="fas fa-arrow-left me-2"></i>Nutrition & energie
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('tools.category.swimming') }}" class="btn btn-danger btn-lg w-100">
                    Sports Aquatiques <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        
        <!-- Physiologie cardiaque fondamentale -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-2">
                    <i class="fas fa-heartbeat me-2"></i>
                    Physiologie Cardiaque et Entraînement
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-danger">Fonction Cardiaque de Base</h6>
                        <p class="small">
                            Le cœur est une pompe musculaire qui s'adapte remarquablement A l'entraînement. 
                            La frequence cardiaque (FC) reflete l'intensite de l'effort et permet de quantifier 
                            la charge cardiovasculaire. L'adaptation cardiaque se manifeste par une baisse 
                            de la FC de repos et une amelioration de l'efficacite de pompage.
                        </p>
                        
                        <h6 class="text-primary mt-3">Adaptations A l'Entraînement</h6>
                        <ul class="small">
                            <li><strong>Bradycardie de repos :</strong> FC repos diminuee (athletes 40-60 bpm)</li>
                            <li><strong>Volume d'ejection :</strong> Augmentation du volume sanguin ejecte</li>
                            <li><strong>Debit cardiaque :</strong> Optimisation efficacite energetique</li>
                            <li><strong>Recuperation :</strong> Retour plus rapide A la FC basale</li>
                            <li><strong>Variabilite :</strong> Amelioration HRV (Heart Rate Variability)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Zones d'Entraînement Physiologiques</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Zone</th>
                                        <th>% FC max</th>
                                        <th>Metabolisme</th>
                                        <th>Adaptations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td>Zone 1</td>
                                        <td>50-60%</td>
                                        <td>Aerobie facile</td>
                                        <td>Recuperation active</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>Zone 2</td>
                                        <td>60-70%</td>
                                        <td>Aerobie base</td>
                                        <td>Endurance fondamentale</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td>Zone 3</td>
                                        <td>70-80%</td>
                                        <td>Aerobie intensif</td>
                                        <td>Seuil aerobie</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Zone 4</td>
                                        <td>80-90%</td>
                                        <td>Seuil lactique</td>
                                        <td>Puissance metabolique</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>Zone 5</td>
                                        <td>90-100%</td>
                                        <td>Anaerobie</td>
                                        <td>VO2 max, puissance</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-warning alert-sm mt-3">
                            <h6 class="small">Principe de Specificite</h6>
                            <p class="small mb-0">
                                Chaque zone developpe des adaptations specifiques. Un entraînement 
                                equilibre utilise toutes les zones selon la periodisation et les objectifs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calcul FC max et personnalisation -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-calculator me-2"></i>
                    Calcul FC Max et Personnalisation
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Formules FC Max Validees</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Formule</th>
                                        <th>equation</th>
                                        <th>Population</th>
                                        <th>Precision</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Astrand</td>
                                        <td>220 - âge</td>
                                        <td>Generale</td>
                                        <td>±12 bpm</td>
                                    </tr>
                                    <tr>
                                        <td>Tanaka</td>
                                        <td>208 - (0.7 × âge)</td>
                                        <td>Adultes sains</td>
                                        <td>±10 bpm</td>
                                    </tr>
                                    <tr>
                                        <td>Gulati (F)</td>
                                        <td>206 - (0.88 × âge)</td>
                                        <td>Femmes</td>
                                        <td>±8 bpm</td>
                                    </tr>
                                    <tr>
                                        <td>Nes</td>
                                        <td>211 - (0.64 × âge)</td>
                                        <td>Athletes</td>
                                        <td>±7 bpm</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info alert-sm">
                            <h6 class="small">Test d'Effort - Gold Standard</h6>
                            <p class="small mb-0">
                                Le test d'effort maximal en laboratoire reste la methode de reference 
                                pour determiner la FC max reelle. Les formules donnent des estimations.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Facteurs de Personnalisation</h6>
                        <ul class="small">
                            <li><strong>Genetique :</strong> Variabilite individuelle importante (±15-20 bpm)</li>
                            <li><strong>Condition physique :</strong> Athletes vs sedentaires</li>
                            <li><strong>Discipline sportive :</strong> Specificites metaboliques</li>
                            <li><strong>Environnement :</strong> Altitude, temperature, humidite</li>
                            <li><strong>etat de forme :</strong> Fatigue, stress, maladie</li>
                            <li><strong>Medication :</strong> Bêta-bloquants, stimulants</li>
                        </ul>
                        
                        <h6 class="text-warning mt-3">FC de Reserve (Karvonen)</h6>
                        <p class="small">
                            Methode plus precise utilisant FC repos : 
                            <strong>Zone = [(FC max - FC repos) × %intensite] + FC repos</strong>
                        </p>
                        
                        <div class="alert alert-success alert-sm">
                            <h6 class="small">Monitoring Continue</h6>
                            <p class="small mb-0">
                                La FC de repos matinale est un excellent indicateur de recuperation 
                                et d'adaptation. Une elevation persistante peut signaler fatigue ou maladie.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coherence cardiaque et HRV -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-wave-square me-2"></i>
                    Coherence Cardiaque et Variabilite
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-info">Qu'est-ce que la Coherence Cardiaque ?</h6>
                        <p class="small">
                            La coherence cardiaque est un etat physiologique où le rythme cardiaque, 
                            la respiration et la pression arterielle se synchronisent naturellement. 
                            Cette pratique respiratoire (5 secondes inspiration, 5 secondes expiration) 
                            optimise le systeme nerveux autonome et favorise l'equilibre sympathique-parasympathique.
                        </p>
                        
                        <h6 class="text-success mt-3">Benefices Scientifiquement Demontres</h6>
                        <ul class="small">
                            <li><strong>Gestion du stress :</strong> Reduction cortisol et anxiete</li>
                            <li><strong>Recuperation :</strong> Activation parasympathique acceleree</li>
                            <li><strong>Performance cognitive :</strong> Amelioration focus et concentration</li>
                            <li><strong>Sante cardiovasculaire :</strong> Amelioration HRV et pression arterielle</li>
                            <li><strong>Sommeil :</strong> Qualite et efficacite du sommeil ameliorees</li>
                            <li><strong>Regulation emotionnelle :</strong> Meilleure gestion des emotions</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Protocole de Pratique</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Phase</th>
                                        <th>Duree</th>
                                        <th>Frequence</th>
                                        <th>Timing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Initiation</td>
                                        <td>3-5 min</td>
                                        <td>2-3×/jour</td>
                                        <td>Matin, apres-midi, soir</td>
                                    </tr>
                                    <tr>
                                        <td>Developpement</td>
                                        <td>5-10 min</td>
                                        <td>3×/jour</td>
                                        <td>Routines fixes</td>
                                    </tr>
                                    <tr>
                                        <td>Maintien</td>
                                        <td>5-15 min</td>
                                        <td>2-3×/jour</td>
                                        <td>Selon besoins</td>
                                    </tr>
                                    <tr>
                                        <td>Situations speciales</td>
                                        <td>3-5 min</td>
                                        <td>A la demande</td>
                                        <td>Stress, pre-competition</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-primary alert-sm">
                            <h6 class="small">Technique de Base</h6>
                            <p class="small mb-0">
                                <strong>Respiration 5-5 :</strong> 5 secondes inspiration, 5 secondes expiration, 
                                soit 6 cycles par minute. Position confortable, attention sur le cœur, 
                                respiration abdominale douce et reguliere.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prevention et securite -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-shield-alt me-2"></i>
                    Securite et Prevention en Entraînement Cardiaque
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-danger">Signaux d'Alarme A Surveiller</h6>
                        <ul class="small">
                            <li><strong>Douleur thoracique :</strong> Pendant ou apres l'effort</li>
                            <li><strong>Essoufflement excessif :</strong> Disproportionne A l'effort</li>
                            <li><strong>Palpitations :</strong> Rythme irregulier ou tres rapide</li>
                            <li><strong>Vertiges/malaises :</strong> Pendant ou apres l'exercice</li>
                            <li><strong>Fatigue inhabituelle :</strong> Persistante malgre le repos</li>
                            <li><strong>FC anormale :</strong> Tres elevee au repos ou qui ne descend pas</li>
                        </ul>
                        
                        <div class="alert alert-danger alert-sm">
                            <h6 class="small">Action Immediate Requise</h6>
                            <p class="small mb-0">
                                En cas de douleur thoracique, malaise, ou tout symptôme cardiaque suspect : 
                                <strong>arrêt immediat de l'activite et consultation medicale urgente.</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Bonnes Pratiques de Securite</h6>
                        <ul class="small">
                            <li><strong>echauffement progressif :</strong> 10-15 minutes minimum</li>
                            <li><strong>Progression graduelle :</strong> Augmentation 10% max/semaine</li>
                            <li><strong>Hydratation adequate :</strong> Avant, pendant, apres effort</li>
                            <li><strong>Recuperation surveillee :</strong> FC doit descendre normalement</li>
                            <li><strong>ecoute corporelle :</strong> Respecter fatigue et signaux</li>
                            <li><strong>Suivi medical :</strong> Bilan cardiologique regulier</li>
                        </ul>
                        
                        <h6 class="text-primary mt-3">Populations A Risque</h6>
                        <p class="small">
                            Hommes >45 ans, femmes >55 ans, antecedents familiaux, hypertension, 
                            diabete, obesite, tabagisme necessitent un suivi medical renforce 
                            avant tout programme d'entraînement intensif.
                        </p>
                        
                        <div class="alert alert-info alert-sm">
                            <h6 class="small">Test d'Effort Recommande</h6>
                            <p class="small mb-0">
                                Un test d'effort sous surveillance medicale est recommande pour evaluer 
                                la reponse cardiaque A l'exercice et detecter d'eventuelles anomalies.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-4">
                    <h6><i class="fas fa-stethoscope me-2"></i>Rappel Important</h6>
                    <p class="mb-0 small">
                        L'entraînement cardiaque doit toujours privilegier la securite et la progression graduelle. 
                        <strong>Aucun objectif de performance ne justifie de prendre des risques pour sa sante.</strong> 
                        En cas de doute, consultez toujours un professionnel de sante qualifie. 
                        Ces outils sont des aides A l'entraînement, non des substituts A l'accompagnement medical.
                    </p>
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

.table-success, .table-info, .table-primary, .table-warning, .table-danger {
    --bs-table-accent-bg: var(--bs-table-bg);
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