@extends('layouts.public')

@section('title', 'Calculateur Calories & Macronutriments Avance - Nutrition Sportive')
@section('meta_description', 'Calculez vos besoins caloriques TDEE, BMR et macronutriments personnalises avec les dernieres recherches en nutrition sportive. Repartition proteines/glucides/lipides optimisee.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-center nataswim-titre3">

<div class="container-lg">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-2 mb-lg-0" style=" background-color: #f9f5f4; color: #63d0c7; padding: 20px 0px; border-radius: 10px; ">
                <h1 class="display-5 fw-bold mb-3">Calculateur Nutritionnel</h1>
                <p>Calculez vos besoins caloriques TDEE</p>
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



<!-- Calculateur -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <h3 class="text-center mb-4">Calculateur TDEE & Macronutriments</h3>
                
                <!-- Donnees personnelles -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="fw-bold mb-2">Âge</label>
                        <input type="number" id="age" class="form-control form-control-lg border-primary" 
                               placeholder="30" min="10" max="120">
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold mb-2">Poids (kg)</label>
                        <input type="number" id="weight" class="form-control form-control-lg border-primary" 
                               placeholder="70" min="30" step="0.1">
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold mb-2">Taille (cm)</label>
                        <input type="number" id="height" class="form-control form-control-lg border-primary" 
                               placeholder="175" min="100" step="1">
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold mb-2">Sexe</label>
                        <select id="gender" class="form-select form-select-lg border-primary">
                            <option value="male">Homme</option>
                            <option value="female">Femme</option>
                        </select>
                    </div>
                </div>

                <!-- Niveau d'activite et objectif -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-running me-2"></i>Niveau d'activite
                        </label>
                        <select id="activity" class="form-select form-select-lg border-warning">
                            <option value="">-- Selectionner --</option>
                            <option value="sedentaire">Sedentaire (bureau, pas d'exercice)</option>
                            <option value="peu-actif">Peu actif (exercice leger 1-3j/sem)</option>
                            <option value="moyennement-actif">Moyennement actif (exercice 3-5j/sem)</option>
                            <option value="actif">Actif (exercice intense 6-7j/sem)</option>
                            <option value="tres-actif">Tres actif (2x/jour ou travail physique)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-target me-2"></i>Objectif principal
                        </label>
                        <select id="goal" class="form-select form-select-lg border-success">
                            <option value="">-- Selectionner --</option>
                            <option value="remise-forme">Remise en forme generale</option>
                            <option value="gain-musculaire">Gain musculaire</option>
                            <option value="tonifier">Se tonifier</option>
                            <option value="endurance">Ameliorer l'endurance</option>
                            <option value="perte-poids">Perdre du poids sainement</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg px-4 py-3 fw-bold w-100" onclick="calculateCalories()">
                            <i class="fas fa-calculator me-2"></i>Calculer mes besoins
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg px-4 py-3 fw-bold w-100" onclick="resetForm()">
                            <i class="fas fa-redo me-2"></i>Reinitialiser
                        </button>
                    </div>
                </div>

                <!-- Resultats -->
                <div id="results" class="d-none">
                    <div class="alert alert-success">
                        <h5 class="alert-heading">
                            <i class="fas fa-chart-bar me-2"></i>Vos Besoins Nutritionnels Personnalises
                        </h5>
                        
                        <!-- Calories -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <div class="card border-secondary h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">
                                            <i class="fas fa-bed me-1"></i>BMR
                                        </h6>
                                        <p class="card-text fs-5">
                                            <strong id="bmrResult">0</strong> kcal
                                        </p>
                                        <small class="text-muted">Metabolisme de base</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">
                                            <i class="fas fa-fire me-1"></i>TDEE
                                        </h6>
                                        <p class="card-text fs-3">
                                            <strong class="text-primary" id="tdeeResult">0</strong> kcal
                                        </p>
                                        <small class="text-muted">Besoins quotidiens totaux</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">
                                            <i class="fas fa-tint me-1"></i>Hydratation
                                        </h6>
                                        <p class="card-text fs-5">
                                            <strong id="hydrationResult">0</strong> L
                                        </p>
                                        <small class="text-muted">Eau par jour minimum</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Macronutriments -->
                        <h6 class="mt-4 mb-3">
                            <i class="fas fa-puzzle-piece me-2"></i>Repartition Macronutriments
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-success">
                                            <i class="fas fa-dumbbell me-1"></i>Proteines
                                        </h6>
                                        <p class="card-text fs-4">
                                            <strong id="proteinGrams">0</strong>g
                                        </p>
                                        <span class="badge bg-success" id="proteinPercent">0%</span>
                                        <div class="mt-2">
                                            <small class="text-muted" id="proteinPerKg">0 g/kg</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-warning">
                                            <i class="fas fa-tint me-1"></i>Lipides
                                        </h6>
                                        <p class="card-text fs-4">
                                            <strong id="fatGrams">0</strong>g
                                        </p>
                                        <span class="badge bg-warning text-dark" id="fatPercent">0%</span>
                                        <div class="mt-2">
                                            <small class="text-muted" id="fatPerKg">0 g/kg</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <h6 class="card-title text-info">
                                            <i class="fas fa-seedling me-1"></i>Glucides
                                        </h6>
                                        <p class="card-text fs-4">
                                            <strong id="carbsGrams">0</strong>g
                                        </p>
                                        <span class="badge bg-info" id="carbsPercent">0%</span>
                                        <div class="mt-2">
                                            <small class="text-muted" id="carbsPerKg">0 g/kg</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barre de progression -->
                        <div class="mt-4">
                            <h6>Repartition Visuelle</h6>
                            <div class="progress" style="height: 30px;" id="macroProgress">
                                <!-- Sera rempli par JavaScript -->
                            </div>
                        </div>

                        <!-- Conseils personnalises -->
                        <div id="personalizedTips" class="mt-4">
                            <!-- Sera rempli par JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Fondements Scientifiques -->
<section class="py-5">
    <div class="container">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-flask me-2"></i>
                    Fondements Scientifiques - Recherches 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Position IOC/ACSM/ISSN 2024 :</strong> Les besoins energetiques des athletes intensifs 
                    peuvent atteindre 40-70 kcal/kg/jour, soit 2800-4900 kcal/jour pour un athlete de 70kg.
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-fire me-2"></i>Besoins Caloriques par Population
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Population</th>
                                        <th>kcal/kg/jour</th>
                                        <th>Exemple 70kg</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-secondary">
                                        <td>Sedentaire</td>
                                        <td>25-30</td>
                                        <td>1750-2100 kcal</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td>Actif general</td>
                                        <td>30-35</td>
                                        <td>2100-2450 kcal</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td>Athlete modere</td>
                                        <td>35-45</td>
                                        <td>2450-3150 kcal</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>Athlete intensif</td>
                                        <td>45-70</td>
                                        <td>3150-4900 kcal</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-puzzle-piece me-2"></i>Macronutriments Recommandes
                        </h6>
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <small>Proteines</small>
                            </div>
                            <div class="card-body">
                                <ul class="small mb-0">
                                    <li><strong>Sedentaire :</strong> 0.8 g/kg</li>
                                    <li><strong>Actif :</strong> 1.2-1.6 g/kg</li>
                                    <li><strong>Force :</strong> 1.6-2.2 g/kg</li>
                                    <li><strong>Endurance :</strong> 1.2-1.8 g/kg</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card border-info mb-3">
                            <div class="card-header bg-info text-white">
                                <small>Glucides</small>
                            </div>
                            <div class="card-body">
                                <ul class="small mb-0">
                                    <li><strong>Faible activite :</strong> 3-5 g/kg</li>
                                    <li><strong>Moderee :</strong> 5-7 g/kg</li>
                                    <li><strong>Intense :</strong> 6-10 g/kg</li>
                                    <li><strong>Ultra-endurance :</strong> 8-12 g/kg</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <small>Lipides</small>
                            </div>
                            <div class="card-body">
                                <ul class="small mb-0">
                                    <li><strong>Minimum sante :</strong> 20% calories</li>
                                    <li><strong>Optimal sport :</strong> 20-35%</li>
                                    <li><strong>Performance :</strong> 0.8-1.0 g/kg</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timing Nutritionnel -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-clock me-2"></i>
                    Chronologie Nutritionnelle (Nutrient Timing)
                </h3>
            </div>
            <div class="card-body">
                <p>Optimisez vos performances en fournissant les bons nutriments au bon moment.</p>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="fas fa-play me-1"></i>Pre-Effort
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Timing</th>
                                                <th>Nutriment</th>
                                                <th>Quantite</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>3-4h avant</td>
                                                <td>Glucides</td>
                                                <td>1-4 g/kg</td>
                                            </tr>
                                            <tr>
                                                <td>1h avant</td>
                                                <td>Glucides</td>
                                                <td>15-75g</td>
                                            </tr>
                                            <tr>
                                                <td>30min avant</td>
                                                <td>Cafeine</td>
                                                <td>3-6 mg/kg</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-running me-1"></i>Pendant Effort
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Duree</th>
                                                <th>Nutriment</th>
                                                <th>Quantite</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>&lt; 60min</td>
                                                <td>Eau</td>
                                                <td>150-250ml/15min</td>
                                            </tr>
                                            <tr>
                                                <td>&gt; 60min</td>
                                                <td>Glucides</td>
                                                <td>30-60g/h</td>
                                            </tr>
                                            <tr>
                                                <td>&gt; 2.5h</td>
                                                <td>Sodium</td>
                                                <td>300-700mg/L</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-check me-1"></i>Post-Effort
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Timing</th>
                                                <th>Nutriment</th>
                                                <th>Quantite</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>0-30min</td>
                                                <td>Proteines</td>
                                                <td>20-40g</td>
                                            </tr>
                                            <tr>
                                                <td>0-60min</td>
                                                <td>Glucides</td>
                                                <td>0.5-1.2 g/kg</td>
                                            </tr>
                                            <tr>
                                                <td>2-4h</td>
                                                <td>Repas complet</td>
                                                <td>equilibre</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hydratation -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-tint me-2"></i>
                    Hydratation et electrolytes
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Protocole d'Hydratation Sportive</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Phase</th>
                                        <th>Volume</th>
                                        <th>Sodium</th>
                                        <th>Glucides</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Pre-effort (2-4h)</td>
                                        <td>5-7 ml/kg</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>Pendant effort</td>
                                        <td>150-250 ml/15-20min</td>
                                        <td>200-700 mg/L</td>
                                        <td>30-60g/h</td>
                                    </tr>
                                    <tr>
                                        <td>Post-effort</td>
                                        <td>150% poids perdu</td>
                                        <td>Selon pertes sudation</td>
                                        <td>Avec proteines</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Signaux de Deshydratation</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Soif</span>
                                <span class="badge bg-warning">Signal tardif</span>
                            </li>
                            <li class="list-group-item">Urine foncee concentree</li>
                            <li class="list-group-item">Fatigue inhabituelle</li>
                            <li class="list-group-item">Maux de tête</li>
                            <li class="list-group-item">Crampes musculaires</li>
                            <li class="list-group-item">Diminution performance</li>
                        </ul>
                        
                        <div class="alert alert-danger mt-3">
                            <small>
                                <strong>Attention :</strong> Une perte de 2% du poids corporel 
                                reduit les performances de 10-15%.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conseils Pratiques -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Conseils Pratiques Evidence-Based
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Planification des Repas</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>5-6 repas/collations par jour</li>
                                    <li>Petit-dejeuner riche en proteines</li>
                                    <li>Collation pre-entraînement (30-90min)</li>
                                    <li>Recuperation dans les 30min post-effort</li>
                                    <li>Dîner leger 3h avant coucher</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Choix Alimentaires</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Aliments entiers prioritaires</li>
                                    <li>Index glycemique adapte au timing</li>
                                    <li>Proteines completes A chaque repas</li>
                                    <li>Graisses insaturees privilegiees</li>
                                    <li>Fibres selon tolerance digestive</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Suivi et Ajustements</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Journal alimentaire (apps recommandees)</li>
                                    <li>Pesee reguliere (tendance, pas quotidien)</li>
                                    <li>Monitoring de la performance</li>
                                    <li>ecoute des signaux corporels</li>
                                    <li>Consultation nutritionniste sport</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important :</strong> Ces recommandations sont generales. Pour des objectifs specifiques 
                    ou des besoins particuliers, consultez un professionnel de la nutrition sportive.
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

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.progress-bar {
    font-size: 0.875rem;
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
// Configuration des ratios bases sur les recherches scientifiques
const activityRatios = {
    'sedentaire': 1.2,
    'peu-actif': 1.375,
    'moyennement-actif': 1.55,
    'actif': 1.725,
    'tres-actif': 1.9
};

const goalRatios = {
    'remise-forme': 1.0,
    'gain-musculaire': 1.1,
    'tonifier': 0.9,
    'endurance': 1.1,
    'perte-poids': 0.8
};

// Besoins en proteines par kg selon activite et objectif
const proteinRatios = {
    'remise-forme': { 'sedentaire': 0.8, 'peu-actif': 1.2, 'moyennement-actif': 1.3, 'actif': 1.6, 'tres-actif': 1.7 },
    'gain-musculaire': { 'sedentaire': 0.8, 'peu-actif': 1.6, 'moyennement-actif': 1.8, 'actif': 2.0, 'tres-actif': 2.2 },
    'tonifier': { 'sedentaire': 0.8, 'peu-actif': 1.3, 'moyennement-actif': 1.6, 'actif': 1.7, 'tres-actif': 1.8 },
    'endurance': { 'sedentaire': 0.8, 'peu-actif': 1.6, 'moyennement-actif': 1.8, 'actif': 1.9, 'tres-actif': 2.0 },
    'perte-poids': { 'sedentaire': 0.8, 'peu-actif': 1.3, 'moyennement-actif': 1.6, 'actif': 1.7, 'tres-actif': 1.8 }
};

// Besoins en lipides par kg selon objectif
const fatRatios = {
    'remise-forme': 0.8,
    'gain-musculaire': 1.0,
    'tonifier': 1.0,
    'endurance': 1.0,
    'perte-poids': 1.0
};

function calculateCalories() {
    const age = parseInt(document.getElementById('age').value);
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    const gender = document.getElementById('gender').value;
    const activity = document.getElementById('activity').value;
    const goal = document.getElementById('goal').value;
    
    if (!age || !weight || !height || !activity || !goal || 
        age <= 0 || weight <= 0 || height <= 0) {
        alert('Veuillez remplir tous les champs avec des valeurs valides');
        return;
    }
    
    // Calcul BMR avec l'equation Mifflin-St Jeor
    let bmr;
    if (gender === 'male') {
        bmr = 10 * weight + 6.25 * height - 5 * age + 5;
    } else {
        bmr = 10 * weight + 6.25 * height - 5 * age - 161;
    }
    
    // Calcul TDEE
    const activityRatio = activityRatios[activity];
    const goalRatio = goalRatios[goal];
    const tdee = bmr * activityRatio * goalRatio;
    
    // Calcul des macronutriments
    const proteinPerKg = proteinRatios[goal][activity];
    const proteinGrams = proteinPerKg * weight;
    const fatGrams = fatRatios[goal] * weight;
    
    const proteinCals = proteinGrams * 4;
    const fatCals = fatGrams * 9;
    const carbsCals = tdee - (proteinCals + fatCals);
    const carbsGrams = Math.max(0, carbsCals / 4); // S'assurer que les glucides ne sont pas negatifs
    
    // Calcul des pourcentages
    const proteinPct = (proteinCals / tdee) * 100;
    const fatPct = (fatCals / tdee) * 100;
    const carbsPct = (carbsCals / tdee) * 100;
    
    // Calcul hydratation (35ml/kg minimum + ajustement activite)
    const hydrationBase = weight * 0.035; // 35ml/kg en litres
    const hydrationBonus = activity === 'tres-actif' ? 0.5 : 
                          activity === 'actif' ? 0.3 : 
                          activity === 'moyennement-actif' ? 0.2 : 0;
    const totalHydration = hydrationBase + hydrationBonus;
    
    displayResults({
        bmr: Math.round(bmr),
        tdee: Math.round(tdee),
        proteinGrams: Math.round(proteinGrams),
        fatGrams: Math.round(fatGrams),
        carbsGrams: Math.round(carbsGrams),
        proteinPct: Math.round(proteinPct),
        fatPct: Math.round(fatPct),
        carbsPct: Math.round(carbsPct),
        proteinPerKg: Math.round(proteinPerKg * 10) / 10,
        fatPerKg: Math.round(fatRatios[goal] * 10) / 10,
        carbsPerKg: Math.round((carbsGrams / weight) * 10) / 10,
        hydration: Math.round(totalHydration * 10) / 10
    }, activity, goal, weight);
}

function displayResults(results, activity, goal, weight) {
    // Affichage des valeurs principales
    document.getElementById('bmrResult').textContent = results.bmr;
    document.getElementById('tdeeResult').textContent = results.tdee;
    document.getElementById('hydrationResult').textContent = results.hydration;
    
    // Macronutriments
    document.getElementById('proteinGrams').textContent = results.proteinGrams;
    document.getElementById('fatGrams').textContent = results.fatGrams;
    document.getElementById('carbsGrams').textContent = results.carbsGrams;
    
    document.getElementById('proteinPercent').textContent = results.proteinPct + '%';
    document.getElementById('fatPercent').textContent = results.fatPct + '%';
    document.getElementById('carbsPercent').textContent = results.carbsPct + '%';
    
    document.getElementById('proteinPerKg').textContent = results.proteinPerKg + ' g/kg';
    document.getElementById('fatPerKg').textContent = results.fatPerKg + ' g/kg';
    document.getElementById('carbsPerKg').textContent = results.carbsPerKg + ' g/kg';
    
    // Barre de progression
    const progressHTML = `
        <div class="progress-bar bg-success" style="width: ${results.proteinPct}%">
            Proteines ${results.proteinPct}%
        </div>
        <div class="progress-bar bg-warning text-dark" style="width: ${results.fatPct}%">
            Lipides ${results.fatPct}%
        </div>
        <div class="progress-bar bg-info" style="width: ${results.carbsPct}%">
            Glucides ${results.carbsPct}%
        </div>
    `;
    document.getElementById('macroProgress').innerHTML = progressHTML;
    
    // Conseils personnalises
    const tipsHTML = generatePersonalizedTips(activity, goal, results, weight);
    document.getElementById('personalizedTips').innerHTML = tipsHTML;
    
    document.getElementById('results').classList.remove('d-none');
    document.getElementById('results').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function generatePersonalizedTips(activity, goal, results, weight) {
    let tips = '';
    
    if (goal === 'perte-poids') {
        tips = `
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-water me-2"></i>Conseils pour Perte de Poids Saine
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li><strong>Deficit modere :</strong> Reduction de 20% des calories (evitez les regimes drastiques)</li>
                        <li><strong>Proteines elevees :</strong> ${results.proteinGrams}g preservent la masse musculaire</li>
                        <li><strong>Timing :</strong> Privilegiez les glucides autour de l'entraînement</li>
                        <li><strong>Surveillance :</strong> Perdez 0.5-1kg/semaine maximum</li>
                    </ul>
                </div>
            </div>
        `;
    } else if (goal === 'gain-musculaire') {
        tips = `
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-dumbbell me-2"></i>Conseils pour Gain Musculaire
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li><strong>Surplus contrôle :</strong> +10% calories pour optimiser les gains</li>
                        <li><strong>Proteines reparties :</strong> 20-30g toutes les 3-4h</li>
                        <li><strong>Post-entraînement :</strong> 25-40g proteines dans les 30min</li>
                        <li><strong>Progression :</strong> Viser 0.25-0.5kg/semaine</li>
                    </ul>
                </div>
            </div>
        `;
    } else if (activity === 'tres-actif') {
        tips = `
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-fire me-2"></i>Conseils Athlete Intensif
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li><strong>energie elevee :</strong> ${results.tdee} kcal necessaires pour la performance</li>
                        <li><strong>Glucides prioritaires :</strong> ${results.carbsGrams}g pour les reserves de glycogene</li>
                        <li><strong>Hydratation :</strong> ${results.hydration}L + pertes sudation</li>
                        <li><strong>Recuperation :</strong> Collation dans les 30min post-effort</li>
                    </ul>
                </div>
            </div>
        `;
    }
    
    return tips;
}

function resetForm() {
    document.getElementById('age').value = '';
    document.getElementById('weight').value = '';
    document.getElementById('height').value = '';
    document.getElementById('gender').value = 'male';
    document.getElementById('activity').value = '';
    document.getElementById('goal').value = '';
    document.getElementById('results').classList.add('d-none');
}
</script>
@endpush