@extends('layouts.public')

@section('title', $category->meta_title ?: $category->name)
@section('meta_description', $category->meta_description ?: ($category->description ? Str::limit($category->description, 160) : ''))
@section('meta_keywords', $category->meta_keywords ?: '')

@section('content')
<!-- En-tête de la catégorie -->
<section class="py-5 bg-primary text-white nataswim-titre3" style="border-left: 20px solid #f9f5f4;border-bottom: 10px solid #4097b5;border-top: 10px solid #4097b5;background-image: linear-gradient(
309deg, rgb(95 202 199) 85%, rgb(64 151 181) 70px);background-attachment: fixed;background-position: top;border-right: 20px solid #f9f5f4;">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg">
                <!-- Fil d'Ariane -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-dark text-decoration-none">
                                Accueil
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('posts.public.index') }}" class="text-dark text-decoration-none">
                                Catégories
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-dark" aria-current="page">
                            {{ $category->name }}
                        </li>
                    </ol>
                </nav>

                <h1 class="display-5 fw-bold mb-3">
                    {{ $category->name }}
                </h1>
                
                @if($category->description)
                    <p class="lead mb-0">{{ $category->description }}</p>
                @endif

                <div class="mt-3">
                    <span class="badge fs-6">
                        <i class="fas fa-file-alt me-1"></i>
                        {{ $posts->total() }} 
                    </span>
                    @if($category->group_name)
                        <span class="badge bg-warning ms-2 fs-6">
                            <i class="fas fa-layer-group me-1"></i>{{ $category->group_name }}
                        </span>
                    @endif
                </div>
            </div>

            @if($category->image)
                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                    <img src="{{ $category->image }}" 
                         alt="{{ $category->name }}"
                         class="img-fluid rounded shadow"
                         style="max-height: 200px; object-fit: cover;">
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Liste des articles -->
<section class="py-5" style="background-image: linear-gradient(229deg, #f9f5f4 85%, #ffffff 0);background-attachment: fixed;background-position: top;">
    <div class="container-lg">
        @if($posts->count() > 0)
            <div class="row g-4" style=" background-color: #fff; padding: 20px 0px; border-radius: 10px; ">
                @foreach($posts as $post)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <!-- Image de l'article -->
                            <div style="height: 180px; overflow: hidden;">
                                @if($post->image)
                                    <img src="{{ $post->image }}" 
                                         alt="{{ $post->name }}"
                                         class="card-img-top"
                                         style="height: 100%; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 100%;">
                                        <i class="fas fa-file-alt text-muted" style="font-size: 2.5rem;"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Contenu -->
                            <div class="card-body">
                                <h3 class="card-title h5 mb-3">
                                    <a href="{{ route('posts.public.show', $post->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ $post->name }}
                                    </a>
                                </h3>
                                
                                @if($post->intro)
                                    <p class="card-text text-muted small">
                                        {!! Str::limit(strip_tags($post->intro), 100) !!}
                                    </p>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $post->published_at->format('d/m/Y') }}
                                </small>
                                <a href="{{ route('posts.public.show', $post->slug) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    Lire
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="mt-5">
                            {{ $posts->links('pagination.five-per-row') }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Aucun article -->
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3 opacity-25"></i>
                <h3 class="text-muted">Aucun article dans cette catégorie</h3>
                <p class="text-muted mb-4">Revenez bientôt pour découvrir du nouveau contenu !</p>
                <a href="{{ route('posts.public.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Voir toutes les catégories
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.7);
}

@media (max-width: 768px) {
    .display-5 {
        font-size: 1.75rem !important;
    }
}
</style>
@endpush
