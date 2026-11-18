@extends('layouts.tools')

@section('title', 'Convertisseur Kcal ↔ Macronutriments Avancé - Calculs Métaboliques 2024')
@section('meta_description', 'Convertisseur calories-macronutriments scientifique 2024. Calculs BMR, TDEE, TEF avec algorithmes personnalisés. Répartitions équilibrée, low-carb, high-protein. Recherches métaboliques actualisees.')

@section('content')
<!-- Section titre -->
@include('public.tools.partials.header', ['title' => 'Convertisseur Kcal ↔ Macronutriments Avancé', 'lead' => '', 'icon' => 'fa-exchange-alt'])

<!-- Calculateur Principal -->
<section class="py-5 bg-light">
    <div class="container">
        
        <!-- Mode de Conversion -->
        <div class="card mb-4 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-exchange-alt me-2"></i>Mode de Conversion
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="conversionMode" 
                                   id="kcalToMacros" value="kcalToMacros" checked>
                            <label class="form-check-label fw-bold" for="kcalToMacros">
                                <i class="fas fa-arrow-right me-2"></i>Calories → Macronutriments
                            </label>
                            <small class="d-block text-muted">Répartition selon differents regimes alimentaires</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="conversionMode" 
                                   id="macrosToKcal" value="macrosToKcal">
                            <label class="form-check-label fw-bold" for="macrosToKcal">
                                <i class="fas fa-arrow-left me-2"></i>Macronutriments → Calories
                            </label>
                            <small class="d-block text-muted">Calcul précis avec pourcentages</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calculateur energetique -->
        <div class="card mb-4 shadow-lg">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-calculator me-2"></i>Calculateur énergétique
                </h3>
            </div>
            <div class="card-body">
                
                <!-- Conversion principale -->
                <div id="kcalToMacrosInputs">
                    <h5 class="fw-bold text-success mb-3">Conversion Calories → Macronutriments</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Énergie totale (kcal)</label>
                            <input type="number" id="totalKcal" class="form-control form-control-lg border-success" 
                                   placeholder="Ex: 2000" min="500" max="5000">
                            <small class="text-muted">Entre 500 et 5000 kcal</small>
                        </div>
                    </div>
                </div>

                <div id="macrosToKcalInputs" class="d-none">
                    <h5 class="fw-bold text-primary mb-3">Conversion Macronutriments → Calories</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Protéines (g)</label>
                            <input type="number" id="proteinGrams" class="form-control form-control-lg border-primary" 
                                   placeholder="Ex: 120" min="0" max="500">
                            <small class="text-muted">4 kcal/g</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Lipides (g)</label>
                            <input type="number" id="fatGrams" class="form-control form-control-lg border-warning" 
                                   placeholder="Ex: 80" min="0" max="300">
                            <small class="text-muted">9 kcal/g</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Glucides (g)</label>
                            <input type="number" id="carbGrams" class="form-control form-control-lg border-info" 
                                   placeholder="Ex: 250" min="0" max="800">
                            <small class="text-muted">4 kcal/g</small>
                        </div>
                    </div>
                </div>

                <!-- Donnees personnelles pour calcul metabolique -->
                <h5 class="fw-bold text-secondary mb-3">
                    <i class="fas fa-user me-2"></i>Données personnelles (calcul métabolique avancé)
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label class="form-label">Poids (kg)</label>
                        <input type="number" id="weight" class="form-control" placeholder="70" min="30" max="200">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Taille (cm)</label>
                        <input type="number" id="height" class="form-control" placeholder="175" min="120" max="220">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Âge</label>
                        <input type="number" id="age" class="form-control" placeholder="30" min="15" max="90">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sexe</label>
                        <select id="gender" class="form-select">
                            <option value="male" selected>Homme</option>
                            <option value="female">Femme</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Niveau d'activité</label>
                        <select id="activityLevel" class="form-select">
                            <option value="sedentary" selected>Sédentaire</option>
                            <option value="light">Légère</option>
                            <option value="moderate">Modérée</option>
                            <option value="active">Active</option>
                            <option value="veryActive">Très active</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg fw-bold w-100" onclick="calculateConversion()">
                            <i class="fas fa-calculator me-2"></i>Calculer & Analyser
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg fw-bold w-100" onclick="resetForm()">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultats de Conversion -->
        <div id="conversionResults" class="d-none">
            <!-- Resultats Calories → Macros -->
            <div id="kcalToMacrosResults" class="card mb-4 shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-chart-pie me-2"></i>Répartitions Recommandées
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white text-center">
                                    <h6 class="mb-0">Regime équilibre</h6>
                                    <small>25% P / 25% L / 50% G</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="mb-2"><strong class="text-primary" id="balancedProtein">0g</strong> protéines</p>
                                    <p class="mb-2"><strong class="text-warning" id="balancedFat">0g</strong> lipides</p>
                                    <p class="mb-0"><strong class="text-info" id="balancedCarb">0g</strong> glucides</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning text-dark text-center">
                                    <h6 class="mb-0">Low Carb</h6>
                                    <small>30% P / 50% L / 20% G</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="mb-2"><strong class="text-primary" id="lowCarbProtein">0g</strong> protéines</p>
                                    <p class="mb-2"><strong class="text-warning" id="lowCarbFat">0g</strong> lipides</p>
                                    <p class="mb-0"><strong class="text-info" id="lowCarbCarb">0g</strong> glucides</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-danger h-100">
                                <div class="card-header bg-danger text-white text-center">
                                    <h6 class="mb-0">High Protein</h6>
                                    <small>35% P / 25% L / 40% G</small>
                                </div>
                                <div class="card-body text-center">
                                    <p class="mb-2"><strong class="text-primary" id="highProteinProtein">0g</strong> protéines</p>
                                    <p class="mb-2"><strong class="text-warning" id="highProteinFat">0g</strong> lipides</p>
                                    <p class="mb-0"><strong class="text-info" id="highProteinCarb">0g</strong> glucides</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultats Macros → Calories -->
            <div id="macrosToKcalResults" class="card mb-4 shadow-lg d-none">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-fire me-2"></i>Analyse énergétique
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total énergétique</h5>
                                    <p class="card-text">
                                        <span class="fs-2"><strong class="text-success" id="totalCalories">0</strong></span>
                                        <span class="d-block">kcal</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Répartition énergétique</h6>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Protéines:</span>
                                        <strong class="text-primary" id="proteinPercent">0%</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Lipides:</span>
                                        <strong class="text-warning" id="fatPercent">0%</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Glucides:</span>
                                        <strong class="text-info" id="carbPercent">0%</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analyse Metabolique -->
            <div id="metabolicResults" class="card mb-4 shadow-lg d-none">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-2">
                        <i class="fas fa-heartbeat me-2"></i>Analyse Métabolique Personnalisée
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card border-primary text-center">
                                <div class="card-body">
                                    <h6 class="card-title">BMR Mifflin-St Jeor</h6>
                                    <p class="card-text">
                                        <span class="fs-4"><strong class="text-primary" id="bmrMifflin">0</strong></span>
                                        <small class="d-block">kcal/jour</small>
                                    </p>
                                    <small class="text-muted">Métabolisme de base</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success text-center">
                                <div class="card-body">
                                    <h6 class="card-title">TDEE Total</h6>
                                    <p class="card-text">
                                        <span class="fs-4"><strong class="text-success" id="tdeeTotal">0</strong></span>
                                        <small class="d-block">kcal/jour</small>
                                    </p>
                                    <small class="text-muted">Dépense totale</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning text-center">
                                <div class="card-body">
                                    <h6 class="card-title">NEAT</h6>
                                    <p class="card-text">
                                        <span class="fs-5"><strong class="text-warning" id="neatValue">0</strong></span>
                                        <small class="d-block">kcal/jour</small>
                                    </p>
                                    <small class="text-muted">Activité spontanee</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger text-center">
                                <div class="card-body">
                                    <h6 class="card-title">TEF</h6>
                                    <p class="card-text">
                                        <span class="fs-5"><strong class="text-danger" id="tefValue">0</strong></span>
                                        <small class="d-block">kcal/jour</small>
                                    </p>
                                    <small class="text-muted">Effet thermique</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-water me-2"></i>Interprétation</h6>
                        <p class="small mb-0" id="metabolicInterpretation">
                            <!-- Sera rempli par JavaScript -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        
        <!-- Coefficients energetiques Actualises -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-flask me-2"></i>
                    Coefficients énergétiques - Recherches 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Valeurs énergétiques Actualisees</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Macronutriment</th>
                                        <th>Standard (kcal/g)</th>
                                        <th>Métabolisable (kcal/g)</th>
                                        <th>TEF (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Protéines</strong></td>
                                        <td>4.0</td>
                                        <td>3.2-3.8</td>
                                        <td>20-30%</td>
                                    </tr>
                                    <tr>
                                        <td>Glucides</td>
                                        <td>4.0</td>
                                        <td>3.7-3.9</td>
                                        <td>5-10%</td>
                                    </tr>
                                    <tr>
                                        <td>Lipides</td>
                                        <td>9.0</td>
                                        <td>8.7-8.9</td>
                                        <td>0-3%</td>
                                    </tr>
                                    <tr>
                                        <td>Alcool</td>
                                        <td>7.0</td>
                                        <td>5.6-6.2</td>
                                        <td>15-20%</td>
                                    </tr>
                                    <tr>
                                        <td>Fibres</td>
                                        <td>2.0</td>
                                        <td>0.5-2.0</td>
                                        <td>Variable</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Facteurs de Correction Modernes</h6>
                        <ul class="small">
                            <li><strong>Qualite protéique :</strong> DIAAS vs PDCAAS (2024)</li>
                            <li><strong>Index glycémique :</strong> Impact sur TEF des glucides</li>
                            <li><strong>Forme des lipides :</strong> MCT vs LCT vs PUFA</li>
                            <li><strong>Transformation alimentaire :</strong> Ultra-transforme vs naturel</li>
                            <li><strong>Timing nutritionnel :</strong> Chronobiologie métabolique</li>
                            <li><strong>Micronutriments :</strong> Cofacteurs métaboliques</li>
                        </ul>
                        
                        <div class="alert alert-info">
                            <small>
                                <strong>Innovation 2024 :</strong> Les nouveaux algorithmes integrent 
                                la variabilité inter-individuelle du métabolisme (±15-20%).
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Composantes de la Depense energetique -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-chart-line me-2"></i>
                    Composantes de la Dépense énergétique Totale
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Breakdown énergétique Détaillé (% TDEE)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Composante</th>
                                        <th>Sédentaire</th>
                                        <th>Actif</th>
                                        <th>Athlete</th>
                                        <th>Variabilité</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary">
                                        <td><strong>BMR/RMR</strong></td>
                                        <td>60-70%</td>
                                        <td>50-60%</td>
                                        <td>45-55%</td>
                                        <td>±8-15%</td>
                                    </tr>
                                    <tr>
                                        <td><strong>TEF</strong></td>
                                        <td>8-12%</td>
                                        <td>8-12%</td>
                                        <td>8-12%</td>
                                        <td>±3-5%</td>
                                    </tr>
                                    <tr>
                                        <td><strong>EAT (Exercice)</strong></td>
                                        <td>5-10%</td>
                                        <td>15-25%</td>
                                        <td>25-35%</td>
                                        <td>Variable</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NEAT</strong></td>
                                        <td>15-30%</td>
                                        <td>15-25%</td>
                                        <td>15-20%</td>
                                        <td>±20-50%</td>
                                    </tr>
                                    <tr>
                                        <td><strong>SPA (Activité spontanee)</strong></td>
                                        <td>2-8%</td>
                                        <td>2-8%</td>
                                        <td>2-8%</td>
                                        <td>±50-100%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Recherche 2024</h6>
                            <p class="small mb-0">
                                La composante NEAT varie jusqu'A 800 kcal/jour entre individus de même profil, 
                                expliquant les differences métaboliques majeures.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Facteurs d'Influence Métabolique</h6>
                        <ul class="small">
                            <li><strong>Génétique :</strong> 40-60% de la variation BMR</li>
                            <li><strong>Composition corporelle :</strong> Masse musculaire vs graisseuse</li>
                            <li><strong>Âge :</strong> -1-2% BMR par decennie après 30 ans</li>
                            <li><strong>Sexe :</strong> Hommes +10-15% BMR vs femmes</li>
                            <li><strong>Thermoregulation :</strong> Adaptation au froid/chaud</li>
                            <li><strong>État nutritionnel :</strong> Restriction/suralimentation</li>
                            <li><strong>Hormones :</strong> Thyroïde, leptine, cortisol</li>
                            <li><strong>Médicaments :</strong> Impact sur métabolisme</li>
                            <li><strong>Pathologies :</strong> Diabète, SOPK, hypothyroïdie</li>
                        </ul>
                        
                        <div class="card mt-3 border-secondary">
                            <div class="card-header bg-secondary text-white text-center">
                                <small>Technologies de Mesure 2024</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Calorimétrie indirecte :</strong> RMR précis</li>
                                    <li><strong>Doubly Labeled Water :</strong> TDEE gold standard</li>
                                    <li><strong>Wearables avances :</strong> Estimation NEAT/EAT</li>
                                    <li><strong>Monitors métaboliques :</strong> TEF en temps réel</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calculs energetiques par Aliment -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-apple-alt me-2"></i>
                    Calculs énergétiques par Aliment, Repas et Jour
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Méthodes de Calcul Precises</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Méthode</th>
                                        <th>Précision</th>
                                        <th>Application</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Calorimétrie bombe</td>
                                        <td>±0.1%</td>
                                        <td>Recherche alimentaire</td>
                                    </tr>
                                    <tr>
                                        <td>Facteurs Atwater</td>
                                        <td>±5-10%</td>
                                        <td>etiquetage commercial</td>
                                    </tr>
                                    <tr>
                                        <td>Atwater spécifiques</td>
                                        <td>±3-5%</td>
                                        <td>Bases nutritionnelles</td>
                                    </tr>
                                    <tr>
                                        <td>Analyse proximale</td>
                                        <td>±2-5%</td>
                                        <td>Laboratoires agrees</td>
                                    </tr>
                                    <tr>
                                        <td>Spectroscopie NIR</td>
                                        <td>±1-3%</td>
                                        <td>Contrôle qualite industriel</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Variabilité énergétique des Aliments</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Catégorie d'Aliment</th>
                                        <th>Variation kcal/100g</th>
                                        <th>Facteurs d'Influence</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Fruits</td>
                                        <td>±15-25%</td>
                                        <td>Maturite, variete, saison</td>
                                    </tr>
                                    <tr>
                                        <td>Legumes</td>
                                        <td>±10-20%</td>
                                        <td>Mode culture, préparation</td>
                                    </tr>
                                    <tr>
                                        <td>Viandes</td>
                                        <td>±20-40%</td>
                                        <td>Partie, elevage, cuisson</td>
                                    </tr>
                                    <tr>
                                        <td>Cereales</td>
                                        <td>±5-15%</td>
                                        <td>Raffinage, transformation</td>
                                    </tr>
                                    <tr>
                                        <td>Produits transformes</td>
                                        <td>±30-50%</td>
                                        <td>Recette, additifs, process</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row g-4 mt-3">
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white text-center">
                                <h6 class="mb-0">Niveau Aliment</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Base :</strong> Macronutriments × coefficients</li>
                                    <li><strong>Correction :</strong> Fibres, alcool, acides organiques</li>
                                    <li><strong>Ajustement :</strong> Transformation, cuisson</li>
                                    <li><strong>Précision :</strong> ±5-15% selon aliment</li>
                                </ul>
                                <div class="text-center mt-2 bg-light p-2 rounded">
                                    <strong class="text-primary">Exemple : 1 pomme (150g)</strong><br/>
                                    <small>78 kcal ± 12 kcal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark text-center">
                                <h6 class="mb-0">Niveau Repas</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Sommation :</strong> Σ calories individuelles</li>
                                    <li><strong>Interactions :</strong> Effet matrice alimentaire</li>
                                    <li><strong>TEF combine :</strong> Synergie macronutriments</li>
                                    <li><strong>Précision :</strong> ±10-20% selon complexite</li>
                                </ul>
                                <div class="text-center mt-2 bg-light p-2 rounded">
                                    <strong class="text-warning">Exemple : Petit-dejeuner</strong><br />
                                    <small>520 kcal ± 85 kcal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white text-center">
                                <h6 class="mb-0">Niveau Journee</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Total :</strong> Σ repas + collations</li>
                                    <li><strong>Rythme :</strong> Chronobiologie nutritionnelle</li>
                                    <li><strong>Adaptation :</strong> TEF journalier global</li>
                                    <li><strong>Précision :</strong> ±15-25% population générale</li>
                                </ul>
                                <div class="text-center mt-2 bg-light p-2 rounded">
                                    <strong class="text-success">Exemple : Apport quotidien</strong><br/>
                                    <small>2150 kcal ± 320 kcal</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technologies et Innovations 2024 -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-microchip me-2"></i>
                    Technologies et Innovations Métaboliques 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Outils de Mesure Avancés</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Technologie</th>
                                        <th>Paramètre Mesure</th>
                                        <th>Précision</th>
                                        <th>Coût</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>MetaboFlex Pro</td>
                                        <td>RMR en temps réel</td>
                                        <td>±3%</td>
                                        <td>€€€</td>
                                    </tr>
                                    <tr>
                                        <td>Lumen device</td>
                                        <td>Ratio RQ métabolique</td>
                                        <td>±5%</td>
                                        <td>€€</td>
                                    </tr>
                                    <tr>
                                        <td>KORR CardioCoach</td>
                                        <td>VO2/VCO2 portable</td>
                                        <td>±2%</td>
                                        <td>€€€€</td>
                                    </tr>
                                    <tr>
                                        <td>InBody 970</td>
                                        <td>Composition corporelle</td>
                                        <td>±1-3%</td>
                                        <td>€€€€</td>
                                    </tr>
                                    <tr>
                                        <td>CGM (Continuous Glucose)</td>
                                        <td>Réponse glycémique</td>
                                        <td>±8%</td>
                                        <td>€</td>
                                    </tr>
                                    <tr>
                                        <td>Whoop/Oura Ring</td>
                                        <td>Dépense estimée 24h</td>
                                        <td>±15-25%</td>
                                        <td>€</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Innovations Algorithmiques</h6>
                        <ul class="small">
                            <li><strong>IA predictive :</strong> Modèles personnalisés bases sur historique</li>
                            <li><strong>Machine Learning :</strong> Ajustement continu selon réponse individuelle</li>
                            <li><strong>Digital Twins :</strong> Jumeaux numeriques métaboliques</li>
                            <li><strong>Computer Vision :</strong> Estimation portions par photo</li>
                            <li><strong>Blockchain nutrition :</strong> Traçabilite énergétique aliments</li>
                            <li><strong>Capteurs IoT :</strong> Monitoring environnemental continu</li>
                        </ul>
                        
                        <div class="card mt-3 border-info">
                            <div class="card-header bg-info text-white">
                                <small>Applications émergentes 2024</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>MyFitnessPal AI :</strong> Reconnaissance alimentaire automatique</li>
                                    <li><strong>Nutrino/Suggestic :</strong> Recommandations personnalisées IA</li>
                                    <li><strong>DayTwo :</strong> Nutrition basee sur microbiome</li>
                                    <li><strong>Habit :</strong> Tests génétiques + coaching métabolique</li>
                                    <li><strong>Virta Health :</strong> Therapie métabolique digitale</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommandations Pratiques -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Recommandations Pratiques et Personnalisation
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6>Stratégies d'Optimisation Métabolique</h6>
                        <ul class="small">
                            <li><strong>Timing nutritionnel :</strong> Synchronisation circadienne</li>
                            <li><strong>Fréquence repas :</strong> 3-6 repas selon profil</li>
                            <li><strong>Hydratation :</strong> Impact sur TEF (+3-5%)</li>
                            <li><strong>Température aliments :</strong> Thermogenese adaptative</li>
                            <li><strong>Mastication :</strong> Augmentation TEF mécanique</li>
                            <li><strong>Activité post-prandiale :</strong> Marche digestive</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Personnalisation selon Profil</h6>
                        <ul class="small">
                            <li><strong>Athletes :</strong> Périodisation énergétique</li>
                            <li><strong>Sédentaires :</strong> Focus sur NEAT et TEF</li>
                            <li><strong>Seniors :</strong> Prévention sarcopenie</li>
                            <li><strong>Metaboliquement malades :</strong> Restriction contrôlee</li>
                            <li><strong>Femmes enceintes :</strong> Besoins évolutifs</li>
                            <li><strong>Pathologies :</strong> Adaptations spécifiques</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Surveillance et Ajustements</h6>
                        <ul class="small">
                            <li><strong>Biomarqueurs :</strong> Suivi métabolique régulier</li>
                            <li><strong>Composition corporelle :</strong> DEXA bi-annuel</li>
                            <li><strong>Performance :</strong> Tests fonctionnels</li>
                            <li><strong>Bien-être :</strong> Énergie, sommeil, humeur</li>
                            <li><strong>Adaptation métabolique :</strong> Prévention plateaux</li>
                            <li><strong>Flexibilite métabolique :</strong> Tests substrats</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-success mt-4">
                    <h6><i class="fas fa-rocket me-2"></i>Vision 2024</h6>
                    <p class="mb-0">
                        L'avenir de la nutrition énergétique tend vers une approche hyper-personnalisee integrant 
                        génétique, microbiome, lifestyle et réponses métaboliques individuelles pour une précision 
                        optimale des apports et dépenses énergétiques.
                    </p>
                </div>
            </div>
        </div>

        <!-- Rappels Nutritionnels Fondamentaux -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-bookmark me-2"></i>
                    Rappels Nutritionnels Fondamentaux
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6>Coefficients énergétiques Standards</h6>
                        <ul class="small">
                            <li><strong>1g de protéine = 4 kcal</strong> (digestibilite 95%)</li>
                            <li><strong>1g de glucide = 4 kcal</strong> (digestibilite 98%)</li>
                            <li><strong>1g de lipide = 9 kcal</strong> (digestibilite 95%)</li>
                            <li><strong>1g d'alcool = 7 kcal</strong> (métabolisme prioritaire)</li>
                            <li><strong>1g de fibres = 2 kcal</strong> (fermentation colique)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Répartitions Classiques Actualisees</h6>
                        <ul class="small">
                            <li><strong>Équilibrée :</strong> 25% protéines, 25% lipides, 50% glucides</li>
                            <li><strong>Méditerranéenne :</strong> 15% protéines, 35% lipides, 50% glucides</li>
                            <li><strong>DASH :</strong> 20% protéines, 25% lipides, 55% glucides</li>
                            <li><strong>Cétogène :</strong> 25% protéines, 70% lipides, 5% glucides</li>
                            <li><strong>Sportif endurance :</strong> 15% protéines, 25% lipides, 60% glucides</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-exclamation-circle me-2"></i>Important</h6>
                    <p class="small mb-0">
                        Ces calculs sont des estimations. Pour un suivi précis, particulierement en cas de pathologie 
                        ou d'objectifs spécifiques, consultez un professionnel de la nutrition.
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
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.card {
    transition: all 0.3s ease;
}

.table th {
    border-top: none;
}
</style>
@endpush

@push('scripts')
<script>
class KcalMacroConverter {
    constructor() {
        this.mode = 'kcalToMacros';
        this.setupEventListeners();
    }
    
    setupEventListeners() {
        // Gestion changement de mode
        document.querySelectorAll('input[name="conversionMode"]').forEach(radio => {
            radio.addEventListener('change', (e) => this.switchMode(e.target.value));
        });
    }
    
    switchMode(mode) {
        this.mode = mode;
        const kcalInputs = document.getElementById('kcalToMacrosInputs');
        const macrosInputs = document.getElementById('macrosToKcalInputs');
        
        if (mode === 'kcalToMacros') {
            kcalInputs.classList.remove('d-none');
            macrosInputs.classList.add('d-none');
        } else {
            kcalInputs.classList.add('d-none');
            macrosInputs.classList.remove('d-none');
        }
        
        // Masquer les resultats lors du changement de mode
        document.getElementById('conversionResults').classList.add('d-none');
    }
    
    calculateBMR(weight, height, age, gender) {
        // Formule Mifflin-St Jeor (plus precise)
        if (gender === 'male') {
            return 10 * weight + 6.25 * height - 5 * age + 5;
        } else {
            return 10 * weight + 6.25 * height - 5 * age - 161;
        }
    }
    
    calculateTDEE(bmr, activityLevel) {
        const activityFactors = {
            sedentary: 1.2,
            light: 1.375,
            moderate: 1.55,
            active: 1.725,
            veryActive: 1.9
        };
        
        return bmr * activityFactors[activityLevel];
    }
    
    calculateAdvancedMetrics(weight, height, age, gender, activityLevel) {
        if (!weight || !height || !age) return null;
        
        const bmr = this.calculateBMR(weight, height, age, gender);
        const tdee = this.calculateTDEE(bmr, activityLevel);
        
        // Composantes energetiques avancees
        const neat = tdee * 0.15; // NEAT (Non-Exercise Activity Thermogenesis)
        const tef = tdee * 0.10;  // TEF (Thermic Effect of Food)
        
        return {
            bmr: Math.round(bmr),
            tdee: Math.round(tdee),
            neat: Math.round(neat),
            tef: Math.round(tef)
        };
    }
    
    calculateKcalToMacros(kcal) {
        const balanced = {
            protein: Math.round((kcal * 0.25) / 4),
            fat: Math.round((kcal * 0.25) / 9),
            carb: Math.round((kcal * 0.5) / 4)
        };
        
        const lowCarb = {
            protein: Math.round((kcal * 0.30) / 4),
            fat: Math.round((kcal * 0.50) / 9),
            carb: Math.round((kcal * 0.20) / 4)
        };
        
        const highProtein = {
            protein: Math.round((kcal * 0.35) / 4),
            fat: Math.round((kcal * 0.25) / 9),
            carb: Math.round((kcal * 0.40) / 4)
        };
        
        return { balanced, lowCarb, highProtein };
    }
    
    calculateMacrosToKcal(protein, fat, carb) {
        const total = protein * 4 + fat * 9 + carb * 4;
        
        return {
            kcal: Math.round(total * 10) / 10,
            proteinPercent: Math.round((protein * 4 / total) * 100),
            fatPercent: Math.round((fat * 9 / total) * 100),
            carbPercent: Math.round((carb * 4 / total) * 100)
        };
    }
    
    displayKcalToMacrosResults(results) {
        // Regime equilibre
        document.getElementById('balancedProtein').textContent = `${results.balanced.protein}g`;
        document.getElementById('balancedFat').textContent = `${results.balanced.fat}g`;
        document.getElementById('balancedCarb').textContent = `${results.balanced.carb}g`;
        
        // Low carb
        document.getElementById('lowCarbProtein').textContent = `${results.lowCarb.protein}g`;
        document.getElementById('lowCarbFat').textContent = `${results.lowCarb.fat}g`;
        document.getElementById('lowCarbCarb').textContent = `${results.lowCarb.carb}g`;
        
        // High protein
        document.getElementById('highProteinProtein').textContent = `${results.highProtein.protein}g`;
        document.getElementById('highProteinFat').textContent = `${results.highProtein.fat}g`;
        document.getElementById('highProteinCarb').textContent = `${results.highProtein.carb}g`;
        
        document.getElementById('kcalToMacrosResults').classList.remove('d-none');
        document.getElementById('macrosToKcalResults').classList.add('d-none');
    }
    
    displayMacrosToKcalResults(results) {
        document.getElementById('totalCalories').textContent = results.kcal;
        document.getElementById('proteinPercent').textContent = `${results.proteinPercent}%`;
        document.getElementById('fatPercent').textContent = `${results.fatPercent}%`;
        document.getElementById('carbPercent').textContent = `${results.carbPercent}%`;
        
        document.getElementById('macrosToKcalResults').classList.remove('d-none');
        document.getElementById('kcalToMacrosResults').classList.add('d-none');
    }
    
    displayMetabolicResults(metrics) {
        document.getElementById('bmrMifflin').textContent = metrics.bmr;
        document.getElementById('tdeeTotal').textContent = metrics.tdee;
        document.getElementById('neatValue').textContent = metrics.neat;
        document.getElementById('tefValue').textContent = metrics.tef;
        
        // Interpretation
        let interpretation = `Votre métabolisme de base est de ${metrics.bmr} kcal/jour. `;
        interpretation += `Avec votre niveau d'activité, vos besoins totaux sont estimes A ${metrics.tdee} kcal/jour. `;
        interpretation += `L'activité non-sportive représente environ ${metrics.neat} kcal et l'effet thermique des aliments ${metrics.tef} kcal.`;
        
        document.getElementById('metabolicInterpretation').textContent = interpretation;
        document.getElementById('metabolicResults').classList.remove('d-none');
    }
}

let converter;

function calculateConversion() {
    if (!converter) converter = new KcalMacroConverter();
    
    // Recuperation des donnees personnelles
    const weight = parseFloat(document.getElementById('weight').value) || 0;
    const height = parseFloat(document.getElementById('height').value) || 0;
    const age = parseInt(document.getElementById('age').value) || 0;
    const gender = document.getElementById('gender').value;
    const activityLevel = document.getElementById('activityLevel').value;
    
    if (converter.mode === 'kcalToMacros') {
        const kcal = parseFloat(document.getElementById('totalKcal').value);
        
        if (!kcal || kcal < 500 || kcal > 5000) {
            alert('Veuillez saisir une valeur entre 500 et 5000 kcal.');
            return;
        }
        
        const results = converter.calculateKcalToMacros(kcal);
        converter.displayKcalToMacrosResults(results);
        
    } else {
        const protein = parseFloat(document.getElementById('proteinGrams').value) || 0;
        const fat = parseFloat(document.getElementById('fatGrams').value) || 0;
        const carb = parseFloat(document.getElementById('carbGrams').value) || 0;
        
        if (protein === 0 && fat === 0 && carb === 0) {
            alert('Veuillez saisir au moins une valeur de macronutriment.');
            return;
        }
        
        const results = converter.calculateMacrosToKcal(protein, fat, carb);
        converter.displayMacrosToKcalResults(results);
    }
    
    // Calculs metaboliques si donnees disponibles
    if (weight > 0 && height > 0 && age > 0) {
        const metrics = converter.calculateAdvancedMetrics(weight, height, age, gender, activityLevel);
        if (metrics) {
            converter.displayMetabolicResults(metrics);
        }
    }
    
    // Afficher les resultats
    document.getElementById('conversionResults').classList.remove('d-none');
    document.getElementById('conversionResults').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function resetForm() {
    // Reinitialiser tous les champs
    document.getElementById('totalKcal').value = '';
    document.getElementById('proteinGrams').value = '';
    document.getElementById('fatGrams').value = '';
    document.getElementById('carbGrams').value = '';
    document.getElementById('weight').value = '';
    document.getElementById('height').value = '';
    document.getElementById('age').value = '';
    document.getElementById('gender').value = 'male';
    document.getElementById('activityLevel').value = 'sedentary';
    
    // Reinitialiser le mode
    document.getElementById('kcalToMacros').checked = true;
    document.getElementById('kcalToMacrosInputs').classList.remove('d-none');
    document.getElementById('macrosToKcalInputs').classList.add('d-none');
    
    // Masquer les resultats
    document.getElementById('conversionResults').classList.add('d-none');
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    converter = new KcalMacroConverter();
});
</script>
@endpush