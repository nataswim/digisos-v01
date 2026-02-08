@extends('layouts.public')

@section('title', $category->name . ' - Espace Telechargement')
@section('meta_description', $category->short_description ?? 'Decouvrez tous les telechargements de la categorie ' . $category->name)

@section('content')

{{-- En-tête de categorie --}}
<section class="hero-aqua">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    @if($category->icon)
                        <i class="{{ $category->icon }} fa-3x me-3 text-white"></i>
                    @endif
                    <div>
                        <h1 class="title-aqua mb-2">{{ $category->name }}</h1>
                        @if($category->short_description)
                            <p class="lead text-white mb-0 opacity-90">{{ $category->short_description }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-aqua bg-white bg-opacity-10 text-white">
                    <div class="row g-3">
                        <div class="col-6">
                            <h3 class="fw-bold mb-1">{{ $stats['total'] }}</h3>
                            <small class="opacity-75">Ressources</small>
                        </div>
                        <div class="col-6">
                            <h3 class="fw-bold mb-1">{{ $stats['formats']->count() }}</h3>
                            <small class="opacity-75">Formats</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contenu principal --}}
<section class="section">
    <div class="container">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-primary-lighter px-3 py-2 rounded">
                <li class="breadcrumb-item">
                    <a href="{{ route('ebook.index') }}" class="text-primary text-decoration-none">
                        <i class="fas fa-home me-1"></i>Espace Telechargement
                    </a>
                </li>
                <li class="breadcrumb-item active text-primary-dark" aria-current="page">
                    {{ $category->name }}
                </li>
            </ol>
        </nav>

        <div class="row g-4">
            {{-- Sidebar filtres --}}
            <div class="col-lg-3">
                <div class="card-aqua sticky-top" style="top: 2rem;">
                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="fas fa-filter me-2 text-primary"></i>Filtres
                    </h6>

                    <form method="GET" action="{{ route('ebook.category', $category->slug) }}">
                        {{-- Recherche --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Rechercher</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Mots-cles...">
                        </div>

                        {{-- Formats --}}
                        @if($stats['formats']->count() > 0)
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Format</label>
                                <select name="format" class="form-select">
                                    <option value="">Tous les formats</option>
                                    @foreach($stats['formats'] as $format => $count)
                                        <option value="{{ $format }}" {{ request('format') === $format ? 'selected' : '' }}>
                                            {{ strtoupper($format) }} ({{ $count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

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
                            <a href="{{ route('ebook.category', $category->slug) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i>Reinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Liste des telechargements --}}
            <div class="col-lg-9">
                @if($downloadables->count() > 0)
                    {{-- En-tête avec compteur --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="title-section mb-0">
                            {{ $downloadables->total() }} ressource(s) disponible(s)
                        </h4>
                    </div>

                    {{-- Vue grille --}}
                    <div class="row g-4">
                        @foreach($downloadables as $download)
                            <div class="col-lg-4 col-md-6 fade-in-up">
                                <div class="card-aqua h-100">
                                    <div class="position-relative">
                                        @if($download->cover_image)
                                            <img src="{{ $download->cover_image }}" 
                                                 class="w-100 rounded-t-lg" 
                                                 style="height: 200px; object-fit: cover;"
                                                 alt="{{ $download->title }}">
                                        @else
                                            <div class="bg-secondary-lighter d-flex align-items-center justify-content-center rounded-t-lg" 
                                                 style="height: 200px;">
                                                <i class="fas fa-file-{{ $download->format === 'pdf' ? 'pdf' : ($download->format === 'mp4' ? 'video' : 'alt') }} fa-3x text-secondary"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="position-absolute top-0 start-0 m-3">
                                            <span class="badge-m2pc badge-materiel">{{ strtoupper($download->format) }}</span>
                                        </div>
                                        
                                        <div class="position-absolute top-0 end-0 m-3">
                                            <span class="badge bg-dark bg-opacity-75 text-white px-2 py-1 rounded">
                                                <i class="fas fa-download me-1"></i>{{ number_format($download->download_count) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 d-flex flex-column flex-grow-1">
                                        <h5 class="fw-bold mb-3">{{ $download->title }}</h5>
                                        
                                        @if($download->short_description)
                                            <p class="text-muted flex-grow-1 small">
                                                {!! Str::limit($download->short_description, 100) !!}
                                            </p>
                                        @endif

                                        @if($download->file_size)
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-hdd me-1"></i>{{ $download->file_size }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-auto">
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('ebook.show', [$category->slug, $download->slug]) }}" 
                                                   class="btn btn-outline-primary btn-sm hover-lift">
                                                    <i class="fas fa-eye me-2"></i>Voir les details
                                                </a>
                                                
                                                @if($download->canBeDownloadedBy(auth()->user()))
                                                    <a href="{{ route('ebook.download', [$category->slug, $download->slug]) }}" 
                                                       class="btn btn-success btn-sm hover-lift">
                                                        <i class="fas fa-download me-2"></i>Telecharger
                                                    </a>
                                                @else
                                                    <div class="alert alert-warning-lighter border-warning p-2 mb-0 mt-2">
                                                        <div class="text-center">
                                                            <i class="fas fa-lock text-warning me-1"></i>
                                                            <small class="text-muted">
                                                                {{ $download->getAccessMessage(auth()->user()) }}
                                                            </small>
                                                        </div>
                                                        <div class="d-flex gap-2 mt-2">
                                                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                                                <i class="fas fa-sign-in-alt me-1"></i>Connexion
                                                            </a>
                                                            <a href="{{ route('register') }}" class="btn btn-sm btn-primary flex-grow-1">
                                                                <i class="fas fa-user-plus me-1"></i>Inscription
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
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
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3 opacity-50"></i>
                        <h4>Aucune ressource trouvee</h4>
                        @if(request()->hasAny(['search', 'format']))
                            <p class="text-muted mb-3">Aucun resultat ne correspond a vos criteres de recherche.</p>
                            <a href="{{ route('ebook.category', $category->slug) }}" class="btn btn-outline-primary hover-lift">
                                <i class="fas fa-arrow-left me-2"></i>Voir toutes les ressources
                            </a>
                        @else
                            <p class="text-muted">Cette categorie ne contient pas encore de ressources.</p>
                            <a href="{{ route('ebook.index') }}" class="btn btn-primary hover-lift">
                                <i class="fas fa-arrow-left me-2"></i>Retour a l'accueil
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Description complète de la categorie --}}
@if($category->description)
<section class="section bg-aqua-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card-aqua">
                    <div class="content-display">
                        {!! nl2br(e($category->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@endsection