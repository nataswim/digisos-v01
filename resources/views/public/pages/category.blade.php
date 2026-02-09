@extends('layouts.public')

@section('title', $category->name . ' - Pages')
@section('meta_description', $category->description ?? 'Découvrez toutes les pages de la catégorie ' . $category->name)

@section('content')

<!-- Hero Section -->
<section class="nataswim-titre1 position-relative  text-white">
    <div class="py-5 container ">
        <h1 class="text-white">{{ $category->name }}</h1>
        
        @if($category->description)
        <div class="lead mb-0 category-hero-description">
            {!! Str::limit($category->description, 200) !!}
        </div>
        @endif
        
        <div class="d-flex align-items-center justify-content-center gap-3 mt-4 flex-wrap">
            <span class="badge badge-primary fs-6 px-4 py-2">
                <i class="fas fa-file-alt me-2"></i>{{ $pages->total() }} page(s)
            </span>
        </div>
    </div>
</section>

<!-- Liste des pages -->
<section class="py-5 bg-white">
    <div class="container-lg">
        @if($pages->count() > 0)
        <div class="row g-4">
            @foreach($pages as $page)
            <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                <div class="card-aqua h-100 hover-lift">
                    @if($page->image)
                    <img src="{{ $page->image }}" 
                         class="card-img-top rounded-top" 
                         style="height: 220px; object-fit: cover;"
                         alt="{{ $page->title }}">
                    @else
                    <div class="card-img-top bg-primary-lighter rounded-top d-flex align-items-center justify-content-center" 
                         style="height: 220px;">
                        <i class="fas fa-file-alt fa-4x text-primary opacity-50"></i>
                    </div>
                    @endif
                    
                    <div class="d-flex flex-column p-4">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($page->visibility === 'authenticated')
                            <span class="badge badge-info">
                                <i class="fas fa-lock me-1"></i>Membres
                            </span>
                            @endif
                        </div>
                        
                        <h5 class="mb-3">{{ $page->title }}</h5>
                        
                        <p class="text-muted flex-grow-1">
                            {!! Str::limit(strip_tags($page->short_description), 120) !!}
                        </p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                            <small class="text-muted">
                                @if($page->visibility === 'public')
                                    <i class="fas fa-globe me-1"></i>Public
                                @else
                                    <i class="fas fa-lock me-1"></i>Membres
                                @endif
                            </small>
                            @if($page->canViewContent(auth()->user()))
                            <a href="{{ route('public.pages.show', [$category, $page]) }}" 
                               class="btn btn-sm btn-primary">
                                Lire <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i>Restreint
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($pages->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                {{ $pages->links('pagination.five-per-row') }}
            </div>
        </div>
        @endif
        @else
        <div class="card-aqua text-center py-5">
            <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-25"></i>
            <h5 class="text-muted mb-3">Aucune page disponible dans cette catégorie</h5>
            <a href="{{ route('public.pages.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour aux catégories
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Navigation -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start">
                <a href="{{ route('public.pages.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-th me-2"></i>Toutes les pages
                </a>
            </div>
            
            @if($category->image)
            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <img src="{{ $category->image }}" 
                     alt="{{ $category->name }}" 
                     class="img-fluid rounded-lg shadow-aqua"
                     style="max-height: 300px;">
            </div>
            @endif
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Styles pour la description dans le hero */
.category-hero-description {
    color: rgba(255, 255, 255, 0.95);
}

.category-hero-description p {
    margin-bottom: 0.5rem;
    color: rgba(255, 255, 255, 0.95);
}

.category-hero-description strong {
    font-weight: 600;
    color: #fff;
}

.category-hero-description em {
    font-style: italic;
}

.category-hero-description ul,
.category-hero-description ol {
    margin: 0.5rem 0;
    padding-left: 1.5rem;
    color: rgba(255, 255, 255, 0.95);
}

.category-hero-description li {
    margin-bottom: 0.25rem;
}
</style>
@endpush
