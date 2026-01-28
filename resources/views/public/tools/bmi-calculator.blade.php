@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', 'Comment Calculer IMC & Composition Corporelle - Hassan EL HAOUAT')
@section('meta_description', 'Calculez votre IMC et analysez votre composition corporelle avec nos outils avancés, incluant % de graisse, WHtR et BRI. Informations scientifiques récentes.')

{{-- Open Graph / Facebook --}}
@section('og_type', 'website')
@section('og_title', 'Calculateur IMC & Composition Corporelle')
@section('og_description', 'Outil avancé d\'analyse de composition corporelle basé sur les dernières recherches scientifiques')
@section('og_url', route('tools.bmi'))

@section('content')

<!-- Section titre -->
<section class="py-5 text-center nataswim-titre3">

<div class="container-lg">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-2 mb-lg-0" style=" background-color: #f9f5f4; color: #63d0c7; padding: 20px 0px; border-radius: 10px; ">
                <h1 class="display-5 fw-bold mb-3">Calculateur IMC</h1>
                <p>Composition Corporelle - Calculez votre IMC et analysez votre composition corporelle</p>
            </div>
            <div class="col-lg-5 text-center">
                <a href="{{ route('posts.public.index') }}">
                    <img src="{{ asset('assets/images/team/nataswim-sport-net-systemes-3.jpg') }}"
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
                <h3 class="text-center mb-4">Calculateur Avance</h3>
                
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
                            <i class="fas fa-redo me-2"></i>Reinitialiser
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
                    <strong>Position AMA 2023 :</strong> L'Association Medicale Americaine reconnaît l'IMC comme 
                    "une mesure clinique imparfaite" en raison de ses prejuges historiques et de son exclusion raciste.
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-bug text-danger me-2"></i>Problemes Fondamentaux Identifies</h6>
                        <ul>
                            <li><strong>Ne distingue pas :</strong> Muscle, graisse, os et eau</li>
                            <li><strong>Ignore la distribution :</strong> Graisse viscerale vs sous-cutanee</li>
                            <li><strong>Biais demographiques :</strong> Developpe sur hommes belges 1830s</li>
                            <li><strong>Variations ethniques :</strong> Differences metaboliques importantes</li>
                            <li><strong>Effet âge :</strong> Changements composition avec vieillissement</li>
                            <li><strong>Paradoxe athletique :</strong> Sportifs "obeses" selon IMC</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-chart-bar text-info me-2"></i>Donnees Scientifiques Recentes</h6>
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
                                        <td>Personnes âgees</td>
                                        <td class="text-warning fw-bold">40-60%</td>
                                        <td>Sous-estimation risques</td>
                                    </tr>
                                    <tr>
                                        <td>Population generale US</td>
                                        <td class="text-warning fw-bold">30%</td>
                                        <td>75 millions mal classifies</td>
                                    </tr>
                                    <tr>
                                        <td>Femmes post-menopause</td>
                                        <td class="text-warning fw-bold">35-45%</td>
                                        <td>Graisse viscerale ignoree</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-warning alert-sm">
                            <small>
                                <strong>Paradoxe metabolique :</strong> 29% des "obeses" sont metaboliquement sains, 
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
                    Alternatives Modernes Validees Scientifiquement
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
                        <h6><i class="fas fa-cogs text-success me-2"></i>Technologies de Precision 2024</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>DEXA (Gold Standard)</strong>
                                <span class="badge bg-success">Precis A 98%</span>
                            </li>
                            <li class="list-group-item">
                                <strong>BIA Multifrequency :</strong> Analyse impedance electrique
                            </li>
                            <li class="list-group-item">
                                <strong>ADP (Bod Pod) :</strong> Deplacement d'air volumetrique
                            </li>
                            <li class="list-group-item">
                                <strong>Ultrasons :</strong> epaisseur graisse sous-cutanee
                            </li>
                            <li class="list-group-item">
                                <strong>IRM/CT Scan :</strong> Graisse viscerale directe
                            </li>
                            <li class="list-group-item">
                                <strong>Anthropometrie 3D :</strong> Scanners corporels complets
                            </li>
                        </ul>
                        
                        <div class="alert alert-success mt-3">
                            <small>
                                <strong>Recommandation AMA 2023 :</strong> Utiliser l'IMC en conjonction avec 
                                des mesures complementaires pour une evaluation complete.
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
                                <th>Categorie IMC</th>
                                <th>Valeur</th>
                                <th>Prevalence</th>
                                <th>Risques Associes</th>
                                <th>Limitations Principales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-info">
                                <td><strong>Insuffisance ponderale</strong></td>
                                <td>&lt; 18.5</td>
                                <td>1-2%</td>
                                <td>Carences, immunite reduite</td>
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
                                <td>Risque modere</td>
                                <td>Inclut athletes muscles</td>
                            </tr>
                            <tr class="table-danger">
                                <td><strong>Obesite</strong></td>
                                <td>≥ 30.0</td>
                                <td>25-30%</td>
                                <td>Risque eleve</td>
                                <td><span class="text-success">29% metaboliquement sains</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Important :</strong> Ces categories doivent être interpretees dans le contexte 
                    de l'âge, du sexe, de l'ethnicite, de la composition corporelle et des marqueurs metaboliques.
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
                                <h6 class="mb-0">evaluation Complete Recommandee</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>IMC + tour de taille (minimum)</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Composition corporelle (DEXA/BIA)</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Biomarqueurs metaboliques</li>
                                    <li><i class="fas fa-check text-success me-2"></i>evaluation cardiovasculaire</li>
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
                                    <li><i class="fas fa-eye text-primary me-2"></i>Monitoring regulier composition</li>
                                    <li><i class="fas fa-calendar text-primary me-2"></i>evaluation metabolique annuelle</li>
                                    <li><i class="fas fa-users text-primary me-2"></i>Adaptation seuils selon population</li>
                                    <li><i class="fas fa-heart text-primary me-2"></i>Focus qualite de vie et fonction</li>
                                    <li><i class="fas fa-shield text-primary me-2"></i>Prevention primaire personnalisee</li>
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
                                    <li><i class="fas fa-bacteria text-info me-2"></i>Microbiome et metabolisme</li>
                                    <li><i class="fas fa-watch text-info me-2"></i>Wearables metaboliques</li>
                                    <li><i class="fas fa-user text-info me-2"></i>Medecine personnalisee</li>
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
        alert('Veuillez saisir un poids et une taille valides');
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
                        <h6 class="card-title">% Graisse Estime</h6>
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
            whtrStatus = 'Risque eleve';
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