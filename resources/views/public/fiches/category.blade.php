@extends('layouts.public')

@section('title', $category->name . ' - Fiches Pratiques')
@section('meta_description', $category->description ?? 'Découvrez toutes les fiches pratiques de la catégorie ' . $category->name)

@section('content')

<!-- Hero Section -->
<section class="nataswim-titre1 position-relative  text-white">
    <div class="py-5 container ">
        <h1 class="text-white">{{ $category->name }}</h1>
        
        @if($category->description)
        <p class="lead mb-0">{{ $category->description }}</p>
        @endif
        
        <div class="d-flex align-items-center justify-content-center gap-3 mt-4 flex-wrap">
            <span class="badge badge-primary fs-6 px-4 py-2">
                <i class="fas fa-file-alt me-2"></i>{{ $fiches->total() }} fiche(s)
            </span>
        </div>
    </div>
</section>

<!-- Sous-catégories -->
@if(isset($sousCategories) && $sousCategories->count() > 0)
<section class="py-5 bg-white">
    <div class="container-lg">        
        <div class="row g-4">
            @foreach($sousCategories as $sousCategory)
            <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                <a href="{{ route('public.fiches.sous-category', [$category, $sousCategory]) }}" 
                   class="text-decoration-none">
                    <div class="card-aqua h-100 hover-lift">
                        <div class="d-flex align-items-center">
                            @if($sousCategory->image)
                            <img src="{{ $sousCategory->image }}" 
                                 class="rounded me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 alt="{{ $sousCategory->name }}">
                            @else
                            <div class="bg-info-lighter rounded d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-layer-group text-info fs-3"></i>
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark">{{ $sousCategory->name }}</h6>
                                <small class="text-muted">
                                    {{ $sousCategory->published_fiches_count }} fiche(s)
                                </small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <hr class="my-5 divider-aqua">
    </div>
</section>
@endif


<!-- Liste des fiches -->
<section class="py-5 bg-white">
    <div class="container-lg">
        @if($fiches->count() > 0)
        <div class="row g-4">
            @foreach($fiches as $fiche)
            <div class="col-md-6 col-lg-4 fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                <div class="card-aqua h-100 hover-lift">
                    @if($fiche->image)
                    <img src="{{ $fiche->image }}" 
                         class="card-img-top rounded-top" 
                         style="height: 220px; object-fit: cover;"
                         alt="{{ $fiche->title }}">
                    @else
                    <div class="card-img-top bg-primary-lighter rounded-top d-flex align-items-center justify-content-center" 
                         style="height: 220px;">
                        <i class="fas fa-file-alt fa-4x text-primary opacity-50"></i>
                    </div>
                    @endif
                    
                    <div class="d-flex flex-column p-4">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($fiche->is_featured)
                            <span class="badge badge-warning">
                                <i class="fas fa-star me-1"></i>En vedette
                            </span>
                            @endif
                            @if($fiche->visibility === 'authenticated')
                            <span class="badge badge-info">
                                <i class="fas fa-lock me-1"></i>Membres
                            </span>
                            @endif
                        </div>
                        
                        <h5 class="mb-3">{{ $fiche->title }}</h5>
                        
                        <p class="text-muted flex-grow-1">
                            {!! Str::limit(strip_tags($fiche->short_description), 120) !!}
                        </p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($fiche->views_count ?? 0) }} lectures
                            </small>
                            @if($fiche->sousCategory)
                            <a href="{{ route('public.fiches.show', [$category, $fiche->sousCategory, $fiche]) }}" 
                               class="btn btn-sm btn-primary">
                                Lire <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>
                                <i class="fas fa-lock me-1"></i>Indisponible
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($fiches->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                {{ $fiches->links('pagination.five-per-row') }}
            </div>
        </div>
        @endif
        @else
        <div class="card-aqua text-center py-5">
            <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-25"></i>
            <h5 class="text-muted mb-3">Aucune fiche disponible dans cette catégorie</h5>
            <a href="{{ route('public.fiches.index') }}" class="btn btn-primary">
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
                <a href="{{ route('public.fiches.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-th me-2"></i>Toutes les fiches
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