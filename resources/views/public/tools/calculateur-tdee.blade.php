@extends('layouts.public')

@section('title', 'Calculateur TDEE Avance & Metabolisme - Hassan EL HAOUAT')
@section('meta_description', 'Calculateur TDEE scientifique avec personnalisation avancee. Multiples formules BMR, ajustements genetiques, hormonaux, environnementaux. Objectifs nutritionnels optimises. Evidence-based 2024.')

@section('content')

<!-- Section titre -->
<section class="py-5 text-center nataswim-titre3">

<div class="container-lg">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-2 mb-lg-0" style=" background-color: #f9f5f4; color: #63d0c7; padding: 20px 0px; border-radius: 10px; ">
                <h1 class="display-5 fw-bold mb-3">Calculateur TDEE  & Metabolisme</h1>
                <p>Multiples formules BMR, ajustements genetiques, hormonaux, environnementaux.</p>
            </div>
            <div class="col-lg-5 text-center">
                <a href="{{ route('posts.public.index') }}">
                    <img src="{{ asset('assets/images/team/nataswim-sport-net-systemes-4.jpg') }}"
                        alt="Guide Nataswim"
                        class="img-fluid rounded-4"
                        style="max-height: 300px; object-fit: cover;">
                </a>

            </div>
        </div>
    </div>
</section>



<!-- Calculateur Principal -->
<section class="py-5 bg-light">
    <div class="container">
        
        <!-- Calculateur TDEE Multi-Parametres -->
        <div class="card mb-4 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-calculator me-2"></i>
                    Calculateur TDEE Multi-Parametres
                </h3>
            </div>
            <div class="card-body">
                
                <!-- Messages d'erreur -->
                <div id="errorMessage" class="alert alert-danger d-none">
                    <!-- Sera rempli par JavaScript -->
                </div>

                <!-- Donnees de base -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="age" class="form-label fw-bold">Âge (annees)</label>
                        <input type="number" id="age" class="form-control form-control-lg border-primary" 
                               placeholder="30" min="10" max="100">
                    </div>
                    <div class="col-md-3">
                        <label for="weight" class="form-label fw-bold">Poids (kg)</label>
                        <input type="number" id="weight" class="form-control form-control-lg border-primary" 
                               placeholder="70" min="30" max="200" step="0.1">
                    </div>
                    <div class="col-md-3">
                        <label for="height" class="form-label fw-bold">Taille (cm)</label>
                        <input type="number" id="height" class="form-control form-control-lg border-primary" 
                               placeholder="175" min="120" max="220">
                    </div>
                    <div class="col-md-3">
                        <label for="gender" class="form-label fw-bold">Sexe</label>
                        <select id="gender" class="form-select form-select-lg border-primary">
                            <option value="male">Homme</option>
                            <option value="female">Femme</option>
                        </select>
                    </div>
                </div>

                <!-- Composition Corporelle (Optionnel) -->
                <h5 class="fw-bold mb-3">Composition Corporelle (Optionnel)</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="bodyFat" class="form-label">% Masse grasse</label>
                        <input type="number" id="bodyFat" class="form-control border-warning" 
                               placeholder="15" min="3" max="50" step="0.1">
                        <small class="text-muted">Pour formule Katch-McArdle</small>
                    </div>
                    <div class="col-md-6">
                        <label for="muscleMass" class="form-label">Masse musculaire (kg)</label>
                        <input type="number" id="muscleMass" class="form-control border-warning" 
                               placeholder="55" min="20" max="100" step="0.1">
                        <small class="text-muted">Pour formule Cunningham</small>
                    </div>
                </div>

                <!-- Activite et Lifestyle -->
                <h5 class="fw-bold mb-3">Activite et Lifestyle</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="activityLevel" class="form-label">Niveau d'activite physique</label>
                        <select id="activityLevel" class="form-select border-success">
                            <option value="sedentary">Sedentaire (bureau, pas d'exercice)</option>
                            <option value="light">Leger (exercice 1-3j/sem)</option>
                            <option value="moderate" selected>Modere (exercice 3-5j/sem)</option>
                            <option value="active">Actif (exercice 6-7j/sem)</option>
                            <option value="very_active">Tres actif (2x/jour ou travail physique)</option>
                            <option value="extreme">Extrême (athlete professionnel)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="occupation" class="form-label">Type d'occupation</label>
                        <select id="occupation" class="form-select border-info">
                            <option value="sedentary" selected>Bureau/Sedentaire</option>
                            <option value="standing">Debout (vente, enseignement)</option>
                            <option value="walking">Marche (infirmier, serveur)</option>
                            <option value="physical">Physique (construction, sport)</option>
                        </select>
                    </div>
                </div>

                <!-- Parametres Metaboliques Avances -->
                <h5 class="fw-bold mb-3">Parametres Metaboliques Avances</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="bmrFormula" class="form-label">Formule BMR</label>
                        <select id="bmrFormula" class="form-select border-danger">
                            <option value="mifflin" selected>Mifflin-St Jeor (Recommandee)</option>
                            <option value="harris">Harris-Benedict Revisee</option>
                            <option value="katch">Katch-McArdle (% graisse requis)</option>
                            <option value="cunningham">Cunningham (masse musculaire)</option>
                            <option value="owen">Owen (validation recente)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="healthCondition" class="form-label">etat de sante</label>
                        <select id="healthCondition" class="form-select border-secondary">
                            <option value="healthy" selected>Bonne sante</option>
                            <option value="hyperthyroid">Hyperthyroïdie</option>
                            <option value="hypothyroid">Hypothyroïdie</option>
                            <option value="diabetes">Diabete</option>
                            <option value="pcos">SOPK</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="geneticFactor" class="form-label">Facteur genetique</label>
                        <select id="geneticFactor" class="form-select border-warning">
                            <option value="slow">Metabolisme lent</option>
                            <option value="average" selected>Moyen</option>
                            <option value="fast">Metabolisme rapide</option>
                        </select>
                    </div>
                </div>

                <!-- Facteurs Environnementaux -->
                <h5 class="fw-bold mb-3">Facteurs Environnementaux</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="sleepHours" class="form-label">Heures de sommeil/nuit</label>
                        <input type="number" id="sleepHours" class="form-control border-info" 
                               placeholder="8" min="4" max="12" step="0.5">
                    </div>
                    <div class="col-md-4">
                        <label for="stressLevel" class="form-label">Niveau de stress</label>
                        <select id="stressLevel" class="form-select border-warning">
                            <option value="low">Faible</option>
                            <option value="moderate" selected>Modere</option>
                            <option value="high">eleve</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="climateTemp" class="form-label">Temperature moyenne (°C)</label>
                        <input type="number" id="climateTemp" class="form-control border-secondary" 
                               placeholder="20" min="-20" max="50">
                    </div>
                </div>

                <!-- Impact Medicamenteux -->
                <h5 class="fw-bold mb-3">Impact Medicamenteux</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label for="medicationImpact" class="form-label">Medicaments affectant le metabolisme</label>
                        <select id="medicationImpact" class="form-select border-danger">
                            <option value="none" selected>Aucun impact significatif</option>
                            <option value="stimulants">Stimulants (cafeine, ADHD)</option>
                            <option value="beta_blockers">Bêta-bloquants</option>
                            <option value="antidepressants">Antidepresseurs</option>
                            <option value="corticosteroids">Corticosteroïdes</option>
                            <option value="thyroid_meds">Medicaments thyroïdiens</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg fw-bold w-100" onclick="calculateTDEE()">
                            <i class="fas fa-calculator me-2"></i>Calculer TDEE Personnalise
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
            <!-- Profil Metabolique -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-user-chart me-2"></i>
                        Votre Profil Metabolique Personnalise
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-danger h-100">
                                <div class="card-header bg-danger text-white text-center">
                                    <h6 class="mb-0">BMR</h6>
                                    <small>Metabolisme de Base</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-danger" id="bmrResult">0</strong></span>
                                        <small class="d-block">kcal/jour</small>
                                    </p>
                                    <small class="text-muted" id="formulaUsed">Formule</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white text-center">
                                    <h6 class="mb-0">TDEE Standard</h6>
                                    <small>Avant ajustements</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-primary" id="standardTDEE">0</strong></span>
                                        <small class="d-block">kcal/jour</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white text-center">
                                    <h6 class="mb-0">TDEE Ajuste</h6>
                                    <small>Personnalise</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-success" id="adjustedTDEE">0</strong></span>
                                        <small class="d-block">kcal/jour</small>
                                    </p>
                                    <small class="text-success" id="adjustmentFactor">Facteur: 100%</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-info h-100">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">Âge Metabolique</h6>
                                    <small>Estimation</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-info" id="metabolicAge">0</strong></span>
                                        <small class="d-block">ans</small>
                                    </p>
                                    <small class="text-muted" id="tdeePerKg">0 kcal/kg</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ajustements appliques -->
                    <div id="adjustmentDetails" class="alert alert-info d-none">
                        <h6>Ajustements Appliques</h6>
                        <ul id="adjustmentsList" class="mb-0">
                            <!-- Sera rempli par JavaScript -->
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Repartition de la Depense energetique -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-2">
                        <i class="fas fa-chart-pie me-2"></i>
                        Repartition de la Depense energetique
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white text-center">
                                    <h6 class="mb-0">BMR/RMR</h6>
                                    <small>60-75% TDEE</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong class="text-danger" id="bmrBreakdown">0</strong></span>
                                        <small class="d-block">kcal</small>
                                    </p>
                                    <small class="text-muted">Fonctions vitales</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark text-center">
                                    <h6 class="mb-0">TEF</h6>
                                    <small>8-12% TDEE</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong class="text-warning" id="tefBreakdown">0</strong></span>
                                        <small class="d-block">kcal</small>
                                    </p>
                                    <small class="text-muted">Digestion</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">NEAT</h6>
                                    <small>15-30% TDEE</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong class="text-info" id="neatBreakdown">0</strong></span>
                                        <small class="d-block">kcal</small>
                                    </p>
                                    <small class="text-muted">Activite spontanee</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white text-center">
                                    <h6 class="mb-0">EAT</h6>
                                    <small>5-35% TDEE</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong class="text-success" id="eatBreakdown">0</strong></span>
                                        <small class="d-block">kcal</small>
                                    </p>
                                    <small class="text-muted">Exercice planifie</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barre de progression -->
                    <div class="progress" style="height: 30px;">
                        <div id="bmrProgress" class="progress-bar bg-danger" style="width: 0%">BMR</div>
                        <div id="tefProgress" class="progress-bar bg-warning" style="width: 0%">TEF</div>
                        <div id="neatProgress" class="progress-bar bg-info" style="width: 0%">NEAT</div>
                        <div id="eatProgress" class="progress-bar bg-success" style="width: 0%">EAT</div>
                    </div>
                </div>
            </div>

            <!-- Objectifs Caloriques et Macronutriments -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-secondary text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-bullseye me-2"></i>
                        Objectifs Caloriques et Macronutriments
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3" id="calorieGoals">
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
        
        <!-- Formules BMR et Validation -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-formula me-2"></i>
                    Formules BMR et Validation Scientifique 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Comparaison des Formules BMR</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Formule</th>
                                        <th>Annee</th>
                                        <th>Population d'etude</th>
                                        <th>Precision (±%)</th>
                                        <th>Recommandation 2024</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td><strong>Mifflin-St Jeor</strong></td>
                                        <td>1990</td>
                                        <td>Adultes normaux (n=498)</td>
                                        <td>±5-10%</td>
                                        <td>Gold Standard actuel</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>Harris-Benedict Revisee</td>
                                        <td>1984</td>
                                        <td>Large cohorte mixte</td>
                                        <td>±10-15%</td>
                                        <td>Alternative acceptable</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td>Katch-McArdle</td>
                                        <td>1996</td>
                                        <td>Basee sur masse maigre</td>
                                        <td>±3-8%</td>
                                        <td>Optimale si % graisse connu</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Cunningham</td>
                                        <td>1991</td>
                                        <td>Athletes et bodybuilders</td>
                                        <td>±5-12%</td>
                                        <td>Athletes muscles</td>
                                    </tr>
                                    <tr>
                                        <td>Owen</td>
                                        <td>1986</td>
                                        <td>Validation recente large</td>
                                        <td>±8-15%</td>
                                        <td>Populations obeses</td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td>Harris-Benedict Originale</td>
                                        <td>1919</td>
                                        <td>Caucasiens 1919</td>
                                        <td>±15-25%</td>
                                        <td>Obsolete</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Facteurs d'Erreur des Formules</h6>
                        <ul class="small">
                            <li><strong>Composition corporelle :</strong> Muscle vs graisse (±20%)</li>
                            <li><strong>Ethnicite :</strong> Differences metaboliques significatives</li>
                            <li><strong>Âge :</strong> Declin BMR -1-2%/decennie</li>
                            <li><strong>Pathologies :</strong> Thyroïde, diabete, SOPK</li>
                            <li><strong>Medicaments :</strong> Impact hormonal/neural</li>
                            <li><strong>Adaptation metabolique :</strong> Restriction calorique</li>
                        </ul>
                        
                        <div class="card mt-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                <small>Technologies Precises 2024</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Calorimetrie indirecte :</strong> Gold standard (±2%)</li>
                                    <li><strong>Doubly Labeled Water :</strong> TDEE reel (±3%)</li>
                                    <li><strong>Room Calorimeter :</strong> Contrôle total (±1%)</li>
                                    <li><strong>Metabolic Cart :</strong> Accessible (±5%)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Composantes TDEE Detaillees -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-puzzle-piece me-2"></i>
                    Composantes TDEE - Recherches Recentes
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>BMR/RMR - Metabolisme de Base (60-75% TDEE)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Organe/Tissu</th>
                                        <th>% BMR</th>
                                        <th>kcal/kg/jour</th>
                                        <th>Variabilite</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Foie</strong></td>
                                        <td>21%</td>
                                        <td>200</td>
                                        <td>±15%</td>
                                    </tr>
                                    <tr>
                                        <td>Cerveau</td>
                                        <td>20%</td>
                                        <td>240</td>
                                        <td>±8%</td>
                                    </tr>
                                    <tr>
                                        <td>Muscle squelettique</td>
                                        <td>22%</td>
                                        <td>13</td>
                                        <td>±25%</td>
                                    </tr>
                                    <tr>
                                        <td>Reins</td>
                                        <td>8%</td>
                                        <td>440</td>
                                        <td>±20%</td>
                                    </tr>
                                    <tr>
                                        <td>Cœur</td>
                                        <td>9%</td>
                                        <td>440</td>
                                        <td>±12%</td>
                                    </tr>
                                    <tr>
                                        <td>Tissu adipeux</td>
                                        <td>4%</td>
                                        <td>4.5</td>
                                        <td>±30%</td>
                                    </tr>
                                    <tr>
                                        <td>Autres</td>
                                        <td>16%</td>
                                        <td>Variable</td>
                                        <td>±20%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>NEAT - Thermogenese Activite Non-Exercice (15-30%)</h6>
                        <ul class="small">
                            <li><strong>Definition :</strong> Toute activite non-exercice conscient</li>
                            <li><strong>Composantes :</strong> Fidgeting, posture, gestes quotidiens</li>
                            <li><strong>Variabilite :</strong> 100-800 kcal/jour entre individus</li>
                            <li><strong>Genetique :</strong> 40-60% heritabilite</li>
                            <li><strong>Facteurs :</strong> Environnement, stress, saison</li>
                            <li><strong>Adaptation :</strong> ↓20-40% en restriction calorique</li>
                        </ul>
                        
                        <h6 class="mt-3">TEF - Effet Thermique Alimentaire (8-12%)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Macronutriment</th>
                                        <th>TEF (%)</th>
                                        <th>Duree</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Proteines</strong></td>
                                        <td>20-30%</td>
                                        <td>3-6h</td>
                                    </tr>
                                    <tr>
                                        <td>Glucides</td>
                                        <td>5-10%</td>
                                        <td>1-3h</td>
                                    </tr>
                                    <tr>
                                        <td>Lipides</td>
                                        <td>0-3%</td>
                                        <td>1-6h</td>
                                    </tr>
                                    <tr>
                                        <td>Alcool</td>
                                        <td>15-20%</td>
                                        <td>1-4h</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facteurs d'Influence Metabolique -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-dna me-2"></i>
                    Facteurs d'Influence sur le Metabolisme
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6>Facteurs Genetiques et epigenetiques</h6>
                        <ul class="small">
                            <li><strong>Genes UCP :</strong> Thermogenese mitochondriale</li>
                            <li><strong>FTO :</strong> Regulation appetit (+9% BMR variants)</li>
                            <li><strong>MC4R :</strong> Contrôle satiete centrale</li>
                            <li><strong>ADRB3 :</strong> Lipolyse et thermogenese</li>
                            <li><strong>Heritabilite BMR :</strong> 40-80% selon etudes</li>
                            <li><strong>epigenetique :</strong> Modulation lifestyle</li>
                        </ul>
                        
                        <div class="card mt-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                <small>Variants Metaboliques</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Metabolisme rapide :</strong> +10-15% population</li>
                                    <li><strong>Metabolisme lent :</strong> -10-20% population</li>
                                    <li><strong>Resistants obesite :</strong> 5% population</li>
                                    <li><strong>Susceptibles obesite :</strong> 15% population</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Facteurs Hormonaux</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Hormone</th>
                                        <th>Impact BMR</th>
                                        <th>Condition Pathologique</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>T3/T4 (Thyroïde)</strong></td>
                                        <td>±30-50%</td>
                                        <td>Hyper/Hypothyroïdie</td>
                                    </tr>
                                    <tr>
                                        <td>Insuline</td>
                                        <td>±10-20%</td>
                                        <td>Diabete, Resistance</td>
                                    </tr>
                                    <tr>
                                        <td>Cortisol</td>
                                        <td>±5-15%</td>
                                        <td>Cushing, Addison</td>
                                    </tr>
                                    <tr>
                                        <td>Leptine</td>
                                        <td>±5-25%</td>
                                        <td>Resistance leptine</td>
                                    </tr>
                                    <tr>
                                        <td>GH</td>
                                        <td>±10-20%</td>
                                        <td>Deficit/Exces GH</td>
                                    </tr>
                                    <tr>
                                        <td>Testosterone</td>
                                        <td>±8-15%</td>
                                        <td>Hypogonadisme</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Facteurs Environnementaux</h6>
                        <ul class="small">
                            <li><strong>Temperature :</strong> Froid ↑5-15%, Chaleur ↑3-8%</li>
                            <li><strong>Altitude :</strong> >2500m ↑10-25% BMR</li>
                            <li><strong>Sommeil :</strong> <6h ↓2-8% BMR</li>
                            <li><strong>Stress chronique :</strong> ↑5-15% cortisol</li>
                            <li><strong>Pollution :</strong> Perturbateurs endocriniens</li>
                            <li><strong>Microbiome :</strong> ±5-20% extraction energetique</li>
                        </ul>
                        
                        <div class="card mt-3 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <small>Adaptations Metaboliques</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Restriction calorique :</strong> ↓15-40% TDEE</li>
                                    <li><strong>Suralimentation :</strong> ↑8-20% TDEE</li>
                                    <li><strong>Effet yo-yo :</strong> Spirale metabolique</li>
                                    <li><strong>Set-point :</strong> Defense ponderale</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strategies d'Optimisation Metabolique -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-rocket me-2"></i>
                    Strategies d'Optimisation Metabolique
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6>Optimisation Nutritionnelle</h6>
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-primary text-white">
                                <small>Boost TEF et BMR</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Proteines elevees :</strong> 1.6-2.2g/kg (↑TEF 20-30%)</li>
                                    <li><strong>Repas frequents :</strong> 4-6 repas/jour</li>
                                    <li><strong>Cafeine :</strong> 100-400mg (↑BMR 3-11%)</li>
                                    <li><strong>The vert :</strong> EGCG + cafeine</li>
                                    <li><strong>epices :</strong> Capsaïcine, gingembre</li>
                                    <li><strong>Eau froide :</strong> 2L (↑BMR 50 kcal)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <small>eviter Adaptations Negatives</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>eviter deficits extrêmes (>30%)</li>
                                    <li>Refeeds periodiques (leptine)</li>
                                    <li>Diet breaks (2-4 semaines)</li>
                                    <li>Cyclage calorique</li>
                                    <li>Monitoring temperature corporelle</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Optimisation par l'Exercice</h6>
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <small>Maximiser EPOC et NEAT</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>HIIT :</strong> ↑EPOC 12-48h (+50-200 kcal)</li>
                                    <li><strong>Musculation :</strong> ↑BMR par masse musculaire</li>
                                    <li><strong>Cardio LISS :</strong> Optimisation lipolyse</li>
                                    <li><strong>NEAT conscient :</strong> Marche, fidgeting</li>
                                    <li><strong>Exposition froid :</strong> Activation BAT</li>
                                    <li><strong>Recuperation active :</strong> Maintien NEAT</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <small>Gains Masse Musculaire</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>+1kg muscle = +13 kcal/jour BMR</li>
                                    <li>Progression charge progressive</li>
                                    <li>Volume optimal: 10-20 sets/muscle/semaine</li>
                                    <li>Frequence: 2-3x/semaine/muscle</li>
                                    <li>Recuperation: 48-72h</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Optimisation Lifestyle</h6>
                        <div class="card border-secondary mb-3">
                            <div class="card-header bg-secondary text-white">
                                <small>Facteurs Circadiens</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Sommeil :</strong> 7-9h qualite (↑leptine)</li>
                                    <li><strong>Lumiere :</strong> Exposition matinale</li>
                                    <li><strong>Horaires repas :</strong> Regularite</li>
                                    <li><strong>Jeûne intermittent :</strong> Flexibilite metabolique</li>
                                    <li><strong>Stress management :</strong> Meditation, yoga</li>
                                    <li><strong>Sauna/Froid :</strong> Hormesis thermique</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <small>Surveillance et Ajustements</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Monitoring poids quotidien</li>
                                    <li>Photos progression mensuelle</li>
                                    <li>Mesures circonferences</li>
                                    <li>Biomarqueurs sanguins (TSH, T3)</li>
                                    <li>Performance exercice</li>
                                    <li>Humeur et energie subjective</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-4">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Avertissement Medical Important</h6>
                    <p class="mb-0">
                        Les calculs TDEE sont des estimations basees sur des equations statistiques. 
                        En cas de pathologie metabolique, troubles alimentaires ou doutes sur votre condition, 
                        consultez un professionnel de sante. evitez les deficits caloriques extrêmes (&gt;30%) 
                        sans supervision medicale.
                    </p>
                </div>
                
                <div class="alert alert-success mt-3">
                    <h6><i class="fas fa-chart-line me-2"></i>Vision 2024-2030</h6>
                    <p class="mb-0">
                        L'avenir du metabolisme tend vers une personnalisation extrême integrant genomique, 
                        epigenetique, microbiome, biomarqueurs temps reel et IA pour optimiser individuellement 
                        la depense energetique et la composition corporelle.
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

.progress-bar {
    font-size: 0.8rem;
    font-weight: bold;
}
</style>
@endpush

@push('scripts')
<script>
// Configuration des facteurs d'activite
const activityFactors = {
    sedentary: { factor: 1.2, neat: 200, eat: 0, description: 'Sedentaire (bureau, pas d\'exercice)' },
    light: { factor: 1.375, neat: 300, eat: 150, description: 'Leger (exercice 1-3j/sem)' },
    moderate: { factor: 1.55, neat: 400, eat: 300, description: 'Modere (exercice 3-5j/sem)' },
    active: { factor: 1.725, neat: 500, eat: 450, description: 'Actif (exercice 6-7j/sem)' },
    very_active: { factor: 1.9, neat: 600, eat: 600, description: 'Tres actif (2x/jour ou travail physique)' },
    extreme: { factor: 2.2, neat: 700, eat: 800, description: 'Extrême (athlete professionnel)' }
};

// Formules BMR
const bmrFormulas = {
    mifflin: {
        male: (weight, height, age) => 10 * weight + 6.25 * height - 5 * age + 5,
        female: (weight, height, age) => 10 * weight + 6.25 * height - 5 * age - 161
    },
    harris: {
        male: (weight, height, age) => 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age),
        female: (weight, height, age) => 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age)
    },
    katch: (weight, bodyFat) => {
        if (!bodyFat || bodyFat <= 0) return null;
        const leanMass = weight * (1 - bodyFat / 100);
        return 370 + (21.6 * leanMass);
    },
    cunningham: (muscleMass) => {
        if (!muscleMass || muscleMass <= 0) return null;
        return 500 + (22 * muscleMass);
    },
    owen: {
        male: (weight) => 879 + (10.2 * weight),
        female: (weight) => 795 + (7.18 * weight)
    }
};

// Calcul du BMR selon la formule choisie
function calculateBMR(weight, height, age, gender, formula, bodyFat, muscleMass) {
    const w = parseFloat(weight);
    const h = parseFloat(height);
    const a = parseInt(age);
    const bf = parseFloat(bodyFat);
    const mm = parseFloat(muscleMass);

    switch (formula) {
        case 'mifflin':
            return bmrFormulas.mifflin[gender](w, h, a);
        case 'harris':
            return bmrFormulas.harris[gender](w, h, a);
        case 'katch':
            return bmrFormulas.katch(w, bf);
        case 'cunningham':
            return bmrFormulas.cunningham(mm);
        case 'owen':
            return bmrFormulas.owen[gender](w);
        default:
            return bmrFormulas.mifflin[gender](w, h, a);
    }
}

// Calcul du TDEE
function calculateTDEE() {
    // Recuperation des valeurs
    const age = parseInt(document.getElementById('age').value);
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    const gender = document.getElementById('gender').value;
    const activityLevel = document.getElementById('activityLevel').value;
    const bodyFat = parseFloat(document.getElementById('bodyFat').value);
    const muscleMass = parseFloat(document.getElementById('muscleMass').value);
    const bmrFormula = document.getElementById('bmrFormula').value;
    const healthCondition = document.getElementById('healthCondition').value;
    const geneticFactor = document.getElementById('geneticFactor').value;
    const sleepHours = parseFloat(document.getElementById('sleepHours').value);
    const stressLevel = document.getElementById('stressLevel').value;
    const climateTemp = parseFloat(document.getElementById('climateTemp').value);
    const medicationImpact = document.getElementById('medicationImpact').value;
    
    // Validation
    if (!age || !weight || !height) {
        showError('Veuillez entrer votre âge, poids et taille.');
        return;
    }
    
    if (age < 10 || age > 100) {
        showError('L\'âge doit être compris entre 10 et 100 ans.');
        return;
    }
    
    if (weight < 30 || weight > 200) {
        showError('Le poids doit être compris entre 30 et 200 kg.');
        return;
    }
    
    if (height < 120 || height > 220) {
        showError('La taille doit être comprise entre 120 et 220 cm.');
        return;
    }
    
    // Masquer les erreurs
    document.getElementById('errorMessage').classList.add('d-none');
    
    // Calcul BMR
    let bmr = calculateBMR(weight, height, age, gender, bmrFormula, bodyFat, muscleMass);
    
    if (!bmr || bmr <= 0) {
        // Fallback vers Mifflin si formule specialisee impossible
        bmr = calculateBMR(weight, height, age, gender, 'mifflin');
    }
    
    // Facteurs d'activite detailles
    const activityData = activityFactors[activityLevel];
    
    // Calcul TDEE standard
    const standardTDEE = bmr * activityData.factor;
    
    // Breakdown detaille des composantes TDEE
    const tef = standardTDEE * 0.10; // Thermic Effect of Food (8-12%)
    const neat = activityData.neat; // Non-Exercise Activity Thermogenesis
    const eat = activityData.eat; // Exercise Activity Thermogenesis
    
    // Ajustements selon conditions personnelles
    let adjustmentFactor = 1.0;
    let adjustmentDetails = [];
    
    // Ajustement condition de sante
    switch (healthCondition) {
        case 'hyperthyroid':
            adjustmentFactor *= 1.15;
            adjustmentDetails.push('Hyperthyroïdie: +15%');
            break;
        case 'hypothyroid':
            adjustmentFactor *= 0.85;
            adjustmentDetails.push('Hypothyroïdie: -15%');
            break;
        case 'diabetes':
            adjustmentFactor *= 0.95;
            adjustmentDetails.push('Diabete: -5%');
            break;
        case 'pcos':
            adjustmentFactor *= 0.90;
            adjustmentDetails.push('SOPK: -10%');
            break;
    }
    
    // Ajustement genetique
    switch (geneticFactor) {
        case 'fast':
            adjustmentFactor *= 1.10;
            adjustmentDetails.push('Metabolisme rapide: +10%');
            break;
        case 'slow':
            adjustmentFactor *= 0.90;
            adjustmentDetails.push('Metabolisme lent: -10%');
            break;
    }
    
    // Ajustement medicaments
    switch (medicationImpact) {
        case 'stimulants':
            adjustmentFactor *= 1.05;
            adjustmentDetails.push('Stimulants: +5%');
            break;
        case 'beta_blockers':
            adjustmentFactor *= 0.95;
            adjustmentDetails.push('Bêta-bloquants: -5%');
            break;
        case 'antidepressants':
            adjustmentFactor *= 0.93;
            adjustmentDetails.push('Antidepresseurs: -7%');
            break;
        case 'corticosteroids':
            adjustmentFactor *= 1.08;
            adjustmentDetails.push('Corticosteroïdes: +8%');
            break;
        case 'thyroid_meds':
            adjustmentFactor *= 1.12;
            adjustmentDetails.push('Medicaments thyroïdiens: +12%');
            break;
    }
    
    // Ajustement stress
    if (stressLevel === 'high') {
        adjustmentFactor *= 1.03;
        adjustmentDetails.push('Stress eleve: +3%');
    } else if (stressLevel === 'low') {
        adjustmentFactor *= 0.98;
        adjustmentDetails.push('Stress faible: -2%');
    }
    
    // Ajustement sommeil
    if (!isNaN(sleepHours)) {
        if (sleepHours < 6) {
            adjustmentFactor *= 0.97;
            adjustmentDetails.push('Sommeil insuffisant: -3%');
        } else if (sleepHours > 9) {
            adjustmentFactor *= 1.02;
            adjustmentDetails.push('Sommeil prolonge: +2%');
        }
    }
    
    // Ajustement temperature
    if (!isNaN(climateTemp)) {
        if (climateTemp < 10) {
            adjustmentFactor *= 1.05;
            adjustmentDetails.push('Froid: +5%');
        } else if (climateTemp > 30) {
            adjustmentFactor *= 1.03;
            adjustmentDetails.push('Chaleur: +3%');
        }
    }
    
    // TDEE final ajuste
    const adjustedTDEE = standardTDEE * adjustmentFactor;
    
    // Estimations pour objectifs
    const maintenanceCalories = Math.round(adjustedTDEE);
    const cuttingCalories = Math.round(adjustedTDEE * 0.8); // -20%
    const bulkingCalories = Math.round(adjustedTDEE * 1.15); // +15%
    const extremeCutCalories = Math.round(adjustedTDEE * 0.7); // -30%
    
    // Calcul composition macronutriments selon objectif
    const macros = {
        maintenance: {
            protein: Math.round((maintenanceCalories * 0.25) / 4),
            fat: Math.round((maintenanceCalories * 0.25) / 9),
            carb: Math.round((maintenanceCalories * 0.5) / 4)
        },
        cutting: {
            protein: Math.round((cuttingCalories * 0.35) / 4),
            fat: Math.round((cuttingCalories * 0.25) / 9),
            carb: Math.round((cuttingCalories * 0.4) / 4)
        },
        bulking: {
            protein: Math.round((bulkingCalories * 0.25) / 4),
            fat: Math.round((bulkingCalories * 0.30) / 9),
            carb: Math.round((bulkingCalories * 0.45) / 4)
        }
    };
    
    // Metriques metaboliques additionnelles
    const metabolicAge = age + (adjustmentFactor < 0.95 ? 5 : adjustmentFactor > 1.05 ? -3 : 0);
    const bmrPerKg = Math.round(bmr / weight * 10) / 10;
    const tdeePerKg = Math.round(adjustedTDEE / weight * 10) / 10;
    
    const results = {
        bmr: Math.round(bmr),
        standardTDEE: Math.round(standardTDEE),
        adjustedTDEE: Math.round(adjustedTDEE),
        adjustmentFactor: Math.round(adjustmentFactor * 1000) / 10,
        adjustmentDetails,
        breakdown: {
            bmr: Math.round(bmr),
            tef: Math.round(tef),
            neat: Math.round(neat),
            eat: Math.round(eat)
        },
        goals: {
            maintenance: maintenanceCalories,
            cutting: cuttingCalories,
            bulking: bulkingCalories,
            extremeCut: extremeCutCalories
        },
        macros,
        metrics: {
            metabolicAge: Math.round(metabolicAge),
            bmrPerKg,
            tdeePerKg
        },
        formula: bmrFormula
    };
    
    displayResults(results);
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
    // Profil metabolique
    document.getElementById('bmrResult').textContent = results.bmr;
    document.getElementById('standardTDEE').textContent = results.standardTDEE;
    document.getElementById('adjustedTDEE').textContent = results.adjustedTDEE;
    document.getElementById('adjustmentFactor').textContent = `Facteur: ${results.adjustmentFactor}%`;
    document.getElementById('metabolicAge').textContent = results.metrics.metabolicAge;
    document.getElementById('tdeePerKg').textContent = `${results.metrics.tdeePerKg} kcal/kg`;
    document.getElementById('formulaUsed').textContent = `Formule ${results.formula}`;
    
    // Ajustements appliques
    if (results.adjustmentDetails.length > 0) {
        const adjustmentDiv = document.getElementById('adjustmentDetails');
        const adjustmentsList = document.getElementById('adjustmentsList');
        adjustmentsList.innerHTML = '';
        
        results.adjustmentDetails.forEach(detail => {
            const li = document.createElement('li');
            li.textContent = detail;
            adjustmentsList.appendChild(li);
        });
        
        adjustmentDiv.classList.remove('d-none');
    }
    
    // Breakdown energetique
    document.getElementById('bmrBreakdown').textContent = results.breakdown.bmr;
    document.getElementById('tefBreakdown').textContent = results.breakdown.tef;
    document.getElementById('neatBreakdown').textContent = results.breakdown.neat;
    document.getElementById('eatBreakdown').textContent = results.breakdown.eat;
    
    // Barre de progression
    const total = results.adjustedTDEE;
    document.getElementById('bmrProgress').style.width = `${(results.breakdown.bmr / total * 100)}%`;
    document.getElementById('tefProgress').style.width = `${(results.breakdown.tef / total * 100)}%`;
    document.getElementById('neatProgress').style.width = `${(results.breakdown.neat / total * 100)}%`;
    document.getElementById('eatProgress').style.width = `${(results.breakdown.eat / total * 100)}%`;
    
    // Objectifs caloriques
    const goalsContainer = document.getElementById('calorieGoals');
    goalsContainer.innerHTML = '';
    
    const goals = [
        { key: 'maintenance', name: 'Maintien', subtitle: 'Poids stable', color: 'secondary', macros: results.macros.maintenance },
        { key: 'cutting', name: 'Seche Moderee', subtitle: '-20% (-0.5kg/sem)', color: 'warning', macros: results.macros.cutting },
        { key: 'extremeCut', name: 'Seche Agressive', subtitle: '-30% (-1kg/sem)', color: 'danger', macros: null },
        { key: 'bulking', name: 'Prise de Masse', subtitle: '+15% (+0.3kg/sem)', color: 'success', macros: results.macros.bulking }
    ];
    
    goals.forEach(goal => {
        const goalCard = document.createElement('div');
        goalCard.className = 'col-md-3';
        
        let macrosHtml = '';
        if (goal.macros) {
            macrosHtml = `
                <hr class="my-2">
                <div class="text-start small">
                    <div>P: ${goal.macros.protein}g</div>
                    <div>L: ${goal.macros.fat}g</div>
                    <div>G: ${goal.macros.carb}g</div>
                </div>
            `;
        } else if (goal.key === 'extremeCut') {
            macrosHtml = `<small class="text-danger">Supervision recommandee</small>`;
        }
        
        goalCard.innerHTML = `
            <div class="card border-${goal.color}">
                <div class="card-header bg-${goal.color} ${goal.color === 'warning' ? 'text-dark' : 'text-white'} text-center">
                    <h6 class="mb-0">${goal.name}</h6>
                    <small>${goal.subtitle}</small>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">
                        <span class="fs-4"><strong class="text-${goal.color}">${results.goals[goal.key]}</strong></span>
                        <small class="d-block">kcal/jour</small>
                    </p>
                    ${macrosHtml}
                </div>
            </div>
        `;
        
        goalsContainer.appendChild(goalCard);
    });
    
    // Afficher la section resultats
    document.getElementById('resultsSection').classList.remove('d-none');
    document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Reinitialisation du calculateur
function resetCalculator() {
    const inputs = ['age', 'weight', 'height', 'bodyFat', 'muscleMass', 'sleepHours', 'climateTemp'];
    inputs.forEach(id => {
        document.getElementById(id).value = '';
    });
    
    const selects = [
        { id: 'gender', value: 'male' },
        { id: 'activityLevel', value: 'moderate' },
        { id: 'occupation', value: 'sedentary' },
        { id: 'bmrFormula', value: 'mifflin' },
        { id: 'healthCondition', value: 'healthy' },
        { id: 'geneticFactor', value: 'average' },
        { id: 'stressLevel', value: 'moderate' },
        { id: 'medicationImpact', value: 'none' }
    ];
    
    selects.forEach(select => {
        document.getElementById(select.id).value = select.value;
    });
    
    document.getElementById('errorMessage').classList.add('d-none');
    document.getElementById('resultsSection').classList.add('d-none');
}
</script>
@endpush