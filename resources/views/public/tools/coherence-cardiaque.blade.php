@extends('layouts.public')

@section('title', 'Simulateur Coherence Cardiaque - Respiration Guidee Scientifique')
@section('meta_description', 'Simulateur de coherence cardiaque avec respiration guidee 3-6-5. Outil scientifique valide pour reduire le stress, ameliorer la variabilite cardiaque et favoriser le bien-être. Gratuit et sans inscription.')

@section('content')
<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container py-3">
        <h1 class="display-4 fw-bold d-flex align-items-center justify-content-center gap-3 mb-3">
            Coherence Cardiaque
        </h1>        
        <!-- Instructions -->
        <div id="instructionsAlert" class="alert alert-info border-0 shadow-sm" 
             style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
            
        </div>
    </div>
</section>

<!-- Interface principale -->
<section class="py-5 bg-light">
    <div class="container">
        <!-- Barre d'actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <div id="statusBadge" class="badge fs-6 px-3 py-2 bg-secondary">
                    En attente
                </div>
                <div id="phaseBadge" class="badge bg-primary fs-6 px-3 py-2">
                    Prêt
                </div>
                <div id="celebrationAlert" class="alert alert-success py-1 px-3 mb-0 d-none">
                    <i class="fas fa-heart me-2"></i>Session terminee !
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button id="settingsBtn" class="btn btn-outline-secondary">
                    <i class="fas fa-cog me-2"></i>Parametres
                </button>
                <button id="soundToggle" class="btn btn-outline-info">
                    <i class="fas fa-volume-up"></i>
                </button>
            </div>
        </div>

        <!-- Interface principale -->
        <div class="card border-0 shadow-lg mb-5" 
             style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
            <div class="card-body p-5">
                <!-- Timer principal -->
                <div class="text-center mb-5">
                    <h2 id="mainTimer" class="display-3 fw-bold mb-2" style="color: #1e293b;">
                        05:00
                    </h2>
                    <div class="progress mb-3" style="height: 8px;">
                        <div id="progressBar" class="progress-bar" 
                             style="width: 0%; background: linear-gradient(90deg, #3b82f6 0%, #10b981 100%);">
                        </div>
                    </div>
                    <p id="cycleInfo" class="text-muted mb-0">
                        Cycle 0 / 30 • 0% termine
                    </p>
                </div>

                <!-- Cercle de respiration -->
                <div class="mb-5">
                    <div id="breathingCircle" class="mx-auto d-flex align-items-center justify-content-center" 
                         style="width: 300px; height: 300px; border-radius: 50%; 
                                background: linear-gradient(135deg, #6b7280 0%, #374151 100%);
                                transition: all 0.1s ease-in-out; box-shadow: 0 0 30px rgba(0,0,0,0.1);">
                        <div class="text-center text-white">
                            <div id="breathingIcon" style="font-size: 4rem; margin-bottom: 10px;">
                                ●
                            </div>
                            <div id="breathingText" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 5px;">
                                PRÊT
                            </div>
                            <div id="breathingTime" style="font-size: 1.2rem; opacity: 0.8;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contrôles -->
                <div class="text-center">
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <button id="toggleBtn" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-lg"
                                style="font-size: 1.2rem; border-radius: 0.75rem; border: none;
                                       background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-play me-2"></i>Commencer
                        </button>
                        
                        <button id="resetBtn" class="btn btn-outline-dark btn-lg px-5 py-3 fw-bold"
                                style="font-size: 1.2rem; border-radius: 0.75rem; border-width: 2px;">
                            <i class="fas fa-redo me-2"></i>Reset
                        </button>
                    </div>

                    <!-- Indicateurs de cycle -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card border-0" style="background: rgba(59, 130, 246, 0.1);">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-lungs text-primary mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="fw-bold">Inspiration</div>
                                    <div class="text-muted" id="inspirationTime">4s</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0" style="background: rgba(16, 185, 129, 0.1);">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-lungs text-success mb-2" style="font-size: 1.5rem;"></i>
                                    <div class="fw-bold">Expiration</div>
                                    <div class="text-muted" id="expirationTime">6s</div>
                                </div>
                            </div>
                        </div>
                    </div>

<div class="d-flex align-items-start">
                <i class="fas fa-water text-info me-3 mt-1"></i>
                <div class="text-start text-dark">
                    <strong>Instructions :</strong> 
                    Suivez le rythme du cercle et des sons. Inspirez quand le cercle grandit (bleu), 
                    expirez quand il se contracte (vert). Concentrez-vous sur votre respiration et detendez-vous.
                    <br />
                    <strong>Raccourcis :</strong> 
                    <kbd class="ms-2">Espace</kbd> Start/Pause • 
                    <kbd class="ms-1">R</kbd> Reset
                </div>
            </div>

                </div>
            </div>
        </div>

        <!-- Bienfaits -->
        <div class="card border-0 shadow-lg">
            <div class="card-header text-white py-3"
                 style="background: linear-gradient(135deg, #004f59 0%, #006170 100%);">
                <h3 class="h4 mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-heart"></i>
                    Bienfaits de la Coherence Cardiaque
                </h3>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-brain text-primary mb-3" style="font-size: 2rem;"></i>
                            <h5>Reduction du Stress</h5>
                            <p class="text-muted">Diminue le cortisol et active le systeme nerveux parasympathique</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-heart text-danger mb-3" style="font-size: 2rem;"></i>
                            <h5>Regulation Cardiaque</h5>
                            <p class="text-muted">Ameliore la variabilite de la frequence cardiaque</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-lungs text-success mb-3" style="font-size: 2rem;"></i>
                            <h5>Meilleure Respiration</h5>
                            <p class="text-muted">Optimise l'oxygenation et la detente profonde</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Configuration -->
<div id="settingsModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-cog me-2"></i>Configuration - Coherence Cardiaque
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeSettings()"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-lungs me-2 text-primary"></i>
                        Duree d'inspiration (secondes)
                    </label>
                    <select id="inspirationSelect" class="form-select form-select-lg">
                        <option value="3000">3 secondes</option>
                        <option value="4000" selected>4 secondes (recommande)</option>
                        <option value="5000">5 secondes</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-lungs me-2 text-success"></i>
                        Duree d'expiration (secondes)
                    </label>
                    <select id="expirationSelect" class="form-select form-select-lg">
                        <option value="5000">5 secondes</option>
                        <option value="6000" selected>6 secondes (recommande)</option>
                        <option value="7000">7 secondes</option>
                        <option value="8000">8 secondes</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-clock me-2 text-info"></i>
                        Duree totale de la session
                    </label>
                    <select id="durationSelect" class="form-select form-select-lg">
                        <option value="60000">1 minute (test)</option>
                        <option value="180000">3 minutes</option>
                        <option value="300000" selected>5 minutes (recommande)</option>
                        <option value="600000">10 minutes</option>
                        <option value="900000">15 minutes</option>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" id="soundEnabledCheck" class="form-check-input" checked>
                            <label class="form-check-label" for="soundEnabledCheck">
                                <i class="fas fa-volume-up me-2"></i>
                                Sons de guidage
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" id="showInstructionsCheck" class="form-check-input" checked>
                            <label class="form-check-label" for="showInstructionsCheck">
                                <i class="fas fa-water me-2"></i>
                                Afficher les instructions
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-check form-switch mb-3" id="vibrationContainer">
                    <input type="checkbox" id="vibrationEnabledCheck" class="form-check-input">
                    <label class="form-check-label" for="vibrationEnabledCheck">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Vibrations (mobile)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-lg" onclick="saveSettings()">
                    <i class="fas fa-save me-2"></i>Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Section transition -->
<section class="py-5" style="background: linear-gradient(135deg, #004f59 0%, #006170 100%);">
    <div class="container">
        <div class="text-center text-white">
            <i class="fas fa-heart mb-3" style="font-size: 3rem;"></i>
            <h2 class="display-5 fw-bold mb-3">Votre bien-être au quotidien</h2>
            <p class="lead fs-4">Pratiquez 3 fois par jour pour des resultats optimaux</p>
            <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                <div class="bg-white bg-opacity-10 rounded-pill px-4 py-2">5 minutes le matin</div>
                <div class="bg-white bg-opacity-10 rounded-pill px-4 py-2">5 minutes avant un repas</div>
                <div class="bg-white bg-opacity-10 rounded-pill px-4 py-2">5 minutes le soir</div>
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

<!-- Section Article Scientifique -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <i class="fas fa-heart text-primary mb-3" style="font-size: 3rem;"></i>
            <h2 class="display-5 fw-bold mb-3">Fondements Scientifiques</h2>
            <p class="lead text-muted">La Coherence Cardiaque : etat des Connaissances 2025</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11">
                <article class="card border-0 shadow-lg">
                    <!-- Header de l'article -->
                    <div class="card-header text-white py-4" 
                         style="background: linear-gradient(135deg, #004f59 0%, #006170 100%);">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-brain me-3" style="font-size: 2rem;"></i>
                            <h1 class="h2 mb-0 text-center">
                                La Coherence Cardiaque : Fondements Scientifiques et Applications Therapeutiques
                            </h1>
                        </div>
                        <p class="text-center mb-0 mt-2 opacity-75">etat des Connaissances 2025</p>
                    </div>
                    
                    <div class="card-body p-5" style="line-height: 1.8;">
                        
                        <!-- Resume Executif -->
                        <div class="alert alert-primary border-0 mb-5" style="background: rgba(59, 130, 246, 0.1);">
                            <h2 class="alert-heading text-primary h4">
                                <i class="fas fa-water me-2"></i>
                                Resume Executif
                            </h2>
                            <p class="mb-0">
                                La coherence cardiaque, scientifiquement designee sous le terme de <strong>"biofeedback de variabilite de la frequence cardiaque" (HRVB)</strong>, 
                                represente une technique de regulation psychophysiologique basee sur l'optimisation de l'arythmie sinusale respiratoire (ASR). 
                                Les recherches recentes, notamment une meta-analyse de 2024 analysant <strong>1,8 million de sessions utilisateurs</strong>, 
                                confirment l'efficacite de cette approche dans la gestion du stress, de l'anxiete et de diverses pathologies.
                            </p>
                        </div>

                        <!-- Introduction -->
                        <div class="mb-5">
                            <h2 class="text-primary mb-4 h3">
                                <span class="badge bg-primary me-3">1</span>
                                Introduction et Definitions
                            </h2>
                            
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="card border-0 h-100" style="background: rgba(16, 185, 129, 0.1);">
                                        <div class="card-body p-4">
                                            <h4 class="text-success h5 mb-3">1.1 Contexte Scientifique</h4>
                                            <p class="mb-0">
                                                La coherence cardiaque est apparue au tournant des annees 2000 dans le cadre des travaux scientifiques 
                                                sur la variabilite de la frequence cardiaque : alors que la frequence cardiaque varie de maniere chaotique 
                                                habituellement, il est apparu que ces variations deviennent <strong>coherentes et plus amples</strong> lors 
                                                des exercices de coherence cardiaque.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 h-100" style="background: rgba(59, 130, 246, 0.1);">
                                        <div class="card-body p-4">
                                            <h4 class="text-primary h5 mb-3">1.2 Terminologie Scientifique</h4>
                                            <ul class="list-unstyled mb-0">
                                                <li><strong class="text-primary">Coherence cardiaque</strong> : Terme de vulgarisation scientifique</li>
                                                <li><strong class="text-success">HRVB</strong> : Terminologie scientifique officielle</li>
                                                <li><strong class="text-warning">ASR</strong> : Phenomene physiologique sous-jacent</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info border-0">
                                <p class="mb-0">
                                    <i class="fas fa-water me-2 text-info"></i>
                                    L'expression « coherence cardiaque » est plutôt A destination du public, tandis que l'expression retenue par les scientifiques est 
                                    <strong> « biofeedback de variabilite de la frequence cardiaque »</strong>, en anglais : « Heart Rate Variability Biofeedback » ou HRVB.
                                </p>
                            </div>
                        </div>

                        <!-- Bases Physiologiques -->
                        <div class="mb-5">
                            <h2 class="text-primary mb-4 h3">
                                <span class="badge bg-primary me-3">2</span>
                                Bases Physiologiques et Mecanismes
                            </h2>

                            <div class="mb-4">
                                <h4 class="text-success h5 mb-3">2.1 L'Arythmie Sinusale Respiratoire (ASR)</h4>
                                <p class="mb-3">
                                    Ce phenomene est appele « arythmie sinusale respiratoire » (ASR) dans le monde biomedical, ou « coherence cardiaque » par le grand public. 
                                    L'amplitude de l'ASR est mesuree par la difference entre le rythme cardiaque maximum pendant l'inspiration et le rythme cardiaque minimum pendant l'expiration.
                                </p>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border-0 text-center p-3" style="background: rgba(59, 130, 246, 0.1);">
                                            <i class="fas fa-lungs text-primary mb-2" style="font-size: 1.5rem;"></i>
                                            <h6 class="fw-bold text-primary">Inspiration</h6>
                                            <p class="small mb-0">Augmentation de la frequence cardiaque<br/>(diminution du tonus vagal)</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 text-center p-3" style="background: rgba(16, 185, 129, 0.1);">
                                            <i class="fas fa-lungs text-success mb-2" style="font-size: 1.5rem;"></i>
                                            <h6 class="fw-bold text-success">Expiration</h6>
                                            <p class="small mb-0">Diminution de la frequence cardiaque<br/>(activation parasympathique)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning border-0">
                                <h5 class="text-warning mb-3">2.3 Protocole 3-6-5</h5>
                                <div class="card border-0" style="background: rgba(255, 255, 255, 0.7);">
                                    <div class="card-body text-center p-3">
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                                                     style="width: 50px; height: 50px;">
                                                    <strong>3</strong>
                                                </div>
                                                <p class="small mb-0">seances par jour</p>
                                            </div>
                                            <div class="col-4">
                                                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                                                     style="width: 50px; height: 50px;">
                                                    <strong>6</strong>
                                                </div>
                                                <p class="small mb-0">respirations par minute</p>
                                            </div>
                                            <div class="col-4">
                                                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                                                     style="width: 50px; height: 50px;">
                                                    <strong>5</strong>
                                                </div>
                                                <p class="small mb-0">minutes par seance</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recherches Recentes -->
                        <div class="mb-5">
                            <h2 class="text-primary mb-4 h3">
                                <span class="badge bg-primary me-3">3</span>
                                Recherches Recentes et Meta-analyses
                            </h2>

                            <div class="row g-4 mb-4">
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 h-100 shadow-sm text-center">
                                        <div class="card-body p-4">
                                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                                 style="width: 70px; height: 70px;">
                                                <strong>0.83</strong>
                                            </div>
                                            <h6 class="fw-bold">Stress & Anxiete</h6>
                                            <p class="text-muted small mb-2">Effet important confirme</p>
                                            <span class="badge bg-success">Meta-analyse 2017</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 h-100 shadow-sm text-center">
                                        <div class="card-body p-4">
                                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                                 style="width: 70px; height: 70px;">
                                                <strong>0.38</strong>
                                            </div>
                                            <h6 class="fw-bold">Depression</h6>
                                            <p class="text-muted small mb-2">Effet modere sur 794 participants</p>
                                            <span class="badge bg-primary">14 etudes (2021)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 h-100 shadow-sm text-center">
                                        <div class="card-body p-4">
                                            <div class="bg-warning text-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                                 style="width: 70px; height: 70px;">
                                                <strong>58</strong>
                                            </div>
                                            <h6 class="fw-bold">etudes Analysees</h6>
                                            <p class="text-muted small mb-2">Meta-analyse globale</p>
                                            <span class="badge bg-warning text-dark">ECR (2020)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="card border-0 h-100 shadow-sm text-center">
                                        <div class="card-body p-4">
                                            <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                                 style="width: 70px; height: 70px; font-size: 0.9rem;">
                                                <strong>1.8M</strong>
                                            </div>
                                            <h6 class="fw-bold">Sessions Analysees</h6>
                                            <p class="text-muted small mb-2">Plus grande etude jamais realisee</p>
                                            <span class="badge bg-danger">Cohorte 2024</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Applications Cliniques -->
                        <div class="mb-5">
                            <h2 class="text-primary mb-4 h3">
                                <span class="badge bg-primary me-3">4</span>
                                Applications Cliniques Validees
                            </h2>

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="card border-0 h-100" style="background: rgba(220, 53, 69, 0.1);">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-heart text-danger me-3" style="font-size: 1.5rem;"></i>
                                                <h5 class="text-danger mb-0">4.1 Pathologies Cardiovasculaires</h5>
                                            </div>
                                            <p class="mb-3">
                                                Cette technique a fait son entree dans les services hospitaliers, en particulier ceux dedies A la readaptation cardiaque, 
                                                car les etudes ont objective ses benefices dont la <strong>reduction des recidives d'infarctus</strong>.
                                            </p>
                                            <div class="list-group list-group-flush">
                                                <div class="list-group-item border-0 px-0 py-2">✓ Readaptation post-infarctus</div>
                                                <div class="list-group-item border-0 px-0 py-2">✓ Prevention des recidives cardiovasculaires</div>
                                                <div class="list-group-item border-0 px-0 py-2">✓ Amelioration de la variabilite cardiaque</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card border-0 h-100" style="background: rgba(255, 193, 7, 0.1);">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-brain text-warning me-3" style="font-size: 1.5rem;"></i>
                                                <h5 class="text-warning mb-0">4.2 Troubles Anxieux et PTSD</h5>
                                            </div>
                                            <p class="mb-3">
                                                Cette technique peut être utilisee pour toutes les formes d'anxiete et particulierement les formes avec une activation 
                                                du systeme sympathique comme les <strong>paniques, le stress, le syndrome de stress post-traumatique</strong>.
                                            </p>
                                            <div class="alert alert-warning border-0 py-2 px-3">
                                                <small>
                                                    <strong>Specialite :</strong> Forte expression cardio-respiratoire des troubles anxieux
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Effets Biologiques -->
                        <div class="mb-5">
                            <h2 class="text-primary mb-4 h3">
                                <span class="badge bg-primary me-3">5</span>
                                Mecanismes d'Action et Effets Biologiques
                            </h2>

                            <div class="alert alert-success border-0 mb-4" style="background: rgba(16, 185, 129, 0.1);">
                                <h5 class="text-success mb-3">5.2 Effets Hormonaux Documentes</h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="text-success mb-3">✓ Augmentation</h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <span class="badge bg-success me-2">DHEA</span>
                                                Hormone de jouvence
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-success me-2">IgA</span>
                                                Defense immunitaire
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-success me-2">Ocytocine</span>
                                                Hormone du bien-être
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-success me-2">Ondes α</span>
                                                Memorisation et apprentissage
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-danger mb-3">✓ Diminution</h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <span class="badge bg-danger me-2">Cortisol</span>
                                                Hormone du stress
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-danger me-2">Sympathique</span>
                                                Activite du systeme nerveux
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-danger me-2">Anxiete</span>
                                                Via dopamine et serotonine
                                            </li>
                                            <li class="mb-2">
                                                <span class="badge bg-danger me-2">Depression</span>
                                                Amelioration des neurotransmetteurs
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recommandations -->
                        <div class="mb-5">
                            <h2 class="text-primary mb-4 h3">
                                <span class="badge bg-primary me-3">6</span>
                                Recommandations Cliniques
                            </h2>

                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="card border-0 h-100" style="background: rgba(16, 185, 129, 0.1);">
                                        <div class="card-body p-4">
                                            <h5 class="text-success mb-3">6.1 Indications Validees</h5>
                                            
                                            <div class="mb-3">
                                                <h6 class="text-success">Niveau de preuve eleve :</h6>
                                                <ul class="list-unstyled small">
                                                    <li>• Gestion du stress et de l'anxiete</li>
                                                    <li>• Readaptation cardiovasculaire</li>
                                                    <li>• Amelioration de la performance</li>
                                                    <li>• Troubles anxieux avec composante somatique</li>
                                                </ul>
                                            </div>

                                            <div>
                                                <h6 class="text-warning">Niveau de preuve modere :</h6>
                                                <ul class="list-unstyled small">
                                                    <li>• Depression (en complement)</li>
                                                    <li>• Stress post-traumatique</li>
                                                    <li>• Troubles du sommeil lies au stress</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card border-0 h-100" style="background: rgba(220, 53, 69, 0.1);">
                                        <div class="card-body p-4">
                                            <h5 class="text-danger mb-3">6.2 Precautions Importantes</h5>
                                            
                                            <div class="alert alert-danger border-0 py-2 mb-3">
                                                <h6 class="text-danger mb-2">Recommandations :</h6>
                                                <ul class="list-unstyled small mb-0">
                                                    <li>• Ne pas remplacer un traitement medical</li>
                                                    <li>• Supervision professionnelle pour pathologies graves</li>
                                                    <li>• Adaptation selon l'âge et les capacites</li>
                                                    <li>• Technique complementaire, non substitutive</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conclusion -->
                        <div class="mb-5">
                            <h2 class="text-primary mb-4 h3">
                                <span class="badge bg-primary me-3">7</span>
                                Conclusion
                            </h2>

                            <div class="alert alert-primary border-0 mb-4" style="background: rgba(59, 130, 246, 0.1);">
                                <h5 class="text-primary mb-3">Synthese Scientifique</h5>
                                <p class="mb-3">
                                    La coherence cardiaque, basee sur l'optimisation de l'arythmie sinusale respiratoire, represente une 
                                    <strong> approche therapeutique non-invasive</strong> dont l'efficacite est soutenue par des preuves scientifiques croissantes. 
                                    Les meta-analyses recentes confirment son utilite dans la gestion du stress, de l'anxiete et de diverses pathologies cardiovasculaires et psychiatriques.
                                </p>
                                <p class="mb-0">
                                    La coherence cardiaque s'impose ainsi comme un <strong>outil therapeutique complementaire precieux</strong>, 
                                    A condition d'être utilisee dans un cadre scientifique rigoureux et en complement d'une prise en charge appropriee.
                                </p>
                            </div>
                        </div>

                        <!-- References -->
                        <div class="border-top pt-4">
                            <h5 class="text-muted mb-3">References Bibliographiques</h5>
                            <div class="row g-3 small text-muted">
                                <div class="col-md-6">
                                    <p class="mb-1">• <strong>Inserm</strong> (2023) - "La coherence cardiaque, une technique pour ameliorer sa sante, vraiment ?" Canal Detox</p>
                                    <p class="mb-1">• <strong>Scientific Reports</strong> (2021) - "A meta-analysis on heart rate variability biofeedback and depressive symptoms"</p>
                                    <p class="mb-1">• <strong>Psychophysiology</strong> (2024) - "Real-time heart rate variability biofeedback amplitude"</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1">• <strong>Applied Psychophysiology and Biofeedback</strong> (2024) - "The Effect of Heart Rate Variability Biofeedback Training"</p>
                                    <p class="mb-1">• <strong>Psychological Medicine</strong> (2017) - "Heart rate variability biofeedback training on stress and anxiety: meta-analysis"</p>
                                    <p class="mb-1">• <strong>Universite de Lausanne</strong> (2025) - "La coherence cardiaque, une realite physiologique?"</p>
                                </div>
                            </div>
                            <p class="small text-muted mt-3 mb-0">
                                <em>Note : Cet article reflete l'etat des connaissances scientifiques en fevrier 2025. 
                                Les recherches dans ce domaine evoluant rapidement, il est recommande de consulter les publications les plus recentes.</em>
                            </p>
                        </div>

                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.breathing-circle-inhale {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    transform: scale(1.2) !important;
    box-shadow: 0 0 40px rgba(59, 130, 246, 0.4) !important;
}

.breathing-circle-exhale {
    background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
    transform: scale(0.8) !important;
    box-shadow: 0 0 40px rgba(16, 185, 129, 0.4) !important;
}

.modal {
    display: none;
}

.modal.show {
    display: block !important;
}

@keyframes celebration {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.celebration {
    animation: celebration 0.6s ease-in-out;
}
</style>
@endpush

@push('scripts')
<script>
class HeartCoherenceSimulator {
    constructor() {
        this.config = this.loadConfig();
        this.timeRemaining = this.config.totalDuration;
        this.isRunning = false;
        this.currentPhase = 'ready'; // 'ready', 'inhale', 'exhale'
        this.cycleTime = 0;
        this.completedCycles = 0;
        this.isFinished = false;
        this.interval = null;
        this.cycleInterval = null;
        
        this.initializeElements();
        this.updateDisplay();
        this.setupEventListeners();
        this.setupKeyboardShortcuts();
    }
    
    loadConfig() {
        const defaultConfig = {
            inspirationTime: 4000,
            expirationTime: 6000,
            totalDuration: 300000,
            soundEnabled: true,
            showInstructions: true,
            vibrationEnabled: false
        };
        
        try {
            const saved = localStorage.getItem('heart_coherence_config');
            return saved ? { ...defaultConfig, ...JSON.parse(saved) } : defaultConfig;
        } catch {
            return defaultConfig;
        }
    }
    
    saveConfig() {
        try {
            localStorage.setItem('heart_coherence_config', JSON.stringify(this.config));
        } catch (error) {
            console.warn('Failed to save config:', error);
        }
    }
    
    initializeElements() {
        this.elements = {
            statusBadge: document.getElementById('statusBadge'),
            phaseBadge: document.getElementById('phaseBadge'),
            mainTimer: document.getElementById('mainTimer'),
            progressBar: document.getElementById('progressBar'),
            cycleInfo: document.getElementById('cycleInfo'),
            breathingCircle: document.getElementById('breathingCircle'),
            breathingIcon: document.getElementById('breathingIcon'),
            breathingText: document.getElementById('breathingText'),
            breathingTime: document.getElementById('breathingTime'),
            toggleBtn: document.getElementById('toggleBtn'),
            resetBtn: document.getElementById('resetBtn'),
            settingsBtn: document.getElementById('settingsBtn'),
            soundToggle: document.getElementById('soundToggle'),
            inspirationTime: document.getElementById('inspirationTime'),
            expirationTime: document.getElementById('expirationTime'),
            celebrationAlert: document.getElementById('celebrationAlert'),
            instructionsAlert: document.getElementById('instructionsAlert')
        };
        
        // Masquer/afficher les vibrations selon la compatibilite
        const vibrationContainer = document.getElementById('vibrationContainer');
        if (!navigator.vibrate) {
            vibrationContainer.style.display = 'none';
        }
    }
    
    setupEventListeners() {
        this.elements.toggleBtn.addEventListener('click', () => this.toggle());
        this.elements.resetBtn.addEventListener('click', () => this.reset());
        this.elements.settingsBtn.addEventListener('click', () => this.openSettings());
        this.elements.soundToggle.addEventListener('click', () => this.toggleSound());
        
        // evenements pour les raccourcis
        window.addEventListener('heartCoherenceToggle', () => this.toggle());
        window.addEventListener('heartCoherenceReset', () => this.reset());
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (event) => {
            if (event.target.tagName === 'INPUT' || event.target.tagName === 'TEXTAREA') return;
            
            switch (event.code) {
                case 'Space':
                    event.preventDefault();
                    this.toggle();
                    break;
                case 'KeyR':
                    event.preventDefault();
                    this.reset();
                    break;
            }
        });
    }
    
    get cycleLength() {
        return this.config.inspirationTime + this.config.expirationTime;
    }
    
    get totalCycles() {
        return Math.floor(this.config.totalDuration / this.cycleLength);
    }
    
    formatTime(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = Math.floor((ms % 60000) / 1000);
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    updateDisplay() {
        // Timer principal
        this.elements.mainTimer.textContent = this.formatTime(this.timeRemaining);
        
        // Barre de progression
        const progress = ((this.config.totalDuration - this.timeRemaining) / this.config.totalDuration) * 100;
        this.elements.progressBar.style.width = `${progress}%`;
        
        // Informations cycle
        this.elements.cycleInfo.textContent = `Cycle ${this.completedCycles} / ${this.totalCycles} • ${Math.round(progress)}% termine`;
        
        // Temps inspiration/expiration
        this.elements.inspirationTime.textContent = `${this.config.inspirationTime / 1000}s`;
        this.elements.expirationTime.textContent = `${this.config.expirationTime / 1000}s`;
        
        // Instructions
        if (this.config.showInstructions) {
            this.elements.instructionsAlert.style.display = 'block';
        } else {
            this.elements.instructionsAlert.style.display = 'none';
        }
        
        // Son toggle
        if (this.config.soundEnabled) {
            this.elements.soundToggle.innerHTML = '<i class="fas fa-volume-up"></i>';
            this.elements.soundToggle.className = 'btn btn-outline-info';
        } else {
            this.elements.soundToggle.innerHTML = '<i class="fas fa-volume-mute"></i>';
            this.elements.soundToggle.className = 'btn btn-outline-secondary';
        }
    }
    
    updateBreathingCircle() {
        const circle = this.elements.breathingCircle;
        const icon = this.elements.breathingIcon;
        const text = this.elements.breathingText;
        const time = this.elements.breathingTime;
        
        // Supprimer les classes precedentes
        circle.classList.remove('breathing-circle-inhale', 'breathing-circle-exhale');
        
        switch (this.currentPhase) {
            case 'inhale':
                circle.classList.add('breathing-circle-inhale');
                icon.textContent = '↑';
                text.textContent = 'INSPIREZ';
                const remainingInhale = Math.ceil((this.config.inspirationTime - this.cycleTime) / 1000);
                time.textContent = `${remainingInhale}s`;
                this.elements.phaseBadge.textContent = 'Inspiration';
                break;
            case 'exhale':
                circle.classList.add('breathing-circle-exhale');
                icon.textContent = '↓';
                text.textContent = 'EXPIREZ';
                const remainingExhale = Math.ceil((this.cycleLength - this.cycleTime) / 1000);
                time.textContent = `${remainingExhale}s`;
                this.elements.phaseBadge.textContent = 'Expiration';
                break;
            default:
                icon.textContent = '●';
                text.textContent = 'PRÊT';
                time.textContent = '';
                this.elements.phaseBadge.textContent = 'Prêt';
        }
    }
    
    updateStatus() {
        if (this.isRunning) {
            this.elements.statusBadge.textContent = 'En cours';
            this.elements.statusBadge.className = 'badge fs-6 px-3 py-2 bg-success';
            this.elements.toggleBtn.innerHTML = '<i class="fas fa-pause me-2"></i>Pause';
            this.elements.toggleBtn.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
        } else if (this.isFinished) {
            this.elements.statusBadge.textContent = 'Termine';
            this.elements.statusBadge.className = 'badge fs-6 px-3 py-2 bg-warning text-dark';
            this.elements.toggleBtn.innerHTML = '<i class="fas fa-check me-2"></i>Termine';
            this.elements.toggleBtn.disabled = true;
        } else {
            this.elements.statusBadge.textContent = 'En pause';
            this.elements.statusBadge.className = 'badge fs-6 px-3 py-2 bg-secondary';
            this.elements.toggleBtn.innerHTML = '<i class="fas fa-play me-2"></i>Commencer';
            this.elements.toggleBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            this.elements.toggleBtn.disabled = false;
        }
    }
    
    playSound(type) {
        if (!this.config.soundEnabled) return;
        
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            if (type === 'inhale') {
                oscillator.frequency.setValueAtTime(600, audioContext.currentTime);
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.3);
            } else if (type === 'exhale') {
                oscillator.frequency.setValueAtTime(400, audioContext.currentTime);
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.25, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.4);
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.4);
            }
        } catch (error) {
            console.warn('Audio not supported:', error);
        }
    }
    
    vibrate(pattern) {
        if (!this.config.vibrationEnabled || !navigator.vibrate) return;
        navigator.vibrate(pattern);
    }
    
    start() {
        if (this.isFinished) return;
        
        this.isRunning = true;
        
        // Timer principal
        this.interval = setInterval(() => {
            this.timeRemaining -= 100;
            
            if (this.timeRemaining <= 0) {
                this.finish();
                return;
            }
            
            this.updateDisplay();
        }, 100);
        
        // Cycle de respiration
        this.cycleInterval = setInterval(() => {
            this.cycleTime += 100;
            
            const previousPhase = this.currentPhase;
            
            // Determiner la phase actuelle
            if (this.cycleTime < this.config.inspirationTime) {
                this.currentPhase = 'inhale';
            } else if (this.cycleTime < this.cycleLength) {
                this.currentPhase = 'exhale';
            } else {
                // Nouveau cycle
                this.cycleTime = 0;
                this.completedCycles++;
                this.currentPhase = 'inhale';
            }
            
            // Declencher sons et vibrations lors des changements de phase
            if (previousPhase !== this.currentPhase && (this.currentPhase === 'inhale' || this.currentPhase === 'exhale')) {
                this.playSound(this.currentPhase);
                
                if (this.currentPhase === 'inhale') {
                    this.vibrate([100]);
                } else if (this.currentPhase === 'exhale') {
                    this.vibrate([200]);
                }
            }
            
            this.updateBreathingCircle();
        }, 100);
        
        this.updateStatus();
    }
    
    pause() {
        this.isRunning = false;
        
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
        
        if (this.cycleInterval) {
            clearInterval(this.cycleInterval);
            this.cycleInterval = null;
        }
        
        this.updateStatus();
    }
    
    toggle() {
        if (this.isRunning) {
            this.pause();
        } else {
            this.start();
        }
    }
    
    reset() {
        this.pause();
        this.timeRemaining = this.config.totalDuration;
        this.currentPhase = 'ready';
        this.cycleTime = 0;
        this.completedCycles = 0;
        this.isFinished = false;
        
        this.elements.celebrationAlert.classList.add('d-none');
        
        this.updateDisplay();
        this.updateBreathingCircle();
        this.updateStatus();
    }
    
    finish() {
        this.pause();
        this.isFinished = true;
        this.updateStatus();
        
        // Afficher celebration
        this.elements.celebrationAlert.classList.remove('d-none');
        this.elements.celebrationAlert.classList.add('celebration');
        
        setTimeout(() => {
            this.elements.celebrationAlert.classList.add('d-none');
            this.elements.celebrationAlert.classList.remove('celebration');
        }, 5000);
    }
    
    toggleSound() {
        this.config.soundEnabled = !this.config.soundEnabled;
        this.updateDisplay();
        this.saveConfig();
    }
    
    openSettings() {
        // Pre-remplir les valeurs
        document.getElementById('inspirationSelect').value = this.config.inspirationTime;
        document.getElementById('expirationSelect').value = this.config.expirationTime;
        document.getElementById('durationSelect').value = this.config.totalDuration;
        document.getElementById('soundEnabledCheck').checked = this.config.soundEnabled;
        document.getElementById('showInstructionsCheck').checked = this.config.showInstructions;
        document.getElementById('vibrationEnabledCheck').checked = this.config.vibrationEnabled;
        
        // Afficher modal
        document.getElementById('settingsModal').classList.add('show');
        document.getElementById('settingsModal').style.display = 'block';
        document.body.classList.add('modal-open');
    }
}

function closeSettings() {
    document.getElementById('settingsModal').classList.remove('show');
    document.getElementById('settingsModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}

function saveSettings() {
    const simulator = window.heartCoherenceSimulator;
    
    simulator.config.inspirationTime = parseInt(document.getElementById('inspirationSelect').value);
    simulator.config.expirationTime = parseInt(document.getElementById('expirationSelect').value);
    simulator.config.totalDuration = parseInt(document.getElementById('durationSelect').value);
    simulator.config.soundEnabled = document.getElementById('soundEnabledCheck').checked;
    simulator.config.showInstructions = document.getElementById('showInstructionsCheck').checked;
    simulator.config.vibrationEnabled = document.getElementById('vibrationEnabledCheck').checked;
    
    simulator.saveConfig();
    simulator.reset(); // Appliquer les nouveaux parametres
    
    closeSettings();
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    window.heartCoherenceSimulator = new HeartCoherenceSimulator();
});
</script>
@endpush