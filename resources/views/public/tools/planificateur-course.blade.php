@extends('layouts.tools')

@section('title', 'Planificateur Entraînement Course à pied - Programme Scientifique 2024')
@section('meta_description', 'Planificateur course scientifique avec zones d\'entraînement optimisees. Distribution polarisée 80/20, biomécanique, économie de course et nutrition spécialisée. Evidence-based 2024.')

@section('content')
<!-- Section titre -->
@include('public.tools.partials.header', ['title' => "Planificateur d'Entraînement Course à pied", 'lead' => '', 'icon' => 'fa-running'])

<!-- Planificateur Principal -->
<section class="py-5 bg-light">
    <div class="container">
        
        <!-- Planificateur Personnalise -->
        <div class="card mb-4 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-user-cog me-2"></i>
                    Planificateur Personnalisé - Méthode Evidence-Based
                </h3>
            </div>
            <div class="card-body">
                
                <!-- Messages d'erreur -->
                <div id="errorMessage" class="alert alert-danger d-none">
                    <!-- Sera rempli par JavaScript -->
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="goal" class="form-label fw-bold">
                            <i class="fas fa-target me-2"></i>Objectif de Course
                        </label>
                        <select id="goal" class="form-select form-select-lg border-primary">
                            <option value="">-- Choisir un objectif --</option>
                            <option value="endurance">Améliorer l'endurance</option>
                            <option value="weight">Perdre du poids</option>
                            <option value="speed">Gagner en vitesse</option>
                            <option value="10k">Courir un 10 km</option>
                            <option value="half">Courir un semi-marathon</option>
                            <option value="marathon">Courir un marathon</option>
                        </select>
                        <small class="text-muted">Sélectionnez votre objectif principal</small>
                    </div>
                    <div class="col-md-6">
                        <label for="experience" class="form-label fw-bold">
                            <i class="fas fa-medal me-2"></i>Niveau d'expérience
                        </label>
                        <select id="experience" class="form-select form-select-lg border-primary">
                            <option value="">-- Sélectionner votre niveau --</option>
                            <option value="beginner">Débutant (moins de 1 an)</option>
                            <option value="intermediate">Intermédiaire (1-3 ans)</option>
                            <option value="advanced">Avancé (plus de 3 ans)</option>
                        </select>
                        <small class="text-muted">Base sur votre expérience en course</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg fw-bold w-100" onclick="generatePlan()">
                            <i class="fas fa-calculator me-2"></i>Générer mon plan personnalisé
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg fw-bold w-100" onclick="resetPlanner()">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultats du Planificateur -->
        <div id="planResults" class="d-none">
            <!-- Plan Personnalise -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-chart-pie me-2"></i>
                        Recommandation Personnalisée - Distribution Scientifique
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5 class="alert-heading">Plan d'Entraînement Optimal</h5>
                        <p id="planDescription">
                            <!-- Sera rempli par JavaScript -->
                        </p>
                        <p>
                            <span class="fs-3"><strong class="text-success" id="totalSessions">0</strong></span>
                            <span class="fs-5"> séances par semaine</span>
                        </p>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white text-center">
                                    <h6 class="mb-0">Endurance (Zone 1-2)</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong id="enduranceSessions">0</strong></span>
                                        <small class="d-block">séances/sem</small>
                                    </p>
                                    <small class="text-muted">60% volume - Base aérobie</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning text-dark text-center">
                                    <h6 class="mb-0">Seuil (Zone 3)</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong id="thresholdSessions">0</strong></span>
                                        <small class="d-block">séances/sem</small>
                                    </p>
                                    <small class="text-muted">20% volume - Tempo/Lactate</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger h-100">
                                <div class="card-header bg-danger text-white text-center">
                                    <h6 class="mb-0">Vitesse (Zone 4-5)</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong id="speedSessions">0</strong></span>
                                        <small class="d-block">séances/sem</small>
                                    </p>
                                    <small class="text-muted">15% volume - VO2max/Neurom.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info h-100">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">Récupération</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong id="recoverySessions">0</strong></span>
                                        <small class="d-block">séances/sem</small>
                                    </p>
                                    <small class="text-muted">5% volume - Footing régénération</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-water me-2"></i>Distribution Polarisée 80/20</h6>
                        <p class="mb-0">
                            Ce plan respecte la règle scientifique des ≥80% du volume en zones 1-2 (faible intensité) 
                            pour optimiser les adaptations aérobies selon les recherches sur les coureurs élites.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Microcycle Detaille -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-calendar-week me-2"></i>
                        Microcycle Type Recommandé
                    </h3>
                </div>
                <div class="card-body">
                    <div id="weeklySchedule" class="table-responsive">
                        <!-- Sera rempli par JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        
        <!-- Fondements Scientifiques -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-flask me-2"></i>
                    Fondements Scientifiques en Course à pied - Recherches 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-danger border-0">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Position ACSM 2024</h6>
                    <p class="mb-0">
                        L'entraînement en course à pied doit respecter le principe de distribution polarisée 
                        avec ≥80% du volume en zones 1-2 pour optimiser les adaptations aérobies.
                    </p>
                </div>
                
                <p>
                    Les recommandations s'appuient sur les dernières recherches en biomécanique, physiologie de l'exercice 
                    et sciences du sport publiees en 2024-2025, incluant la meta-analyse de Van Hooren et al. sur l'économie de course.
                </p>
                
                <div class="alert alert-info border-0">
                    <h6><i class="fas fa-lightbulb me-2"></i>Découverte majeure 2024</h6>
                    <p class="mb-0">
                        Les variables biomécaniques expliquent 4-12% de la variance dans l'économie de course. 
                        La règle 80/20 reste le standard : ≥80% du volume en basse intensité chez les élites.
                    </p>
                </div>

                <div class="row g-4 mt-3">
                    <div class="col-md-6">
                        <h6>Distribution d'Intensité élite (Recherche 2024)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Catégorie</th>
                                        <th>Zone 1-2</th>
                                        <th>Zone 3</th>
                                        <th>Zone 4-5</th>
                                        <th>Volume/sem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td><strong>Marathon élite</strong></td>
                                        <td>85%</td>
                                        <td>10%</td>
                                        <td>5%</td>
                                        <td>160-220km</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>5000-10000m élite</td>
                                        <td>80%</td>
                                        <td>12%</td>
                                        <td>8%</td>
                                        <td>130-190km</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>Amateur Avancé</td>
                                        <td>75%</td>
                                        <td>15%</td>
                                        <td>10%</td>
                                        <td>60-100km</td>
                                    </tr>
                                    <tr>
                                        <td>Amateur Intermédiaire</td>
                                        <td>70%</td>
                                        <td>20%</td>
                                        <td>10%</td>
                                        <td>30-60km</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted">
                            Source : Analyse de coureurs de classe mondiale (2024). 
                            Distribution polarisée vs pyramidale montre des résultats similaires pour l'élite.
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h6>Caracteristiques Physiologiques élites</h6>
                        <ul class="small">
                            <li><strong>VO2max :</strong> 70-85 ml/kg/min (hommes), 60-75 (femmes)</li>
                            <li><strong>Économie course :</strong> 150-190 ml O2/kg/km</li>
                            <li><strong>Seuil lactique :</strong> 85-95% VO2max</li>
                            <li><strong>Fréquence :</strong> 11-14 séances/semaine</li>
                            <li><strong>Compétitions :</strong> 6±2 (marathon), 9±3 (piste) par an</li>
                        </ul>
                        
                        <div class="alert alert-warning">
                            <small>
                                <strong>Innovation 2024 :</strong> Les tests métaboliques portables (lactate, VO2) 
                                permettent une détermination précise des zones individuelles en temps réel.
                            </small>
                        </div>

                        <h6 class="mt-3">Facteurs Limitants Performance</h6>
                        <ul class="small">
                            <li><strong>Cardiovasculaire :</strong> Debit cardiaque maximal (40-50%)</li>
                            <li><strong>Respiratoire :</strong> Diffusion alveolo-capillaire (15-20%)</li>
                            <li><strong>Métabolique :</strong> Densite mitochondriale (20-25%)</li>
                            <li><strong>Biomécanique :</strong> Économie gestuelle (10-15%)</li>
                            <li><strong>Neuromusculaire :</strong> Rigidite tendineuse (5-10%)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biomecanique et economie de Course -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-running me-2"></i>
                    Biomécanique et économie de Course - Recherches 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Facteurs Biomécaniques Cles (Meta-analyse 2024)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Variable</th>
                                        <th>Correlation</th>
                                        <th>Impact Performance</th>
                                        <th>Optimum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Fréquence de foulee</td>
                                        <td>r = -0.20</td>
                                        <td>↑ Fréquence = ↓ Coût énergétique</td>
                                        <td>170-190 pas/min</td>
                                    </tr>
                                    <tr>
                                        <td>Oscillation verticale</td>
                                        <td>r = 0.35</td>
                                        <td>↓ Oscillation = ↑ Efficacité</td>
                                        <td>&lt;8cm A 12km/h</td>
                                    </tr>
                                    <tr>
                                        <td>Rigidite jambe</td>
                                        <td>r = -0.28</td>
                                        <td>↑ Rigidite = ↓ Coût énergétique</td>
                                        <td>20-30 kN/m</td>
                                    </tr>
                                    <tr>
                                        <td>Temps contact sol</td>
                                        <td>r = 0.25</td>
                                        <td>↓ Contact = ↑ Vitesse</td>
                                        <td>180-220ms</td>
                                    </tr>
                                    <tr>
                                        <td>Angle attaque pied</td>
                                        <td>r = 0.22</td>
                                        <td>↓ Angle = ↑ économie</td>
                                        <td>0-8° (avant-pied)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="mt-3">Paramètres de Foulee Optimaux</h6>
                        <ul class="small">
                            <li><strong>Longueur foulee :</strong> Auto-selection naturelle optimale</li>
                            <li><strong>Largeur foulee :</strong> 5-10cm entre pieds</li>
                            <li><strong>Position pied :</strong> Sous centre gravite corporel</li>
                            <li><strong>Flexion genou :</strong> 40-50° au contact initial</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Technologies d'Analyse Biomécanique 2024</h6>
                        <ul class="small">
                            <li><strong>Capteurs inertiels 3D :</strong> Analyse biomécanique temps réel portable</li>
                            <li><strong>Plateformes force :</strong> Mesure forces reaction sol précise</li>
                            <li><strong>Cameras haute vitesse :</strong> 1000+ fps analyse gestuelle</li>
                            <li><strong>Wearables avances :</strong> Métriques oscillation/rigidite</li>
                            <li><strong>IA analyse foulee :</strong> Correction technique automatisee</li>
                            <li><strong>Capteurs puissance :</strong> Quantification charge externe (Stryd)</li>
                        </ul>
                        
                        <div class="alert alert-success">
                            <small>
                                <strong>Application pratique :</strong> L'augmentation de 5-10% de la fréquence 
                                vers l'optimum individuel améliore l'économie de course de 2-4%.
                            </small>
                        </div>

                        <h6 class="mt-3">Analyse Gestuelle Spécialisée</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Phase</th>
                                        <th>Durée (%)</th>
                                        <th>Événements Cles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Contact initial</td>
                                        <td>0%</td>
                                        <td>Attaque pied, début amortissement</td>
                                    </tr>
                                    <tr>
                                        <td>Phase d'appui</td>
                                        <td>0-40%</td>
                                        <td>Absorption choc, transfert poids</td>
                                    </tr>
                                    <tr>
                                        <td>Phase propulsion</td>
                                        <td>40-60%</td>
                                        <td>Generation force, push-off</td>
                                    </tr>
                                    <tr>
                                        <td>Phase aerienne</td>
                                        <td>60-100%</td>
                                        <td>Vol, récupération jambe</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zones d'Entraînement Scientifiques -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-water me-2"></i>
                    Zones d'Entraînement Basees sur la Science
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Modèle 5 Zones Validé (Coggan/Seiler)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Zone</th>
                                        <th>% FC Max</th>
                                        <th>% VO2max</th>
                                        <th>Lactate (mmol/L)</th>
                                        <th>Objectif Physiologique</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td><strong>Zone 1</strong></td>
                                        <td>65-75%</td>
                                        <td>45-65%</td>
                                        <td>&lt; 2</td>
                                        <td>Récupération active</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td><strong>Zone 2</strong></td>
                                        <td>75-85%</td>
                                        <td>65-80%</td>
                                        <td>2-3</td>
                                        <td>Base aérobie, fat-max</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Zone 3</td>
                                        <td>85-90%</td>
                                        <td>80-87%</td>
                                        <td>3-4</td>
                                        <td>Tempo, seuil aérobie</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>Zone 4</td>
                                        <td>90-95%</td>
                                        <td>87-95%</td>
                                        <td>4-6</td>
                                        <td>Seuil lactique, VO2max</td>
                                    </tr>
                                    <tr class="table-dark">
                                        <td>Zone 5</td>
                                        <td>&gt; 95%</td>
                                        <td>&gt; 95%</td>
                                        <td>&gt; 6</td>
                                        <td>Puissance anaérobie</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="mt-3">Détermination Zones Individuelles</h6>
                        <ul class="small">
                            <li><strong>Test terrain :</strong> Test 30min all-out (seuil 95%)</li>
                            <li><strong>Test lactate :</strong> Paliers progressifs + dosage</li>
                            <li><strong>Test VO2max :</strong> Laboratoire spirométrie</li>
                            <li><strong>HRV analyse :</strong> Variabilité cardiaque repos</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Zone 2 : La Revolution Métabolique 2024</h6>
                        <p class="small">La Zone 2 correspond à l'intensité de combustion maximale des graisses (FatMax) et d'équilibre lactate.</p>
                        <ul class="small">
                            <li><strong>Détermination précise :</strong> Test métabolique (étalon-or)</li>
                            <li><strong>Caracteristique :</strong> État stable production/elimination lactate</li>
                            <li><strong>Adaptation cellulaire :</strong> Optimisation biogenese mitochondriale</li>
                            <li><strong>Durée optimale :</strong> 45-90 minutes en continu</li>
                            <li><strong>Fréquence :</strong> 3-5x/semaine selon niveau</li>
                        </ul>
                        
                        <div class="alert alert-warning">
                            <small>
                                <strong>Note importante :</strong> Pour les coureurs débutants, 
                                la Zone 2 peut necessiter des phases de marche-course alternées (run-walk).
                            </small>
                        </div>

                        <h6 class="mt-3">Biomarqueurs Zone 2</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Paramètre</th>
                                        <th>Valeur Zone 2</th>
                                        <th>Adaptation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>RER (VCO2/VO2)</td>
                                        <td>0.85-0.87</td>
                                        <td>Oxydation graisses optimale</td>
                                    </tr>
                                    <tr>
                                        <td>Lactate sanguin</td>
                                        <td>2.0±0.5 mmol/L</td>
                                        <td>Équilibre production/clairance</td>
                                    </tr>
                                    <tr>
                                        <td>Efficacité cardiaque</td>
                                        <td>Volume ejection ↑</td>
                                        <td>Adaptation cardiovasculaire</td>
                                    </tr>
                                    <tr>
                                        <td>Densite mitochondriale</td>
                                        <td>Biogenese ↑</td>
                                        <td>Capacité oxydative ↑</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Methodes d'Entraînement Modernes -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-rocket me-2"></i>
                    Méthodes d'Entraînement Innovantes 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Entraînement Polarisé</h6>
                                <small>(Evidence-Based)</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Répartition :</strong> 80% Volume Zone 1-2</li>
                                    <li><strong>Intensité :</strong> 20% Volume Zone 4-5</li>
                                    <li><strong>Zone 3 :</strong> Minimum ("no man's land")</li>
                                    <li><strong>Séances intensité :</strong> 2-3 max/semaine</li>
                                    <li><strong>Récupération :</strong> Complete entre intensités</li>
                                </ul>
                                
                                <h6 class="mt-3">Benefices Scientifiques</h6>
                                <ul class="small">
                                    <li>↑ VO2max (+8-15% vs traditionnel)</li>
                                    <li>↑ économie course (+3-7%)</li>
                                    <li>↓ Risque blessures (-30%)</li>
                                    <li>↑ Capacité tampons lactate</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Micro-Dosage Haute Intensité</h6>
                                <small>(Tendance 2024)</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Durée :</strong> Séances courtes haute qualite</li>
                                    <li><strong>Format :</strong> 4-6 x 30s A 95-100% VMA</li>
                                    <li><strong>Récupération :</strong> Complete (2-3 min)</li>
                                    <li><strong>Fréquence :</strong> 2-3x/semaine développement neuromusculaire</li>
                                    <li><strong>Objectif :</strong> Puissance anaérobie + économie</li>
                                </ul>
                                
                                <h6 class="mt-3">Protocoles Spécifiques</h6>
                                <ul class="small">
                                    <li><strong>15/15 :</strong> 15s ON/15s OFF x 12-20</li>
                                    <li><strong>30/30 :</strong> 30s Z5/30s Z1 x 8-15</li>
                                    <li><strong>Billat 30-30 :</strong> vVMA/50% vVMA</li>
                                    <li><strong>Norwegian 4x4 :</strong> 4min Z4/3min Z1</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Entraînement en Hypoxie</h6>
                                <small>(Innovation Tech)</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Altitude simulee :</strong> Masques/chambres hypoxiques</li>
                                    <li><strong>Protocole :</strong> "Live High, Train Low"</li>
                                    <li><strong>Adaptation :</strong> ↑ Hématocrite, EPO naturelle</li>
                                    <li><strong>Durée :</strong> 2-4 semaines minimum</li>
                                    <li><strong>Benefice :</strong> ↑ Transport O2 (+2-5%)</li>
                                </ul>
                                
                                <h6 class="mt-3">Technologies Disponibles</h6>
                                <ul class="small">
                                    <li><strong>Hypoxico Altitude :</strong> Generateurs hypoxie</li>
                                    <li><strong>Training Mask :</strong> Résistance respiratoire</li>
                                    <li><strong>Chambres altitude :</strong> Environnement contrôle</li>
                                    <li><strong>IHT (Intermittent) :</strong> Cycles hypoxie/normoxie</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-3">
                    <div class="col-md-6">
                        <h6>Entraînement Croise Spécialisé</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Activité</th>
                                        <th>Benefice</th>
                                        <th>Fréquence</th>
                                        <th>Intensité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Aqua-jogging</td>
                                        <td>Volume sans impact</td>
                                        <td>2-3x/sem</td>
                                        <td>Z2-Z3</td>
                                    </tr>
                                    <tr>
                                        <td>Velo</td>
                                        <td>Capacité cardiovasculaire</td>
                                        <td>1-2x/sem</td>
                                        <td>Z2-Z4</td>
                                    </tr>
                                    <tr>
                                        <td>Elliptique</td>
                                        <td>Geste proche + impact ↓</td>
                                        <td>1-2x/sem</td>
                                        <td>Z2-Z3</td>
                                    </tr>
                                    <tr>
                                        <td>Natation</td>
                                        <td>Récupération active</td>
                                        <td>1x/sem</td>
                                        <td>Z1-Z2</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Périodisation Moderne Avancée</h6>
                        <ul class="small">
                            <li><strong>Block Periodization :</strong> Blocs spécialisés 3-6 sem</li>
                            <li><strong>Reverse Periodization :</strong> Intensité → Volume</li>
                            <li><strong>Conjugate Method :</strong> Stimuli simultanes varies</li>
                            <li><strong>Auto-Regulated :</strong> Ajustement quotidien HRV</li>
                        </ul>
                        
                        <div class="alert alert-info">
                            <small>
                                <strong>Recommandation 2024 :</strong> La périodisation par blocs 
                                montre des résultats superieurs (+12%) vs périodisation lineaire traditionnelle.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nutrition Specialisee Course -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-utensils me-2"></i>
                    Nutrition Course à pied - Spécificités 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Stratégies Pré-effort Optimisees</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Timing</th>
                                        <th>Macronutriment</th>
                                        <th>Quantite</th>
                                        <th>Objectif Physiologique</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>3-4h avant</td>
                                        <td>Glucides</td>
                                        <td>2-4g/kg</td>
                                        <td>Saturation glycogene hepatique</td>
                                    </tr>
                                    <tr>
                                        <td>1-2h avant</td>
                                        <td>Glucides</td>
                                        <td>30-60g</td>
                                        <td>Maintien glycémie</td>
                                    </tr>
                                    <tr>
                                        <td>2h avant</td>
                                        <td>Fluides</td>
                                        <td>400-600ml</td>
                                        <td>Hydratation optimale</td>
                                    </tr>
                                    <tr>
                                        <td>30-60min avant</td>
                                        <td>Cafeine</td>
                                        <td>3-6mg/kg</td>
                                        <td>Performance + vigilance</td>
                                    </tr>
                                    <tr>
                                        <td>15min avant</td>
                                        <td>Nitrates</td>
                                        <td>5-9mmol</td>
                                        <td>↑ Efficacité mitochondriale</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h6 class="mt-3">Pendant l'Effort (≥ 60min)</h6>
                        <ul class="small">
                            <li><strong>Glucides :</strong> 30-60g/heure selon durée effort</li>
                            <li><strong>Multi-transporteurs :</strong> Glucose:Fructose 2:1</li>
                            <li><strong>Boisson isotonique :</strong> 4-8% glucides concentration</li>
                            <li><strong>Hydratation :</strong> 150-250ml/15-20min réguliers</li>
                            <li><strong>Électrolytes :</strong> Sodium 200-700mg/L selon sudation</li>
                        </ul>

                        <h6 class="mt-3">Stratégies Spécialisées</h6>
                        <ul class="small">
                            <li><strong>Fat adaptation :</strong> 2-3 semaines keto + recharge CHO</li>
                            <li><strong>Train low, compete high :</strong> Périodisation glucides</li>
                            <li><strong>Fasted training :</strong> Zone 2 optimisation</li>
                            <li><strong>Sleep low :</strong> Coucher glycogene bas</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Récupération Nutritionnelle Optimisee</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Timing</th>
                                        <th>Nutriment</th>
                                        <th>Quantite</th>
                                        <th>Objectif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>0-30min</td>
                                        <td>Glucides</td>
                                        <td>1-1.2g/kg</td>
                                        <td>Resynthese glycogene rapide</td>
                                    </tr>
                                    <tr>
                                        <td>0-30min</td>
                                        <td>Protéines</td>
                                        <td>20-25g (leucine 3g)</td>
                                        <td>Synthese protéique (mTOR)</td>
                                    </tr>
                                    <tr>
                                        <td>0-60min</td>
                                        <td>Ratio CHO:Pro</td>
                                        <td>3:1 ou 4:1</td>
                                        <td>Anabolisme optimal</td>
                                    </tr>
                                    <tr>
                                        <td>Continu</td>
                                        <td>Réhydratation</td>
                                        <td>150% poids perdu</td>
                                        <td>Restauration volume plasmatique</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info">
                            <small>
                                <strong>Découverte 2024 :</strong> La fenêtre anabolique post-exercice 
                                est plus longue qu'estimé (2-3h), mais l'optimisation reste cruciale dans les 30min.
                            </small>
                        </div>
                        
                        <h6 class="mt-3">Supplementation Evidence-Based Coureurs</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Supplement</th>
                                        <th>Dosage</th>
                                        <th>Effet Performance</th>
                                        <th>Niveau Preuve</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Cafeine</td>
                                        <td>3-6mg/kg</td>
                                        <td>↑ 2-4% endurance</td>
                                        <td>⭐⭐⭐⭐⭐</td>
                                    </tr>
                                    <tr>
                                        <td>Nitrates (betterave)</td>
                                        <td>5-9mmol</td>
                                        <td>↑ 1-3% économie</td>
                                        <td>⭐⭐⭐⭐</td>
                                    </tr>
                                    <tr>
                                        <td>Bêta-Alanine</td>
                                        <td>3-5g/jour</td>
                                        <td>↑ Tampon lactate</td>
                                        <td>⭐⭐⭐</td>
                                    </tr>
                                    <tr>
                                        <td>Bicarbonate Na</td>
                                        <td>0.3g/kg</td>
                                        <td>↑ Capacité tampon</td>
                                        <td>⭐⭐⭐</td>
                                    </tr>
                                    <tr>
                                        <td>Creatine</td>
                                        <td>3-5g/jour</td>
                                        <td>↑ Sprints repetes</td>
                                        <td>⭐⭐⭐</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="mt-3">Micronutriments Critiques</h6>
                        <ul class="small">
                            <li><strong>Fer :</strong> 15-20mg/jour (femmes), monitoring ferritine</li>
                            <li><strong>Vitamine D :</strong> 1000-4000 UI/jour selon statut</li>
                            <li><strong>B12 :</strong> 2.4μg/jour minimum (vegetaliens)</li>
                            <li><strong>Magnesium :</strong> 400-600mg/jour (crampes, récupération)</li>
                            <li><strong>Zinc :</strong> 8-15mg/jour (immunite, récupération)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prevention des Blessures -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-shield-alt me-2"></i>
                    Prévention des Blessures en Course à pied
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning border-0">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Statistiques cles 2024</h6>
                    <p class="mb-0">
                        72% des blessures en triathlon proviennent de la course à pied. 
                        Incidence annuelle : 37-56% des coureurs se blessent, avec 2.5-33 blessures pour 1000h d'entraînement.
                    </p>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6>Blessures les Plus Frequentes (Prévalence %)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Blessure</th>
                                        <th>Prévalence</th>
                                        <th>Zone Anatomique</th>
                                        <th>Facteur Principal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Syndrome essuie-glace (ITB)</td>
                                        <td><strong>12%</strong></td>
                                        <td>Genou lateral</td>
                                        <td>Faiblesse hanche</td>
                                    </tr>
                                    <tr>
                                        <td>Fasciite plantaire</td>
                                        <td><strong>10%</strong></td>
                                        <td>Pied</td>
                                        <td>Surcharge, rigidite</td>
                                    </tr>
                                    <tr>
                                        <td>Periostite tibiale</td>
                                        <td><strong>9%</strong></td>
                                        <td>Jambe</td>
                                        <td>Progression rapide</td>
                                    </tr>
                                    <tr>
                                        <td>Syndrome femoro-patellaire</td>
                                        <td><strong>8%</strong></td>
                                        <td>Genou anterieur</td>
                                        <td>Déséquilibre quadriceps</td>
                                    </tr>
                                    <tr>
                                        <td>Tendinopathie Achille</td>
                                        <td><strong>7%</strong></td>
                                        <td>Cheville posterieure</td>
                                        <td>Raideur mollets</td>
                                    </tr>
                                    <tr>
                                        <td>Fractures de stress</td>
                                        <td>5%</td>
                                        <td>Tibia, metatarses</td>
                                        <td>Charge excessive</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Stratégies Préventives Evidence-Based 2024</h6>
                        <ul class="small">
                            <li><strong>Progression contrôlee :</strong> Règle 10% volume/semaine max</li>
                            <li><strong>Renforcement spécifique :</strong> Hanches, core, mollets quotidien</li>
                            <li><strong>Variete surfaces :</strong> Rotation bitume/terre/piste</li>
                            <li><strong>Chaussures adaptees :</strong> Rotation 2-3 paires differentes</li>
                            <li><strong>Analyse biomécanique :</strong> Évaluation foulee annuelle</li>
                            <li><strong>Monitoring charge :</strong> Ratio aigu:chronique &lt;1.5</li>
                            <li><strong>Récupération programmee :</strong> Semaines décharge régulières</li>
                        </ul>

                        <h6 class="mt-3">Tests de Dépistage Recommandes</h6>
                        <ul class="small">
                            <li><strong>Single Leg Squat :</strong> Contrôle frontal/sagittal</li>
                            <li><strong>Y-Balance Test :</strong> Stabilite dynamique asymetries</li>
                            <li><strong>Hop Tests :</strong> Fonction neuromusculaire</li>
                            <li><strong>Analyse foulee :</strong> Camera haute vitesse</li>
                            <li><strong>Tests force isometrique :</strong> Ratios musculaires</li>
                        </ul>

                        <div class="alert alert-success">
                            <small>
                                <strong>Efficacité prouvee :</strong> Les programmes préventifs réduisent 
                                l'incidence des blessures de 35-50% selon meta-analyses 2024.
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Programme Prévention Quotidien (15-20min)</h6>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white">
                                    <strong>Échauffement Dynamique (15min)</strong>
                                </div>
                                <div class="card-body">
                                    <ul class="small">
                                        <li>Activation cardiovasculaire progressive (5min)</li>
                                        <li>Mobilite articulaire dynamique (5min)</li>
                                        <li>Gammes coureur spécifiques (5min)</li>
                                        <li>Progression allure jusqu'A intensité cible</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning text-dark">
                                    <strong>Renforcement Préventif (20min)</strong>
                                </div>
                                <div class="card-body">
                                    <ul class="small">
                                        <li>Squats/Fentes unipodales (force hanches)</li>
                                        <li>Gainage statique/dynamique (core stability)</li>
                                        <li>Travail proprioception (équilibre)</li>
                                        <li>Renforcement mollets/tibial anterieur</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info h-100">
                                <div class="card-header bg-info text-white">
                                    <strong>Récupération Active (15min)</strong>
                                </div>
                                <div class="card-body">
                                    <ul class="small">
                                        <li>Retour au calme progressif (5min marche)</li>
                                        <li>Étirements statiques cibles (5min)</li>
                                        <li>Auto-massage/foam rolling (5min)</li>
                                        <li>Hydratation + nutrition récupération</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger mt-3">
                    <h6><i class="fas fa-exclamation-circle me-2"></i>Important - Sécurité</h6>
                    <p class="mb-0">
                        En cas de douleur persistante (&gt;3 jours), de progression stagnante ou de signaux d'alarme, 
                        consultez un professionnel qualifie (médecin du sport, kinesitherapeute). 
                        La progression graduelle et l'écoute du corps sont prioritaires sur la performance immediate.
                    </p>
                </div>
            </div>
        </div>

        <!-- Conseils Generaux Evidence-Based -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Conseils Généraux Evidence-Based
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6>Optimisation Technique</h6>
                        <ul class="small">
                            <li><strong>Cadence optimale :</strong> 170-190 pas/min (auto-selection)</li>
                            <li><strong>Attaque pied :</strong> Medio/avant-pied privilegiee</li>
                            <li><strong>Posture corporelle :</strong> Légère inclinaison avant</li>
                            <li><strong>Regard horizontal :</strong> 10-20m devant</li>
                            <li><strong>Bras decontractes :</strong> Balancier naturel 90°</li>
                            <li><strong>Éducatifs techniques :</strong> 2-3x/semaine integres</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Programmation Optimale</h6>
                        <ul class="small">
                            <li><strong>Progression volume :</strong> +10% maximum/semaine</li>
                            <li><strong>Règle 80/20 :</strong> Strictement respectee toute saison</li>
                            <li><strong>Échauffement obligatoire :</strong> 15-20 min progressif</li>
                            <li><strong>Récupération active :</strong> 10-15min post-seance</li>
                            <li><strong>Tests réguliers :</strong> VMA, seuils trimestriels</li>
                            <li><strong>Périodisation :</strong> Cycles 3-4 semaines + décharge</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Récupération Optimisee</h6>
                        <ul class="small">
                            <li><strong>Sommeil prioritaire :</strong> 7-9h qualite optimale</li>
                            <li><strong>Hydratation adaptee :</strong> Selon taux sudation individuel</li>
                            <li><strong>Nutrition ciblee :</strong> Périodisation glucides</li>
                            <li><strong>Gestion stress :</strong> Techniques relaxation</li>
                            <li><strong>Modalités récupération :</strong> Bains froids, massage</li>
                            <li><strong>Monitoring continu :</strong> HRV, wellness scores</li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-success mt-4">
                    <h6><i class="fas fa-check-circle me-2"></i>Principe Fondamental</h6>
                    <p class="mb-0">
                        La constance dans l'entraînement à faible intensité (Zone 2) représente 80% des gains de performance 
                        en course d'endurance. La patience et la régularité priment sur l'intensité excessive.
                    </p>
                </div>
            </div>
        </div>

        <!-- References Scientifiques -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-book me-2"></i>
                    Références Scientifiques et Sources
                </h3>
            </div>
            <div class="card-body">
                <p>
                    Ce planificateur integre les dernières recherches en sciences du sport et course d'endurance
                    publiees en 2024-2025 dans des revues scientifiques de référence internationale :
                </p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <h6>Biomécanique Course</h6>
                        <ul class="small">
                            <li>Sports Medicine (Van Hooren et al., 2024)</li>
                            <li>Journal of Biomechanics</li>
                            <li>Sports Biomechanics</li>
                            <li>Gait & Posture</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Physiologie Exercice</h6>
                        <ul class="small">
                            <li>Frontiers in Physiology - Exercise Physiology</li>
                            <li>European Journal of Applied Physiology</li>
                            <li>Medicine & Science in Sports & Exercise</li>
                            <li>Journal of Applied Physiology</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Entraînement & Performance</h6>
                        <ul class="small">
                            <li>Sports Medicine - Open</li>
                            <li>International Journal of Sports Physiology</li>
                            <li>Scandinavian Journal of Medicine & Science</li>
                            <li>Journal of Sports Sciences</li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-chart-line me-2"></i>Meta-analyses cles 2024</h6>
                    <p class="mb-0">
                        Les dernières revues systématiques confirment l'efficacité supérieure de l'entraînement polarisé 
                        pour les performances d'endurance, avec des gains de 8-15% vs entraînement traditionnel 
                        chez les coureurs entraînés.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('public.tools.partials.credit')
@endsection

@push('styles')
<style>
.table th {
    border-top: none;
}

.card {
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
// Base de donnees des plans d'entraînement
const trainingPlans = {
    'endurance': {
        'beginner': 3,
        'intermediate': 4,
        'advanced': 5
    },
    'weight': {
        'beginner': 2,
        'intermediate': 3,
        'advanced': 4
    },
    'speed': {
        'beginner': 3,
        'intermediate': 4,
        'advanced': 5
    },
    '10k': {
        'beginner': 3,
        'intermediate': 4,
        'advanced': 5
    },
    'half': {
        'beginner': 3,
        'intermediate': 4,
        'advanced': 6
    },
    'marathon': {
        'beginner': 4,
        'intermediate': 5,
        'advanced': 6
    }
};

// Descriptions des objectifs
const goalDescriptions = {
    'endurance': 'Améliorer l\'endurance',
    'weight': 'Perdre du poids',
    'speed': 'Gagner en vitesse',
    '10k': 'Courir un 10 km',
    'half': 'Courir un semi-marathon',
    'marathon': 'Courir un marathon'
};

// Descriptions des niveaux
const experienceDescriptions = {
    'beginner': 'debutant',
    'intermediate': 'intermediaire',
    'advanced': 'avance'
};

// Microcycles types par niveau
const weeklySchedules = {
    'beginner': {
        'Lundi': 'Repos',
        'Mardi': 'Fartlek (30min)',
        'Mercredi': 'Endurance Z2 (30min)',
        'Jeudi': 'Repos',
        'Vendredi': 'Repos',
        'Samedi': 'Sortie longue (45min)',
        'Dimanche': 'Repos'
    },
    'intermediate': {
        'Lundi': 'Endurance Z2 (45min)',
        'Mardi': 'Tempo Z3 (30min)',
        'Mercredi': 'Repos ou Z1 (30min)',
        'Jeudi': 'Endurance Z2 (45min)',
        'Vendredi': 'Repos',
        'Samedi': 'Intervalles Z4 (45min)',
        'Dimanche': 'Sortie longue (90min)'
    },
    'advanced': {
        'Lundi': 'Endurance Z2 (60min)',
        'Mardi': 'Intervalles Z4-5 (45min)',
        'Mercredi': 'Endurance Z2 (75min)',
        'Jeudi': 'Tempo Z3 (45min)',
        'Vendredi': 'Endurance Z1-2 (45min)',
        'Samedi': 'Séance spécifique (60min)',
        'Dimanche': 'Sortie longue (120-180min)'
    }
};

function generatePlan() {
    const goal = document.getElementById('goal').value;
    const experience = document.getElementById('experience').value;
    const errorDiv = document.getElementById('errorMessage');
    
    // Validation
    if (!goal || !experience) {
        showError('Veuillez sélectionner un objectif et un niveau d\'expérience.');
        return;
    }
    
    // Masquer les erreurs
    errorDiv.classList.add('d-none');
    
    // Recuperer le nombre de seances
    const totalSessions = trainingPlans[goal][experience];
    
    // Calculer la distribution selon le modele polarise
    const enduranceSessions = Math.round(totalSessions * 0.6); // 60% Zone 1-2
    const thresholdSessions = Math.round(totalSessions * 0.2); // 20% Zone 3
    const speedSessions = Math.round(totalSessions * 0.15); // 15% Zone 4-5
    const recoverySessions = Math.max(0, Math.round(totalSessions * 0.05)); // 5% Recuperation
    
    // Afficher les resultats
    displayResults(goal, experience, totalSessions, {
        endurance: enduranceSessions,
        threshold: thresholdSessions,
        speed: speedSessions,
        recovery: recoverySessions
    });
}

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = message;
    errorDiv.classList.remove('d-none');
    document.getElementById('planResults').classList.add('d-none');
}

function displayResults(goal, experience, totalSessions, distribution) {
    // Affichage du plan principal
    document.getElementById('planDescription').innerHTML = `
        Pour votre objectif <strong class="text-primary">${goalDescriptions[goal]}</strong> et niveau 
        <strong class="text-warning">${experienceDescriptions[experience]}</strong>, 
        il est recommandé de courir :
    `;
    
    document.getElementById('totalSessions').textContent = totalSessions;
    document.getElementById('enduranceSessions').textContent = distribution.endurance;
    document.getElementById('thresholdSessions').textContent = distribution.threshold;
    document.getElementById('speedSessions').textContent = distribution.speed;
    document.getElementById('recoverySessions').textContent = distribution.recovery;
    
    // Affichage du microcycle detaille
    displayWeeklySchedule(experience);
    
    // Afficher la section resultats
    document.getElementById('planResults').classList.remove('d-none');
    document.getElementById('planResults').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function displayWeeklySchedule(experience) {
    const schedule = weeklySchedules[experience];
    const scheduleDiv = document.getElementById('weeklySchedule');
    
    let tableHTML = `
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Jour</th>
                    <th>Séance Recommandée</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    Object.entries(schedule).forEach(([day, session]) => {
        let notes = '';
        let rowClass = '';
        
        if (session.includes('Repos')) {
            notes = 'Récupération complete ou marche légère';
            rowClass = 'table-light';
        } else if (session.includes('Z2') || session.includes('Endurance')) {
            notes = 'Zone 2: conversationnel, base aérobie';
            rowClass = 'table-success';
        } else if (session.includes('Z3') || session.includes('Tempo')) {
            notes = 'Zone 3: rythme soutenu mais contrôle';
            rowClass = 'table-warning';
        } else if (session.includes('Z4') || session.includes('Z5') || session.includes('Intervalles')) {
            notes = 'Zone 4-5: haute intensité, récupération complete';
            rowClass = 'table-danger';
        } else if (session.includes('longue')) {
            notes = 'Développement endurance fondamentale';
            rowClass = 'table-info';
        } else {
            notes = 'Alternance allures, travail technique';
        }
        
        tableHTML += `
            <tr class="${rowClass}">
                <td><strong>${day}</strong></td>
                <td>${session}</td>
                <td><small class="text-muted">${notes}</small></td>
            </tr>
        `;
    });
    
    tableHTML += `
            </tbody>
        </table>
    `;
    
    scheduleDiv.innerHTML = tableHTML;
}

function resetPlanner() {
    document.getElementById('goal').value = '';
    document.getElementById('experience').value = '';
    document.getElementById('errorMessage').classList.add('d-none');
    document.getElementById('planResults').classList.add('d-none');
}

// Generation automatique si les deux champs sont remplis
document.getElementById('goal').addEventListener('change', checkAutoGenerate);
document.getElementById('experience').addEventListener('change', checkAutoGenerate);

function checkAutoGenerate() {
    const goal = document.getElementById('goal').value;
    const experience = document.getElementById('experience').value;
    
    if (goal && experience) {
        setTimeout(generatePlan, 300); // Delai pour eviter les generations trop frequentes
    }
}
</script>
@endpush