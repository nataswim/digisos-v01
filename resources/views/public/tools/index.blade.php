@extends('layouts.public')

@section('title', 'Outils & Calculateurs Sportifs - Hassan EL HAOUAT')
@section('meta_description', 'Suite complete d\'outils et calculateurs pour optimiser votre sante, performance et entraînement sportif. Organises par categories specialisees, bases sur les recherches scientifiques 2024.')

@section('content')



<section class="position-relative text-white py-5 nataswim-titre3 overflow-hidden" style="min-height: 600px;">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 1;">
        <source src="{{ asset('assets/images/team/fitness-nataswim-courir.mp4') }}" type="video/mp4">
    </video>

    <!-- Overlay sombre pour meilleure lisibilité -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;"></div>

    <!-- Contenu -->
    <div class="container-lg py-4 position-relative" style="z-index: 3;">
        <div class="row align-items-center">
            <div class="col-lg mb-2 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ route('posts.public.index') }}" style=" color: #fff; text-decoration: none; ">
                    
                    <h1 class="display-4 fw-bold mb-0 shadow-lg border-0" style="text-shadow: 2px 2px 4px rgb(3 64 71);background-color: #63d0c7;padding: 10px;border-radius: 10px;"> <i class="fas fa-calculator me-3"></i>Outils & Calculateurs</h1>
                    </a>
                </div>

                <p class="lead mb-4">
                    <strong>Collection</strong> d'outils pour le sport et la santé basés sur les dernières recherches.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Section Outils Individuels -->
<section class="py-5">
    <div class="container">
        <!-- Santé Hydratation & Nutrition -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-danger">
                <i class="fas fa-water text-danger me-3" style="font-size: 2rem;"></i>
                <h3 class="mb-0 text-danger">Santé Hydratation & Nutrition</h3>
            </div>
            <div class="row g-4">
                <!-- IMC -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.bmi') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-weight text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Mon IMC</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Calculateur IMC complet avec alternatives modernes (BRI, WHtR, RFM)
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Masse grasse -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.masse-grasse') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-percentage text-warning" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Masse grasse</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Multiples méthodes d'estimation de la composition corporelle
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Calories -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.calories') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-fire text-danger" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Conso Calories</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Besoins caloriques sportifs personnalisés selon activité
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- TDEE -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.tdee-calculator') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-chart-line text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Ma TDEE</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Dépense énergétique totale avec facteurs métaboliques
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Kcal/Macros -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.kcal-macros') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-calculator text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Kcal/Macros</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Conversion et répartition macronutriments optimisée
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Hydratation -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.hydratation') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-tint text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Hydratation</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Besoins hydriques personnalisés pour sportifs
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Natation Sportive -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-info">
                <i class="fas fa-swimmer text-info me-3" style="font-size: 2rem;"></i>
                <h3 class="mb-0 text-info">Natation Sportive</h3>
            </div>
            <div class="row g-4">
                <!-- Chrono Sport -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.chronometre') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-stopwatch text-warning" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Chrono Sport</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Chronomètre spécialisé pour natation et sports aquatiques
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- VNC -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.vnc') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-tachometer-alt text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Vitesse Nage</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Vitesse critique de nage et seuils métaboliques
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Prédicteur -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.swimming-predictor') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-swimmer text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Prédicteur performance</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Prédiction scientifique des performances natation
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Planification -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.swimming-planner') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-calendar-alt text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Planification natation</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Séances et périodisation natation personnalisées
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Efficacité -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.swimming-efficiency') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-chart-area text-warning" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Efficacité Technique</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Analyse DPS et SWOLF pour technique optimale
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Performance -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-success">
                <i class="fas fa-heart text-success me-3" style="font-size: 2rem;"></i>
                <h3 class="mb-0 text-success">Performance</h3>
            </div>
            <div class="row g-4">
                <!-- Zones Cardio -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.heart-rate-zones') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-heart text-danger" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Zones Cardio</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Zones d'entraînement cardiaque personnalisées
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Cohérence Cardiaque -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.coherence-cardiaque') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-lungs text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Cohérence Cardiaque</h5>
                                        <span class="badge bg-info small">Bien-être</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Gestion du stress et optimisation récupération
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Course -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.running-planner') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-route text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Planification Course</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Plans d'entraînement course à pied progressifs
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 1RM -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.onermcalculator') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-dumbbell text-warning" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Charge Maximale</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Calcul 1RM et charges d'entraînement sécurisées
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Fitness -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.fitness') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-chart-pie text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Ma Forme</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Évaluation condition physique globale
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Outils Pratiques -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-warning">
                <i class="fas fa-tools text-warning me-3" style="font-size: 2rem;"></i>
                <h3 class="mb-0 text-warning">Outils pratiques</h3>
            </div>
            <div class="row g-4">
                <!-- Chrono Pro -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.chronometre-pro') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-stopwatch-20 text-danger" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Chrono Multi Pro</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Chronométrage multi-athlètes professionnel
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Carte Interactive -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.carte-interactive') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-map-marked-alt text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Où Suis-Je Carte</h5>
                                        <span class="badge bg-secondary small">Pratique</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Géolocalisation et planification parcours sportifs
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Triathlon -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.triathlon-planner') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-2 me-3">
                                        <i class="fas fa-biking text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Planification Triathlon</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Entraînement multidisciplinaire natation-vélo-course
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>







<!-- Navigation par Categories -->
<section class="py-5 bg-light">
    <div class="container">

        <!-- Categories d'outils -->
        <div class="row g-4 mb-5">
            
            <!-- 1. Sante & Composition Corporelle -->
            <div class="col-lg-6">
                <a href="{{ route('tools.category.health') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-heartbeat me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-1">Sante & Composition Corporelle</h4>
                                    <p class="mb-0 opacity-75">4 outils disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Analysez votre sante et composition corporelle avec precision scientifique : 
                                IMC, masse grasse, TDEE, indices de forme physique.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">Decouvrir les outils →</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-success">Essentiel</span>
                                    <span class="badge bg-primary">Avance</span>
                                    <span class="badge bg-warning">Pro</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 2. Nutrition & energie -->
            <div class="col-lg-6">
                <a href="{{ route('tools.category.nutrition') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-apple-alt me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-1">Nutrition & energie</h4>
                                    <p class="mb-0 opacity-75">3 outils disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Optimisez votre nutrition sportive : conversion calories-macros, 
                                besoins caloriques et hydratation personnalisee.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-success fw-bold">Decouvrir les outils →</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-success">Essentiel</span>
                                    <span class="badge bg-primary">Avance</span>
                                    <span class="badge bg-warning">Pro</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 3. Performance Cardiaque -->
            <div class="col-lg-6">
                <a href="{{ route('tools.category.cardiac') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-heart me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-1">Performance Cardiaque</h4>
                                    <p class="mb-0 opacity-75">2 outils disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Optimisez votre entraînement cardio : zones d'entraînement scientifiques 
                                et coherence cardiaque pour la performance.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-danger fw-bold">Decouvrir les outils →</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-info">Bien-être</span>
                                    <span class="badge bg-warning">Pro</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 4. Sports Aquatiques & Natation -->
            <div class="col-lg-6">
                <a href="{{ route('tools.category.swimming') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-swimmer me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-1">Sports Aquatiques & Natation</h4>
                                    <p class="mb-0 opacity-75">6 outils disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Suite complete natation : prediction performance, planification, 
                                VNC, efficacite technique, triathlon et chronometrage.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-info fw-bold">Decouvrir les outils →</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-success">Essentiel</span>
                                    <span class="badge bg-primary">Avance</span>
                                    <span class="badge bg-warning">Pro</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 5. Course A Pied & Endurance -->
            <div class="col-lg-6">
                <a href="{{ route('tools.category.running') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-running me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-1">Course A Pied & Endurance</h4>
                                    <p class="mb-0 opacity-75">1 outil disponible</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Planification intelligente course A pied : plans d'entraînement 
                                personnalises selon votre objectif et niveau.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-warning fw-bold">Decouvrir les outils →</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-primary">Avance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 6. Force & Musculation -->
            <div class="col-lg-6">
                <a href="{{ route('tools.category.strength') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-dumbbell me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-1">Force & Musculation</h4>
                                    <p class="mb-0 opacity-75">1 outil disponible</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Calculs musculation precis : repetition maximale (1RM) 
                                et pourcentages d'entraînement pour optimiser vos charges.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-dark fw-bold">Decouvrir les outils →</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-primary">Avance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 7. Outils Pratiques -->
            <div class="col-lg-6">
                <a href="{{ route('tools.category.practical') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tools me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-1">Outils Pratiques</h4>
                                    <p class="mb-0 opacity-75">2 outils disponibles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Utilitaires d'entraînement : chronometrage professionnel 
                                multi-athletes et carte interactive pour planification parcours.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary fw-bold">Decouvrir les outils →</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-secondary">Pratique</span>
                                    <span class="badge bg-warning">Pro</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- 8. Outils en Developpement -->
            <div class="col-lg-6">
                <a href="{{ route('tools.category.development') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-lg border-0 bg-white hover-lift category-card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-wrench me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-1">Outils en Developpement</h4>
                                    <p class="mb-0 opacity-75">40+ outils prevus</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <p class="card-text text-muted mb-3">
                                Aperçu des innovations A venir : biomecanique, recuperation avancee, 
                                nutrition specialisee, psychologie sportive et plus encore.
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-warning fw-bold">Decouvrir le roadmap →</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-danger">Priorite 1</span>
                                    <span class="badge bg-warning">Priorite 2</span>
                                    <span class="badge bg-secondary">Priorite 3</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>
</section>




<!-- Call to Action -->
<section class="py-5 bg-danger text-white" >
    
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="alert alert-info border-0 bg-white mt-4 d-inline-block">
                    <small>
                        <i class="fas fa-shield-alt me-2"></i>
                        <strong>Avis medical :</strong> Ces outils ne remplacent pas l'avis d'un professionnel de sante. 
                        Consultez un medecin avant tout programme sportif intense.
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Section Credit et Contact -->
<section class="py-5 nataswim-titre1 text-white">

    <div class="container">


        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Sport Net Systèmes</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-info mb-2">Developpement & Expertise</h6>
                        <p class="mb-3">
                            Contenus et outils developpes par 
                            <a href="https://www.facebook.com/Sports.Ressources/" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="text-warning fw-bold text-decoration-none">
                                M H El Haouat
                                <i class="fas fa-external-link-alt ms-1 small"></i>
                            </a>
                        </p>
                        <p class="small text-light opacity-75">
                            Sciences du sport, physiologie de l'exercice et developpement 
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
                            <a href="https://www.facebook.com/Elhaouat.Hassan" 
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
               <a href="https://www.instagram.com/med_hassan_el_haouat/" target="_blank" > <div class="bg-white bg-opacity-10 rounded-circle p-2 d-inline-flex align-items-center justify-content-center" 
                     style="width: 150px; height: 150px; overflow: hidden;">
                    <img src="{{ asset('assets/images/team/med_Hassan_EL_HAOUAT.png') }}" 
                         alt="Med H El Haouat - Expert en sciences du sport" 
                         class="w-100 h-100 rounded-circle"
                         style="object-fit: cover;">
                </div></a>
                <div class="mt-3">
                    <h6 class="text-warning mb-1">Evidence-Based</h6>
                    <small class="text-light opacity-75">Recherches integrees</small>
                </div>
            </div>
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
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.category-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.category-card:hover {
    border-left-color: var(--bs-primary);
}

.card {
    transition: all 0.3s ease;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entree pour les cards
    const cards = document.querySelectorAll('.category-card');
    
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