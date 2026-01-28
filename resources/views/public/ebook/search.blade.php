@extends('layouts.public')

@section('title', 'Rechercher - Espace Telechargement')
@section('meta_description', 'Recherchez parmi notre collection de ressources telechargeables : eBooks, guides, videos et documents.')

@push('styles')
<style>
.search-header {
    background: linear-gradient(116deg, #0f5c78 0%, #016170 100%);
    padding: 2rem 0;
    color: white;
}

.search-form {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
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

.filter-sidebar {
    background: #f8f9fa;
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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

.search-results-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 1rem;
    margin-bottom: 2rem;
}
</style>
@endpush

@section('content')
<!-- En-tÃªte de recherche -->
<section class="search-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3">Rechercher des ressources</h1>
                <p class="lead mb-4">
                    Trouvez exactement ce que vous cherchez parmi notre collection
                </p>
                
                <!-- Formulaire de recherche principal -->
                <div class="search-form p-4">
                    <form method="GET" action="{{ route('ebook.search') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" 
                                       name="q" 
                                       value="{{ $query }}"
                                       class="form-control form-control-lg"
                                       placeholder="Mots-cles...">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select form-select-lg">
                                    <option value="">Toutes categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="format" class="form-select form-select-lg">
                                    <option value="">Tous formats</option>
                                    @foreach($formats as $fmt)
                                        <option value="{{ $fmt }}" {{ $format === $fmt ? 'selected' : '' }}>
                                            {{ strtoupper($fmt) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Resultats de recherche -->
<section class="py-5">
    <div class="container">
        <!-- En-tÃªte des resultats -->
        <div class="search-results-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-2">
                        @if($query || $categoryId || $format)
                            Resultats de recherche
                        @else
                            Toutes les ressources
                        @endif
                    </h2>
                    <p class="text-muted mb-0">
                        {{ $downloadables->total() }} ressource(s) trouvee(s)
                        @if($query)
                            pour "<strong>{{ $query }}</strong>"
                        @endif
                        @if($categoryId)
                            @php $selectedCategory = $categories->firstWhere('id', $categoryId) @endphp
                            dans <strong>{{ $selectedCategory->name ?? 'categorie inconnue' }}</strong>
                        @endif
                        @if($format)
                            au format <strong>{{ strtoupper($format) }}</strong>
                        @endif
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    @if($query || $categoryId || $format)
                        <a href="{{ route('ebook.search') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Effacer les filtres
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if($downloadables->count() > 0)
            <div class="row g-4">
                <!-- Filtres lateraux -->
                <div class="col-lg-3">
                    <div class="filter-sidebar p-4 sticky-top" style="top: 2rem;">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-filter me-2"></i>Affiner la recherche
                        </h6>
                        
                        <form method="GET" action="{{ route('ebook.search') }}">
                            <input type="hidden" name="q" value="{{ $query }}">
                            
                            <!-- Categories -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Categorie</label>
                                <select name="category" class="form-select form-select-sm">
                                    <option value="">Toutes categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Formats -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Format</label>
                                <select name="format" class="form-select form-select-sm">
                                    <option value="">Tous formats</option>
                                    @foreach($formats as $fmt)
                                        <option value="{{ $fmt }}" {{ $format === $fmt ? 'selected' : '' }}>
                                            {{ strtoupper($fmt) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tri -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Trier par</label>
                                <select name="sort" class="form-select form-select-sm">
                                    <option value="relevance" {{ request('sort', 'relevance') === 'relevance' ? 'selected' : '' }}>
                                        Pertinence
                                    </option>
                                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>
                                        Titre A-Z
                                    </option>
                                    <option value="downloads" {{ request('sort') === 'downloads' ? 'selected' : '' }}>
                                        Plus telecharges
                                    </option>
                                    <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>
                                        Plus recents
                                    </option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-2"></i>Appliquer
                                </button>
                            </div>
                        </form>

                        <!-- Statistiques -->
                        <hr class="my-4">
                        <div class="text-center">
                            <h6 class="fw-bold mb-3">Statistiques</h6>
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="bg-primary bg-opacity-10 rounded p-2">
                                        <div class="fw-bold text-primary">{{ $downloadables->total() }}</div>
                                        <small class="text-muted">Resultats</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-success bg-opacity-10 rounded p-2">
                                        <div class="fw-bold text-success">{{ $categories->count() }}</div>
                                        <small class="text-muted">Categories</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resultats -->
                <div class="col-lg-9">
                    <div class="row g-4">
                        @foreach($downloadables as $downloadable)
                            <div class="col-lg-4 col-md-6">
                                <div class="card download-card h-100">
                                    <div class="position-relative">
                                        @if($downloadable->cover_image)
                                            <img src="{{ $downloadable->cover_image }}" 
                                                 class="card-img-top" 
                                                 style="height: 100%; object-fit: cover;"
                                                 alt="{{ $downloadable->title }}">
                                        @else
                                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="fas fa-file-{{ $downloadable->format === 'pdf' ? 'pdf' : ($downloadable->format === 'mp4' ? 'video' : 'alt') }} fa-3x text-white"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="format-badge">
                                            <span class="badge bg-dark">{{ strtoupper($downloadable->format) }}</span>
                                        </div>
                                        
                                        <div class="stats-badge">
                                            <i class="fas fa-water me-1"></i>{{ number_format($downloadable->download_count) }}
                                        </div>

                                        <!-- Indicateur de permission -->
                                        <div class="position-absolute" style="bottom: 1rem; left: 1rem;">
                                            @if($downloadable->user_permission === 'public')
                                                <span class="badge bg-success" title="Acces libre">
                                                    <i class="fas fa-water"></i>
                                                </span>
                                            @elseif($downloadable->user_permission === 'visitor')
                                                <span class="badge bg-info" title="Visiteurs uniquement">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-warning" title="Membres uniquement">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <div class="mb-2">
                                            <span class="badge bg-primary-subtle text-primary">
                                                {{ $downloadable->category->name }}
                                            </span>
                                        </div>
                                        
                                        <h5 class="card-title fw-bold mb-3">{{ $downloadable->title }}</h5>
                                        
                                        @if($downloadable->short_description)
                                            <p class="card-text text-muted flex-grow-1 small">
                                                {!! Str::limit($downloadable->short_description, 100) !!}
                                            </p>
                                        @endif

                                        @if($downloadable->file_size)
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-hdd me-1"></i>{{ $downloadable->file_size }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-auto">
                                            @if($downloadable->canBeDownloadedBy(auth()->user()))
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('ebook.show', [$downloadable->category->slug, $downloadable->slug]) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye me-2"></i>Voir les details
                                                    </a>
                                                    <a href="{{ route('ebook.download', [$downloadable->category->slug, $downloadable->slug]) }}" 
                                                       class="btn btn-success btn-sm">
                                                        <i class="fas fa-water me-2"></i>Telecharger
                                                    </a>
                                                </div>
                                            @else
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('ebook.show', [$downloadable->category->slug, $downloadable->slug]) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye me-2"></i>Voir les details
                                                    </a>
                                                    <div class="text-center mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-lock me-1"></i>
                                                            @if($downloadable->user_permission === 'user' && !auth()->check())
                                                                <a href="{{ route('login') }}" class="text-decoration-none">
                                                                    Connexion requise
                                                                </a>
                                                            @else
                                                                Acces restreint
                                                            @endif
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

                    <!-- Pagination -->
                    @if($downloadables->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $downloadables->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3 opacity-50"></i>
                <h4>Aucune ressource trouvee</h4>
                @if($query || $categoryId || $format)
                    <p class="text-muted mb-3">Aucun resultat ne correspond A vos criteres de recherche.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('ebook.search') }}" class="btn btn-outline-primary">
                            <i class="fas fa-times me-2"></i>Effacer les filtres
                        </a>
                        <a href="{{ route('ebook.index') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Retour A l'accueil
                        </a>
                    </div>
                @else
                    <p class="text-muted mb-3">Commencez par effectuer une recherche.</p>
                    <a href="{{ route('ebook.index') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>Decouvrir les categories
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- Suggestions de recherche -->
@if(!$query && !$categoryId && !$format)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="fw-bold mb-4">Suggestions de recherche</h3>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    @foreach($categories->take(5) as $cat)
                        <a href="{{ route('ebook.search', ['category' => $cat->id]) }}" 
                           class="btn btn-outline-primary">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                    @foreach($formats->take(3) as $fmt)
                        <a href="{{ route('ebook.search', ['format' => $fmt]) }}" 
                           class="btn btn-outline-secondary">
                            {{ strtoupper($fmt) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au scroll
    const cards = document.querySelectorAll('.download-card');
    
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

    // Auto-submit du formulaire de recherche
    const filterForm = document.querySelector('.filter-sidebar form');
    if (filterForm) {
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Auto-submit apres un delai pour eviter les soumissions multiples
                setTimeout(() => {
                    filterForm.submit();
                }, 300);
            });
        });
    }
});
</script>
@endpush