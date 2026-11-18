@extends('layouts.tools')

{{-- SEO Meta --}}
@section('title', 'Comment Calculer IMC & Composition Corporelle')
@section('meta_description', 'Calculez votre IMC et analysez votre composition corporelle avec nos outils avancés, incluant % de graisse, WHtR et BRI. Informations scientifiques récentes.')

{{-- Open Graph / Facebook --}}
@section('og_type', 'website')
@section('og_title', 'Calculateur IMC & Composition Corporelle')
@section('og_description', 'Outil avancé d\'analyse de composition corporelle basé sur les dernières recherches scientifiques')
@section('og_url', route('tools.imc'))

@section('content')

<!-- Section titre -->
@include('public.tools.partials.header', ['title' => 'Calculateur IMC', 'lead' => 'Composition Corporelle - Calculez votre IMC et analysez votre composition corporelle', 'icon' => 'fa-weight'])

<!-- Calculateur -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <h3 class="text-center mb-4">Calculateur Avancé</h3>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="fw-bold mb-2">Poids (kg)</label>
                        <input type="number" id="weight" class="form-control form-control-lg border-primary" 
                               placeholder="70" min="30" step="0.1">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold mb-2">Taille (cm)</label>
                        <input type="number" id="height" class="form-control form-control-lg border-primary" 
                               placeholder="170" min="100" step="1">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold mb-2">Âge (optionnel)</label>
                        <input type="number" id="age" class="form-control form-control-lg border-primary" 
                               placeholder="30" min="10" max="120">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">Sexe</label>
                        <select id="gender" class="form-select form-select-lg border-primary">
                            <option value="male">Homme</option>
                            <option value="female">Femme</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold mb-2">Tour de taille (cm) - optionnel</label>
                        <input type="number" id="waist" class="form-control form-control-lg border-primary" 
                               placeholder="85" min="50" step="0.5">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg px-4 py-3 fw-bold w-100" onclick="calculateBMI()">
                            <i class="fas fa-calculator me-2"></i>Analyser ma composition
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-secondary btn-lg px-4 py-3 fw-bold w-100" onclick="resetForm()">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Resultats -->
                <div id="results" class="d-none">
                    <div class="alert alert-success">
                        <h5 class="alert-heading">
                            <i class="fas fa-chart-line me-2"></i>Analyse de Votre Composition Corporelle
                        </h5>
                        
                        <div class="row g-3 mt-3" id="resultsContent">
                            <!-- Les resultats seront injectes ici par JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Limitations de l'IMC -->
<section class="py-5">
    <div class="container">
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Limitations Majeures de l'IMC - Recherches 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    <strong>Position AMA 2023 :</strong> L'Association Médicale Americaine reconnaît l'IMC comme 
                    "une mesure clinique imparfaite" en raison de ses préjugés historiques et de son exclusion raciste.
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-bug text-danger me-2"></i>Problemes Fondamentaux Identifies</h6>
                        <ul>
                            <li><strong>Ne distingue pas :</strong> Muscle, graisse, os et eau</li>
                            <li><strong>Ignore la distribution :</strong> Graisse viscérale vs sous-cutanée</li>
                            <li><strong>Biais demographiques :</strong> Developpe sur hommes belges 1830s</li>
                            <li><strong>Variations ethniques :</strong> Differences métaboliques importantes</li>
                            <li><strong>Effet âge :</strong> Changements composition avec vieillissement</li>
                            <li><strong>Paradoxe athletique :</strong> Sportifs "obeses" selon IMC</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-chart-bar text-info me-2"></i>Données Scientifiques Recentes</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Population</th>
                                        <th>% Mal Classifies</th>
                                        <th>Impact Principal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Athletes NFL</td>
                                        <td class="text-danger fw-bold">95%+</td>
                                        <td>Faux positifs obesite</td>
                                    </tr>
                                    <tr>
                                        <td>Personnes âgées</td>
                                        <td class="text-warning fw-bold">40-60%</td>
                                        <td>Sous-estimation risques</td>
                                    </tr>
                                    <tr>
                                        <td>Population générale US</td>
                                        <td class="text-warning fw-bold">30%</td>
                                        <td>75 millions mal classifies</td>
                                    </tr>
                                    <tr>
                                        <td>Femmes post-ménopause</td>
                                        <td class="text-warning fw-bold">35-45%</td>
                                        <td>Graisse viscérale ignoree</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-warning alert-sm">
                            <small>
                                <strong>Paradoxe métabolique :</strong> 29% des "obeses" sont metaboliquement sains, 
                                tandis que 30% des "normaux" sont metaboliquement malades.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alternatives Modernes -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-microscope me-2"></i>
                    Alternatives Modernes Validées Scientifiquement
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-ruler text-primary me-2"></i>Indices Anthropometriques Ameliores</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Indice</th>
                                        <th>Formule</th>
                                        <th>Avantage Principal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>WHtR</strong></td>
                                        <td>Tour taille / Taille</td>
                                        <td>Graisse abdominale</td>
                                    </tr>
                                    <tr>
                                        <td><strong>WHR</strong></td>
                                        <td>Tour taille / Tour hanches</td>
                                        <td>Distribution des graisses</td>
                                    </tr>
                                    <tr>
                                        <td><strong>BRI</strong></td>
                                        <td>Fonction complexe taille-tour</td>
                                        <td>Forme corporelle 3D</td>
                                    </tr>
                                    <tr>
                                        <td><strong>RFM</strong></td>
                                        <td>Taille + tour taille + sexe</td>
                                        <td>% graisse corporelle</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-cogs text-success me-2"></i>Technologies de Précision 2024</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>DEXA (Gold Standard)</strong>
                                <span class="badge bg-success">Précis A 98%</span>
                            </li>
                            <li class="list-group-item">
                                <strong>BIA Multifrequency :</strong> Analyse impedance electrique
                            </li>
                            <li class="list-group-item">
                                <strong>ADP (Bod Pod) :</strong> Déplacement d'air volumetrique
                            </li>
                            <li class="list-group-item">
                                <strong>Ultrasons :</strong> epaisseur graisse sous-cutanée
                            </li>
                            <li class="list-group-item">
                                <strong>IRM/CT Scan :</strong> Graisse viscérale directe
                            </li>
                            <li class="list-group-item">
                                <strong>Anthropometrie 3D :</strong> Scanners corporels complets
                            </li>
                        </ul>
                        
                        <div class="alert alert-success mt-3">
                            <small>
                                <strong>Recommandation AMA 2023 :</strong> Utiliser l'IMC en conjonction avec 
                                des mesures complementaires pour une évaluation complete.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classification IMC Traditionnelle -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-table me-2"></i>
                    Classification IMC Traditionnelle (avec limitations)
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Catégorie IMC</th>
                                <th>Valeur</th>
                                <th>Prévalence</th>
                                <th>Risques Associes</th>
                                <th>Limitations Principales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-info">
                                <td><strong>Insuffisance ponderale</strong></td>
                                <td>&lt; 18.5</td>
                                <td>1-2%</td>
                                <td>Carences, immunite réduite</td>
                                <td>Peut masquer sarcopenie</td>
                            </tr>
                            <tr class="table-success">
                                <td><strong>Poids normal</strong></td>
                                <td>18.5 - 24.9</td>
                                <td>30-35%</td>
                                <td>Risque de base</td>
                                <td><span class="text-danger">30% metaboliquement malades</span></td>
                            </tr>
                            <tr class="table-warning">
                                <td><strong>Surpoids</strong></td>
                                <td>25.0 - 29.9</td>
                                <td>35-40%</td>
                                <td>Risque modéré</td>
                                <td>Inclut athletes muscles</td>
                            </tr>
                            <tr class="table-danger">
                                <td><strong>Obesite</strong></td>
                                <td>≥ 30.0</td>
                                <td>25-30%</td>
                                <td>Risque élevé</td>
                                <td><span class="text-success">29% metaboliquement sains</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Important :</strong> Ces catégories doivent être interprétées dans le contexte 
                    de l'âge, du sexe, de l'ethnicité, de la composition corporelle et des marqueurs métaboliques.
                </div>
            </div>
        </div>

        <!-- Recommandations Pratiques -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="mb-2">
                    <i class="fas fa-lightbulb me-2"></i>
                    Recommandations Pratiques 2024
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Évaluation Complete Recommandée</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>IMC + tour de taille (minimum)</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Composition corporelle (DEXA/BIA)</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Biomarqueurs métaboliques</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Évaluation cardiovasculaire</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Tests fonctionnels</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Historique familial</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Surveillance et Suivi</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-eye text-primary me-2"></i>Monitoring régulier composition</li>
                                    <li><i class="fas fa-calendar text-primary me-2"></i>Évaluation métabolique annuelle</li>
                                    <li><i class="fas fa-users text-primary me-2"></i>Adaptation seuils selon population</li>
                                    <li><i class="fas fa-heart text-primary me-2"></i>Focus qualite de vie et fonction</li>
                                    <li><i class="fas fa-shield text-primary me-2"></i>Prévention primaire personnalisée</li>
                                    <li><i class="fas fa-user-md text-primary me-2"></i>Approche multidisciplinaire</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Nouvelles Perspectives</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-robot text-info me-2"></i>Algorithmes IA predictifs</li>
                                    <li><i class="fas fa-dna text-info me-2"></i>Genomique nutritionnelle</li>
                                    <li><i class="fas fa-bacteria text-info me-2"></i>Microbiome et métabolisme</li>
                                    <li><i class="fas fa-watch text-info me-2"></i>Wearables métaboliques</li>
                                    <li><i class="fas fa-user text-info me-2"></i>Medecine personnalisée</li>
                                    <li><i class="fas fa-crosshairs text-info me-2"></i>Intervention precoce ciblee</li>
                                </ul>
                            </div>
                        </div>
                    </div>
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
</style>
@endpush

@push('scripts')
<script>
function calculateBMI() {
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    const age = parseInt(document.getElementById('age').value);
    const gender = document.getElementById('gender').value;
    const waist = parseFloat(document.getElementById('waist').value);
    
    if (!weight || !height || weight <= 0 || height <= 0) {
        alert('Veuillez saisir un poids et une taille validés');
        return;
    }
    
    const heightM = height / 100;
    const bmi = weight / (heightM * heightM);
    
    let category = '';
    let categoryColor = '';
    let badgeClass = '';
    
    if (bmi < 18.5) {
        category = 'Insuffisance ponderale';
        categoryColor = 'text-info';
        badgeClass = 'bg-info';
    } else if (bmi < 25) {
        category = 'Poids normal';
        categoryColor = 'text-success';
        badgeClass = 'bg-success';
    } else if (bmi < 30) {
        category = 'Surpoids';
        categoryColor = 'text-warning';
        badgeClass = 'bg-warning text-dark';
    } else {
        category = 'Obesite';
        categoryColor = 'text-danger';
        badgeClass = 'bg-danger';
    }

    // Calculs complementaires
    let whtr = null;
    let bri = null;
    let bodyFat = null;
    
    if (waist && waist > 0) {
        whtr = waist / height;
        // Estimation BRI simplifiee
        bri = 364.2 - 365.5 * Math.sqrt(1 - ((waist / (2 * Math.PI)) ** 2) / ((0.5 * height) ** 2));
    }

    // Body Fat estimation (Deurenberg formula)
    if (age && age > 0) {
        const genderFactor = gender === 'male' ? 1 : 0;
        bodyFat = (1.20 * bmi) + (0.23 * age) - (10.8 * genderFactor) - 5.4;
        bodyFat = Math.max(0, bodyFat); // Ne peut pas être negatif
    }

    // Construire les resultats
    let resultsHTML = `
        <div class="col-md-3">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <h6 class="card-title">IMC Traditionnel</h6>
                    <p class="card-text fs-4">
                        <strong class="text-primary">${Math.round(bmi * 10) / 10}</strong>
                    </p>
                    <span class="badge ${badgeClass}">
                        ${category}
                    </span>
                </div>
            </div>
        </div>
    `;
    
    if (bodyFat !== null) {
        resultsHTML += `
            <div class="col-md-3">
                <div class="card border-warning h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title">% Graisse Estimé</h6>
                        <p class="card-text fs-5">
                            <strong>${Math.round(bodyFat * 10) / 10}%</strong>
                        </p>
                        <small class="text-muted">Formule Deurenberg</small>
                    </div>
                </div>
            </div>
        `;
    }
    
    if (whtr !== null) {
        let whtrStatus = '';
        let whtrClass = '';
        if (whtr < 0.5) {
            whtrStatus = 'Excellent';
            whtrClass = 'text-success';
        } else if (whtr < 0.6) {
            whtrStatus = 'Attention';
            whtrClass = 'text-warning';
        } else {
            whtrStatus = 'Risque élevé';
            whtrClass = 'text-danger';
        }
        
        resultsHTML += `
            <div class="col-md-3">
                <div class="card border-info h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title">Ratio Taille/Hauteur</h6>
                        <p class="card-text fs-5">
                            <strong>${Math.round(whtr * 100) / 100}</strong>
                        </p>
                        <small class="${whtrClass} fw-bold">${whtrStatus}</small>
                    </div>
                </div>
            </div>
        `;
    }
    
    if (bri !== null && !isNaN(bri)) {
        resultsHTML += `
            <div class="col-md-3">
                <div class="card border-secondary h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title">BRI (Body Roundness)</h6>
                        <p class="card-text fs-5">
                            <strong>${Math.round(bri * 10) / 10}</strong>
                        </p>
                        <small class="text-muted">Index moderne</small>
                    </div>
                </div>
            </div>
        `;
    }
    
    document.getElementById('resultsContent').innerHTML = resultsHTML;
    document.getElementById('results').classList.remove('d-none');
    
    // Scroll vers les resultats
    document.getElementById('results').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function resetForm() {
    document.getElementById('weight').value = '';
    document.getElementById('height').value = '';
    document.getElementById('age').value = '';
    document.getElementById('waist').value = '';
    document.getElementById('gender').value = 'male';
    document.getElementById('results').classList.add('d-none');
}
</script>
@endpush