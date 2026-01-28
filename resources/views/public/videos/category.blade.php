@extends('layouts.public')

@section('title', $category->name . ' - Vidéos')
@section('meta_description', $category->description ?? 'Découvrez toutes les vidéos de la catégorie ' . $category->name)

@section('content')
<!-- Section titre avec breadcrumb -->
<section class="py-5 text-white text-center nataswim-titre3">
    <div class="container">
 

        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    {{ $category->name }}
                </h1>



                <div class="d-flex align-items-center gap-3 mt-4">
                    <span class="badge bg-light text-dark fs-6">
                        <i class="fas fa-video me-1"></i>{{ $videos->total() }} vidéo(s)
                    </span>
                </div>

    </div>
</section>










<!-- Liste des vidéos -->
<section class="py-5 bg-light">
    <div class="container">
        @if($videos->count() > 0)
        <div class="row g-4">
            @foreach($videos as $video)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-lg hover-lift">
                    @if($video->thumbnail)
                    <div class="position-relative">
                        <img src="{{ $video->thumbnail }}"
                            class="card-img-top"
                            style="height: 220px; object-fit: cover;"
                            alt="{{ $video->title }}">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <div class="bg-danger bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-play text-white fs-4"></i>
                            </div>
                        </div>
                        @if($video->duration)
                        <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark">
                            {{ $video->getFormattedDuration() }}
                        </span>
                        @endif

                        {{-- Badge Premium --}}
                        @if($video->visibility === 'authenticated')
                        <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark">
                            <i class="fas fa-crown me-1"></i>Premium
                        </span>
                        @endif
                    </div>
                    @else
                    <div class="card-img-top bg-primary d-flex align-items-center justify-content-center"
                        style="height: 220px;">
                        <i class="fas fa-video fa-4x text-white opacity-50"></i>
                    </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach($video->categories->take(2) as $cat)
                            <span class="badge bg-primary">
                                {{ $cat->name }}
                            </span>
                            @endforeach

                            @if($video->visibility === 'authenticated')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-crown me-1"></i>Premium
                            </span>
                            @endif

                            @if($video->is_featured)
                            <span class="badge bg-success">
                                <i class="fas fa-star me-1"></i>Vedette
                            </span>
                            @endif
                        </div>

                        <h5 class="card-title mb-3">{{ $video->title }}</h5>

                        @if($video->description)
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit(strip_tags($video->description), 120) }}
                        </p>
                        @endif

                        <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($video->views_count) }} vues
                            </small>
                            <a href="{{ route('public.videos.show', $video) }}"
                                class="btn btn-sm btn-primary">
                                Regarder <i class="fas fa-play ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <!-- Pagination -->
        @if($videos->hasPages())
            <div class="row mt-5">
                <div class="col-12">
                    <div class="mt-5">
                        {{ $videos->links('pagination.five-per-row') }}
                    </div>
                </div>
            </div>
        @endif
        @else
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="fas fa-video fa-3x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted mb-3">Aucune vidéo disponible dans cette catégorie</h5>
                <a href="{{ route('public.videos.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux vidéos
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Breadcrumb -->
<section class="py-3 bg-white border-bottom">
    <div class="container-lg">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb rounded px-3 py-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('public.videos.index') }}" class="text-dark">
                        <i class="fas fa-home me-1"></i>Vidéos
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $category->name }}
                </li>
            </ol>
        </nav>
    </div>
                </div>

            @if($category->image)
            <div class="col-lg text-center mt-4 mt-lg-0 ">
                <img src="{{ $category->image }}"
                    alt="{{ $category->name }}"
                    class="img-fluid rounded shadow-lg"
                    style="max-height: 250px; object-fit: cover;">
                            @if($category->description)
                <p class="lead mb-0">{{ $category->description }}</p>
                @endif
                </div>
            
            @endif
            
        </div>
</section>

<!-- Navigation rapide -->
<section class="py-4 border-top">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('public.videos.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-th me-2"></i>Toutes les catégories
            </a>
        </div>
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
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    
</style>
@endpush