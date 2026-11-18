@extends('layouts.tools')

@section('title', 'Calculateur 1RM Avancé & Préparation Physique - Recherches 2024')
@section('meta_description', 'Calculateur 1RM scientifique avec zones d\'entraînement optimisees. Périodisation moderne, techniques avancées, nutrition peri-entraînement et prévention blessures. Evidence-based 2024.')

@section('content')
<!-- Section titre -->
@include('public.tools.partials.header', ['title' => 'Calculateur de la charge maximale (1RM)', 'lead' => '', 'icon' => 'fa-dumbbell'])

<!-- Calculateur Principal -->
<section class="py-5 bg-light">
    <div class="container">
        
        <!-- Calculateur 1RM -->
        <div class="card mb-4 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-calculator me-2"></i>
                    Calculateur 1RM - Formule Epley Validée
                </h3>
            </div>
            <div class="card-body">
                
                <!-- Messages d'erreur -->
                <div id="errorMessage" class="alert alert-danger d-none">
                    <!-- Sera rempli par JavaScript -->
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="weight" class="form-label fw-bold">
                            <i class="fas fa-weight-hanging me-2"></i>Poids souleve (kg)
                        </label>
                        <input type="number" id="weight" class="form-control form-control-lg border-primary" 
                               placeholder="Ex: 100" min="0" step="0.5" max="500">
                        <small class="text-muted">Entre 0 et 500 kg</small>
                    </div>
                    <div class="col-md-6">
                        <label for="reps" class="form-label fw-bold">
                            <i class="fas fa-redo me-2"></i>Nombre de répétitions
                        </label>
                        <input type="number" id="reps" class="form-control form-control-lg border-primary" 
                               placeholder="Ex: 8" min="1" max="20">
                        <small class="text-muted">Entre 1 et 20 répétitions</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg fw-bold w-100" onclick="calculateOneRM()">
                            <i class="fas fa-calculator me-2"></i>Calculer 1RM
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg fw-bold w-100" onclick="resetCalculator()">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultats 1RM -->
        <div id="resultsSection" class="d-none">
            <!-- Resultat Principal -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-trophy me-2"></i>
                        Votre 1RM Estimé et Zones d'Entraînement
                    </h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5 class="alert-heading">Votre 1RM Estimé</h5>
                        <p class="mb-0">
                            <span class="fs-2"><strong class="text-success" id="oneRMResult">0</strong></span>
                            <span class="fs-4"> kg</span>
                        </p>
                        <small class="text-muted">Formule Epley: Poids × (1 + 0.025 × Répétitions)</small>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card border-danger h-100">
                                <div class="card-header bg-danger text-white text-center">
                                    <h6 class="mb-0">Force (85-100%)</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text fs-5">
                                        <strong id="forceRange">0 - 0</strong>
                                        <small class="d-block">kg</small>
                                    </p>
                                    <small class="text-muted">1-5 reps | RIR 1-3</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning text-dark text-center">
                                    <h6 class="mb-0">Hypertrophie (67-85%)</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text fs-5">
                                        <strong id="hypertrophyRange">0 - 0</strong>
                                        <small class="d-block">kg</small>
                                    </p>
                                    <small class="text-muted">6-12 reps | RIR 0-2</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white text-center">
                                    <h6 class="mb-0">Endurance (50-67%)</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text fs-5">
                                        <strong id="enduranceRange">0 - 0</strong>
                                        <small class="d-block">kg</small>
                                    </p>
                                    <small class="text-muted">12-20+ reps | RIR 1-3</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info h-100">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">Échauffement (40-60%)</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text fs-5">
                                        <strong id="warmupRange">0 - 0</strong>
                                        <small class="d-block">kg</small>
                                    </p>
                                    <small class="text-muted">Activation neuromusculaire</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau detaille -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-table me-2"></i>
                        Tableau Détaillé des Pourcentages
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Répétitions</th>
                                    <th>% 1RM</th>
                                    <th>Poids (kg)</th>
                                    <th>Zone d'Entraînement</th>
                                    <th>RIR Recommandé</th>
                                </tr>
                            </thead>
                            <tbody id="percentageTableBody">
                                <!-- Sera rempli par JavaScript -->
                            </tbody>
                        </table>
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
                    Fondements Scientifiques - Recherches 2024-2025
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-danger border-0">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Position ACSM 2024</h6>
                    <p class="mb-0">
                        L'entraînement en résistance doit être periodise et individualise selon les objectifs, 
                        le niveau d'entraînement et les capacités de récupération de chaque individu.
                    </p>
                </div>
                
                <p>
                    Les recommandations s'appuient sur les dernières meta-analyses et recherches en sciences du sport, 
                    incluant les travaux de Schoenfeld, Van Hooren et les études sur la dose minimale efficace.
                </p>
                
                <div class="alert alert-info border-0">
                    <h6><i class="fas fa-lightbulb me-2"></i>Découverte majeure 2024</h6>
                    <p class="mb-0">
                        Une seule série de 6-12 répétitions A 70-85% 1RM, 2-3 fois par semaine, 
                        peut produire des gains significatifs de force chez les pratiquants entraînés.
                    </p>
                </div>

                <div class="row g-4 mt-3">
                    <div class="col-md-6">
                        <h6>Zones d'Entraînement Validées Scientifiquement</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Zone</th>
                                        <th>% 1RM</th>
                                        <th>Reps</th>
                                        <th>Adaptation Principale</th>
                                        <th>Repos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-danger">
                                        <td><strong>Force Max</strong></td>
                                        <td>85-100%</td>
                                        <td>1-5</td>
                                        <td>Neuromusculaire</td>
                                        <td>3-5min</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>Hypertrophie</td>
                                        <td>67-85%</td>
                                        <td>6-12</td>
                                        <td>Métabolique + Mécanique</td>
                                        <td>60-90s</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>Endurance</td>
                                        <td>50-67%</td>
                                        <td>12-20+</td>
                                        <td>Métabolique</td>
                                        <td>30-60s</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted">
                            Source : Meta-analyses 2024 sur les adaptations musculaires et zones optimales d'entraînement.
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h6>Mécanismes d'Hypertrophie (Modèle 2024)</h6>
                        <ul>
                            <li><strong>Tension mécanique :</strong> Force exercee sur le muscle pendant contraction</li>
                            <li><strong>Stress métabolique :</strong> Accumulation lactates/metabolites</li>
                            <li><strong>Dommages musculaires :</strong> Micro-lesions reparees par synthese protéique</li>
                        </ul>
                        <div class="alert alert-warning">
                            <small>
                                <strong>Innovation 2024 :</strong> L'hypertrophie peut survenir même avec 
                                des charges légères (&lt;30% 1RM) si l'effort est maximal (échec musculaire).
                            </small>
                        </div>

                        <h6 class="mt-3">Facteurs Influençant la Force Maximale</h6>
                        <ul class="small">
                            <li><strong>Neuraux :</strong> Recrutement, fréquence, synchronisation</li>
                            <li><strong>Musculaires :</strong> Masse, architecture, type fibres</li>
                            <li><strong>Biomécaniques :</strong> Longueur segments, points insertion</li>
                            <li><strong>Énergétiques :</strong> Phosphocréatine, glycolyse anaérobie</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Periodisation Moderne -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Périodisation Moderne - Evidence-Based
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Modèles de Périodisation Validés</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Modèle</th>
                                        <th>Force (1RM)</th>
                                        <th>Hypertrophie</th>
                                        <th>Population</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Periodisee vs Non-periodisee</td>
                                        <td class="text-success"><strong>Supérieure</strong></td>
                                        <td class="text-warning">Équivalente</td>
                                        <td>Toutes</td>
                                    </tr>
                                    <tr>
                                        <td>Ondulante vs Linéaire</td>
                                        <td class="text-success"><strong>Supérieure</strong></td>
                                        <td class="text-warning">Équivalente</td>
                                        <td>Entraînes</td>
                                    </tr>
                                    <tr>
                                        <td>Volume élevé vs Standard</td>
                                        <td class="text-success"><strong>Supérieur</strong></td>
                                        <td class="text-success"><strong>Supérieur</strong></td>
                                        <td>Intermédiaires+</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="mt-3">Principes de Progression</h6>
                        <ul class="small">
                            <li><strong>Surcharge progressive :</strong> Augmentation graduelle stimulus</li>
                            <li><strong>Spécificité :</strong> Adaptation selon type d'entraînement</li>
                            <li><strong>Variation :</strong> Prévention plateaux et adaptations</li>
                            <li><strong>Récupération :</strong> Période nécessaire super-compensation</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Périodisation Ondulante (DUP) - Recommandée 2024</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Semaine</th>
                                        <th>Intensité</th>
                                        <th>Volume</th>
                                        <th>Objectif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Semaine 1</td>
                                        <td>75-80% 1RM</td>
                                        <td>3x8-10 reps</td>
                                        <td>Hypertrophie</td>
                                    </tr>
                                    <tr>
                                        <td>Semaine 2</td>
                                        <td>85-90% 1RM</td>
                                        <td>4x4-6 reps</td>
                                        <td>Force</td>
                                    </tr>
                                    <tr>
                                        <td>Semaine 3</td>
                                        <td>65-70% 1RM</td>
                                        <td>3x12-15 reps</td>
                                        <td>Endurance</td>
                                    </tr>
                                    <tr>
                                        <td>Semaine 4</td>
                                        <td>Décharge</td>
                                        <td>-40% volume</td>
                                        <td>Récupération</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-success">
                            <small>
                                <strong>Avantage DUP :</strong> Variation frequente stimule continuellement 
                                les adaptations neuromusculaires et previent les plateaux.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variables d'Entraînement Avancees -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-cogs me-2"></i>
                    Variables d'Entraînement Optimisees
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Paramètres Temporels (Tempo)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Objectif</th>
                                        <th>Excentrique</th>
                                        <th>Pause</th>
                                        <th>Concentrique</th>
                                        <th>TUT Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Force Max</td>
                                        <td>2-3s</td>
                                        <td>1s</td>
                                        <td>Explosif</td>
                                        <td>20-40s</td>
                                    </tr>
                                    <tr>
                                        <td>Hypertrophie</td>
                                        <td>2-4s</td>
                                        <td>0-1s</td>
                                        <td>1-3s</td>
                                        <td>40-70s</td>
                                    </tr>
                                    <tr>
                                        <td>Puissance</td>
                                        <td>Contrôle</td>
                                        <td>0s</td>
                                        <td>Explosif</td>
                                        <td>10-20s</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h6 class="mt-3">Densite d'Entraînement</h6>
                        <ul class="small">
                            <li><strong>Fréquence optimale :</strong> 2-3x/semaine par groupe musculaire</li>
                            <li><strong>Volume hebdomadaire :</strong> 10-20 séries par groupe</li>
                            <li><strong>Repos inter-series :</strong> Selon intensité et objectif</li>
                            <li><strong>Repos inter-exercices :</strong> 2-5 minutes polyarticulaires</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>RIR (Reps in Réserve) - Concept Cle 2024</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>RIR</th>
                                        <th>Description</th>
                                        <th>Utilisation</th>
                                        <th>% 1RM Estimé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-danger">
                                        <td><strong>0</strong></td>
                                        <td>Échec musculaire</td>
                                        <td>Hypertrophie maximale</td>
                                        <td>100%</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>1-2</td>
                                        <td>Très proche échec</td>
                                        <td>Hypertrophie optimale</td>
                                        <td>85-95%</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td>3-4</td>
                                        <td>Effort élevé</td>
                                        <td>Force/technique</td>
                                        <td>70-85%</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td>5+</td>
                                        <td>Effort modéré</td>
                                        <td>Échauffement/recup</td>
                                        <td>&lt;70%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info">
                            <small>
                                <strong>Recherche 2024 :</strong> RIR 0-4 optimal pour hypertrophie. 
                                Au-delA de 4 RIR, les gains diminuent significativement (-40%).
                            </small>
                        </div>

                        <h6 class="mt-3">Auto-Regulation de l'Entraînement</h6>
                        <ul class="small">
                            <li><strong>RPE (Rate of Perceived Exertion) :</strong> Échelle 1-10 effort perçu</li>
                            <li><strong>Velocity-Based Training :</strong> Mesure vitesse concentrique</li>
                            <li><strong>Ajustements quotidiens :</strong> Selon état forme/fatigue</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Techniques Avancees -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-dumbbell me-2"></i>
                    Techniques Avancées Validées Scientifiquement
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6>Techniques d'Intensification</h6>
                                <ul class="small">
                                    <li><strong>Drop Sets :</strong> Réduction charge 15-25% À l'échec</li>
                                    <li><strong>Rest-Pause :</strong> 10-15s repos après échec, continuer</li>
                                    <li><strong>Cluster Sets :</strong> Repos intra-serie (10-20s)</li>
                                    <li><strong>Tempo Negatif :</strong> Phase excentrique ralentie (3-5s)</li>
                                    <li><strong>Pause Rep :</strong> 2-3s pause position basse</li>
                                    <li><strong>1¼ Rep :</strong> Amplitude partielle + complete</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Méthodes de Pre-Fatigue</h6>
                                <ul class="small">
                                    <li><strong>Pre-epuisement :</strong> Isolation puis polyarticulaire</li>
                                    <li><strong>Post-epuisement :</strong> Polyarticulaire puis isolation</li>
                                    <li><strong>Supersets :</strong> Exercices consecutifs sans repos</li>
                                    <li><strong>Trisets :</strong> 3 exercices enchaînes</li>
                                    <li><strong>Circuits :</strong> 4+ exercices rotation</li>
                                    <li><strong>Complexes :</strong> Combinaison force + puissance</li>
                                </ul>
                            </div>
                        </div>
                        
                        <h6 class="mt-3">BFR (Blood Flow Restriction) - Innovation 2024</h6>
                        <div class="alert alert-warning">
                            <small>
                                <strong>Protocole BFR :</strong> 50-80% occlusion arterielle + 20-50% 1RM. 
                                Permet hypertrophie similaire aux charges lourdes avec moins de stress articulaire. 
                                <strong>Supervision médicale recommandée.</strong>
                            </small>
                        </div>

                        <h6 class="mt-3">Techniques Excentriques Avancées</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <ul class="small">
                                    <li><strong>Overload Excentrique :</strong> 105-120% 1RM phase négative</li>
                                    <li><strong>Tempo Excentrique :</strong> 4-6 secondes contrôlees</li>
                                    <li><strong>Yielding Isometrique :</strong> Résistance etirement</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="small">
                                    <li><strong>Efficacité :</strong> +20-40% gains force vs concentrique seul</li>
                                    <li><strong>DOMS :</strong> Courbatures augmentees 24-72h</li>
                                    <li><strong>Récupération :</strong> 48-72h minimum entre sessions</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-warning mb-3">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Signaux de Surentraînement</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Stagnation/regression performance (&gt;2 semaines)</li>
                                    <li>Fatigue persistante excessive</li>
                                    <li>Troubles du sommeil/appetit</li>
                                    <li>Irritabilite, démotivation</li>
                                    <li>Douleurs articulaires accrues</li>
                                    <li>Infections frequentes (immunite↓)</li>
                                    <li>FC repos élevée (+5-10 bpm)</li>
                                    <li>HRV (variabilité cardiaque) réduite</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Technologies de Monitoring 2024</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Encodeurs lineaires :</strong> Velocity-based training</li>
                                    <li><strong>Électromyographie portable :</strong> Activation musculaire</li>
                                    <li><strong>Apps charge/récupération :</strong> HRV, sommeil, RPE</li>
                                    <li><strong>Plateformes de force :</strong> Tests saut, CMJ</li>
                                    <li><strong>Accélérométrie :</strong> Évaluation puissance</li>
                                    <li><strong>Compression pneumatique :</strong> Récupération active</li>
                                    <li><strong>Cryothérapie corps entier :</strong> -110°C A -140°C</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programmation Pratique -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Programmation Pratique par Objectif
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-danger h-100">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Programme Force (1RM↑)</h6>
                                <small>Gains Force Maximale</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Fréquence :</strong> 3-4x/semaine par groupe</li>
                                    <li><strong>Intensité :</strong> 85-95% 1RM</li>
                                    <li><strong>Volume :</strong> 3-5 séries x 1-5 reps</li>
                                    <li><strong>Repos :</strong> 3-5 minutes</li>
                                    <li><strong>RIR :</strong> 1-3</li>
                                    <li><strong>Exercices :</strong> Polyarticulaires (squat, bench, deadlift)</li>
                                    <li><strong>Progressions :</strong> +2.5-5kg/semaine</li>
                                    <li><strong>Phases :</strong> 4-6 semaines, décharge</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Programme Hypertrophie</h6>
                                <small>Gains Masse Musculaire</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Fréquence :</strong> 2-3x/semaine par groupe</li>
                                    <li><strong>Intensité :</strong> 67-85% 1RM</li>
                                    <li><strong>Volume :</strong> 3-4 séries x 6-12 reps</li>
                                    <li><strong>Repos :</strong> 60-90 secondes</li>
                                    <li><strong>RIR :</strong> 0-2</li>
                                    <li><strong>Exercices :</strong> Mix poly/mono-articulaires</li>
                                    <li><strong>TUT :</strong> 40-70 secondes par série</li>
                                    <li><strong>Volume hebdo :</strong> 12-20 séries/groupe</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Programme Endurance</h6>
                                <small>Endurance Musculaire</small>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li><strong>Fréquence :</strong> 2-3x/semaine par groupe</li>
                                    <li><strong>Intensité :</strong> 50-67% 1RM</li>
                                    <li><strong>Volume :</strong> 2-3 séries x 12-20+ reps</li>
                                    <li><strong>Repos :</strong> 30-60 secondes</li>
                                    <li><strong>RIR :</strong> 1-3</li>
                                    <li><strong>Exercices :</strong> Circuits/complexes</li>
                                    <li><strong>Densite :</strong> Ratio travail/repos élevé</li>
                                    <li><strong>Métabolique :</strong> Lactates, VO2max</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-4">
                    <h6><i class="fas fa-water me-2"></i>Principe de Spécificité SAID</h6>
                    <p class="mb-0">
                        <strong>(Specific Adaptations to Imposed Demands) :</strong> Les adaptations sont spécifiques aux contraintes imposees. 
                        Pour maximiser la force, entraînez-vous à haute intensité. Pour l'hypertrophie, privilegiez le volume et le stress métabolique.
                    </p>
                </div>
            </div>
        </div>

        <!-- Nutrition et Recuperation -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-utensils me-2"></i>
                    Nutrition et Récupération - Optimisation 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Nutrition Peri-Entraînement Optimisee</h6>
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
                                        <td>Pre (1-2h)</td>
                                        <td>Glucides</td>
                                        <td>0.5-1g/kg</td>
                                        <td>Énergie disponible, glycogene</td>
                                    </tr>
                                    <tr>
                                        <td>Pre (30min)</td>
                                        <td>Cafeine</td>
                                        <td>3-6mg/kg</td>
                                        <td>Performance, vigilance</td>
                                    </tr>
                                    <tr>
                                        <td>Post (0-30min)</td>
                                        <td>Protéines</td>
                                        <td>20-40g</td>
                                        <td>Synthese protéique (mTOR↑)</td>
                                    </tr>
                                    <tr>
                                        <td>Post (0-60min)</td>
                                        <td>Glucides</td>
                                        <td>0.5-1.2g/kg</td>
                                        <td>Resynthese glycogene</td>
                                    </tr>
                                    <tr>
                                        <td>Quotidien</td>
                                        <td>Protéines</td>
                                        <td>1.6-2.2g/kg</td>
                                        <td>Balance azotee positive</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h6 class="mt-3">Supplementation Evidence-Based</h6>
                        <ul class="small">
                            <li><strong>Creatine monohydrate :</strong> 3-5g/jour (↑force 5-15%)</li>
                            <li><strong>Cafeine :</strong> 3-6mg/kg pré-entraînement (↑performance 3-7%)</li>
                            <li><strong>Bêta-Alanine :</strong> 3-5g/jour (endurance musculaire)</li>
                            <li><strong>HMB :</strong> 3g/jour (anti-catabolique phases intensives)</li>
                            <li><strong>Citrulline :</strong> 6-8g pré-entraînement (↑pump, endurance)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Stratégies de Récupération Optimisees</h6>
                        <ul class="small">
                            <li><strong>Sommeil :</strong> 7-9h, qualite optimale (HGH↑, récupération)</li>
                            <li><strong>Hydratation :</strong> 35-40ml/kg/jour + pertes sudation</li>
                            <li><strong>Gestion stress :</strong> Meditation, cohérence cardiaque</li>
                            <li><strong>Mobilite active :</strong> 10-15min quotidiennes</li>
                            <li><strong>Récupération active :</strong> 20-30min activité légère</li>
                        </ul>
                        
                        <h6 class="mt-3">Modalités de Récupération Validées</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Modalite</th>
                                        <th>Protocole</th>
                                        <th>Efficacité</th>
                                        <th>Mécanisme</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Cryothérapie</td>
                                        <td>10-15min A 10-15°C</td>
                                        <td>⭐⭐⭐⭐</td>
                                        <td>Vasoconstriction, ↓inflammation</td>
                                    </tr>
                                    <tr>
                                        <td>Massage</td>
                                        <td>15-30min post-effort</td>
                                        <td>⭐⭐⭐</td>
                                        <td>Circulation, relaxation</td>
                                    </tr>
                                    <tr>
                                        <td>Compression</td>
                                        <td>2-4h post-effort</td>
                                        <td>⭐⭐⭐</td>
                                        <td>Retour veineux</td>
                                    </tr>
                                    <tr>
                                        <td>Étirements</td>
                                        <td>20-30s par groupe</td>
                                        <td>⭐⭐</td>
                                        <td>Amplitude, relaxation</td>
                                    </tr>
                                    <tr>
                                        <td>Sauna</td>
                                        <td>15-20min 80-90°C</td>
                                        <td>⭐⭐</td>
                                        <td>Vasodilatation, ↑protéines choc thermique</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-warning">
                            <small>
                                <strong>Recherche 2024 :</strong> La combinaison sommeil optimal + nutrition adaptee 
                                représente 70-80% de la récupération. Les modalités externes sont complementaires.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prevention des Blessures -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-shield-alt me-2"></i>
                    Prévention des Blessures en Musculation
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning border-0">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Statistique cle 2024</h6>
                    <p class="mb-0">
                        Les blessures en musculation représentent 4-7 cas pour 1000h d'entraînement, 
                        principalement au niveau du bas du dos (23%), des épaules (18%) et des genoux (12%).
                    </p>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <h6>Facteurs de Risque Principaux</h6>
                        <ul class="small">
                            <li><strong>Progression trop rapide :</strong> &gt;10% charge/semaine</li>
                            <li><strong>Technique défaillante :</strong> Compensation/déséquilibres</li>
                            <li><strong>Récupération insuffisante :</strong> Surentraînement</li>
                            <li><strong>Échauffement inadequat :</strong> Muscles/articulations froids</li>
                            <li><strong>Déséquilibres musculaires :</strong> Agonistes/antagonistes</li>
                            <li><strong>Mobilite restreinte :</strong> Amplitudes limitees</li>
                            <li><strong>Historique blessures :</strong> Zones de faiblesse persistantes</li>
                        </ul>

                        <h6 class="mt-3">Zones A Risque Spécifiques</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Zone</th>
                                        <th>% Blessures</th>
                                        <th>Exercices Risques</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Bas du dos</td>
                                        <td><strong>23%</strong></td>
                                        <td>Deadlift, Squat, Rowing</td>
                                    </tr>
                                    <tr>
                                        <td>Épaules</td>
                                        <td><strong>18%</strong></td>
                                        <td>Bench Press, Overhead Press</td>
                                    </tr>
                                    <tr>
                                        <td>Genoux</td>
                                        <td><strong>12%</strong></td>
                                        <td>Squat, Lunges, Leg Press</td>
                                    </tr>
                                    <tr>
                                        <td>Poignets</td>
                                        <td>8%</td>
                                        <td>Bench Press, Push-ups</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Stratégies Préventives Evidence-Based 2024</h6>
                        <ul class="small">
                            <li><strong>Progression contrôlee :</strong> Règle des 2-5% max/semaine</li>
                            <li><strong>Échauffement spécifique :</strong> 12-15min progressif</li>
                            <li><strong>Travail mobilite/stabilite :</strong> Quotidien 10-15min</li>
                            <li><strong>Monitoring charge :</strong> RPE, volume, HRV</li>
                            <li><strong>Évaluation technique :</strong> Coaching régulier</li>
                            <li><strong>Décharge programmee :</strong> Semaines récupération</li>
                            <li><strong>Corrections déséquilibres :</strong> Tests FMS, asymetries</li>
                        </ul>

                        <h6 class="mt-3">Protocoles d'échauffement Optimises</h6>
                        <div class="card border-info">
                            <div class="card-body">
                                <ol class="small mb-0">
                                    <li><strong>Activation générale (3-5min) :</strong> Velo, rameur léger</li>
                                    <li><strong>Mobilite dynamique (5-7min) :</strong> Mouvements articulaires</li>
                                    <li><strong>Activation spécifique (3-5min) :</strong> Mouvements exercices à vide</li>
                                    <li><strong>Montee en charge (5-8min) :</strong> 40-60-80% intensité cible</li>
                                </ol>
                            </div>
                        </div>

                        <h6 class="mt-3">Tests de Dépistage Recommandes</h6>
                        <ul class="small">
                            <li><strong>FMS (Functional Movement Screen) :</strong> 7 mouvements fondamentaux</li>
                            <li><strong>Y-Balance Test :</strong> Stabilite dynamique</li>
                            <li><strong>Tests force relative :</strong> Ratios agonistes/antagonistes</li>
                            <li><strong>Évaluation posturale :</strong> Déséquilibres musculaires</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-danger mt-3">
                    <h6><i class="fas fa-exclamation-circle me-2"></i>Important - Sécurité</h6>
                    <p class="mb-0">
                        En cas de douleur persistante, de technique défaillante ou de progression stagnante, 
                        consultez un professionnel qualifie (kinesitherapeute, preparateur physique). 
                        La progression graduelle et la technique correcte sont prioritaires sur la performance immediate.
                    </p>
                </div>
            </div>
        </div>

        <!-- References Scientifiques -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-book me-2"></i>
                    Références Scientifiques et Sources
                </h3>
            </div>
            <div class="card-body">
                <p>
                    Ce calculateur integre les dernières recherches en sciences du sport et préparation physique
                    publiees en 2024-2025 dans des revues scientifiques de référence internationale :
                </p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <h6>Physiologie Musculaire et Force</h6>
                        <ul class="small">
                            <li>Sports Medicine (Schoenfeld et al., 2024)</li>
                            <li>Journal of Strength & Conditioning Research</li>
                            <li>European Journal of Applied Physiology</li>
                            <li>Frontiers in Physiology - Exercise Physiology</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Entraînement en Résistance</h6>
                        <ul class="small">
                            <li>Medicine & Science in Sports & Exercise</li>
                            <li>International Journal of Sports Medicine</li>
                            <li>Journal of Sports Sciences</li>
                            <li>Applied Physiology, Nutrition and Metabolism</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Périodisation et Programmation</h6>
                        <ul class="small">
                            <li>Sports Medicine - Open</li>
                            <li>Scandinavian Journal of Medicine & Science in Sports</li>
                            <li>International Journal of Sports Physiology</li>
                            <li>Journal of Human Kinetics</li>
                        </ul>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-chart-line me-2"></i>Meta-analyses cles 2024</h6>
                    <p class="mb-0">
                        Les dernières revues systématiques confirment l'efficacité supérieure de l'entraînement periodise 
                        pour les gains de force maximale, avec un avantage de 15-25% vs entraînement non-periodise 
                        chez les pratiquants entraînés.
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
// Tableau des pourcentages pour les calculs 1RM
const percentageTable = [
    { reps: 1, percentage: 100 },
    { reps: 2, percentage: 95 },
    { reps: 3, percentage: 90 },
    { reps: 4, percentage: 88 },
    { reps: 5, percentage: 86 },
    { reps: 6, percentage: 85 },
    { reps: 7, percentage: 82 },
    { reps: 8, percentage: 80 },
    { reps: 9, percentage: 78 },
    { reps: 10, percentage: 75 },
    { reps: 11, percentage: 73 },
    { reps: 12, percentage: 70 },
    { reps: 13, percentage: 68 },
    { reps: 14, percentage: 66 },
    { reps: 15, percentage: 64 },
    { reps: 20, percentage: 60 }
];

function calculateOneRM() {
    const weight = parseFloat(document.getElementById('weight').value);
    const reps = parseFloat(document.getElementById('reps').value);
    const errorDiv = document.getElementById('errorMessage');
    
    // Validation
    if (!weight || !reps) {
        showError('Veuillez saisir le poids et le nombre de répétitions.');
        return;
    }
    
    if (weight < 1 || weight > 500) {
        showError('Le poids doit être compris entre 1 et 500 kg.');
        return;
    }
    
    if (reps < 1 || reps > 20) {
        showError('Le nombre de répétitions doit être compris entre 1 et 20.');
        return;
    }
    
    // Masquer les erreurs
    errorDiv.classList.add('d-none');
    
    // Calcul 1RM avec formule Epley modifiee
    const oneRM = weight * (1 + 0.025 * reps);
    const roundedOneRM = Math.round(oneRM * 100) / 100;
    
    // Affichage des resultats
    displayResults(roundedOneRM);
}

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = message;
    errorDiv.classList.remove('d-none');
    document.getElementById('resultsSection').classList.add('d-none');
}

function displayResults(oneRM) {
    // Afficher le 1RM principal
    document.getElementById('oneRMResult').textContent = oneRM;
    
    // Calculer les zones d'entraînement
    const forceMin = Math.round(oneRM * 0.85);
    const forceMax = oneRM;
    const hypertrophyMin = Math.round(oneRM * 0.67);
    const hypertrophyMax = Math.round(oneRM * 0.85);
    const enduranceMin = Math.round(oneRM * 0.5);
    const enduranceMax = Math.round(oneRM * 0.67);
    const warmupMin = Math.round(oneRM * 0.4);
    const warmupMax = Math.round(oneRM * 0.6);
    
    // Afficher les zones
    document.getElementById('forceRange').textContent = `${forceMin} - ${forceMax}`;
    document.getElementById('hypertrophyRange').textContent = `${hypertrophyMin} - ${hypertrophyMax}`;
    document.getElementById('enduranceRange').textContent = `${enduranceMin} - ${enduranceMax}`;
    document.getElementById('warmupRange').textContent = `${warmupMin} - ${warmupMax}`;
    
    // Remplir le tableau detaille
    fillPercentageTable(oneRM);
    
    // Afficher la section resultats
    document.getElementById('resultsSection').classList.remove('d-none');
    document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function fillPercentageTable(oneRM) {
    const tbody = document.getElementById('percentageTableBody');
    tbody.innerHTML = '';
    
    percentageTable.forEach(({ reps, percentage }) => {
        const weight = Math.round((oneRM * percentage) / 100 * 100) / 100;
        
        // Determiner la zone et RIR
        let zone = '';
        let rir = '';
        let badgeClass = '';
        
        if (percentage >= 85) {
            zone = 'Force';
            rir = '1-3';
            badgeClass = 'bg-danger';
        } else if (percentage >= 67) {
            zone = 'Hypertrophie';
            rir = '0-2';
            badgeClass = 'bg-warning text-dark';
        } else {
            zone = 'Endurance';
            rir = '1-3';
            badgeClass = 'bg-success';
        }
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>${reps}</strong></td>
            <td>${percentage}%</td>
            <td><strong>${weight} kg</strong></td>
            <td><span class="badge ${badgeClass}">${zone}</span></td>
            <td><small class="text-muted">${rir}</small></td>
        `;
        
        tbody.appendChild(row);
    });
}

function resetCalculator() {
    document.getElementById('weight').value = '';
    document.getElementById('reps').value = '';
    document.getElementById('errorMessage').classList.add('d-none');
    document.getElementById('resultsSection').classList.add('d-none');
}

// Validation en temps reel
document.getElementById('weight').addEventListener('input', function() {
    const value = parseFloat(this.value);
    if (value && (value < 1 || value > 500)) {
        this.setCustomValidity('Le poids doit être entre 1 et 500 kg');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('reps').addEventListener('input', function() {
    const value = parseFloat(this.value);
    if (value && (value < 1 || value > 20)) {
        this.setCustomValidity('Les répétitions doivent être entre 1 et 20');
    } else {
        this.setCustomValidity('');
    }
});

// Calcul automatique si les deux champs sont remplis
document.getElementById('weight').addEventListener('input', checkAutoCalculate);
document.getElementById('reps').addEventListener('input', checkAutoCalculate);

function checkAutoCalculate() {
    const weight = document.getElementById('weight').value;
    const reps = document.getElementById('reps').value;
    
    if (weight && reps && weight >= 1 && weight <= 500 && reps >= 1 && reps <= 20) {
        setTimeout(calculateOneRM, 500); // Delai pour eviter les calculs trop frequents
    }
}

// Gestion des touches Entree
document.getElementById('weight').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('reps').focus();
    }
});

document.getElementById('reps').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        calculateOneRM();
    }
});
</script>
@endpush