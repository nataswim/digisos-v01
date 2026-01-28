@extends('layouts.public')

@section('title', 'Outils Sports Aquatiques & Natation - Analyse Technique et Performance Evidence-Based')
@section('meta_description', 'Suite complete d\'outils scientifiques pour natation et sports aquatiques : prediction performance, planification, technique, VNC, triathlon. Approche securisee et biomecanique.')

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
                    Sports Aquatiques & Natation
                </li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-swimmer me-3"></i>
                    Sports Aquatiques & Natation
                </h1>
                <p class="lead mb-4">
                    Suite complete d'outils scientifiques pour optimiser votre pratique aquatique. 
                    Analyse technique, planification, prediction performance basees sur la biomecanique et la physiologie aquatique.
                </p>
                <div class="alert alert-warning border-0 bg-white bg-opacity-25">
                    <small>
                        <i class="fas fa-water me-2"></i>
                        <strong>6 outils disponibles</strong> - De l'initiation A l'expertise technique
                    </small>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle p-4 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px;">
                    <i class="fas fa-swimmer text-white" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avertissement securite aquatique -->
<section class="py-4 bg-warning">
    <div class="container">
        <div class="alert alert-dark border-0 mb-0">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <i class="fas fa-life-ring fa-2x text-dark"></i>
                </div>
                <div class="col-md-11">
                    <h6 class="fw-bold mb-2">Securite Aquatique Prioritaire</h6>
                    <p class="mb-0 small">
                        <strong>La natation necessite des competences aquatiques solides et une surveillance appropriee.</strong> 
                        Ne nagez jamais seul, respectez votre niveau technique, et assurez-vous de la presence de secours qualifies. 
                        Ces outils d'analyse ne remplacent pas l'apprentissage technique avec un maître-nageur qualifie 
                        ni les regles de securite aquatique fondamentales.
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
            
            <!-- 1. Prediction Performance Natation -->
            <div class="col-lg-6">
                <a href="{{ route('tools.swimming-predictor') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-chart-line text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Prediction Performance Natation</h5>
                                        <span class="badge bg-warning ms-2">Pro</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Analyse et prediction scientifique des performances natation sur differentes distances. 
                                        Modeles mathematiques valides pour planification objetifs et evaluation progression.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-primary fw-semibold">Acceder A l'outil →</small>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>5-8 min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 2. Planificateur Natation -->
            <div class="col-lg-6">
                <a href="{{ route('tools.swimming-planner') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-calendar-alt text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Planificateur Natation</h5>
                                        <span class="badge bg-primary ms-2">Avance</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Seances et periodisation d'entraînement natation personnalisees. 
                                        Structure progressive respectant les principes physiologiques et techniques.
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

            <!-- 3. Planificateur Triathlon -->
            <div class="col-lg-6">
                <a href="{{ route('tools.triathlon-planner') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-medal text-warning" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Planificateur Triathlon</h5>
                                        <span class="badge bg-warning ms-2">Pro</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Entraînement multidisciplinaire scientifique integrant natation, cyclisme et course. 
                                        Periodisation equilibree et gestion des transitions specifiques.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-primary fw-semibold">Acceder A l'outil →</small>
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

            <!-- 4. VNC - Vitesse Critique Natation -->
            <div class="col-lg-6">
                <a href="{{ route('tools.vnc') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-water text-warning" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">VNC - Vitesse Critique Natation</h5>
                                        <span class="badge bg-warning ms-2">Pro</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Vitesse de Nage Critique et seuils metaboliques personnalises. 
                                        Determination zones d'entraînement specifiques A la natation.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-primary fw-semibold">Acceder A l'outil →</small>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>6-10 min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 5. Efficacite Technique Natation -->
            <div class="col-lg-6">
                <a href="{{ route('tools.swimming-efficiency') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-ruler text-success" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Efficacite Technique Natation</h5>
                                        <span class="badge bg-success ms-2">Essentiel</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Calculateur DPS (Distance Par Stroke) et SWOLF pour analyse efficacite technique. 
                                        Comparaisons normatives et recommandations d'amelioration.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-primary fw-semibold">Acceder A l'outil →</small>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>3-5 min</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 6. Chronometre Natation -->
            <div class="col-lg-6">
                <a href="{{ route('tools.chronometre') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="fas fa-stopwatch text-warning" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold">Chronometre Natation</h5>
                                        <span class="badge bg-success ms-2">Essentiel</span>
                                    </div>
                                    <p class="card-text text-muted mb-3">
                                        Chronometrage specialise natation et sports aquatiques. 
                                        Interface optimisee pour suivi seances et calculs automatiques.
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-primary fw-semibold">Acceder A l'outil →</small>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <small>En temps reel</small>
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
                <a href="{{ route('tools.category.cardiac') }}" class="btn btn-outline-info btn-lg w-100">
                    <i class="fas fa-arrow-left me-2"></i>Performance Cardiaque
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('tools.category.running') }}" class="btn btn-info btn-lg w-100">
                    Course & Endurance <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        
        <!-- Biomecanique et technique natation -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-cogs me-2"></i>
                    Biomecanique et Technique en Natation
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-info">Principes Biomecaniques Fondamentaux</h6>
                        <p class="small">
                            La natation est unique par sa dimension tridimensionnelle et l'absence d'appui fixe. 
                            L'efficacite resulte de l'equilibre entre forces propulsives et resistances hydrodynamiques. 
                            La technique prime sur la force pure, rendant l'apprentissage progressif essentiel.
                        </p>
                        
                        <h6 class="text-primary mt-3">Facteurs de Performance</h6>
                        <ul class="small">
                            <li><strong>Hydrodynamisme :</strong> Position corps, alignement, reduction traînee</li>
                            <li><strong>Propulsion :</strong> Efficacite prise d'appui, coordination gestuelle</li>
                            <li><strong>Respiration :</strong> Technique adaptee, perturbation minimale</li>
                            <li><strong>Coordination :</strong> Synchronisation bras-jambes-respiration</li>
                            <li><strong>Rythme :</strong> Amplitude vs frequence selon distance</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Progression Technique Securisee</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>etape</th>
                                        <th>Objectif</th>
                                        <th>Duree</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Familiarisation</td>
                                        <td>Aisance aquatique, flottaison</td>
                                        <td>4-8 semaines</td>
                                    </tr>
                                    <tr>
                                        <td>Technique base</td>
                                        <td>Mouvement fondamentaux</td>
                                        <td>8-16 semaines</td>
                                    </tr>
                                    <tr>
                                        <td>Coordination</td>
                                        <td>Synchronisation globale</td>
                                        <td>12-24 semaines</td>
                                    </tr>
                                    <tr>
                                        <td>Perfectionnement</td>
                                        <td>Efficacite, automatisation</td>
                                        <td>Processus continu</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-warning alert-sm mt-3">
                            <h6 class="small">Patience et Progression</h6>
                            <p class="small mb-0">
                                La technique natation demande temps et repetition. Forcer la progression 
                                peut creer des defauts techniques durables. La supervision qualifiee 
                                accelere l'apprentissage correct.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Securite aquatique et prevention -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-2">
                    <i class="fas fa-life-ring me-2"></i>
                    Securite Aquatique et Prevention
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-danger">Regles de Securite Fondamentales</h6>
                        <ul class="small">
                            <li><strong>Jamais seul :</strong> Toujours nager accompagne ou surveille</li>
                            <li><strong>Connaître ses limites :</strong> Respecter niveau technique et condition physique</li>
                            <li><strong>Surveillance qualifiee :</strong> Presence maître-nageur ou sauveteur</li>
                            <li><strong>equipement securite :</strong> Materiel flottaison si necessaire</li>
                            <li><strong>Conditions environnementales :</strong> evaluer securite lieu de nage</li>
                            <li><strong>etat physique :</strong> Ne pas nager malade, fatigue ou sous influence</li>
                        </ul>
                        
                        <div class="alert alert-danger alert-sm">
                            <h6 class="small">Signaux d'Alarme</h6>
                            <p class="small mb-0">
                                Essoufflement excessif, crampes, vertiges, panique : 
                                <strong>sortir immediatement de l'eau et signaler.</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Prevention Noyade et Accidents</h6>
                        <p class="small">
                            La noyade est silencieuse et rapide. Elle peut survenir même chez des nageurs experimentes 
                            en cas de malaise, fatigue excessive ou conditions difficiles.
                        </p>
                        
                        <h6 class="text-success mt-3">Comportements Preventifs</h6>
                        <ul class="small">
                            <li>echauffement progressif, surtout en eau froide</li>
                            <li>Entree graduelle dans l'eau</li>
                            <li>Hydratation avant et apres la nage</li>
                            <li>Protection solaire et thermique adaptee</li>
                            <li>Signalement de sa presence au personnel</li>
                            <li>Connaissance basiques premiers secours</li>
                        </ul>
                        
                        <div class="alert alert-info alert-sm">
                            <h6 class="small">Eau Libre - Precautions Renforcees</h6>
                            <p class="small mb-0">
                                Mer, lac, riviere necessitent experience et precautions supplementaires : 
                                courants, temperature, visibilite, faune aquatique.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Physiologie aquatique -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lungs me-2"></i>
                    Physiologie et Adaptations Aquatiques
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Adaptations Cardiovasculaires</h6>
                        <p class="small">
                            L'immersion modifie la distribution sanguine (effet de compression hydrostatique) 
                            et la thermoregulation. La FC en natation est generalement 10-15 bpm inferieure 
                            A la course A allure equivalente.
                        </p>
                        
                        <h6 class="text-success mt-3">Respiration en Natation</h6>
                        <ul class="small">
                            <li><strong>Contrôle respiratoire :</strong> Expiration complete sous l'eau</li>
                            <li><strong>Timing :</strong> Inspiration rapide, expiration prolongee</li>
                            <li><strong>Bilaterale :</strong> Developpement equilibre musculaire</li>
                            <li><strong>Hypoxie moderee :</strong> Adaptation progressive, securisee</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info">Adaptations Musculaires</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Systeme</th>
                                        <th>Adaptation</th>
                                        <th>Benefice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Cardiovasculaire</td>
                                        <td>Efficacite pompe cardiaque</td>
                                        <td>Endurance generale</td>
                                    </tr>
                                    <tr>
                                        <td>Respiratoire</td>
                                        <td>Capacite et contrôle</td>
                                        <td>Gestion apnee</td>
                                    </tr>
                                    <tr>
                                        <td>Musculaire</td>
                                        <td>Coordination fine</td>
                                        <td>Efficacite gestuelle</td>
                                    </tr>
                                    <tr>
                                        <td>Articulaire</td>
                                        <td>Mobilite, souplesse</td>
                                        <td>Amplitude mouvement</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-success alert-sm">
                            <h6 class="small">Benefices Sante Globale</h6>
                            <p class="small mb-0">
                                Natation : sport complet, faible impact articulaire, 
                                developpement harmonieux, accessible tous âges.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approche equilibree entraînement -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-balance-scale me-2"></i>
                    Approche equilibree de l'Entraînement Aquatique
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-success">Principes d'Entraînement Sain</h6>
                        <ul class="small">
                            <li><strong>Progression graduelle :</strong> Augmentation volume/intensite 10% max/semaine</li>
                            <li><strong>Recuperation integree :</strong> Repos actif et passif necessaires</li>
                            <li><strong>Variete technique :</strong> Travail 4 nages pour developpement complet</li>
                            <li><strong>Plaisir preserve :</strong> Motivation et adhesion long terme</li>
                            <li><strong>ecoute corporelle :</strong> Adaptation selon signaux fatigue</li>
                            <li><strong>Objectifs realistes :</strong> Progression respectant capacites individuelles</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Prevention Surentraînement</h6>
                        <p class="small">
                            Le surentraînement en natation peut se manifester par stagnation performance, 
                            fatigue chronique, infections repetees, perte motivation ou troubles sommeil.
                        </p>
                        
                        <h6 class="text-info mt-3">Signaux d'Alerte</h6>
                        <ul class="small">
                            <li>FC repos elevee de façon persistante</li>
                            <li>Sensation de lourdeur dans l'eau</li>
                            <li>Temps degrades malgre l'effort</li>
                            <li>Irritabilite, troubles de l'humeur</li>
                            <li>Appetit diminue, perte de poids</li>
                            <li>Blessures A repetition</li>
                        </ul>
                        
                        <div class="alert alert-warning mt-3">
                            <h6><i class="fas fa-heart me-2"></i>Bien-être Prioritaire</h6>
                            <p class="mb-0 small">
                                L'entraînement natation doit enrichir votre vie, non la dominer. 
                                <strong>Sante et equilibre personnel priment sur performance.</strong> 
                                Si la natation devient source de stress ou obsession, 
                                consultez un professionnel pour retrouver une approche saine.
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