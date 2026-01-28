@extends('layouts.public')

@section('title', $sousCategory->name . ' - ' . $category->name)
@section('meta_description', $sousCategory->description ?? 'Découvrez tous les exercices de la sous-catégorie ' . $sousCategory->name)

@section('content')

<!-- Section titre -->
<section class="py-5 text-white text-center nataswim-titre3">     <div class="container">
        <div class="row align-items-center">
            <div class="col-lg">
                <h1 class="display-4 fw-bold mb-3">{{ $sousCategory->name }}</h1>
                
                @if($sousCategory->description)
                    <p class="lead mb-0">{!! $sousCategory->description !!}</p>
                @endif
                
                <div class="d-flex align-items-center gap-3 mt-4">
                    <span class="badge bg-light text-dark fs-6">
                        <i class="fas fa-folder me-1"></i>{{ $category->name }}
                    </span>
                    <span class="badge bg-light text-dark fs-6">
                        <i class="fas fa-running me-1"></i>{{ $exercices->total() }} exercice(s)
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Breadcrumb -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-primary px-3 py-2 mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('exercices.index') }}" class="text-white">
                        <i class="fas fa-home me-1"></i>Exercices
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('exercices.category', $category) }}" class="text-white">
                        {{ $category->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">
                    {{ $sousCategory->name }}
                </li>
            </ol>
        </nav>
    </div>
</section>

<!-- Liste des exercices -->
<section class="py-5 bg-light">
    <div class="container">
        @if($exercices->count() > 0)
            <div class="row g-4">
                @foreach($exercices as $exercice)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-lg hover-lift">
                            @if($exercice->image)
                                <img src="{{ $exercice->image }}" 
                                     class="card-img-top" 
                                     style="height: 220px; object-fit: cover;"
                                     alt="{{ $exercice->titre }}">
                            @else
                                <div class="card-img-top bg-primary d-flex align-items-center justify-content-center" 
                                     style="height: 220px;">
                                    <i class="fas fa-running fa-4x text-white opacity-50"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge bg-info">
                                        <i class="fas fa-layer-group me-1"></i>{{ $sousCategory->name }}
                                    </span>
                                </div>
                                
                                <h5 class="card-title mb-3">{{ $exercice->titre }}</h5>
                                
                                <p class="card-text text-muted flex-grow-1">
                                    {!! Str::limit(strip_tags($exercice->description), 120) !!}
                                </p>
                                
                                <div class="mt-auto">
                                    <a href="{{ route('exercices.show', $exercice) }}" 
                                       class="btn btn-primary w-100">
                                        Voir <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <!-- Pagination -->
@if($exercices->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <div class="mt-5">
                {{ $exercices->links('pagination.five-per-row') }}
            </div>
        </div>
    </div>
@endif
        @else
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="fas fa-running fa-3x text-muted mb-3 opacity-25"></i>
                    <h5 class="text-muted mb-3">Aucun exercice disponible dans cette sous-catégorie</h5>
                    <a href="{{ route('exercices.category', $category) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à {{ $category->name }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Navigation rapide -->
<section class="py-4 bg-primary ">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('exercices.category', $category) }}" class="btn bg-warning text-white p-2">
                <i class="fas fa-arrow-left me-2"></i>Retour à {{ $category->name }}
            </a>
            <a href="{{ route('exercices.index') }}" class="btn bg-warning text-white p-2">
                <i class="fas fa-th me-2"></i>Toutes les catégories
            </a>
        </div>

                    
            @if($sousCategory->image)
                <div class="col-lg text-center mt-4 mt-lg-0">
                    <img src="{{ $sousCategory->image }}" 
                         alt="{{ $sousCategory->name }}" 
                         class="img-fluid rounded shadow-lg"
                         style="max-height: 600px; max-width: -webkit-fill-available;">
                </div>
            @endif
    </div>
</section>

@endsection

@push('styles')
<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}


</style>
@endpush