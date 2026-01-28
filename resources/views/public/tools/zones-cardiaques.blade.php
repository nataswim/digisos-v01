@extends('layouts.public')

@section('title', 'Calculateur Zones Cardiaques Avance - FC Max & Zones Entraînement Scientifiques')
@section('meta_description', 'Calculateur zones cardiaques scientifique avec FC max personnalisee. Methodes Karvonen, Tanaka, zones d\'entraînement optimisees. Personnalisation sport, niveau, objectifs. Evidence-based 2024.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container py-3">
        <h1 class="display-4 fw-bold d-flex align-items-center justify-content-center gap-3 mb-3">
            <i class="fas fa-heartbeat text-danger"></i>
            Calculateur de Zones Cardiaques
        </h1>
        
    </div>
</section>

<!-- Calculateur Principal -->
<section class="py-5 bg-light">
    <div class="container">
        
        <!-- Calculateur de Zones -->
        <div class="card mb-4 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-calculator me-2"></i>
                    Calculateur de Zones Personnalisees
                </h3>
            </div>
            <div class="card-body">
                
                <!-- Messages d'erreur -->
                <div id="errorMessage" class="alert alert-danger d-none">
                    <!-- Sera rempli par JavaScript -->
                </div>

                <!-- Donnees de base -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="age" class="form-label fw-bold">Âge (annees)</label>
                        <input type="number" id="age" class="form-control form-control-lg border-primary" 
                               placeholder="30" min="10" max="100">
                    </div>
                    <div class="col-md-4">
                        <label for="restingHr" class="form-label fw-bold">FC repos (bpm)</label>
                        <input type="number" id="restingHr" class="form-control form-control-lg border-danger" 
                               placeholder="65" min="30" max="120">
                        <small class="text-muted">Mesuree au reveil</small>
                    </div>
                    <div class="col-md-4">
                        <label for="gender" class="form-label fw-bold">Sexe</label>
                        <select id="gender" class="form-select form-select-lg border-info">
                            <option value="male">Homme</option>
                            <option value="female">Femme</option>
                        </select>
                    </div>
                </div>

                <!-- Formule FC max et FC max connue -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="formula" class="form-label fw-bold">Formule FC max</label>
                        <select id="formula" class="form-select border-primary">
                            <option value="tanaka">Tanaka (208 - 0.7 × âge) - Recommandee</option>
                            <option value="asstrand">Åstrand (220 - âge) - Classique</option>
                            <option value="gulati">Gulati (206 - 0.88 × âge) - Femmes</option>
                            <option value="nes">Nes (211 - 0.64 × âge) - Athletes</option>
                            <option value="fairbarn">Fairbarn (201 - 0.63 × âge) - Seniors</option>
                            <option value="gellish">Gellish (207 - 0.7 × âge)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="useKnownMax">
                            <label class="form-check-label fw-bold" for="useKnownMax">
                                J'ai une FC max mesuree
                            </label>
                        </div>
                        <input type="number" id="maxHrKnown" class="form-control mt-2 border-success d-none" 
                               placeholder="190" min="120" max="220">
                    </div>
                </div>

                <!-- Personnalisation avancee -->
                <h5 class="fw-bold mb-3">Personnalisation Avancee</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="fitnessLevel" class="form-label">Niveau de forme</label>
                        <select id="fitnessLevel" class="form-select border-warning">
                            <option value="beginner">Debutant</option>
                            <option value="average" selected>Moyen</option>
                            <option value="trained">Entraîne</option>
                            <option value="athlete">Athlete</option>
                            <option value="elite">elite</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="sport" class="form-label">Sport principal</label>
                        <select id="sport" class="form-select border-info">
                            <option value="general" selected>General/Fitness</option>
                            <option value="running">Course A pied</option>
                            <option value="cycling">Cyclisme</option>
                            <option value="swimming">Natation</option>
                            <option value="triathlon">Triathlon</option>
                            <option value="team_sports">Sports collectifs</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="trainingGoal" class="form-label">Objectif d'entraînement</label>
                        <select id="trainingGoal" class="form-select border-secondary">
                            <option value="general" selected>Forme generale</option>
                            <option value="fat_loss">Perte de graisse</option>
                            <option value="endurance">Endurance</option>
                            <option value="performance">Performance</option>
                            <option value="recovery">Recuperation</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg fw-bold w-100" onclick="calculateZones()">
                            <i class="fas fa-calculator me-2"></i>Calculer Zones Cardiaques
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg fw-bold w-100" onclick="resetCalculator()">
                            <i class="fas fa-redo me-2"></i>Reinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultats -->
        <div id="resultsSection" class="d-none">
            <!-- Metriques principales -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-chart-line me-2"></i>
                        Vos Zones Cardiaques Personnalisees
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-danger h-100">
                                <div class="card-header bg-danger text-white text-center">
                                    <h6 class="mb-0">FC Max Estimee</h6>
                                    <small id="formulaUsed">Formule</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-danger" id="maxHrResult">0</strong></span>
                                        <small class="d-block">bpm</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white text-center">
                                    <h6 class="mb-0">Reserve Cardiaque</h6>
                                    <small>HRR</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-primary" id="hrrResult">0</strong></span>
                                        <small class="d-block">bpm</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-info h-100" id="fitnessCard">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">Condition Physique</h6>
                                    <small id="restingHrDisplay">FC repos</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <strong id="fitnessLevel">-</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning text-dark text-center">
                                    <h6 class="mb-0">Seuils</h6>
                                    <small>LT1 / LT2</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <strong class="text-warning" id="thresholds">0 / 0</strong>
                                        <small class="d-block">bpm</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zones d'entraînement detaillees -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-dark text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-layer-group me-2"></i>
                        Zones d'Entraînement Scientifiques
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="zonesContainer">
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
        
        <!-- Bases Physiologiques -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-heartbeat me-2"></i>
                    Bases Physiologiques des Zones Cardiaques
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Adaptations Cardiovasculaires et Metaboliques par Zone</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Zone</th>
                                        <th>Systeme energetique</th>
                                        <th>RER (VCO2/VO2)</th>
                                        <th>Adaptations Cardiovasculaires</th>
                                        <th>Adaptations Metaboliques</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td><strong>Zone 1</strong></td>
                                        <td>Aerobie lipidique (85%+)</td>
                                        <td>0.70-0.78</td>
                                        <td>↑ Capillarisation, ↑ VES repos</td>
                                        <td>↑ Enzymes lipolytiques, ↑ Mitochondries</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>Zone 2</td>
                                        <td>Aerobie mixte (70% lipides)</td>
                                        <td>0.78-0.85</td>
                                        <td>↑ VO2max, ↑ Debit cardiaque</td>
                                        <td>↑ Oxydation graisses, ↑ Glycogene musculaire</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td>Zone 3</td>
                                        <td>Aerobie glucidique dominant</td>
                                        <td>0.85-0.90</td>
                                        <td>↑ FC max, ↑ Extraction O2</td>
                                        <td>↑ Enzymes glycolytiques, Transition metabolique</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Zone 4</td>
                                        <td>Transition aero-anaerobie</td>
                                        <td>0.90-0.95</td>
                                        <td>↑ Puissance cardiaque max</td>
                                        <td>↑ Seuil lactique, ↑ Tampon lactate</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>Zone 5</td>
                                        <td>Glycolyse anaerobie (90%+)</td>
                                        <td>0.95-1.10</td>
                                        <td>Stress cardiovasculaire maximal</td>
                                        <td>↑ VO2max, ↑ Capacite anaerobie</td>
                                    </tr>
                                    <tr class="table-dark">
                                        <td>Zone 6</td>
                                        <td>Phosphocreatine + glycolyse</td>
                                        <td>Variable (&gt;1.00)</td>
                                        <td>FC max, recuperation critique</td>
                                        <td>↑ Puissance, ↑ Force neuromusculaire</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Marqueurs Physiologiques Cles</h6>
                        <ul class="small">
                            <li><strong>Seuil Aerobie (LT1) :</strong> 2 mmol/L lactate, ~Zone 2</li>
                            <li><strong>Seuil Anaerobie (LT2) :</strong> 4 mmol/L lactate, ~Zone 4</li>
                            <li><strong>Point de Compensation Respiratoire (RCP) :</strong> ~90% FCmax</li>
                            <li><strong>MLSS :</strong> Maximal Lactate Steady State</li>
                            <li><strong>Critical Power :</strong> Puissance critique soutenue</li>
                            <li><strong>VO2max :</strong> Consommation maximale O2</li>
                        </ul>
                        
                        <div class="card mt-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                <small>Validation Scientifique</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Tests d'effort avec lactatemie</li>
                                    <li>Spirometrie (VCO2/VO2)</li>
                                    <li>Seuils ventilatoires (VT1/VT2)</li>
                                    <li>Variabilite frequence cardiaque</li>
                                    <li>Biomarqueurs metaboliques</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Methodes de Calcul et Validation -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-cogs me-2"></i>
                    Methodes de Calcul et Validation - Recherches 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Methodes de Determination des Zones</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Methode</th>
                                        <th>Base Physiologique</th>
                                        <th>Precision</th>
                                        <th>Accessibilite</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td><strong>Test Lactatemie</strong></td>
                                        <td>Seuils LT1/LT2 mesures</td>
                                        <td>Gold Standard (±2%)</td>
                                        <td>Laboratoire</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>Spirometrie (VT1/VT2)</td>
                                        <td>Seuils ventilatoires</td>
                                        <td>Excellent (±3%)</td>
                                        <td>Laboratoire/Clinique</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td>Karvonen (HRR)</td>
                                        <td>Reserve cardiaque</td>
                                        <td>Bon (±5-8%)</td>
                                        <td>Large public</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>% FC maximale</td>
                                        <td>Pourcentage FC max</td>
                                        <td>Modere (±8-12%)</td>
                                        <td>Large public</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td>Talk Test</td>
                                        <td>Confort respiratoire</td>
                                        <td>Pratique (±10-15%)</td>
                                        <td>Universelle</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td>RPE Borg</td>
                                        <td>Perception effort</td>
                                        <td>Subjective (±15-20%)</td>
                                        <td>Universelle</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Limitations et Ajustements Necessaires</h6>
                        <ul class="small">
                            <li><strong>Variabilite individuelle :</strong> ±10-15 bpm même profil</li>
                            <li><strong>Derive cardiaque :</strong> +5-15 bpm apres 30min</li>
                            <li><strong>Conditions environnementales :</strong> Chaleur, altitude</li>
                            <li><strong>etat d'entraînement :</strong> Adaptation seuils</li>
                            <li><strong>Medicaments :</strong> Bêta-bloquants (-20-40 bpm)</li>
                            <li><strong>Pathologies :</strong> Cardiopathies, arythmies</li>
                        </ul>
                        
                        <div class="card mt-3 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <small>Facteurs de Confusion</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Mode d'exercice (course vs velo)</li>
                                    <li>Position corporelle (debout vs allonge)</li>
                                    <li>Temperature ambiante (+10-20 bpm)</li>
                                    <li>Hydratation (deshydratation +5-15 bpm)</li>
                                    <li>Stress psychologique</li>
                                    <li>Fatigue accumulee</li>
                                    <li>Rythme circadien</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications par Sport -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-running me-2"></i>
                    Applications Specialisees par Sport et Population
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Adaptations par Discipline Sportive</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sport</th>
                                        <th>Zones Prioritaires</th>
                                        <th>Distribution Recommandee</th>
                                        <th>Specificites</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Course Longue Distance</strong></td>
                                        <td>Zone 1-2 (80%), Zone 4-5 (20%)</td>
                                        <td>Polarisee</td>
                                        <td>economie course, VO2max</td>
                                    </tr>
                                    <tr>
                                        <td>Cyclisme Route</td>
                                        <td>Zone 2-3 (70%), Zone 4-5 (25%)</td>
                                        <td>Tempo + intensite</td>
                                        <td>Puissance seuil, FTP</td>
                                    </tr>
                                    <tr>
                                        <td>Natation</td>
                                        <td>Zone 2-3 (60%), Zone 4-6 (35%)</td>
                                        <td>Technique + vitesse</td>
                                        <td>Efficacite technique prioritaire</td>
                                    </tr>
                                    <tr>
                                        <td>Triathlon</td>
                                        <td>Zone 1-2 (70%), Zone 3-4 (25%)</td>
                                        <td>Endurance + transition</td>
                                        <td>Gestion efforts multiples</td>
                                    </tr>
                                    <tr>
                                        <td>Sports Collectifs</td>
                                        <td>Zone 3-5 (60%), Zone 6 (25%)</td>
                                        <td>Intermittent haute intensite</td>
                                        <td>Repetition sprints, recuperation</td>
                                    </tr>
                                    <tr>
                                        <td>CrossFit/HIIT</td>
                                        <td>Zone 4-6 (70%), Zone 1-2 (30%)</td>
                                        <td>Haute intensite + recuperation</td>
                                        <td>Capacite anaerobie, VO2max</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Adaptations par Population Speciale</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Population</th>
                                        <th>Ajustements Zones</th>
                                        <th>Precautions</th>
                                        <th>Monitoring Special</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Seniors (+65 ans)</td>
                                        <td>Zones 1-3 privilegiees</td>
                                        <td>Progression tres graduelle</td>
                                        <td>PA, symptômes</td>
                                    </tr>
                                    <tr>
                                        <td>Cardiaques</td>
                                        <td>Zones 1-2 exclusivement</td>
                                        <td>Supervision medicale</td>
                                        <td>ECG, dyspnee</td>
                                    </tr>
                                    <tr>
                                        <td>Diabetiques</td>
                                        <td>Zones 2-4 optimales</td>
                                        <td>Glycemie pre/post</td>
                                        <td>CGM recommande</td>
                                    </tr>
                                    <tr>
                                        <td>Grossesse</td>
                                        <td>Zones 1-3, eviter Zone 5-6</td>
                                        <td>Talk test prioritaire</td>
                                        <td>Bien-être maternel</td>
                                    </tr>
                                    <tr>
                                        <td>Enfants/Ados</td>
                                        <td>Jeu libre + Zone 3-4</td>
                                        <td>Plaisir avant performance</td>
                                        <td>Fatigue, croissance</td>
                                    </tr>
                                    <tr>
                                        <td>Obeses</td>
                                        <td>Zone 1-2 predominantes</td>
                                        <td>Articulations, surcharge</td>
                                        <td>Poids, motivation</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Periodisation et Planification -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Periodisation et Planification d'Entraînement
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Modeles de Periodisation par Zones</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Modele</th>
                                        <th>Zone 1-2</th>
                                        <th>Zone 3</th>
                                        <th>Zone 4-5</th>
                                        <th>Zone 6</th>
                                        <th>Application</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td><strong>Polarise (Seiler)</strong></td>
                                        <td>80%</td>
                                        <td>5%</td>
                                        <td>15%</td>
                                        <td>0%</td>
                                        <td>Endurance pure (marathon, cyclisme)</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>Pyramidal</td>
                                        <td>70%</td>
                                        <td>20%</td>
                                        <td>10%</td>
                                        <td>0%</td>
                                        <td>Debutants, sante generale</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Seuil (Tempo)</td>
                                        <td>50%</td>
                                        <td>35%</td>
                                        <td>15%</td>
                                        <td>0%</td>
                                        <td>Cyclisme route, triathlon courte distance</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td>HIIT Dominant</td>
                                        <td>40%</td>
                                        <td>20%</td>
                                        <td>30%</td>
                                        <td>10%</td>
                                        <td>Sports collectifs, CrossFit</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td>Blocs Conjugues</td>
                                        <td colspan="4">Variable selon bloc - Succession blocs specialises</td>
                                        <td>Athletes elite, sports multiples</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h6 class="mt-4">Phases de Developpement Saisonnier</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white text-center">
                                        <small>Phase Preparatoire (12-16 semaines)</small>
                                    </div>
                                    <div class="card-body">
                                        <ul class="small">
                                            <li><strong>Volume :</strong> ↑ progressif</li>
                                            <li><strong>Intensite :</strong> 80% Zone 1-2</li>
                                            <li><strong>Focus :</strong> Base aerobie</li>
                                            <li><strong>Adaptations :</strong> Mitochondries, capillaires</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark text-center">
                                        <small>Phase Specifique (8-12 semaines)</small>
                                    </div>
                                    <div class="card-body">
                                        <ul class="small">
                                            <li><strong>Volume :</strong> Maintien/↓</li>
                                            <li><strong>Intensite :</strong> ↑ Zone 4-5</li>
                                            <li><strong>Focus :</strong> Seuils, VO2max</li>
                                            <li><strong>Adaptations :</strong> Puissance, vitesse</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-danger">
                                    <div class="card-header bg-danger text-white text-center">
                                        <small>Phase Competitive (4-8 semaines)</small>
                                    </div>
                                    <div class="card-body">
                                        <ul class="small">
                                            <li><strong>Volume :</strong> ↓ significative</li>
                                            <li><strong>Intensite :</strong> Maintien/affûtage</li>
                                            <li><strong>Focus :</strong> Performance, recuperation</li>
                                            <li><strong>Adaptations :</strong> Optimisation</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Principes de Progression</h6>
                        <ul class="small">
                            <li><strong>Regle 10% :</strong> Augmentation volume hebdomadaire</li>
                            <li><strong>Microcycles :</strong> 3:1 (3 sem progression, 1 sem allegement)</li>
                            <li><strong>Mesocycles :</strong> 4-6 semaines thematiques</li>
                            <li><strong>Macrocycles :</strong> Planification annuelle</li>
                            <li><strong>Supercompensation :</strong> Stress + recuperation</li>
                            <li><strong>Specificite :</strong> Adaptation mode d'exercice</li>
                        </ul>
                        
                        <div class="card mt-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                <small>Monitoring Charge Interne</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>TRIMP :</strong> Training Impulse</li>
                                    <li><strong>TSS :</strong> Training Stress Score</li>
                                    <li><strong>HRV :</strong> Variabilite cardiaque</li>
                                    <li><strong>RPE session :</strong> Perception effort</li>
                                    <li><strong>Temps dans zones :</strong> Distribution</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3 border-danger">
                            <div class="card-header bg-danger text-white">
                                <small>Signaux de Surcharge</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>FC repos elevee persistante</li>
                                    <li>HRV diminuee durablement</li>
                                    <li>Performance stagnante/declin</li>
                                    <li>Fatigue chronique</li>
                                    <li>Troubles sommeil/humeur</li>
                                    <li>Immunite reduite</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technologies de Monitoring 2024 -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-microchip me-2"></i>
                    Technologies de Monitoring des Zones Cardiaques 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6>Capteurs et Dispositifs</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Dispositif</th>
                                        <th>Precision Zones</th>
                                        <th>Fonctionnalites</th>
                                        <th>Prix</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Polar H10</strong></td>
                                        <td>±1%</td>
                                        <td>HRV, zones adaptatives</td>
                                        <td>€</td>
                                    </tr>
                                    <tr>
                                        <td>Garmin HRM-Pro</td>
                                        <td>±1%</td>
                                        <td>Metriques course, natation</td>
                                        <td>€€</td>
                                    </tr>
                                    <tr>
                                        <td>Wahoo TICKR X</td>
                                        <td>±1%</td>
                                        <td>Memoire interne, ANT+</td>
                                        <td>€€</td>
                                    </tr>
                                    <tr>
                                        <td>Apple Watch Ultra 2</td>
                                        <td>±2-3%</td>
                                        <td>Zones auto, coaching IA</td>
                                        <td>€€€</td>
                                    </tr>
                                    <tr>
                                        <td>Garmin Fenix 7</td>
                                        <td>±2-3%</td>
                                        <td>Navigation, multisport</td>
                                        <td>€€€€</td>
                                    </tr>
                                    <tr>
                                        <td>Suunto 9 Peak</td>
                                        <td>±2-4%</td>
                                        <td>Ultra-endurance, meteo</td>
                                        <td>€€€</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Algorithmes d'Optimisation</h6>
                        <ul class="small">
                            <li><strong>Zones Adaptatives :</strong> Ajustement automatique selon forme</li>
                            <li><strong>Intelligence Artificielle :</strong> Apprentissage patterns personnels</li>
                            <li><strong>Fusion Multi-Capteurs :</strong> FC + puissance + allure</li>
                            <li><strong>Prediction Performance :</strong> Modeles physiologiques</li>
                            <li><strong>Coaching Temps Reel :</strong> Guidance zones optimales</li>
                            <li><strong>Recuperation Predictive :</strong> HRV + charge entraînement</li>
                        </ul>
                        
                        <div class="card mt-3 border-info">
                            <div class="card-header bg-info text-white">
                                <small>Applications Specialisees</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>TrainingPeaks :</strong> Analyse detaillee TSS</li>
                                    <li><strong>HRV4Training :</strong> Zones basees HRV</li>
                                    <li><strong>Polar Flow :</strong> Tests orthostatiques</li>
                                    <li><strong>Garmin Connect IQ :</strong> Zones lactate</li>
                                    <li><strong>Strava Segments :</strong> Analyse comparative</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Innovations emergentes 2024</h6>
                        <ul class="small">
                            <li><strong>Capteurs Non-Invasifs :</strong> Radar Doppler portable</li>
                            <li><strong>IA Predictive :</strong> Zones optimales jour J</li>
                            <li><strong>Realite Augmentee :</strong> Zones overlays visuels</li>
                            <li><strong>Biofeedback Temps Reel :</strong> Stimulation tactile</li>
                            <li><strong>Integration IoT :</strong> Environnement connecte</li>
                            <li><strong>Telemedecine :</strong> Monitoring cardiaque distant</li>
                        </ul>
                        
                        <div class="card mt-3 border-success">
                            <div class="card-header bg-success text-white">
                                <small>Validation Recherche</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Correlation lactatemie laboratoire</li>
                                    <li>Validation populations athletes</li>
                                    <li>etudes longitudinales performance</li>
                                    <li>Certification medicale ISO</li>
                                    <li>Protocoles ACSM/ESC</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <small>Limitations Technologiques</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Artefacts mouvement (±5-10%)</li>
                                    <li>Derive temperature/humidite</li>
                                    <li>Interferences electromagnetiques</li>
                                    <li>Algorithmes proprietaires non valides</li>
                                    <li>Variabilite inter-dispositifs</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strategies d'Entraînement Avancees -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-dumbbell me-2"></i>
                    Strategies d'Entraînement Avancees par Zones
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6>Entraînement Polarise</h6>
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <small>Principe 80/20 (Seiler)</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>80% Volume :</strong> Zones 1-2 (intensite faible)</li>
                                    <li><strong>20% Volume :</strong> Zones 4-5 (intensite elevee)</li>
                                    <li><strong>Zone 3 evitee :</strong> "Zone grise" non optimale</li>
                                    <li><strong>Avantages :</strong> Recuperation + adaptations qualitatives</li>
                                    <li><strong>Applications :</strong> Endurance pure, athletes elite</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <small>Micro-Dosage HIIT</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>4×4min Zone 5 (VO2max)</li>
                                    <li>8×30s Zone 6 (puissance)</li>
                                    <li>Recuperation active Zone 1</li>
                                    <li>Frequence : 2-3×/semaine max</li>
                                    <li>Progression volume avant intensite</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Entraînement Seuil</h6>
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-primary text-white">
                                <small>Developpement Seuil Lactique</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Zone 4 Continue :</strong> 20-60min soutenues</li>
                                    <li><strong>Intervalles Seuil :</strong> 5×8min rec 2min</li>
                                    <li><strong>Tempo Long :</strong> 60-90min Zone 3</li>
                                    <li><strong>Sweet Spot :</strong> 88-94% FCmax</li>
                                    <li><strong>Progression :</strong> Duree puis intensite</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <small>Methodologies Avancees</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Bi-Threshold (double seuil)</li>
                                    <li>Fartlek structure</li>
                                    <li>Pyramides ascendantes/descendantes</li>
                                    <li>Blocs alternes intensite</li>
                                    <li>Entraînement concurrent</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Recuperation et Regeneration</h6>
                        <div class="card border-secondary mb-3">
                            <div class="card-header bg-secondary text-white">
                                <small>Optimisation Zone 1</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Recuperation Active :</strong> 20-45min Zone 1</li>
                                    <li><strong>Amelioration Circulation :</strong> elimination lactate</li>
                                    <li><strong>Activation Parasympathique :</strong> HRV ↑</li>
                                    <li><strong>Frequence :</strong> Quotidien si besoin</li>
                                    <li><strong>Modalites :</strong> Marche, velo, natation douce</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <small>Strategies Recuperation</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Hydratation optimale</li>
                                    <li>Nutrition post-exercice (30min)</li>
                                    <li>Sommeil qualite (8-9h athletes)</li>
                                    <li>Techniques relaxation (meditation)</li>
                                    <li>Massage, compression, froid</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-4">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Avertissement Medical Important</h6>
                    <p class="mb-0">
                        Ces zones cardiaques sont des estimations basees sur des formules statistiques. 
                        En cas de pathologie cardiaque, prise de medicaments ou doutes sur votre condition physique, 
                        consultez un professionnel de sante avant tout programme d'entraînement intense. 
                        Un test d'effort medical reste l'etalon-or pour determiner vos zones personnelles.
                    </p>
                </div>
                
                <div class="alert alert-success mt-3">
                    <h6><i class="fas fa-chart-line me-2"></i>Vision 2024-2030</h6>
                    <p class="mb-0">
                        L'avenir de l'entraînement par zones cardiaques tend vers une personnalisation extrême 
                        integrant IA, biomarqueurs temps reel, genomique et environnement pour un coaching adaptatif 
                        optimisant la performance et la sante cardiovasculaire de chaque individu.
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

#useKnownMax:checked ~ #maxHrKnown {
    display: block !important;
}
</style>
@endpush

@push('scripts')
<script>
// Configuration des zones d'entraînement
const zoneDefinitions = {
    recovery: { 
        name: 'Zone 1 - Recuperation Active', 
        hrrMin: 50, hrrMax: 60, 
        fcMaxMin: 65, fcMaxMax: 75,
        color: 'success',
        lactateMmol: '< 2',
        metabolism: 'Lipides (>85%)',
        duration: '3-6h+',
        purpose: 'Recuperation, capillarisation'
    },
    aerobic: { 
        name: 'Zone 2 - Aerobie Base', 
        hrrMin: 60, hrrMax: 70, 
        fcMaxMin: 75, fcMaxMax: 85,
        color: 'info',
        lactateMmol: '2-3',
        metabolism: 'Lipides (70-80%)',
        duration: '2-4h',
        purpose: 'Endurance fondamentale, VO2'
    },
    tempo: { 
        name: 'Zone 3 - Tempo/Aerobie', 
        hrrMin: 70, hrrMax: 80, 
        fcMaxMin: 85, fcMaxMax: 90,
        color: 'primary',
        lactateMmol: '3-4',
        metabolism: 'Mixte (50/50)',
        duration: '1-2h',
        purpose: 'Efficacite aerobie, economie'
    },
    threshold: { 
        name: 'Zone 4 - Seuil Lactique', 
        hrrMin: 80, hrrMax: 90, 
        fcMaxMin: 90, fcMaxMax: 95,
        color: 'warning',
        lactateMmol: '4-6',
        metabolism: 'Glucides (70-80%)',
        duration: '30-60min',
        purpose: 'Seuil lactique, puissance'
    },
    vo2max: { 
        name: 'Zone 5 - VO2max', 
        hrrMin: 90, hrrMax: 95, 
        fcMaxMin: 95, fcMaxMax: 100,
        color: 'danger',
        lactateMmol: '6-12',
        metabolism: 'Glucides (>90%)',
        duration: '8-40min',
        purpose: 'VO2max, capacite anaerobie'
    },
    neuromuscular: { 
        name: 'Zone 6 - Neuromusculaire', 
        hrrMin: 95, hrrMax: 100, 
        fcMaxMin: 100, fcMaxMax: 105,
        color: 'dark',
        lactateMmol: '>12',
        metabolism: 'Phosphocreatine',
        duration: '10s-8min',
        purpose: 'Puissance, vitesse, force'
    }
};

// Formules de FC max
const maxHrFormulas = {
    asstrand: (age, gender) => 220 - age,
    tanaka: (age, gender) => 208 - (0.7 * age),
    gulati: (age, gender) => 206 - (0.88 * age), // Femmes
    nes: (age, gender) => 211 - (0.64 * age), // Athletes
    fairbarn: (age, gender) => 201 - (0.63 * age), // Seniors
    gellish: (age, gender) => 207 - (0.7 * age)
};

// Gestion de la checkbox FC max connue
document.getElementById('useKnownMax').addEventListener('change', function() {
    const maxHrInput = document.getElementById('maxHrKnown');
    if (this.checked) {
        maxHrInput.classList.remove('d-none');
    } else {
        maxHrInput.classList.add('d-none');
        maxHrInput.value = '';
    }
});

// Calcul des zones cardiaques
function calculateZones() {
    const age = parseInt(document.getElementById('age').value);
    const restingHr = parseInt(document.getElementById('restingHr').value);
    const gender = document.getElementById('gender').value;
    const formula = document.getElementById('formula').value;
    const useKnownMax = document.getElementById('useKnownMax').checked;
    const maxHrKnown = document.getElementById('maxHrKnown').value;
    const sport = document.getElementById('sport').value;
    const goal = document.getElementById('trainingGoal').value;
    
    // Validation
    if (!age || !restingHr) {
        showError('Veuillez entrer votre âge et votre frequence cardiaque de repos.');
        return;
    }
    
    if (age < 10 || age > 100) {
        showError('L\'âge doit être compris entre 10 et 100 ans.');
        return;
    }
    
    if (restingHr < 30 || restingHr > 120) {
        showError('La frequence cardiaque de repos doit être comprise entre 30 et 120 bpm.');
        return;
    }
    
    if (useKnownMax && (!maxHrKnown || maxHrKnown < 120 || maxHrKnown > 220)) {
        showError('La frequence cardiaque maximale doit être comprise entre 120 et 220 bpm.');
        return;
    }
    
    // Masquer les erreurs
    document.getElementById('errorMessage').classList.add('d-none');
    
    // Calcul FC max
    let maxHr;
    if (useKnownMax && maxHrKnown) {
        maxHr = parseInt(maxHrKnown);
    } else {
        maxHr = maxHrFormulas[formula](age, gender);
    }
    
    // Calcul HRR (Heart Rate Reserve)
    const hrr = maxHr - restingHr;
    
    // Ajustements selon sport et objectif
    let adjustedZones = { ...zoneDefinitions };
    if (sport === 'cycling' || sport === 'running') {
        adjustedZones.threshold.hrrMin = 82;
        adjustedZones.threshold.hrrMax = 88;
    }
    
    if (goal === 'fat_loss') {
        adjustedZones.aerobic.purpose += ', optimisation lipolyse';
    }
    
    // Calcul des zones avec methode Karvonen (HRR) et % FC max
    const zones = Object.keys(adjustedZones).map(key => {
        const zone = adjustedZones[key];
        return {
            key,
            ...zone,
            hrrBpmMin: Math.round((hrr * zone.hrrMin / 100) + restingHr),
            hrrBpmMax: Math.round((hrr * zone.hrrMax / 100) + restingHr),
            fcMaxBpmMin: Math.round(maxHr * zone.fcMaxMin / 100),
            fcMaxBpmMax: Math.round(maxHr * zone.fcMaxMax / 100)
        };
    });
    
    // Calcul metriques additionnelles
    const lt1 = Math.round((hrr * 0.65) + restingHr); // Premier seuil (aerobie)
    const lt2 = Math.round((hrr * 0.85) + restingHr); // Deuxieme seuil (anaerobie)
    
    // Estimation condition physique basee sur FC repos
    const fitnessAssessment = getFitnessLevel(restingHr, gender);
    
    // Affichage des resultats
    displayResults({
        maxHr: Math.round(maxHr),
        hrr: Math.round(hrr),
        zones,
        lt1,
        lt2,
        formula,
        restingHr,
        fitnessLevel: fitnessAssessment.level,
        fitnessColor: fitnessAssessment.color
    });
}

// evaluation du niveau de forme
function getFitnessLevel(restingHr, gender) {
    let level = '';
    let color = '';
    
    if (gender === 'male') {
        if (restingHr < 50) { level = 'Athlete excellent'; color = 'success'; }
        else if (restingHr < 60) { level = 'Excellent'; color = 'success'; }
        else if (restingHr < 70) { level = 'Bon'; color = 'info'; }
        else if (restingHr < 80) { level = 'Moyen'; color = 'warning'; }
        else { level = 'Faible'; color = 'danger'; }
    } else {
        if (restingHr < 55) { level = 'Athlete excellent'; color = 'success'; }
        else if (restingHr < 65) { level = 'Excellent'; color = 'success'; }
        else if (restingHr < 75) { level = 'Bon'; color = 'info'; }
        else if (restingHr < 85) { level = 'Moyen'; color = 'warning'; }
        else { level = 'Faible'; color = 'danger'; }
    }
    
    return { level, color };
}

// Affichage des erreurs
function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i><strong>Erreur :</strong> ${message}`;
    errorDiv.classList.remove('d-none');
    document.getElementById('resultsSection').classList.add('d-none');
}

// Affichage des resultats
function displayResults(results) {
    // Metriques principales
    document.getElementById('maxHrResult').textContent = results.maxHr;
    document.getElementById('hrrResult').textContent = results.hrr;
    document.getElementById('formulaUsed').textContent = `Formule ${results.formula}`;
    document.getElementById('restingHrDisplay').textContent = `FC repos ${results.restingHr} bpm`;
    document.getElementById('fitnessLevel').textContent = results.fitnessLevel;
    document.getElementById('thresholds').textContent = `${results.lt1} / ${results.lt2}`;
    
    // Mise A jour couleur condition physique
    const fitnessCard = document.getElementById('fitnessCard');
    fitnessCard.className = `card border-${results.fitnessColor} h-100`;
    fitnessCard.querySelector('.card-header').className = `card-header bg-${results.fitnessColor} ${results.fitnessColor === 'warning' ? 'text-dark' : 'text-white'} text-center`;
    document.getElementById('fitnessLevel').className = `text-${results.fitnessColor}`;
    
    // Zones detaillees
    const zonesContainer = document.getElementById('zonesContainer');
    zonesContainer.innerHTML = '';
    
    results.zones.forEach((zone, index) => {
        const zoneCard = document.createElement('div');
        zoneCard.className = 'col-md-6';
        
        zoneCard.innerHTML = `
            <div class="card border-${zone.color} h-100">
                <div class="card-header bg-${zone.color} ${zone.color === 'warning' ? 'text-dark' : 'text-white'}">
                    <h6 class="mb-1">${zone.name}</h6>
                    <div class="row g-1 text-sm">
                        <div class="col-6">
                            <small>HRR: ${zone.hrrMin}-${zone.hrrMax}%</small>
                        </div>
                        <div class="col-6">
                            <small>FCmax: ${zone.fcMaxMin}-${zone.fcMaxMax}%</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <h5 class="text-${zone.color} text-center">
                                ${zone.hrrBpmMin}-${zone.hrrBpmMax} bpm
                            </h5>
                        </div>
                        <div class="col-6">
                            <small><strong>Lactate:</strong> ${zone.lactateMmol} mmol/L</small>
                        </div>
                        <div class="col-6">
                            <small><strong>Duree:</strong> ${zone.duration}</small>
                        </div>
                        <div class="col-12">
                            <small><strong>Metabolisme:</strong> ${zone.metabolism}</small>
                        </div>
                        <div class="col-12">
                            <small><strong>Objectif:</strong> ${zone.purpose}</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        zonesContainer.appendChild(zoneCard);
    });
    
    // Afficher la section resultats
    document.getElementById('resultsSection').classList.remove('d-none');
    document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Reinitialisation du calculateur
function resetCalculator() {
    document.getElementById('age').value = '';
    document.getElementById('restingHr').value = '';
    document.getElementById('gender').value = 'male';
    document.getElementById('formula').value = 'tanaka';
    document.getElementById('useKnownMax').checked = false;
    document.getElementById('maxHrKnown').value = '';
    document.getElementById('maxHrKnown').classList.add('d-none');
    document.getElementById('fitnessLevel').value = 'average';
    document.getElementById('sport').value = 'general';
    document.getElementById('trainingGoal').value = 'general';
    document.getElementById('errorMessage').classList.add('d-none');
    document.getElementById('resultsSection').classList.add('d-none');
}
</script>
@endpush