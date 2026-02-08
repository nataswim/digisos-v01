@extends('layouts.public')

@section('title', 'Rechercher - Espace Telechargement')
@section('meta_description', 'Recherchez parmi notre collection de ressources telechargeables : eBooks, guides, videos et documents.')

@section('content')

{{-- En-tête de recherche --}}
<section class="hero-aqua">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="title-aqua mb-3">Rechercher des ressources</h1>
                <p class="lead text-white mb-4">
                    Trouvez exactement ce que vous cherchez parmi notre collection
                </p>
                
                {{-- Formulaire de recherche principal --}}
                <div class="card-aqua bg-white shadow-aqua-lg">
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
                                <button type="submit" class="btn btn-primary btn-lg w-100 hover-lift">
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

{{-- Resultats de recherche --}}
<section class="section">
    <div class="container">
        {{-- En-tête des resultats --}}
        <div class="border-bottom-2 border-primary pb-4 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="title-section">
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
                        <a href="{{ route('ebook.search') }}" class="btn btn-outline-secondary hover-lift">
                            <i class="fas fa-times me-2"></i>Effacer les filtres
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if($downloadables->count() > 0)
            <div class="row g-4">
                {{-- Filtres lateraux --}}
                <div class="col-lg-3">
                    <div class="card-aqua sticky-top" style="top: 2rem;">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="fas fa-filter me-2 text-primary"></i>Affiner la recherche
                        </h6>
                        
                        <form method="GET" action="{{ route('ebook.search') }}">
                            <input type="hidden" name="q" value="{{ $query }}">
                            
                            {{-- Categories --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Categorie</label>
                                <select name="category" class="form-select">
                                    <option value="">Toutes categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Formats --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Format</label>
                                <select name="format" class="form-select">
                                    <option value="">Tous formats</option>
                                    @foreach($formats as $fmt)
                                        <option value="{{ $fmt }}" {{ $format === $fmt ? 'selected' : '' }}>
                                            {{ strtoupper($fmt) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Tri --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Trier par</label>
                                <select name="sort" class="form-select">
                                    <option value="title" {{ request('sort', 'title') === 'title' ? 'selected' : '' }}>
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
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                                <a href="{{ route('ebook.search') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i>Reinitialiser
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Grille des resultats --}}
                <div class="col-lg-9">
                    <div class="row g-4">
                        @foreach($downloadables as $downloadable)
                            <div class="col-lg-4 col-md-6 fade-in-up">
                                <div class="card-aqua h-100">
                                    <div class="position-relative">
                                        @if($downloadable->cover_image)
                                            <img src="{{ $downloadable->cover_image }}" 
                                                 class="w-100 rounded-t-lg" 
                                                 style="height: 200px; object-fit: cover;"
                                                 alt="{{ $downloadable->title }}">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded-t-lg" 
                                                 style="height: 200px;">
                                                <i class="fas fa-file-{{ $downloadable->format === 'pdf' ? 'pdf' : ($downloadable->format === 'mp4' ? 'video' : 'alt') }} fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge-m2pc badge-materiel">{{ strtoupper($downloadable->format) }}</span>
                                        </div>
                                        
                                        <div class="position-absolute top-0 end-0 m-3">
                                            <span class="badge bg-dark bg-opacity-75 text-white px-2 py-1 rounded">
                                                <i class="fas fa-download me-1"></i>{{ number_format($downloadable->download_count) }}
                                            </span>
                                        </div>

                                        <div class="position-absolute bottom-0 end-0 m-3">
                                            @if($downloadable->user_permission === 'all')
                                                <span class="badge bg-success" title="Acces libre">
                                                    <i class="fas fa-unlock"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-warning" title="Membres uniquement">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 d-flex flex-column flex-grow-1">
                                        <div class="mb-2">
                                            <span class="badge badge-primary">
                                                {{ $downloadable->category->name }}
                                            </span>
                                        </div>
                                        
                                        <h5 class="fw-bold mb-3">{{ $downloadable->title }}</h5>
                                        
                                        @if($downloadable->short_description)
                                            <p class="text-muted flex-grow-1 small">
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
                                                       class="btn btn-outline-primary btn-sm hover-lift">
                                                        <i class="fas fa-eye me-2"></i>Voir les details
                                                    </a>
                                                    <a href="{{ route('ebook.download', [$downloadable->category->slug, $downloadable->slug]) }}" 
                                                       class="btn btn-success btn-sm hover-lift">
                                                        <i class="fas fa-download me-2"></i>Telecharger
                                                    </a>
                                                </div>
                                            @else
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('ebook.show', [$downloadable->category->slug, $downloadable->slug]) }}" 
                                                       class="btn btn-outline-primary btn-sm hover-lift">
                                                        <i class="fas fa-eye me-2"></i>Voir les details
                                                    </a>
                                                    <div class="text-center mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-lock me-1"></i>
                                                            @if($downloadable->user_permission === 'user' && !auth()->check())
                                                                <a href="{{ route('login') }}" class="text-decoration-none text-primary">
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

                    {{-- Pagination --}}
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
                    <p class="text-muted mb-3">Aucun resultat ne correspond a vos criteres de recherche.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('ebook.search') }}" class="btn btn-outline-primary hover-lift">
                            <i class="fas fa-times me-2"></i>Effacer les filtres
                        </a>
                        <a href="{{ route('ebook.index') }}" class="btn btn-primary hover-lift">
                            <i class="fas fa-home me-2"></i>Retour a l'accueil
                        </a>
                    </div>
                @else
                    <p class="text-muted mb-3">Commencez par effectuer une recherche.</p>
                    <a href="{{ route('ebook.index') }}" class="btn btn-primary hover-lift">
                        <i class="fas fa-home me-2"></i>Decouvrir les categories
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

{{-- Suggestions de recherche --}}
@if(!$query && !$categoryId && !$format)
<section class="section bg-aqua-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="title-section">Suggestions de recherche</h3>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    @foreach($categories->take(5) as $cat)
                        <a href="{{ route('ebook.search', ['category' => $cat->id]) }}" 
                           class="btn btn-outline-primary hover-lift">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                    @foreach($formats->take(3) as $fmt)
                        <a href="{{ route('ebook.search', ['format' => $fmt]) }}" 
                           class="btn btn-outline-secondary hover-lift">
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