@extends('layouts.public')

@section('title', $sousCategory->name . ' - ' . $category->name)
@section('meta_description', $sousCategory->description ?? 'Découvrez toutes les fiches pratiques de la sous-catégorie ' . $sousCategory->name)

@section('content')

<!-- Hero Section -->
<section class="hero-aqua">
    <div class="hero-content">
        <h1 class="text-white">{{ $sousCategory->name }}</h1>
        
        @if($sousCategory->description)
        <p class="lead mb-0">{{ $sousCategory->description }}</p>
        @endif
        
        <div class="d-flex align-items-center justify-content-center gap-3 mt-4 flex-wrap">
            <span class="badge badge-primary fs-6 px-4 py-2">
                <i class="fas fa-folder me-2"></i>{{ $category->name }}
            </span>
            <span class="badge badge-info fs-6 px-4 py-2">
                <i class="fas fa-file-alt me-2"></i>{{ $fiches->total() }} fiche(s)
            </span>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<section class="py-3 bg-aqua-light border-bottom">
    <div class="container-lg">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('public.fiches.index') }}" class="text-primary">
                        <i class="fas fa-home me-1"></i>Fiches
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('public.fiches.category', $category) }}" class="text-primary">
                        {{ $category->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $sousCategory->name }}
                </li>
            </ol>
        </nav>
    </div>
</section>

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
                            <span class="badge badge-info">
                                <i class="fas fa-layer-group me-1"></i>{{ $sousCategory->name }}
                            </span>
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
                                <i class="fas fa-eye me-1"></i>{{ number_format($fiche->views_count ?? 0) }} Lectures
                            </small>
                            <a href="{{ route('public.fiches.show', [$category, $sousCategory, $fiche]) }}" 
                               class="btn btn-sm btn-primary">
                                Lire <i class="fas fa-arrow-right ms-1"></i>
                            </a>
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
            <h5 class="text-muted mb-3">Aucune fiche disponible dans cette sous-catégorie</h5>
            <a href="{{ route('public.fiches.category', $category) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour à {{ $category->name }}
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Navigation -->
<section class="py-5 bg-aqua-light">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('public.fiches.category', $category) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à {{ $category->name }}
                    </a>
                    <a href="{{ route('public.fiches.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-th me-2"></i>Toutes les catégories
                    </a>
                </div>
            </div>
            
            @if($sousCategory->image)
            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <img src="{{ $sousCategory->image }}" 
                     alt="{{ $sousCategory->name }}" 
                     class="img-fluid rounded-lg shadow-aqua"
                     style="max-height: 300px;">
            </div>
            @endif
        </div>
    </div>
</section>

@endsection