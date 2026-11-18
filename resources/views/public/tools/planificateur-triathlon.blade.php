@extends('layouts.tools')

@section('title', 'Planificateur d\'Entraînement Triathlon - Périodisation Scientifique Multi-Sports')
@section('meta_description', 'Planificateur triathlon base sur la science du sport. Calcul séances hebdomadaires optimales selon objectif et niveau. Périodisation, nutrition, prévention blessures. Evidence-based 2024.')

@section('content')
<!-- Section titre -->
@include('public.tools.partials.header', ['title' => "Planificateur d'Entraînement Triathlon", 'lead' => '', 'icon' => 'fa-biking'])

<!-- Planificateur Principal -->
<section class="py-5 bg-light">
    <div class="container">
        
        <!-- Calculateur de Plan -->
        <div class="card mb-4 shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Generateur de Plan Personnalisé
                </h3>
            </div>
            <div class="card-body">
                
                <!-- Messages d'erreur -->
                <div id="errorMessage" class="alert alert-danger d-none">
                    <!-- Sera rempli par JavaScript -->
                </div>

                <!-- Selection objectif et niveau -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="goal" class="form-label fw-bold">Objectif de Triathlon</label>
                        <select id="goal" class="form-select form-select-lg border-primary">
                            <option value="">-- Choisir un objectif --</option>
                            <option value="discovery">Découverte / Sprint (750m-20km-5km)</option>
                            <option value="olympic">Distance Olympique (1.5km-40km-10km)</option>
                            <option value="half_ironman">Half Ironman 70.3 (1.9km-90km-21km)</option>
                            <option value="ironman">Ironman (3.8km-180km-42km)</option>
                            <option value="ultra">Ultra-Distance (>Ironman)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="experience" class="form-label fw-bold">Niveau d'expérience</label>
                        <select id="experience" class="form-select form-select-lg border-success">
                            <option value="">-- Sélectionner votre niveau --</option>
                            <option value="beginner">Débutant (moins de 1 an)</option>
                            <option value="intermediate">Intermédiaire (1-3 ans)</option>
                            <option value="advanced">Avancé (3-5 ans)</option>
                            <option value="expert">Expert (plus de 5 ans)</option>
                        </select>
                    </div>
                </div>

                <!-- Parametres additionnels -->
                <h5 class="fw-bold mb-3">Paramètres de Personnalisation</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="timeAvailable" class="form-label">Temps disponible/semaine</label>
                        <select id="timeAvailable" class="form-select border-warning">
                            <option value="low">Limite (3-5h/semaine)</option>
                            <option value="moderate" selected>Modéré (6-10h/semaine)</option>
                            <option value="high">Élevé (11-15h/semaine)</option>
                            <option value="very_high">Tres eleve (>15h/semaine)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="discipline" class="form-label">Point fort</label>
                        <select id="discipline" class="form-select border-info">
                            <option value="balanced" selected>Équilibre</option>
                            <option value="swimming">Natation</option>
                            <option value="cycling">Cyclisme</option>
                            <option value="running">Course à pied</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="season" class="form-label">Période de l'année</label>
                        <select id="season" class="form-select border-secondary">
                            <option value="preparation">Préparation générale</option>
                            <option value="base" selected>Phase de base</option>
                            <option value="build">Phase de développement</option>
                            <option value="peak">Phase spécifique/pic</option>
                            <option value="taper">Affûtage pre-competition</option>
                            <option value="recovery">Récupération/Transition</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg fw-bold w-100" onclick="generatePlan()">
                            <i class="fas fa-magic me-2"></i>Générer mon Plan Personnalisé
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

        <!-- Resultats du Plan -->
        <div id="planResults" class="d-none">
            <!-- Plan Recommande -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Votre Plan d'Entraînement Personnalisé
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h5 class="alert-heading">Recommandation Personnalisée</h5>
                        <p id="planSummary">
                            <!-- Sera rempli par JavaScript -->
                        </p>
                    </div>

                    <!-- Repartition par discipline -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white text-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-swimmer me-2"></i>Natation
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-info" id="swimmingSessions">0</strong></span>
                                        <small class="d-block">séances/semaine</small>
                                    </p>
                                    <small class="text-muted" id="swimmingPercentage">% du volume total</small>
                                    <hr>
                                    <small id="swimmingFocus" class="text-muted">Focus technique et endurance</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark text-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-biking me-2"></i>Cyclisme
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-warning" id="cyclingSessions">0</strong></span>
                                        <small class="d-block">séances/semaine</small>
                                    </p>
                                    <small class="text-muted" id="cyclingPercentage">% du volume total</small>
                                    <hr>
                                    <small id="cyclingFocus" class="text-muted">Focus endurance et puissance</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white text-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-running me-2"></i>Course à pied
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <p class="card-text">
                                        <span class="fs-3"><strong class="text-danger" id="runningSessions">0</strong></span>
                                        <small class="d-block">séances/semaine</small>
                                    </p>
                                    <small class="text-muted" id="runningPercentage">% du volume total</small>
                                    <hr>
                                    <small id="runningFocus" class="text-muted">Focus économie et résistance</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seances specialisees -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-link me-2"></i>Séances Brick
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong id="brickSessions">0</strong> séance(s) velo-course/semaine
                                    </p>
                                    <small class="text-muted">Adaptation aux transitions spécifiques</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-dumbbell me-2"></i>Renforcement
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <strong id="strengthSessions">0</strong> séance(s) force/semaine
                                    </p>
                                    <small class="text-muted">Prévention blessures et performance</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planning Hebdomadaire Detaille -->
            <div class="card mb-4 shadow-lg">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-2">
                        <i class="fas fa-calendar-week me-2"></i>
                        Planning Hebdomadaire Type
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
        
        <!-- Principes Scientifiques du Triathlon -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-flask me-2"></i>
                    Principes Scientifiques du Triathlon
                </h3>
            </div>
            <div class="card-body">
                <p>
                    La planification du triathlon nécessite une approche multi-disciplinaire equilibrant trois sports distincts. 
                    Nos recommandations integrent la science de la périodisation, la spécificité de l'entraînement, 
                    et la prévention des blessures selon les recherches les plus recentes.
                </p>

                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Mise à jour Recherche 2024</h6>
                    <p class="mb-0">
                        La "variabilité de l'entraînement" (alternance d'intensité/volume) est cruciale pour éviter 
                        le surentraînement et maximiser les gains dans chaque discipline tout en preservant la santé.
                    </p>
                </div>

                <div class="row g-4 mt-3">
                    <div class="col-md-6">
                        <h6>Périodisation Spécifique au Triathlon</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Phase</th>
                                        <th>Durée</th>
                                        <th>Objectif</th>
                                        <th>Focus Disciplines</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Base</strong></td>
                                        <td>12-16 sem</td>
                                        <td>Volume, endurance générale, technique</td>
                                        <td>Équilibre des 3 disciplines</td>
                                    </tr>
                                    <tr>
                                        <td>Build</td>
                                        <td>8-10 sem</td>
                                        <td>Intensité, force spécifique, seuil</td>
                                        <td>Focus progressif velo/course</td>
                                    </tr>
                                    <tr>
                                        <td>Spécialisée</td>
                                        <td>4-6 sem</td>
                                        <td>Rythme course, transitions, affûtage</td>
                                        <td>Simulation course, brick frequents</td>
                                    </tr>
                                    <tr>
                                        <td>Affûtage</td>
                                        <td>1-3 sem</td>
                                        <td>Récupération, supercompensation</td>
                                        <td>Réduction volume, maintien intensité</td>
                                    </tr>
                                    <tr>
                                        <td>Transition</td>
                                        <td>2-4 sem</td>
                                        <td>Récupération mentale/physique</td>
                                        <td>Activités alternatives</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="small">
                            L'integration de blocs d'entraînement "brick" (velo-course) est essentielle des la phase Build 
                            pour l'adaptation spécifique aux transitions.
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h6>Adaptations Physiologiques Croisees</h6>
                        <ul class="small">
                            <li><strong>Natation :</strong> Efficacité propulsive, adaptation à l'hypoxie, proprioception aquatique</li>
                            <li><strong>Cyclisme :</strong> Puissance aérobie, endurance musculaire des jambes, position aerodynamique</li>
                            <li><strong>Course à pied :</strong> Économie de course, résistance aux chocs, adaptation post-velo</li>
                            <li><strong>Physiologie Integree :</strong> Amélioration VO2 Max générale, gestion fatigue neuromusculaire</li>
                        </ul>
                        
                        <div class="alert alert-warning">
                            <h6>Découverte Recherche 2024</h6>
                            <p class="mb-0 small">
                                La natation en eau libre developpe une proprioception unique, ameliorant l'équilibre 
                                et la coordination sur les autres disciplines. Les entraînements en eau libre 
                                sont recommandés des que possible.
                            </p>
                        </div>

                        <h6 className="mt-3">Charges d'Entraînement Relatives</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Distance Course</th>
                                        <th>Natation</th>
                                        <th>Cyclisme</th>
                                        <th>Course</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Sprint/Olympique</td>
                                        <td>25-30%</td>
                                        <td>40-45%</td>
                                        <td>25-35%</td>
                                    </tr>
                                    <tr>
                                        <td>Half Ironman</td>
                                        <td>20-25%</td>
                                        <td>45-50%</td>
                                        <td>25-35%</td>
                                    </tr>
                                    <tr>
                                        <td>Ironman</td>
                                        <td>15-20%</td>
                                        <td>50-55%</td>
                                        <td>25-35%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nutrition et Recuperation -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-apple-alt me-2"></i>
                    Nutrition et Récupération Avancées en Triathlon
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Stratégies Nutritionnelles par Phase</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Phase</th>
                                        <th>Focus Macronutriments</th>
                                        <th>Hydratation</th>
                                        <th>Timing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Entraînement</td>
                                        <td>Glucides complexes 5-7g/kg<br>Protéines 1.2-1.7g/kg</td>
                                        <td>35-40ml/kg/jour<br>+ électrolytes</td>
                                        <td>Pre: 1-4h avant<br>Post: 30min fenêtre</td>
                                    </tr>
                                    <tr>
                                        <td>Avant course</td>
                                        <td>Surcharge glucidique<br>8-12g/kg (3 jours)</td>
                                        <td>Hyperhydratation<br>contrôlee</td>
                                        <td>Dernier repas:<br>3-4h avant</td>
                                    </tr>
                                    <tr>
                                        <td>Pendant course</td>
                                        <td>Gels, barres<br>30-90g glucides/h</td>
                                        <td>Boissons énergétiques<br>500-1000ml/h</td>
                                        <td>Début des 1ere heure<br>Régularité cruciale</td>
                                    </tr>
                                    <tr>
                                        <td>Post course</td>
                                        <td>Glucides + Protéines<br>ratio 3:1 ou 4:1</td>
                                        <td>Réhydratation<br>150% pertes</td>
                                        <td>Immediat puis<br>toutes les 2h</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Techniques de Récupération Optimisees</h6>
                        <ul class="small">
                            <li><strong>Sommeil :</strong> 7-9 heures minimum, siestes strategiques possible (20-30min)</li>
                            <li><strong>Nutrition post-effort :</strong> "Fenêtre métabolique" critique 30-60 minutes</li>
                            <li><strong>Récupération active :</strong> Nage légère ou velo zone 1 (50-60% FCmax)</li>
                            <li><strong>Compression :</strong> Manches, chaussettes de récupération (2-4h post-effort)</li>
                            <li><strong>Therapie par le froid :</strong> Bains 10-15°C, 10-15 minutes maximum</li>
                            <li><strong>Massage :</strong> Techniques de drainage, éviter massage profond pre-competition</li>
                        </ul>
                        
                        <div class="alert alert-success">
                            <h6>Innovation Récupération 2024</h6>
                            <p class="mb-0 small">
                                L'integration de la meditation et de la cohérence cardiaque (5-10min quotidien) 
                                réduit significativement le stress perçu et accelere la récupération 
                                chez les triathletes d'endurance.
                            </p>
                        </div>

                        <h6 className="mt-3">Supplementation Evidence-Based</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Supplement</th>
                                        <th>Benefice</th>
                                        <th>Dosage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Creatine</td>
                                        <td>Puissance, récupération</td>
                                        <td>3-5g/jour</td>
                                    </tr>
                                    <tr>
                                        <td>Bêta-alanine</td>
                                        <td>Tampon lactique</td>
                                        <td>3-6g/jour (divise)</td>
                                    </tr>
                                    <tr>
                                        <td>Cafeine</td>
                                        <td>Performance, focus</td>
                                        <td>3-6mg/kg pré-effort</td>
                                    </tr>
                                    <tr>
                                        <td>Nitrates</td>
                                        <td>Efficacité O2</td>
                                        <td>5-9mmol 2-3h avant</td>
                                    </tr>
                                </tbody>
                            </table>
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
                    Prévention des Blessures et Materiel 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Blessures Courantes en Triathlon</h6>
                        <div class="accordion" id="injuryAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#swimming-injuries">
                                        <i class="fas fa-swimmer text-info me-2"></i>Blessures Natation
                                    </button>
                                </h2>
                                <div id="swimming-injuries" class="accordion-collapse collapse" data-bs-parent="#injuryAccordion">
                                    <div class="accordion-body small">
                                        <ul>
                                            <li><strong>Épaule du nageur :</strong> Conflit sous-acromial, tendinopathie coiffe</li>
                                            <li><strong>Douleurs cervicales :</strong> Hyperextension repetee</li>
                                            <li><strong>Prévention :</strong> Renforcement coiffe des rotateurs, étirements anterieurs</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cycling-injuries">
                                        <i class="fas fa-biking text-warning me-2"></i>Blessures Cyclisme
                                    </button>
                                </h2>
                                <div id="cycling-injuries" class="accordion-collapse collapse" data-bs-parent="#injuryAccordion">
                                    <div class="accordion-body small">
                                        <ul>
                                            <li><strong>Douleurs lombaires :</strong> Position prolongée, core faible</li>
                                            <li><strong>Syndrome femoro-patellaire :</strong> Mauvais réglage selle</li>
                                            <li><strong>Neuropathies :</strong> Compression nerf pudendal/cubital</li>
                                            <li><strong>Prévention :</strong> Étude posturale, renforcement core, selle adaptee</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#running-injuries">
                                        <i class="fas fa-running text-danger me-2"></i>Blessures Course à pied
                                    </button>
                                </h2>
                                <div id="running-injuries" class="accordion-collapse collapse" data-bs-parent="#injuryAccordion">
                                    <div class="accordion-body small">
                                        <ul>
                                            <li><strong>Tendinopathie d'Achille :</strong> Surcharge, transition velo-course</li>
                                            <li><strong>Syndrome essuie-glace :</strong> Friction TFL sur condyle femoral</li>
                                            <li><strong>Periostite tibiale :</strong> Progression volume trop rapide</li>
                                            <li><strong>Prévention :</strong> Progression graduelle, chaussures adaptees, technique</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Materiel Essentiel 2024</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Catégorie</th>
                                        <th>Équipement</th>
                                        <th>Priorite</th>
                                        <th>Budget</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="3">Natation</td>
                                        <td>Combinaison neoprene</td>
                                        <td><span class="badge bg-danger">Essentiel</span></td>
                                        <td>€€€</td>
                                    </tr>
                                    <tr>
                                        <td>Lunettes eau libre</td>
                                        <td><span class="badge bg-warning">Important</span></td>
                                        <td>€</td>
                                    </tr>
                                    <tr>
                                        <td>Bonnet silicone</td>
                                        <td><span class="badge bg-success">Utile</span></td>
                                        <td>€</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3">Cyclisme</td>
                                        <td>Velo triathlon/TT</td>
                                        <td><span class="badge bg-danger">Essentiel</span></td>
                                        <td>€€€€</td>
                                    </tr>
                                    <tr>
                                        <td>Capteur puissance</td>
                                        <td><span class="badge bg-warning">Important</span></td>
                                        <td>€€€</td>
                                    </tr>
                                    <tr>
                                        <td>Chaussures triathlon</td>
                                        <td><span class="badge bg-warning">Important</span></td>
                                        <td>€€</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">Course</td>
                                        <td>Chaussures running tri</td>
                                        <td><span class="badge bg-danger">Essentiel</span></td>
                                        <td>€€</td>
                                    </tr>
                                    <tr>
                                        <td>Lacets élastiques</td>
                                        <td><span class="badge bg-success">Utile</span></td>
                                        <td>€</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">Tech</td>
                                        <td>Montre GPS multi-sports</td>
                                        <td><span class="badge bg-danger">Essentiel</span></td>
                                        <td>€€€</td>
                                    </tr>
                                    <tr>
                                        <td>Ceinture cardiaque</td>
                                        <td><span class="badge bg-warning">Important</span></td>
                                        <td>€</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6>Tendance Technologique 2024</h6>
                            <p class="mb-0 small">
                                Les capteurs de puissance en natation et l'analyse biomécanique en course 
                                deviennent accessibles, permettant une prévention ultra-personnalisee des blessures 
                                et une optimisation de la technique.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conseils par Discipline -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Conseils Spécifiques Evidence-Based par Discipline
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6><i class="fas fa-swimmer text-info me-2"></i>Natation</h6>
                        <ul class="small">
                            <li><strong>Technique prioritaire :</strong> Efficacité propulsive avant volume</li>
                            <li><strong>Respiration bilaterale :</strong> Équilibre musculaire et navigation</li>
                            <li><strong>Eau libre régulière :</strong> Adaptation conditions réelles</li>
                            <li><strong>Sighting practice :</strong> Technique visee toutes les 6-8 mouvements</li>
                            <li><strong>Drafting :</strong> Économie d'énergie 10-20% en peloton</li>
                            <li><strong>Échauffement spécifique :</strong> 400-800m progression intensité</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="fas fa-biking text-warning me-2"></i>Cyclisme</h6>
                        <ul class="small">
                            <li><strong>Position aerodynamique :</strong> Gain 15-30% résistance air</li>
                            <li><strong>Entraînement puissance :</strong> Zones FTP spécifiques triathlon</li>
                            <li><strong>Cadence optimale :</strong> 85-95 rpm preservation jambes</li>
                            <li><strong>Nutrition timing :</strong> Début alimentation des 1ere heure</li>
                            <li><strong>Pacing strategy :</strong> Répartition effort selon distance</li>
                            <li><strong>Brick training :</strong> Transition velo-course 2x/semaine</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="fas fa-running text-danger me-2"></i>Course à pied</h6>
                        <ul class="small">
                            <li><strong>Économie de course :</strong> Technique plus importante que VO2max</li>
                            <li><strong>Adaptation post-velo :</strong> Jambes lourdes 5-10 premières minutes</li>
                            <li><strong>Stratégie allure :</strong> Start conservateur puis progression</li>
                            <li><strong>Renforcement spécifique :</strong> Travail excentrique prévention</li>
                            <li><strong>Récupération active :</strong> Footing léger jours off</li>
                            <li><strong>Terrain varie :</strong> Adaptation surface course (route/trail)</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-4">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Recommandations de Santé et Sécurité</h6>
                    <p class="mb-0">
                        Le triathlon est un sport exigeant nécessitant une progression graduelle et methodique. 
                        Consultez un professionnel de santé avant de debuter, surtout si vous avez des antecedents 
                        cardiovasculaires. Respectez les signaux de fatigue et integrez des periodes de récupération 
                        complete. La performance durable prime sur la performance immediate.
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

.accordion-button:not(.collapsed) {
    background-color: #e7f3ff;
    border-color: #0d6efd;
}

.badge {
    font-size: 0.7rem;
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
// Configuration des plans d'entraînement
const trainingPlans = {
    discovery: {
        beginner: { total: 3, swimming: 1, cycling: 1, running: 1, brick: 0, strength: 1 },
        intermediate: { total: 4, swimming: 1, cycling: 2, running: 1, brick: 1, strength: 1 },
        advanced: { total: 5, swimming: 2, cycling: 2, running: 1, brick: 1, strength: 1 },
        expert: { total: 6, swimming: 2, cycling: 2, running: 2, brick: 1, strength: 1 }
    },
    olympic: {
        beginner: { total: 4, swimming: 1, cycling: 2, running: 1, brick: 1, strength: 1 },
        intermediate: { total: 5, swimming: 2, cycling: 2, running: 1, brick: 1, strength: 1 },
        advanced: { total: 6, swimming: 2, cycling: 2, running: 2, brick: 1, strength: 1 },
        expert: { total: 7, swimming: 2, cycling: 3, running: 2, brick: 1, strength: 1 }
    },
    half_ironman: {
        beginner: { total: 5, swimming: 2, cycling: 2, running: 1, brick: 1, strength: 1 },
        intermediate: { total: 6, swimming: 2, cycling: 3, running: 1, brick: 1, strength: 1 },
        advanced: { total: 7, swimming: 2, cycling: 3, running: 2, brick: 1, strength: 1 },
        expert: { total: 8, swimming: 3, cycling: 3, running: 2, brick: 2, strength: 1 }
    },
    ironman: {
        beginner: { total: 6, swimming: 2, cycling: 3, running: 1, brick: 1, strength: 1 },
        intermediate: { total: 7, swimming: 2, cycling: 3, running: 2, brick: 1, strength: 1 },
        advanced: { total: 8, swimming: 3, cycling: 3, running: 2, brick: 2, strength: 1 },
        expert: { total: 9, swimming: 3, cycling: 4, running: 2, brick: 2, strength: 1 }
    },
    ultra: {
        beginner: { total: 7, swimming: 2, cycling: 4, running: 1, brick: 1, strength: 1 },
        intermediate: { total: 8, swimming: 3, cycling: 4, running: 1, brick: 2, strength: 1 },
        advanced: { total: 9, swimming: 3, cycling: 4, running: 2, brick: 2, strength: 1 },
        expert: { total: 10, swimming: 3, cycling: 5, running: 2, brick: 2, strength: 1 }
    }
};

// Ajustements selon parametres
const adjustmentFactors = {
    timeAvailable: {
        low: 0.7,
        moderate: 1.0,
        high: 1.2,
        very_high: 1.4
    },
    discipline: {
        balanced: { swimming: 1.0, cycling: 1.0, running: 1.0 },
        swimming: { swimming: 1.3, cycling: 0.9, running: 0.9 },
        cycling: { swimming: 0.9, cycling: 1.3, running: 0.9 },
        running: { swimming: 0.9, cycling: 0.9, running: 1.3 }
    },
    season: {
        preparation: { volume: 0.8, intensity: 0.6 },
        base: { volume: 1.0, intensity: 0.7 },
        build: { volume: 1.1, intensity: 1.0 },
        peak: { volume: 1.2, intensity: 1.1 },
        taper: { volume: 0.6, intensity: 0.8 },
        recovery: { volume: 0.5, intensity: 0.5 }
    }
};

// Generation du plan d'entraînement
function generatePlan() {
    const goal = document.getElementById('goal').value;
    const experience = document.getElementById('experience').value;
    const timeAvailable = document.getElementById('timeAvailable').value;
    const discipline = document.getElementById('discipline').value;
    const season = document.getElementById('season').value;
    
    // Validation
    if (!goal || !experience) {
        showError('Veuillez sélectionner votre objectif et votre niveau d\'expérience.');
        return;
    }
    
    // Masquer les erreurs
    document.getElementById('errorMessage').classList.add('d-none');
    
    // Plan de base
    const basePlan = trainingPlans[goal][experience];
    
    // Ajustements
    const timeAdjustment = adjustmentFactors.timeAvailable[timeAvailable];
    const disciplineAdj = adjustmentFactors.discipline[discipline];
    const seasonAdj = adjustmentFactors.season[season];
    
    // Calcul plan ajuste
    const adjustedPlan = {
        total: Math.round(basePlan.total * timeAdjustment * seasonAdj.volume),
        swimming: Math.max(1, Math.round(basePlan.swimming * timeAdjustment * disciplineAdj.swimming * seasonAdj.volume)),
        cycling: Math.max(1, Math.round(basePlan.cycling * timeAdjustment * disciplineAdj.cycling * seasonAdj.volume)),
        running: Math.max(1, Math.round(basePlan.running * timeAdjustment * disciplineAdj.running * seasonAdj.volume)),
        brick: Math.round(basePlan.brick * timeAdjustment * seasonAdj.volume),
        strength: Math.round(basePlan.strength * timeAdjustment)
    };
    
    // S'assurer que le total est coherent
    const disciplineSum = adjustedPlan.swimming + adjustedPlan.cycling + adjustedPlan.running;
    if (disciplineSum > adjustedPlan.total) {
        adjustedPlan.total = disciplineSum + adjustedPlan.brick + adjustedPlan.strength;
    }
    
    displayPlan(adjustedPlan, goal, experience, timeAvailable, season);
}

// Affichage du plan
function displayPlan(plan, goal, experience, timeAvailable, season) {
    // Texte de resume
    const goalNames = {
        discovery: 'Decouverte/Sprint',
        olympic: 'Distance Olympique',
        half_ironman: 'Half Ironman 70.3',
        ironman: 'Ironman',
        ultra: 'Ultra-Distance'
    };
    
    const experienceNames = {
        beginner: 'debutant',
        intermediate: 'intermediaire',
        advanced: 'avance',
        expert: 'expert'
    };
    
    const timeNames = {
        low: 'temps limite',
        moderate: 'temps modéré',
        high: 'temps élevé',
        very_high: 'temps très élevé'
    };
    
    const seasonNames = {
        preparation: 'préparation générale',
        base: 'phase de base',
        build: 'phase de développement',
        peak: 'phase spécifique',
        taper: 'affûtage',
        recovery: 'recuperation'
    };
    
    document.getElementById('planSummary').innerHTML = `
        Pour votre objectif <strong>${goalNames[goal]}</strong> et niveau <strong>${experienceNames[experience]}</strong>,
        avec <strong>${timeNames[timeAvailable]}</strong> en <strong>${seasonNames[season]}</strong>,
        il est recommandé de faire <strong>${plan.total} séances</strong> d'entraînement par semaine.
    `;
    
    // Seances par discipline
    document.getElementById('swimmingSessions').textContent = plan.swimming;
    document.getElementById('cyclingSessions').textContent = plan.cycling;
    document.getElementById('runningSessions').textContent = plan.running;
    document.getElementById('brickSessions').textContent = plan.brick;
    document.getElementById('strengthSessions').textContent = plan.strength;
    
    // Pourcentages
    const swimPercent = Math.round((plan.swimming / plan.total) * 100);
    const cyclePercent = Math.round((plan.cycling / plan.total) * 100);
    const runPercent = Math.round((plan.running / plan.total) * 100);
    
    document.getElementById('swimmingPercentage').textContent = `~${swimPercent}% du volume total`;
    document.getElementById('cyclingPercentage').textContent = `~${cyclePercent}% du volume total`;
    document.getElementById('runningPercentage').textContent = `~${runPercent}% du volume total`;
    
    // Focus specifiques selon la phase
    updateFocusText(season);
    
    // Planning hebdomadaire
    generateWeeklySchedule(plan, season);
    
    // Afficher les resultats
    document.getElementById('planResults').classList.remove('d-none');
    document.getElementById('planResults').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Mise A jour du focus selon la saison
function updateFocusText(season) {
    const focusTexts = {
        preparation: {
            swimming: 'Technique, mobilite articulaire',
            cycling: 'Endurance base, force générale',
            running: 'Volume progressif, prévention'
        },
        base: {
            swimming: 'Volume, endurance aérobie',
            cycling: 'Endurance, économie gestuelle',
            running: 'Endurance fondamentale'
        },
        build: {
            swimming: 'Seuil, vitesse spécifique',
            cycling: 'Puissance, seuil lactique',
            running: 'Tempo, seuil anaérobie'
        },
        peak: {
            swimming: 'Allure course, transitions',
            cycling: 'Spécifique compétition',
            running: 'Rythme cible, brick'
        },
        taper: {
            swimming: 'Maintien sensation',
            cycling: 'Qualite, récupération',
            running: 'Activation, confiance'
        },
        recovery: {
            swimming: 'Plaisir, technique',
            cycling: 'Exploration, plaisir',
            running: 'Récupération active'
        }
    };
    
    const focus = focusTexts[season];
    document.getElementById('swimmingFocus').textContent = focus.swimming;
    document.getElementById('cyclingFocus').textContent = focus.cycling;
    document.getElementById('runningFocus').textContent = focus.running;
}

// Generation du planning hebdomadaire
function generateWeeklySchedule(plan, season) {
    const scheduleContainer = document.getElementById('weeklySchedule');
    
    // Structure de la semaine type
    const weekDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    
    // Repartition intelligente des seances
    const schedule = distributeWorkouts(plan, season);
    
    let tableHTML = `
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Jour</th>
                    <th>Séance Principale</th>
                    <th>Séance Secondaire</th>
                    <th>Durée Approximative</th>
                    <th>Intensité</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    weekDays.forEach((day, index) => {
        const daySchedule = schedule[index];
        tableHTML += `
            <tr>
                <td><strong>${day}</strong></td>
                <td>${daySchedule.primary}</td>
                <td>${daySchedule.secondary}</td>
                <td>${daySchedule.duration}</td>
                <td>
                    <span class="badge bg-${daySchedule.intensityColor}">${daySchedule.intensity}</span>
                </td>
            </tr>
        `;
    });
    
    tableHTML += '</tbody></table>';
    scheduleContainer.innerHTML = tableHTML;
}

// Distribution intelligente des seances
function distributeWorkouts(plan, season) {
    const baseSchedule = [
        { primary: 'Natation technique', secondary: '', duration: '45-60min', intensity: 'Moderee', intensityColor: 'warning' },
        { primary: 'Cyclisme endurance', secondary: '', duration: '60-90min', intensity: 'Faible', intensityColor: 'success' },
        { primary: 'Course à pied', secondary: 'Renforcement', duration: '30-45min + 20min', intensity: 'Moderee', intensityColor: 'warning' },
        { primary: 'Natation intensité', secondary: '', duration: '45-60min', intensity: 'elevee', intensityColor: 'danger' },
        { primary: 'Brick (Velo + Course)', secondary: '', duration: '90-120min', intensity: 'elevee', intensityColor: 'danger' },
        { primary: 'Cyclisme long', secondary: '', duration: '2-4h', intensity: 'Faible-Moderee', intensityColor: 'info' },
        { primary: 'Récupération ou Repos', secondary: '', duration: 'Optionnel', intensity: 'Très faible', intensityColor: 'secondary' }
    ];
    
    // Ajustements selon la phase
    if (season === 'recovery') {
        return baseSchedule.map(day => ({
            ...day,
            intensity: 'Très faible',
            intensityColor: 'secondary',
            duration: day.primary.includes('Repos') ? 'Repos complet' : '30-45min'
        }));
    }
    
    if (season === 'taper') {
        return baseSchedule.map(day => ({
            ...day,
            duration: day.duration.replace(/(\d+)/g, (match) => Math.round(parseInt(match) * 0.7))
        }));
    }
    
    return baseSchedule;
}

// Affichage des erreurs
function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i><strong>Erreur :</strong> ${message}`;
    errorDiv.classList.remove('d-none');
    document.getElementById('planResults').classList.add('d-none');
}

// Reinitialisation du planificateur
function resetPlanner() {
    const selects = [
        { id: 'goal', value: '' },
        { id: 'experience', value: '' },
        { id: 'timeAvailable', value: 'moderate' },
        { id: 'discipline', value: 'balanced' },
        { id: 'season', value: 'base' }
    ];
    
    selects.forEach(select => {
        document.getElementById(select.id).value = select.value;
    });
    
    document.getElementById('errorMessage').classList.add('d-none');
    document.getElementById('planResults').classList.add('d-none');
}
</script>
@endpush