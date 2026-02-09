@extends('layouts.public')

{{-- SEO Meta --}}
@section('title', $fiche->title)
@section('meta_description', strip_tags($fiche->short_description))

{{-- Open Graph / Facebook --}}
@section('og_type', 'article')
@section('og_title', $fiche->title)
@section('og_description', strip_tags($fiche->short_description))
@if($fiche->image)
    @section('og_image', $fiche->image)
    @section('og_image_alt', $fiche->title)
@endif

{{-- Twitter Card --}}
@section('twitter_title', $fiche->title)
@section('twitter_description', strip_tags($fiche->short_description))
@if($fiche->image)
    @section('twitter_image', $fiche->image)
@endif

@section('content')

<!-- Section Hero -->
<section>
    <div class="nataswim-titre1 position-relative  text-white">
        <div class="container-lg">
            <h1 class="text-white">{{ $fiche->title }}</h1>
            
            @if($fiche->image)
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8">
                    <img src="{{ $fiche->image }}" 
                         alt="{{ $fiche->title }}" 
                         class="img-fluid rounded-lg shadow-aqua">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<article class="py-5 bg-white">
    <div class="container-lg">
        <div class="row justify-content-center">
            <div class="col-lg">
                
                <!-- Métadonnées -->
                <div class="card-aqua mb-4">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        @if($fiche->is_featured)
                        <span class="badge badge-warning px-3 py-2">
                            <i class="fas fa-star me-1"></i>En vedette
                        </span>
                        @endif
                        
                        @if($fiche->visibility === 'authenticated')
                        <span class="badge badge-info px-3 py-2">
                            <i class="fas fa-lock me-1"></i>Membres
                        </span>
                        @endif
                        
                        <span class="d-flex align-items-center text-muted">
                            <i class="fas fa-eye me-2"></i>
                            {{ number_format($fiche->views_count ?? 0) }} vue{{ $fiche->views_count > 1 ? 's' : '' }}
                        </span>
                        
                        <span class="d-flex align-items-center text-muted">
                            <i class="fas fa-calendar me-2"></i>
                            {{ $fiche->published_at?->format('d M Y') ?? $fiche->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <!-- Description courte -->
                @if($fiche->short_description)
                <div class="card-aqua mb-4">
                    <div class="alert alert-info border-0 mb-0 bg-info-lighter">
                        <div class="content-display">
                            {!! $fiche->short_description !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Description longue -->
                @if($fiche->long_description)
                <div class="card-aqua mb-4">
                    @if($fiche->canViewContent(auth()->user()))
                    <div class="content-display">
                        {!! $fiche->long_description !!}
                    </div>
                    @else
                    <div class="alert alert-warning border-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-lock text-warning fs-2"></i>
                            </div>
                            <div class="col">
                                <h5 class="alert-heading mb-2">
                                    <i class="fas fa-crown me-1"></i>
                                    Fiche réservée aux membres Premium
                                </h5>
                                <p class="mb-3">
                                    {{ $fiche->getAccessMessage(auth()->user()) }}
                                </p>
                                @if(!auth()->check())
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('register') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-user-plus me-2"></i>Inscription
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                    </a>
                                </div>
                                @elseif(auth()->user()->hasRole('visitor'))
                                <a href="{{ route('payments.index') }}" 
                                   class="btn btn-warning">
                                    <i class="fas fa-crown me-2"></i>Devenir Premium
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Informations -->
                <div class="card-aqua mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-primary-lighter rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-folder me-2"></i>Catégorie
                                    </span>
                                    <strong class="text-primary">{{ $category->name }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-info-lighter rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-calendar me-2"></i>Publié le
                                    </span>
                                    <strong class="text-info">{{ $fiche->published_at?->format('d F Y') ?? $fiche->created_at->format('d F Y') }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-success-lighter rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-eye me-2"></i>Vues
                                    </span>
                                    <strong class="text-success">{{ number_format($fiche->views_count ?? 0) }}</strong>
                                </div>
                            </div>
                        </div>
                        @if($fiche->creator)
                        <div class="col-md-6">
                            <div class="p-3 bg-warning-lighter rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="fas fa-user me-2"></i>Auteur
                                    </span>
                                    <strong class="text-warning">{{ $fiche->creator->name }}</strong>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Navigation -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card-aqua h-100 hover-lift">
                            <div class="bg-primary-lighter p-3 rounded-top mb-3">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-folder me-2"></i>Catégorie
                                </h5>
                            </div>
                            <a href="{{ route('public.fiches.category', $category) }}" 
                               class="d-flex align-items-center text-decoration-none">
                                @if($category->image)
                                <img src="{{ $category->image }}" 
                                     class="rounded me-3" 
                                     style="width: 70px; height: 70px; object-fit: cover;"
                                     alt="{{ $category->name }}">
                                @else
                                <div class="bg-primary-lighter rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 70px; height: 70px;">
                                    <i class="fas fa-folder text-primary fs-3"></i>
                                </div>
                                @endif
                                <div>
                                    <h6 class="mb-1 text-dark">{{ $category->name }}</h6>
                                    <small class="text-muted">Voir toutes les fiches</small>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-aqua h-100">
                            <div class="d-grid gap-2">
                                <a href="{{ route('public.fiches.category', $category) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à {!! Str::limit($category->name, 30) !!}
                                </a>
                                <a href="{{ route('public.fiches.index') }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-th me-2"></i>Toutes les catégories
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</article>

@endsection

@push('styles')
<style>
/* Styles pour le contenu WYSIWYG */
.content-display h1,
.content-display h2,
.content-display h3 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
    line-height: 1.3;
}

.content-display h1 { font-size: 1.7rem; color: #38859b; }
.content-display h2 { font-size: 1.5rem; color: #49aaca; }
.content-display h3 { font-size: 1.3rem; color: #4fa79c; }

.content-display p {
    margin-bottom: 1.5rem;
    line-height: 1.8;
    text-align: justify;
    color: #4a5568;
}

.content-display ul,
.content-display ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
    line-height: 1.7;
}

.content-display li {
    margin-bottom: 0.5rem;
}

.content-display blockquote {
    border-left: 4px solid #38859b;
    padding: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    background: #e8f4f7;
    border-radius: 0.375rem;
    color: #2d3748;
}

.content-display img {
    max-width: 100%;
    height: auto;
    margin: 2rem 0;
    display: block;
    margin-left: auto;
    margin-right: auto;
    border-radius: 0.75rem;
}

.content-display pre {
    background: #303030;
    color: #e2e8f0;
    padding: 1.5rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 2rem 0;
    font-size: 0.875rem;
    line-height: 1.6;
}

.content-display code {
    background-color: #edf2f7;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
    color: #d63384;
    font-family: 'Courier New', monospace;
}

.content-display table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.content-display th,
.content-display td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.content-display th {
    background-color: #e8f4f7;
    font-weight: 600;
    color: #38859b;
}

.ql-video {
    width: 100%;
    display: block;
    margin: 15px auto;
    height: 480px;
    border-radius: 0.75rem;
}

@media (max-width: 768px) {
    .content-display {
        font-size: 0.95rem;
    }
    
    .ql-video {
        height: 300px;
    }
}
</style>
@endpush