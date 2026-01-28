@extends('layouts.public')

@section('title', 'Outils en Developpement - Roadmap et Innovations Futures Evidence-Based')
@section('meta_description', 'Aperçu des futurs outils sportifs en developpement : biomecanique, recuperation, nutrition specialisee, psychologie sportive. Roadmap basee sur les besoins utilisateurs et recherches scientifiques.')

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
                    Outils en Developpement
                </li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="fas fa-wrench me-3"></i>
                    Outils en Developpement
                </h1>
                <p class="lead mb-4">
                    Aperçu des innovations futures de notre ecosysteme d'outils sportifs. 
                    Developpement base sur vos retours, les dernieres recherches scientifiques et l'evolution des besoins en sport-sante.
                </p>
                <div class="alert alert-info border-0 bg-white bg-opacity-75">
                    <small>
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>40+ outils prevus</strong> - Developpement selon priorites et retours utilisateurs
                    </small>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-25 rounded-circle p-4 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px;">
                    <i class="fas fa-wrench text-dark" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Avertissement projets en cours -->
<section class="py-4 bg-info text-white">
    <div class="container">
        <div class="alert alert-light border-0 mb-0">
            <div class="row align-items-center">
                <div class="col-md-1 text-center">
                    <i class="fas fa-clock fa-2x text-info"></i>
                </div>
                <div class="col-md-11">
                    <h6 class="fw-bold mb-2 text-info">Projets en Cours de Developpement</h6>
                    <p class="mb-0 small text-dark">
                        <strong>Ces outils sont actuellement en phase de conception et developpement.</strong> 
                        Les fonctionnalites, priorites et delais peuvent evoluer selon les retours utilisateurs, 
                        l'avancement des recherches scientifiques et les ressources de developpement disponibles. 
                        Aucune date de disponibilite ferme n'est garantie.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Outils par priorite de developpement -->
<section class="py-5 bg-light">
    <div class="container">
        
        <!-- Priorite 1 - Impact eleve, demande forte -->
        <div class="mb-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-danger me-3" style="font-size: 1.2rem;">Priorite 1</span>
                        <div>
                            <h3 class="mb-1">Impact eleve - Demande Forte</h3>
                            <p class="mb-0 opacity-75">Outils prioritaires selon analyse des besoins utilisateurs</p>
                        </div>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="row g-4">
                        
                        <!-- Calculateur Puissance Cyclisme -->
                        <div class="col-lg-6">
                            <div class="card border-danger bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-danger">
                                        <i class="fas fa-biking me-2"></i>Calculateur Puissance Cyclisme
                                    </h6>
                                    <p class="card-text small">
                                        FTP, zones de puissance, analyse performance cyclisme. 
                                        Integration donnees capteurs et planification entraînement personnalisee.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-danger">Priorite 1</span>
                                        <small class="text-muted">Performance specialisee</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Generateur HIIT Personnalise -->
                        <div class="col-lg-6">
                            <div class="card border-primary bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-bolt me-2"></i>Generateur HIIT Personnalise
                                    </h6>
                                    <p class="card-text small">
                                        Protocoles HIIT scientifiques adaptes selon objectifs, niveau et discipline. 
                                        Timing precis et progression automatique.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">Priorite 1</span>
                                        <small class="text-muted">Planification avancee</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- evaluateur Risque Blessure -->
                        <div class="col-lg-6">
                            <div class="card border-success bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-success">
                                        <i class="fas fa-first-aid me-2"></i>evaluateur Risque Blessure
                                    </h6>
                                    <p class="card-text small">
                                        Screening preventif multi-factoriel pour identifier les risques de blessure. 
                                        Recommandations personnalisees de prevention.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success">Priorite 1</span>
                                        <small class="text-muted">Prevention & Sante</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Priorite 2 - Complementarite forte -->
        <div class="mb-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-dark text-warning me-3" style="font-size: 1.2rem;">Priorite 2</span>
                        <div>
                            <h3 class="mb-1">Complementarite Forte</h3>
                            <p class="mb-0 opacity-75">Outils enrichissant l'ecosysteme existant</p>
                        </div>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="row g-4">
                        
                        <!-- Tracker HRV -->
                        <div class="col-lg-4">
                            <div class="card border-danger bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-danger">
                                        <i class="fas fa-heartbeat me-2"></i>Tracker Variabilite Cardiaque (HRV)
                                    </h6>
                                    <p class="card-text small">
                                        evaluation quotidienne etat de forme via analyse HRV. 
                                        Recommandations d'intensite d'entraînement.
                                    </p>
                                    <span class="badge bg-danger">Priorite 2</span>
                                </div>
                            </div>
                        </div>

                        <!-- Calculateur Charge d'Entraînement -->
                        <div class="col-lg-4">
                            <div class="card border-danger bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-danger">
                                        <i class="fas fa-weight me-2"></i>Calculateur Charge d'Entraînement
                                    </h6>
                                    <p class="card-text small">
                                        TSS, TRIMP, gestion fatigue et planification charge optimale. 
                                        Prevention surentraînement.
                                    </p>
                                    <span class="badge bg-danger">Priorite 2</span>
                                </div>
                            </div>
                        </div>

                        <!-- Strategie Nutritionnelle Course -->
                        <div class="col-lg-4">
                            <div class="card border-success bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-success">
                                        <i class="fas fa-route me-2"></i>Strategie Nutritionnelle Course
                                    </h6>
                                    <p class="card-text small">
                                        Plan detaille nutrition/hydratation selon distance course. 
                                        Timing optimal et quantites personnalisees.
                                    </p>
                                    <span class="badge bg-success">Priorite 2</span>
                                </div>
                            </div>
                        </div>

                        <!-- Calculateur Qualite Sommeil -->
                        <div class="col-lg-4">
                            <div class="card border-success bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-success">
                                        <i class="fas fa-moon me-2"></i>Calculateur Qualite Sommeil Athlete
                                    </h6>
                                    <p class="card-text small">
                                        Analyse phases sommeil et recommandations recuperation. 
                                        Optimisation performance via sommeil.
                                    </p>
                                    <span class="badge bg-success">Priorite 2</span>
                                </div>
                            </div>
                        </div>

                        <!-- Calculateur Timing Nutritionnel -->
                        <div class="col-lg-4">
                            <div class="card border-warning bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">
                                        <i class="fas fa-clock me-2"></i>Calculateur Timing Nutritionnel
                                    </h6>
                                    <p class="card-text small">
                                        Fenêtres metaboliques pre/pendant/post effort. 
                                        Optimisation absorption nutriments.
                                    </p>
                                    <span class="badge bg-warning">Priorite 2</span>
                                </div>
                            </div>
                        </div>

                        <!-- Calculateur Vitesse Critique Course -->
                        <div class="col-lg-4">
                            <div class="card border-warning bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">
                                        <i class="fas fa-running me-2"></i>Calculateur Vitesse Critique Course
                                    </h6>
                                    <p class="card-text small">
                                        Seuils physiologiques course A pied. 
                                        Zones d'entraînement specifiques endurance.
                                    </p>
                                    <span class="badge bg-warning">Priorite 2</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Priorite 3 - Specialisation avancee -->
        <div class="mb-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-secondary text-white">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-secondary me-3" style="font-size: 1.2rem;">Priorite 3</span>
                        <div>
                            <h3 class="mb-1">Specialisation Avancee</h3>
                            <p class="mb-0 opacity-75">Outils pour utilisateurs experts et cas specifiques</p>
                        </div>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="row g-4">
                        
                        <!-- Biomecanique -->
                        <div class="col-md-6">
                            <h6 class="text-secondary border-bottom pb-2">
                                <i class="fas fa-running me-2"></i>Analyse Biomecanique
                            </h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card border-secondary bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title small mb-1">Analyseur de Foulee Course</h6>
                                            <p class="card-text small mb-1">Cadence, longueur foulee, temps contact sol</p>
                                            <span class="badge bg-secondary small">Priorite 3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card border-secondary bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title small mb-1">Analyseur Position Velo</h6>
                                            <p class="card-text small mb-1">Optimisation aerodynamique et confort</p>
                                            <span class="badge bg-secondary small">Priorite 3</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Psychologie Sportive -->
                        <div class="col-md-6">
                            <h6 class="text-info border-bottom pb-2">
                                <i class="fas fa-brain me-2"></i>Psychologie Sportive
                            </h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card border-info bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title small mb-1">evaluateur Motivation Sportive</h6>
                                            <p class="card-text small mb-1">Profils motivationnels, strategies</p>
                                            <span class="badge bg-info small">Priorite 3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card border-info bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title small mb-1">Planificateur Objectifs SMART</h6>
                                            <p class="card-text small mb-1">Methodologie structuree progression</p>
                                            <span class="badge bg-info small">Priorite 3</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Facteurs Environnementaux -->
                        <div class="col-md-6">
                            <h6 class="text-warning border-bottom pb-2">
                                <i class="fas fa-cloud me-2"></i>Facteurs Environnementaux
                            </h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card border-warning bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title small mb-1">Calculateur Impact Altitude</h6>
                                            <p class="card-text small mb-1">Ajustements performance, acclimatation</p>
                                            <span class="badge bg-warning small">Priorite 3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card border-warning bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title small mb-1">Correcteur Performance Climatique</h6>
                                            <p class="card-text small mb-1">Temperature, humidite, vent</p>
                                            <span class="badge bg-warning small">Priorite 3</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Benchmarking -->
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="fas fa-chart-bar me-2"></i>Comparaison & Benchmarking
                            </h6>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card border-primary bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title small mb-1">Comparateur Performances Standardise</h6>
                                            <p class="card-text small mb-1">Normes par âge/sexe/niveau</p>
                                            <span class="badge bg-primary small">Priorite 3</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card border-primary bg-light">
                                        <div class="card-body py-2">
                                            <h6 class="card-title small mb-1">Calculateur Âge Sportif</h6>
                                            <p class="card-text small mb-1">Performance vs âge biologique</p>
                                            <span class="badge bg-primary small">Priorite 3</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Contribution utilisateurs -->
        <div class="card mb-5">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-users me-2"></i>
                    Contribuez au Developpement
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-success">Vos Retours Orientent nos Priorites</h6>
                        <p class="small">
                            Le developpement de nouveaux outils se base sur l'analyse des besoins reels des utilisateurs. 
                            Vos suggestions, retours d'usage et demandes specifiques influencent directement 
                            nos priorites de developpement et l'evolution de la roadmap.
                        </p>
                        
                        <h6 class="text-primary mt-3">Comment Contribuer</h6>
                        <ul class="small">
                            <li><strong>Suggestions d'outils :</strong> Proposez de nouveaux calculateurs</li>
                            <li><strong>Ameliorations :</strong> Signalez bugs ou ameliorations possibles</li>
                            <li><strong>Cas d'usage :</strong> Partagez comment vous utilisez les outils</li>
                            <li><strong>Expertise :</strong> Apportez votre connaissance scientifique/terrain</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Criteres de Priorisation</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Critere</th>
                                        <th>Poids</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Impact utilisateur</td>
                                        <td>eleve</td>
                                        <td>Nombre personnes beneficiant</td>
                                    </tr>
                                    <tr>
                                        <td>Base scientifique</td>
                                        <td>eleve</td>
                                        <td>Evidence disponible</td>
                                    </tr>
                                    <tr>
                                        <td>Faisabilite technique</td>
                                        <td>Moyen</td>
                                        <td>Complexite developpement</td>
                                    </tr>
                                    <tr>
                                        <td>Complementarite</td>
                                        <td>Moyen</td>
                                        <td>Synergie avec existant</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('contact') }}" class="btn btn-success">
                                <i class="fas fa-envelope me-2"></i>Nous Contacter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="row g-3 mt-5">
            <div class="col-md-6">
                <a href="{{ route('tools.category.practical') }}" class="btn btn-outline-warning btn-lg w-100">
                    <i class="fas fa-arrow-left me-2"></i>Outils Pratiques
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('tools.index') }}" class="btn btn-warning btn-lg w-100">
                    Retour Index General <i class="fas fa-home ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Methodologie developpement -->
<section class="py-5">
    <div class="container">
        
        <!-- Approche scientifique -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-flask me-2"></i>
                    Methodologie de Developpement Evidence-Based
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Processus de Validation Scientifique</h6>
                        <p class="small">
                            Chaque nouvel outil suit un processus rigoureux de validation scientifique 
                            avant developpement. Nous nous assurons que les algorithmes, formules et 
                            recommandations s'appuient sur des recherches peer-reviewed recentes.
                        </p>
                        
                        <h6 class="text-success mt-3">etapes de Developpement</h6>
                        <ol class="small">
                            <li><strong>Revue litterature :</strong> Analyse recherches recentes</li>
                            <li><strong>Validation expert :</strong> Consultation professionnels</li>
                            <li><strong>Prototype :</strong> Developpement version test</li>
                            <li><strong>Tests utilisateurs :</strong> Feedback et ajustements</li>
                            <li><strong>Deploiement :</strong> Version finale avec documentation</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Criteres de Qualite</h6>
                        <ul class="small">
                            <li><strong>Precision scientifique :</strong> Algorithmes valides</li>
                            <li><strong>Securite utilisateur :</strong> Avertissements appropries</li>
                            <li><strong>Facilite d'usage :</strong> Interface intuitive</li>
                            <li><strong>Approche equilibree :</strong> Sante avant performance</li>
                            <li><strong>education :</strong> Contenu informatif accompagnant</li>
                        </ul>
                        
                        <div class="alert alert-info alert-sm">
                            <h6 class="small">evolution Continue</h6>
                            <p class="small mb-0">
                                Les outils existants sont regulierement mis A jour selon 
                                l'evolution des connaissances scientifiques et retours utilisateurs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Limitations et transparence -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-balance-scale me-2"></i>
                    Transparence et Limitations
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-secondary">Contraintes de Developpement</h6>
                        <p class="small">
                            Le developpement d'outils de qualite necessite du temps, des ressources 
                            et une expertise multidisciplinaire. Nous privilegions la qualite sur la quantite, 
                            ce qui peut retarder certains developpements mais garantit la fiabilite.
                        </p>
                        
                        <h6 class="text-warning mt-3">Facteurs Influençant les Delais</h6>
                        <ul class="small">
                            <li>Complexite technique de l'outil</li>
                            <li>Disponibilite de recherches validees</li>
                            <li>Ressources de developpement</li>
                            <li>Retours et tests utilisateurs</li>
                            <li>evolution priorites selon besoins</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-info">Engagement Qualite</h6>
                        <p class="small">
                            Nous nous engageons A maintenir les standards eleves de qualite scientifique 
                            et de securite utilisateur qui caracterisent nos outils actuels. 
                            Aucun outil ne sera publie sans validation rigoureuse.
                        </p>
                        
                        <div class="alert alert-warning">
                            <h6 class="small">Aucune Garantie de Delai</h6>
                            <p class="mb-0 small">
                                Les projets presentes sont des intentions de developpement, 
                                <strong>non des engagements fermes avec dates garanties.</strong> 
                                Les priorites peuvent evoluer selon les besoins utilisateurs 
                                et l'avancement des recherches scientifiques dans chaque domaine.
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
.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(0,0,0,0.7);
}

.breadcrumb-item.active {
    color: rgba(0,0,0,0.9);
}

.border-bottom {
    border-bottom: 2px solid #dee2e6 !important;
}

.badge.small {
    font-size: 0.7rem;
}

.card-title.small {
    font-size: 0.9rem;
    font-weight: 600;
}

.table th {
    border-top: none;
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'apparition progressive pour les cards de priorite
    const priorityCards = document.querySelectorAll('.card.border-danger, .card.border-primary, .card.border-success, .card.border-warning, .card.border-info, .card.border-secondary');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -30px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);
    
    priorityCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        observer.observe(card);
    });
});
</script>
@endpush