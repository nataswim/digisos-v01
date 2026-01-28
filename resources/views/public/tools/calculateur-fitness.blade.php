@extends('layouts.public')

@section('title', 'Calculateur Fitness & Frequence Cardiaque - Zones d\'Entraînement ')
@section('meta_description', 'Optimisez votre entraînement avec notre calculateur fitness scientifique : FC max, zones cardiaques, VO2 max estime. Formules validees Tanaka, Gellish, Karvonen pour un entraînement sain et efficace.')

@section('content')

<!-- Section titre -->
<section class="py-5 text-center nataswim-titre3">

<div class="container-lg">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-2 mb-lg-0" style=" background-color: #f9f5f4; color: #63d0c7; padding: 20px 0px; border-radius: 10px; ">
                <h1 class="display-5 fw-bold mb-3">Fit Calculateur</h1>
                <p>Optimisez votre entraînement avec notre calculateur fitness</p>
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
            <div class="card-body p-5">
                <h3 class="text-center mb-4">Parametres Personnels</h3>
                
                <!-- Messages d'erreur -->
                <div id="errorMessage" class="alert alert-danger d-none">
                    <!-- Sera rempli par JavaScript -->
                </div>
                
                <!-- Formulaire de saisie -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-calendar me-2 text-primary"></i>Âge
                        </label>
                        <input type="number" id="age" class="form-control form-control-lg border-primary" 
                               placeholder="30" min="10" max="100">
                        <small class="text-muted">Entre 10 et 100 ans</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-heart me-2 text-danger"></i>FC au Repos (bpm)
                        </label>
                        <input type="number" id="restingHR" class="form-control form-control-lg border-danger" 
                               placeholder="65" min="30" max="100">
                        <small class="text-muted">Mesurez au reveil, au calme</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-weight me-2 text-success"></i>Poids (kg)
                        </label>
                        <input type="number" id="weight" class="form-control form-control-lg border-success" 
                               placeholder="70" min="30" step="0.1">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-ruler-vertical me-2 text-info"></i>Taille (cm)
                        </label>
                        <input type="number" id="height" class="form-control form-control-lg border-info" 
                               placeholder="175" min="100" max="250">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-venus-mars me-2 text-warning"></i>Sexe
                        </label>
                        <select id="gender" class="form-select form-select-lg border-warning">
                            <option value="male">Homme</option>
                            <option value="female">Femme</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">
                            <i class="fas fa-running me-2 text-secondary"></i>Niveau de Forme
                        </label>
                        <select id="fitnessLevel" class="form-select form-select-lg border-secondary">
                            <option value="below-average">En dessous de la moyenne</option>
                            <option value="average" selected>Moyenne</option>
                            <option value="above-average">Au-dessus de la moyenne</option>
                            <option value="excellent">Excellent</option>
                        </select>
                    </div>
                </div>

                <!-- Configuration FC Max -->
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-cog me-2"></i>Configuration FC Maximale
                        </h6>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="useKnownMax">
                            <label class="form-check-label fw-bold" for="useKnownMax">
                                J'ai une FC Max mesuree precisement
                            </label>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6" id="knownMaxField" style="display: none;">
                                <label class="fw-bold mb-2">FC Max connue (bpm)</label>
                                <input type="number" id="maxHRKnown" class="form-control border-primary" 
                                       placeholder="185" min="100" max="220">
                                <small class="text-muted">Mesuree lors d'un test d'effort</small>
                            </div>
                            
                            <div class="col-md-6" id="formulaField">
                                <label class="fw-bold mb-2">Formule d'estimation</label>
                                <select id="formula" class="form-select border-primary">
                                    <option value="tanaka">Tanaka (208 - 0.7 × Âge) - Recommandee</option>
                                    <option value="gellish">Gellish (207 - 0.7 × Âge)</option>
                                    <option value="roberts">Roberts (205 - 0.5 × Âge) - Athletes</option>
                                    <option value="nes">Nes (211 - 0.64 × Âge)</option>
                                    <option value="astrand">Åstrand (220 - Âge) - Moins precise</option>
                                    <option value="oakland">Oakland (206 - 0.88 × Âge) - Femmes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Intensite cible -->
                <div class="mb-4">
                    <label class="fw-bold mb-3 d-block">
                        <i class="fas fa-bullseye me-2 text-primary"></i>
                        Intensite d'entraînement cible : <span id="intensityValue" class="text-primary">70%</span>
                    </label>
                    <input type="range" id="intensity" class="form-range" 
                           min="50" max="100" step="5" value="70">
                    <div class="d-flex justify-content-between text-muted small">
                        <span>50% (Recuperation)</span>
                        <span>70% (Aerobie)</span>
                        <span>85% (Seuil)</span>
                        <span>100% (Maximal)</span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg px-4 py-3 fw-bold w-100" onclick="calculateFitness()">
                            <i class="fas fa-calculator me-2"></i>Analyser ma condition
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
                    <div class="alert alert-success shadow-sm">
                        <h5 class="alert-heading text-center mb-4">
                            <i class="fas fa-chart-pie me-2"></i>Analyse de votre Condition Physique
                        </h5>
                        
                        <div class="row g-3 mb-4" id="metricsCards">
                            <!-- Sera rempli par JavaScript -->
                        </div>

                        <!-- Zones d'entraînement -->
                        <h6 class="text-center mb-3">
                            <i class="fas fa-layer-group me-2"></i>Vos Zones d'Entraînement Cardiaques
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Zone</th>
                                        <th class="text-center">% FC Max</th>
                                        <th class="text-center">Plage (bpm)</th>
                                        <th class="text-center">Objectif</th>
                                        <th class="text-center">Sensation</th>
                                    </tr>
                                </thead>
                                <tbody id="zonesTableBody">
                                    <!-- Sera rempli par JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-water me-2"></i>
                            <small>
                                Ces zones sont calculees selon la methode Karvonen (reserve cardiaque) pour plus de precision. 
                                Adaptez selon votre ressenti et consultez un professionnel pour un programme personnalise.
                            </small>
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
        <!-- Frequence Cardiaque Maximale -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-heart me-2"></i>
                    Frequence Cardiaque Maximale - Recherches 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Definition :</strong> La FC Max est le nombre maximal de battements que votre cœur peut 
                    effectuer par minute lors d'un effort intense. C'est la base pour definir vos zones d'entraînement.
                </div>
                
                <h6>Formules de Calcul Validees Scientifiquement</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Formule</th>
                                <th>equation</th>
                                <th>Fiabilite</th>
                                <th>Population Cible</th>
                                <th>Annee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-success">
                                <td><strong>Tanaka et al.</strong></td>
                                <td>208 - (0.7 × Âge)</td>
                                <td><span class="badge bg-success">elevee</span></td>
                                <td>Adultes sains 18-81 ans</td>
                                <td>2001</td>
                            </tr>
                            <tr class="table-primary">
                                <td><strong>Gellish et al.</strong></td>
                                <td>207 - (0.7 × Âge)</td>
                                <td><span class="badge bg-primary">elevee</span></td>
                                <td>Adultes en bonne sante</td>
                                <td>2007</td>
                            </tr>
                            <tr class="table-info">
                                <td><strong>Roberts & Landwehr</strong></td>
                                <td>205 - (0.5 × Âge)</td>
                                <td><span class="badge bg-info">Moderee</span></td>
                                <td>Athletes experimentes</td>
                                <td>2002</td>
                            </tr>
                            <tr class="table-secondary">
                                <td><strong>Nes et al.</strong></td>
                                <td>211 - (0.64 × Âge)</td>
                                <td><span class="badge bg-secondary">elevee</span></td>
                                <td>Adultes en bonne sante</td>
                                <td>2013</td>
                            </tr>
                            <tr class="table-warning">
                                <td><strong>Åstrand</strong></td>
                                <td>220 - Âge</td>
                                <td><span class="badge bg-warning text-dark">Faible</span></td>
                                <td>Formule historique</td>
                                <td>1954</td>
                            </tr>
                            <tr>
                                <td><strong>Oakland (Femmes)</strong></td>
                                <td>206 - (0.88 × Âge)</td>
                                <td><span class="badge bg-secondary">Moderee</span></td>
                                <td>Femmes specifiquement</td>
                                <td>2003</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-success">
                    <h6>Recommandation Scientifique 2024</h6>
                    <p class="mb-0">
                        La <strong>formule de Tanaka</strong> est actuellement consideree comme la plus precise 
                        pour la population generale. Cependant, un test d'effort maximal reste l'etalon-or 
                        pour une mesure precise.
                    </p>
                </div>
            </div>
        </div>

        <!-- Zones d'Entraînement -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-layer-group me-2"></i>
                    Zones d'Entraînement Cardiaque - Methode Karvonen
                </h3>
            </div>
            <div class="card-body">
                <p>
                    Les zones d'entraînement sont calculees selon la <strong>methode Karvonen</strong>, 
                    plus precise que le simple pourcentage de FC Max car elle prend en compte votre FC de repos.
                </p>
                
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h6>Formule Karvonen :</h6>
                        <code>FC Cible = FC Repos + ((FC Max - FC Repos) × % Intensite)</code>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Zone 1 : Tres Legere</h6>
                                <small>50-60% FC Reserve</small>
                            </div>
                            <div class="card-body">
                                <p><strong>Objectif :</strong> Recuperation active, echauffement</p>
                                <p><strong>Sensation :</strong> Tres facile, conversation fluide</p>
                                <p><strong>Duree :</strong> 20-90 minutes</p>
                                <p><strong>Benefices :</strong> Circulation, bien-être general</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Zone 2 : Legere</h6>
                                <small>60-70% FC Reserve</small>
                            </div>
                            <div class="card-body">
                                <p><strong>Objectif :</strong> Endurance de base, combustion graisses</p>
                                <p><strong>Sensation :</strong> Facile, conversation possible</p>
                                <p><strong>Duree :</strong> 30-120 minutes</p>
                                <p><strong>Benefices :</strong> Base aerobie, metabolisme lipidique</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Zone 3 : Moderee</h6>
                                <small>70-80% FC Reserve</small>
                            </div>
                            <div class="card-body">
                                <p><strong>Objectif :</strong> Amelioration endurance, capacite aerobie</p>
                                <p><strong>Sensation :</strong> Moderee, conversation difficile</p>
                                <p><strong>Duree :</strong> 20-60 minutes</p>
                                <p><strong>Benefices :</strong> Efficacite cardiovasculaire</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-6">
                        <div class="card border-danger h-100">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Zone 4 : Intense</h6>
                                <small>80-90% FC Reserve</small>
                            </div>
                            <div class="card-body">
                                <p><strong>Objectif :</strong> Amelioration VMA, capacite anaerobie</p>
                                <p><strong>Sensation :</strong> Difficile, quelques mots seulement</p>
                                <p><strong>Duree :</strong> 8-40 minutes (fractionne)</p>
                                <p><strong>Benefices :</strong> Puissance aerobie, seuil lactique</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-6">
                        <div class="card border-dark h-100">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Zone 5 : Maximale</h6>
                                <small>90-100% FC Reserve</small>
                            </div>
                            <div class="card-body">
                                <p><strong>Objectif :</strong> Puissance maximale, vitesse</p>
                                <p><strong>Sensation :</strong> Extrêmement difficile</p>
                                <p><strong>Duree :</strong> 30 secondes - 8 minutes</p>
                                <p><strong>Benefices :</strong> Puissance neuromusculaire</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- VO2 Max et Condition Physique -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lungs me-2"></i>
                    VO2 Max et evaluation de la Condition Physique
                </h3>
            </div>
            <div class="card-body">
                <p>
                    Le VO2 Max represente la quantite maximale d'oxygene que votre corps peut utiliser 
                    par minute et par kilogramme de poids corporel. C'est le meilleur indicateur de 
                    votre capacite cardiorespiratoire.
                </p>
                
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6>Classification VO2 Max par Âge et Sexe</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Categorie</th>
                                        <th>Hommes 20-29</th>
                                        <th>Femmes 20-29</th>
                                        <th>Hommes 30-39</th>
                                        <th>Femmes 30-39</th>
                                        <th>Niveau</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success">
                                        <td><strong>elite</strong></td>
                                        <td>&gt; 60</td>
                                        <td>&gt; 56</td>
                                        <td>&gt; 56</td>
                                        <td>&gt; 52</td>
                                        <td>Athletes de haut niveau</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Excellent</strong></td>
                                        <td>52-60</td>
                                        <td>47-56</td>
                                        <td>48-56</td>
                                        <td>44-52</td>
                                        <td>Tres bonne forme</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td><strong>Bon</strong></td>
                                        <td>43-51</td>
                                        <td>39-46</td>
                                        <td>40-47</td>
                                        <td>36-43</td>
                                        <td>Bonne condition</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>Moyen</strong></td>
                                        <td>35-42</td>
                                        <td>31-38</td>
                                        <td>32-39</td>
                                        <td>28-35</td>
                                        <td>Condition moyenne</td>
                                    </tr>
                                    <tr class="table-danger">
                                        <td><strong>Faible</strong></td>
                                        <td>&lt; 35</td>
                                        <td>&lt; 31</td>
                                        <td>&lt; 32</td>
                                        <td>&lt; 28</td>
                                        <td>Amelioration necessaire</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Facteurs d'Influence</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Genetique :</strong> 25-50% hereditaire</li>
                                    <li><strong>Entraînement :</strong> Amelioration 15-25%</li>
                                    <li><strong>Âge :</strong> Declin ~1%/an apres 25 ans</li>
                                    <li><strong>Sexe :</strong> Hommes +10-15% en moyenne</li>
                                    <li><strong>Poids corporel :</strong> Impact sur valeurs relatives</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mt-3">
                            <small>
                                <strong>Note :</strong> L'estimation VO2 Max par formules est approximative. 
                                Un test en laboratoire reste l'etalon-or.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variabilite Frequence Cardiaque -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-chart-line me-2"></i>
                    Variabilite de la Frequence Cardiaque (HRV)
                </h3>
            </div>
            <div class="card-body">
                <p>
                    La HRV mesure les variations temporelles entre chaque battement cardiaque. 
                    Elle reflete l'equilibre entre les systemes nerveux sympathique et parasympathique.
                </p>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Indicateurs Positifs</h6>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li><strong>HRV elevee :</strong> Bonne recuperation</li>
                                    <li><strong>Variabilite stable :</strong> Adaptation optimale</li>
                                    <li><strong>Amelioration progressive :</strong> Forme en hausse</li>
                                </ul>
                                <div class="alert alert-success alert-sm">
                                    <small>Une HRV elevee indique generalement un systeme nerveux equilibre</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Signaux d'Alerte</h6>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li><strong>HRV tres basse :</strong> Fatigue, stress</li>
                                    <li><strong>Chute brutale :</strong> Sur-entraînement possible</li>
                                    <li><strong>Stagnation :</strong> Besoin d'adaptation</li>
                                </ul>
                                <div class="alert alert-danger alert-sm">
                                    <small>Consultez un professionnel si la HRV reste durablement basse</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h6 class="mt-4">Applications Pratiques</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Surveillance Quotidienne</h6>
                                <p class="small">Mesure matinale pour adapter l'entraînement du jour</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Prevention Sur-entraînement</h6>
                                <p class="small">Detection precoce de la fatigue excessive</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>Optimisation Recuperation</h6>
                                <p class="small">Ajustement des periodes de repos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommandations Pratiques -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Recommandations pour un Entraînement Sain
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h6>Principes d'Entraînement equilibre</h6>
                    <p class="mb-0">
                        Un entraînement efficace respecte la regle <strong>80/20</strong> : 
                        80% du temps dans les zones 1-2 (facile), 20% dans les zones 3-5 (intense).
                    </p>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Debutants</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Commencer par zones 1-2 exclusivement</li>
                                    <li>Progression graduelle sur 8-12 semaines</li>
                                    <li>ecoute des signaux corporels</li>
                                    <li>Recuperation entre seances</li>
                                    <li>Consulter un professionnel</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Intermediaires</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Base solide en zones 1-2 (80%)</li>
                                    <li>Introduction progressive zone 3</li>
                                    <li>1-2 seances intenses/semaine max</li>
                                    <li>Surveillance de la recuperation</li>
                                    <li>Periodisation des charges</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Avances</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Planification structuree</li>
                                    <li>Utilisation de toutes les zones</li>
                                    <li>Monitoring HRV et FC repos</li>
                                    <li>Cycles de surcompensation</li>
                                    <li>Suivi professionnel recommande</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger mt-4">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Signaux d'Alarme</h6>
                    <p class="mb-2">Consultez un professionnel de sante si vous ressentez :</p>
                    <ul class="mb-0">
                        <li>Douleurs thoraciques ou palpitations anormales</li>
                        <li>Essoufflement excessif au repos ou effort leger</li>
                        <li>Vertiges, malaises ou evanouissements</li>
                        <li>FC anormalement elevee ou basse</li>
                        <li>Fatigue chronique inexpliquee</li>
                    </ul>
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

.form-range::-webkit-slider-thumb {
    background: #0d6efd;
}

.form-range::-moz-range-thumb {
    background: #0d6efd;
    border: none;
}

.alert-sm {
    padding: 0.5rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
// Configuration des formules FC Max
const hrMaxFormulas = {
    'tanaka': (age, gender) => 208 - (0.7 * age),
    'gellish': (age, gender) => 207 - (0.7 * age),
    'roberts': (age, gender) => 205 - (0.5 * age),
    'nes': (age, gender) => 211 - (0.64 * age),
    'astrand': (age, gender) => 220 - age,
    'oakland': (age, gender) => gender === 'female' ? 206 - (0.88 * age) : 220 - age
};

// Mise A jour de l'affichage de l'intensite
document.getElementById('intensity').addEventListener('input', function() {
    document.getElementById('intensityValue').textContent = this.value + '%';
});

// Gestion de l'affichage FC Max connue
document.getElementById('useKnownMax').addEventListener('change', function() {
    const knownField = document.getElementById('knownMaxField');
    const formulaField = document.getElementById('formulaField');
    
    if (this.checked) {
        knownField.style.display = 'block';
        formulaField.style.display = 'none';
    } else {
        knownField.style.display = 'none';
        formulaField.style.display = 'block';
    }
});

function calculateMaxHeartRate(age, gender, formula, useKnownMax, knownMax) {
    if (useKnownMax && knownMax) {
        return parseFloat(knownMax);
    }
    
    const ageNum = parseInt(age);
    if (isNaN(ageNum)) return null;
    
    return hrMaxFormulas[formula](ageNum, gender);
}

function calculateTargetHR(maxHR, restingHR, intensity) {
    // Methode Karvonen (Reserve cardiaque)
    const hrReserve = maxHR - restingHR;
    return Math.round(restingHR + (hrReserve * (intensity / 100)));
}

function estimateVO2Max(age, gender, fitnessLevel) {
    let baseVO2 = 40;
    
    // Ajustement par sexe
    if (gender === 'male') {
        baseVO2 += 5;
    } else {
        baseVO2 -= 2;
    }
    
    // Ajustement par âge (declin avec l'âge)
    const ageNum = parseInt(age);
    if (ageNum > 30) {
        baseVO2 -= Math.floor((ageNum - 30) / 5) * 2;
    }
    
    // Ajustement par niveau de forme
    const adjustments = {
        'below-average': -8,
        'average': 0,
        'above-average': +8,
        'excellent': +15
    };
    
    baseVO2 += adjustments[fitnessLevel] || 0;
    
    return Math.max(20, Math.round(baseVO2)); // Minimum 20
}

function getVO2Category(vo2max) {
    if (vo2max >= 60) return { category: 'elite', color: 'success' };
    if (vo2max >= 50) return { category: 'Excellent', color: 'primary' };
    if (vo2max >= 40) return { category: 'Bon', color: 'info' };
    if (vo2max >= 30) return { category: 'Moyen', color: 'warning' };
    return { category: 'Faible', color: 'danger' };
}

function calculateBMI(weight, height) {
    return weight / Math.pow(height / 100, 2);
}

function calculateFitness() {
    // Recuperation des valeurs
    const age = document.getElementById('age').value;
    const restingHR = document.getElementById('restingHR').value;
    const weight = document.getElementById('weight').value;
    const height = document.getElementById('height').value;
    const gender = document.getElementById('gender').value;
    const fitnessLevel = document.getElementById('fitnessLevel').value;
    const intensity = parseInt(document.getElementById('intensity').value);
    const useKnownMax = document.getElementById('useKnownMax').checked;
    const knownMax = document.getElementById('maxHRKnown').value;
    const formula = document.getElementById('formula').value;
    
    // Validation
    const errorDiv = document.getElementById('errorMessage');
    if (!age || !restingHR || !weight || !height) {
        errorDiv.textContent = "Veuillez remplir tous les champs obligatoires (âge, FC repos, poids, taille).";
        errorDiv.classList.remove('d-none');
        document.getElementById('results').classList.add('d-none');
        return;
    }
    
    if (useKnownMax && !knownMax) {
        errorDiv.textContent = "Veuillez saisir votre FC Max connue ou deselectionner cette option.";
        errorDiv.classList.remove('d-none');
        document.getElementById('results').classList.add('d-none');
        return;
    }
    
    // Masquer les erreurs
    errorDiv.classList.add('d-none');
    
    // Calculs
    const maxHR = calculateMaxHeartRate(age, gender, formula, useKnownMax, knownMax);
    const targetHR = calculateTargetHR(maxHR, parseInt(restingHR), intensity);
    const bmi = calculateBMI(parseFloat(weight), parseFloat(height));
    const vo2max = estimateVO2Max(age, gender, fitnessLevel);
    const vo2Category = getVO2Category(vo2max);
    
    // Affichage des metriques principales
    const metricsHTML = `
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">
                        <i class="fas fa-heart me-1 text-danger"></i>FC Max
                    </h6>
                    <p class="card-text fs-3">
                        <strong class="text-primary">${Math.round(maxHR)}</strong>
                    </p>
                    <small class="text-muted">bpm</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">
                        <i class="fas fa-bullseye me-1 text-warning"></i>FC Cible
                    </h6>
                    <p class="card-text fs-3">
                        <strong class="text-warning">${targetHR}</strong>
                    </p>
                    <small class="text-muted">bpm A ${intensity}%</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">
                        <i class="fas fa-weight me-1 text-info"></i>IMC
                    </h6>
                    <p class="card-text fs-4">
                        <strong class="text-info">${Math.round(bmi * 10) / 10}</strong>
                    </p>
                    <small class="text-muted">kg/m²</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">
                        <i class="fas fa-lungs me-1 text-success"></i>VO2 Max Est.
                    </h6>
                    <p class="card-text fs-4">
                        <strong class="text-success">${vo2max}</strong>
                    </p>
                    <span class="badge bg-${vo2Category.color}">${vo2Category.category}</span>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('metricsCards').innerHTML = metricsHTML;
    
    // Calcul des zones d'entraînement (methode Karvonen)
    const restingHRNum = parseInt(restingHR);
    const hrReserve = maxHR - restingHRNum;
    
    const zones = [
        {
            name: "Zone 1: Tres Legere",
            percentage: "50-60%",
            minHR: Math.round(restingHRNum + (hrReserve * 0.5)),
            maxHR: Math.round(restingHRNum + (hrReserve * 0.6)),
            objective: "Recuperation, echauffement",
            sensation: "Tres facile, conversation fluide",
            color: "table-success"
        },
        {
            name: "Zone 2: Legere", 
            percentage: "60-70%",
            minHR: Math.round(restingHRNum + (hrReserve * 0.6)),
            maxHR: Math.round(restingHRNum + (hrReserve * 0.7)),
            objective: "Endurance de base, combustion graisses",
            sensation: "Facile, conversation possible",
            color: "table-info"
        },
        {
            name: "Zone 3: Moderee",
            percentage: "70-80%", 
            minHR: Math.round(restingHRNum + (hrReserve * 0.7)),
            maxHR: Math.round(restingHRNum + (hrReserve * 0.8)),
            objective: "Amelioration endurance, capacite aerobie",
            sensation: "Moderee, conversation difficile",
            color: "table-warning"
        },
        {
            name: "Zone 4: Intense",
            percentage: "80-90%",
            minHR: Math.round(restingHRNum + (hrReserve * 0.8)),
            maxHR: Math.round(restingHRNum + (hrReserve * 0.9)),
            objective: "VMA, capacite anaerobie",
            sensation: "Difficile, quelques mots",
            color: "table-danger"
        },
        {
            name: "Zone 5: Maximale",
            percentage: "90-100%",
            minHR: Math.round(restingHRNum + (hrReserve * 0.9)),
            maxHR: Math.round(maxHR),
            objective: "Puissance maximale, vitesse",
            sensation: "Extrêmement difficile",
            color: "table-dark text-white"
        }
    ];
    
    const zonesHTML = zones.map(zone => `
        <tr class="${zone.color}">
            <td class="fw-bold">${zone.name}</td>
            <td class="text-center">${zone.percentage}</td>
            <td class="text-center fw-bold">${zone.minHR} - ${zone.maxHR} bpm</td>
            <td>${zone.objective}</td>
            <td><em>${zone.sensation}</em></td>
        </tr>
    `).join('');
    
    document.getElementById('zonesTableBody').innerHTML = zonesHTML;
    
    // Afficher les resultats
    document.getElementById('results').classList.remove('d-none');
    document.getElementById('results').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function resetForm() {
    document.getElementById('age').value = '';
    document.getElementById('restingHR').value = '';
    document.getElementById('weight').value = '';
    document.getElementById('height').value = '';
    document.getElementById('gender').value = 'male';
    document.getElementById('fitnessLevel').value = 'average';
    document.getElementById('intensity').value = 70;
    document.getElementById('intensityValue').textContent = '70%';
    document.getElementById('useKnownMax').checked = false;
    document.getElementById('maxHRKnown').value = '';
    document.getElementById('formula').value = 'tanaka';
    
    document.getElementById('knownMaxField').style.display = 'none';
    document.getElementById('formulaField').style.display = 'block';
    
    document.getElementById('results').classList.add('d-none');
    document.getElementById('errorMessage').classList.add('d-none');
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Masquer le champ FC Max connue par defaut
    document.getElementById('knownMaxField').style.display = 'none';
});
</script>
@endpush