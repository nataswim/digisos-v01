@extends('layouts.public')

@section('title', 'Espace Telechargement - Ressources et eBooks')
@section('meta_description', 'Decouvrez notre collection de ressources telechargeables : eBooks, guides, videos et documents pour votre developpement personnel et professionnel.')

@push('styles')
<style>
.hero-section {
        background: #008e80;
    padding: 4rem 0;
    color: white;
    border-left: 10px solid #0f5c78;
}

.category-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
}

.download-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.download-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.stats-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
}

.format-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    font-size: 0.75rem;
    font-weight: bold;
}
</style>
@endpush

@section('content')




<section class="position-relative text-white py-5 nataswim-titre3 overflow-hidden" style="min-height: 600px;">
    <!-- Video Background -->
    <video autoplay muted loop playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 1;">
        <source src="{{ asset('assets/images/team/ebooks.mp4') }}" type="video/mp4">
    </video>

    <!-- Overlay sombre pour meilleure lisibilité -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;"></div>

    <!-- Contenu -->
    <div class="container-lg py-4 position-relative" style="z-index: 3;">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">Ressources</h1>
                <p class="lead mb-4">
                    eBooks, guides pratiques, 
                    formation et documents pour booster votre developpement.
                </p>
                <div class="d-flex gap-3">
                    <a href="#categories" class="btn btn-light btn-lg">
                        Les categories
                    </a>
                    <a href="#featured" class="btn btn-outline-light btn-lg">
                        A la une
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="bg-white bg-opacity-10 rounded-3 p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="fw-bold mb-1">{{ $categories->sum('downloadables_count') }}</h3>
                                <small class="opacity-75">Ressources</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="fw-bold mb-1">{{ $categories->count() }}</h3>
                                <small class="opacity-75">Categories</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center">
                                <h3 class="fw-bold mb-1">{{ \App\Models\Downloadable::sum('download_count') }}</h3>
                                <small class="opacity-75">Telechargements</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Section Telechargements Vedettes -->
@if($featuredDownloads->count() > 0)
<section id="featured" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-3">
                    A la une
                </h2>
             
            </div>
        </div>

        <div class="row g-4">
            @foreach($featuredDownloads as $download)
                <div class="col-lg-4 col-md-6">
                    <div class="card download-card h-100">
                        <div class="position-relative">
                            @if($download->cover_image)
                                <img src="{{ $download->cover_image }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;"
                                     alt="{{ $download->title }}">
                            @else
                                <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-file-{{ $download->format === 'pdf' ? 'pdf' : ($download->format === 'mp4' ? 'video' : 'alt') }} fa-3x text-white"></i>
                                </div>
                            @endif
                            
                            <div class="format-badge">
                                <span class="badge bg-dark">{{ strtoupper($download->format) }}</span>
                            </div>
                            
                            <div class="stats-badge">
                                <i class="fas fa-water me-1"></i>{{ number_format($download->download_count) }}
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $download->category->name }}
                                </span>
                            </div>
                            <h5 class="card-title fw-bold mb-3">{{ $download->title }}</h5>
                            @if($download->short_description)
                                <p class="card-text text-muted flex-grow-1">
                                    {!! Str::limit($download->short_description, 120) !!}
                                </p>
                            @endif
                            
                            <div class="mt-auto">
                                @if($download->canBeDownloadedBy(auth()->user()))
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-2"></i>Voir les details
                                        </a>

                                    </div>
                                @else
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-2"></i>Voir les details
                                        </a>
                                        <div class="text-center">
                                            <small class="text-muted">
                                                <i class="fas fa-lock me-1"></i>
                                                {{ $download->getAccessMessage(auth()->user()) }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif




<!-- Section Categories -->
<section id="categories" class="py-5 nataswim-titre3" >
    <div class="container">
        

        @if($categories->count() > 0)
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-lg-4 col-md-6">
                        <div class="card category-card h-100">
                            <div class="card-body p-4 text-center">
                                @if($category->icon)
                                    <div class="mb-3">
                                        <i class="{{ $category->icon }} fa-3x text-primary"></i>
                                    </div>
                                @endif
                                <h5 class="fw-bold mb-3">{{ $category->name }}</h5>
                                @if($category->short_description)
                                    <p class="text-muted mb-4">{{ $category->short_description }}</p>
                                @endif
                                <div class="d-flex justify-content-center align-items-center mb-3">
                                    <span class="badge bg-primary-subtle text-primary me-2">
                                        {{ $category->downloadables_count }} ressource(s)
                                    </span>
                                </div>
                                <a href="{{ route('ebook.category', $category->slug) }}" 
                                   class="btn btn-outline-primary">
                                    Explorer ce rayon
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                <h4>Aucune categorie disponible</h4>
                <p class="text-muted">Les categories seront bientÃ´t disponibles.</p>
            </div>
        @endif
    </div>
</section>



<!-- Section Telechargements Recents -->
@if($recentDownloads->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="fw-bold mb-3">
                    Ressources
                </h2>

            </div>
        </div>

        <div class="row g-4">
            @foreach($recentDownloads as $download)
                <div class="col-lg-3 col-md-6">
                    <div class="card download-card h-100">
                        <div class="position-relative">
                            @if($download->cover_image)
                                <img src="{{ $download->cover_image }}" 
                                     class="card-img-top" 
                                     style="height: 100%; object-fit: cover;"
                                     alt="{{ $download->title }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 150px;">
                                    <i class="fas fa-file-{{ $download->format === 'pdf' ? 'pdf' : ($download->format === 'mp4' ? 'video' : 'alt') }} fa-2x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="format-badge">
                                <span class="badge bg-info">{{ strtoupper($download->format) }}</span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge bg-primary-subtle text-primary small">
                                    {{ $download->category->name }}
                                </span>
                            </div>
                            <h6 class="card-title fw-bold mb-3">{!! Str::limit($download->title, 50) !!}</h6>
                            
                            <div class="d-grid">
                                <a href="{{ route('ebook.show', [$download->category->slug, $download->slug]) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('ebook.search') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i>Voir toutes les ressources
            </a>
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au scroll
    const cards = document.querySelectorAll('.category-card, .download-card');
    
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
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});
</script>
@endpush