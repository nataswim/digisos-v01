@extends('layouts.public')

@section('title', 'Chronometre Pro Entraîneurs & Configuration Avancee - Hassan EL HAOUAT')
@section('meta_description', 'Chronometre professionnel multi-nageurs avec configuration avancee, export CSV, sauvegarde automatique, raccourcis clavier et sons. Outil indispensable pour entraîneurs professionnels.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-center nataswim-titre3">

<div class="container-lg">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-2 mb-lg-0" style=" background-color: #f9f5f4; color: #63d0c7; padding: 20px 0px; border-radius: 10px; ">
                <h1 class="display-5 fw-bold mb-3">Chronometre Avec Configuration Avancee</h1>
                <p>Chronometre  multi-nageurs avec configuration avancee, export CSV, sauvegarde automatique, raccourcis clavier et sons.</p>
            </div>
            <div class="col-lg-5 text-center">
                <a href="{{ route('posts.public.index') }}">
                    <img src="{{ asset('assets/images/team/nataswim-application-banner-5.jpg') }}"
                        alt="Guide Nataswim"
                        class="img-fluid rounded-4"
                        style="max-height: 300px; object-fit: cover;">
                </a>

            </div>
        </div>
    </div>
</section>

<!-- Barre d'Actions -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div id="statusBadge" class="badge fs-5 px-3 py-2 bg-secondary">
                    Prêt
                </div>
                <div id="saveSuccess" class="alert alert-success py-1 px-3 mb-0 d-none">
                    <i class="fas fa-save me-2"></i>Course sauvegardee !
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button id="configBtn" class="btn btn-danger btn-lg">
                    <i class="fas fa-cog me-2"></i>Configuration
                </button>
                <button id="soundToggle" class="btn btn-info btn-lg">
                    <i class="fas fa-volume-up"></i>
                </button>
                <button id="helpBtn" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-question-circle"></i>
                </button>
                <div id="raceActions" class="d-none">
                    <button id="saveRaceBtn" class="btn btn-success me-2">
                        <i class="fas fa-save me-2"></i>Sauvegarder
                    </button>
                    <button id="exportCSVBtn" class="btn btn-success">
                        <i class="fas fa-water me-2"></i>Export CSV
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Aide Raccourcis Clavier -->
<div id="keyboardHelp" class="alert alert-warning mx-3 mt-3">
    <h6><i class="fas fa-keyboard me-2"></i>Raccourcis Clavier :</h6>
    <div class="row small">
        <div class="col-md-6">
            <strong>Espace :</strong> Demarrer/Pause<br>
            <strong>Chiffres 1-8 :</strong> Passage nageur<br>
            <strong>Shift + Chiffres :</strong> Arrivee nageur
        </div>
        <div class="col-md-6">
            <strong>Ctrl+R :</strong> Reset<br>
            <strong>Ctrl+S :</strong> Sauvegarder<br>
            <strong>Ctrl+E :</strong> Export CSV
        </div>
    </div>
</div>

<!-- Chronometre Principal -->
<section class="py-5 bg-light">
    <div class="container">
        <!-- Affichage principal du temps -->
        <div class="card shadow-lg border-0 mb-4" style="background: linear-gradient(135deg, #006170 0%, #004d5a 100%);">
            <div class="card-body text-center py-5">
                <div class="text-white mb-4">
                    <div id="mainTimer" class="display-1 fw-bold mb-3" style="font-family: 'Courier New', monospace; font-size: 4rem;">
                        00:00.00
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <button id="startStopBtn" class="btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg">
                            <i class="fas fa-play me-2"></i>Depart
                        </button>
                        <button id="resetBtn" class="btn btn-dark btn-lg px-5 py-3 fw-bold shadow-lg">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contrôles Nageurs Dynamiques -->
        <div id="swimmersContainer" class="row g-4 mb-4">
            <!-- Sera rempli par JavaScript -->
        </div>

        <!-- Resultats Finaux -->
        <div id="finalResults" class="card shadow-lg border-0 mb-4 d-none">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Classement Final
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center py-3">Pos</th>
                                <th class="text-center py-3">Nageur</th>
                                <th class="text-center py-3">Temps Final</th>
                                <th class="text-center py-3">Difference</th>
                                <th class="text-center py-3">Passages</th>
                                <th class="text-center py-3">Moyenne</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody">
                            <!-- Sera rempli par JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Details des Temps -->
        <div id="lapDetails" class="d-none">
            <!-- Sera rempli par JavaScript -->
        </div>
    </div>
</section>

<!-- Modal Configuration -->
<div id="configModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-cog me-2"></i>Configuration Chronometre Pro
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-users me-2 text-primary"></i>
                        Nombre de nageurs
                    </label>
                    <select id="swimmerCountSelect" class="form-select form-select-lg">
                        <option value="2">2 nageurs</option>
                        <option value="3" selected>3 nageurs</option>
                        <option value="4">4 nageurs</option>
                        <option value="6">6 nageurs</option>
                        <option value="8">8 nageurs</option>
                    </select>
                    <div class="form-text">Nombre de nageurs A chronometrer simultanement</div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-stopwatch me-2 text-success"></i>
                        Precision du chronometre
                    </label>
                    <select id="precisionSelect" class="form-select form-select-lg">
                        <option value="10" selected>Centiemes (0.01s) - Competition</option>
                        <option value="100">Dixiemes (0.1s) - Entraînement</option>
                        <option value="1000">Secondes (1s) - Loisir</option>
                    </select>
                    <div class="form-text">Plus la precision est elevee, plus la mesure est fine</div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" id="soundEnabledCheck" class="form-check-input" checked>
                            <label class="form-check-label" for="soundEnabledCheck">
                                <i class="fas fa-volume-up me-2"></i>
                                Sons de notification
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" id="autoSaveCheck" class="form-check-input" checked>
                            <label class="form-check-label" for="autoSaveCheck">
                                <i class="fas fa-save me-2"></i>
                                Sauvegarde automatique
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-check form-switch mb-3">
                    <input type="checkbox" id="showHelpCheck" class="form-check-input" checked>
                    <label class="form-check-label" for="showHelpCheck">
                        <i class="fas fa-keyboard me-2"></i>
                        Afficher l'aide clavier
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="saveConfigBtn" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmation Reset -->
<div id="confirmResetModal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation Reset</h5>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir remettre le chronometre A zero ? Toutes les donnees non sauvegardees seront perdues.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirmResetBtn" class="btn btn-danger">Reset</button>
            </div>
        </div>
    </div>
</div>

<!-- Informations educatives -->
<section class="py-5">
    <div class="container">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-2">
                    <i class="fas fa-star me-2"></i>
                    Fonctionnalites Professionnelles
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6><i class="fas fa-cog me-2 text-primary"></i>Configuration Avancee</h6>
                        <ul>
                            <li>Gestion de 2 A 8 nageurs simultanement</li>
                            <li>Precision ajustable (centiemes A secondes)</li>
                            <li>Noms de nageurs personnalisables</li>
                            <li>Interface adaptative selon le nombre</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-keyboard me-2 text-success"></i>Raccourcis Professionnels</h6>
                        <ul>
                            <li>Contrôle clavier complet</li>
                            <li>Passages rapides par numero</li>
                            <li>Arrivees avec Shift+Numero</li>
                            <li>Sauvegarde et export directs</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-save me-2 text-warning"></i>Gestion des Donnees</h6>
                        <ul>
                            <li>Sauvegarde locale automatique</li>
                            <li>Export CSV detaille</li>
                            <li>Historique des 10 dernieres courses</li>
                            <li>Donnees persistantes navigateur</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-volume-up me-2 text-info"></i>Notifications Audio</h6>
                        <ul>
                            <li>Sons distincts par action</li>
                            <li>Depart, passage, arrivee</li>
                            <li>Activation/desactivation facile</li>
                            <li>Compatible avec concentration</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cas d'Usage Professionnels -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3 class="mb-2">
                    <i class="fas fa-users me-2"></i>
                    Cas d'Usage Professionnels
                </h3>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Entraînements en Club</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Series chronometrees multiples</li>
                                    <li>Suivi progression individuelle</li>
                                    <li>Analyse comparative groupes</li>
                                    <li>Donnees pour planification</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Competitions Locales</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Chronometrage backup officiel</li>
                                    <li>Verification temps automatique</li>
                                    <li>Export direct resultats</li>
                                    <li>Gestion plusieurs series</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Tests et evaluations</h6>
                            </div>
                            <div class="card-body">
                                <ul class="small">
                                    <li>Tests standardises (T30, 400m)</li>
                                    <li>evaluations techniques</li>
                                    <li>Suivi performance saisonniere</li>
                                    <li>Analyses statistiques poussees</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avantages vs Version Standard -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h3 class="mb-2">
                    <i class="fas fa-arrow-up me-2"></i>
                    Avantages vs Chronometre Standard
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Fonctionnalite</th>
                                <th class="text-center">Standard</th>
                                <th class="text-center">Pro</th>
                                <th>Avantage Pro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Nombre de nageurs</strong></td>
                                <td class="text-center">3 fixe</td>
                                <td class="text-center"><span class="badge bg-success">2-8 variable</span></td>
                                <td>Adaptation A tout contexte</td>
                            </tr>
                            <tr>
                                <td><strong>Precision</strong></td>
                                <td class="text-center">Centiemes</td>
                                <td class="text-center"><span class="badge bg-success">Configurable</span></td>
                                <td>Optimisation selon usage</td>
                            </tr>
                            <tr>
                                <td><strong>Raccourcis clavier</strong></td>
                                <td class="text-center">Basiques</td>
                                <td class="text-center"><span class="badge bg-success">Complets</span></td>
                                <td>Efficacite maximale</td>
                            </tr>
                            <tr>
                                <td><strong>Sauvegarde</strong></td>
                                <td class="text-center">-</td>
                                <td class="text-center"><span class="badge bg-success">Auto + Manuel</span></td>
                                <td>Securite des donnees</td>
                            </tr>
                            <tr>
                                <td><strong>Export donnees</strong></td>
                                <td class="text-center">-</td>
                                <td class="text-center"><span class="badge bg-success">CSV detaille</span></td>
                                <td>Analyse post-course</td>
                            </tr>
                            <tr>
                                <td><strong>Interface</strong></td>
                                <td class="text-center">Fixe</td>
                                <td class="text-center"><span class="badge bg-success">Adaptative</span></td>
                                <td>UX optimisee</td>
                            </tr>
                        </tbody>
                    </table>
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

#mainTimer {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.swimmer-card .lap-btn, .swimmer-card .finish-btn {
    transition: all 0.3s ease;
}

.swimmer-card .lap-btn:hover, .swimmer-card .finish-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.swimmer-card .lap-btn:disabled, .swimmer-card .finish-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.status-running {
    animation: pulse 1s infinite;
}

.swimmer-name-input {
    background: transparent !important;
    border: none !important;
    text-align: center;
    font-weight: bold;
}

.swimmer-name-input:focus {
    outline: 2px solid #0d6efd;
    border-radius: 4px;
}
</style>
@endpush

@push('scripts')
<script>
class ChronometerPro {
    constructor() {
        this.config = this.loadConfig();
        this.time = 0;
        this.isRunning = false;
        this.raceFinished = false;
        this.interval = null;
        this.swimmers = [];
        this.colors = ['#0d6efd', '#dc3545', '#198754', '#ffc107', '#6f42c1', '#fd7e14', '#20c997', '#6c757d'];
        
        this.initializeSwimmers();
        this.initEventListeners();
        this.updateDisplay();
        this.updateKeyboardHelp();
        this.setupKeyboardShortcuts();
    }
    
    loadConfig() {
        const defaultConfig = {
            swimmerCount: 3,
            precision: 10,
            soundEnabled: true,
            autoSave: true,
            showKeyboardHelp: true
        };
        
        try {
            const saved = localStorage.getItem('chronometerPro_config');
            return saved ? { ...defaultConfig, ...JSON.parse(saved) } : defaultConfig;
        } catch {
            return defaultConfig;
        }
    }
    
    saveConfig() {
        try {
            localStorage.setItem('chronometerPro_config', JSON.stringify(this.config));
        } catch (error) {
            console.warn('Failed to save config:', error);
        }
    }
    
    initializeSwimmers() {
        this.swimmers = Array.from({ length: this.config.swimmerCount }, (_, i) => ({
            id: i + 1,
            name: `Athlete ${i + 1}`,
            laps: [],
            finishTime: null
        }));
        this.renderSwimmers();
    }
    
    renderSwimmers() {
        const container = document.getElementById('swimmersContainer');
        const colSize = this.config.swimmerCount <= 4 ? 12 / this.config.swimmerCount : 6;
        
        container.innerHTML = this.swimmers.map((swimmer, idx) => `
            <div class="col-lg-${colSize} col-md-6" id="swimmer${swimmer.id}">
                <div class="swimmer-card card border-0 shadow-sm h-100" 
                     style="border-left: 5px solid ${this.colors[idx % this.colors.length]};">
                    <div class="card-header text-white" 
                         style="background: ${this.colors[idx % this.colors.length]};">
                        <input type="text" 
                               class="swimmer-name-input text-white" 
                               value="${swimmer.name}"
                               data-swimmer="${swimmer.id}"
                               placeholder="Nom du nageur"
                               maxlength="20">
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex flex-column gap-2">
                            <button class="lap-btn btn btn-info btn-lg fw-bold" data-swimmer="${swimmer.id}">
                                <i class="fas fa-flag me-2"></i>Passage
                                <span class="lap-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
                            </button>
                            <button class="finish-btn btn btn-warning btn-lg fw-bold" data-swimmer="${swimmer.id}">
                                <i class="fas fa-trophy me-2"></i>Arrivee
                            </button>
                        </div>
                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="d-block"><strong>Passages:</strong> <span class="passages-count">0</span></small>
                            <small class="d-block"><strong>Moyenne:</strong> <span class="avg-time">00:00.00</span></small>
                            <small class="d-block"><strong>Statut:</strong> <span class="status badge bg-secondary">Prêt</span></small>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    initEventListeners() {
        // Boutons principaux
        document.getElementById('startStopBtn').addEventListener('click', () => this.toggleTimer());
        document.getElementById('resetBtn').addEventListener('click', () => this.showResetConfirm());
        
        // Configuration - VERSION CORRIGeE
        document.getElementById('configBtn').addEventListener('click', (e) => {
            e.preventDefault();
            console.log('Configuration clicked'); // Debug
            this.showConfigModal();
        });
        
        document.getElementById('saveConfigBtn').addEventListener('click', () => this.saveConfiguration());
        document.getElementById('soundToggle').addEventListener('click', () => this.toggleSound());
        document.getElementById('helpBtn').addEventListener('click', () => this.toggleKeyboardHelp());
        
        // Actions de course
        document.getElementById('saveRaceBtn').addEventListener('click', () => this.saveRace());
        document.getElementById('exportCSVBtn').addEventListener('click', () => this.exportCSV());
        
        // Confirmation reset - VERSION CORRIGeE
        document.getElementById('confirmResetBtn').addEventListener('click', () => {
            this.confirmReset();
        });
        
        // Fermeture des modals avec les boutons close
        document.querySelectorAll('.btn-close').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal');
                if (modal) {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                }
            });
        });
        
        // Fermeture des modals en cliquant A l'exterieur
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
                e.target.classList.remove('show');
            }
        });
        
        // evenements delegues pour les nageurs
        document.getElementById('swimmersContainer').addEventListener('click', (e) => {
            if (e.target.classList.contains('lap-btn') || e.target.closest('.lap-btn')) {
                const btn = e.target.classList.contains('lap-btn') ? e.target : e.target.closest('.lap-btn');
                const swimmerId = parseInt(btn.dataset.swimmer);
                this.recordLap(swimmerId);
            } else if (e.target.classList.contains('finish-btn') || e.target.closest('.finish-btn')) {
                const btn = e.target.classList.contains('finish-btn') ? e.target : e.target.closest('.finish-btn');
                const swimmerId = parseInt(btn.dataset.swimmer);
                this.finishSwimmer(swimmerId);
            }
        });
        
        // Noms des nageurs
        document.getElementById('swimmersContainer').addEventListener('input', (e) => {
            if (e.target.classList.contains('swimmer-name-input')) {
                const swimmerId = parseInt(e.target.dataset.swimmer);
                const swimmer = this.swimmers.find(s => s.id === swimmerId);
                if (swimmer) {
                    swimmer.name = e.target.value || `Athlete ${swimmerId}`;
                }
            }
        });
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.target.tagName === 'INPUT' && !e.target.classList.contains('swimmer-name-input')) return;
            
            switch (e.code) {
                case 'Space':
                    e.preventDefault();
                    this.toggleTimer();
                    break;
                case 'KeyR':
                    if (e.ctrlKey) {
                        e.preventDefault();
                        this.showResetConfirm();
                    }
                    break;
                case 'KeyS':
                    if (e.ctrlKey && this.raceFinished) {
                        e.preventDefault();
                        this.saveRace();
                    }
                    break;
                case 'KeyE':
                    if (e.ctrlKey && this.raceFinished) {
                        e.preventDefault();
                        this.exportCSV();
                    }
                    break;
                default:
                    // Chiffres 1-8 pour passages
                    const match = e.code.match(/^Digit(\d)$/);
                    if (match) {
                        e.preventDefault();
                        const swimmerIndex = parseInt(match[1]) - 1;
                        if (this.swimmers[swimmerIndex] && !this.swimmers[swimmerIndex].finishTime) {
                            if (e.shiftKey) {
                                this.finishSwimmer(this.swimmers[swimmerIndex].id);
                            } else {
                                this.recordLap(this.swimmers[swimmerIndex].id);
                            }
                        }
                    }
            }
        });
    }
    
    // VERSION CORRIGeE - Sans dependance Bootstrap
    showConfigModal() {
        console.log('Opening config modal'); // Debug
        
        // Pre-remplir les valeurs actuelles
        document.getElementById('swimmerCountSelect').value = this.config.swimmerCount;
        document.getElementById('precisionSelect').value = this.config.precision;
        document.getElementById('soundEnabledCheck').checked = this.config.soundEnabled;
        document.getElementById('autoSaveCheck').checked = this.config.autoSave;
        document.getElementById('showHelpCheck').checked = this.config.showKeyboardHelp;
        
        // Afficher le modal (methode native)
        const modal = document.getElementById('configModal');
        modal.style.display = 'block';
        modal.classList.add('show');
        
        // Ajouter la classe au body pour l'effet backdrop
        document.body.classList.add('modal-open');
        
        // Focus sur le modal
        modal.focus();
    }
    
    hideConfigModal() {
        const modal = document.getElementById('configModal');
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }
    
    showResetConfirm() {
        const modal = document.getElementById('confirmResetModal');
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
    }
    
    hideResetModal() {
        const modal = document.getElementById('confirmResetModal');
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }
    
    saveConfiguration() {
        const newConfig = {
            swimmerCount: parseInt(document.getElementById('swimmerCountSelect').value),
            precision: parseInt(document.getElementById('precisionSelect').value),
            soundEnabled: document.getElementById('soundEnabledCheck').checked,
            autoSave: document.getElementById('autoSaveCheck').checked,
            showKeyboardHelp: document.getElementById('showHelpCheck').checked
        };
        
        const needsReset = newConfig.swimmerCount !== this.config.swimmerCount || 
                          newConfig.precision !== this.config.precision;
        
        this.config = newConfig;
        this.saveConfig();
        
        if (needsReset) {
            this.reset();
            this.initializeSwimmers();
        }
        
        this.updateKeyboardHelp();
        this.updateSoundToggle();
        
        this.hideConfigModal();
        
        // Notification de sauvegarde
        alert('Configuration sauvegardee avec succes !');
    }
    
    confirmReset() {
        this.reset();
        this.hideResetModal();
    }
    
    // ... [Reste du code identique] ...
    
    toggleTimer() {
        if (this.raceFinished) return;
        
        if (!this.isRunning) {
            this.interval = setInterval(() => {
                this.time += this.config.precision;
                this.updateDisplay();
            }, this.config.precision);
            
            document.getElementById('startStopBtn').innerHTML = '<i class="fas fa-pause me-2"></i>Pause';
            document.getElementById('startStopBtn').className = 'btn btn-danger btn-lg px-5 py-3 fw-bold shadow-lg';
            document.getElementById('statusBadge').textContent = 'En cours';
            document.getElementById('statusBadge').className = 'badge fs-5 px-3 py-2 bg-success status-running';
            
            this.enableSwimmerButtons();
            this.playSound('start');
        } else {
            clearInterval(this.interval);
            document.getElementById('startStopBtn').innerHTML = '<i class="fas fa-play me-2"></i>Reprendre';
            document.getElementById('startStopBtn').className = 'btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg';
            document.getElementById('statusBadge').textContent = 'Pause';
            document.getElementById('statusBadge').className = 'badge fs-5 px-3 py-2 bg-warning text-dark';
            
            this.disableSwimmerButtons();
            this.playSound('pause');
        }
        
        this.isRunning = !this.isRunning;
    }
    
    recordLap(swimmerId) {
        if (!this.isRunning) return;
        
        const swimmer = this.swimmers.find(s => s.id === swimmerId);
        if (!swimmer || swimmer.finishTime) return;
        
        const lastLapTime = swimmer.laps.length > 0 ? 
            swimmer.laps[swimmer.laps.length - 1].time : 0;
        
        const lap = {
            number: swimmer.laps.length + 1,
            time: this.time,
            splitTime: this.time - lastLapTime
        };
        
        swimmer.laps.push(lap);
        this.updateSwimmerDisplay(swimmerId);
        this.updateLapDetails();
        this.playSound('lap');
    }
    
    finishSwimmer(swimmerId) {
        if (!this.isRunning) return;
        
        const swimmer = this.swimmers.find(s => s.id === swimmerId);
        if (!swimmer || swimmer.finishTime) return;
        
        swimmer.finishTime = this.time;
        this.updateSwimmerDisplay(swimmerId);
        this.playSound('finish');
        
        // Verifier si tous les nageurs ont fini
        const finishedCount = this.swimmers.filter(s => s.finishTime !== null).length;
        if (finishedCount === this.swimmers.length) {
            this.endRace();
        }
        
        this.showFinalResults();
    }
    
    endRace() {
        clearInterval(this.interval);
        this.isRunning = false;
        this.raceFinished = true;
        
        document.getElementById('startStopBtn').innerHTML = '<i class="fas fa-check me-2"></i>Termine';
        document.getElementById('startStopBtn').className = 'btn btn-success btn-lg px-5 py-3 fw-bold shadow-lg';
        document.getElementById('startStopBtn').disabled = true;
        
        document.getElementById('statusBadge').textContent = 'Termine';
        document.getElementById('statusBadge').className = 'badge fs-5 px-3 py-2 bg-danger';
        
        document.getElementById('raceActions').classList.remove('d-none');
        this.disableSwimmerButtons();
        
        if (this.config.autoSave) {
            setTimeout(() => this.saveRace(), 1000);
        }
    }
    
    reset() {
        clearInterval(this.interval);
        this.time = 0;
        this.isRunning = false;
        this.raceFinished = false;
        
        // Reinitialiser les nageurs
        this.swimmers.forEach(swimmer => {
            swimmer.laps = [];
            swimmer.finishTime = null;
        });
        
        // Reinitialiser l'interface
        document.getElementById('startStopBtn').innerHTML = '<i class="fas fa-play me-2"></i>Depart';
        document.getElementById('startStopBtn').className = 'btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg';
        document.getElementById('startStopBtn').disabled = false;
        
        document.getElementById('statusBadge').textContent = 'Prêt';
        document.getElementById('statusBadge').className = 'badge fs-5 px-3 py-2 bg-secondary';
        
        document.getElementById('finalResults').classList.add('d-none');
        document.getElementById('lapDetails').classList.add('d-none');
        document.getElementById('raceActions').classList.add('d-none');
        
        this.updateDisplay();
        this.swimmers.forEach(swimmer => this.updateSwimmerDisplay(swimmer.id));
        this.disableSwimmerButtons();
    }
    
    updateDisplay() {
        document.getElementById('mainTimer').textContent = this.formatTime(this.time);
    }
    
    updateSwimmerDisplay(swimmerId) {
        const swimmer = this.swimmers.find(s => s.id === swimmerId);
        const swimmerElement = document.getElementById(`swimmer${swimmerId}`);
        
        // Passages
        const passagesElement = swimmerElement.querySelector('.passages-count');
        passagesElement.textContent = swimmer.laps.length;
        
        // Badge count
        const lapButton = swimmerElement.querySelector('.lap-btn');
        const countBadge = lapButton.querySelector('.lap-count');
        if (swimmer.laps.length > 0) {
            countBadge.textContent = swimmer.laps.length;
            countBadge.style.display = 'block';
        } else {
            countBadge.style.display = 'none';
        }
        
        // Moyenne
        const avgElement = swimmerElement.querySelector('.avg-time');
        if (swimmer.laps.length > 0) {
            const totalSplitTime = swimmer.laps.reduce((sum, lap) => sum + lap.splitTime, 0);
            avgElement.textContent = this.formatTime(totalSplitTime / swimmer.laps.length);
        } else {
            avgElement.textContent = '00:00.00';
        }
        
        // Statut
        const statusElement = swimmerElement.querySelector('.status');
        const finishBtn = swimmerElement.querySelector('.finish-btn');
        
        if (swimmer.finishTime) {
            statusElement.textContent = 'Arrive';
            statusElement.className = 'status badge bg-success';
            finishBtn.innerHTML = `<i class="fas fa-trophy me-2"></i>${this.formatTime(swimmer.finishTime)}`;
            finishBtn.disabled = true;
            lapButton.disabled = true;
        } else if (this.isRunning) {
            statusElement.textContent = 'En course';
            statusElement.className = 'status badge bg-primary';
        } else {
            statusElement.textContent = 'Prêt';
            statusElement.className = 'status badge bg-secondary';
        }
    }
    
    showFinalResults() {
        const finishedSwimmers = this.swimmers
            .filter(s => s.finishTime !== null)
            .sort((a, b) => a.finishTime - b.finishTime);
        
        if (finishedSwimmers.length === 0) return;
        
        const tbody = document.getElementById('resultsTableBody');
        const bestTime = finishedSwimmers[0].finishTime;
        
        tbody.innerHTML = finishedSwimmers.map((swimmer, index) => {
            const diff = swimmer.finishTime - bestTime;
            const diffText = diff === 0 ? '-' : '+' + this.formatTime(diff);
            const avgTime = swimmer.laps.length > 0 ? 
                this.formatTime(swimmer.laps.reduce((sum, lap) => sum + lap.splitTime, 0) / swimmer.laps.length) : '-';
            
            let badgeClass = 'bg-primary';
            if (index === 0) badgeClass = 'bg-warning';
            else if (index === 1) badgeClass = 'bg-secondary';
            else if (index === 2) badgeClass = 'bg-dark';
            
            return `
                <tr>
                    <td class="text-center py-3">
                        <span class="badge ${badgeClass} fs-6">${index + 1}</span>
                    </td>
                    <td class="text-center fw-semibold py-3">${swimmer.name}</td>
                    <td class="text-center py-3">
                        <span class="badge bg-success fs-6 px-3 py-2">${this.formatTime(swimmer.finishTime)}</span>
                    </td>
                    <td class="text-center text-muted py-3">${diffText}</td>
                    <td class="text-center text-muted py-3">${swimmer.laps.length}</td>
                    <td class="text-center text-muted py-3">${avgTime}</td>
                </tr>
            `;
        }).join('');
        
        document.getElementById('finalResults').classList.remove('d-none');
    }
    
    updateLapDetails() {
        const details = document.getElementById('lapDetails');
        details.innerHTML = '';
        
        this.swimmers.forEach((swimmer, idx) => {
            if (swimmer.laps.length === 0) return;
            
            const color = this.colors[idx % this.colors.length];
            
            const card = document.createElement('div');
            card.className = 'card border-0 shadow-sm mb-4';
            card.innerHTML = `
                <div class="card-header py-3" style="background: ${color}; color: white;">
                    <h5 class="mb-0 fw-bold">${swimmer.name} - Details des Temps de Passage</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center fw-bold py-3">Passage</th>
                                    <th class="text-center fw-bold py-3">Temps Intermediaire</th>
                                    <th class="text-center fw-bold py-3">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${swimmer.laps.map(lap => `
                                    <tr>
                                        <td class="text-center fw-semibold py-3">${lap.number}</td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-primary fs-6 px-3 py-2">${this.formatTime(lap.splitTime)}</span>
                                        </td>
                                        <td class="text-center text-muted py-3">${this.formatTime(lap.time)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            details.appendChild(card);
        });
        
        details.classList.remove('d-none');
    }
    
    toggleSound() {
        this.config.soundEnabled = !this.config.soundEnabled;
        this.updateSoundToggle();
        this.saveConfig();
    }
    
    updateSoundToggle() {
        const btn = document.getElementById('soundToggle');
        if (this.config.soundEnabled) {
            btn.innerHTML = '<i class="fas fa-volume-up"></i>';
            btn.className = 'btn btn-info btn-lg';
        } else {
            btn.innerHTML = '<i class="fas fa-volume-mute"></i>';
            btn.className = 'btn btn-outline-secondary btn-lg';
        }
    }
    
    toggleKeyboardHelp() {
        this.config.showKeyboardHelp = !this.config.showKeyboardHelp;
        this.updateKeyboardHelp();
        this.saveConfig();
    }
    
    updateKeyboardHelp() {
        const help = document.getElementById('keyboardHelp');
        if (this.config.showKeyboardHelp) {
            help.classList.remove('d-none');
        } else {
            help.classList.add('d-none');
        }
    }
    
    enableSwimmerButtons() {
        document.querySelectorAll('.lap-btn, .finish-btn').forEach(btn => {
            if (!btn.closest('.swimmer-card').querySelector('.finish-btn').textContent.includes(':')) {
                btn.disabled = false;
            }
        });
    }
    
    disableSwimmerButtons() {
        document.querySelectorAll('.lap-btn, .finish-btn').forEach(btn => {
            btn.disabled = true;
        });
    }
    
    playSound(type) {
        if (!this.config.soundEnabled) return;
        
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            const frequencies = { start: 800, lap: 600, finish: 1000, pause: 400 };
            oscillator.frequency.setValueAtTime(frequencies[type] || 600, audioContext.currentTime);
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.2);
        } catch (error) {
            console.warn('Audio not supported:', error);
        }
    }
    
    saveRace() {
        if (!this.raceFinished) return;
        
        try {
            const savedRaces = JSON.parse(localStorage.getItem('chronometerPro_races') || '[]');
            const raceData = {
                id: Date.now(),
                timestamp: new Date().toISOString(),
                date: new Date().toLocaleDateString('fr-FR'),
                swimmers: this.swimmers.map(s => ({
                    name: s.name,
                    laps: s.laps,
                    finishTime: s.finishTime
                })),
                totalTime: this.time,
                config: { ...this.config }
            };
            
            savedRaces.push(raceData);
            
            // Garder seulement les 10 dernieres courses
            if (savedRaces.length > 10) {
                savedRaces.shift();
            }
            
            localStorage.setItem('chronometerPro_races', JSON.stringify(savedRaces));
            
            // Afficher confirmation
            const success = document.getElementById('saveSuccess');
            success.classList.remove('d-none');
            setTimeout(() => success.classList.add('d-none'), 3000);
            
        } catch (error) {
            console.warn('Failed to save race:', error);
            alert('Erreur lors de la sauvegarde');
        }
    }
    
    exportCSV() {
        if (!this.raceFinished) return;
        
        const finishedSwimmers = this.swimmers
            .filter(s => s.finishTime !== null)
            .sort((a, b) => a.finishTime - b.finishTime);
        
        if (finishedSwimmers.length === 0) return;
        
        const bestTime = finishedSwimmers[0].finishTime;
        
        const csvContent = [
            ['Position', 'Nageur', 'Temps Final', 'Difference', 'Passages', 'Temps Moyen', 'Details Passages'],
            ...finishedSwimmers.map((swimmer, index) => {
                const diff = swimmer.finishTime - bestTime;
                const diffText = diff === 0 ? '-' : this.formatTime(diff);
                const avgTime = swimmer.laps.length > 0 ? 
                    this.formatTime(swimmer.laps.reduce((sum, lap) => sum + lap.splitTime, 0) / swimmer.laps.length) : '-';
                const lapDetails = swimmer.laps.map(lap => this.formatTime(lap.splitTime)).join(';');
                
                return [
                    index + 1,
                    swimmer.name,
                    this.formatTime(swimmer.finishTime),
                    diffText,
                    swimmer.laps.length,
                    avgTime,
                    lapDetails
                ];
            })
        ].map(row => row.join(',')).join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `chronometre_pro_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
    
    formatTime(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = Math.floor((ms % 60000) / 1000);
        const subseconds = Math.floor((ms % 1000) / this.config.precision);
        
        let precision = '';
        if (this.config.precision === 10) {
            precision = `.${subseconds.toString().padStart(2, '0')}`;
        } else if (this.config.precision === 100) {
            precision = `.${subseconds}`;
        }
        
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}${precision}`;
    }
}

// Initialiser le chronometre Pro au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    new ChronometerPro();
});
</script>
@endpush