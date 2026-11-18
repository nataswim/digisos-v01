@extends('layouts.tools')

@section('title', 'Vitesse de Nage Critique (VNC) & Zones d\'Entraînement')
@section('meta_description', 'Calculez votre VNC et vos zones d\'entraînement natation avec la méthode scientifique validée. Formule : (Temps 400m - Temps 200m) / 2. Simple, rapide, efficace.')

@section('content')

<!-- Section titre -->
@include('public.tools.partials.header', ['title' => 'Calculateur de Vitesse de Nage Critique (VNC)', 'lead' => 'VNC et vos zones d\'entraînement natation', 'icon' => 'fa-tachometer-alt'])

<!-- Calculateur Principal -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <h3 class="text-center mb-4">Saisissez vos temps de référence</h3>
                
                <!-- Messages d'erreur -->
                <div id="errorMessage" class="alert alert-danger d-none">
                    <!-- Sera rempli par JavaScript -->
                </div>
                
                <!-- Formulaire de saisie -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="fw-bold mb-2 text-primary">
                            <i class="fas fa-stopwatch me-2"></i>Temps sur 200m
                        </label>
                        <div class="input-group input-group-lg">
                            <input type="number" id="time200min" class="form-control border-primary" 
                                   placeholder="Min" min="0" max="59">
                            <span class="input-group-text bg-primary text-white">min</span>
                            <input type="number" id="time200sec" class="form-control border-primary" 
                                   placeholder="Sec" min="0" max="59.9" step="0.1">
                            <span class="input-group-text bg-primary text-white">sec</span>
                        </div>
                        <small class="text-muted">Exemple : 2 min 30.5 sec</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="fw-bold mb-2 text-success">
                            <i class="fas fa-stopwatch me-2"></i>Temps sur 400m
                        </label>
                        <div class="input-group input-group-lg">
                            <input type="number" id="time400min" class="form-control border-success" 
                                   placeholder="Min" min="0" max="59">
                            <span class="input-group-text bg-success text-white">min</span>
                            <input type="number" id="time400sec" class="form-control border-success" 
                                   placeholder="Sec" min="0" max="59.9" step="0.1">
                            <span class="input-group-text bg-success text-white">sec</span>
                        </div>
                        <small class="text-muted">Exemple : 5 min 15.2 sec</small>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg px-4 py-3 fw-bold w-100" onclick="calculateVNC()">
                            <i class="fas fa-calculator me-2"></i>Calculer ma VNC
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg px-4 py-3 fw-bold w-100" onclick="resetForm()">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Resultats VNC -->
                <div id="vncResults" class="d-none">
                    <div class="alert alert-success shadow-sm">
                        <h5 class="alert-heading text-center mb-4">
                            <i class="fas fa-trophy me-2"></i>Votre Vitesse de Nage Critique
                        </h5>
                        
                        <div class="text-center mb-4">
                            <div class="display-4 text-primary fw-bold" id="vncValue">
                                <!-- Sera rempli par JavaScript -->
                            </div>
                            <p class="text-muted fs-5">
                                C'est votre allure cible pour l'entraînement au seuil
                            </p>
                        </div>

                        <!-- Zones d'entraînement -->
                        <h6 class="text-center mb-3">
                            <i class="fas fa-layer-group me-2"></i>Vos Zones d'Entraînement
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Zone</th>
                                        <th class="text-center">% VNC</th>
                                        <th class="text-center">Allure / 100m</th>
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
                                Ces zones sont des estimations basees sur votre VNC. Adaptez selon votre ressenti 
                                et les conseils de votre entraîneur.
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
        <!-- Comprendre la VNC -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Comprendre la Vitesse de Nage Critique (VNC)
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Définition :</strong> La VNC représente la vitesse maximale qu'un nageur peut maintenir 
                    de manière quasi-stationnaire sans accumulation significative de lactate. C'est votre seuil anaérobie en natation.
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-flask me-2 text-primary"></i>Base Scientifique
                        </h6>
                        <p>La formule utilisee est simple mais efficace :</p>
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <code class="fs-5">VNC = (T400m - T200m) ÷ 2</code>
                                <p class="small mt-2 mb-0">Résultat en secondes par 100 mètres</p>
                            </div>
                        </div>
                        <p class="mt-3">
                            Cette méthode est basee sur la difference de temps entre deux distances sub-maximales 
                            pour reveler votre seuil d'endurance.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>
                            <i class="fas fa-bullseye me-2 text-success"></i>Pourquoi s'entraîner à la VNC ?
                        </h6>
                        <ul>
                            <li><strong>Amélioration du seuil :</strong> Repousse votre limite lactique</li>
                            <li><strong>Endurance de puissance :</strong> Combine endurance et vitesse</li>
                            <li><strong>Économie de nage :</strong> Améliore votre efficacité technique</li>
                            <li><strong>Progression mesurable :</strong> Test reproductible dans le temps</li>
                        </ul>
                        
                        <div class="alert alert-success">
                            <small>
                                <strong>La VNC est un excellent indicateur</strong> de votre capacité aérobie 
                                et de votre efficacité propulsive en natation.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail des Zones -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-layer-group me-2"></i>
                    Détail des Zones d'Entraînement
                </h3>
            </div>
            <div class="card-body">
                <p>
                    L'entraînement à differentes intensités permet de cibler des adaptations physiologiques 
                    spécifiques. Voici le détail de chaque zone :
                </p>
                
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Zone 1 : Récupération</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Intensité :</strong> 115-125% VNC</p>
                                <p><strong>Sensation :</strong> Très facile, conversation fluide</p>
                                <p><strong>Objectif :</strong> Favoriser la circulation et la récupération musculaire</p>
                                <p><strong>Usage :</strong> Échauffement, récupération active</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Zone 2 : Endurance Aérobie</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Intensité :</strong> 105-115% VNC</p>
                                <p><strong>Sensation :</strong> Facile à modéré, soutenable longtemps</p>
                                <p><strong>Objectif :</strong> Développer la base aérobie et l'efficacité énergétique</p>
                                <p><strong>Usage :</strong> Séries longues, fond</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Zone 3 : Seuil (VNC)</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Intensité :</strong> 98-102% VNC</p>
                                <p><strong>Sensation :</strong> Difficile mais soutenable</p>
                                <p><strong>Objectif :</strong> Maintenir une vitesse élevée sur la durée</p>
                                <p><strong>Usage :</strong> Séries au seuil, tempo</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-6">
                        <div class="card border-danger h-100">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">Zone 4 : VO2 Max</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Intensité :</strong> 90-98% VNC</p>
                                <p><strong>Sensation :</strong> Très difficile, effort maximal sur courtes durees</p>
                                <p><strong>Objectif :</strong> Améliorer la puissance aérobie maximale</p>
                                <p><strong>Usage :</strong> Séries courtes intenses, fractionné</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-6">
                        <div class="card border-dark h-100">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0">Zone 5 : Vitesse Maximale</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Intensité :</strong> 80-90% VNC</p>
                                <p><strong>Sensation :</strong> Sprint, effort maximal</p>
                                <p><strong>Objectif :</strong> Développer la vitesse pure et la puissance neuromusculaire</p>
                                <p><strong>Usage :</strong> Sprints, departs, virages</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Pratiques -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-swimming-pool me-2"></i>
                    Applications Pratiques
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Planification d'Entraînement</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Définir les allures de séries</li>
                                    <li>Periodiser les intensités</li>
                                    <li>Suivre la progression</li>
                                    <li>Adapter les charges</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Test et évaluation</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Test reproductible mensuel</li>
                                    <li>Indicateur de forme</li>
                                    <li>Validation des progrès</li>
                                    <li>Ajustement des objectifs</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Optimisation Performance</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Stratégie de course</li>
                                    <li>Gestion de l'effort</li>
                                    <li>Amélioration technique</li>
                                    <li>Préparation compétition</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- References Scientifiques -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-book me-2"></i>
                    Références Scientifiques
                </h3>
            </div>
            <div class="card-body">
                <p>
                    La méthode VNC est largement reconnue et utilisee en physiologie de l'exercice 
                    et en entraînement sportif depuis les années 1990.
                </p>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6>Études Fondamentales</h6>
                        <ul class="small">
                            <li><strong>Wakayoshi et al. (1993)</strong> - Validation de la VNC comme standard aérobie en natation</li>
                            <li><strong>Pelayo et al. (1996)</strong> - Validite chez les nageurs masculins et feminins</li>
                            <li><strong>Dekerle et al. (2002)</strong> - Applications pratiques en entraînement</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Applications Modernes</h6>
                        <ul class="small">
                            <li><strong>Costill et al. (1985)</strong> - Métabolisme énergétique en natation</li>
                            <li><strong>Olbrecht (2000)</strong> - Science de l'entraînement optimal</li>
                            <li><strong>Maglischo (2003)</strong> - Biomécanique et physiologie</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-water me-2"></i>
                    <small>
                        La VNC reste un outil de référence utilise par les entraîneurs du monde entier 
                        pour optimiser les performances de leurs nageurs.
                    </small>
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

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.input-group-lg .form-control {
    font-size: 1.1rem;
}
</style>
@endpush

@push('scripts')
<script>
function convertToSeconds(minutes, seconds) {
    return (parseInt(minutes) || 0) * 60 + (parseFloat(seconds) || 0);
}

function formatSecondsToTime(totalSeconds) {
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = (totalSeconds % 60).toFixed(1).padStart(4, '0');
    return `${minutes}:${seconds}`;
}

function calculateVNC() {
    // Recuperer les valeurs
    const time200min = document.getElementById('time200min').value;
    const time200sec = document.getElementById('time200sec').value;
    const time400min = document.getElementById('time400min').value;
    const time400sec = document.getElementById('time400sec').value;
    
    // Convertir en secondes
    const t200 = convertToSeconds(time200min, time200sec);
    const t400 = convertToSeconds(time400min, time400sec);
    
    // Validation
    const errorDiv = document.getElementById('errorMessage');
    if (t200 <= 0 || t400 <= 0) {
        errorDiv.textContent = "Veuillez entrer des temps validés pour les deux distances.";
        errorDiv.classList.remove('d-none');
        document.getElementById('vncResults').classList.add('d-none');
        return;
    }
    
    if (t400 <= t200) {
        errorDiv.textContent = "Le temps sur 400m doit être supérieur au temps sur 200m.";
        errorDiv.classList.remove('d-none');
        document.getElementById('vncResults').classList.add('d-none');
        return;
    }
    
    // Masquer les erreurs
    errorDiv.classList.add('d-none');
    
    // Calcul VNC : (T400 - T200) / 2
    const vnc = (t400 - t200) / 2;
    
    // Affichage du resultat principal
    document.getElementById('vncValue').textContent = formatSecondsToTime(vnc) + " / 100m";
    
    // Definition des zones d'entraînement
    const zones = [
        {
            name: "Zone 1: Récupération",
            percentage: "115-125%",
            min: vnc * 1.15,
            max: vnc * 1.25,
            objectif: "Circulation, récupération musculaire",
            sensation: "Très facile, conversation fluide",
            color: "table-info"
        },
        {
            name: "Zone 2: Endurance Aérobie",
            percentage: "105-115%",
            min: vnc * 1.05,
            max: vnc * 1.15,
            objectif: "Base aérobie, efficacité énergétique",
            sensation: "Facile à modéré, longue durée",
            color: "table-success"
        },
        {
            name: "Zone 3: Seuil (VNC)",
            percentage: "98-102%",
            min: vnc * 0.98,
            max: vnc * 1.02,
            objectif: "Maintenir vitesse élevée sur durée",
            sensation: "Difficile mais soutenable",
            color: "table-warning"
        },
        {
            name: "Zone 4: VO2 Max",
            percentage: "90-98%",
            min: vnc * 0.90,
            max: vnc * 0.98,
            objectif: "Puissance aérobie maximale",
            sensation: "Très difficile, courtes durees",
            color: "table-danger"
        },
        {
            name: "Zone 5: Vitesse Maximale",
            percentage: "80-90%",
            min: vnc * 0.80,
            max: vnc * 0.90,
            objectif: "Vitesse pure, puissance neuromusculaire",
            sensation: "Sprint, effort maximal",
            color: "table-dark"
        }
    ];
    
    // Remplissage du tableau des zones
    const tbody = document.getElementById('zonesTableBody');
    tbody.innerHTML = zones.map(zone => `
        <tr class="${zone.color}">
            <td class="fw-bold">${zone.name}</td>
            <td class="text-center">${zone.percentage}</td>
            <td class="text-center fw-bold">
                ${formatSecondsToTime(zone.min)} - ${formatSecondsToTime(zone.max)}
            </td>
            <td>${zone.objectif}</td>
            <td><em>${zone.sensation}</em></td>
        </tr>
    `).join('');
    
    // Afficher les resultats
    document.getElementById('vncResults').classList.remove('d-none');
    
    // Scroll vers les resultats
    document.getElementById('vncResults').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function resetForm() {
    document.getElementById('time200min').value = '';
    document.getElementById('time200sec').value = '';
    document.getElementById('time400min').value = '';
    document.getElementById('time400sec').value = '';
    document.getElementById('vncResults').classList.add('d-none');
    document.getElementById('errorMessage').classList.add('d-none');
}

// Validation en temps reel
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="number"]');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            // Validation simple des valeurs
            if (this.id.includes('min') && parseFloat(this.value) > 59) {
                this.value = 59;
            }
            if (this.id.includes('sec') && parseFloat(this.value) >= 60) {
                this.value = 59.9;
            }
            if (parseFloat(this.value) < 0) {
                this.value = 0;
            }
        });
    });
});
</script>
@endpush