@extends('layouts.tools')

@section('title', 'Outils & Calculateurs Sportifs')
@section('meta_description', 'Suite complete d\'outils et calculateurs pour optimiser votre santé, performance et entraînement sportif. Organises par catégories spécialisées, bases sur les recherches scientifiques 2024.')

@section('content')

@include('public.tools.partials.header', ['title' => 'Outils & Calculateurs', 'lead' => 'Calculateurs, chronomètres et planificateurs pour la natation, la santé et la préparation physique.', 'icon' => 'fa-calculator', 'home' => true])

<!-- Section Outils Individuels -->
<section class="py-5">
    <div class="container">
        <!-- Santé Hydratation & Nutrition -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-danger">
                <i class="fas fa-water text-danger me-3" style="font-size: 2rem;"></i>
                <h3 class="mb-0 text-danger">Santé Hydratation & Nutrition</h3>
            </div>
            <div class="row g-4">
                <!-- IMC -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.imc') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-weight text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Mon IMC</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Calculateur IMC complet avec alternatives modernes (BRI, WHtR, RFM)
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Masse grasse -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.masse-grasse') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-percentage text-warning" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Masse grasse</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Multiples méthodes d'estimation de la composition corporelle
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Calories -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.calories') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-fire text-danger" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Conso Calories</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Besoins caloriques sportifs personnalisés selon activité
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- TDEE -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.tdee') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-chart-line text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Ma TDEE</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Dépense énergétique totale avec facteurs métaboliques
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Kcal/Macros -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.kcal-macros') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-calculator text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Kcal/Macros</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Conversion et répartition macronutriments optimisée
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Hydratation -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.hydratation') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-tint text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Hydratation</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Besoins hydriques personnalisés pour sportifs
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Natation Sportive -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-info">
                <i class="fas fa-swimmer text-info me-3" style="font-size: 2rem;"></i>
                <h3 class="mb-0 text-info">Natation Sportive</h3>
            </div>
            <div class="row g-4">
                <!-- Chrono Sport -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.chronometre') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-stopwatch text-warning" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Chrono Sport</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Chronomètre spécialisé pour natation et sports aquatiques
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Départs avec bip -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.departs') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-bell text-danger" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Départs avec bip</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Séries à intervalle fixe : bips 3-2-1, départ sonore et temps de chaque répétition
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- VNC -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.vnc') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-tachometer-alt text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Vitesse Nage</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Vitesse critique de nage et seuils métaboliques
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Prédicteur -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.predicteur') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-swimmer text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Prédicteur performance</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Prédiction scientifique des performances natation
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Planification -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.planificateur-natation') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-calendar-alt text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Planification natation</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Séances et périodisation natation personnalisées
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Efficacité -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.efficacite-technique') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-chart-area text-warning" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Efficacité Technique</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Analyse DPS et SWOLF pour technique optimale
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Performance -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-success">
                <i class="fas fa-heart text-success me-3" style="font-size: 2rem;"></i>
                <h3 class="mb-0 text-success">Performance</h3>
            </div>
            <div class="row g-4">
                <!-- Zones Cardio -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.zones-cardiaques') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-heart text-danger" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Zones Cardio</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Zones d'entraînement cardiaque personnalisées
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Cohérence Cardiaque -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.coherence-cardiaque') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-lungs text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Cohérence Cardiaque</h5>
                                        <span class="badge bg-info small">Bien-être</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Gestion du stress et optimisation récupération
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Course -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.planificateur-course') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-route text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Planification Course</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Plans d'entraînement course à pied progressifs
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 1RM -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.charge-maximale') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-dumbbell text-warning" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Charge Maximale</h5>
                                        <span class="badge bg-primary small">Avancé</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Calcul 1RM et charges d'entraînement sécurisées
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Fitness -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.condition-physique') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-chart-pie text-primary" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Ma Forme</h5>
                                        <span class="badge bg-success small">Essentiel</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Évaluation condition physique globale
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Outils Pratiques -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-warning">
                <i class="fas fa-tools text-warning me-3" style="font-size: 2rem;"></i>
                <h3 class="mb-0 text-warning">Outils pratiques</h3>
            </div>
            <div class="row g-4">
                <!-- Chrono Pro -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.chronometre-pro') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-stopwatch-20 text-danger" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Chrono Multi Pro</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Chronométrage multi-athlètes professionnel
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Carte Interactive -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.carte-interactive') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-map-marked-alt text-info" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Où Suis-Je Carte</h5>
                                        <span class="badge bg-secondary small">Pratique</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Géolocalisation et planification parcours sportifs
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Triathlon -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tools.planificateur-triathlon') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 hover-lift">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="tool-icon rounded-3 p-2 me-3">
                                        <i class="fas fa-biking text-success" style="font-size: 1.3rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title text-dark mb-1">Planification Triathlon</h5>
                                        <span class="badge bg-warning small">Pro</span>
                                    </div>
                                </div>
                                <p class="card-text text-muted small mb-0">
                                    Entraînement multidisciplinaire natation-vélo-course
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('public.tools.partials.credit')
@endsection

@push('styles')
<style>
.tool-icon { background: #eef6fa !important; min-width: 2.6rem; text-align: center; }

.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.category-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.category-card:hover {
    border-left-color: var(--bs-primary);
}

.card {
    transition: all 0.3s ease;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'entree pour les cards
    const cards = document.querySelectorAll('.category-card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush