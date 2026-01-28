@extends('layouts.public')

@section('title', 'Calculateur Besoins Hydriques - Hydratation Personnalisee Scientifique')
@section('meta_description', 'Calculez vos besoins en eau quotidiens selon votre activite, climat et sante. Methodes IOM et scientifiques validees. Recommandations personnalisees pour sportifs et populations speciales.')

@section('content')

<!-- Section titre -->
<section class="py-5 text-center nataswim-titre3">

<div class="container-lg">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-2 mb-lg-0" style=" background-color: #f9f5f4; color: #63d0c7; padding: 20px 0px; border-radius: 10px; ">
                <h1 class="display-5 fw-bold mb-3">Calculateur Besoins Hydriques</h1>
                <p>Calculez vos besoins en eau quotidiens selon votre activite, climat et sante</p>
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
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-calculator me-2"></i>
                    Calculateur Personnalise Multi-Facteurs
                </h3>
            </div>
            <div class="card-body p-4">
                
                <!-- Messages d'erreur -->
                <div id="errorMessage" class="alert alert-danger d-none">
                    <!-- Sera rempli par JavaScript -->
                </div>

                <!-- Donnees Personnelles -->
                <h5 class="fw-bold text-primary mb-3">
                    <i class="fas fa-user me-2"></i>Donnees Personnelles
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Poids (kg)</label>
                        <input type="number" id="weight" class="form-control form-control-lg border-primary" 
                               placeholder="70" min="30" max="200">
                        <small class="text-muted">Entre 30 et 200 kg</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Taille (cm)</label>
                        <input type="number" id="height" class="form-control form-control-lg border-primary" 
                               placeholder="175" min="120" max="220">
                        <small class="text-muted">Entre 120 et 220 cm</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Âge (annees)</label>
                        <input type="number" id="age" class="form-control form-control-lg border-primary" 
                               placeholder="30" min="5" max="100">
                        <small class="text-muted">Entre 5 et 100 ans</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sexe</label>
                        <select id="gender" class="form-select form-select-lg border-primary">
                            <option value="male">Homme</option>
                            <option value="female">Femme</option>
                        </select>
                    </div>
                </div>

                <!-- Activite Physique -->
                <h5 class="fw-bold text-warning mb-3">
                    <i class="fas fa-running me-2"></i>Activite Physique
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Heures d'activite/jour</label>
                        <input type="number" id="activityHours" class="form-control border-warning" 
                               placeholder="1.5" step="0.5" min="0" max="8">
                        <small class="text-muted">Entre 0 et 8 heures par jour</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Intensite d'activite</label>
                        <select id="activityIntensity" class="form-select border-warning">
                            <option value="light">Legere (marche, yoga)</option>
                            <option value="moderate" selected>Moderee (jogging, natation)</option>
                            <option value="intense">Intense (course, HIIT)</option>
                            <option value="extreme">Extrême (ultra-endurance)</option>
                        </select>
                    </div>
                </div>

                <!-- Conditions Environnementales -->
                <h5 class="fw-bold text-danger mb-3">
                    <i class="fas fa-sun me-2"></i>Conditions Environnementales
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Temperature (°C)</label>
                        <input type="number" id="temperature" class="form-control border-danger" 
                               placeholder="20" min="-20" max="50">
                        <small class="text-muted">Entre -20 et 50°C</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Humidite (%)</label>
                        <input type="number" id="humidity" class="form-control border-info" 
                               placeholder="50" min="0" max="100">
                        <small class="text-muted">Entre 0 et 100%</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Altitude (m)</label>
                        <input type="number" id="altitude" class="form-control border-secondary" 
                               placeholder="0" min="0" max="5000">
                        <small class="text-muted">Entre 0 et 5000m</small>
                    </div>
                </div>

                <!-- Conditions de Sante -->
                <h5 class="fw-bold text-success mb-3">
                    <i class="fas fa-heartbeat me-2"></i>Conditions de Sante
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">etat de sante</label>
                        <select id="healthCondition" class="form-select border-success">
                            <option value="healthy" selected>Bonne sante</option>
                            <option value="fever">Fievre/Infection</option>
                            <option value="diabetes">Diabete</option>
                            <option value="kidney">Insuffisance renale</option>
                            <option value="heart">Insuffisance cardiaque</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="pregnancyContainer" style="display: none;">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="pregnancyLactation">
                            <label class="form-check-label fw-bold" for="pregnancyLactation">
                                <i class="fas fa-baby me-2"></i>Grossesse ou Allaitement
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg px-4 py-3 fw-bold w-100" onclick="calculateHydration()">
                            <i class="fas fa-calculator me-2"></i>Calculer Besoins Hydriques
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg px-4 py-3 fw-bold w-100" onclick="resetForm()">
                            <i class="fas fa-redo me-2"></i>Reinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultats -->
        <div id="results" class="d-none">
            <!-- Resultats Principaux -->
            <div class="card shadow-lg border-0 mt-4">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-tint me-2"></i>Vos Besoins Hydriques Personnalises
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4" id="mainResults">
                        <!-- Sera rempli par JavaScript -->
                    </div>
                    
                    <!-- Repartition des ajustements -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Repartition des Ajustements</h6>
                            <div class="row g-2" id="adjustmentsBreakdown">
                                <!-- Sera rempli par JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Protocole Sportif -->
                    <div id="sportsProtocol" class="alert alert-info d-none">
                        <h6 class="fw-bold">
                            <i class="fas fa-dumbbell me-2"></i>Protocole Sportif Recommande
                        </h6>
                        <div class="row g-2" id="sportsBreakdown">
                            <!-- Sera rempli par JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance Hydrique -->
            <div class="card shadow-lg border-0 mt-4">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-balance-scale me-2"></i>Balance Hydrique Estimee
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Pertes Hydriques (Litres/jour)</h6>
                            
                            <!-- Visualisation des pertes -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Urinaire (60%)</span>
                                    <span id="urinaryLoss" class="fw-bold">0.0L</span>
                                </div>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div id="urinaryBar" class="progress-bar bg-primary" style="width: 60%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Cutanee + Sudation</span>
                                    <span id="skinLoss" class="fw-bold">0.0L</span>
                                </div>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div id="skinBar" class="progress-bar bg-danger" style="width: 20%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Respiratoire (15%)</span>
                                    <span id="respiratoryLoss" class="fw-bold">0.0L</span>
                                </div>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div id="respiratoryBar" class="progress-bar bg-info" style="width: 15%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Fecale (5%)</span>
                                    <span id="fecalLoss" class="fw-bold">0.0L</span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div id="fecalBar" class="progress-bar bg-secondary" style="width: 5%"></div>
                                </div>
                            </div>

                            <!-- Bilan -->
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <span>Pertes totales :</span>
                                        <strong id="totalLosses">0.0L</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Apports recommandes :</span>
                                        <strong class="text-success" id="totalIntake">0.0L</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Balance :</span>
                                        <strong id="balance" class="text-success">+0.0L</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Sources d'Apports Recommandees</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Source</th>
                                            <th>% Total</th>
                                            <th>Volume (L)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="intakeSourcesTable">
                                        <!-- Sera rempli par JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Recommandations -->
                            <div class="alert alert-warning mt-3">
                                <h6 class="fw-bold">
                                    <i class="fas fa-lightbulb me-2"></i>Conseils Pratiques
                                </h6>
                                <ul class="small mb-0">
                                    <li>Repartir les apports tout au long de la journee</li>
                                    <li>Surveiller la couleur des urines (jaune pâle = optimal)</li>
                                    <li>Augmenter les apports par temps chaud/exercice</li>
                                    <li>Ne pas attendre d'avoir soif pour boire</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contenu educatif -->
<section class="py-5">
    <div class="container">
        <!-- Recommandations par Âge -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-users me-2"></i>
                    Recommandations par Âge et Population - Standards 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Besoins Hydriques par Tranche d'Âge (IOM/EFSA)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Âge</th>
                                        <th>Hommes (L/jour)</th>
                                        <th>Femmes (L/jour)</th>
                                        <th>ml/kg/jour</th>
                                        <th>Specificites</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>0-6 mois</td>
                                        <td colspan="2" class="text-center">0.7L (lait maternel)</td>
                                        <td>150ml/kg</td>
                                        <td>Allaitement exclusif</td>
                                    </tr>
                                    <tr>
                                        <td>7-12 mois</td>
                                        <td colspan="2" class="text-center">0.8L</td>
                                        <td>120ml/kg</td>
                                        <td>Lait + diversification</td>
                                    </tr>
                                    <tr>
                                        <td>1-3 ans</td>
                                        <td colspan="2" class="text-center">1.3L</td>
                                        <td>100ml/kg</td>
                                        <td>Surveillance renale immature</td>
                                    </tr>
                                    <tr>
                                        <td>4-8 ans</td>
                                        <td colspan="2" class="text-center">1.7L</td>
                                        <td>80ml/kg</td>
                                        <td>Activite physique croissante</td>
                                    </tr>
                                    <tr>
                                        <td>9-13 ans</td>
                                        <td>2.4L</td>
                                        <td>2.1L</td>
                                        <td>60ml/kg</td>
                                        <td>Puberte, croissance</td>
                                    </tr>
                                    <tr>
                                        <td>14-18 ans</td>
                                        <td>3.3L</td>
                                        <td>2.3L</td>
                                        <td>50ml/kg</td>
                                        <td>Pic croissance, hormones</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td>19-64 ans</td>
                                        <td><strong>3.7L</strong></td>
                                        <td><strong>2.7L</strong></td>
                                        <td><strong>35ml/kg</strong></td>
                                        <td><strong>Adulte reference</strong></td>
                                    </tr>
                                    <tr>
                                        <td>65+ ans</td>
                                        <td>3.7L</td>
                                        <td>2.7L</td>
                                        <td>30ml/kg</td>
                                        <td>Diminution sensation soif</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Grossesse</td>
                                        <td>-</td>
                                        <td><strong>+0.3L (3.0L)</strong></td>
                                        <td>+10ml/kg</td>
                                        <td>Volume sanguin accru</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>Allaitement</td>
                                        <td>-</td>
                                        <td><strong>+0.7L (3.4L)</strong></td>
                                        <td>+20ml/kg</td>
                                        <td>Production lactee</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Populations A Risque</h6>
                        <div class="card border-danger mb-3">
                            <div class="card-header bg-danger text-white">
                                <small>Risque Deshydratation eleve</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Nourrissons (&lt;1 an)</li>
                                    <li>Personnes âgees (&gt;65 ans)</li>
                                    <li>Diabetiques (polyurie)</li>
                                    <li>Insuffisants renaux</li>
                                    <li>Femmes enceintes/allaitantes</li>
                                    <li>Athletes d'endurance</li>
                                    <li>Travailleurs exposes chaleur</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <small>Surveillance Renforcee</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Couleur urine (paille claire)</li>
                                    <li>Frequence mictionnelle (6-8/jour)</li>
                                    <li>Pli cutane (elasticite)</li>
                                    <li>Poids corporel (variations)</li>
                                    <li>Symptômes neurologiques</li>
                                    <li>Pression arterielle</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facteurs d'Influence -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-thermometer-half me-2"></i>
                    Facteurs d'Influence et Ajustements Scientifiques
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Facteurs Environnementaux</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Facteur</th>
                                        <th>Condition</th>
                                        <th>Ajustement</th>
                                        <th>Mecanisme</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Temperature</td>
                                        <td>&gt;25°C</td>
                                        <td>+50ml/°C</td>
                                        <td>Sudation thermoregulation</td>
                                    </tr>
                                    <tr>
                                        <td>Humidite</td>
                                        <td>&gt;70%</td>
                                        <td>+200ml</td>
                                        <td>Inefficacite evaporation</td>
                                    </tr>
                                    <tr>
                                        <td>Altitude</td>
                                        <td>&gt;2500m</td>
                                        <td>+500-750ml</td>
                                        <td>Hyperventilation, diurese</td>
                                    </tr>
                                    <tr>
                                        <td>Froid intense</td>
                                        <td>&lt;0°C</td>
                                        <td>+200-400ml</td>
                                        <td>Diurese induite par froid</td>
                                    </tr>
                                    <tr>
                                        <td>Climatisation</td>
                                        <td>Air sec</td>
                                        <td>+300ml</td>
                                        <td>Pertes respiratoires</td>
                                    </tr>
                                    <tr>
                                        <td>Chauffage</td>
                                        <td>Air sec hiver</td>
                                        <td>+200ml</td>
                                        <td>evaporation cutanee</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Facteurs Physiologiques et Pathologiques</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Condition</th>
                                        <th>Impact</th>
                                        <th>Ajustement</th>
                                        <th>Surveillance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Fievre</td>
                                        <td>+10-15%/°C</td>
                                        <td>+500-750ml</td>
                                        <td>Balance hydro-electrolytique</td>
                                    </tr>
                                    <tr>
                                        <td>Vomissements</td>
                                        <td>Pertes directes</td>
                                        <td>+150ml/episode</td>
                                        <td>electrolytes, pH</td>
                                    </tr>
                                    <tr>
                                        <td>Diarrhee</td>
                                        <td>Pertes massives</td>
                                        <td>+200ml/selle</td>
                                        <td>SRO, hospitalisation</td>
                                    </tr>
                                    <tr>
                                        <td>Diabete</td>
                                        <td>Polyurie osmotique</td>
                                        <td>+500ml</td>
                                        <td>Glycemie, cetonurie</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Insuffisance renale</td>
                                        <td>Retention hydrique</td>
                                        <td><strong>-300-500ml</strong></td>
                                        <td>Creatinine, œdemes</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Insuffisance cardiaque</td>
                                        <td>Congestion</td>
                                        <td><strong>-200-400ml</strong></td>
                                        <td>Poids, dyspnee</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Efficacite des Boissons -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-glass-water me-2"></i>
                    Efficacite des Boissons - Index d'Hydratation 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Index d'Hydratation par Type de Boisson</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Boisson</th>
                                        <th>Index Hydratation</th>
                                        <th>Retention 4h (%)</th>
                                        <th>electrolytes</th>
                                        <th>Recommandation Usage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-primary">
                                        <td><strong>Eau pure</strong></td>
                                        <td>1.00 (reference)</td>
                                        <td>60%</td>
                                        <td>Faible</td>
                                        <td>Hydratation generale</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>Lait ecreme</td>
                                        <td><strong>1.54</strong></td>
                                        <td>78%</td>
                                        <td>Na+, K+, Ca++</td>
                                        <td>Recuperation optimale</td>
                                    </tr>
                                    <tr>
                                        <td>Lait entier</td>
                                        <td>1.50</td>
                                        <td>75%</td>
                                        <td>electrolytes + proteines</td>
                                        <td>Post-exercice prolonge</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>SRO (OMS)</td>
                                        <td>1.46</td>
                                        <td>72%</td>
                                        <td>Na+ 75mmol/L</td>
                                        <td>Rehydratation therapeutique</td>
                                    </tr>
                                    <tr>
                                        <td>Jus orange</td>
                                        <td>1.33</td>
                                        <td>68%</td>
                                        <td>K+ eleve</td>
                                        <td>Apport vitamines</td>
                                    </tr>
                                    <tr>
                                        <td>Boisson sportive</td>
                                        <td>1.20</td>
                                        <td>65%</td>
                                        <td>Na+ 20-30mmol/L</td>
                                        <td>Exercice &gt;1h</td>
                                    </tr>
                                    <tr>
                                        <td>The</td>
                                        <td>1.15</td>
                                        <td>62%</td>
                                        <td>K+, polyphenols</td>
                                        <td>Hydratation quotidienne</td>
                                    </tr>
                                    <tr>
                                        <td>Cafe</td>
                                        <td>1.03</td>
                                        <td>58%</td>
                                        <td>Faible</td>
                                        <td>Moderation (&lt;400mg cafeine)</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Biere (4%)</td>
                                        <td><strong>0.62</strong></td>
                                        <td>35%</td>
                                        <td>Variable</td>
                                        <td>eviter post-exercice</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td>Alcool fort</td>
                                        <td><strong>0.30</strong></td>
                                        <td>15%</td>
                                        <td>Deshydratant</td>
                                        <td>Contre-productif</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Facteurs d'Efficacite</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Osmolalite :</strong> 250-320 mOsm/kg optimale</li>
                                    <li><strong>Sodium :</strong> 20-50 mmol/L pour retention</li>
                                    <li><strong>Potassium :</strong> Restauration intracellulaire</li>
                                    <li><strong>Glucides :</strong> 6-8% facilitent absorption</li>
                                    <li><strong>Proteines :</strong> Retention prolongee</li>
                                    <li><strong>Temperature :</strong> 8-15°C absorption optimale</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card mt-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                <small>Recommandations Sportives</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>&lt;1h exercice :</strong> Eau pure suffisante</li>
                                    <li><strong>1-2h exercice :</strong> Boisson sportive</li>
                                    <li><strong>&gt;2h exercice :</strong> SRO ou lait</li>
                                    <li><strong>Ultra-endurance :</strong> Strategie multi-boissons</li>
                                    <li><strong>Recuperation :</strong> 150% pertes sudorales</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biomarqueurs -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-microscope me-2"></i>
                    Biomarqueurs et evaluation du Statut Hydrique
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Biomarqueurs de l'etat d'Hydratation</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Biomarqueur</th>
                                        <th>Methode</th>
                                        <th>Euhydratation</th>
                                        <th>Deshydratation Legere</th>
                                        <th>Deshydratation Severe</th>
                                        <th>Precision</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td>Osmolalite plasmatique</td>
                                        <td>Osmometre</td>
                                        <td>275-295 mOsm/kg</td>
                                        <td>295-305 mOsm/kg</td>
                                        <td>&gt;305 mOsm/kg</td>
                                        <td><strong>Gold standard</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Osmolalite urinaire</td>
                                        <td>Refractometre</td>
                                        <td>&lt;700 mOsm/kg</td>
                                        <td>700-1000 mOsm/kg</td>
                                        <td>&gt;1000 mOsm/kg</td>
                                        <td>Tres bonne</td>
                                    </tr>
                                    <tr>
                                        <td>Densite urinaire</td>
                                        <td>Densimetre</td>
                                        <td>&lt;1.020</td>
                                        <td>1.020-1.025</td>
                                        <td>&gt;1.025</td>
                                        <td>Bonne</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Couleur urinaire</strong></td>
                                        <td>echelle 1-8</td>
                                        <td><strong>1-3 (paille claire)</strong></td>
                                        <td>4-5 (jaune)</td>
                                        <td>6-8 (fonce)</td>
                                        <td>Pratique</td>
                                    </tr>
                                    <tr>
                                        <td>Volume urinaire</td>
                                        <td>Mesure directe</td>
                                        <td>&gt;1.2 L/24h</td>
                                        <td>0.8-1.2 L/24h</td>
                                        <td>&lt;0.8 L/24h</td>
                                        <td>Variable</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>Perte de poids</strong></td>
                                        <td>Balance precise</td>
                                        <td>&lt;1%</td>
                                        <td><strong>1-3%</strong></td>
                                        <td><strong>&gt;3%</strong></td>
                                        <td><strong>Excellente</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Pli cutane</td>
                                        <td>Test clinique</td>
                                        <td>&lt;2 secondes</td>
                                        <td>2-4 secondes</td>
                                        <td>&gt;4 secondes</td>
                                        <td>Moderee</td>
                                    </tr>
                                    <tr>
                                        <td>Soif subjective</td>
                                        <td>echelle 1-9</td>
                                        <td>1-3</td>
                                        <td>4-6</td>
                                        <td>7-9</td>
                                        <td>Retardee</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Consequences de la Deshydratation</h6>
                        <div class="card border-warning mb-3">
                            <div class="card-header bg-warning text-dark">
                                <small>Deshydratation 1-2% Poids Corporel</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Diminution performance physique</li>
                                    <li>Alteration thermoregulation</li>
                                    <li>Fatigue precoce</li>
                                    <li>Concentration reduite</li>
                                    <li>Humeur alteree</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-danger mb-3">
                            <div class="card-header bg-danger text-white">
                                <small>Deshydratation 3-5% Poids Corporel</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Chute performance 20-30%</li>
                                    <li>Hyperthermie dangereuse</li>
                                    <li>Troubles cardiovasculaires</li>
                                    <li>Crampes musculaires</li>
                                    <li>Nausees, cephalees</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-dark">
                            <div class="card-header bg-dark text-white">
                                <small>Deshydratation &gt;5% - URGENCE</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Collapsus cardiovasculaire</li>
                                    <li>Insuffisance renale aiguë</li>
                                    <li>Troubles neurologiques</li>
                                    <li>Choc hypovolemique</li>
                                    <li>Pronostic vital engage</li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                            <p class="small mb-0">
                                En cas de pathologie chronique, consulter un professionnel 
                                de sante avant de modifier significativement ses apports hydriques.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommandations Pratiques -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Recommandations Pratiques pour une Hydratation Optimale
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Hydratation Quotidienne</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Reveil :</strong> 250-500ml au lever</li>
                                    <li><strong>Avant repas :</strong> 250ml (30min avant)</li>
                                    <li><strong>Repartition :</strong> Toutes les heures</li>
                                    <li><strong>Surveillance :</strong> Couleur urine</li>
                                    <li><strong>Adaptation :</strong> Selon saison/climat</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Hydratation Sportive</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Pre-exercice :</strong> 5-7ml/kg (2-4h avant)</li>
                                    <li><strong>Pendant :</strong> 150-250ml/15-20min</li>
                                    <li><strong>Post-exercice :</strong> 150% pertes sudorales</li>
                                    <li><strong>Temperature :</strong> 8-15°C optimal</li>
                                    <li><strong>Monitoring :</strong> Perte de poids</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-danger h-100">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Situations Speciales</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Fievre :</strong> +500-750ml/jour</li>
                                    <li><strong>Altitude :</strong> +500ml si &gt;2500m</li>
                                    <li><strong>Chaleur :</strong> +50ml par °C &gt;25°C</li>
                                    <li><strong>Vol long :</strong> 150ml/h de vol</li>
                                    <li><strong>Âge :</strong> Surveillance renforcee &gt;65 ans</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success mt-4">
                    <h6><i class="fas fa-check-circle me-2"></i>Regles d'Or de l'Hydratation</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <ul class="small mb-0">
                                <li>Boire avant d'avoir soif</li>
                                <li>Repartir les apports sur la journee</li>
                                <li>Adapter selon l'activite et le climat</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="small mb-0">
                                <li>Surveiller les signes de deshydratation</li>
                                <li>Privilegier l'eau pour l'hydratation generale</li>
                                <li>Consulter en cas de pathologie chronique</li>
                            </ul>
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

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.progress {
    background-color: #e9ecef;
}

#pregnancyContainer {
    transition: all 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
// Configuration des besoins de base selon IOM/EFSA
const baseNeedsConfig = {
    male: {
        19: 3.7,   // 19+ ans
        14: 3.3,   // 14-18 ans
        9: 2.4,    // 9-13 ans
        default: 1.7 // 4-8 ans
    },
    female: {
        19: 2.7,   // 19+ ans
        14: 2.3,   // 14-18 ans
        9: 2.1,    // 9-13 ans
        default: 1.5 // 4-8 ans
    }
};

// Facteurs d'intensite d'activite (litres par heure)
const intensityFactors = {
    light: 0.4,     // 400ml/h
    moderate: 0.6,  // 600ml/h  
    intense: 0.8,   // 800ml/h
    extreme: 1.2    // 1200ml/h
};

// Gestion affichage grossesse/allaitement
document.getElementById('gender').addEventListener('change', function() {
    const pregnancyContainer = document.getElementById('pregnancyContainer');
    if (this.value === 'female') {
        pregnancyContainer.style.display = 'block';
    } else {
        pregnancyContainer.style.display = 'none';
        document.getElementById('pregnancyLactation').checked = false;
    }
});

function getBaseNeeds(age, gender) {
    const config = baseNeedsConfig[gender];
    
    if (age >= 19) return config[19];
    if (age >= 14) return config[14];
    if (age >= 9) return config[9];
    return config.default;
}

function calculateHydration() {
    // Recuperation des valeurs
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    const age = parseInt(document.getElementById('age').value);
    const gender = document.getElementById('gender').value;
    const activityHours = parseFloat(document.getElementById('activityHours').value) || 0;
    const activityIntensity = document.getElementById('activityIntensity').value;
    const temperature = parseFloat(document.getElementById('temperature').value) || 20;
    const humidity = parseFloat(document.getElementById('humidity').value) || 50;
    const altitude = parseFloat(document.getElementById('altitude').value) || 0;
    const healthCondition = document.getElementById('healthCondition').value;
    const pregnancyLactation = document.getElementById('pregnancyLactation').checked;
    
    // Validation
    const errorDiv = document.getElementById('errorMessage');
    if (!weight || !age) {
        errorDiv.textContent = "Veuillez saisir au minimum votre poids et votre âge.";
        errorDiv.classList.remove('d-none');
        document.getElementById('results').classList.add('d-none');
        return;
    }
    
    if (weight < 30 || weight > 200) {
        errorDiv.textContent = "Le poids doit être compris entre 30 et 200 kg.";
        errorDiv.classList.remove('d-none');
        document.getElementById('results').classList.add('d-none');
        return;
    }
    
    if (age < 5 || age > 100) {
        errorDiv.textContent = "L'âge doit être compris entre 5 et 100 ans.";
        errorDiv.classList.remove('d-none');
        document.getElementById('results').classList.add('d-none');
        return;
    }
    
    // Masquer les erreurs
    errorDiv.classList.add('d-none');
    
    // Calcul base selon methode IOM
    let baseNeeds = getBaseNeeds(age, gender);
    
    // Ajustement grossesse/allaitement
    if (pregnancyLactation && gender === 'female') {
        baseNeeds += 0.5; // Moyenne grossesse (+0.3L) et allaitement (+0.7L)
    }
    
    // Calcul alternatif base sur poids
    const weightBasedNeeds = age >= 65 ? (weight * 30) / 1000 : (weight * 35) / 1000;
    
    // Ajustements activite physique
    const activityAdjustment = activityHours * intensityFactors[activityIntensity];
    
    // Ajustements climatiques
    let climateAdjustment = 0;
    if (temperature > 25) {
        climateAdjustment += (temperature - 25) * 0.05; // +50ml par degre >25°C
    }
    if (humidity > 70) {
        climateAdjustment += 0.2; // +200ml si humidite elevee
    }
    if (altitude > 2500) {
        climateAdjustment += 0.5; // +500ml en altitude
    }
    
    // Ajustements pathologiques
    let healthAdjustment = 0;
    switch (healthCondition) {
        case 'fever': healthAdjustment = 0.6; break; // +600ml fievre
        case 'diabetes': healthAdjustment = 0.5; break; // +500ml diabete
        case 'kidney': healthAdjustment = -0.4; break; // -400ml insuffisance renale
        case 'heart': healthAdjustment = -0.3; break; // -300ml insuffisance cardiaque
        default: healthAdjustment = 0;
    }
    
    // Total besoins
    const totalNeedsIOM = baseNeeds + activityAdjustment + climateAdjustment + healthAdjustment;
    const totalNeedsWeight = weightBasedNeeds + activityAdjustment + climateAdjustment + healthAdjustment;
    
    // Repartition dans la journee
    const hourlyIntake = (totalNeedsIOM / 16) * 1000; // Sur 16h eveillees, en ml
    
    // Protocole sportif
    const preWorkout = activityHours > 0 ? 0.5 : 0;
    const duringWorkout = activityHours * intensityFactors[activityIntensity];
    const postWorkout = activityHours > 0 ? 0.75 : 0; // 150% regle
    
    // Statut hydratation
    let hydrationStatus = 'Optimal';
    let statusColor = 'success';
    if (totalNeedsIOM < 1.5) {
        hydrationStatus = 'Insuffisant';
        statusColor = 'danger';
    } else if (totalNeedsIOM > 5) {
        hydrationStatus = 'Risque surhydratation';
        statusColor = 'warning';
    } else if (healthCondition === 'kidney' || healthCondition === 'heart') {
        hydrationStatus = 'Surveillance medicale';
        statusColor = 'info';
    }
    
    // Pertes hydriques estimees
    const urinaryLoss = totalNeedsIOM * 0.6; // 60%
    const respiratoryLoss = totalNeedsIOM * 0.15; // 15%
    const skinLoss = (totalNeedsIOM * 0.20) + (activityAdjustment * 0.8); // 20% + sudation
    const fecalLoss = totalNeedsIOM * 0.05; // 5%
    const totalLosses = urinaryLoss + respiratoryLoss + skinLoss + fecalLoss;
    
    // Affichage des resultats
    displayResults({
        totalNeedsIOM: Math.round(totalNeedsIOM * 100) / 100,
        totalNeedsWeight: Math.round(totalNeedsWeight * 100) / 100,
        baseNeeds: Math.round(baseNeeds * 100) / 100,
        activityAdjustment: Math.round(activityAdjustment * 100) / 100,
        climateAdjustment: Math.round(climateAdjustment * 100) / 100,
        healthAdjustment: Math.round(healthAdjustment * 100) / 100,
        hourlyIntake: Math.round(hourlyIntake),
        preWorkout: Math.round(preWorkout * 100) / 100,
        duringWorkout: Math.round(duringWorkout * 100) / 100,
        postWorkout: Math.round(postWorkout * 100) / 100,
        hydrationStatus,
        statusColor,
        losses: {
            urinary: Math.round(urinaryLoss * 100) / 100,
            respiratory: Math.round(respiratoryLoss * 100) / 100,
            skin: Math.round(skinLoss * 100) / 100,
            fecal: Math.round(fecalLoss * 100) / 100,
            total: Math.round(totalLosses * 100) / 100
        },
        hasActivity: activityHours > 0
    });
}

function displayResults(results) {
    // Resultats principaux
    document.getElementById('mainResults').innerHTML = `
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-header bg-primary text-white text-center">
                    <h6 class="mb-0">Besoins IOM</h6>
                    <small>Institute of Medicine</small>
                </div>
                <div class="card-body text-center">
                    <p class="card-text fs-3">
                        <strong class="text-primary">${results.totalNeedsIOM}L</strong>
                        <small class="d-block text-muted">par jour</small>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-header bg-info text-white text-center">
                    <h6 class="mb-0">Methode Poids</h6>
                    <small>35ml/kg (30ml/kg +65ans)</small>
                </div>
                <div class="card-body text-center">
                    <p class="card-text fs-3">
                        <strong class="text-info">${results.totalNeedsWeight}L</strong>
                        <small class="d-block text-muted">par jour</small>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-header bg-warning text-dark text-center">
                    <h6 class="mb-0">Apport Horaire</h6>
                    <small>16h eveillees</small>
                </div>
                <div class="card-body text-center">
                    <p class="card-text fs-3">
                        <strong class="text-warning">${results.hourlyIntake}ml</strong>
                        <small class="d-block text-muted">par heure</small>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-${results.statusColor} h-100">
                <div class="card-header bg-${results.statusColor} ${results.statusColor === 'warning' ? 'text-dark' : 'text-white'} text-center">
                    <h6 class="mb-0">Statut</h6>
                    <small>evaluation</small>
                </div>
                <div class="card-body text-center">
                    <p class="card-text fs-6">
                        <strong class="text-${results.statusColor}">${results.hydrationStatus}</strong>
                    </p>
                </div>
            </div>
        </div>
    `;
    
    // Repartition des ajustements
    document.getElementById('adjustmentsBreakdown').innerHTML = `
        <div class="col-md-3">
            <div class="d-flex justify-content-between">
                <span>Base :</span>
                <strong class="text-primary">${results.baseNeeds}L</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex justify-content-between">
                <span>Activite :</span>
                <strong class="text-warning">+${results.activityAdjustment}L</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex justify-content-between">
                <span>Climat :</span>
                <strong class="text-danger">+${results.climateAdjustment}L</strong>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex justify-content-between">
                <span>Sante :</span>
                <strong class="${results.healthAdjustment >= 0 ? 'text-success' : 'text-secondary'}">
                    ${results.healthAdjustment >= 0 ? '+' : ''}${results.healthAdjustment}L
                </strong>
            </div>
        </div>
    `;
    
    // Protocole sportif
    if (results.hasActivity) {
        document.getElementById('sportsProtocol').classList.remove('d-none');
        document.getElementById('sportsBreakdown').innerHTML = `
            <div class="col-md-4">
                <strong>Pre-exercice :</strong> ${results.preWorkout}L (2-3h avant)
            </div>
            <div class="col-md-4">
                <strong>Pendant exercice :</strong> ${results.duringWorkout}L
            </div>
            <div class="col-md-4">
                <strong>Post-exercice :</strong> ${results.postWorkout}L
            </div>
        `;
    } else {
        document.getElementById('sportsProtocol').classList.add('d-none');
    }
    
    // Balance hydrique
    document.getElementById('urinaryLoss').textContent = `${results.losses.urinary}L`;
    document.getElementById('skinLoss').textContent = `${results.losses.skin}L`;
    document.getElementById('respiratoryLoss').textContent = `${results.losses.respiratory}L`;
    document.getElementById('fecalLoss').textContent = `${results.losses.fecal}L`;
    document.getElementById('totalLosses').textContent = `${results.losses.total}L`;
    document.getElementById('totalIntake').textContent = `${results.totalNeedsIOM}L`;
    
    const balance = results.totalNeedsIOM - results.losses.total;
    document.getElementById('balance').textContent = `${balance >= 0 ? '+' : ''}${Math.round(balance * 100) / 100}L`;
    document.getElementById('balance').className = balance >= 0 ? 'text-success' : 'text-danger';
    
    // Mise A jour des barres de progression
    const maxLoss = Math.max(results.losses.urinary, results.losses.skin, results.losses.respiratory, results.losses.fecal);
    document.getElementById('urinaryBar').style.width = `${(results.losses.urinary / results.losses.total) * 100}%`;
    document.getElementById('skinBar').style.width = `${(results.losses.skin / results.losses.total) * 100}%`;
    document.getElementById('respiratoryBar').style.width = `${(results.losses.respiratory / results.losses.total) * 100}%`;
    document.getElementById('fecalBar').style.width = `${(results.losses.fecal / results.losses.total) * 100}%`;
    
    // Sources d'apports
    document.getElementById('intakeSourcesTable').innerHTML = `
        <tr>
            <td>Boissons diverses</td>
            <td>80%</td>
            <td>${(results.totalNeedsIOM * 0.8).toFixed(1)}L</td>
        </tr>
        <tr>
            <td>Eau alimentaire</td>
            <td>20%</td>
            <td>${(results.totalNeedsIOM * 0.2).toFixed(1)}L</td>
        </tr>
        <tr>
            <td>Eau metabolique</td>
            <td>~300ml</td>
            <td>0.3L</td>
        </tr>
    `;
    
    // Afficher les resultats
    document.getElementById('results').classList.remove('d-none');
    document.getElementById('results').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function resetForm() {
    document.getElementById('weight').value = '';
    document.getElementById('height').value = '';
    document.getElementById('age').value = '';
    document.getElementById('gender').value = 'male';
    document.getElementById('activityHours').value = '';
    document.getElementById('activityIntensity').value = 'moderate';
    document.getElementById('temperature').value = '';
    document.getElementById('humidity').value = '';
    document.getElementById('altitude').value = '';
    document.getElementById('healthCondition').value = 'healthy';
    document.getElementById('pregnancyLactation').checked = false;
    
    document.getElementById('pregnancyContainer').style.display = 'none';
    document.getElementById('results').classList.add('d-none');
    document.getElementById('errorMessage').classList.add('d-none');
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Masquer le conteneur grossesse par defaut
    document.getElementById('pregnancyContainer').style.display = 'none';
});
</script>
@endpush