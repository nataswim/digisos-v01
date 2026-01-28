@extends('layouts.public')

@section('title', 'A propos')

@section('content')


<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">À propos </h1>
                <p class="lead mb-0">
                    Depuis 2006, nous accompagnons sportifs et entraîneurs dans leur quête d'excellence. Plus de 30 ans d'expertise au service de la performance.
                </p>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                     style="width: 200px; height: 200px;">
                    <i class="fas fa-water" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</section>





<!-- Notre Mission -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <header class="text-center mb-5">
                    <h2 class="display-5 fw-bold mb-4">Notre Mission</h2>
                    <p class="lead text-muted">
                        Démocratiser l'accès aux connaissances pour tous les passionnés, du débutant au compétiteur confirmé.
                    </p>
                </header>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <article class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-lightbulb text-warning fa-2x"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-3">Excellence & Innovation</h3>
                                <p class="text-muted mb-0">
                                    Nous développons des outils d'entraînement innovants basés sur les dernières recherches en sciences du sport et préparation physique.
                                </p>
                            </div>
                        </article>
                    </div>
                    
                    <div class="col-md-4">
                        <article class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-graduation-cap text-success fa-2x"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-3">Pédagogie & Transmission</h3>
                                <p class="text-muted mb-0">
                                    Notre approche pédagogique rend les techniques et la préparation physique accessibles à tous les niveaux de pratique.
                                </p>
                            </div>
                        </article>
                    </div>
                    
                    <div class="col-md-4">
                        <article class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-handshake text-info fa-2x"></i>
                                </div>
                                <h3 class="h5 fw-bold mb-3">Communauté & Partage</h3>
                                <p class="text-muted mb-0">
                                    Nous créons des ponts entre nageurs, triathlètes et entraîneurs pour favoriser l'entraide et le partage d'expériences.
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- Notre Histoire -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Notre Histoire</h2>
                <div class="timeline">
                    <article class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="min-width: 60px; height: 60px;">
                                <strong>2006</strong>
                            </div>
                            <div>
                                <h3 class="h5 fw-bold">Les débuts</h3>
                                <p class="text-muted">
                                    Création par MH El Haouat et Steve Marsh Vedravokivish, deux passionnés de aps et de préparation physique souhaitant partager leur expertise.
                                </p>
                            </div>
                        </div>
                    </article>
                    
                    <article class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="min-width: 60px; height: 60px;">
                                <strong>2010</strong>
                            </div>
                            <div>
                                <h3 class="h5 fw-bold">Partenariats clubs</h3>
                                <p class="text-muted">
                                    Début des collaborations avec plusieurs clubs et coachs. Mise en place de programmes d'entraînement personnalisés.
                                </p>
                            </div>
                        </div>
                    </article>
                    
                    <article class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="min-width: 60px; height: 60px;">
                                <strong>2015</strong>
                            </div>
                            <div>
                                <h3 class="h5 fw-bold">Extension triathlon</h3>
                                <p class="text-muted">
                                    Développement de programmes spécialisés pour triathlètes. Intégration d'outils de calcul et de planification triathlon.
                                </p>
                            </div>
                        </div>
                    </article>
                    
                    <article>
                        <div class="d-flex align-items-start">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="min-width: 60px; height: 60px;">
                                <strong>{{ now()->year }}</strong>
                            </div>
                            <div>
                                <h3 class="h5 fw-bold">Refonte de la Plateforme </h3>
                                <p class="text-muted">
                                    Aujourd'hui, nous offrons une plateforme nouvelle avec plus de 300 articles, 100 plans et 500 exercices.
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="display-4 fw-bold text-primary mb-2">25+</div>
                            <small class="text-muted">Années d'expérience</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="display-4 fw-bold text-success mb-2">350+</div>
                            <small class="text-muted">Utilisateurs actifs</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="display-4 fw-bold text-warning mb-2">35000+</div>
                            <small class="text-muted">Vues mensuelles</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="display-4 fw-bold text-info mb-2">50+</div>
                            <small class="text-muted">Plans d'entraînement</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Nos Partenariats -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Nos Partenariats</h2>
            <p class="lead text-muted">Collaborations avec clubs et coachs</p>
        </header>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-swimming-pool text-primary fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">Clubs </h3>
                        <p class="text-muted small mb-0">
                            Partenariats avec plusieurs clubs pour le développement de leurs athletes
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-medal text-success fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">Coachs de renom</h3>
                        <p class="text-muted small mb-0">
                            Collaboration avec entraîneurs reconnus pour valider nos programmes
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-university text-warning fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">Formations STAPS</h3>
                        <p class="text-muted small mb-0">
                            Support pédagogique pour étudiants en sciences du sport
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-4">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-heartbeat text-info fa-2x"></i>
                        </div>
                        <h3 class="h5 fw-bold mb-2">Préparateurs physiques</h3>
                        <p class="text-muted small mb-0">
                            Outils spécialisés pour la préparation physique et la formation continue
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nos Valeurs -->
<section class="py-5 bg-white">
    <div class="container-lg">
        <header class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Valeurs</h2>
            <p class="lead text-muted">Ce qui guide notre travail au quotidien</p>
        </header>
        
        <div class="row g-4">
            <div class="col-md-6">
                <article class="d-flex align-items-start">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-microscope fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-2">Evidence-Based</h3>
                        <p class="text-muted mb-0">
                            Nos programmes d'entraînement s'appuient sur les recherches les plus récentes en physiologie de l'exercice et biomécanique.
                        </p>
                    </div>
                </article>
            </div>
            
            <div class="col-md-6">
                <article class="d-flex align-items-start">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-2">Sécurité & Progression</h3>
                        <p class="text-muted mb-0">
                            La sécurité des pratiquants est notre priorité. Nos plans intègrent une progression adaptée pour prévenir les blessures et optimiser les performances.
                        </p>
                    </div>
                </article>
            </div>
            
            <div class="col-md-6">
                <article class="d-flex align-items-start">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-2">Accessibilité</h3>
                        <p class="text-muted mb-0">
                            Nous croyons que l'entraînement de qualité doit être accessible à tous, quels que soient le niveau et les objectifs.
                        </p>
                    </div>
                </article>
            </div>
            
            <div class="col-md-6">
                <article class="d-flex align-items-start">
                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-sync-alt fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="h5 fw-bold mb-2">Amélioration Continue</h3>
                        <p class="text-muted mb-0">
                            Nous mettons à jour régulièrement nos contenus et outils pour intégrer les dernières avancées en entraînement et préparation physique.
                        </p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>


@endsection


@push('styles')
<style>
    /* Cards membres de l'équipe */
    .team-member-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .team-member-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    
    .team-member-img {
        transition: transform 0.4s ease;
    }
    
    .team-member-card:hover .team-member-img {
        transform: scale(1.1);
    }
    
    /* Gradient sombre pour le texte */
    .bg-gradient-dark {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 50%, transparent 100%);
    }
    
    /* Object fit pour les images */
    .object-fit-cover {
        object-fit: cover;
        object-position: center;
    }
    
    /* Badge responsive */
    .team-member-card .badge {
        font-size: 0.85rem;
        text-align: center;
    }
    
    @media (max-width: 576px) {
        .team-member-card .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem !important;
        }
    }
    
    /* Hauteur responsive des images */
    @media (max-width: 768px) {
        .team-member-card .position-relative {
            height: 200px !important;
        }
    }
    
    @media (max-width: 576px) {
        .team-member-card .position-relative {
            height: 180px !important;
        }
    }
</style>
@endpush