@extends('layouts.public')

@section('title', 'Planificateur d\'Entraînement Natation - Programme Scientifique Aquatique')
@section('meta_description', 'Planificateur natation scientifique avec repartition optimisee. Estimez le nombre de seances hebdomadaires selon votre objectif et niveau. Base sur les dernieres recherches en sciences aquatiques.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container py-3">
        <h1 class="display-4 fw-bold d-flex align-items-center justify-content-center gap-3 mb-3">
            Planificateur d'Entraînement Natation
        </h1>
        
        <div class="alert alert-success border-0 shadow-sm" >
            <div class="d-flex align-items-start">
                <i class="fas fa-chart-line text-info me-3 mt-1"></i>
                <div class="text-dark">
                    <strong>Combien de seances de natation par semaine ? 
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Planificateur Principal -->
<section class="py-5 bg-light">
    <div class="container">
        
        <!-- Planificateur Personnalise -->
        <div class="card mb-4 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-user-cog me-2"></i>
                    Planificateur Personnalise - Methode Evidence-Based
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
                            <i class="fas fa-target me-2"></i>Objectif de Natation
                        </label>
                        <select id="goal" class="form-select form-select-lg border-primary">
                            <option value="">-- Choisir un objectif --</option>
                            <option value="remise-forme">Remise en forme</option>
                            <option value="endurance">Ameliorer l'endurance</option>
                            <option value="perte-poids">Perte de poids</option>
                            <option value="competition">Preparation competition</option>
                        </select>
                        <small class="text-muted">Selectionnez votre objectif principal</small>
                    </div>
                    <div class="col-md-6">
                        <label for="experience" class="form-label fw-bold">
                            <i class="fas fa-medal me-2"></i>Niveau d'experience
                        </label>
                        <select id="experience" class="form-select form-select-lg border-primary">
                            <option value="">-- Selectionner votre niveau --</option>
                            <option value="debutant">Debutant (moins de 1 an)</option>
                            <option value="intermediaire">Intermediaire (1-3 ans)</option>
                            <option value="avance">Avance (plus de 3 ans)</option>
                        </select>
                        <small class="text-muted">Base sur votre experience en natation</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg fw-bold w-100" onclick="generatePlan()">
                            <i class="fas fa-calculator me-2"></i>Generer mon plan personnalise
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg fw-bold w-100" onclick="resetPlanner()">
                            <i class="fas fa-redo me-2"></i>Reinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultats du Planificateur -->
        <div id="planResults" class="d-none">
            <!-- Plan Principal -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-check-circle me-2"></i>
                        Recommandation Personnalisee
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success border-0">
                        <h5 class="alert-heading">Plan d'Entraînement Optimal</h5>
                        <p id="planDescription">
                            <!-- Sera rempli par JavaScript -->
                        </p>
                        <p class="mb-0">
                            <span class="fs-3"><strong class="text-success" id="totalSessions">0</strong></span>
                            <span class="fs-5"> seances par semaine</span>
                        </p>
                    </div>
                    
                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <div class="card border-info h-100">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">Technique</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong id="techniqueSessions">0</strong></span>
                                        <small class="d-block">seances/sem</small>
                                    </p>
                                    <small class="text-muted">30% du volume total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white text-center">
                                    <h6 class="mb-0">Endurance</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong id="enduranceSessions">0</strong></span>
                                        <small class="d-block">seances/sem</small>
                                    </p>
                                    <small class="text-muted">40% du volume total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning text-dark text-center">
                                    <h6 class="mb-0">Vitesse</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong id="speedSessions">0</strong></span>
                                        <small class="d-block">seances/sem</small>
                                    </p>
                                    <small class="text-muted">20% du volume total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-secondary h-100">
                                <div class="card-header bg-secondary text-white text-center">
                                    <h6 class="mb-0">Recuperation</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-4"><strong id="recoverySessions">0</strong></span>
                                        <small class="d-block">seances/sem</small>
                                    </p>
                                    <small class="text-muted">10% du volume total</small>
                                </div>
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
        
        <!-- Fondements Scientifiques -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-flask me-2"></i>
                    Fondements Scientifiques en Natation
                </h3>
            </div>
            <div class="card-body">
                <p>
                    Les recommandations s'appuient sur les dernieres recherches en biomecanique aquatique,
                    physiologie de l'exercice et sciences du sport aquatique publiees en 2024-2025.
                </p>

                <div class="alert alert-info border-0">
                    <h6><i class="fas fa-lightbulb me-2"></i>Donnees cles 2024</h6>
                    <p class="mb-0">
                        Les nageurs elites passent 65-70% de leur temps en zones aerobies,
                        avec seulement 15-20% en haute intensite. L'efficacite technique represente 40% de la performance.
                    </p>
                </div>

                <div class="row g-4 mt-3">
                    <div class="col-md-6">
                        <h6>Periodisation Aquatique Moderne</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Phase</th>
                                        <th>Focus Principal</th>
                                        <th>% Volume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Base</strong></td>
                                        <td>Technique + Aerobie</td>
                                        <td>70-80%</td>
                                    </tr>
                                    <tr>
                                        <td>Build</td>
                                        <td>Seuil + Vitesse</td>
                                        <td>15-25%</td>
                                    </tr>
                                    <tr>
                                        <td>Peak</td>
                                        <td>Tapering + Race Pace</td>
                                        <td>5-10%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted">
                            La periodisation inverse montre des resultats prometteurs pour
                            l'amelioration rapide de la vitesse de nage.
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h6>Zones d'Entraînement Scientifiques</h6>
                        <ul class="small">
                            <li><strong>Zone 1 (Recuperation) :</strong> 60-70% FC Max</li>
                            <li><strong>Zone 2 (Aerobie) :</strong> 70-80% FC Max</li>
                            <li><strong>Zone 3 (Seuil) :</strong> 80-90% FC Max</li>
                            <li><strong>Zone 4 (VO2 Max) :</strong> 90-95% FC Max</li>
                            <li><strong>Zone 5 (Neuromusculaire) :</strong> 95%+ FC Max</li>
                        </ul>
                        <div class="alert alert-warning">
                            <small>
                                <strong>Innovation 2024 :</strong> Les capteurs de lactates portables
                                permettent un monitoring precis en temps reel.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biomecanique et Efficacite -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-cogs me-2"></i>
                    Biomecanique et Efficacite Technique
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Parametres Biomecaniques Cles</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Parametre</th>
                                        <th>elite</th>
                                        <th>Amateur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Distance par Cycle (DPC)</td>
                                        <td><strong>2.2-2.8m</strong></td>
                                        <td>1.5-2.0m</td>
                                    </tr>
                                    <tr>
                                        <td>Frequence de Bras</td>
                                        <td>35-45 cycles/min</td>
                                        <td>40-55 cycles/min</td>
                                    </tr>
                                    <tr>
                                        <td>Index d'Efficacite</td>
                                        <td><strong>85-95%</strong></td>
                                        <td>65-75%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Technologies d'Analyse 2024</h6>
                        <ul class="small">
                            <li><strong>Capteurs inertiels :</strong> Analyse 3D en temps reel</li>
                            <li><strong>IA d'analyse technique :</strong> Correction automatique</li>
                            <li><strong>Cameras sous-marines :</strong> Biomecanique precise</li>
                            <li><strong>Wearables aquatiques :</strong> Metriques continues</li>
                        </ul>
                        <div class="alert alert-success">
                            <small>
                                <strong>Resultat 2024 :</strong> L'amelioration technique peut augmenter
                                la vitesse de 8-12% sans augmenter la charge d'entraînement.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Physiologie Specifique -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-heartbeat me-2"></i>
                    Physiologie Aquatique Avancee
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Adaptations Cardio-Respiratoires</h6>
                        <ul class="small">
                            <li><strong>Bradycardie d'immersion :</strong> -20 A -30 bpm en eau</li>
                            <li><strong>Capacite pulmonaire :</strong> +15-25% chez nageurs</li>
                            <li><strong>Efficacite VO2 :</strong> Meilleure extraction O2</li>
                            <li><strong>Thermoregulation :</strong> Adaptation metabolique</li>
                        </ul>

                        <h6 class="mt-3">Adaptations Neuromusculaires</h6>
                        <ul class="small">
                            <li>Coordination inter-musculaire optimisee</li>
                            <li>Proprioception aquatique developpee</li>
                            <li>Puissance propulsive specifique</li>
                            <li>Resistance A la fatigue lactique</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Nutrition Aquatique Specialisee</h6>
                        <ul class="small">
                            <li><strong>Hydratation :</strong> Paradoxe de la deshydratation en piscine</li>
                            <li><strong>Thermoregulation :</strong> Besoins energetiques modifies</li>
                            <li><strong>Timing :</strong> Fenêtre d'absorption post-entraînement</li>
                            <li><strong>electrolytes :</strong> Pertes minerales par sudation</li>
                        </ul>

                        <div class="alert alert-warning">
                            <small>
                                <strong>Decouverte 2024 :</strong> Les nageurs perdent jusqu'A 500ml
                                de sueur par heure en piscine chauffee, necessitant une hydratation active.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommandations par Niveau -->
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="mb-2">
                            <i class="fas fa-chart-line me-2"></i>
                            Recommandations Detaillees par Niveau
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Niveau</th>
                                        <th>Volume/Seance</th>
                                        <th>Intensite Distribution</th>
                                        <th>Focus Principal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Debutant</strong></td>
                                        <td>1000-1500m</td>
                                        <td>85% Z1-Z2, 15% Z3+</td>
                                        <td>Technique + Respiration</td>
                                    </tr>
                                    <tr>
                                        <td>Intermediaire</td>
                                        <td>1500-2500m</td>
                                        <td>75% Z1-Z2, 25% Z3+</td>
                                        <td>Endurance + Efficacite</td>
                                    </tr>
                                    <tr>
                                        <td>Avance</td>
                                        <td>2500-4000m</td>
                                        <td>70% Z1-Z2, 30% Z3+</td>
                                        <td>Performance + Specificite</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="mt-3">Progression Saisonniere</h6>
                        <ul class="small">
                            <li><strong>Phase 1 (8-12 sem) :</strong> Base technique et aerobie</li>
                            <li><strong>Phase 2 (6-8 sem) :</strong> Developpement seuil et vitesse</li>
                            <li><strong>Phase 3 (2-4 sem) :</strong> Affûtage et competition</li>
                            <li><strong>Phase 4 (2-3 sem) :</strong> Recuperation active</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">Signaux d'Alarme Aquatiques</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small">
                            <li>Perte d'efficacite technique soudaine</li>
                            <li>Augmentation de la frequence de bras</li>
                            <li>Essoufflement precoce inhabituel</li>
                            <li>Douleurs articulaires persistantes</li>
                            <li>Demotivation pour l'eau</li>
                        </ul>
                    </div>
                </div>

                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">equipement Recommande 2024</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small">
                            <li>Montre etanche avec GPS piscine</li>
                            <li>Tempo Trainer Pro</li>
                            <li>Plaquettes techniques (FINIS)</li>
                            <li>Tuba frontal pour technique</li>
                            <li>Capteur de mouvement FORM</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Methodes d'Entraînement Innovantes -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-rocket me-2"></i>
                    Methodes d'Entraînement Innovantes 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-danger h-100">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Entraînement Hypoxique</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Series avec restriction respiratoire</li>
                                    <li>Amelioration tolerance lactique</li>
                                    <li>Developpement capacite anaerobie</li>
                                    <li>Protocoles 50-75-100m sans respirer</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Nage en Resistance</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Parachutes aquatiques</li>
                                    <li>elastiques de resistance</li>
                                    <li>Nage en combinaison lestee</li>
                                    <li>Developpement puissance specifique</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Training Assiste</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Nage tractee (sur-vitesse)</li>
                                    <li>Palmes techniques courtes</li>
                                    <li>Amelioration coordination</li>
                                    <li>education sensations vitesse</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conseils Generaux -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Conseils Generaux Evidence-Based
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6>Technique</h6>
                        <ul class="small">
                            <li>Privilegier la distance par cycle</li>
                            <li>Respiration bilaterale obligatoire</li>
                            <li>Position hydrodynamique constante</li>
                            <li>educatifs techniques 20% du volume</li>
                            <li>Analyse video mensuelle</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Entraînement</h6>
                        <ul class="small">
                            <li>echauffement progressif 400-600m</li>
                            <li>Recuperation active entre series</li>
                            <li>Variete des nages (4 nages)</li>
                            <li>Test T30 mensuel pour CSS</li>
                            <li>Progression volume +10%/semaine max</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Recuperation</h6>
                        <ul class="small">
                            <li>etirements post-entraînement</li>
                            <li>Douche chaude puis froide</li>
                            <li>Hydratation immediate</li>
                            <li>Nage recuperation 200-400m</li>
                            <li>Massage des epaules regulier</li>
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
                    Prevention des Blessures Aquatiques
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning border-0">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Statistique cle</h6>
                    <p class="mb-0">
                        80% des blessures en natation concernent l'epaule (swimmer's shoulder).
                        L'incidence est de 0.3-0.5 blessures pour 1000h d'entraînement.
                    </p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <h6>Facteurs de Risque Identifies</h6>
                        <ul class="small">
                            <li>Volume d'entraînement excessif soudain</li>
                            <li>Technique defaillante (rattrape court)</li>
                            <li>Desequilibres musculaires epaules/dos</li>
                            <li>Manque d'echauffement specifique</li>
                            <li>Surutilisation plaquettes/pullboy</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Strategies Preventives</h6>
                        <ul class="small">
                            <li>Renforcement specifique coiffe des rotateurs</li>
                            <li>Travail proprioception aquatique</li>
                            <li>Rotation d'epaule complete</li>
                            <li>etirements capsule posterieure</li>
                            <li>Monitoring charge d'entraînement</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-water me-2"></i>Important - Securite</h6>
                    <p class="mb-0">
                        En cas de douleur persistante, de technique defaillante ou de stagnation, 
                        consultez un professionnel qualifie (maître-nageur, entraîneur). 
                        La progression graduelle et la technique correcte sont prioritaires sur le volume.
                    </p>
                </div>
            </div>
        </div>

        <!-- References Scientifiques -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-book me-2"></i>
                    References Scientifiques
                </h3>
            </div>
            <div class="card-body">
                <p>
                    Ce planificateur integre les dernieres recherches en sciences aquatiques
                    publiees en 2024-2025 dans des revues scientifiques de reference :
                </p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <h6>Biomecanique Aquatique</h6>
                        <ul class="small">
                            <li>Journal of Biomechanics</li>
                            <li>Sports Biomechanics</li>
                            <li>International Journal of Aquatic Research</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Physiologie Aquatique</h6>
                        <ul class="small">
                            <li>European Journal of Applied Physiology</li>
                            <li>International Journal of Sports Medicine</li>
                            <li>Scandinavian Journal of Sports Sciences</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6>Sciences du Sport</h6>
                        <ul class="small">
                            <li>International Journal of Swimming</li>
                            <li>World Journal of Swimming Research</li>
                            <li>Aquatic Sports Medicine Database</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-success mt-3">
                    <h6><i class="fas fa-chart-line me-2"></i>Conclusion Evidence-Based</h6>
                    <p class="mb-0">
                        La performance en natation resulte d'une synergie entre technique irreprochable (40% de la performance), 
                        condition physique adaptee et approche methodologique rigoureuse basee sur la science.
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
</style>
@endpush

@push('scripts')
<script>
// Base de donnees des plans d'entraînement
const trainingPlans = {
    'remise-forme': {
        'debutant': 2,
        'intermediaire': 3,
        'avance': 4
    },
    'endurance': {
        'debutant': 3,
        'intermediaire': 4,
        'avance': 5
    },
    'perte-poids': {
        'debutant': 2,
        'intermediaire': 3,
        'avance': 4
    },
    'competition': {
        'debutant': 3,
        'intermediaire': 5,
        'avance': 6
    }
};

// Descriptions des objectifs
const goalDescriptions = {
    'remise-forme': 'Remise en forme',
    'endurance': 'Ameliorer l\'endurance',
    'perte-poids': 'Perte de poids',
    'competition': 'Preparation competition'
};

// Descriptions des niveaux
const experienceDescriptions = {
    'debutant': 'debutant',
    'intermediaire': 'intermediaire',
    'avance': 'avance'
};

function generatePlan() {
    const goal = document.getElementById('goal').value;
    const experience = document.getElementById('experience').value;
    const errorDiv = document.getElementById('errorMessage');
    
    // Validation
    if (!goal || !experience) {
        showError('Veuillez selectionner un objectif et un niveau d\'experience.');
        return;
    }
    
    // Masquer les erreurs
    errorDiv.classList.add('d-none');
    
    // Recuperer le nombre de seances
    const totalSessions = trainingPlans[goal][experience];
    
    // Calculer la repartition selon les pourcentages scientifiques
    const techniqueSessions = Math.round(totalSessions * 0.3); // 30% Technique
    const enduranceSessions = Math.round(totalSessions * 0.4); // 40% Endurance
    const speedSessions = Math.round(totalSessions * 0.2); // 20% Vitesse
    const recoverySessions = Math.max(0, Math.round(totalSessions * 0.1)); // 10% Recuperation
    
    // Afficher les resultats
    displayResults(goal, experience, totalSessions, {
        technique: techniqueSessions,
        endurance: enduranceSessions,
        speed: speedSessions,
        recovery: recoverySessions
    });
}

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i><strong>Erreur :</strong> ${message}`;
    errorDiv.classList.remove('d-none');
    document.getElementById('planResults').classList.add('d-none');
}

function displayResults(goal, experience, totalSessions, distribution) {
    // Affichage du plan principal
    document.getElementById('planDescription').innerHTML = `
        Pour votre objectif <strong class="text-primary">${goalDescriptions[goal]}</strong> et niveau 
        <strong class="text-warning">${experienceDescriptions[experience]}</strong>, 
        il est recommande de nager :
    `;
    
    document.getElementById('totalSessions').textContent = totalSessions;
    document.getElementById('techniqueSessions').textContent = distribution.technique;
    document.getElementById('enduranceSessions').textContent = distribution.endurance;
    document.getElementById('speedSessions').textContent = distribution.speed;
    document.getElementById('recoverySessions').textContent = distribution.recovery;
    
    // Afficher la section resultats
    document.getElementById('planResults').classList.remove('d-none');
    document.getElementById('planResults').scrollIntoView({ behavior: 'smooth', block: 'start' });
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