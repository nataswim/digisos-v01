@extends('layouts.public')

@section('title', $unit->title . ' - ' . $module->name . ' - Catalogue')
@section('meta_description', $unit->description ?? 'Unité de formation : ' . $unit->title)
@section('meta_keywords', 'formation, ' . $unit->title . ', ' . $module->name)

@section('content')

<!-- Section Titre avec Breadcrumb -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container-lg">
        

        <div class="row align-items-center">
            <div class="col-lg mb-4 mb-lg-0">
                <!-- Badges -->

                <h1 class="display-4 fw-bold mb-3">
                    {{ $unit->title }}
                </h1>
                @if($unit->description)
    <p class="lead mb-0">
        {!! strip_tags($unit->description, '<strong><em><b><i>') !!}
    </p>
@endif
            </div>
        </div>
    </div>
</section>

<!-- Contenu principal -->
<section class="py-5 bg-light">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                @if($unit->contents->count() > 0)
                    
                    
                    <!-- Liste des contenus -->
                    <div class="card border-0 shadow-sm mb-4">
 
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach($unit->contents as $content)
                                    <div class="list-group-item px-4 py-3 content-item">
                                        <div class="row align-items-center">
                                            <!-- Numéro d'ordre -->
                                            <div class="col-auto">
                                                <div class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                                     style="width: 35px; height: 35px; font-size: 1rem;">
                                                    {{ $content->order }}
                                                </div>
                                            </div>
                                            
                                            <!-- Informations du contenu -->
                                            <div class="col">
                                                <h6 class="mb-1 fw-bold">
                                                    {{ $content->display_title }}
                                                </h6>
                                                
                                                <!-- Badges -->
                                                <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                                                    <span class="badge bg-info-subtle text-info">
                                                        <i class="fas {{ 
                                                            $content->contentable_type == 'App\Models\Video' ? 'fa-video' : 
                                                            ($content->contentable_type == 'App\Models\Fiche' ? 'fa-file-alt' : 
                                                            ($content->contentable_type == 'App\Models\Exercice' ? 'fa-dumbbell' : 
                                                            ($content->contentable_type == 'App\Models\Workout' ? 'fa-running' : 
                                                            ($content->contentable_type == 'App\Models\Downloadable' ? 'fa-download' : 
                                                            ($content->contentable_type == 'App\Models\EbookFile' ? 'fa-book' : 'fa-file')))))
                                                        }} me-1"></i>
                                                        {{ $content->content_type_label }}
                                                    </span>
                                                </div>
                                                
                                                @if($content->custom_description)
    <p class="text-muted mb-0 mt-2 small">
        {!! strip_tags($content->custom_description, '<strong><em><b><i><br>') !!}
    </p>
@endif
                                            </div>
                                            
                                            <!-- Bouton d'action -->
                                            <div class="col-auto">
                                                @if($content->content_url)
                                                    <a href="{{ $content->content_url }}" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-play-circle me-2"></i>
                                                        Accéder
                                                    </a>
                                                @else
                                                    <span class="badge bg-secondary p-2">
                                                        <i class="fas fa-lock me-1"></i>
                                                        Bientôt disponible
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Aucun contenu -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-5 text-center">
                            <div class="mb-4">
                                <i class="fas fa-hourglass-half fa-4x text-warning opacity-50"></i>
                            </div>
                            <h2 class="h4 fw-bold mb-3">Unité en cours de préparation</h2>
                            <p class="text-muted mb-0">
                                Les contenus de cette unité sont actuellement en cours de préparation
                                et seront disponibles prochainement.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Informations sur l'unité -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong class="text-muted d-block mb-1">Section</strong>
                                <a href="{{ route('public.catalogue.section', $section->slug) }}" 
                                   class="text-decoration-none">
                                    {{ $section->name }}
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong class="text-muted d-block mb-1">Module</strong>
                                <a href="{{ route('public.catalogue.module', [$section->slug, $module->slug]) }}" 
                                   class="text-decoration-none">
                                    {{ $module->name }}
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <!-- Bouton retour module -->
                    <a href="{{ route('public.catalogue.module', [$section->slug, $module->slug]) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au module
                    </a>

                    <!-- Bouton retour catalogue -->
                    <a href="{{ route('public.catalogue.index') }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-th-large me-2"></i>
                        Retour au catalogue
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Breadcrumb personnalisé */
.breadcrumb {
    background: transparent;
    margin-bottom: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: rgba(255, 255, 255, 0.7);
}

/* Dégradé pour l'en-tête */


/* Items de contenu */
.content-item {
    transition: all 0.3s ease;
    border-left: 3px solid transparent !important;
}

.content-item:hover {
    background-color: #f8f9fa;
    border-left-color: #0d6efd !important;
}

/* Cartes avec effet hover */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

/* Animation des icônes */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.fa-4x {
    animation: float 3s ease-in-out infinite;
}

/* Responsive */
@media (max-width: 767px) {
    .display-6 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation d'entrée pour les cartes
        const cards = document.querySelectorAll('.card');

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
        
        // Animation pour les items de contenu
        const contentItems = document.querySelectorAll('.content-item');
        contentItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                item.style.transition = 'all 0.5s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 100);
        });
    });
</script>
@endpush