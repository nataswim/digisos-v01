@extends('layouts.public')

@section('title', 'Chronometre Professionnel Natation & Analyse Performance - Outil Pro')
@section('meta_description', 'Chronometre haute precision pour natation avec analyse en temps reel, metriques avancees et graphiques de performance. Outil professionnel pour entraîneurs et nageurs.')

@section('content')

<!-- Section titre -->
<section class="py-5 text-center nataswim-titre3">

<div class="container-lg">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-2 mb-lg-0" style=" background-color: #f9f5f4; color: #63d0c7; padding: 20px 0px; border-radius: 10px; ">
                <h1 class="display-5 fw-bold mb-3">Chronometre Avec Analyse Performance</h1>
                <p>Chronometre haute precision pour natation avec analyse en temps reel, metriques avancees et graphiques de performance.</p>
            </div>
            <div class="col-lg-5 text-center">
                <a href="{{ route('posts.public.index') }}">
                    <img src="{{ asset('assets/images/team/nataswim-application-banner-5.jpg') }}"
                        alt="Guide Nataswim"
                        class="img-fluid rounded-4"
                        style="max-height: 300px; object-fit: cover;">
                </a>

            </div>
        </div>
    </div>
</section>

<!-- Chronometre Principal -->
<section class="py-5 bg-light">
    <div class="container">
        <!-- Affichage principal du temps -->
        <div class="card shadow-lg border-0 mb-4" style="background: linear-gradient(135deg, #006170 0%, #004d5a 100%);">
            <div class="card-body text-center py-5">
                <div class="text-white mb-4">
                    <div id="mainTimer" class="display-1 fw-bold mb-3" style="font-family: 'Courier New', monospace; font-size: 4rem;">
                        00:00.00
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <button id="startStopBtn" class="btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg">
                            <i class="fas fa-play me-2"></i>Depart
                        </button>
                        <button id="resetBtn" class="btn btn-dark btn-lg px-5 py-3 fw-bold shadow-lg">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contrôles Nageurs -->
        <div class="row g-4 mb-4">
            <div class="col-md-4" id="swimmer1">
                <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #0d6efd;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-swimmer me-2"></i>Athlete 1
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-info btn-lg fw-bold lap-btn" data-swimmer="1">
                                <i class="fas fa-flag me-2"></i>Passage
                            </button>
                            <button class="btn btn-warning btn-lg fw-bold finish-btn" data-swimmer="1">
                                <i class="fas fa-trophy me-2"></i>Arrivee
                            </button>
                        </div>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="d-block"><strong>Passages:</strong> <span class="lap-count">0</span></small>
                            <small class="d-block"><strong>Moyenne:</strong> <span class="avg-time">00:00.00</span></small>
                            <small class="d-block"><strong>Statut:</strong> <span class="status badge bg-secondary">En attente</span></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" id="swimmer2">
                <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #dc3545;">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-swimmer me-2"></i>Athlete 2
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-info btn-lg fw-bold lap-btn" data-swimmer="2">
                                <i class="fas fa-flag me-2"></i>Passage
                            </button>
                            <button class="btn btn-warning btn-lg fw-bold finish-btn" data-swimmer="2">
                                <i class="fas fa-trophy me-2"></i>Arrivee
                            </button>
                        </div>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="d-block"><strong>Passages:</strong> <span class="lap-count">0</span></small>
                            <small class="d-block"><strong>Moyenne:</strong> <span class="avg-time">00:00.00</span></small>
                            <small class="d-block"><strong>Statut:</strong> <span class="status badge bg-secondary">En attente</span></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4" id="swimmer3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #198754;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-swimmer me-2"></i>Athlete 3
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex flex-column gap-2">
                            <button class="btn btn-info btn-lg fw-bold lap-btn" data-swimmer="3">
                                <i class="fas fa-flag me-2"></i>Passage
                            </button>
                            <button class="btn btn-warning btn-lg fw-bold finish-btn" data-swimmer="3">
                                <i class="fas fa-trophy me-2"></i>Arrivee
                            </button>
                        </div>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="d-block"><strong>Passages:</strong> <span class="lap-count">0</span></small>
                            <small class="d-block"><strong>Moyenne:</strong> <span class="avg-time">00:00.00</span></small>
                            <small class="d-block"><strong>Statut:</strong> <span class="status badge bg-secondary">En attente</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultats Finaux -->
        <div id="finalResults" class="card shadow-lg border-0 mb-4 d-none">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Classement Final
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center py-3">Position</th>
                                <th class="text-center py-3">Athlete</th>
                                <th class="text-center py-3">Temps Final</th>
                                <th class="text-center py-3">Difference</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody">
                            <!-- Sera rempli par JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Details des Temps -->
        <div id="lapDetails" class="d-none">
            <!-- Sera rempli par JavaScript -->
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        <!-- Technologies de Chronometrage -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-cogs me-2"></i>
                    Technologies de Chronometrage Moderne - 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Standards Omega Olympic :</strong> Les systemes utilises aux JO mesurent au millieme de seconde 
                    avec des capteurs tactiles dans les plaques de touchee et une precision de ±0.001s.
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-microchip me-2"></i>Systemes Automatiques Professionnels
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Systeme</th>
                                        <th>Precision</th>
                                        <th>Usage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Omega Quantum Timer</strong></td>
                                        <td>±0.001s</td>
                                        <td>JO, Championnats</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Colorado Time Systems</strong></td>
                                        <td>±0.01s</td>
                                        <td>Competitions nationales</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Daktronics AllSport</strong></td>
                                        <td>±0.01s</td>
                                        <td>Clubs, lycees</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Chronometrage manuel</strong></td>
                                        <td>±0.1s</td>
                                        <td>Entraînement</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-video me-2"></i>Innovations Technologiques 2024
                        </h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span><strong>Analyse video HD</strong></span>
                                <span class="badge bg-primary">1000 fps</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Capteurs portables :</strong> Garmin Swim, Tritonwear
                            </li>
                            <li class="list-group-item">
                                <strong>IA temps reel :</strong> Analyse automatique technique
                            </li>
                            <li class="list-group-item">
                                <strong>Capteurs sous-marins :</strong> Tracking 3D immerge
                            </li>
                            <li class="list-group-item">
                                <strong>Realite augmentee :</strong> Metriques dans les lunettes
                            </li>
                        </ul>
                        
                        <div class="alert alert-success mt-3">
                            <small>
                                <strong>Innovation 2024 :</strong> Les systemes IA combinent video + capteurs 
                                pour une analyse technique automatique en temps reel.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metriques Chronometriques -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-chart-bar me-2"></i>
                    Metriques Chronometriques Cles
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white text-center">
                                <h6 class="mb-0">Frequence de Coup de Bras (FCB)</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="display-4 text-primary fw-bold mb-2">45-55</div>
                                <small class="text-muted d-block mb-3">coups/min (50m sprint NL)</small>
                                <hr>
                                <p class="small mb-0">
                                    <strong>Caeleb Dressel</strong><br>
                                    <span class="badge bg-success">Record 50m NL: 20"24</span><br>
                                    FCB: ~52 coups/min
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white text-center">
                                <h6 class="mb-0">Longueur de Coup de Bras (LCB)</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="display-4 text-success fw-bold mb-2">2.2-2.6</div>
                                <small class="text-muted d-block mb-3">metres/coup (elite masculine)</small>
                                <hr>
                                <p class="small mb-0">
                                    <strong>Sun Yang</strong><br>
                                    <span class="badge bg-info">Ex-record 1500m</span><br>
                                    LCB: 2.8m (25m en 9 coups!)
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark text-center">
                                <h6 class="mb-0">Indice de Nage (IN)</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="display-4 text-warning fw-bold mb-2">110-130</div>
                                <small class="text-muted d-block mb-3">IN = FCB × LCB (sprint)</small>
                                <hr>
                                <p class="small mb-0">
                                    <strong>Efficacite globale</strong><br>
                                    <span class="badge bg-warning text-dark">Plus eleve = plus efficace</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparaison Styles -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-swimming-pool me-2"></i>
                    Comparaison entre Styles de Nage
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Style</th>
                                <th class="text-center">FCB moyenne<br><small>(coups/min)</small></th>
                                <th class="text-center">LCB moyenne<br><small>(m/coup)</small></th>
                                <th class="text-center">Vitesse 50m<br><small>(m/s)</small></th>
                                <th class="text-center">Temps Elite<br><small>(50m)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-primary">
                                <td><strong><i class="fas fa-swimmer me-2"></i>Nage libre</strong></td>
                                <td class="text-center">50-55</td>
                                <td class="text-center">2.2-2.6</td>
                                <td class="text-center">2.15-2.35</td>
                                <td class="text-center"><span class="badge bg-primary">20"24 - 21"30</span></td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-swimmer me-2"></i>Dos crawle</strong></td>
                                <td class="text-center">45-50</td>
                                <td class="text-center">2.0-2.4</td>
                                <td class="text-center">1.85-2.05</td>
                                <td class="text-center"><span class="badge bg-secondary">23"71 - 24"50</span></td>
                            </tr>
                            <tr class="table-light">
                                <td><strong><i class="fas fa-swimmer me-2"></i>Brasse</strong></td>
                                <td class="text-center">35-40</td>
                                <td class="text-center">1.8-2.2</td>
                                <td class="text-center">1.65-1.85</td>
                                <td class="text-center"><span class="badge bg-success">25"95 - 26"80</span></td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-swimmer me-2"></i>Papillon</strong></td>
                                <td class="text-center">40-45</td>
                                <td class="text-center">2.0-2.4</td>
                                <td class="text-center">1.95-2.15</td>
                                <td class="text-center"><span class="badge bg-warning text-dark">22"27 - 23"20</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Strategies de Course -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-bullseye me-2"></i>
                    Strategies de Course et Allures
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="fas fa-bolt me-2"></i>50m Sprint
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Depart :</strong> 95-100% effort maximal</li>
                                    <li class="mb-2"><strong>Maintien :</strong> 98-102%</li>
                                    <li class="mb-2"><strong>Strategie :</strong> All-out des le depart</li>
                                </ul>
                                <div class="alert alert-info alert-sm">
                                    <small>
                                        <strong>Exemple Dressel 20"24 :</strong><br>
                                        <span class="badge bg-primary me-1">9"45</span>
                                        <span class="badge bg-secondary">10"79</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-line me-2"></i>200m Contrôle
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Strategie :</strong> "Negative split"</li>
                                    <li class="mb-2"><strong>2e moitie :</strong> 2-4% plus rapide</li>
                                    <li class="mb-2"><strong>Gestion :</strong> Repartition energetique</li>
                                </ul>
                                <div class="alert alert-success alert-sm">
                                    <small>
                                        <strong>Exemple Ledecky 1'53"73 :</strong><br>
                                        <span class="badge bg-info me-1">28"89</span>
                                        <span class="badge bg-primary me-1">29"66</span>
                                        <span class="badge bg-secondary me-1">29"63</span>
                                        <span class="badge bg-success">25"55</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cas d'etude -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-microscope me-2"></i>
                    Cas d'etude : Optimisation Technique - Adam Peaty
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Probleme Identifie</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>• FCB initiale : <span class="badge bg-danger">42 coups/min</span></li>
                                    <li>• LCB : <span class="badge bg-danger">1,95 m/coup</span></li>
                                    <li>• IN : <span class="badge bg-danger">82</span></li>
                                    <li>• Temps : <span class="badge bg-danger">57"92</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Apres Optimisation</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>• FCB optimisee : <span class="badge bg-success">38 coups/min</span></li>
                                    <li>• LCB amelioree : <span class="badge bg-success">2,15 m/coup</span></li>
                                    <li>• Nouvel IN : <span class="badge bg-success">95</span></li>
                                    <li>• Temps final : <span class="badge bg-success">57"37</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-success mt-4 text-center">
                    <h5 class="mb-2">Resultat</h5>
                    <p class="mb-0">
                        Amelioration : <span class="badge bg-danger fs-6">57"92</span> → 
                        <span class="badge bg-success fs-6">57"37</span> = 
                        <span class="badge bg-warning fs-6 text-dark">-0"55</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Perspectives Futures -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-rocket me-2"></i>
                    Perspectives d'Avenir - Technologies 2025-2030
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-primary h-100 text-center">
                            <div class="card-body">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h6 class="fw-bold mb-3">Capteurs Sous-marins</h6>
                                <p class="small text-muted">Tracking 3D immerge pour analyse complete du mouvement sous l'eau</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100 text-center">
                            <div class="card-body">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <h6 class="fw-bold mb-3">Realite Augmentee</h6>
                                <p class="small text-muted">Metriques en temps reel affichees dans les lunettes de natation</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100 text-center">
                            <div class="card-body">
                                <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <h6 class="fw-bold mb-3">IA Avancee</h6>
                                <p class="small text-muted">Prediction et optimisation automatique des strategies de course</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-primary mt-4">
                    <h5 class="mb-2">Impact Prevu</h5>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <div class="h4 fw-bold text-primary mb-1">2.3%</div>
                                <small>Amelioration moyenne performances</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <div class="h4 fw-bold text-success mb-1">1.1s</div>
                                <small>Gain moyen 100m elite</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <div class="h4 fw-bold text-warning mb-1">340%</div>
                                <small>ROI investissements tech</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-danger bg-opacity-10 p-3 rounded">
                                <div class="h4 fw-bold text-danger mb-1">0.01s</div>
                                <small>Precision predictive IA</small>
                            </div>
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
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

#mainTimer {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.lap-btn, .finish-btn {
    transition: all 0.3s ease;
}

.lap-btn:hover, .finish-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.lap-btn:disabled, .finish-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.status.bg-success {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}
</style>
@endpush

@push('scripts')
<script>
class SwimmingChronometer {
    constructor() {
        this.time = 0;
        this.isRunning = false;
        this.interval = null;
        this.swimmers = {
            1: { laps: [], finishTime: null, name: 'Athlete 1' },
            2: { laps: [], finishTime: null, name: 'Athlete 2' },
            3: { laps: [], finishTime: null, name: 'Athlete 3' }
        };
        this.raceFinished = false;
        
        this.initEventListeners();
        this.updateDisplay();
    }
    
    initEventListeners() {
        // Boutons principaux
        document.getElementById('startStopBtn').addEventListener('click', () => this.toggleTimer());
        document.getElementById('resetBtn').addEventListener('click', () => this.reset());
        
        // Boutons nageurs
        document.querySelectorAll('.lap-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const swimmerId = parseInt(e.target.dataset.swimmer);
                this.recordLap(swimmerId);
            });
        });
        
        document.querySelectorAll('.finish-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const swimmerId = parseInt(e.target.dataset.swimmer);
                this.finishSwimmer(swimmerId);
            });
        });
    }
    
    toggleTimer() {
        if (this.raceFinished) return;
        
        if (!this.isRunning) {
            this.interval = setInterval(() => {
                this.time += 10;
                this.updateDisplay();
            }, 10);
            
            document.getElementById('startStopBtn').innerHTML = '<i class="fas fa-pause me-2"></i>Pause';
            document.getElementById('startStopBtn').className = 'btn btn-danger btn-lg px-5 py-3 fw-bold shadow-lg';
            
            // Activer les boutons nageurs
            this.enableSwimmerButtons();
        } else {
            clearInterval(this.interval);
            document.getElementById('startStopBtn').innerHTML = '<i class="fas fa-play me-2"></i>Reprendre';
            document.getElementById('startStopBtn').className = 'btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg';
            
            // Desactiver les boutons nageurs
            this.disableSwimmerButtons();
        }
        
        this.isRunning = !this.isRunning;
    }
    
    recordLap(swimmerId) {
        if (!this.isRunning || this.swimmers[swimmerId].finishTime) return;
        
        const lastLapTime = this.swimmers[swimmerId].laps.length > 0 ? 
            this.swimmers[swimmerId].laps[this.swimmers[swimmerId].laps.length - 1].time : 0;
        
        const lap = {
            number: this.swimmers[swimmerId].laps.length + 1,
            time: this.time,
            splitTime: this.time - lastLapTime
        };
        
        this.swimmers[swimmerId].laps.push(lap);
        this.updateSwimmerDisplay(swimmerId);
        this.updateLapDetails();
    }
    
    finishSwimmer(swimmerId) {
        if (!this.isRunning || this.swimmers[swimmerId].finishTime) return;
        
        this.swimmers[swimmerId].finishTime = this.time;
        this.updateSwimmerDisplay(swimmerId);
        
        // Verifier si tous les nageurs ont fini
        const finishedSwimmers = Object.values(this.swimmers).filter(s => s.finishTime !== null);
        if (finishedSwimmers.length === 3) {
            this.endRace();
        }
        
        this.showFinalResults();
    }
    
    endRace() {
        clearInterval(this.interval);
        this.isRunning = false;
        this.raceFinished = true;
        
        document.getElementById('startStopBtn').innerHTML = '<i class="fas fa-check me-2"></i>Course Terminee';
        document.getElementById('startStopBtn').className = 'btn btn-success btn-lg px-5 py-3 fw-bold shadow-lg';
        document.getElementById('startStopBtn').disabled = true;
        
        this.disableSwimmerButtons();
    }
    
    reset() {
        clearInterval(this.interval);
        this.time = 0;
        this.isRunning = false;
        this.raceFinished = false;
        
        // Reinitialiser les nageurs
        Object.keys(this.swimmers).forEach(id => {
            this.swimmers[id] = { laps: [], finishTime: null, name: this.swimmers[id].name };
            this.updateSwimmerDisplay(parseInt(id));
        });
        
        // Reinitialiser l'interface
        document.getElementById('startStopBtn').innerHTML = '<i class="fas fa-play me-2"></i>Depart';
        document.getElementById('startStopBtn').className = 'btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg';
        document.getElementById('startStopBtn').disabled = false;
        
        document.getElementById('finalResults').classList.add('d-none');
        document.getElementById('lapDetails').classList.add('d-none');
        
        this.updateDisplay();
        this.disableSwimmerButtons();
    }
    
    updateDisplay() {
        document.getElementById('mainTimer').textContent = this.formatTime(this.time);
    }
    
    updateSwimmerDisplay(swimmerId) {
        const swimmer = this.swimmers[swimmerId];
        const swimmerElement = document.getElementById(`swimmer${swimmerId}`);
        
        // Mettre A jour le nombre de passages
        swimmerElement.querySelector('.lap-count').textContent = swimmer.laps.length;
        
        // Calculer et afficher la moyenne
        let avgTime = '00:00.00';
        if (swimmer.laps.length > 0) {
            const totalSplitTime = swimmer.laps.reduce((sum, lap) => sum + lap.splitTime, 0);
            avgTime = this.formatTime(totalSplitTime / swimmer.laps.length);
        }
        swimmerElement.querySelector('.avg-time').textContent = avgTime;
        
        // Mettre A jour le statut
        const statusElement = swimmerElement.querySelector('.status');
        if (swimmer.finishTime) {
            statusElement.textContent = 'Arrive';
            statusElement.className = 'status badge bg-success';
            
            // Desactiver les boutons
            swimmerElement.querySelector('.lap-btn').disabled = true;
            swimmerElement.querySelector('.finish-btn').disabled = true;
        } else if (this.isRunning) {
            statusElement.textContent = 'En course';
            statusElement.className = 'status badge bg-primary';
        }
    }
    
    showFinalResults() {
        const finishedSwimmers = Object.entries(this.swimmers)
            .filter(([id, swimmer]) => swimmer.finishTime !== null)
            .map(([id, swimmer]) => ({ id: parseInt(id), ...swimmer }))
            .sort((a, b) => a.finishTime - b.finishTime);
        
        if (finishedSwimmers.length === 0) return;
        
        const tbody = document.getElementById('resultsTableBody');
        tbody.innerHTML = '';
        
        const bestTime = finishedSwimmers[0].finishTime;
        
        finishedSwimmers.forEach((swimmer, index) => {
            const row = document.createElement('tr');
            const diff = swimmer.finishTime - bestTime;
            const diffText = diff === 0 ? '-' : '+' + this.formatTime(diff);
            
            let badgeClass = 'bg-primary';
            if (index === 0) badgeClass = 'bg-warning';
            else if (index === 1) badgeClass = 'bg-secondary';
            else if (index === 2) badgeClass = 'bg-dark';
            
            row.innerHTML = `
                <td class="text-center py-3">
                    <span class="badge ${badgeClass} fs-6">${index + 1}</span>
                </td>
                <td class="text-center fw-semibold py-3">${swimmer.name}</td>
                <td class="text-center py-3">
                    <span class="badge bg-success fs-6 px-3 py-2">${this.formatTime(swimmer.finishTime)}</span>
                </td>
                <td class="text-center text-muted py-3">${diffText}</td>
            `;
            
            tbody.appendChild(row);
        });
        
        document.getElementById('finalResults').classList.remove('d-none');
    }
    
    updateLapDetails() {
        const details = document.getElementById('lapDetails');
        details.innerHTML = '';
        
        Object.entries(this.swimmers).forEach(([id, swimmer]) => {
            if (swimmer.laps.length === 0) return;
            
            const colors = ['#0d6efd', '#dc3545', '#198754'];
            const color = colors[parseInt(id) - 1];
            
            const card = document.createElement('div');
            card.className = 'card border-0 shadow-sm mb-4';
            card.innerHTML = `
                <div class="card-header py-3" style="background: ${color}; color: white;">
                    <h5 class="mb-0 fw-bold">${swimmer.name} - Details des Temps de Passage</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center fw-bold py-3">Fraction</th>
                                    <th class="text-center fw-bold py-3">Temps Intermediaire</th>
                                    <th class="text-center fw-bold py-3">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${swimmer.laps.map(lap => `
                                    <tr>
                                        <td class="text-center fw-semibold py-3">${lap.number}</td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-primary fs-6 px-3 py-2">${this.formatTime(lap.splitTime)}</span>
                                        </td>
                                        <td class="text-center text-muted py-3">${this.formatTime(lap.time)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            details.appendChild(card);
        });
        
        details.classList.remove('d-none');
    }
    
    enableSwimmerButtons() {
        document.querySelectorAll('.lap-btn, .finish-btn').forEach(btn => {
            btn.disabled = false;
        });
    }
    
    disableSwimmerButtons() {
        document.querySelectorAll('.lap-btn, .finish-btn').forEach(btn => {
            btn.disabled = true;
        });
    }
    
    formatTime(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = Math.floor((ms % 60000) / 1000);
        const centiseconds = Math.floor((ms % 1000) / 10);
        
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}.${centiseconds.toString().padStart(2, '0')}`;
    }
}

// Initialiser le chronometre au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    new SwimmingChronometer();
});
</script>
@endpush